<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Nexus',
    'version_from' => '11.0.4',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/nexus/updates/update_11.0.4_12.0.0/',
    'home_uri' => 'nexus_update_1104_1200',

    'module_dir' => 'boonex/nexus/',
    'module_uri' => 'nexus',

    'db_prefix' => 'bx_nexus_',
    'class_prefix' => 'BxNexus',

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
    'language_category' => 'Nexus',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
