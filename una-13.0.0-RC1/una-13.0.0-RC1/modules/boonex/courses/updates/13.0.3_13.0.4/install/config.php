<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Courses',
    'version_from' => '13.0.3',
    'version_to' => '13.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/courses/updates/update_13.0.3_13.0.4/',
    'home_uri' => 'courses_update_1303_1304',

    'module_dir' => 'boonex/courses/',
    'module_uri' => 'courses',

    'db_prefix' => 'bx_courses_',
    'class_prefix' => 'BxCourses',

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
    'language_category' => 'Courses',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/forms.css',
        'template/css/manage_tools.css',
        'template/css/prices.css',
        'template/account_link.html',
        'template/badges.html',
        'template/entry-all-actions.html',
        'template/entry-share.html',
        'template/entry-text.html',
        'template/favorite-list-info.html',
        'template/favorite-lists.html',
        'template/form_ghost_template.html',
        'template/image_popup.html',
        'template/labels_breadcrumbs.html',
        'template/labels_tree.html',
        'template/menu_main_submenu.html',
        'template/name_link.html',
        'template/picture_preview.html',
        'template/popup_invite.html',
        'template/popup_price.html',
        'template/search_extended_results.html',
        'template/set_acl_popup.html',
        'template/set_role_popup.html',
        'template/uploader_form_crop_cover.html',
    ),
);
