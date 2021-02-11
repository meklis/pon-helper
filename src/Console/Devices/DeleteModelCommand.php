<?php


namespace PonHelper\Console\Devices;

use DI\Annotation\Inject;
use InvalidArgumentException;
use PonHelper\Console\AbstractCommand;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteModelCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $modelStorage;

    /**
     * @var array
     */
    protected function configure()
    {
        $this->setName("device-model:delete")
            ->setDescription("Delete model device")
            ->addArgument("model_id", InputArgument::REQUIRED, "ID of device model for delete");
    }
    function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('model_id');
        if($model = $this->modelStorage->getById($id)) {
            if($this->confirm("WARNING!!!   This will remove all devices using this model!
            
Are you sure to delete model {$model->getName()} with ID {$id}?")) {
                $this->modelStorage->delete($model);
                $output->writeln("Device success deleted!");
            }
        } else {
            throw new InvalidArgumentException("Model with ID $id not found");
        }

        return self::SUCCESS;
    }
}