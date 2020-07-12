<?php
/**
* $Id: menu.php,v 1.8 2006/09/12 15:58:56 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

$i = 0;

// Index
$adminmenu[$i]['title'] = _MI_FSECTION_ADMENU1;
$adminmenu[$i]['link'] = "admin/index.php";
$i++;
// Category
$adminmenu[$i]['title'] = _MI_FSECTION_ADMENU2;
$adminmenu[$i]['link'] = "admin/category.php";
$i++;
// Items
$adminmenu[$i]['title'] = _MI_FSECTION_ADMENU3;
$adminmenu[$i]['link'] = "admin/item.php";
$i++;
// Permissions
$adminmenu[$i]['title'] = _MI_FSECTION_ADMENU4;
$adminmenu[$i]['link'] = "admin/permissions.php";
$i++;
// Mimetypes
$adminmenu[$i]['title'] = _MI_FSECTION_ADMENU6;
$adminmenu[$i]['link'] = "admin/mimetypes.php";


if (isset($xoopsModule)) {
	$i = 0;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
	$i++;

	$headermenu[$i]['title'] = _AM_FSECTION_GOMOD;
	$headermenu[$i]['link'] = FREESECTION_URL;
	$i++;

	$headermenu[$i]['title'] = _AM_FSECTION_UPDATE_MODULE;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
	$i++;

	if (FREESECTION_LEVEL > 0 ) {
		$headermenu[$i]['title'] = _AM_FSECTION_IMPORT;
		$headermenu[$i]['link'] = FREESECTION_URL . "admin/import.php";
		$i++;
	}

	$headermenu[$i]['title'] = _AM_FSECTION_ABOUT;
	$headermenu[$i]['link'] = FREESECTION_URL . "admin/about.php";
}
?>
