<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Posts',
    'version_from' => '11.0.2',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/posts/updates/update_11.0.2_12.0.0/',
    'home_uri' => 'posts_update_1102_1200',

    'module_dir' => 'boonex/posts/',
    'module_uri' => 'posts',

    'db_prefix' => 'bx_posts_',
    'class_prefix' => 'BxPosts',

    /**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_posts_sounds_mp3',
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'register_transcoders' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Posts',

    /**
     * Files Section
     */
    'delete_files' => array(
        'js/categories.js',
    ),
);
