<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleConnect Google Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleConStudioPage extends BxTemplStudioModule
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = array(
            'settings' => array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            'help' => array('name' => 'help', 'icon' => 'question', 'title' => '_sys_connect_information'),
        );
    }
    
    function getHelp ()
    {
        $oModule = BxDolModule::getInstance('bx_googlecon'); 
        return _t('_bx_googlecon_information_block', BX_DOL_URL_ROOT . $oModule -> _oConfig -> getBaseUri() . 'handle');
    }
}

/** @} */
