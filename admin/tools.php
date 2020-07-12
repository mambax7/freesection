<?php

/**
* $Id: tools.php,v 1.2 2007/02/02 19:36:39 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once("admin_header.php");

$op = '';
if (isset($_POST['replacepermissions'])) {
	$op = 'replacepermissions';
}

switch ($op) {
	case 'replacepermissions' :
		$categoriesObj = $freesection_category_handler->getObjects();
		$groups_read = isset($_POST['groups_read']) ? $_POST['groups_read'] : array();
		foreach($categoriesObj as $categoryObj) {
			freesection_saveCategory_Permissions($groups_read, $categoryObj->categoryid(), 'category_read');
			freesection_overrideItemsPermissions($groups_read, $categoryObj->categoryid());
		}
		redirect_header("index.php", 3, _AM_FSECTION_PERMISSIONS_UPDATED);
		exit;


	break;

	case "default":
	default:
	freesection_xoops_cp_header();

	freesection_adminMenu(-1, _AM_FSECTION_TOOLS);

	freesection_collapsableBar('tools1', 'tools1icon', _AM_FSECTION_CONFIGURE_READ_PERMISSIONS, _AM_FSECTION_CONFIGURE_READ_PERMISSIONS_EXP);

	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

	$sform = new XoopsThemeForm(_AM_FSECTION_FULLACCESS , "form", xoops_getenv('PHP_SELF'));
	$sform->setExtra('enctype="multipart/form-data"');

	// READ PERMISSIONS
	$groups_read_checkbox = new XoopsFormCheckBox(_AM_FSECTION_PERMISSIONS_CAT_READ, 'groups_read[]');
	$member_handler =& xoops_gethandler('member');

	foreach ( $member_handler->getGroupList() as $group_id=>$group_name ) {
		if ($group_id != XOOPS_GROUP_ADMIN) {
			$groups_read_checkbox->addOption($group_id, $group_name);
		}
	}
	$sform->addElement($groups_read_checkbox);

	$button_tray = new XoopsFormElementTray('', '');

	$button_tray->addElement(new XoopsFormButton('', 'replacepermissions', _AM_FSECTION_REPLACE_PERMISSIONS, 'submit'));
	$sform->addElement($button_tray);
	$sform->display();

	freesection_close_collapsable('tools1', 'tools1icon');

	break;
}
freesection_modFooter();
xoops_cp_footer();

?>