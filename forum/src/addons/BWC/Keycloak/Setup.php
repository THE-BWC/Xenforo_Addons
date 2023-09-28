<?php

namespace BWC\Keycloak;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Exception;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    /**
     * @throws Exception
     */
    public function installStep1(array $stepParams = [])
    {
        $db = $this->db();
        $db->query("REPLACE INTO `xf_connected_account_provider` (provider_id, provider_class, display_order, options)
        VALUES
            ('keycloak', 'BWC\\\Keycloak:Provider\\\\Keycloak', 80, '')");
    }

    /**
     * @throws Exception
     */
    public function uninstallStep1(array $stepParams = [])
    {
        $db = $this->db();
        $db->query("DELETE FROM `xf_connected_account_provider` WHERE provider_id = 'keycloak'");
    }

}