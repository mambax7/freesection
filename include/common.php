<?php

/**
* $Id: common.php,v 1.25 2007/01/22 21:05:28 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("FREESECTION_DIRNAME") ){
	define("FREESECTION_DIRNAME", 'freesection');
}

if( !defined("FREESECTION_URL") ){
	define("FREESECTION_URL", XOOPS_URL.'/modules/'.FREESECTION_DIRNAME.'/');
}
if( !defined("FREESECTION_ROOT_PATH") ){
	define("FREESECTION_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.FREESECTION_DIRNAME.'/');
}

if( !defined("FREESECTION_IMAGES_URL") ){
	define("FREESECTION_IMAGES_URL", FREESECTION_URL.'/images/');
}

/** Configuring the module level of available features
 *
 * 0  = light mode
 * 10 = full mode
 */
if( !defined("FREESECTION_LEVEL") ){
	define("FREESECTION_LEVEL", 10);
}


// include common language files
global $xoopsConfig;
$common_lang_file = FREESECTION_ROOT_PATH . "language/" . $xoopsConfig['language'] . "/common.php";
if (!file_exists($common_lang_file)) {
	$common_lang_file = FREESECTION_ROOT_PATH . "language/english/common.php";
}
include_once($common_lang_file);

include_once(FREESECTION_ROOT_PATH . "include/functions.php");

// Check XOOPS version to see if we are on XOOPS 2.2.x plateform
$xoops22 = freesection_isXoops22();

include_once(FREESECTION_ROOT_PATH . "include/seo_functions.php");
include_once(FREESECTION_ROOT_PATH . "class/keyhighlighter.class.php");

// Creating the SmartModule object
$smartModule =& freesection_getModuleInfo();

// Find if the user is admin of the module
$freesection_isAdmin = freesection_userIsAdmin();

$freesection_moduleName = $smartModule->getVar('name');

// Creating the SmartModule config Object
$smartConfig =& freesection_getModuleConfig();

if (!defined('SMARTOBJECT_ROOT_PATH')) {
	include_once FREESECTION_ROOT_PATH . "class/smartmetagen.php";
}
include_once(FREESECTION_ROOT_PATH . "class/permission.php");
include_once(FREESECTION_ROOT_PATH . "class/category.php");
include_once(FREESECTION_ROOT_PATH . "class/item.php");
include_once(FREESECTION_ROOT_PATH . "class/file.php");
include_once(FREESECTION_ROOT_PATH . "class/session.php");

// Creating the item handler object
$freesection_item_handler =& xoops_getmodulehandler('item', FREESECTION_DIRNAME);

// Creating the category handler object
$freesection_category_handler =& xoops_getmodulehandler('category', FREESECTION_DIRNAME);

// Creating the permission handler object
$freesection_permission_handler =& xoops_getmodulehandler('permission', FREESECTION_DIRNAME);

// Creating the file handler object
$freesection_file_handler =& xoops_getmodulehandler('file', FREESECTION_DIRNAME);

// get current page
$freesection_current_page = freesection_getCurrentPage();

?>