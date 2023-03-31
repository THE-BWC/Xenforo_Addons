<?php

namespace src\addons\Patrick\ApiExtension;

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
        $this->schemaManager()->createTable('ApiExtension_api_login_token', function(Create $table)
        {
            $table->addColumn('login_token_id', 'int', 10)->primaryKey()->autoIncrement();
            $table->addColumn('login_token', 'varbinary', 32);
            $table->addUniqueKey('login_token');
            $table->addColumn('user_id', 'int');
            $table->addColumn('expiry_date', 'int');
            $table->addColumn('limit_ip', 'varbinary', 16)->nullable(true)->setDefault('null');
        });
    }
}