<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Channels',
    'version_from' => '11.0.2',
    'version_to' => '11.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/channels/updates/update_11.0.2_11.0.3/',
    'home_uri' => 'channels_update_1102_1103',

    'module_dir' => 'boonex/channels/',
    'module_uri' => 'channels',

    'db_prefix' => 'bx_cnl_',
    'class_prefix' => 'BxCnl',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Channels',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
