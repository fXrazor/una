<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 *
 * @{
 */

class BxBaseMenuCustom extends BxTemplMenuMoreAuto
{
    protected static $_sTmplContent;
    protected static $_sTmplContentItem;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty(self::$_sTmplContent))
            self::$_sTmplContent = $this->_oTemplate->getHtml($aObject['template']);

        if(empty(self::$_sTmplContentItem))
            self::$_sTmplContentItem = $this->_oTemplate->getHtml('menu_custom_item.html');

        $this->_sTmplNameItemMore = 'menu_custom_item_more.html';
    }

    protected function _getCode($sTmplName, $aTmplVars)
    {
        if($sTmplName != $this->_aObject['template'])
            return parent::_getCode($sTmplName, $aTmplVars);

        return $this->_oTemplate->parseHtmlByContent(self::$_sTmplContent, $aTmplVars);
    }

    protected function _getMenuItem ($aItem)
    {
    	if (isset($aItem['active']) && !$aItem['active'])
            return false;

        if (isset($aItem['visible_for_levels']) && !$this->_isVisible($aItem))
            return false;

    	$sMethod = '_getMenuItem' . str_replace(' ', '', ucwords(str_replace('-', ' ', $aItem['name'])));

    	if(!method_exists($this, $sMethod)) {
            $aItem = parent::_getMenuItem($aItem);
            if($aItem === false)
                return false;

            $mixedItem = $this->_getMenuItemDefault($aItem);
    	}
    	else
            $mixedItem = $this->$sMethod($aItem);

    	if(empty($mixedItem))
            return false;

        $sItem = $sClass = '';
        if(is_array($mixedItem)) 
            list($sItem, $sClass) = $mixedItem;
        else
            $sItem = $mixedItem;

        if(!empty($sClass))
            $sClass = ' ' . $sClass;
        if($this->_isSelected($aItem))
            $sClass .= ' bx-menu-tab-active';

        return array(
            'name' => $aItem['name'],
            'class' => $sClass,
            'item' => $sItem
        );
    }

    protected function _getMenuItemMoreAuto ($aItem)
    {
        $aItem['onclick'] = $this->_sJsObjectMoreAuto . '.more(this);';

        $aItem = parent::_getMenuItem($aItem);
        if($aItem === false)
            return false;

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItemMore(), array(
            'item' => $this->_getMenuItemDefault($aItem),
            'popup' => BxTemplFunctions::getInstance()->transBox($this->_aHtmlIds['more_auto_popup'], $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItemMorePopup(), array(
                'content' => ''
            )), true)
        ));
    }

    protected function _getMenuItemDefault ($aItem)
    {
        if(!isset($aItem['class_wrp']))
            $aItem['class_wrp'] = '';

        if(!isset($aItem['class_link']))
            $aItem['class_link'] = '';

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentItem(), $aItem);
    }

    protected function _getTmplContentItem()
    {
        return self::$_sTmplContentItem;
    }
}

/** @} */
