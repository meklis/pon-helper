<?php


namespace PonHelper\Console\Cron;

use DI\Annotation\Inject;
use Monolog\Logger;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\ActionLogger;
use PonHelper\DeviceCore\CoreInit;
use PonHelper\Models\Devices\DeviceInterface;
use PonHelper\Models\SystemAction;
use PonHelper\Storage\Devices\DeviceInterfaceStorage;
use PonHelper\Storage\Devices\DeviceStorage;
use SwitcherCore\Switcher\Core;
use SwitcherCore\Switcher\Objects\TelnetLazyConnect;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateInterfacesCommand extends AbstractCommand
{

    /**
     * @Inject
     * @var Logger
     */
    protected $logger;

    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $deviceStorage;

    /**
     * @var array
     */
    protected function configure()
    {
        $this->setName("cron:update-interface-data")
            ->setDescription("Update interface data");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger = $this->logger->withName('cron');
        foreach ($this->deviceStorage->fetchAll() as $device) {

        }
        return self::SUCCESS;
    }


}