SET @sName = 'bx_forum';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_forum_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `media_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_links2content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `link_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link_id`, `content_id`)
);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name` IN ('cf', 'link', 'resolvable');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
(@sName, @sName, 'link', '', '', 0, 'custom', '_bx_forum_form_post_input_sys_link', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'resolvable', '1', '', 0, 'switcher', '_bx_forum_form_entry_input_sys_resolvable', '_bx_forum_form_entry_input_resolvable', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_forum_entry_add', 'bx_forum_entry_edit') AND `input_name`='link';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_forum_entry_add', 'bx_forum_entry_edit') AND `input_name`='cf';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_forum_entry_add', 'bx_forum_entry_edit') AND `input_name`='resolvable';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_entry_add', 'link', 2147483647, 1, 4),
('bx_forum_entry_add', 'cf', 2147483647, 1, 11),
('bx_forum_entry_add', 'resolvable', 2147483647, 1, 12),
('bx_forum_entry_edit', 'link', 2147483647, 1, 4),
('bx_forum_entry_edit', 'cf', 2147483647, 1, 11),
('bx_forum_entry_edit', 'resolvable', 2147483647, 1, 12);

DELETE FROM `sys_objects_form` WHERE `object`='bx_forum_attach_link';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_attach_link', 'bx_forum', '_bx_forum_form_attach_link', '', '', 'do_submit', 'bx_forum_links', 'id', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `object`='bx_forum_attach_link';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_forum_attach_link_add', 'bx_forum', 'bx_forum_attach_link', '_bx_forum_form_attach_link_display_add', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_forum_attach_link';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_forum_attach_link', 'bx_forum', 'content_id', '0', '', 0, 'hidden', '_bx_forum_form_attach_link_input_sys_content_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'url', '', '', 0, 'text', '_bx_forum_form_attach_link_input_sys_url', '_bx_forum_form_attach_link_input_url', '', 0, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:0:"";}', '_bx_forum_form_attach_link_input_url_err', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'do_submit', '_bx_forum_form_attach_link_input_do_submit', '', 0, 'submit', '_bx_forum_form_attach_link_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'do_cancel', '_bx_forum_form_attach_link_input_do_cancel', '', 0, 'button', '_bx_forum_form_attach_link_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_forum_attach_link_add';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_attach_link_add', 'content_id', 2147483647, 1, 1),
('bx_forum_attach_link_add', 'url', 2147483647, 1, 2),
('bx_forum_attach_link_add', 'controls', 2147483647, 1, 3),
('bx_forum_attach_link_add', 'do_submit', 2147483647, 1, 4),
('bx_forum_attach_link_add', 'do_cancel', 2147483647, 1, 5);


-- SCORES
UPDATE `sys_objects_score` SET `is_on`='1' WHERE `name`=@sName;


-- REPORTS
UPDATE `sys_objects_report` SET `object_comment`='bx_forum_notes' WHERE `name`='bx_forum';
