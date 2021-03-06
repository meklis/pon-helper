<?php


namespace PonHelper\Console\SwitcherCore;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\SwitcherCore;
use PonHelper\Storage\Devices\DeviceStorage;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitcherCoreModulesCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var SwitcherCore
     */
    protected $core;
    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $devStorage;

    function configure()
    {
        $this->setName("switcher-core:modules")
            ->addArgument("ip", InputArgument::REQUIRED, "Device ip address");
        parent::configure(); // TODO: Change the autogenerated stub
    }
    function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->core->getCore($this->devStorage->getByIp($input->getArgument('ip')))->getModulesData();
        $table = new Table($output);
        $table->setHeaders([
           'Module name',
           'Called class',
           'Arguments',
        ]);
        foreach ($data as $d) {
            $arguments = '';
            foreach ($d['arguments'] as $argData) {
                $req = $argData['required'] ? "*" : "";
                $arguments .= "{$argData['name']}$req, pattern: /{$argData['pattern']}/\n";
            }
            $table->addRow([
                $d['name'],
                $d['class'],
                trim($arguments),
            ]);
        }
        $table->render();
        return self::SUCCESS;
    }
}