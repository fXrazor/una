<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Ads',
    'version_from' => '11.0.2',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/ads/updates/update_11.0.2_12.0.0/',
    'home_uri' => 'ads_update_1102_1200',

    'module_dir' => 'boonex/ads/',
    'module_uri' => 'ads',

    'db_prefix' => 'bx_ads_',
    'class_prefix' => 'BxAds',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Ads',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
