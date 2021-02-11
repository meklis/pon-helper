<?php


namespace PonHelper\Console\Devices;

use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Storage\Devices\DeviceStorage;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListDeviceCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $storage;


    /**
     * @var array
     */
    protected function configure()
    {
        $this->setName("device:list")
            ->setDescription("Table of devices")
            ->addOption("output", "o", InputOption::VALUE_OPTIONAL, "Output format. support: table, yaml, json", "table");
    }


    function execute(InputInterface $input, OutputInterface $output)
    {
        $devices = $this->storage->fetchAll();
        switch ($input->getOption('output')) {
            case 'table':
                $table = new Table($output);
                $table->setHeaders([
                    'Id',
                    'IP',
                    'Name',
                    'Description',
                    'Access',
                    'Model',
                    'MAC',
                    'Serial',
                    'Created At',
                    'Updated At',
                    'Parameters'
                ]);
                foreach ($devices as $device) {
                    $params = '';
                    if($device->getParams()) foreach ($device->getParams() as $key => $val) {
                        $params .= "$key={$val['value']}, ";
                    }
                    $params = trim($params, ", ");
                    $table->addRow([
                        $device->getId(),
                        $device->getIp(),
                        $device->getName(),
                        $device->getDescription(),
                        $device->getAccess(),
                        $device->getModel(),
                        $device->getMac(),
                        $device->getSerial(),
                        $device->getCreatedAt(),
                        $device->getUpdatedAt(),
                        $params
                    ]);
                }
                $table->render();
                break;
            case 'json':
                $devices = array_map(function ($e) {
                    return $e->getAsArray();
                }, $devices);
                $output->writeln($this->toJson($devices));
                break;
            case 'yaml':
                $devices = array_map(function ($e) {
                    return $e->getAsArray();
                }, $devices);
                $output->writeln($this->toYaml($devices));
                break;
        }
        return self::SUCCESS;
    }
}