<?php

/**
* $Id: header.php,v 1.7 2006/05/26 17:35:21 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once "../../mainfile.php";

if( !defined("FREESECTION_DIRNAME") ){
	define("FREESECTION_DIRNAME", 'freesection');
}

include_once XOOPS_ROOT_PATH.'/modules/' . FREESECTION_DIRNAME . '/include/common.php';

include_once XOOPS_ROOT_PATH."/class/pagenav.php";
$myts = MyTextSanitizer::getInstance();

?>