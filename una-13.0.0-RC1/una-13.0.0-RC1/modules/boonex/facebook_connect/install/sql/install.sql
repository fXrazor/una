
CREATE TABLE `bx_facebook_accounts` (
  `id_profile` int(10) unsigned NOT NULL,
  `fb_profile` bigint(20) NOT NULL,
  PRIMARY KEY (`id_profile`),
  KEY `fb_profile` (`fb_profile`)
);

-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_facebook', '_bx_facebook', '_bx_facebook', 'bx_facebook@modules/boonex/facebook_connect/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_facebook', 'integrations', '{url_studio}module.php?name=bx_facebook', '', 'bx_facebook@modules/boonex/facebook_connect/|std-icon.svg', '_bx_facebook', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

