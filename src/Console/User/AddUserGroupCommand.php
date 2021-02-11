<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\Permissions;
use PonHelper\Models\User\UserGroup;
use PonHelper\Storage\UserGroupStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddUserGroupCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var UserGroupStorage
     */
    protected $storage;

    /**
     * @Inject
     * @var Permissions
     */
    protected $permissions;

    protected function configure()
    {
        $this->setName("user-group:add")
            ->setDescription("Create new user group");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("User group adding, please fill form");
        $output->writeln("-------------------------------------");
        $group = new UserGroup();
        $group->setName($this->question("*Name (displayed)", '', function ($answer) {
            if (!$answer) throw new RuntimeException("Name can't be empty");
            return $answer;
        }));
        $group->setDisplay($this->confirm("*Display group", true));


        $perms = $this->permissions->getPermissions();
        if($this->confirm("Are you want add permissions", true)) {
            $permissions = $this->choiseQuestion("Choose rules", $perms, $perms, true);
            $rules = array_map(function ($e) {
                return $e->getKey();
            },$permissions);
            $group->setPermissions($rules);
        }
        $this->storage->add($group);
        $output->writeln("Group success created");
        $output->writeln("-------------------------------------");
        $output->writeln($this->toJson($group->getAsArray()));
        $output->writeln("-------------------------------------");
        return self::SUCCESS;
    }

}