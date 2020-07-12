<?php

/**
* $Id: blocks.php,v 1.13 2007/02/03 16:23:35 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

/*global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
if (isset($xoopsModuleConfig) && isset($xoopsModule) && $xoopsModule->getVar('dirname') == 'freesection') {
	$itemType = $xoopsModuleConfig['itemtype'];
} else {
	$hModule = &xoops_gethandler('module');
	$hModConfig = &xoops_gethandler('config');
	if ($freesection_Module = &$hModule->getByDirname('freesection')) {
		$module_id = $freesection_Module->getVar('mid');
		$freesection_Config = &$hModConfig->getConfigsByCat(0, $freesection_Module->getVar('mid'));
		$itemType = $freesection_Config['itemtype'];
	} else {
		$itemType = 'article';
	}
}

include_once(XOOPS_ROOT_PATH . "/modules/freesection/language/" . $xoopsConfig['language'] . "/plugin/" . $itemType . "/blocks.php");
*/
// Blocks

define("_MB_FSECTION_ALLCAT", "All categories");
define("_MB_FSECTION_AUTO_LAST_ITEMS", "Automatically display last item(s)?");
define("_MB_FSECTION_CATEGORY", "Category");
define("_MB_FSECTION_CHARS", "Length of the title");
define("_MB_FSECTION_COMMENTS", "Comment(s)");
define("_MB_FSECTION_DATE", "Published date");
define("_MB_FSECTION_DISP", "Display");
define("_MB_FSECTION_DISPLAY_CATEGORY", "Display the category name?");
define("_MB_FSECTION_DISPLAY_COMMENTS", "Display comment count?");
define("_MB_FSECTION_DISPLAY_TYPE", "Display type :");
define("_MB_FSECTION_DISPLAY_TYPE_BLOCK", "Each item is a block");
define("_MB_FSECTION_DISPLAY_TYPE_BULLET", "Each item is a bullet");
define("_MB_FSECTION_DISPLAY_WHO_AND_WHEN", "Display the poster and date?");
define("_MB_FSECTION_FULLITEM", "Read the complete article");
define("_MB_FSECTION_HITS", "Number of hits");
define("_MB_FSECTION_ITEMS", "Articles");
define("_MB_FSECTION_LAST_ITEMS_COUNT", "if yes, how many items to display?");
define("_MB_FSECTION_LENGTH", " characters");
define("_MB_FSECTION_ORDER", "Display order");
define("_MB_FSECTION_POSTEDBY", "Published by");
define("_MB_FSECTION_READMORE", "Read more...");
define("_MB_FSECTION_READS", "reads");
define("_MB_FSECTION_SELECT_ITEMS", "if no, select the articles to be displayed :");
define("_MB_FSECTION_SELECTCAT", "Display the articles of :");
define("_MB_FSECTION_VISITITEM", "Visit the");
define("_MB_FSECTION_WEIGHT", "List by weight");
define("_MB_FSECTION_WHO_WHEN", "Published by %s on %s");
//bd tree block hack
define("_MB_FSECTION_LEVELS", "levels");
define("_MB_FSECTION_CURRENTCATEGORY", "Current Category");
define("_MB_FSECTION_ASC", "ASC");
define("_MB_FSECTION_DESC", "DESC");
define("_MB_FSECTION_SHOWITEMS", "Show Items");
//--/bd

define("_MB_FSECTION_FILES", "files");
define("_MB_FSECTION_DIRECTDOWNLOAD", "Direct link to dowload the file instead of a link to the article?");

?>