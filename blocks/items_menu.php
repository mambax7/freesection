<?php

/**
* $Id: items_menu.php,v 1.8 2007/02/03 16:23:18 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
die("XOOPS root path not defined");
}

function freesection_items_menu_show($options)
{	
	include_once(XOOPS_ROOT_PATH."/modules/freesection/include/common.php");	
	
	$block = array();
	/*if ($options[0] == 0) {
		$categoryid = -1;
	} else {
		$categoryid = $options[0];
	}

	$sort = $options[1];
	$order = freesection_getOrderBy($sort);				
	$limit = $options[2];
	*/
	$freesection_item_handler =& freesection_gethandler('item');
	$freesection_category_handler =& freesection_gethandler('category');
	
	// Are we in FreeSection ?
	global $xoopsModule;
	
	$block['inModule'] = (isset($xoopsModule) && ($xoopsModule->getVar('dirname') == 'freesection'));
	
	$catlink_class = 'menuMain';
	
	$categoryid = 0;
	
	if ($block['inModule']) {
		// Are we in a category and if yes, in which one ?
		$categoryid = isset($_GET['categoryid']) ? $_GET['categoryid'] : 0;
		
		if ($categoryid != 0) {
			// if we are in a category, then the $categoryObj is already defined in freesection/category.php
			global $categoryObj;
			$block['currentcat'] = $categoryObj->getCategoryLink('menuTop');
			$catlink_class = 'menuSub';
		}
	}
	
	// Getting all top cats
	$block_categoriesObj = $freesection_category_handler->getCategories(0, 0, $categoryid);

	$array_categoryids = array_keys($block_categoriesObj);
	$categoryids = implode(', ', $array_categoryids);
	
	foreach ($block_categoriesObj as $categoryid => $block_categoryObj) {
		$block['categories'][$categoryid]['categoryLink'] = $block_categoryObj->getCategoryLink($catlink_class);
	}

	return $block;	
} 

function freesection_items_menu_edit($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser;
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

    $form .= "&nbsp;" . _MB_FSECTION_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "' />&nbsp;" . _MB_FSECTION_ITEMS . "";

    return $form;
} 

?>