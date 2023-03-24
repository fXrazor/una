-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_albums' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_albums_enable_auto_approve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_albums_enable_auto_approve', 'on', @iCategId, '_bx_albums_option_enable_auto_approve', 'checkbox', '', '', '', '', 0);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_entry', 2, 'bx_albums', '', '_bx_albums_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:14:\"entity_reports\";}', '', 0, '', 0, 0, 1, 6);


UPDATE `sys_pages_blocks` SET `title_system`='_bx_albums_page_block_title_sys_favorites_entries' WHERE `object`='bx_albums_favorites' AND `title`='_bx_albums_page_block_title_favorites_entries';

-- MENUS

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view' AND `name`='approve';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view', 'bx_albums', 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', '', '', 2147483647, '', 1, 0, 40);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions' AND `name` IN ('approve', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions', 'bx_albums', 'approve', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 40),
('bx_albums_view_actions', 'bx_albums', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_albums&content_id={content_id}', '', '', 'history', '', '', '', 0, 192, '', 1, 0, 290);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_media' AND `name` IN ('delete-image', 'move-image');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_media', 'bx_albums', 'delete-image', '_bx_albums_menu_item_title_system_delete_image', '_bx_albums_menu_item_title_delete_image', 'javascript:void(0)', 'javascript:{js_object}.deleteMedia(this, {media_id});', '', 'remove', '', '', '', 2147483647, '', 1, 0, 40),
('bx_albums_view_media', 'bx_albums', 'move-image', '_bx_albums_menu_item_title_system_move_image', '_bx_albums_menu_item_title_move_image', 'javascript:void(0)', 'javascript:{js_object}.moveMedia(this, {media_id});', '', 'exchange-alt', '', '', '', 2147483647, '', 1, 0, 50);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media' AND `name` IN ('delete-image', 'move-image');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media', 'bx_albums', 'delete-image', '_bx_albums_menu_item_title_system_delete_image', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 40),
('bx_albums_view_actions_media', 'bx_albums', 'move-image', '_bx_albums_menu_item_title_system_move_image', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 50);


-- ACL
SET @iIdActionEntryDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_albums' AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionEntryDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_albums', 'delete any entry', NULL, '_bx_albums_acl_action_delete_any_entry', '', 1, 3);
SET @iIdActionEntryDeleteAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iAdministrator, @iIdActionEntryDeleteAny);


-- GRIDS:
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_albums_common' AND `name`='added';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_albums_common' AND `name`='status_admin';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_albums_common', 'status_admin', '_bx_albums_grid_column_title_adm_status_admin', '15%', 0, '16', '', 5);