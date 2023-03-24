<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Discussions',
    'version_from' => '11.0.3',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/forum/updates/update_11.0.3_12.0.0/',
    'home_uri' => 'forum_update_1103_1200',

    'module_dir' => 'boonex/forum/',
    'module_uri' => 'forum',

    'db_prefix' => 'bx_forum_',
    'class_prefix' => 'BxForum',

    /**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_forum_preview',
        'bx_forum_gallery',
        'bx_forum_cover',
        'bx_forum_preview_photos',
        'bx_forum_gallery_photos',
        'bx_forum_videos_poster',
        'bx_forum_videos_poster_preview',
        'bx_forum_videos_mp4',
        'bx_forum_videos_mp4_hd',
        'bx_forum_preview_files',
        'bx_forum_gallery_files'
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
    'language_category' => 'Discussions',

    /**
     * Files Section
     */
    'delete_files' => array(
        'js/categories.js',
    ),
);
