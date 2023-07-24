<?php

namespace BWC\PostEditor;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->schemaManager()->alterTable('xf_post', function(Alter $table)
        {
            $table->addColumn('bwc_posteditor_last_edit_username', 'varchar', '50')
                ->setDefault(null)
                ->after('last_edit_user_id');
        });
    }

//    public function uninstallStep1()
//    {
//        $this->schemaManager()->alterTable('xf_post', function(Alter $table)
//        {
//            $table->dropColumns('bwc_posteditor_last_edit_username');
//        });
//    }
}