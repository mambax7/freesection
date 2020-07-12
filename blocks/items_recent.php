<?php

/**
* $Id: items_recent.php,v 1.11 2007/02/03 16:23:18 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
die("XOOPS root path not defined");
}

function freesection_items_recent_show($options)
{
	include_once(XOOPS_ROOT_PATH."/modules/freesection/include/common.php");

	$myts = &MyTextSanitizer::getInstance();

   	$smartModule =& freesection_getModuleInfo();

	$block = array();

	if ($options[0] == 0) {
		$categoryid = -1;
	} else {
		$categoryid = $options[0];
	}

	$sort = $options[1];
	$order = freesection_getOrderBy($sort);
	$limit = $options[2];
	$freesection_item_handler =& freesection_gethandler('item');
	// creating the ITEM objects that belong to the selected category
	$itemsObj = $freesection_item_handler->getAllPublished($limit, 0, $categoryid, $sort, $order);
	$totalItems = count($itemsObj);

	if ($itemsObj) {
		for ( $i = 0; $i < $totalItems; $i++ ) {

			$newItems['itemid'] = $itemsObj[$i]->itemid();
			$newItems['title'] = $itemsObj[$i]->title();
			$newItems['categoryname'] = $itemsObj[$i]->getCategoryName();
			$newItems['categoryid'] = $itemsObj[$i]->categoryid();
			$newItems['date'] = $itemsObj[$i]->datesub();
			$newItems['poster'] = xoops_getLinkedUnameFromId($itemsObj[$i]->uid());
			$newItems['itemlink'] = $itemsObj[$i]->getItemLink(false, isset($options[3]) ? $options[3] : 65);
			$newItems['categorylink'] = $itemsObj[$i]->getCategoryLink();

			$block['items'][] = $newItems;
		}

		$block['lang_title'] = _MB_FSECTION_ITEMS;
		$block['lang_category'] = _MB_FSECTION_CATEGORY;
		$block['lang_poster'] = _MB_FSECTION_POSTEDBY;
		$block['lang_date'] = _MB_FSECTION_DATE;
		$modulename = $myts->makeTboxData4Show($smartModule->getVar('name'));
		$block['lang_visitItem'] = _MB_FSECTION_VISITITEM . " " . $modulename;
	}

	return $block;
}

function freesection_items_recent_edit($options)
{
	include_once(XOOPS_ROOT_PATH."/modules/freesection/include/functions.php");

	$form = freesection_createCategorySelect($options[0]);

	$form .= "&nbsp;<br>" . _MB_FSECTION_ORDER . "&nbsp;<select name='options[]'>";

	$form .= "<option value='datesub'";
	if ($options[1] == "datesub") {
		$form .= " selected='selected'";
	}
	$form .= ">" . _MB_FSECTION_DATE . "</option>\n";

	$form .= "<option value='counter'";
	if ($options[1] == "counter") {
		$form .= " selected='selected'";
	}
	$form .= ">" . _MB_FSECTION_HITS . "</option>\n";

	$form .= "<option value='weight'";
	if ($options[1] == "weight") {
		$form .= " selected='selected'";
	}
	$form .= ">" . _MB_FSECTION_WEIGHT . "</option>\n";

	$form .= "</select>\n";

	$form .= "&nbsp;" . _MB_FSECTION_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "' />&nbsp;" . _MB_FSECTION_ITEMS . "<br />";
	$form .= _MB_FSECTION_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[3] . "' />&nbsp;chars";

	return $form;
}

?>