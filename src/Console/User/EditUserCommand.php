<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Storage\UserGroupStorage;
use PonHelper\Storage\UserStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditUserCommand extends AbstractCommand
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
        $this->setName("user:update")
            ->addArgument("id", InputArgument::REQUIRED, "User ID")
            ->setDescription("Update user");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("User updating, please fill form");
        $output->writeln("-------------------------------------");
        $user = $this->storage->getById($input->getArgument('id'));
        $user->setLogin($this->question("*Login (Current: {$user->getLogin()})", $user->getLogin(), function ($answer) {
            if (!$answer) throw new RuntimeException("Login can't be empty");
            return $answer;
        }));
        $password = '';
        $password = $this->question("*Password (Current: <setted>)", $user->getPassword(), function ($answer) {
            if (!$answer) throw new RuntimeException("Password can't be empty");
            return $answer;
        });
        if ($password !== $user->getPassword()) {
            $user->setPassword(sha1(trim($password)));
        }
        $user->setName($this->question("*Name (Current: {$user->getName()})", $user->getName(), function ($answer) {
            if (!$answer) throw new RuntimeException("Name can't be empty");
            return $answer;
        }));
        $groups = $this->groups->fetchAll();
        $user->setGroup($this->choiseQuestion("User group (Current: {$user->getGroup()->getName()})", $groups, $user->getGroup()));
        $this->storage->update($user);
        $output->writeln("User success updated");
        $output->writeln("-------------------------------------");
        $output->writeln($this->toJson($user->getAsArray()));
        $output->writeln("-------------------------------------");
        return self::SUCCESS;
    }
}