<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineTemplate extends BxBaseModNotificationsTemplate
{
    protected static $_aMemoryCacheItems;
    protected static $_aMemoryCacheItemsData;
    protected static $_sMemoryCacheItemsKeyMask;

    protected static $_sTmplContentItemItem;
    protected static $_sTmplContentItemOutline;
    protected static $_sTmplContentItemOutlineSample;
    protected static $_sTmplContentItemTimeline;
    protected static $_sTmplContentItemTimelineSample;
    protected static $_sTmplContentItemSearch;
    protected static $_sTmplContentTypePost;
    protected static $_sTmplContentTypeRepost;

    protected $_bShowTimelineDividers;
    protected $_aAclId2Name;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_bShowTimelineDividers = false;

        $this->_aAclId2Name = array();

        bx_import('BxTemplAcl');
        $aAclLevels = BxDolAcl::getInstance()->getMemberships(false, false, false);
        foreach($aAclLevels as $iAclId => $sAclName)
            $this->_aAclId2Name[$iAclId] = str_replace('_', '-', str_replace('_adm_prm_txt_level_', '', $sAclName));
    }

    public function init()
    {
        parent::init();

        self::$_aMemoryCacheItems = array();
        self::$_aMemoryCacheItemsData = array();
        self::$_sMemoryCacheItemsKeyMask = "%s_%d";
    }

    public function getAddedCss($sType = '', $bDynamic = false)
    {
        $aCss = array();
        switch($sType) {
            case 'view':
                $aCss = array(
                    BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css',
                    'cmts.css',
                    'view.css',
                    'view-media-tablet.css',
                    'view-media-desktop.css',
                    'repost.css',
                );

                if($this->_oConfig->isJumpTo())
                    $aCss[] = 'jquery-ui/jquery-ui.css';
                break;

            case 'post':
                if($this->_oConfig->isEmoji())
                    $aCss[] = BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'emoji/css/|emoji.css';

                $aCss[] = 'post.css';
                break;
        }

        $mixedResult = $this->addCss($aCss, $bDynamic);
        if($bDynamic)
            return $mixedResult; 
    }

    public function getAddedJs($sType = '', $bDynamic = false)
    {
        $aJs = array(
            'autosize.min.js',
            'jquery.anim.js',
            'main.js',
        );
        switch($sType) {
            case 'view':
                $aJs = array_merge($aJs, array(
                    'masonry.pkgd.min.js',
                    'flickity/flickity.pkgd.min.js',
                    'embedly-player.min.js',
                    'BxDolCmts.js',
                    'view.js',
                    'repost.js',
                ));

                if($this->_oConfig->isJumpTo())
                    $aJs = array_merge($aJs, array(
                        'jquery-ui/jquery.ui.core.min.js',
                        'jquery-ui/jquery.ui.datepicker.min.js',
                    ));
                    
                break;

            case 'post':
                $aJs = array_merge($aJs, array(
                    'jquery.form.min.js',
                    'post.js',
                ));
                break;
        }

        $mixedResult = $this->addJs($aJs, $bDynamic);
        if($bDynamic)
            return $mixedResult; 
    }

    public function getJsCodeView($aParams = array(), $bWrap = true, $bDynamic = false)
    {
        $aParams = array_merge(array(
            'bInfScroll' => $this->_oConfig->isInfiniteScroll(),
            'iInfScrollAutoPreloads' => $this->_oConfig->getAutoPreloads(),
        ), $aParams);

        return parent::getJsCode('view', $aParams, $bWrap, $bDynamic);
    }

    public function getJsCodePost($iOwnerId, $aParams = array(), $bWrap = true, $bDynamic = false)
    {
        return $this->getJsCode('post', array(
            'bEmoji' => $this->_oConfig->isEmoji(),
            'iLimitAttachLinks' => $this->_oConfig->getLimitAttachLinks(),
            'sLimitAttachLinksErr' => bx_js_string(_t('_bx_timeline_txt_err_attach_links')),
            'oAttachedLinks' => $this->_oDb->getLinksBy(array(
                'type' => 'unused', 
                'profile_id' => $this->getModule()->getUserId(), 
                'short' => true
            )),
            'sVideosAutoplay' => $this->_oConfig->getVideosAutoplay(),
            'oRequestParams' => array_merge(array(
                'type' => isset($aParams['type']) ? $aParams['type'] : BX_TIMELINE_TYPE_DEFAULT, 
                'owner_id' => $iOwnerId
            ), $aParams)
        ), $bWrap, $bDynamic);
    }

    public function getPostBlock($iOwnerId, $aParams = array())
    {
        $aForm = $this->getModule()->getFormPost($aParams);

        return $this->parseHtmlByName('block_post.html', array (
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('post'),
            'js_content' => $this->getJsCodePost($iOwnerId, $aParams),
            'form' => $aForm['form']
        ));
    }

    public function getViewsBlock($aParams)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aParams);
        
        $sType = isset($aParams['type']) ? $aParams['type'] : '';

        $sMenu = $this->_oConfig->getObject('menu_view');
        $oMenu = BxDolMenu::getObjectInstance($sMenu);

    	$aMenuItems = $oMenu->getMenuItems();
    	if(empty($aMenuItems) || !is_array($aMenuItems))
            return '';

    	if(empty($sType)) {
            $aMenuItem = array_shift($aMenuItems);
            $sType = $aMenuItem['name'];
    	}
    	$oMenu->setSelected($this->_oConfig->getName(), $sType);
        $oMenu->addMarkers(array(
            'js_object_view' => $sJsObject
        ));

        $sTitle = _t('_bx_timeline_page_block_title_views_' . $aParams['view']);

        return array(
            'content' => $this->parseHtmlByName('block_views.html', array(
                'style_prefix' => $sStylePrefix,
                'html_id' => $this->_oConfig->getHtmlIdView('views', $aParams, array('with_type' => false)),
                'html_id_content' => $this->_oConfig->getHtmlIdView('views_content', $aParams, array('with_type' => false)),
                'title' => $sTitle,
                'content' => $this->getViewBlock($aParams)
            )),
            'menu' => $oMenu
        );
    }

    public function getViewBlock($aParams)
    {
        $oModule = $this->getModule();

        list($sContent, $sLoadMore, $sBack, $sEmpty, $iEvent, $bEventsToLoad) = $this->getPosts($aParams);

        //--- Add live update
        $oModule->actionResumeLiveUpdate($aParams['type'], $aParams['owner_id']);

        $sModuleName = $oModule->getName();
        $sModuleMethod = !empty($aParams['get_live_updates']) ? $aParams['get_live_updates'] : 'get_live_update';
        $sService = BxDolService::getSerializedService($sModuleName, $sModuleMethod, array($aParams, $oModule->getUserId(), '{count}', '{init}'));

        $aLiveUpdateParams = array($this->_oConfig->getLiveUpdateKey($aParams), 1, $sService, true);
        if($sModuleMethod == 'get_live_update')
            $aLiveUpdateParams[] = $iEvent;

        $sLiveUpdateCode = null;
        bx_alert($sModuleName, 'add_live_update', 0, 0, array(
            'browse_params' => $aParams,
            'live_update_params' => &$aLiveUpdateParams,
            'override_result' => &$sLiveUpdateCode,
        ));

        if($sLiveUpdateCode === null)
            $sLiveUpdateCode = call_user_func_array(array(BxDolLiveUpdates::getInstance(), 'add'), $aLiveUpdateParams);
        //--- Add live update

        $sContentBefore = '';
        $sContentAfter = '';

        $sJsObject = $this->_oConfig->getJsObjectView($aParams);
        $sJsContent = $this->getJsCodeView(array(
            'sObjName' => $sJsObject,
            'sName' => $aParams['name'],
            'sView' => $aParams['view'],
            'sType' => $aParams['type'],
            'sVideosAutoplay' => $this->_oConfig->getVideosAutoplay(),
            'bEventsToLoad' => $bEventsToLoad,
            'oRequestParams' => $aParams
        ), array(
            'wrap' => true,
            'mask_markers' => array('object' => $sJsObject)
        )) . $this->getJsCode('repost');

        bx_alert($sModuleName, 'get_view', 0, 0, array(
            'params' => $aParams,
            'back' => &$sBack,
            'empty' => &$sEmpty,
            'content_before' => &$sContentBefore,
            'content' => &$sContent,
            'content_after' => &$sContentAfter,
            'load_more' => &$sLoadMore,
            'js_content' => &$sJsContent
        ));

        return $sLiveUpdateCode . $this->parseHtmlByName('block_view.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'html_id' => $this->_oConfig->getHtmlIdView('main', $aParams),
            'view' => $aParams['view'],
            'back' => $sBack,
            'empty' => $sEmpty,
            'content_before' => $sContentBefore,
            'content' => $sContent,
            'content_after' => $sContentAfter,
            'load_more' =>  $sLoadMore,
            'show_more' => $this->_getShowMore($aParams),
            'view_image_popup' => $this->_getImagePopup($aParams),
            'js_content' => $sJsContent
        ));
    }

    public function getSearchBlock($sContent)
    {
        $oModule = $this->getModule();
        $aParams = $oModule->getParams(BX_TIMELINE_VIEW_SEARCH);

        return $this->parseHtmlByName('block_search.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'html_id' => $this->_oConfig->getHtmlIdView('main', $aParams),
            'view' => $aParams['view'],
            'content' => $sContent,
            'view_image_popup' => $this->_getImagePopup($aParams),
            'js_content' => $this->getJsCodeView(array(
            	'oRequestParams' => $aParams
            ))
        ));
    }

    public function getItemBlock($mixedId, $aBrowseParams = array())
    {
        $CNF = $this->_oConfig->CNF;
        $oModule = $this->getModule();

        if(is_numeric($mixedId))
            $mixedId = $oModule->getItemData($mixedId);

        if(empty($mixedId) || !is_array($mixedId))
            return array('content' => MsgBox(_t('_Empty')), 'designbox_id' => 13);

        if($mixedId['code'] != 0)
            return array('content' => MsgBox($mixedId['content']), 'designbox_id' => 13);

        $aEvent = $mixedId['event'];
        $sContent = $mixedId['content'];

        if(!$this->_oConfig->isSystem($aEvent['type'], $aEvent['action'])) {
            $mixedViews = $oModule->getViewsData($aEvent['views']);
            if($mixedViews !== false) {
                list($sSystem, $iObjectId) = $mixedViews;
                $oModule->getViewObject($sSystem, $iObjectId)->doView();
            }
        }

        $sAuthorName = $oModule->getObjectUser($aEvent['object_owner_id'])->getDisplayName();

        $sTitle = $sAuthorName . ' ' . _t($aEvent['sample_action'], _t($aEvent['sample']));
        $sDescription = $aEvent['title'];
        if(get_mb_substr($sDescription, 0, 1) == '_')
            $sDescription = _t($sDescription);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageHeader(strip_tags($sTitle));
        $oTemplate->setPageDescription(strip_tags($sDescription));

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        if($oMetatags)
            $oMetatags->addPageMetaInfo($aEvent[$CNF['FIELD_ID']]);

        $sReferrer = '';
        if(isset($_SERVER['HTTP_REFERER']) && mb_stripos($_SERVER['HTTP_REFERER'], BX_DOL_URL_ROOT) === 0)
            $sReferrer = $_SERVER['HTTP_REFERER'];
        else 
            $sReferrer = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_HOME']);

        $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);
        return array('content' => $this->parseHtmlByName('block_item.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'html_id' => $this->_oConfig->getHtmlIdView('main', $aBrowseParams),
            'content' => $sContent,
            'show_more' => $this->_getShowMore($aBrowseParams),
            'view_image_popup' => $this->_getImagePopup($aBrowseParams),
            'js_content' => $this->getJsCodeView(array(
                'sObjName' => $sJsObject,
                'sVideosAutoplay' => $this->_oConfig->getVideosAutoplay(),
                'sReferrer' => $sReferrer,
                'oRequestParams' => $aBrowseParams
            ), array(
                'wrap' => true,
                'mask_markers' => array('object' => $sJsObject)
            )) . $this->getJsCode('repost')
        )));
    }

    /**
     * Get event's content.
     * @param integer $iId - event ID.
     * @param string $sMode - 'photo' is only one mode which is available for now.
     */
    public function getItemBlockContent($iId, $sMode) {
        $CNF = $this->_oConfig->CNF;
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aTmplVars = array(
        	'style_prefix' => $sStylePrefix,
            'bx_if:show_image' => array(
                'condition' => false,
                'content' => array()
            )
        );

        switch($sMode) {
            case 'photo':
                $aTmplVars['bx_if:show_image']['condition'] = true;
                $aTmplVars['bx_if:show_image']['content'] = array(
                    'style_prefix' => $sStylePrefix,
                    'src' => base64_decode(bx_process_input(bx_get('src'))),
                );
                break;
        }

        return $this->parseHtmlByName('block_item_content.html', $aTmplVars);
    }

    public function getItemBlockInfo($iId) {
        $CNF = $this->_oConfig->CNF;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aResult = $this->getDataCached($aEvent);
        if($aResult === false)
            return '';

        $sAuthorUnit = $this->getModule()->getObjectUser($aResult['object_owner_id'])->getUnit();

        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_post'), $this->_oConfig->getObject('form_display_post_view'), $this);
        $oForm->initChecker($aEvent);

        return $this->parseHtmlByName('block_item_info.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'author' => $sAuthorUnit,
            'fields' => $oForm->getCode()
        ));
    }

    public function getItemBlockComments($iId) {
        $CNF = &$this->_oConfig->CNF;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aResult = $this->getDataCached($aEvent);
        if($aResult === false)
            return '';

        return $this->parseHtmlByName('block_item_comments.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'content' => $this->_getComments($aResult['comments'])
        ));
                
    }

    public function getUnit(&$aEvent, $aBrowseParams = array())
    {
        $oModule = $this->getModule();

        if(empty($aBrowseParams) || !is_array($aBrowseParams))
            $aBrowseParams = $oModule->getParams(BX_TIMELINE_VIEW_SEARCH);

        return $this->getPost($aEvent, $aBrowseParams);
    }

    public function getPost(&$aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $iEventId = (int)$aEvent[$CNF['FIELD_ID']];

        $sMemoryCacheItemsKey = sprintf(self::$_sMemoryCacheItemsKeyMask, $aBrowseParams['view'], $iEventId);
        if(array_key_exists($sMemoryCacheItemsKey, self::$_aMemoryCacheItems)) {
            if(array_key_exists($sMemoryCacheItemsKey, self::$_aMemoryCacheItemsData))
                $aEvent = self::$_aMemoryCacheItemsData[$sMemoryCacheItemsKey];

            return self::$_aMemoryCacheItems[$sMemoryCacheItemsKey];
        }

        /**
         * Add all items in memory cache even if they are empty.
         */
        self::$_aMemoryCacheItems[$sMemoryCacheItemsKey] = '';
        self::$_aMemoryCacheItemsData[$sMemoryCacheItemsKey] = array();

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->getObject('privacy_view'));
        if($oPrivacy) {
            $oPrivacy->setTableFieldAuthor($this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? 'owner_id' : 'object_id');
            if(!$oPrivacy->check($iEventId)) {
                if($this->_oConfig->isCacheList())
                    $this->_oDb->deleteCache(array(
                        'type' => $aBrowseParams['type'], 
                        'profile_id' => bx_get_logged_profile_id(),
                        'event_id' => $iEventId
                    ));

                return '';
            }
        }

        $aResult = $this->getDataCached($aEvent, $aBrowseParams);
        if($aResult === false)
            return '';

        if(isset($aResult['owner_id']))
            $aEvent['owner_id'] = $aResult['owner_id'];

        $aEvent['object_owner_id'] = $aResult['object_owner_id'];
        $aEvent['icon'] = !empty($aResult['icon']) ? $aResult['icon'] : '';
        $aEvent['sample'] = !empty($aResult['sample']) ? $aResult['sample'] : '_bx_timeline_txt_sample';
        $aEvent['sample_action'] = !empty($aResult['sample_action']) ? $aResult['sample_action'] : '_bx_timeline_txt_added_sample';
        if(isset($aResult['sample_action_custom']))
            $aEvent['sample_action_custom'] = $aResult['sample_action_custom'];
        $aEvent['content'] = $aResult['content'];
        $aEvent['views'] = $aResult['views'];
        $aEvent['votes'] = $aResult['votes'];
        $aEvent['reactions'] = $aResult['reactions'];
        $aEvent['scores'] = $aResult['scores'];
        $aEvent['reports'] = $aResult['reports'];
        $aEvent['comments'] = $aResult['comments'];

        $sKey = 'allowed_view';
        $aEvent[$sKey] = $this->_preparePrivacy($sKey, $aEvent, $aResult);
        if(isset($aEvent[$sKey]) && $aEvent[$sKey] !== CHECK_ACTION_RESULT_ALLOWED) 
            return '';

        if($this->_oConfig->isCacheList())
            $this->_cacheEvent(bx_get_logged_profile_id(), $aEvent, $aBrowseParams);

        $sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        self::$_aMemoryCacheItems[$sMemoryCacheItemsKey] = $this->_getPost($sType, $aEvent, $aBrowseParams);
        self::$_aMemoryCacheItemsData[$sMemoryCacheItemsKey] = $aEvent;

        return self::$_aMemoryCacheItems[$sMemoryCacheItemsKey];
    }

    public function getPosts($aParams)
    {
        $bReturnArray = isset($aParams['return_data_type']) && $aParams['return_data_type'] == 'array';
        $bViewTimeline = $aParams['view'] == BX_TIMELINE_VIEW_TIMELINE;

        $iStart = $aParams['start'];
        $iPerPage = $aParams['per_page'];

        $aParamsDb = $aParams;

        //--- Before: Check for Previous
        $iDays = -1;
        $bPrevious = false;
        if($iStart - 1 >= 0) {
            $aParamsDb['start'] -= 1;
            $aParamsDb['per_page'] += 1;
            $bPrevious = true;
        }

        //--- Before: Check for Next
        $aParamsDb['per_page'] += 1;

        $aEvents = $this->_getPosts($aParamsDb);

        //--- After: Check for Previous
        if($bPrevious) {
            $aEvent = array_shift($aEvents);
            $iDays = (int)$aEvent['days'];
        }

        //--- After: Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $sContent = '';
        $sContent .= $this->getSizer($aParams);

        $iFirst = 0;
        $iEvents = count($aEvents);
        if($iEvents > 0)
            $iFirst = $this->_getFirst($aEvents, $aParams);
        else 
            $sContent .= $bViewTimeline ? $this->getDividerToday() : '';

        //--- Check for Visual Grouping
        $aGroups = array();
        foreach($aEvents as $iIndex => $aEvent) {
            $aContent = unserialize($aEvent['content']);
            if(!isset($aContent['timeline_group']))
                continue;

            $aGroup = $aContent['timeline_group'];
            $sGroup = $aGroup['by'];
            if(!isset($aGroups[$sGroup]))
               $aGroups[$sGroup] = array('field' => $aGroup['field'], 'indexes' => array(), 'processed' => false);

            $aGroups[$sGroup]['indexes'][] = $iIndex;
        }

        //--- Perform Visual Grouping
        foreach($aGroups as $sGroup => $aGroup) {
            if(empty($aGroup['field']) || empty($aGroup['indexes']))
                continue;

            switch($aGroup['field']) {
                case 'owner_id':
                    $aOwnerIds = array();
                    foreach($aGroup['indexes'] as $iIndex)
                        if(!in_array($aEvents[$iIndex]['owner_id'], $aOwnerIds))
                            $aOwnerIds[] = $aEvents[$iIndex]['owner_id'];

                    $iGroupIndex = (int)array_shift($aGroup['indexes']);
                    if(is_null($iGroupIndex))
                        break;

                    foreach($aGroup['indexes'] as $iIndex)
                        unset($aEvents[$iIndex]);

                    $aEvents[$iGroupIndex]['owner_id_grouped'] = $aOwnerIds;
                    break;
            }
        }

        $iExtenalsEvery = $this->_oConfig->getExtenalsEvery($aParams['type']);

        $bFirst = true;
        $iEventIndex = 0;
        $mixedEvents = $bReturnArray ? array() : '';
        foreach($aEvents as $aEvent) {
            $iEvent = (int)$aEvent['id'];

            $sEvent = $this->getPost($aEvent, $aParams);
            if(empty($sEvent))
                continue;

            if($bReturnArray) {
                $mixedEvents[] = $aEvent;
                continue;
            }

            if($bFirst && $bViewTimeline) {
                $mixedEvents .= $this->getDividerToday($aEvent);

                $bFirst = false;
            }

            $mixedEvents .= $bViewTimeline ? $this->getDivider($iDays, $aEvent) : '';
            $mixedEvents .= $sEvent;

            $iEventIndex++;
            if($iExtenalsEvery > 0 && $iEventIndex % $iExtenalsEvery == 0) {
                $sExternalPost = false;
                bx_alert($this->_oConfig->getName(), 'get_external_post', 0, 0, array(
                    'params' => $aParams,
                    'override_result' => &$sExternalPost,
                ));

                if($sExternalPost !== false)
                    $mixedEvents .= $sExternalPost;
            }
        }

        if($bReturnArray)
            return $mixedEvents;

        $sContent .= $mixedEvents;

        $bEvents = !empty($mixedEvents);

        $sBack = $this->getBack($aParams);

        $iPerPage = $this->_oConfig->getPerPage();
        $iPreloads = $this->_oConfig->getAutoPreloads();
        if(!$this->_oConfig->isInfiniteScroll() || (($aParams['start'] - $iPerPage * ($iPreloads - 1)) % ($iPerPage * $iPreloads) == 0))
            $sLoadMore = $this->getLoadMore($aParams, $bNext, $iEvents > 0 && $bEvents);
        else
            $sLoadMore = $this->getLoadMoreAuto($aParams, $bNext, $iEvents > 0 && $bEvents);

        $sEmpty = $this->getEmpty($iEvents <= 0 || !$bEvents);

        return array($sContent, $sLoadMore, $sBack, $sEmpty, $iFirst, $bNext);
    }

    public function getEmpty($bVisible)
    {
        return $this->parseHtmlByName('empty.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_timeline_txt_msg_no_results'))
        ));
    }

    public function getDivider(&$iDays, &$aEvent)
    {
        if(!$this->_bShowTimelineDividers || $iDays == $aEvent['days'])
            return '';

        $iDays = $aEvent['days'];
        $iDaysAgo = (int)$aEvent['ago_days'];
        if($aEvent['today'] == $aEvent['days'] || (($aEvent['today'] - $aEvent['days']) == 1 && $iDaysAgo == 0))
            return '';

        return $this->parseHtmlByName('divider.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'type' => 'common',
            'bx_if:show_hidden' => array(
                'condition' => false,
                'content' => array()
            ),
            'content' => bx_time_js($aEvent['date'])
        ));
    }

    public function getDividerToday($aEvent = array())
    {
        if(!$this->_bShowTimelineDividers)
            return '';

    	$bToday = !empty($aEvent) && ($aEvent['today'] == $aEvent['days'] || (($aEvent['today'] - $aEvent['days']) == 1 && (int)$aEvent['ago_days'] == 0));

        return $this->parseHtmlByName('divider.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'type' => 'today',
        	'bx_if:show_hidden' => array(
                'condition' => !$bToday,
                'content' => array()
            ),
            'content' => _t('_bx_timeline_txt_today')
        ));
    }

    public function getSizer($aParams)
    {
        if($aParams['view'] != BX_TIMELINE_VIEW_OUTLINE)
            return '';

        return $this->parseHtmlByName('sizer_' . $aParams['view'] . '.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style')
        ));
    }

    public function getBack($aParams)
    {
        $iYearSel = (int)$aParams['timeline'];
        if($iYearSel == 0)
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aParams);

        $iYearNow = date('Y', time());
        return $this->parseHtmlByName('back.html', array(
        	'style_prefix' => $sStylePrefix,
            'content' => $this->parseLink('javascript:void(0)', _t('_bx_timeline_txt_jump_to_recent'), array(
                'title' => _t('_bx_timeline_txt_jump_to_n_year', $iYearNow),
        		'onclick' => 'javascript:' . $sJsObject . '.changeTimeline(this, 0)'
            ))
        ));
    }

    public function getLoadMore($aParams, $bEnabled, $bVisible = true)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aParams);

        $iStart = $aParams['start'];
        $iPerPage = $aParams['per_page'];

        $aTmplVars = array(
            'style_prefix' => $sStylePrefix,
            'visible' => ($aParams['view'] == BX_TIMELINE_VIEW_TIMELINE && $bVisible) || ($aParams['view'] == BX_TIMELINE_VIEW_OUTLINE && $bEnabled && $bVisible) ? 'block' : 'none',
            'bx_if:is_disabled' => array(
                'condition' => !$bEnabled,
                'content' => array()
            ),
            'bx_if:show_on_click' => array(
                'condition' => $bEnabled,
                'content' => array(
                    'on_click' => 'javascript:' . $sJsObject . '.changePage(this, ' . ($iStart + $iPerPage) . ', ' . $iPerPage . ')'
                )
            ),
            'bx_if:show_jump_to' => array(
                'condition' => $this->_oConfig->isJumpTo(),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'content' => $this->getJumpTo($aParams)
                )
            )
        );
        return $this->parseHtmlByName('load_more.html', $aTmplVars);
    }

    public function getLoadMoreAuto($aParams, $bEnabled, $bVisible = true)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        return $this->parseHtmlByName('load_more_auto.html', array(
            'style_prefix' => $sStylePrefix,
            'visible' => ($aParams['view'] == BX_TIMELINE_VIEW_TIMELINE && $bVisible) || ($aParams['view'] == BX_TIMELINE_VIEW_OUTLINE && $bEnabled && $bVisible) ? 'block' : 'none',
            'loading' => _t('_bx_timeline_txt_loading' . ($bEnabled ? '' : '_complete'))
        ));
    }

    /**
     * Note. For now both List and Caledar based Jump To elements are available.
     * Calendar based element is used by default. List based one can be removed 
     * later if it won't be used completely.
     */
    public function getJumpTo($aParams)
    {
        if(!$this->_oConfig->isJumpTo())
            return '';

        $bList = false;
        $bDynamicMode = isset($aParams['dynamic_mode']) && (bool)$aParams['dynamic_mode'] === true;

        if($bList && !$bDynamicMode)
            return '';

        return $this->{'_getJumpTo' . ($bList ? 'List' : 'Caledar')}($aParams);
    }

    public function getComments($sSystem, $iId, $aBrowseParams = array())
    {
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $oCmts = $oModule->getCmtsObject($sSystem, $iId);
        if($oCmts === false)
            return '';

        $aCmtsBp = array();
        if(!empty($aBrowseParams['cmts_preload_number']))
            $aCmtsBp['per_view'] = $aBrowseParams['cmts_preload_number'];

        $aCmtsDp = array(
            'in_designbox' => false, 
            'dynamic_mode' => isset($aBrowseParams['dynamic_mode']) && $aBrowseParams['dynamic_mode'] === true
        );
        if(!empty($aBrowseParams['cmts_min_post_form']))
            $aCmtsDp['min_post_form'] = $aBrowseParams['cmts_min_post_form'];

        $aComments = $oCmts->getCommentsBlock($aCmtsBp, $aCmtsDp);
        if(empty($aComments) || !is_array($aComments))
            return '';

        return $this->parseHtmlByName('comments.html', array(
            'style_prefix' => $sStylePrefix,
            'id' => $iId,
            'content' => $aComments['content']
        ));
    }

    public function getRepostElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
        $aReposted = $this->_oDb->getReposted($sType, $sAction, $iObjectId);
        if(empty($aReposted) || !is_array($aReposted))
            return '';

		$oModule = $this->getModule();
		$bDisabled = $oModule->isAllowedRepost($aReposted) !== true || $this->_oDb->isReposted($aReposted['id'], $iOwnerId, $oModule->getUserId());
		if($bDisabled && (int)$aReposted['reposts'] == 0)
            return '';

		$sStylePrefix = $this->_oConfig->getPrefix('style');
        $sStylePrefixRepost = $sStylePrefix . '-repost-';

        $bDynamicMode = isset($aParams['dynamic_mode']) && $aParams['dynamic_mode'] === true;

        $bShowDoRepostAsButtonSmall = isset($aParams['show_do_repost_as_button_small']) && $aParams['show_do_repost_as_button_small'] == true;
        $bShowDoRepostAsButton = !$bShowDoRepostAsButtonSmall && isset($aParams['show_do_repost_as_button']) && $aParams['show_do_repost_as_button'] == true;

        $bShowDoRepostImage = isset($aParams['show_do_repost_image']) && $aParams['show_do_repost_image'] == true && !empty($aParams['image_do_repost']);
        $bShowDoRepostIcon = isset($aParams['show_do_repost_icon']) && $aParams['show_do_repost_icon'] == true && !empty($aParams['icon_do_repost']);
        $bShowDoRepostText = isset($aParams['show_do_repost_text']) && $aParams['show_do_repost_text'] == true && !empty($aParams['text_do_repost']);
        $bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true;

        //--- Do repost link ---//
        $sClass = $sStylePrefixRepost . 'do-repost';
        if($bShowDoRepostAsButton)
            $sClass .= ' bx-btn';
        else if($bShowDoRepostAsButtonSmall)
            $sClass .= ' bx-btn bx-btn-small';

        $sOnClick = '';
        if(!$bDisabled) {
            $sCommonPrefix = $this->_oConfig->getPrefix('common_post');
            if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_REPOST) {
                $aRepostedData = unserialize($aReposted['content']);

                $sOnClick = $this->_getRepostJsClick($iOwnerId, $aRepostedData['type'], $aRepostedData['action'], $aRepostedData['object_id']);
            }
            else
                $sOnClick = $this->_getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);
        }
        else
            $sClass .= $bShowDoRepostAsButton || $bShowDoRepostAsButtonSmall ? ' bx-btn-disabled' : ' ' . $sStylePrefixRepost . 'disabled';

        $aOnClickAttrs = array(
            'title' => _t('_bx_timeline_txt_do_repost')
        );
        if(!empty($sClass))
            $aOnClickAttrs['class'] = $sClass;
        if(!empty($sOnClick))
            $aOnClickAttrs['onclick'] = $sOnClick;

        //--- Do repost label ---//
        $sMethodDoRepostLabel = ''; 
        $sTemplateDoRepostLabel = '';
        if(!empty($aParams['template_do_repost_label'])) {
            $sMethodDoRepostLabel = 'parseHtmlByContent';
            $sTemplateDoRepostLabel = $aParams['template_do_repost_label'];
        }
        else {
            $sMethodDoRepostLabel = 'parseHtmlByName';
            $sTemplateDoRepostLabel = $aParams['template_do_repost_label_name'];
        }

        $sDoRepost = $this->$sMethodDoRepostLabel($sTemplateDoRepostLabel, array(
            'style_prefix' => $sStylePrefix,
            'bx_if:show_image' => array(
                'condition' => $bShowDoRepostImage,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'src' => $this->getIconUrl($aParams['image_do_repost'])
                )
            ),
            'bx_if:show_icon' => array(
                'condition' => $bShowDoRepostIcon,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'name' => $aParams['icon_do_repost']
                )
            ),
            'bx_if:show_text' => array(
                'condition' => $bShowDoRepostText,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'text' => _t($aParams['text_do_repost'])
                )
            )
        ));

        return $this->parseHtmlByName('repost_element_block.html', array(
            'style_prefix' => $sStylePrefix,
            'html_id' => $this->_oConfig->getHtmlIds('repost', 'main') . $aReposted['id'],
            'class' => ($bShowDoRepostAsButton ? $sStylePrefixRepost . 'button' : '') . ($bShowDoRepostAsButtonSmall ? $sStylePrefixRepost . 'button-small' : ''),
            'count' => $aReposted['reposts'],
            'do_repost' => $this->parseLink('javascript:void(0)', $sDoRepost, $aOnClickAttrs),
            'bx_if:show_counter' => array(
                'condition' => $bShowCounter,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_if:show_hidden' => array(
                        'condition' => (int)$aReposted['reposts'] == 0,
                        'content' => array()
                    ),
                    'counter' => $this->getRepostCounter($aReposted, $aParams)
                )
            ),
            'script' => $this->getRepostJsScript($bDynamicMode)
        ));
    }

    public function getRepostCounter($aEvent, $aParams = array())
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('repost');

        $bShowDoRepostAsButtonSmall = isset($aParams['show_do_repost_as_button_small']) && $aParams['show_do_repost_as_button_small'] == true;
        $bShowDoRepostAsButton = !$bShowDoRepostAsButtonSmall && isset($aParams['show_do_repost_as_button']) && $aParams['show_do_repost_as_button'] == true;

        $sClass = $sStylePrefix . '-repost-counter';
        if($bShowDoRepostAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoRepostAsButton)
            $sClass .= ' bx-btn-height';

        return $this->parseLink('javascript:void(0)', !empty($aEvent['reposts']) && (int)$aEvent['reposts'] > 0 ? $this->getRepostCounterLabel($aEvent['reposts']) : '', array(
            'id' => $this->_oConfig->getHtmlIds('repost', 'counter') . $aEvent['id'],
            'class' => $sClass,
            'title' => _t('_bx_timeline_txt_reposted_by'),
            'onclick' => 'javascript:' . $sJsObject . '.toggleByPopup(this, ' . $aEvent['id'] . ')'
        ));
    }

    public function getRepostCounterLabel($iCount)
    {
        return $iCount;
    }

    public function getRepostedBy($iId)
    {
        $aTmplUsers = array();
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aUserIds = $this->_oDb->getRepostedBy($iId);
        foreach($aUserIds as $iUserId)
            $aTmplUsers[] = array(
                'style_prefix' => $sStylePrefix,
                'user_unit' => $oModule->getObjectUser($iUserId)->getUnit()
            );

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->parseHtmlByName('repost_by_list.html', array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    public function getRepostJsScript($bDynamicMode = false)
    {
        $sCode = $this->getJsCode('repost', array(), array('mask' => '{object} = new {class}({params});', 'wrap' => false));

        if($bDynamicMode) {
            $sJsObject = $this->_oConfig->getJsObject('repost');

            $sCode = "var " . $sJsObject . " = null; 
            $.getScript('" . bx_js_string($this->getJsUrl('main.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                $.getScript('" . bx_js_string($this->getJsUrl('repost.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                    bx_get_style('" . bx_js_string($this->getCssUrl('repost.css'), BX_ESCAPE_STR_APOS) . "');
                    " . $sCode . "
                });
            }); ";
        }
        else {
            $sCode = "var " . $sCode;

            $this->addCss(array('repost.css'));
            $this->addJs(array('main.js', 'repost.js'));
        }

        return $this->_wrapInTagJsCode($sCode);
    }

    public function getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
        $aReposted = $this->_oDb->getReposted($sType, $sAction, $iObjectId);
        if(empty($aReposted) || !is_array($aReposted))
            return '';

        $sResult = '';
        $sCommonPrefix = $this->_oConfig->getPrefix('common_post');
        if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_REPOST) {
            $aRepostedData = unserialize($aReposted['content']);

            $sResult = $this->_getRepostJsClick($iOwnerId, $aRepostedData['type'], $aRepostedData['action'], $aRepostedData['object_id']);
        }
        else
            $sResult = $this->_getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);

        return $sResult;
    }

    public function getAttachLinkForm($iEventId = 0)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('post');

        $aForm = $this->getModule()->getFormAttachLink($iEventId);

        return $this->parseHtmlByName('attach_link_form.html', array(
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'form_id' => $aForm['form_id'],
            'form' => $aForm['form'],
        ));
    }

    public function getAttachLinkField($iUserId, $iEventId = 0)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        if(!$iEventId)
            $aLinks = $this->_oDb->getUnusedLinks($iUserId);
        else
            $aLinks = $this->_oDb->getLinks($iEventId);

        $sLinks = '';
        foreach($aLinks as $aLink)
            $sLinks .= $this->getAttachLinkItem($iUserId, $aLink);

        return $this->parseHtmlByName('attach_link_form_field.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('post', 'attach_link_form_field') . $iEventId,
            'style_prefix' => $sStylePrefix,
            'links' => $sLinks
        ));
    }

    public function getAttachLinkItem($iUserId, $mixedLink)
    {
        $aLink = is_array($mixedLink) ? $mixedLink : $this->_oDb->getLinksBy(array('type' => 'id', 'id' => (int)$mixedLink, 'profile_id' => $iUserId));
        if(empty($aLink) || !is_array($aLink))
            return '';

        $sLinkIdPrefix = $this->_oConfig->getHtmlIds('post', 'attach_link_item');
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sClass = $sStylePrefix . '-al-item';
        $sJsObject = $this->_oConfig->getJsObject('post');

        $oEmbed = BxDolEmbed::getObjectInstance();
        $bEmbed = $oEmbed !== false;

        $sThumbnail = '';
        $aLinkAttrs = array();
        if(!$bEmbed) {
            $aLinkAttrs = array(
            	'title' => bx_html_attribute($aLink['title'])
            );
            if(!$this->_oConfig->isEqualUrls(BX_DOL_URL_ROOT, $aLink['url'])) {
                $aLinkAttrs['target'] = '_blank';
    
                if($this->_oDb->getParam('sys_add_nofollow') == 'on')
            	    $aLinkAttrs['rel'] = 'nofollow';
            }

            if((int)$aLink['media_id'] != 0)
                $sThumbnail = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_preview'))->getFileUrl($aLink['media_id']);
        }
        else
            $sClass .= ' embed';

        return $this->parseHtmlByName('attach_link_item.html', array(
            'html_id' => $sLinkIdPrefix . $aLink['id'],
            'style_prefix' => $sStylePrefix,
            'class' => $sClass,
            'js_object' => $sJsObject,
            'id' => $aLink['id'],
            'bx_if:show_embed_outer' => array(
                'condition' => $bEmbed,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'embed' => $bEmbed ? $oEmbed->getLinkHTML($aLink['url'], $aLink['title'], 300) : '',
                )
            ),
            'bx_if:show_embed_inner' => array(
                'condition' => !$bEmbed,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_if:show_thumbnail' => array(
                        'condition' => !empty($sThumbnail),
                        'content' => array(
                            'style_prefix' => $sStylePrefix,
                            'thumbnail' => $sThumbnail
                        )
                    ),
                    'url' => $aLink['url'],
                    'link' => $this->parseLink($aLink['url'], $aLink['title'], $aLinkAttrs)
                )
            ),
        ));
    }

    public function getData(&$aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $this->_getSystemData($aEvent, $aBrowseParams) : $this->_getCommonData($aEvent, $aBrowseParams);
        if(empty($aResult) || empty($aResult['object_owner_id']) || empty($aResult['content']))
            return false;

        $sSample = !empty($aResult['sample']) ? $aResult['sample'] : '_bx_timeline_txt_sample';

        $aUpdate = array(
            'object_owner_id' => $aResult['object_owner_id']
        );

        //--- Update Title if empty.
        if(empty($aEvent['title'])) {
            $sTitle = !empty($aResult['title']) ? $this->_oConfig->getTitle($aResult['title']) : _t($sSample);

            $aUpdate['title'] = bx_process_input(strip_tags($sTitle));
        }

        //--- Update Description if empty.
        if(empty($aEvent['description'])) {
            $sUserName = $this->getModule()->getObjectUser($aResult['object_owner_id'])->getDisplayName();

            $sDescription = !empty($aResult['description']) ? $aResult['description'] : _t('_bx_timeline_txt_user_added_sample', $sUserName, _t($sSample));
            if($sDescription == '' && !empty($aResult['content']['text']))
                $sDescription = $aResult['content']['text'];

            $aUpdate['description'] = bx_process_input(strip_tags($sDescription));
        }

        if(!empty($aUpdate) && is_array($aUpdate)) 
            $this->_oDb->updateEvent($aUpdate, array('id' => $aEvent['id']));

        return $aResult;
    }

    public function getDataCached($aEvent, $aBrowseParams = array())
    {
        if(!$this->_oConfig->isCacheItem()) 
            return $this->getData($aEvent, $aBrowseParams);

        /**
         * For now parameters from $aBrowseParams array aren't used during data retrieving.
         * If they will then the cache should be created depending on their values.
         */
        $sCacheKey = $this->_oConfig->getCacheItemKey($aEvent['id']);
        $iCacheLifetime = $this->_oConfig->getCacheItemLifetime();

        $oCache = $this->getModule()->getCacheItemObject();
        $aCached = $oCache->getData($sCacheKey, $iCacheLifetime);
        if(!empty($aCached)) 
            return unserialize($aCached);

        $aBrowseParams['dynamic_mode'] = true;
        $aResult = $this->getData($aEvent, $aBrowseParams);
        if(!empty($aResult) && isset($aResult['_cache']) && (bool)$aResult['_cache'] === false)
            return $aResult;

        $oCache->setData($sCacheKey, serialize($aResult), $iCacheLifetime);           
        return $aResult;
    }

    public function getVideo($aEvent, $aVideo)
    {
        $sVideoId = $this->_oConfig->getHtmlIds('view', 'video') . $aEvent['id'] . '-' . $aVideo['id'];
        $oPlayer = BxDolPlayer::getObjectInstance();
        if (!$oPlayer)
            return '';

        $sPlayer = $oPlayer->getCodeVideo (BX_PLAYER_EMBED, array(
            'poster' => $aVideo['src_poster'],
            'mp4' => array('sd' => $aVideo['src_mp4'], 'hd' => $aVideo['src_mp4_hd']),
            'attrs' => array('id' => $sVideoId),
        ));
        return $this->parseHtmlByName('video_player.html', array(
            'player' => $sPlayer,
            'html_id' => $sVideoId
        ));
    }

    /*
     * Show only one Live Update notification for all new Events.
     */
    function getLiveUpdate($aBrowseParams, $iProfileId, $iCountOld = 0, $iCountNew = 0)
    {
        $oModule = $this->getModule();

    	$iCount = (int)$iCountNew - (int)$iCountOld;
    	if($iCount < 0)
            return '';

        $aParams = $oModule->getParamsExt($aBrowseParams);
        $aParams['start'] = 0;
        $aParams['per_page'] = 1;
        $aParams['newest'] = true;
        $aParams['filter'] = BX_TIMELINE_FILTER_OTHER_VIEWER;
        $aEvents = $this->_oDb->getEvents($aParams);
        if(empty($aEvents) || !is_array($aEvents))
            return '';

        $aEvent = array_shift($aEvents);
        if(empty($aEvent) || !is_array($aEvent))
            return '';

        $sJsObject = $this->_oConfig->getJsObjectView($aParams);
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        return $this->parseHtmlByName('live_update_button.html', array(
            'style_prefix' => $sStylePrefix,
            'html_id' => $this->_oConfig->getHtmlIds('view', 'live_update_popup') . $aBrowseParams['type'],
            'onclick_show' => "javascript:" . $sJsObject . ".goToBtn(this, 'timeline-event-" . $aEvent['id'] . "', '" . $aEvent['id'] . "');",
        ));
    }

    /*
     * Show Live Update notification separately for each new Event. Popup Chain is used here.
     * 
     * Note. This way to display live update notifications isn't used for now. 
     * See BxTimelineTemplate::getLiveUpdate method instead.
     */
    function getLiveUpdates($aBrowseParams, $iProfileId, $iCountOld = 0, $iCountNew = 0)
    {
        $bShowAll = true;
        $bShowActions = false;
        $oModule = $this->getModule();

    	$iCount = (int)$iCountNew - (int)$iCountOld;
    	if($iCount < 0)
            return '';

        $iCountMax = $this->_oConfig->getLiveUpdateLength();
        if($iCount > $iCountMax)
            $iCount = $iCountMax;

        $aParams = $oModule->getParamsExt($aBrowseParams);
        $aParams['start'] = 0;
        $aParams['per_page'] = $iCount;
        $aParams['filter'] = BX_TIMELINE_FILTER_OTHER_VIEWER;
        $aEvents = $this->_oDb->getEvents($aParams);
        if(empty($aEvents) || !is_array($aEvents))
            return '';

        $sJsObject = $this->_oConfig->getJsObjectView($aParams);
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $iUserId = $oModule->getUserId();
        $bModerator = $oModule->isModerator();

        $aEvents = array_reverse($aEvents);
        $iEvents = count($aEvents);

        $aTmplVarsItems = array();
        foreach($aEvents as $iIndex => $aEvent) {
            $aData = $this->getDataCached($aEvent);
            if($aData === false)
                continue;

            $iEventId = $aEvent['id'];
            $iEventAuthorId = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? (int)$aEvent['owner_id'] : (int)$aEvent['object_id'];
            if($iEventAuthorId < 0) {
                if(abs($iEventAuthorId) == $iUserId)
                    continue;
                else if($bModerator)
                    $iEventAuthorId *= -1;
            }

            $oAuthor = $oModule->getObjectUser($iEventAuthorId);
            $sAuthorName = $oAuthor->getDisplayName();

            $aTmplVarsItems[] = array(
                'bx_if:show_as_hidden' => array(
                    'condition' => !$bShowAll && $iIndex < ($iEvents - 1),
                    'content' => array(),
                ),
                'item' => $this->parseHtmlByName('live_update_notification.html', array(
                    'style_prefix' => $sStylePrefix,
                    'onclick_show' => "javascript:" . $sJsObject . ".goTo(this, 'timeline-event-" . $iEventId . "', '" . $iEventId . "');",
                    'author_link' => $oAuthor->getUrl(), 
                    'author_title' => bx_html_attribute($sAuthorName),
                    'author_name' => $sAuthorName,
                    'author_unit' => $oAuthor->getUnit(0, array('template' => 'unit_wo_info_links')), 
                    'text' => _t($aData['sample_action'], _t($aData['sample'])),
                )),
                'bx_if:show_previous' => array(
                    'condition' => $bShowActions && $iIndex > 0,
                    'content' => array(
                        'onclick_previous' => $sJsObject . '.previousLiveUpdate(this)'
                    )
                ),
                'bx_if:show_close' => array(
                    'condition' => $bShowActions,
                    'content' => array(
                        'onclick_close' => $sJsObject . '.hideLiveUpdate(this)'
                    )
                )
            );
        }

        return $this->parseHtmlByName('popup_chain.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('view', 'live_update_popup') . $aBrowseParams['type'],
            'bx_repeat:items' => $aTmplVarsItems
        ));
    }

    protected function _cacheEvent($iProfileId, &$aEvent, &$aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aParamsSet = array(
            'type' => $aParams['type'],
            'context_id' => is_array($aEvent[$CNF['FIELD_OWNER_ID']]) ? array_shift($aEvent[$CNF['FIELD_OWNER_ID']]) : $aEvent[$CNF['FIELD_OWNER_ID']],  
            'profile_id' => $iProfileId, 
            'event_id' => $aEvent[$CNF['FIELD_ID']], 
            'date' => $aEvent[$CNF['FIELD_ADDED']]
        );

        //--- Process Context ID value.
        if($aParams['type'] == BX_BASE_MOD_NTFS_TYPE_PUBLIC)
            $aParamsSet['context_id'] = 0;
        else if(in_array($aParams['type'], array(BX_TIMELINE_TYPE_FEED, BX_TIMELINE_TYPE_CHANNELS, BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS)))
            $aParamsSet['context_id'] = $iProfileId;

        //--- Process 'important' flag.
        if($aParams['type'] == BX_BASE_MOD_NTFS_TYPE_OWNER && (int)$aEvent['pinned'] > 0) 
            $aParamsSet['important'] = 1;
        else if(in_array($aParams['type'], array(BX_BASE_MOD_NTFS_TYPE_PUBLIC, BX_BASE_MOD_NTFS_TYPE_CONNECTIONS, BX_TIMELINE_TYPE_FEED, BX_TIMELINE_TYPE_CHANNELS, BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS)) && (int)$aEvent['sticked'] > 0)
            $aParamsSet['important'] = 1;

        return $this->_oDb->insertCache($aParamsSet);
    }

    protected function _cacheEvents($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $iPerPage = (int)$aParams['per_page'];
        $aParamsCopy = $aParams;

        $aIds = array();
        while(count($aIds) < $iPerPage) {
            $aEvents = $this->_oDb->getEvents($aParamsCopy);
            if(empty($aEvents))
                break;

            foreach($aEvents as $aEvent) {
                if($this->getPost($aEvent, $aParamsCopy) == '')
                    continue;

                $aIds[] = $aEvent[$CNF['FIELD_ID']];
                if(count($aIds) == $iPerPage)
                    break 2;
            }

            $aParamsCopy['start'] += $iPerPage;
        }

        return $aIds;
    }

    protected function _getCachedEvents($iProfileId, &$aParams)
    {
        return $this->_oDb->getCache(array(
            'browse' => $aParams['browse'], 
            'type' => $aParams['type'], 
            'context_id' => $aParams['owner_id'], 
            'profile_id' => $iProfileId, 
            'start' => $aParams['start'], 
            'per_page' => $aParams['per_page']
        ));
    }

    protected function _getPosts($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isCacheList() || $this->_oConfig->isCacheListException($aParams['type']))
            return $this->_oDb->getEvents($aParams);

        $iPerPage = (int)$aParams['per_page'];
        $iProfileId = (int)bx_get_logged_profile_id(); 

        /*
         * Get cached data.
         */
        $aCache = $this->_getCachedEvents($iProfileId, $aParams);
        if(!empty($aCache) && is_array($aCache) && count($aCache) == $iPerPage)
            return $this->_oDb->getEvents(array('browse' => 'ids', 'type' => $aParams['type'], 'ids' => array_keys($aCache)));

        /*
         * Cache data.
         */
        $aIds = $this->_cacheEvents($aParams);
        return $this->_oDb->getEvents(array('browse' => 'ids', 'type' => $aParams['type'], 'ids' => $aIds));
    }

    protected function _getPost($sType, $aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;
        $bViewOutline = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_OUTLINE;

        $oAuthor = $oModule->getObjectUser($aEvent['object_owner_id']);
        $sAuthorName = $oAuthor->getDisplayName(); 
        $sAuthorUrl = $oAuthor->getUrl();
        $sAuthorUnit = $oAuthor->getUnit(0, array('template' => 'unit_wo_info'));
        $sAuthorBadges = $oAuthor->getBadges();
        $sAuthorAction = '';

        if(!empty($aEvent['sample_action_custom']) && is_array($aEvent['sample_action_custom'])) {
            $aAuthorAction = $aEvent['sample_action_custom'];

            foreach($aAuthorAction['markers'] as $iIndex => $sMarker)
                if(get_mb_substr($sMarker, 0, 1) == '_')
                    $aAuthorAction['markers'][$iIndex] = _t($sMarker);

            $sAuthorAction = bx_replace_markers(_t($aAuthorAction['content']), $aAuthorAction['markers']);
        }
        else
            $sAuthorAction = _t($aEvent['sample_action'], _t($aEvent['sample']));

        if(($bViewItem || $this->_oConfig->isCountAllViews()) && !empty($aEvent['views']) && is_array($aEvent['views']) && isset($aEvent['views']['system']))
            $oModule->getViewObject($aEvent['views']['system'], $aEvent['views']['object_id'])->doView();

        $aTmplVarsNote = $this->_getTmplVarsNote($aEvent);
        $aTmplVarsMenuItemCounters = $this->_getTmplVarsMenuItemCounters($aEvent, $aBrowseParams);
        $aTmplVarsMenuItemActions = $this->_getTmplVarsMenuItemActions($aEvent, $aBrowseParams);
        $aTmplVarsMenuItemMeta = $this->_getTmplVarsMenuItemMeta($aEvent, $aBrowseParams);

        $aTmplVarsManage = $this->_getTmplVarsManage($aEvent, $aBrowseParams);

        $aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);
        $bTmplVarsTimelineOwner = !empty($aTmplVarsTimelineOwner);

        $aTmplVarsOwnerActions = $this->_getTmplVarsOwnerActions($aEvent, $aBrowseParams);
        $bTmplVarsOwnerActions = !empty($aTmplVarsOwnerActions); 

        $bPinned = $aBrowseParams['type'] == BX_BASE_MOD_NTFS_TYPE_OWNER && (int)$aEvent['pinned'] > 0;
        $bSticked = (int)$aEvent['sticked'] > 0;
        $bPromoted = (int)$aEvent['promoted'] > 0;

        $sClass = $sStylePrefix . '-view-sizer';
        if($bViewOutline) {
            $sClass = $sStylePrefix . '-grid-item-sizer';
            if($bPinned || $bSticked || $bPromoted) {
                $sClass .= ' ' . $sStylePrefix . '-gis';

            if($bPinned)
                $sClass .= '-pnd';
            if($bSticked)
                $sClass .= '-psd';
            if($bPromoted)
                $sClass .= '-pmd';
            }
        }

        $sClass .= ' ' . $aEvent['type'] . (!empty($aEvent['action']) ? ' ' . $aEvent['action'] : '');
        $aAuthorAcl = BxDolAcl::getInstance()->getMemberMembershipInfo($aEvent['object_owner_id']);
        if(!empty($aAuthorAcl) && isset($this->_aAclId2Name[$aAuthorAcl['id']]))
            $sClass .= ' ' . $sStylePrefix . '-aml-' . $this->_aAclId2Name[$aAuthorAcl['id']];

        if(!empty($aBrowseParams['blink']) && in_array($aEvent['id'], $aBrowseParams['blink']))
            $sClass .= ' ' . $sStylePrefix . '-blink';
        if($bPinned)
            $sClass .= ' ' . $sStylePrefix . '-pinned';
        if($bSticked)
            $sClass .= ' ' . $sStylePrefix . '-sticked';
        if($bPromoted)
            $sClass .= ' ' . $sStylePrefix . '-promoted';

        $sClassOwner = $bTmplVarsOwnerActions ? $sStylePrefix . '-io-with-actions' : '';

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        $sLocation = $oMetatags->locationsString($aEvent['id']);
 
        $sFields = '';
        if(!$bViewItem) {
            $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_post'), $this->_oConfig->getObject('form_display_post_view'), $this, $aEvent['id']);
            $oForm->initChecker($aEvent);

            if(!empty($oForm->aInputs) && is_array($oForm->aInputs))
                foreach($oForm->aInputs as $aInput)
                    if($aInput['type'] != 'hidden' && !empty($aInput['value'])) {
                        $sFields = $oForm->getCode();
                        break;
                    }
        }

        $aTmplVars = array (
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'html_id' => $this->_oConfig->getHtmlIdView('item', $aBrowseParams, array('whole' => false)) . $aEvent['id'],
            'class' => $sClass,
            'class_owner' => $sClassOwner,
            'class_content' => $bViewItem ? 'bx-def-color-bg-block' : 'bx-def-color-bg-box',
            'id' => $aEvent['id'],
            'bx_if:show_note' => array(
                'condition' => !empty($aTmplVarsNote),
                'content' => $aTmplVarsNote
            ),
            'bx_if:show_owner_actions' => array(
                'condition' => $bTmplVarsOwnerActions,
                'content' => $aTmplVarsOwnerActions
            ),
            'item_icon' => !empty($aEvent['icon']) ? $aEvent['icon'] : $CNF['ICON'],
            'item_owner_url' => $sAuthorUrl,
            'item_owner_title' => bx_html_attribute($sAuthorName),
            'item_owner_name' => $sAuthorName .' '. $sAuthorBadges,
            'item_owner_unit' => $sAuthorUnit,
            'item_view_url' => $this->_oConfig->getItemViewUrl($aEvent),
            'item_date' => bx_time_js($aEvent['date']),
            'bx_if:show_pinned' => array(
            	'condition' => $bPinned,
            	'content' => array(
                    'style_prefix' => $sStylePrefix,
            	)
            ),
            'bx_if:show_sticked' => array(
            	'condition' => $bSticked,
            	'content' => array(
                    'style_prefix' => $sStylePrefix,
            	)
            ),
            'bx_if:show_hot' => array(
            	'condition' => $this->_oConfig->isHotEvent($aEvent['id']),
            	'content' => array(
                    'style_prefix' => $sStylePrefix,
            	)
            ),
            'bx_if:show_manage' => array(
                'condition' => !empty($aTmplVarsManage),
                'content' => $aTmplVarsManage
            ),
            'bx_if:show_item_action' => array(
                'condition' => $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) || $bTmplVarsTimelineOwner,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'item_owner_action' => $sAuthorAction,
                    'bx_if:show_timeline_owner' => array(
                        'condition' => $bTmplVarsTimelineOwner,
                        'content' => $aTmplVarsTimelineOwner
                    ),
                )
            ),
            'content_type' => $sType,
            'content' => is_string($aEvent['content']) ? $aEvent['content'] : $this->_getContent($sType, $aEvent, $aBrowseParams),
            'bx_if:show_location' => array(
            	'condition' => !empty($sLocation),
            	'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'location' => $sLocation
            	)
            ),
            'bx_if:show_fields' => array(
                'condition' => !empty($sFields),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'fields' => $sFields
            	)
            ),
            'bx_if:show_menu_item_counters' => array(
                'condition' => !empty($aTmplVarsMenuItemCounters),
                'content' => $aTmplVarsMenuItemCounters
            ),
            'bx_if:show_menu_item_actions' => array(
                'condition' => !empty($aTmplVarsMenuItemActions),
                'content' => $aTmplVarsMenuItemActions
            ),
            'bx_if:show_menu_item_meta' => array(
                'condition' => !empty($aTmplVarsMenuItemMeta),
                'content' => $aTmplVarsMenuItemMeta
            ),
            'comments' => '',
        );

        $iPreloadComments = $this->_oConfig->getPreloadComments();
        if($iPreloadComments > 0 && $oModule->isAllowedComment($aEvent) && in_array($aBrowseParams['view'], array(BX_TIMELINE_VIEW_TIMELINE, BX_TIMELINE_VIEW_OUTLINE)))
            $aTmplVars['comments'] = $this->_getComments($aEvent['comments'], array_merge($aBrowseParams, array(
                'cmts_preload_number' => $iPreloadComments,
                'cmts_min_post_form' => false
            )));       

        $sVariable = '_sTmplContentItem' . bx_gen_method_name($aBrowseParams['view']);
        if(empty(self::$$sVariable))
            self::$$sVariable = $this->getHtml('item_' . $aBrowseParams['view'] . '.html');

        $sTmplCode = self::$$sVariable;
        bx_alert($this->_oConfig->getName(), 'get_post', 0, 0, array(
            'type' => $sType,
            'event' => $aEvent,
            'browse_params' => $aBrowseParams,
            'tmpl_code' => &$sTmplCode,
            'tmpl_vars' => &$aTmplVars
        ));

        return $this->parseHtmlByContent($sTmplCode, $aTmplVars);
    }

    protected function _getContent($sType, $aEvent, $aBrowseParams = array())
    {
        $sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(!method_exists($this, $sMethod))
            return '';

        $sVariable = '_sTmplContentType' . bx_gen_method_name($sType);
        if(empty(self::$$sVariable))
            self::$$sVariable = $this->getHtml('type_' . $sType . '.html');

		return $this->parseHtmlByContent(self::$$sVariable, $this->$sMethod($aEvent, $aBrowseParams));
    }

    protected function _getComments($aComments, $aBrowseParams = array())
    {
        $mixedComments = $this->getModule()->getCommentsData($aComments);
        if($mixedComments === false)
            return '';

        list($sSystem, $iObjectId, $iCount) = $mixedComments;
        return $this->getComments($sSystem, $iObjectId, $aBrowseParams);
    }

    protected function _getShowMore($aParams)
    {
        return $this->parseHtmlByName('show_more.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObjectView($aParams),
        ));
    }

    protected function _getImagePopup($aParams)
    {
        $sViewImagePopupId = $this->_oConfig->getHtmlIdView('photo_popup', $aParams);
        $sViewImagePopupContent = $this->parseHtmlByName('popup_image.html', array(
            'image_url' => ''
    	));

    	return BxTemplFunctions::getInstance()->transBox($sViewImagePopupId, $sViewImagePopupContent, true);
    }

    protected function _getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
        $sJsObject = $this->_oConfig->getJsObject('repost');
        $sFormat = "%s.repostItem(this, %d, '%s', '%s', %d);";

        $iOwnerId = !empty($iOwnerId) ? (int)$iOwnerId : $this->getModule()->getUserId(); //--- in whose timeline the content will be reposted
        return sprintf($sFormat, $sJsObject, $iOwnerId, $sType, $sAction, (int)$iObjectId);
    }

    protected function _getJumpToList($aParams)
    {
        $iYearSel = (int)$aParams['timeline'];
        $iYearMin = $this->_oDb->getMaxDuration($aParams);      
        if(empty($iYearMin))
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aParams);

        $aYears = array();
        $iYearMax = date('Y', time()) - 1;
        for($i = $iYearMax; $i >= $iYearMin; $i--) {
            $bCurrent = $i == $iYearSel;
            $aYears[] = array(
                'style_prefix' => $sStylePrefix,
                'bx_if:show_link' => array(
                    'condition' => !$bCurrent,
                    'content' => array(
                        'title' => _t('_bx_timeline_txt_jump_to_n_year', $i),
                        'onclick' => 'javascript:' . $sJsObject . '.changeTimeline(this, \'' . $i . '-12-31\')',
                        'content' => $i
                    )
                ),
                'bx_if:show_text' => array(
                    'condition' => $bCurrent,
                    'content' => array(
                        'content' => $i
                    )
                ),
            );
        }

        return $this->parseHtmlByName('jump_to.html', array(
            'bx_if:show_list' => array(
                'condition' => true,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:links' => $aYears,
                )
            ),
            'bx_if:show_calendar' => array(
                'condition' => false,
                'content' => array()
            )
        ));
    }

    protected function _getJumpToCaledar($aParams)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aParams);
                
        return $this->parseHtmlByName('jump_to.html', array(
            'bx_if:show_list' => array(
                'condition' => false,
                'content' => array()
            ),
            'bx_if:show_calendar' => array(
                'condition' => true,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'onclick' => 'javascript:' . $sJsObject . '.showCalendar(this)',
                )
            )
        ));
    }

    protected function _getTmplVarsMenuItemActions(&$aEvent, $aBrowseParams = array())
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions_all'));
        if(!$oMenu) {
            $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions'));
            if(!$oMenu)
                return array();
        }

        $oMenu->setEvent($aEvent, $aBrowseParams);
        $oMenu->setDynamicMode(isset($aBrowseParams['dynamic_mode']) && $aBrowseParams['dynamic_mode'] === true);

        $sMenu = $oMenu->getCode();
        if(empty($sMenu))
            return array();

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObjectView($aBrowseParams),
            'menu_item_actions' => $sMenu
        );
    }

    protected function _getTmplVarsManage(&$aEvent, $aBrowseParams = array())
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions_all'));
        if($oMenu)
            return array();
        
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_manage'));
        if(!$oMenu)
            return array();

        $oMenu->setEvent($aEvent, $aBrowseParams);
        $oMenu->setDynamicMode(isset($aBrowseParams['dynamic_mode']) && $aBrowseParams['dynamic_mode'] === true);
        if(!$oMenu->isVisible())
            return array();

        $sOnclick = "bx_menu_popup('bx_timeline_menu_item_manage', this, {'id':'bx_timeline_menu_item_manage_{content_id}'}, {content_id:{content_id}, name:'{name}', view:'{view}', type:'{type}'});";
        $sOnclick = bx_replace_markers($sOnclick, array(
            'content_id' => $aEvent['id'],            
            'view' => $aBrowseParams['view'],
            'type' => $aBrowseParams['type'],
            'name' => $aBrowseParams['name'],
        ));

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'onclick' => $sOnclick
        );
    }

    protected function _getTmplVarsMenuItemCounters(&$aEvent, $aBrowseParams = array())
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_counters'));
        $oMenu->setEvent($aEvent, $aBrowseParams);
        $oMenu->setDynamicMode(isset($aBrowseParams['dynamic_mode']) && $aBrowseParams['dynamic_mode'] === true);

        $sMenu = $oMenu->getCode();
        if(empty($sMenu))
            return array();

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObjectView($aBrowseParams),
            'menu_item_counters' => $sMenu
        );
    }

    protected function _getTmplVarsMenuItemMeta(&$aEvent, $aBrowseParams = array())
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_meta'));
        if(!$oMenu)
            return array();

        $oMenu->setEvent($aEvent);

        $sMenu = $oMenu->getCode();
        if(empty($sMenu))
            return array();

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'meta' => $sMenu
        );
    }

    protected function _getTmplVarsTimelineOwner(&$aEvent)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $iUser = bx_get_logged_profile_id();
        $oModule = $this->getModule();

        $sConnection = $this->_oConfig->getObject('conn_subscriptions');
        $oConnection = BxDolConnection::getObjectInstance($sConnection);
        $sConnectionTitle = _t('_sys_menu_item_title_sm_subscribe');

        $sKeyOwnerId = isset($aEvent['owner_id_grouped']) ? 'owner_id_grouped' : 'owner_id';
        $aOwnerIds = is_array($aEvent[$sKeyOwnerId]) ? $aEvent[$sKeyOwnerId] : array($aEvent[$sKeyOwnerId]);

        $aTmplVarsOwners = array();
        foreach($aOwnerIds as $iOwnerId) {
            $iOwnerId = (int)$iOwnerId;
            $iObjectOwner = (int)$aEvent['object_owner_id'];
            if($iObjectOwner < 0 && abs($iObjectOwner) == $iUser)
                $iObjectOwner *= -1;

            if($iOwnerId == 0 || $iOwnerId == $iObjectOwner)
                continue;

            $oOwner = $oModule->getObjectUser($iOwnerId);
            $sToType = $oOwner->getModule();
            $sToName = $oOwner->getDisplayName();
            $sToUrl = $oOwner->getUrl();

            $aTmplVarsActions = array();
            if(!empty($iUser) && $iUser != $iOwnerId && $oConnection->checkAllowedConnect($iUser, $iOwnerId) === CHECK_ACTION_RESULT_ALLOWED) {
                $aTmplVarsActions[] = array(
                    'href' => "javascript:void(0)",
                    'onclick' => "bx_conn_action(this, '" . $sConnection . "', 'add', '" . $iOwnerId . "')",
                    'title' => bx_html_attribute($sConnectionTitle),
                    'content' => $sConnectionTitle,
                    'icon' => 'check'
                );
            }

            $aTmplVarsOwners[] =  array(
                'style_prefix' => $sStylePrefix,
                'owner_type' => _t('_' . $sToType),
                'owner_url' => $sToUrl,
                'owner_username' => $sToName,
                'bx_if:show_timeline_owner_actions' => array(
                    'condition' => !empty($aTmplVarsActions),
                    'content' => array(
                        'style_prefix' => $sStylePrefix,
                        'bx_repeat:timeline_owner_actions' => $aTmplVarsActions
                    )
                )
            );
        }

        if(empty($aTmplVarsOwners))
            return array();

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:owners' => $aTmplVarsOwners
        );
    }

    protected function _getTmplVarsContentPost(&$aEvent, $aBrowseParams = array())
    {
    	$aContent = &$aEvent['content'];
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;
        $bViewSearch = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_SEARCH;

        //--- Process Raw ---//
        $sRaw = isset($aContent['raw']) ? $aContent['raw'] : '';

        //--- Process Text ---//
        $sUrl = isset($aContent['url']) ? bx_html_attribute($aContent['url']) : '';
        $sTitle = '';
        if(isset($aContent['title']))
            $sTitle = bx_process_output($aContent['title']);

        if(!empty($sUrl) && !empty($sTitle))
            $sTitle = $this->parseLink($sUrl, $sTitle, array(
            	'class' => $sStylePrefix . '-title',
                'title' => $sTitle
            ));

        $sText = isset($aContent['text']) ? $aContent['text'] : '';
        $sText = $this->_prepareTextForOutput($sText, $aEvent['id']);

        //--- Process Links ---//
        $bAddNofollow = $this->_oDb->getParam('sys_add_nofollow') == 'on';

        $aTmplVarsLinks = array();
        if(!empty($aContent['links']))
            foreach($aContent['links'] as $aLink) {
                $sLink = '';

                $oEmbed = BxDolEmbed::getObjectInstance();
                if ($oEmbed) {
                    $sLink = $this->parseHtmlByName('link_embed_provider.html', array(
                        'style_prefix' => $sStylePrefix,
                        'embed' => $oEmbed->getLinkHTML($aLink['url'], $aLink['title']),
                    ));
                }
                else {
                    $aLinkAttrs = array(
                    	'title' => $aLink['title']
                    );
                    if(!$this->_oConfig->isEqualUrls(BX_DOL_URL_ROOT, $aLink['url'])) {
                        $aLinkAttrs['target'] = '_blank';
    
                        if($bAddNofollow)
                    	    $aLinkAttrs['rel'] = 'nofollow';
                    }

                    $sLinkAttrs = '';
                    foreach($aLinkAttrs as $sKey => $sValue)
                        $sLinkAttrs .= ' ' . $sKey . '="' . bx_html_attribute($sValue) . '"';

                    $sLink = $this->parseHtmlByName('link_embed_common.html', array(
                        'bx_if:show_thumbnail' => array(
                            'condition' => !empty($aLink['thumbnail']),
                            'content' => array(
                                'style_prefix' => $sStylePrefix,
                                'thumbnail' => $aLink['thumbnail'],
                                'link' => !empty($aLink['url']) ? $aLink['url'] : 'javascript:void(0)',
                                'attrs' => $sLinkAttrs
                            )
                        ),
                        'link' => !empty($aLink['url']) ? $aLink['url'] : 'javascript:void(0)',
                        'attrs' => $sLinkAttrs,
                        'content' => $aLink['title'],
                        'bx_if:show_text' => array(
                            'condition' => !empty($aLink['text']),
                            'content' => array(
                                'style_prefix' => $sStylePrefix,
                                'text' => $aLink['text']
                            )
                        )
                    ));
                }

                $aTmplVarsLinks[] = array(
                    'style_prefix' => $sStylePrefix,
                    'link' => $sLink
                );
            }

        /*
         * View Item page and Snippet in Search Results should use Gallery layout.
         */
        $sAttachmentsLayout = $this->_oConfig->getAttachmentsLayout();
        if($bViewItem || $bViewSearch)
            $sAttachmentsLayout = BX_TIMELINE_ML_GALLERY;

        $iAttachmentsTotal = 0;
        $aTmplVarsImages = $aTmplVarsVideos = $aTmplVarsAttachments = array();

        //--- Process Photos ---//
        $bImages = !empty($aContent['images']) && is_array($aContent['images']);
        if($bImages) {
            $aImages = $this->_getTmplVarsImages($aContent['images'], true, $aEvent, $aBrowseParams);
            if(!empty($aImages))
                $aTmplVarsImages = array(
                    'style_prefix' => $sStylePrefix,
                    'display' => $aImages['display'],
                    'bx_repeat:items' => $aImages['items']
                );
        }

        $bImagesAttach = !empty($aContent['images_attach']) && is_array($aContent['images_attach']);
        if($bImagesAttach) {
            $aImagesAttach = $this->_getTmplVarsImages($aContent['images_attach'], array('layout' => $sAttachmentsLayout, 'first' => empty($aTmplVarsAttachments)), $aEvent, $aBrowseParams);
            if(!empty($aImagesAttach)) {
                $iAttachmentsTotal += $aImagesAttach['total'];
                $aTmplVarsAttachments = array_merge($aTmplVarsAttachments, $aImagesAttach['items']);
            }
        }

        //--- Add Meta Image when Item is viewed on a separate page ---//
        if($bViewItem) {
            $sMetaImageSrc = '';
            if($bImages && !empty($aContent['images'][0]['src']))
                $sMetaImageSrc = $aContent['images'][0]['src'];
            else if($bImagesAttach && !empty($aContent['images_attach'][0]['src']))
                $sMetaImageSrc = $aContent['images_attach'][0]['src'];

            if(!empty($sMetaImageSrc))
                BxDolTemplate::getInstance()->addPageMetaImage($sMetaImageSrc);
        }

    	//--- Process Videos ---//
        $bVideos = !empty($aContent['videos']) && is_array($aContent['videos']);
        if($bVideos) {
            $aVideos = $this->_getTmplVarsVideos($aContent['videos'], true, $aEvent, $aBrowseParams);
            if(!empty($aVideos))
                $aTmplVarsVideos = array(
                    'style_prefix' => $sStylePrefix,
                    'display' => $aVideos['display'],
                    'bx_repeat:items' => $aVideos['items']
                );
        }

        $bVideosAttach = !empty($aContent['videos_attach']) && is_array($aContent['videos_attach']);
        if($bVideosAttach) {
            $aVideosAttach = $this->_getTmplVarsVideos($aContent['videos_attach'], array('layout' => $sAttachmentsLayout, 'first' => empty($aTmplVarsAttachments)), $aEvent, $aBrowseParams);
            if(!empty($aVideosAttach)) {
                $iAttachmentsTotal += $aVideosAttach['total'];
                $aTmplVarsAttachments = array_merge($aTmplVarsAttachments, $aVideosAttach['items']);
            }
        }

        //--- Process Files ---//
        $bFiles = !empty($aContent['files']) && is_array($aContent['files']);
        if($bFiles) {
            $aFiles = $this->_getTmplVarsFiles($aContent['files'], $aEvent, $aBrowseParams);
            if(!empty($aFiles))
                $aTmplVarsFiles = array(
                    'style_prefix' => $sStylePrefix,
                    'display' => $aFiles['display'],
                    'bx_repeat:items' => $aFiles['items']
                );
        }

        $bFilesAttach = !empty($aContent['files_attach']) && is_array($aContent['files_attach']);
        if($bFilesAttach) {
            $aFilesAttach = $this->_getTmplVarsFiles($aContent['files_attach'], $aEvent, $aBrowseParams);
            if(!empty($aFilesAttach)) {
                $iAttachmentsTotal += $aFilesAttach['total'];
                $aTmplVarsAttachments = array_merge($aTmplVarsAttachments, $aFilesAttach['items']);
            }
        }

        /*
         *  Process collected attachments in case of Showcase layout.
         */
        $iAttachmentsShow = 4;
        $iAttachmentsCount = count($aTmplVarsAttachments);
        if($sAttachmentsLayout == BX_TIMELINE_ML_SHOWCASE && $iAttachmentsCount > 0) {
            $aTmplVarsAttachments[0]['class'] .= ' ' . $sStylePrefix . '-ia-first';

            if($iAttachmentsCount > $iAttachmentsShow)
                $aTmplVarsAttachments = array_slice($aTmplVarsAttachments, 0, $iAttachmentsShow);

            if($iAttachmentsTotal > $iAttachmentsShow)
                $aTmplVarsAttachments[$iAttachmentsShow - 1]['item'] .= $this->parseHtmlByName('attach_more.html', array(
                    'style_prefix' => $sStylePrefix,
                    'link' => $this->_oConfig->getItemViewUrl($aEvent),
                    'more' => $iAttachmentsTotal - $iAttachmentsShow
                ));
        }

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_if:show_title' => array(
                'condition' => !empty($sTitle),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'title' => $sTitle,
                )
            ),
            'bx_if:show_content' => array(
                'condition' => !empty($sText),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'item_content' => $sText
                )
            ),
            'bx_if:show_raw' => array(
                'condition' => !empty($sRaw),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'item_content_raw' => $sRaw
                )
            ),
            'bx_if:show_links' => array(
                'condition' => !empty($aTmplVarsLinks),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:links' => $aTmplVarsLinks
                )
            ),
            'bx_if:show_images' => array(
                'condition' => !empty($aTmplVarsImages),
                'content' => $aTmplVarsImages
            ),
            'bx_if:show_videos' => array(
                'condition' => !empty($aTmplVarsVideos),
                'content' => $aTmplVarsVideos
            ),
            'bx_if:show_attachments' => array(
                'condition' => !empty($aTmplVarsAttachments),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'display' => $sAttachmentsLayout,
                    'count' => count($aTmplVarsAttachments),
                    'bx_repeat:items' => $aTmplVarsAttachments
                )
            )
        );
    }

    protected function _getTmplVarsContentRepost(&$aEvent, $aBrowseParams = array())
    {
    	$aContent = &$aEvent['content'];
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $sOwnerLink = $this->parseLink($aContent['owner_url'], $aContent['owner_name']);

        $sSample = _t($aContent['sample']);
        $sSampleLink = empty($aContent['url']) ? $sSample : $this->parseLink($aContent['url'], $sSample);

        $sContent = $this->_getContent($aContent['parse_type'], $aEvent, $aBrowseParams);

        return array(
            'style_prefix' => $sStylePrefix,
            'item_owner_action' => _t('_bx_timeline_txt_reposted', $sOwnerLink, $sSampleLink),
            'bx_if:show_content' => array(
                'condition' => !empty($sContent),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'content' => $sContent,
                )
            )
        );
    }

    protected function _getTmplVarsNote(&$aEvent)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $iUser = bx_get_logged_profile_id();
        $iOwner = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $aEvent['owner_id'] : $aEvent['object_id'];

        $aTmplVars = array();
        if(!empty($iOwner) && !is_array($iOwner) && !empty($aEvent['promoted'])) {
            $sConnection = $this->_oConfig->getObject('conn_subscriptions');
            $oConnection = BxDolConnection::getObjectInstance($sConnection);
            if(!$oConnection->isConnected($iUser, $iOwner))
                $aTmplVars[] = array(
                    'style_prefix' => $sStylePrefix,
                    'class' => '',
                    'bx_if:show_note_color' => array(
                        'condition' => false,
                        'content' => array(
                            'item_note_color' => 'red1',
                        )
                    ),
                    'item_note' => _t('_bx_timeline_txt_promoted')
                );
        }

        //--- Awaiting status related notes.
        if($aEvent['status'] == BX_TIMELINE_STATUS_AWAITING) {
            $sNote = '';
            if((int)$aEvent['published'] > (int)$aEvent['date'])
                $sNote = _t('_bx_timeline_txt_note_scheduled_awaiting', bx_time_js($aEvent['published'], BX_FORMAT_DATE, true));
            else
                $sNote = _t('_bx_timeline_txt_note_processing_awaiting');

            $aTmplVars[] = array(
                'style_prefix' => $sStylePrefix,
                'bx_if:show_note_color' => array(
                    'condition' => true,
                    'content' => array(
                        'item_note_color' => 'red3'
                    )
                ),
                'item_note' => $sNote
            );
        }

        //--- Failed status related notes.
        if($aEvent['status'] == BX_TIMELINE_STATUS_FAILED)
            $aTmplVars[] = array(
                'style_prefix' => $sStylePrefix,
                'bx_if:show_note_color' => array(
                    'condition' => true,
                    'content' => array(
                        'item_note_color' => 'red2'
                    )
                ),
                'item_note' => _t('_bx_timeline_txt_note_processing_failed')
            );

        return empty($aTmplVars) ? array() : array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:notes' => $aTmplVars
        );
    }

    protected function _getTmplVarsOwnerActions(&$aEvent, $aBrowseParams = array())
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $iUser = bx_get_logged_profile_id();
        $iOwner = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $aEvent['object_owner_id'] : $aEvent['object_id'];

        $aTmplVarsActions = array();
        if(!empty($iUser) && !empty($iOwner) && $iUser != $iOwner) {
            $oOwner = BxDolProfile::getInstance($iOwner);
            if($oOwner !== false && bx_srv($oOwner->getModule(), 'check_allowed_with_content', array('subscribe_add', $oOwner->getContentId())) === CHECK_ACTION_RESULT_ALLOWED) {
                $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);
                $sConnection = $this->_oConfig->getObject('conn_subscriptions');

                $sContent = _t('_sys_menu_item_title_sm_subscribe');
                $aTmplVarsActions[] = array(
                    'style_prefix' => $sStylePrefix,
                    'href' => "javascript:void(0)",
                    'onclick' => "bx_conn_action(this, '" . $sConnection . "', 'add', '" . $iOwner . "', false, function(oData, eElement) {" . $sJsObject . ".onConnect(eElement, oData);})",
                    'title' => bx_html_attribute($sContent),
                    'content' => $sContent,
                    'icon' => 'check'
                );
            }
        }

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:owner_actions' => $aTmplVarsActions
        );
    }

    protected function _getTmplVarsImages($aImages, $mixedLayout, &$aEvent, &$aBrowseParams)
    {
        if(empty($aImages) || !is_array($aImages))
            return array();

        $iTotal = 0; //--- Total count of images related to the event.
        if(isset($aImages['total']) && isset($aImages['items'])) {
            $iTotal = (int)$aImages['total'];
            $aImages = $aImages['items'];
        }
        else
            $iTotal = count($aImages);

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;

        $sDisplay = '';
        $aTmplVarsImages = array();

        $sImageSrcKey = $sImageSrcKeyBig = '';
        $sImageSrcKeyDefault = 'src';
        if(count($aImages) == 1) {
            $sDisplay = BX_TIMELINE_ML_SINGLE;
            $sImageSrcKey = $bViewItem ? 'src_orig' : 'src_medium';
        }
        else if(is_array($mixedLayout) && !empty($mixedLayout['layout'])) {
            $sDisplay = $mixedLayout['layout'];
            $sImageSrcKey = 'src';
            $sImageSrcKeyBig = 'src_medium';
        }
        else {
            $sDisplay = BX_TIMELINE_ML_GALLERY;
            $sImageSrcKey = 'src';
            $sImageSrcKeyBig = 'src_medium';
        }

        $bAttachFirst = false;
        if($sDisplay == BX_TIMELINE_ML_SHOWCASE)
            $bAttachFirst = isset($mixedLayout['first']) && $mixedLayout['first'] === true;

        $aImageFirst = reset($aImages);
        $iImageFirst = isset($aImageFirst['id']) ? (int)$aImageFirst['id'] : 0;

        foreach($aImages as $aImage) {
            $sImageSrcKeyCur = $sImageSrcKey;
            if($bAttachFirst && isset($aImage['id']) && (int)$aImage['id'] == $iImageFirst)
                $sImageSrcKeyCur = $sImageSrcKeyBig;

            $sImageSrc = !empty($aImage[$sImageSrcKeyCur]) ? $aImage[$sImageSrcKeyCur] : $aImage[$sImageSrcKeyDefault];
            if(empty($sImageSrc))
                continue;

            $sImage = $this->parseImage($sImageSrc, array(
                'class' => $sStylePrefix . '-item-image'
            ));

            $aAttrs = array();
            if(isset($aImage['onclick']))
                $aAttrs['onclick'] = $aImage['onclick'];
            else if(!empty($aImage['src_orig']))
                $aAttrs['onclick'] = 'return ' . $sJsObject . '.showItem(this, \'' . $aEvent['id'] . '\', \'photo\', ' . json_encode(array('src' => base64_encode($aImage['src_orig']))) . ')'; 

            $sImage = $this->parseLinkByName('image_link.html', isset($aImage['url']) ? $aImage['url'] : 'javascript:void(0)', $sImage, $aAttrs);

            $aTmplVarsImages[] = array(
                'style_prefix' => $sStylePrefix,
                'class' => '',
                'item' => $sImage
            );
        }
        
        return array(
            'display' => $sDisplay,
            'total' => $iTotal,
            'items' => $aTmplVarsImages
        );
    }

    protected function _getTmplVarsVideos($aVideos, $mixedLayout, &$aEvent, &$aBrowseParams)
    {
        if(empty($aVideos) || !is_array($aVideos))
            return array();

        $iTotal = 0; //--- Total count of videos related to the event.
        if(isset($aVideos['total']) && isset($aVideos['items'])) {
            $iTotal = (int)$aVideos['total'];
            $aVideos = $aVideos['items'];
        }
        else
            $iTotal = count($aVideos);

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $aTmplVarsVideos = array();

        /*
         * For now Main Section may have only one video which can use 'autoplay' feature.
         */
        $bMain = $mixedLayout === true;
        if($bMain) {
            $sDisplay = BX_TIMELINE_ML_SINGLE;
            if(count($aVideos) > 1)
                $aVideos = array_slice($aVideos, 0, 1);
        }
        else
            $sDisplay = is_array($mixedLayout) && !empty($mixedLayout['layout']) ? $mixedLayout['layout'] : BX_TIMELINE_AML_DEFAULT;

        /*
         * Main Section: Autoplay feature is only available here.
         */
        $sVap = $sVapId = $sVapSrc = $sVapTmpl = '';
        if($bMain) {
            $sVap = $this->_oConfig->getVideosAutoplay();
            if($sVap != BX_TIMELINE_VAP_OFF) {
                $sVapId = $this->_oConfig->getHtmlIds('view', 'video_iframe') . $aEvent['id'] . '-';
                $sVapSrc = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'video/' . $aEvent['id'] . '/';
                $sVapTmpl = $this->getHtml('video_iframe.html'); 
            }
        }

        /*
         * Attachments Section.
         */
        $sAttachTmpl = '';
        $bAttachFirst = false;
        if($sDisplay == BX_TIMELINE_ML_SHOWCASE) {
            $sAttachTmpl = $this->getHtml('attach_video_preview.html');
            $bAttachFirst = isset($mixedLayout['first']) && $mixedLayout['first'] === true;
        }

        $iVideoFirst = reset($aVideos)['id'];
        foreach($aVideos as $aVideo) {
            $iVideo = (int)$aVideo['id'];

            if($bMain && $sVap != BX_TIMELINE_VAP_OFF)
                $aTmplVarsVideos[] = array(
                    'style_prefix' => $sStylePrefix,
                    'class' => '',
                    'item' => $this->parseHtmlByContent($sVapTmpl, array(
                        'style_prefix' => $sStylePrefix,
                        'html_id' => $sVapId . $iVideo,
                        'src' => $sVapSrc . $iVideo . '/'
                    )) 
                );
            else {
                if($bMain || $sDisplay == BX_TIMELINE_ML_GALLERY || ($bAttachFirst && $iVideo == $iVideoFirst))
                    $aTmplVarsVideos[] = array(
                        'style_prefix' => $sStylePrefix,
                        'class' => '',
                        'item' => BxTemplFunctions::getInstance()->videoPlayer($aVideo['src_poster'], $aVideo['src_mp4'], $aVideo['src_mp4_hd'], array(
                            'preload' => 'auto'
                        ), '', $aBrowseParams['dynamic_mode'])
                    );
                else {
                    $bUrl = !empty($aVideo['url']);
                    $sUrl = $bUrl ? $aVideo['url'] : '';
                    
                    $bDuration = !empty($aVideo['duration']);
                    $sDuration = _t_format_duration($bDuration ? $aVideo['duration'] : 0);

                    $aTmplVarsVideos[] = array(
                        'style_prefix' => $sStylePrefix,
                        'class' => '',
                        'item' => $this->parseHtmlByContent($sAttachTmpl, array(
                            'style_prefix' => $sStylePrefix,
                            'bx_if:show_link' => array(
                                'condition' => $bUrl,
                                'content' => array(
                                    'style_prefix' => $sStylePrefix,
                                    'url' => $sUrl,
                                    'src' => $aVideo['src_poster'],
                                )
                            ),
                            'bx_if:show_non_link' => array(
                                'condition' => !$bUrl,
                                'content' => array(
                                    'style_prefix' => $sStylePrefix,
                                    'src' => $aVideo['src_poster'],
                                )
                            ),
                            'bx_if:show_duration' => array(
                                'condition' => $bDuration,
                                'content' => array(
                                    'style_prefix' => $sStylePrefix,
                                    'duration' => $sDuration,
                                )
                            )
                        ))
                    );
                }
            }
                
        }

        return array( 
            'display' => $sDisplay,
            'total' => $iTotal,
            'items' => $aTmplVarsVideos
        );
    }

    protected function _getTmplVarsFiles($aFiles, &$aEvent, &$aBrowseParams)
    {
        if(empty($aFiles) || !is_array($aFiles))
            return array();

        $iTotal = 0; //--- Total count of files related to the event.
        if(isset($aFiles['total']) && isset($aFiles['items'])) {
            $iTotal = (int)$aFiles['total'];
            $aFiles = $aFiles['items'];
        }
        else
            $iTotal = count($aFiles);

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;

        $sDisplay = '';
        $aTmplVarsFiles = array();

        $sFileSrcKey = '';
        $sFileSrcKeyDefault = 'src';
        if(count($aFiles) == 1) {
            $sDisplay = 'single';
            $sFileSrcKey = $bViewItem ? 'src_orig' : 'src_medium';
        }
        else {
            $sDisplay = 'gallery';
            $sFileSrcKey = 'src';
        }

        foreach($aFiles as $aFile) {
            $sFileSrc = !empty($aFile[$sFileSrcKey]) ? $aFile[$sFileSrcKey] : $aFile[$sFileSrcKeyDefault];
            if(empty($sFileSrc))
                continue;

            $sImage = $this->parseImage($sFileSrc, array(
                'class' => $sStylePrefix . '-item-file'
            ));

            $aAttrs = array();
            if(isset($aFile['onclick']))
                $aAttrs['onclick'] = $aFile['onclick'];
            else if(!$bViewItem && !empty($aFile['src_orig']))
                $aAttrs['onclick'] = 'return ' . $sJsObject . '.showItem(this, \'' . $aEvent['id'] . '\', \'photo\', ' . json_encode(array('src' => base64_encode($aFile['src_orig']))) . ')'; 

            $sImage = $this->parseLinkByName('file_link.html', isset($aFile['url']) ? $aFile['url'] : 'javascript:void(0)', $sImage, $aAttrs);

            $aTmplVarsFiles[] = array(
                'style_prefix' => $sStylePrefix,
                'class' => '',
                'item' => $sImage
            );
        }

        return array(
            'display' => $sDisplay,
            'total' => $iTotal,
            'items' => $aTmplVarsFiles
        );
    }

    protected function _getSystemData(&$aEvent, $aBrowseParams = array())
    {
        $mixedResult = $this->_oConfig->getSystemData($aEvent, $aBrowseParams);
        if($mixedResult === false) {
            $sMethod = 'display' . bx_gen_method_name($aEvent['type'] . '_' . $aEvent['action']);
            if(method_exists($this, $sMethod))
                $mixedResult = $this->$sMethod($aEvent);
        }

        if($mixedResult === false)
            return '';

        $this->_preparetDataActions($aEvent, $mixedResult);
        return $mixedResult;
    }

    protected function _getCommonData(&$aEvent, $aBrowseParams = array())
    {
        $CNF = $this->_oConfig->CNF;

        $oModule = $this->getModule();
        $sJsObject = $this->_oConfig->getJsObjectView($aBrowseParams);
        $sPrefix = $this->_oConfig->getPrefix('common_post');
        $sType = str_replace($sPrefix, '', $aEvent['type']);

        $oObjectOwner = BxDolProfile::getInstanceMagic($aEvent['object_id']);

        $iOwnerId = $aEvent['owner_id'];
        if(is_array($aEvent['owner_id']))
            $iOwnerId = is_numeric($aEvent['object_privacy_view']) && (int)$aEvent['object_privacy_view'] < 0 ? abs((int)$aEvent['object_privacy_view']) : (int)array_shift($aEvent['owner_id']);

        $aResult = array(
            'owner_id' => $iOwnerId,
            'object_owner_id' => $aEvent['object_id'],
            'icon' => $CNF['ICON'],
            'sample' => '_bx_timeline_txt_sample_with_article',
            'sample_wo_article' => '_bx_timeline_txt_sample',
            'sample_action' => '_bx_timeline_txt_added_sample',
            'content_type' => $sType,
            'content' => array(
                'sample' => '_bx_timeline_txt_sample_with_article',
                'sample_wo_article' => '_bx_timeline_txt_sample',
                'sample_action' => '_bx_timeline_txt_added_sample',
                'url' => $this->_oConfig->getItemViewUrl($aEvent)
            ), //a string to display or array to parse default template before displaying.
            'views' => '',
            'votes' => '',
            'reactions' => '',
            'scores' => '',
            'reports' => '',
            'comments' => '',
            'title' => $aEvent['title'], //may be empty.
            'description' => bx_replace_markers($aEvent['description'], array(
                'profile_name' => $oObjectOwner->getDisplayName()
            )) //may be empty.
        );

        switch($sType) {
            case BX_TIMELINE_PARSE_TYPE_POST:
                if(!empty($aEvent['content']))
                    $aResult['content'] = array_merge($aResult['content'], unserialize($aEvent['content']));

                $aResult['content']['links'] = $oModule->getEventLinks($aEvent['id']);
                $aResult['content']['images_attach'] = $oModule->getEventImages($aEvent['id']);
                $aResult['content']['videos_attach'] = $oModule->getEventVideos($aEvent['id']);
                break;

            case BX_TIMELINE_PARSE_TYPE_REPOST:
                if(empty($aEvent['content']))
                    return array();

                $aContent = unserialize($aEvent['content']);

                if(!$this->_oConfig->isSystem($aContent['type'] , $aContent['action'])) {
                    $aEventReposted = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $aContent['object_id']));
                    $aReposted = $this->_getCommonData($aEventReposted, $aBrowseParams);
                } 
                else {
                    $aEventReposted = $this->_oDb->getEvents(array_merge(array('browse' => 'descriptor'), $aContent));
                    $aReposted = $this->_getSystemData($aEventReposted, $aBrowseParams);
                }

                if(empty($aReposted) || !is_array($aReposted))
                    return array();

                $aResult['content'] = array_merge($aContent, $aReposted['content']);
                $aResult['content']['parse_type'] = !empty($aReposted['content_type']) ? $aReposted['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;

                $sKey = 'allowed_view';
                $aResult['content'][$sKey] = $this->_preparePrivacy($sKey, $aEventReposted, $aReposted);

                $oObjectOwner = $oModule->getObjectUser($aReposted['object_owner_id']);
                $aResult['content']['owner_id'] = $aReposted['object_owner_id'];
                $aResult['content']['owner_name'] = $oObjectOwner->getDisplayName();
                $aResult['content']['owner_url'] = $oObjectOwner->getUrl();

                if(!empty($aReposted['sample']))
                    $aResult['content']['sample'] = $aReposted['sample'];
                if(!empty($aReposted['sample_wo_article']))
                    $aResult['content']['sample'] = $aReposted['sample_wo_article'];

                $sUserName = $oModule->getObjectUser($aEvent['object_id'])->getDisplayName();
                $aResult['title'] = _t('_bx_timeline_txt_user_repost', $sUserName, _t($aResult['content']['sample']));
                $aResult['description'] = _t('_bx_timeline_txt_user_reposted_user_sample', $sUserName, $aResult['content']['owner_name'], _t($aResult['content']['sample']));
                $aResult['allowed_view'] = array(
                    'module' => $this->_oConfig->getName(),
                    'method' => 'get_timeline_repost_allowed_view',
                );

                if(!$this->_oConfig->isRepostOwnActions()) {
                    $aResult['views'] = $aReposted['views'];
                    $aResult['votes'] = $aReposted['votes'];
                    $aResult['reactions'] = $aReposted['reactions'];
                    $aResult['scores'] = $aReposted['scores'];
                    $aResult['reports'] = $aReposted['reports'];
                    $aResult['comments'] = $aReposted['comments'];
                }
                break;
        }

        $this->_preparetDataActions($aEvent, $aResult);
        return $aResult;
    }

    protected function _getFirst($aEvents, $aParams = array())
    {
        $CNF = $this->_oConfig->CNF;

        foreach($aEvents as $aEvent)
            if((int)$aEvent[$CNF['FIELD_STICKED']] == 0)
                return (int)$aEvent[$CNF['FIELD_ID']];

        $aParams['start'] += $aParams['per_page'];
        $aEvents = $this->_getPosts($aParams);
        if(!empty($aEvents) && is_array($aEvents))
            return $this->_getFirst($aEvents, $aParams);

        return 0;
    }

    protected function _preparetDataActions(&$aEvent, &$aResult)
    {
        if(empty($aEvent) || !is_array($aEvent) || empty($aEvent['id']))
            return;

        $oModule = $this->getModule();

        $sSystem = $this->_oConfig->getObject('view');
        if(empty($aResult['views'])) {
            $aResult['views'] = array();
            if($oModule->getViewObject($sSystem, $aEvent['id']) !== false)
                $aResult['views'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'count' => $aEvent['views']
                );
        }

        $sSystem = $this->_oConfig->getObject('vote');
        if(empty($aResult['votes'])) {
            $aResult['votes'] = array();
            if($oModule->getVoteObject($sSystem, $aEvent['id']) !== false)
                $aResult['votes'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'count' => $aEvent['votes']
                );
        }
        
        $sSystem = $this->_oConfig->getObject('reaction');
        if(empty($aResult['reactions'])) {
            $aResult['reactions'] = array();
            if($oModule->getReactionObject($sSystem, $aEvent['id']) !== false)
                $aResult['reactions'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'count' => $aEvent['rvotes']
                );
        }

        $sSystem = $this->_oConfig->getObject('score');
        if(empty($aResult['scores'])) {
            $aResult['scores'] = array();
            if($oModule->getScoreObject($sSystem, $aEvent['id']) !== false)
                $aResult['scores'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'score' => $aEvent['score']
                );
        }

        $sSystem = $this->_oConfig->getObject('report');
        if(empty($aResult['reports'])) {
            $aResult['reports'] = array();
            if($oModule->getReportObject($sSystem, $aEvent['id']) !== false)
                $aResult['reports'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'count' => $aEvent['reports']
                );
        }

        $sSystem = $this->_oConfig->getObject('comment');
        if(empty($aResult['comments'])) {
            $aResult['comments'] = array();
            if($oModule->getCmtsObject($sSystem, $aEvent['id']) !== false)
                $aResult['comments'] = array(
                    'system' => $sSystem,
                    'object_id' => $aEvent['id'],
                    'count' => $aEvent['comments']
                );
        }
    }

    protected function _prepareTextForOutput($s, $iEventId = 0)
    {
        if($this->_oConfig->isBriefCards())
            $s = strip_tags($s, $this->_oConfig->getBriefCardsTags(true));

    	$s = bx_process_output($s, BX_DATA_HTML);

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        $s = $oMetatags->metaParse($iEventId, $s);

        return $s;
    }
    
    protected function _preparePrivacy($sKey, $aEvent, $aEventData)
    {
        $iResult = CHECK_ACTION_RESULT_ALLOWED;
        if(isset($aEventData[$sKey], $aEventData[$sKey]['module'], $aEventData[$sKey]['method']))
            $iResult = BxDolService::call($aEventData[$sKey]['module'], $aEventData[$sKey]['method'], array($aEvent));
        else if(($aHandler = $this->_oConfig->getHandler($aEvent)) !== false && BxDolRequest::serviceExists($aHandler['module_name'], 'get_timeline_post_allowed_view'))
            $iResult = BxDolService::call($aHandler['module_name'], 'get_timeline_post_allowed_view', array($aEvent));

        return $iResult;
    }
}

/** @} */
