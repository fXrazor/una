<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Mass mailer',
    'version_from' => '12.0.1',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/massmailer/updates/update_12.0.1_13.0.0/',
    'home_uri' => 'massmailer_update_1201_1300',

    'module_dir' => 'boonex/massmailer/',
    'module_uri' => 'massmailer',

    'db_prefix' => 'bx_massmailer_',
    'class_prefix' => 'BxMassMailer',

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
    'language_category' => 'MassMailer',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
