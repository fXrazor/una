
-- TABLES: entries

DROP TABLE IF EXISTS `bx_convos_conversations`, `bx_convos_conv2folder`, `bx_convos_folders`, `bx_convos_files`, `bx_convos_photos_resized`, `bx_convos_cmts`, `bx_convos_views_track`;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_convos_files' OR `object` = 'bx_convos_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_convos_files' OR `object` = 'bx_convos_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` = 'bx_convos_preview';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'bx_convos_preview';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = 'bx_convos_preview';

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_convos';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_convos';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_convos';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_convos_entry_add', 'bx_convos_entry_edit', 'bx_convos_entry_view', 'bx_convos_entry_delete');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_convos';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_convos';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_convos';
