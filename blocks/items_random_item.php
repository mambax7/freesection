<?php

/**
* $Id: items_random_item.php,v 1.11 2007/02/03 16:23:18 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
die("XOOPS root path not defined");
}

function freesection_items_random_item_show($options)
{
	include_once(XOOPS_ROOT_PATH."/modules/freesection/include/common.php");

    $block = array();
	$freesection_item_handler =& freesection_gethandler('item');
    // creating the ITEM object
    $itemsObj = $freesection_item_handler->getRandomItem('summary', array(_FSECTION_STATUS_PUBLISHED));

    if ($itemsObj) {
       	$block['content'] = $itemsObj->summary();
       	$block['id'] = $itemsObj->itemid();
       	$block['url'] = $itemsObj->getItemUrl();
       	$block['lang_fullitem'] = _MB_FSECTION_FULLITEM;
    }

    return $block;
}

?>