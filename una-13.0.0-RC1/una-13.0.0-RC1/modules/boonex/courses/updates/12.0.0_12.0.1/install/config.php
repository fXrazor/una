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
    'version_from' => '12.0.0',
    'version_to' => '12.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.1.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/courses/updates/update_12.0.0_12.0.1/',
    'home_uri' => 'courses_update_1200_1201',

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
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Courses',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
