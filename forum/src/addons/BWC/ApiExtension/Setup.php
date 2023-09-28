<?php

namespace BWC\ApiExtension;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->schemaManager()->createTable('bwc_api_login_token', function(Create $table)
        {
            $table->addColumn('login_token_id', 'int')->autoIncrement();
            $table->addColumn('login_token', 'varchar', 32);
            $table->addColumn('user_id', 'int');
            $table->addColumn('expiry_date', 'int');
            $table->addColumn('limit_ip', 'varbinary', 16)->nullable();

            $table->addPrimaryKey('login_token_id');
            $table->addUniqueKey('login_token');
            $table->addKey('user_id');
            $table->addKey('expiry_date');
        });
    }

    public function uninstallStep1()
    {
        $this->schemaManager()->dropTable('bwc_api_login_token');
    }
}