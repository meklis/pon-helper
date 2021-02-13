<?php


namespace PonHelper\Console\Devices;


use PonHelper\App;
use PonHelper\Console\AbstractCommand;
use PonHelper\Infrastructure\ImgHelper;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeIconModelCommand extends AbstractCommand
{

    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected function configure()
    {
        $this->setName("device-model:change-icon")
            ->setDescription("Update icon model")
            ->addArgument("id", InputArgument::REQUIRED, "Model ID for update");
    }
    function execute(InputInterface $input, OutputInterface $output)
    {
        $model = $this->storage->getById($input->getArgument('id'));
        $model->setIcon($this->question("Icon URL:", '', function ($answer) use ($model) {
            if (!$answer) return  $answer;
            $image = ImgHelper::loadImage($answer);
            $path = App::getInstance()->conf('icons.upload_dir');
            $format = explode("/",ImgHelper::getMimeType($image));
            if(count($format) < 2) {
                throw new \Exception("Error detect mime type or not supported format");
            }
            $name = "{$model->getId()}.{$format[1]}";
            $path .= "/$name";
            ImgHelper::saveBinary($image, $path);
            return $name;
        }));
        $output->writeln("Image success updated");
        $this->storage->update($model);
        return self::SUCCESS;
    }
}