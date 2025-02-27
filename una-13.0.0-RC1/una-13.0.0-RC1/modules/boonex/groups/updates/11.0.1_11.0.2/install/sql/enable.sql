-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_view_profile' AND `title`='_bx_groups_page_block_title_entry_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_groups_view_profile', 3, 'bx_groups', '', '_bx_groups_page_block_title_entry_context', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:14:\"entity_context\";}', '', 0, 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_context';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_context', 'groups-context', '_bx_groups_page_title_sys_entries_in_context', '_bx_groups_page_title_entries_in_context', 'bx_groups', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxGroupsPageEntriesInContext', 'modules/boonex/groups/classes/BxGroupsPageEntriesInContext.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_context', 1, 'bx_groups', '_bx_groups_page_block_title_sys_entries_in_context', '_bx_groups_page_block_title_entries_in_context_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:14:"browse_context";}', '', 0, 0, 0, 1, 1);


DELETE FROM `sys_pages_blocks` WHERE `module`='bx_groups' AND `title_system`='' AND `title`='_bx_groups_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `order`, `active`) VALUES
('trigger_page_group_view_entry', 4, 'bx_groups', '', '_bx_groups_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:14:"browse_context";s:6:"params";a:1:{s:10:"context_id";s:12:"{profile_id}";}}', '', 0, 0, 0, 1, 0);


-- MENUS
UPDATE `sys_menu_items` SET `visibility_custom`='a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:19:"is_badges_avaliable";}' WHERE `set_name`='bx_groups_view_actions_all' AND `name`='profile-set-badges';

DELETE FROM `sys_menu_items` WHERE `module`='bx_groups' AND `title`='_bx_groups_menu_item_title_view_entries_in_context';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_submenu', 'bx_groups', 'groups-context', '_bx_groups_menu_item_title_system_view_entries_in_context', '_bx_groups_menu_item_title_view_entries_in_context', 'page.php?i=groups-context&profile_id={profile_id}', '', '', 'users col-red2', '', '', 2147483647, 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"is_enable_for_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 0);


-- PRIVACY
UPDATE `sys_objects_privacy` SET `spaces`='bx_events,bx_spaces' WHERE `object`='bx_groups_allow_view_to';
