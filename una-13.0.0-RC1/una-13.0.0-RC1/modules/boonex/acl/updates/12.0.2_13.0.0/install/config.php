<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Paid Levels',
    'version_from' => '12.0.2',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/acl/updates/update_12.0.2_13.0.0/',
    'home_uri' => 'acl_update_1202_1300',

    'module_dir' => 'boonex/acl/',
    'module_uri' => 'acl',

    'db_prefix' => 'bx_acl_',
    'class_prefix' => 'BxAcl',

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
    'language_category' => 'Paid Levels',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
