<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Payment',
    'version_from' => '11.0.4',
    'version_to' => '11.0.5',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/payment/updates/update_11.0.4_11.0.5/',
    'home_uri' => 'payment_update_1104_1105',

    'module_dir' => 'boonex/payment/',
    'module_uri' => 'payment',

    'db_prefix' => 'bx_payment_',
    'class_prefix' => 'BxPayment',

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
    'language_category' => 'Payment',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
