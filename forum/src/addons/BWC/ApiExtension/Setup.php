<?php

namespace BWC\ApiExtension;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    // 1.1.0 removed login tokens (auth/login-token): their table goes when upgrading from 1.0.0.
    public function upgrade1010070Step1()
    {
        $this->schemaManager()->dropTable('bwc_api_login_token');
    }

    public function uninstallStep1()
    {
        $this->schemaManager()->dropTable('bwc_api_login_token');
    }
}
