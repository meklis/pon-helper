<?php


namespace PonHelper\Console\Devices;

use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Models\Devices\DeviceModel;
use PonHelper\Storage\Devices\DeviceModelStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AddModelCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $modelStorage;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected $question;

    /**
     * @var array
     */
    protected function configure()
    {
        $this->setName("device-model:add")
            ->setDescription("Add new device model");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $this->prepareForQuestions($input, $output);
        $output->writeln("Device model adding, please fill form");
        $output->writeln("-------------------------------------");

        $vendor = $this->question("Vendor", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Vendor can't be empty");
            return $answer;
        });
        $model = $this->question("Model", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Model can't be empty");
            return $answer;
        });
        $name = $this->question("Name (Default: {$vendor} {$model})", "{$vendor} {$model}", function ($answer) {
            if (!$answer) throw new RuntimeException("Name can't be empty");
            return $answer;
        });
        //Block adding props
        $deviceModel = new DeviceModel();
        $deviceModel->setVendor($vendor)->setModel($model)->setName($name);
        if ($this->question->ask($input, $output, new ConfirmationQuestion('Config extra properties (y/n, default: yes)?', true))) {
            $props = [];
            foreach ($deviceModel->getParams() as $key => $value) {
                $props[$key]['name'] = $value['name'];
                $props[$key]['value'] = $this->question($value['name'], $value['value']);
            }
            $deviceModel->setParams($props);
        }
        $this->modelStorage->add($deviceModel);
        $output->writeln("Model success added");
        $output->writeln("-------------------------------------");
        $output->writeln($this->toJson($deviceModel->getAsArray()));
        $output->writeln("-------------------------------------");
        return self::SUCCESS;
    }
}