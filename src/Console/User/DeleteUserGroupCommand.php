<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use InvalidArgumentException;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\Permissions;
use PonHelper\Storage\UserGroupStorage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUserGroupCommand extends AbstractCommand
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
        $this->setName("user-group:delete")
            ->addArgument("id", InputArgument::REQUIRED, "User group ID")
            ->setDescription("Delete user group");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        if($group = $this->storage->getById($id)) {
            if($this->confirm("WARNING!!!   This will remove all users with current group!
            
Are you sure to delete group {$group->getName()} with ID {$id}?")) {
                $this->storage->delete($group);
                $output->writeln("Group success deleted!");
            }
        } else {
            throw new InvalidArgumentException("Group with ID $id not found");
        }
        return self::SUCCESS;
    }

}