<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Events',
    'version_from' => '12.0.1',
    'version_to' => '12.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/events/updates/update_12.0.1_12.0.2/',
    'home_uri' => 'events_update_1201_1202',

    'module_dir' => 'boonex/events/',
    'module_uri' => 'events',

    'db_prefix' => 'bx_events_',
    'class_prefix' => 'BxEvents',

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
    'language_category' => 'Events',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
