<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Organizations profiles module.
 */
class BxOrgsModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;

        $this->_aSearchableNamesExcept[] = $CNF['FIELD_AUTHOR'];
        $this->_aSearchableNamesExcept[] = $CNF['FIELD_JOIN_CONFIRMATION'];
    }

	/**
     * Check if this module entry can be used as profile
     */
    public function serviceActAsProfile ()
    {
        return true;
    }

    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit_with_cover.html';

        return parent::serviceGetSearchResultUnit($iContentId, $sUnitTemplate);
    }

    /**
     * @see BxBaseModProfileModule::serviceGetSpaceTitle
     */ 
    public function serviceGetSpaceTitle()
    {
		$aExcludeModules = explode(',', getParam('sys_hide_post_to_context_for_privacy'));
		if (in_array($this->_aModule['name'], $aExcludeModules))
              return BxBaseModProfileModule::serviceGetSpaceTitle();
		else
			return _t($this->_oConfig->CNF['T']['txt_sample_single']);
    }
    
    /**
     * @see iBxDolProfileService::serviceGetParticipatingProfiles
     */ 
    public function serviceGetParticipatingProfiles($iProfileId, $aConnectionObjects = false)
    {
		$aExcludeModules = explode(',', getParam('sys_hide_post_to_context_for_privacy'));
        if (false === $aConnectionObjects){
			if (in_array($this->_aModule['name'], $aExcludeModules)){
				$aConnectionObjects = array('sys_profiles_friends');
            }
            else{
                $aConnectionObjects = array('sys_profiles_subscriptions');
                if (isset($this->_oConfig->CNF['OBJECT_CONNECTIONS'])){
                    $aConnectionObjects = array($this->_oConfig->CNF['OBJECT_CONNECTIONS'], 'sys_profiles_subscriptions');
                }
            }
        } 
        return BxBaseModProfileModule::serviceGetParticipatingProfiles($iProfileId, $aConnectionObjects);
    }
    
    public function servicePrepareFields ($aFieldsProfile)
    {
        return parent::_servicePrepareFields($aFieldsProfile, array('org_cat' => 35), array(
            'org_name' => 'name',
            'org_desc' => 'description',
        ));
    }

    public function serviceGetTimelineData()
    {
    	return BxBaseModProfileModule::serviceGetTimelineData();
    }

    public function onFanRemovedFromAdmins($iGroupProfileId, $iProfileId)
    {
        if (!($oProfile = BxDolProfile::getInstance($iProfileId)))
            return false;
        $oAccount = $oProfile->getAccountObject();
        $oAccount->updateProfileContextAuto();
    }

    public function checkAllowedCompose (&$aDataEntry, $isPerformAction = false)
    {
        return BxBaseModProfileModule::checkAllowedCompose ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedContact($aDataEntry, $isPerformAction = false)
    {
        return BxBaseModProfileModule::checkAllowedContact($aDataEntry, $isPerformAction);
    }
}

/** @} */
