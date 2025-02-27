SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_block' AND `name`='class';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_block', @sName, 'class', '', '', 0, 'text', '_bx_dev_bp_txt_sys_block_class', '_bx_dev_bp_txt_block_class', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_block_edit' AND `input_name`='class';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_block_edit', 'class', 2147483647, 1, 12);
