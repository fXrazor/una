<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Conversations',
    'version_from' => '13.0.3',
    'version_to' => '13.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/convos/updates/update_13.0.3_13.0.4/',
    'home_uri' => 'convos_update_1303_1304',

    'module_dir' => 'boonex/convos/',
    'module_uri' => 'convos',

    'db_prefix' => 'bx_convos_',
    'class_prefix' => 'BxCnv',

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
    'language_category' => 'Conversations',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/manage_tools.css',
        'template/attachments.html',
        'template/author.html',
        'template/breadcrumb.html',
        'template/entry-all-actions.html',
        'template/form_ghost_template.html',
    ),
);
