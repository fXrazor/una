<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Files',
    'version_from' => '13.0.1',
    'version_to' => '13.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/files/updates/update_13.0.1_13.0.2/',
    'home_uri' => 'files_update_1301_1302',

    'module_dir' => 'boonex/files/',
    'module_uri' => 'files',

    'db_prefix' => 'bx_files_',
    'class_prefix' => 'BxFiles',

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
    'language_category' => 'Files',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
