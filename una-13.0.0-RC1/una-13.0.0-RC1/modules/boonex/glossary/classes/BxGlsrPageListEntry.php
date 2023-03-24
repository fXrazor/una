<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * List entry page
 */
class BxGlsrPageListEntry extends BxBaseModTextPageListEntry
{    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_glossary';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
