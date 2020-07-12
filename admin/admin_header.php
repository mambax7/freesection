<?php

/**
* $Id: admin_header.php,v 1.9 2006/02/13 20:54:58 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once "../../../mainfile.php";

if (!defined("FREESECTION_NOCPFUNC")) {
	include_once '../../../include/cp_header.php';
}

include_once XOOPS_ROOT_PATH . "/class/xoopsmodule.php";
include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

include XOOPS_ROOT_PATH.'/modules/freesection/include/common.php';

if( !defined("FREESECTION_ADMIN_URL") ){
	define('FREESECTION_ADMIN_URL', FREESECTION_URL . "admin");
}

$imagearray = array(
	'editimg' => "<img src='". FREESECTION_IMAGES_URL ."/button_edit.png' alt='" . _AM_FSECTION_ICO_EDIT . "' align='middle' />",
    'deleteimg' => "<img src='". FREESECTION_IMAGES_URL ."/button_delete.png' alt='" . _AM_FSECTION_ICO_DELETE . "' align='middle' />",
    'online' => "<img src='". FREESECTION_IMAGES_URL ."/on.png' alt='" . _AM_FSECTION_ICO_ONLINE . "' align='middle' />",
    'offline' => "<img src='". FREESECTION_IMAGES_URL ."/off.png' alt='" . _AM_FSECTION_ICO_OFFLINE . "' align='middle' />",
	);

$myts = &MyTextSanitizer::getInstance();

?>