<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Shopify',
    'version_from' => '11.0.0',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/shopify/updates/update_11.0.0_12.0.0/',
    'home_uri' => 'shopify_update_1100_1200',

    'module_dir' => 'boonex/shopify/',
    'module_uri' => 'shopify',

    'db_prefix' => 'bx_shopify_',
    'class_prefix' => 'BxShopify',

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
    'language_category' => 'Shopify',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
