<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Accounts Manager',
    'version_from' => '13.0.2',
    'version_to' => '13.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/accounts/updates/update_13.0.2_13.0.3/',
    'home_uri' => 'accounts_update_1302_1303',

    'module_dir' => 'boonex/accounts/',
    'module_uri' => 'accounts',

    'db_prefix' => 'bx_accnt_',
    'class_prefix' => 'BxAccnt',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 2,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Accounts',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
