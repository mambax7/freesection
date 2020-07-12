<?php

/**
* $Id: category-delete.php,v 1.2 2007/02/03 16:23:17 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU GPL
*/

include_once("admin_header.php");

global $freesection_category_handler;
global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
	
	$module_id = $xoopsModule->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	
	$categoryid = (isset($_POST['categoryid'])) ? intval($_POST['categoryid']) : 0;
	$categoryid = (isset($_GET['categoryid'])) ? intval($_GET['categoryid']) : $categoryid;
	
	$categoryObj = $freesection_category_handler->get($categoryid);
	
	$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
	$name = (isset($_POST['name'])) ? $_POST['name'] : '';
	
	if ($confirm) {
		if ( !$freesection_category_handler->delete($categoryObj)) {
			redirect_header("category.php", 1, _AM_FSECTION_DELETE_CAT_ERROR);
			exit;
		}
		
		redirect_header("category.php", 1, sprintf(_AM_FSECTION_COLISDELETED, $name));
		exit();
	} else {
		// no confirm: show deletion condition
		$categoryid = (isset($_GET['categoryid'])) ? intval($_GET['categoryid']) : 0;
		xoops_cp_header();
		xoops_confirm(array('op' => 'del', 'categoryid' => $categoryObj->categoryid(), 'confirm' => 1, 'name' => $categoryObj->name()), 'category.php', _AM_FSECTION_DELETECOL . " '" . $categoryObj->name() . "'. <br /> <br />" . _AM_FSECTION_DELETE_CAT_CONFIRM, _AM_FSECTION_DELETE);
		xoops_cp_footer();
	}
