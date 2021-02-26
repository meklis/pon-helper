<?php


namespace PonHelper\Console\System;


use DI\Annotation\Inject;
use PonHelper\App;
use PonHelper\Console\AbstractCommand;
use PonHelper\Storage\UserAuthKeyStorage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecalcActiveSessions extends AbstractCommand
{
    /**
     * @Inject
     * @var UserAuthKeyStorage
     */
    protected $app;

    protected function configure()
    {
        $this->setName("system:recalc-sessions")
            ->setDescription("Recalculate active user sessions")
        ;

    }
    function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $this->app->recalcAllSessionStatus();
        $this->output->writeln("Recalculated. Count affected keys = {$count}");
        return self::SUCCESS;
    }
}