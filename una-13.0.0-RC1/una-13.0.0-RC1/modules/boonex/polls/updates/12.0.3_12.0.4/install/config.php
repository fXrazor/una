<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Polls',
    'version_from' => '12.0.3',
    'version_to' => '12.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.1.0'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/polls/updates/update_12.0.3_12.0.4/',
    'home_uri' => 'polls_update_1203_1204',

    'module_dir' => 'boonex/polls/',
    'module_uri' => 'polls',

    'db_prefix' => 'bx_polls_',
    'class_prefix' => 'BxPolls',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Polls',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
