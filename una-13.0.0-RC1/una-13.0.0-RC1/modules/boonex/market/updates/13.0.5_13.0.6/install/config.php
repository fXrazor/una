<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Market',
    'version_from' => '13.0.5',
    'version_to' => '13.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/market/updates/update_13.0.5_13.0.6/',
    'home_uri' => 'market_update_1305_1306',

    'module_dir' => 'boonex/market/',
    'module_uri' => 'market',

    'db_prefix' => 'bx_market_',
    'class_prefix' => 'BxMarket',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Market',

    /**
     * Files Section
     */
    'delete_files' => array(
        'classes/BxMarketUploaderSimple.php',
        'template/css/manage_tools.css',
        'template/author.html',
        'template/author_link.html',
        'template/badges.html',
        'template/breadcrumb.html',
        'template/context.html',
        'template/entry-all-actions.html',
        'template/entry-location.html',
        'template/entry-share.html',
        'template/favorite-list-info.html',
        'template/favorite-lists.html',
        'template/link.html',
        'template/title_link.html',
    ),
);
