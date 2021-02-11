<?php


namespace PonHelper\Storage;


use Exception;
use InvalidArgumentException;
use PDO;
use PonHelper\App;
use DI\Annotation\Inject;
use PonHelper\Storage\Exceptions\RecordNotFoundException;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * Class AbstractStorage
 * @package PonHelper\Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * @Inject
     * @var App
     */
    protected $app;

    /**
     * @Inject
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var PDO
     */
    protected $pdo;

    protected $tableName;


    private function getPropertiesDoc($model)
    {
        $reflect = new ReflectionClass($model);
        $properties = $reflect->getProperties();
        $props = [];
        foreach ($properties as $property) {
            $doc = $property->getDocComment();
            if ($doc && preg_match_all('/\@morm\.(.*)?=(.*)/', $doc, $matches)) {
                if (!isset($matches[2])) continue;
                foreach ($matches[1] as $key => $name) {
                    $value = $matches[2][$key];
                    $props[$property->getName()][$name] = $value;
                }
            } elseif ($doc && preg_match('/\@morm/', $doc, $matches)) {
                $props[$property->getName()]['name'] = $property->getName();
            }
        }
        return $props;
    }

    protected function getSQLFields($object)
    {
        $fields = $this->getPropertiesDoc($object);
        $return = [];
        foreach ($fields as $fieldName => $fieldVal) {
            if (isset($fieldVal['name'])) {
                $return[] = $fieldVal['name'];
            }
        }
        return $return;
    }

    protected function getPropertyBySQLname($object, $propertyName)
    {
        $fields = $this->getPropertiesDoc($object);
        foreach ($fields as $fieldName => $fieldVal) {
            if (isset($fieldVal['name']) && $fieldVal['name'] === $propertyName) {
                return $fieldName;
            }
        }
        return null;
    }

    protected function getObjectById($object, $id)
    {
        $obj = new $object;
        $obj->id = $id;
        return $this->fill($obj);
    }

    /**
     * Fill object by array
     *
     * @param $object
     * @param array $fetchArr
     */
    protected function fillByArr($object, $fetchArr = [])
    {
        $propsAssoc = [];
        foreach ($this->getPropertiesDoc($object) as $propName => $propData) {
            $propsAssoc[$propData['name']] = $propName;
        }
        foreach ($fetchArr as $k => $v) {
            if (isset($propsAssoc[$k])) {
                $k = $propsAssoc[$k];
            }
            //Detect json
            if ($this->isJson($v)) {
                $v = json_decode($v, JSON_PRETTY_PRINT);
            }
            $object->$k = $v;
        }
        return $object;
    }

    /**
     * Fill object by select from database
     *
     * @param $object
     * @return mixed
     * @throws Exception
     */
    public function fill($object)
    {
        $psth = null;
        $fields = $this->getSQLFields($object);
        $fields = array_map(function ($e) {
            return "`$e`";
        }, $fields);
        $selLine = join(',', $fields);
        if ($object->getId()) {
            $this->logger->debug("SELECT $selLine FROM {$this->tableName} WHERE id = ?", [$object->getId()]);
            $psth = $this->pdo->prepare("SELECT $selLine FROM {$this->tableName} WHERE id = ?");
            $psth->execute([$object->getId()]);
            if ($psth->rowCount() === 0) {
                $objName = get_class($object);
                throw (new RecordNotFoundException("Object {$objName} with table `{$this->tableName}` with id={$object->id} not found"))->setId($object->id)->setObjectName($objName);
            }
            $propsAssoc = [];
            foreach ($this->getPropertiesDoc($object) as $propName => $propData) {
                $propsAssoc[$propData['name']] = $propName;
            }
            foreach ($psth->fetch() as $k => $v) {
                if (isset($propsAssoc[$k])) {
                    $k = $propsAssoc[$k];
                }
                //Detect json
                if ($this->isJson($v)) {
                    $v = json_decode($v, JSON_PRETTY_PRINT);
                }
                $object->$k = $v;
            }
            return $object;
        }
        throw new InvalidArgumentException("Fill method for table $this->tableName require field id");
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function add($object)
    {
        $fields = [];
        $values = [];
        foreach ($this->getSQLFields($object) as $field) {
            $valueName = $this->getPropertyBySQLname($object, $field);
            $value = $object->$valueName;
            if (!$value) continue;
            if (is_array($value)) {
                $value = json_encode($value, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
            }
            if(is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            $values[$field] = $value;
            $fields[$field] = "`$field`";

        }
        ksort($fields);
        ksort($values);
        $values = array_values($values);
        $fields = array_values($fields);

        $mapper = trim(str_repeat("?,", count($fields)), ",");
        $selLine = join(',', $fields);
        $this->logger->debug("INSERT INTO {$this->tableName} ($selLine) VALUES ($mapper)", $values);
        $psth = $this->pdo->prepare("INSERT INTO {$this->tableName} ($selLine) VALUES ($mapper)");
        $psth->execute($values);
        $object->id = $this->pdo->lastInsertId();
        return $this->fill($object);
    }

    public function update($object)
    {
        $query = "UPDATE {$this->tableName} SET ";

        $values = [];
        foreach ($this->getSQLFields($object) as $field) {
            $valueName = $this->getPropertyBySQLname($object, $field);
            $value = $object->$valueName;
            if (is_array($value)) {
                $value = json_encode($value, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
            }
            if(is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            if (!$value) continue;
            $values[] = $value;
            $query .= "`$field` = ?, ";
        }
        $values[] = $object->getId();
        $query = trim($query, ", ");
        $query .= " WHERE id = ?";
        $this->logger->debug($query, $values);
        $psth = $this->pdo->prepare($query);
        $psth->execute($values);
        return $this->fill($object);
    }

    public function delete($object)
    {
        $this->logger->debug("DELETE FROM {$this->tableName} WHERE id = ?", [$object->getId()]);
        $psth = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE id = ?");
        $psth->execute([$object->getId()]);
        return $this;
    }

    protected function getOneIdByWhere($condition, $params = [])
    {
        $this->logger->debug("SELECT id FROM {$this->tableName} WHERE $condition", $params);
        $psth = $this->pdo->prepare("SELECT id FROM {$this->tableName} WHERE $condition");
        $psth->execute($params);
        if ($psth->rowCount() === 0) {
            return null;
        }
        return (int)$psth->fetch()['id'];
    }
}