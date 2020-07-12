<?php

/**
* $Id: footer.php,v 1.23 2006/11/02 21:44:22 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

global $xoopsModule, $xoopsModuleConfig;
$uid = ($xoopsUser) ? ($xoopsUser->getVar("uid")) : 0;

$xoopsTpl->assign("freesection_adminpage", "<a href='" . XOOPS_URL . "/modules/freesection/admin/index.php'>" ._MD_FSECTION_ADMIN_PAGE . "</a>");
$xoopsTpl->assign("isAdmin", $freesection_isAdmin);
$xoopsTpl->assign('freesection_url', FREESECTION_URL);
$xoopsTpl->assign('freesection_images_url', FREESECTION_IMAGES_URL);

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="' . FREESECTION_URL . 'module.css" />');

$xoopsTpl->assign('lang_total', _MD_FSECTION_TOTAL_SMARTITEMS);
$xoopsTpl->assign('lang_home', _MD_FSECTION_HOME);
$xoopsTpl->assign('lang_description', _MD_FSECTION_DESCRIPTION);
$xoopsTpl->assign('displayType', $xoopsModuleConfig['displaytype']);
// display_category_summary enabled by Freeform Solutions March 21 2006
$xoopsTpl->assign('display_category_summary', $xoopsModuleConfig['display_category_summary']);
$xoopsTpl->assign('displayList', $xoopsModuleConfig['displaytype']=='list');
$xoopsTpl->assign('displayFull', $xoopsModuleConfig['displaytype']=='full');
$xoopsTpl->assign('modulename', $xoopsModule->dirname());
$xoopsTpl->assign('displaylastitem', $xoopsModuleConfig['displaylastitem']);
$xoopsTpl->assign('displaysubcatdsc', $xoopsModuleConfig['displaysubcatdsc']);
$xoopsTpl->assign('collapsable_heading', $xoopsModuleConfig['collapsable_heading']);
$xoopsTpl->assign('display_comment_link', $xoopsModuleConfig['display_comment_link']);
$xoopsTpl->assign('display_whowhen_link', $xoopsModuleConfig['display_whowhen_link']);
$xoopsTpl->assign('displayarticlescount', $xoopsModuleConfig['displayarticlescount']);

$xoopsTpl->assign('display_date_col', $xoopsModuleConfig['display_date_col']);
$xoopsTpl->assign('display_hits_col', $xoopsModuleConfig['display_hits_col']);

$xoopsTpl->assign('category_list_image_width', $xoopsModuleConfig['category_list_image_width']);
$xoopsTpl->assign('category_main_image_width', $xoopsModuleConfig['category_main_image_width']);


$xoopsTpl->assign('lang_reads', _MD_FSECTION_READS);
$xoopsTpl->assign('lang_items', _MD_FSECTION_ITEMS);
$xoopsTpl->assign('lang_last_freesection', _MD_FSECTION_LAST_SMARTITEM);
$xoopsTpl->assign('lang_category_column', _MD_FSECTION_CATEGORY );
$xoopsTpl->assign('lang_editcategory', _MD_FSECTION_CATEGORY_EDIT);
$xoopsTpl->assign('lang_comments', _MD_FSECTION_COMMENTS);
$xoopsTpl->assign('lang_view_more',_MD_FSECTION_VIEW_MORE);
$xoopsTpl->assign("ref_smartfactory", "FreeSection is developed by The SmartFactory (http://www.smartfactory.ca), a division of INBOX Solutions (http://inboxinternational.com)");
?>