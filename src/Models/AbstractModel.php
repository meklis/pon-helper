<?php


namespace PonHelper\Models;



use ReflectionClass;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @morm.name=id
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return  $this;
    }

    function __set($key, $value) {
        $this->$key = $value;
    }
    function __get($key) {
        return isset($this->$key) ? $this->$key : null;
    }

    function __toString()
    {
        return json_encode($this->getAsArray(), JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }
    private function getProperties($model)
    {
        $reflect = new ReflectionClass($model);
        $properties = $reflect->getProperties();
        $props = [];
        foreach ($properties as $property) {
            $doc = $property->getDocComment();
            $props[$property->getName()] = null;
            if ($doc && preg_match_all('/\@prop\.(.*)?=(.*)/', $doc, $matches)) {
                foreach ($matches[1] as $key => $name) {
                    $value = $matches[2][$key];
                    $props[$property->getName()][$name] = $value;
                }
            }
        }
        return $props;
    }
    function getAsArray($object = null, $displayAllProps = false) {
        $return = [];
        if($object === null) {
            $object = $this;
        }
        foreach ($this->getProperties($object) as $propName=>$propValues) {
            if(!$displayAllProps && isset($propValues['display']) && $propValues['display'] === 'no') {
                continue;
            }
            $displayedName = $propName;
            if(isset($propValues['name'])) {
                $displayedName = $propValues['name'];
            }
            if(is_object($this->$propName) && method_exists($this->$propName, 'getAsArray') ) {
                $return[$displayedName] = $this->$propName->getAsArray();
            } else {
                $return[$displayedName] = $this->$propName;
            }
        }
        return $return;
    }

    function __construct($id = null) {
        $this->id = $id;
    }
}