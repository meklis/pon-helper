<?php


namespace PonHelper\Console\User;


use DI\Annotation\Inject;
use PonHelper\Console\AbstractCommand;
use PonHelper\Controllers\Permissions;
use PonHelper\Storage\UserGroupStorage;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListUserGroupCommand extends AbstractCommand
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
        $this->setName("user-group:list")
            ->setDescription("Edit user group")
            ->addOption("output", "o", InputOption::VALUE_OPTIONAL, "Output format. support: table, yaml, json", "table");
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $list = $this->storage->fetchAll();

        switch ($input->getOption('output')) {
            case 'table':
                $table = new Table($output);
                $table->setHeaders([
                    'ID',
                    'Name',
                    'Display',
                    'Permissions',
                ]);
                foreach ($list as $l) {
                    $perms = join(", ", $l->getPermissions());
                    $params = trim($perms, ", ");
                    $table->addRow([
                        $l->getId(),
                        $l->getName(),
                        $l->isDisplay() ? "Yes" : "No",
                        $params
                    ]);
                }
                $table->render();
                break;
            case 'json':
                $list = array_map(function ($e) {
                    return $e->getAsArray();
                }, $list);
                $output->writeln($this->toJson($list));
                break;
            case 'yaml':
                $list = array_map(function ($e) {
                    return $e->getAsArray();
                }, $list);
                $output->writeln($this->toYaml($list));
                break;
        }
        return self::SUCCESS;
    }

}