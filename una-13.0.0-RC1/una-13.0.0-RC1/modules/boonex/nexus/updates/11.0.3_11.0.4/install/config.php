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
    'version_from' => '11.0.3',
    'version_to' => '11.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/nexus/updates/update_11.0.3_11.0.4/',
    'home_uri' => 'nexus_update_1103_1104',

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
        'update_languages' => 1,
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
