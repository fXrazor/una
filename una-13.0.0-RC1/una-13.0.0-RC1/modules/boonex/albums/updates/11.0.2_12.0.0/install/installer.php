<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAlbumsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_albums_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_albums_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");
            if(!$this->oDb->isFieldExists('bx_albums_cmts_media', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_albums_cmts_media` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");
            if(!$this->oDb->isFieldExists('bx_albums_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_albums_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
