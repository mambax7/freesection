<?php

/**
* $Id: search.inc.php,v 1.11 2007/01/22 21:05:28 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

function freesection_search($queryarray, $andor, $limit, $offset, $userid)
{
	include_once XOOPS_ROOT_PATH.'/modules/freesection/include/functions.php';

	$ret = array();

	if (!isset($freesection_item_handler)) {
		$freesection_item_handler = xoops_getmodulehandler('item', 'freesection');
	}

	if ($queryarray == '' || count($queryarray) == 0){
		$keywords= '';
		$hightlight_key = '';
	} else {
		$keywords=implode('+', $queryarray);
		$hightlight_key = "&amp;keywords=" . $keywords;
	}

	$itemsObj =& $freesection_item_handler->getItemsFromSearch($queryarray, $andor, $limit, $offset, $userid);

	$withCategoryPath = freesection_getConfig('catpath_search');

	foreach ($itemsObj as $result) {
		$item['image'] = "images/item_icon.gif";
		$item['link'] = "item.php?itemid=" . $result['id'] . $hightlight_key;
		if ($withCategoryPath) {
			$item['title'] = $result['categoryPath'] . $result['title'];
		} else {
			$item['title'] = "" . $result['title'];
		}
		$item['time'] = $result['datesub'];
		$item['uid'] = $result['uid'];
		$ret[] = $item;
		unset($item);
	}

	return $ret;
}

?>