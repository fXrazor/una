<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Profile's entries page.
 */
class BxPhotosPageAuthor extends BxBaseModTextPageAuthor
{
    protected $_sModule;
    protected $_oModule;
    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_photos';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);
    }

    //TODO: Continue from here!
    public function getCode()
    {
        $this->_oModule->_oTemplate->addJs(array('main.js'));

        return parent::getCode() . $this->_oModule->_oTemplate->getJsCode('main');
    }
}

/** @} */
