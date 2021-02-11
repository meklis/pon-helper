<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Models\User\User;
use PonHelper\Storage\UserGroupStorage;
use PonHelper\Storage\UserStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddUserCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var UserStorage
     */
    protected $storage;

    /**
     * @Inject
     * @var UserGroupStorage
     */
    protected $groups;

    protected function configure()
    {
        $this->setName("user:add")
            ->setDescription("Create new user");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("User adding, please fill form");
        $output->writeln("-------------------------------------");
        $user = new User();
        $user->setLogin($this->question("*Login", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Login can't be empty");
            return $answer;
        }));
        $user->setPassword(sha1($this->question("*Password", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Password can't be empty");
            return $answer;
        })));
        $user->setName($this->question("*Name", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Name can't be empty");
            return $answer;
        }));
        $groups = $this->groups->fetchAll();
        $user->setGroup($this->choiseQuestion("User group", $groups));
        $this->storage->add($user);
        $output->writeln("User success created");
        $output->writeln("-------------------------------------");
        $output->writeln($this->toJson($user->getAsArray()));
        $output->writeln("-------------------------------------");
        return self::SUCCESS;
    }
}