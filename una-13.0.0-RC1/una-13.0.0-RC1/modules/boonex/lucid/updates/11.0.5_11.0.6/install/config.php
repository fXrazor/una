<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Lucid',
    'version_from' => '11.0.5',
    'version_to' => '11.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/lucid/updates/update_11.0.5_11.0.6/',
    'home_uri' => 'lucid_update_1105_1106',

    'module_dir' => 'boonex/lucid/',
    'module_uri' => 'lucid',

    'db_prefix' => 'bx_lucid_',
    'class_prefix' => 'BxLucid',

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
    'language_category' => 'Boonex Lucid Template',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
