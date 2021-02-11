<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\Permissions;
use PonHelper\Storage\UserGroupStorage;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditUserGroupCommand extends AbstractCommand
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
        $this->setName("user-group:update")
            ->addArgument("id", InputArgument::REQUIRED, "User group ID")
            ->setDescription("Edit user group");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("User group editing");
        $output->writeln("-------------------------------------");
        $group = $this->storage->getById($input->getArgument('id'));
        $group->setName($this->question("*Name (Current: {$group->getName()})", $group->getName(), function ($answer) {
            if (!$answer) throw new RuntimeException("Name can't be empty");
            return $answer;
        }));
        $diplay = $group->isDisplay() ? "yes" : "no";
        $group->setDisplay($this->confirm("*Display group (Current: {$diplay})", $group->isDisplay()));

        $perms = $this->permissions->getPermissions();
        if($this->confirm("WARNING!!! Current permissions will be overwrited! Are you sure to change permission", true)) {
            $permissions = $this->choiseQuestion("Choose rules", $perms, $perms, true);
            $rules = array_map(function ($e) {
                return $e->getKey();
            },$permissions);
            $group->setPermissions($rules);
        }
        $this->storage->update($group);
        $output->writeln("Group success update");
        $output->writeln("-------------------------------------");
        $output->writeln($this->toJson($group->getAsArray()));
        $output->writeln("-------------------------------------");
        return self::SUCCESS;
    }

}