<?php


namespace PonHelper\Console\Cron;

use DI\Annotation\Inject;
use Monolog\Logger;
use PonHelper\Console\AbstractCommand;
use PonHelper\DeviceCore\CoreInit;
use PonHelper\Models\Devices\DeviceAccess;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use PonHelper\Storage\Devices\DeviceStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetOntsCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var CoreInit
     */
    protected $coreInit;

    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $deviceStorage;

    /**
     * @Inject
     * @var Logger
     */
    protected $logger;
    /**
     * @var array
     */
    protected function configure()
    {
        $this->logger = $this->logger->withName('cron');
        $this->setName("cron:update-ont-info")
            ->setDescription("Update ont database");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $devices = $this->deviceStorage->fetchAll();
        foreach ($devices as $dev) {
            $core = $this->coreInit->getCore($dev);
            $output->writeln(json_encode($core->getDeviceMetaData(), JSON_PRETTY_PRINT));
        }
        return self::SUCCESS;
    }
}