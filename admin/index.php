<?php

/**
* $Id: index.php,v 1.34 2007/02/02 19:36:39 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once("admin_header.php");
$myts = &MyTextSanitizer::getInstance();

$itemid = isset($_POST['itemid']) ? intval($_POST['itemid']) : 0;

$pick = isset($_GET['pick']) ? intval($_GET['pick']) : 0;
$pick = isset($_POST['pick']) ? intval($_POST['pick']) : $pick;

$statussel = isset($_GET['statussel']) ? intval($_GET['statussel']) : 0;
$statussel = isset($_POST['statussel']) ? intval($_POST['statussel']) : $statussel;

$sortsel = isset($_GET['sortsel']) ? $_GET['sortsel'] : 'itemid';
$sortsel = isset($_POST['sortsel']) ? $_POST['sortsel'] : $sortsel;

$ordersel = isset($_GET['ordersel']) ? $_GET['ordersel'] : 'DESC';
$ordersel = isset($_POST['ordersel']) ? $_POST['ordersel'] : $ordersel;

$module_id = $xoopsModule->getVar('mid');
$gperm_handler = &xoops_gethandler('groupperm');
$groups = ($xoopsUser) ? ($xoopsUser->getGroups()) : XOOPS_GROUP_ANONYMOUS;

// auto crate folders----------------------------------------

function createDir(){
// auto crate folders
$thePath = freesection_getUploadDir();

	if(freesection_admin_getPathStatus('root', true) < 0){
		$thePath = freesection_getUploadDir();
		$res = freesection_admin_mkdir($thePath);
		$msg = ($res)?_AM_FSECTION_DIRCREATED:_AM_FSECTION_DIRNOTCREATED;
	}

	if(freesection_admin_getPathStatus('images', true) < 0){
		$thePath = freesection_getImageDir();
		$res = freesection_admin_mkdir($thePath);

		if ($res) {
			$source = FREESECTION_ROOT_PATH . "images/blank.png";
			$dest = $thePath . "blank.png";
			freesection_copyr($source, $dest);
		}
		$msg = ($res)?_AM_FSECTION_DIRCREATED:_AM_FSECTION_DIRNOTCREATED;
	}

	if(freesection_admin_getPathStatus('images/category', true) < 0){
		$thePath = freesection_getImageDir('category');
		$res = freesection_admin_mkdir($thePath);

		if ($res) {
			$source = FREESECTION_ROOT_PATH . "images/blank.png";
			$dest = $thePath . "blank.png";
			freesection_copyr($source, $dest);
		}
		$msg = ($res)?_AM_FSECTION_DIRCREATED:_AM_FSECTION_DIRNOTCREATED;
	}

	if(freesection_admin_getPathStatus('images/item', true) < 0){
		$thePath = freesection_getImageDir('item');
		$res = freesection_admin_mkdir($thePath);

		if ($res) {
			$source = FREESECTION_ROOT_PATH . "images/blank.png";
			$dest = $thePath . "blank.png";
			freesection_copyr($source, $dest);
		}
		$msg = ($res)?_AM_FSECTION_DIRCREATED:_AM_FSECTION_DIRNOTCREATED;
	}

	if(freesection_admin_getPathStatus('content', true) < 0){
		$thePath = freesection_getUploadDir(true, 'content');
		$res = freesection_admin_mkdir($thePath);
		$msg = ($res)?_AM_FSECTION_DIRCREATED:_AM_FSECTION_DIRNOTCREATED;
	}
}
//----------------------------------------------------------



function buildTable()
{
	global $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
	echo "<tr>";
	echo "<td width='40px' class='bg3' align='center'><b>" . _AM_FSECTION_ITEMID . "</b></td>";
	echo "<td width='100px' class='bg3' align='center'><b>" . _AM_FSECTION_ITEMCAT . "</b></td>";
	echo "<td class='bg3' align='center'><b>" . _AM_FSECTION_TITLE . "</b></td>";
	echo "<td width='90px' class='bg3' align='center'><b>" . _AM_FSECTION_CREATED . "</b></td>";
	echo "<td width='90px' class='bg3' align='center'><b>" . _AM_FSECTION_STATUS . "</b></td>";
	echo "<td width='90px' class='bg3' align='center'><b>" . _AM_FSECTION_ACTION . "</b></td>";
	echo "</tr>";
}
// Code for the page
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

global $freesection_category_handler, $freesection_item_handler;

$startentry = isset($_GET['startentry']) ? intval($_GET['startentry']) : 0;

freesection_xoops_cp_header();

global $xoopsUser, $xoopsConfig,$xoopsModuleConfig, $xoopsModule;

freesection_adminMenu(0, _AM_FSECTION_INDEX);

// Total ITEMs -- includes everything on the table
$totalitems = $freesection_item_handler->getItemsCount();

// Total categories
$totalcategories = $freesection_category_handler->getCategoriesCount(-1);

// Total submitted ITEMs
$totalsubmitted = $freesection_item_handler->getItemsCount(-1, array(_FSECTION_STATUS_SUBMITTED));

// Total published ITEMs
$totalpublished = $freesection_item_handler->getItemsCount(-1, array(_FSECTION_STATUS_PUBLISHED));

// Total offline ITEMs
$totaloffline = $freesection_item_handler->getItemsCount(-1, array(_FSECTION_STATUS_OFFLINE));

// Total rejected
$totalrejected = $freesection_item_handler->getItemsCount(-1, array(_FSECTION_STATUS_REJECTED));


// Check Path Configuration
if ((freesection_admin_getPathStatus('root', true) < 0) ||
(freesection_admin_getPathStatus('images', true) < 0) ||
(freesection_admin_getPathStatus('images/category', true) < 0) ||
(freesection_admin_getPathStatus('images/item', true) < 0) ||
(freesection_admin_getPathStatus('content', true) < 0)
) {

	createDir();
}

// -- //
freesection_collapsableBar('inventorytable', 'inventoryicon', _AM_FSECTION_INVENTORY);
echo "<br />";
echo "<table width='100%' class='outer' cellspacing='1' cellpadding='3' border='0' ><tr>";
echo "<td class='head'>" . _AM_FSECTION_TOTALCAT . "</td><td align='center' class='even'>" . $totalcategories . "</td>";
echo "<td class='head'>" . _AM_FSECTION_TOTALSUBMITTED . "</td><td align='center' class='even'>" . $totalsubmitted . "</td>";
echo "<td class='head'>" . _AM_FSECTION_TOTALPUBLISHED . "</td><td align='center' class='even'>" . $totalpublished . "</td>";
echo "<td class='head'>" . _AM_FSECTION_TOTAL_OFFLINE . "</td><td align='center' class='even'>" . $totaloffline . "</td>";
echo "</tr></table>";
echo "<br />";

echo "<form><div style=\"margin-bottom: 12px;\">";
echo "<input type='button' name='button' onclick=\"location='category.php?op=mod'\" value='" . _AM_FSECTION_CATEGORY_CREATE . "'>&nbsp;&nbsp;";
echo "<input type='button' name='button' onclick=\"location='item.php?op=mod'\" value='" . _AM_FSECTION_CREATEITEM . "'>&nbsp;&nbsp;";
echo "</div></form>";

freesection_close_collapsable('inventorytable', 'inventoryicon');

// Construction of lower table
freesection_collapsableBar('allitemstable', 'allitemsicon', _AM_FSECTION_ALLITEMS, _AM_FSECTION_ALLITEMSMSG);

$showingtxt = '';
$selectedtxt = '';
$cond = "";
$selectedtxt0 = '';
$selectedtxt1 = '';
$selectedtxt2 = '';
$selectedtxt3 = '';
$selectedtxt4 = '';

$sorttxttitle = "";
$sorttxtcreated = "";
$sorttxtweight = "";
$sorttxtitemid = "";

$ordertxtasc='';
$ordertxtdesc='';

switch ($sortsel) {
	case 'title':
	$sorttxttitle = "selected='selected'";
	break;

	case 'datesub':
	$sorttxtcreated = "selected='selected'";
	break;

	case 'weight':
	$sorttxtweight = "selected='selected'";
	break;

	default :
	$sorttxtitemid = "selected='selected'";
	break;
}

switch ($ordersel) {
	case 'ASC':
	$ordertxtasc = "selected='selected'";
	break;

	default :
	$ordertxtdesc = "selected='selected'";
	break;
}

switch ($statussel) {
	case _FSECTION_STATUS_ALL :
	$selectedtxt0 = "selected='selected'";
	$caption = _AM_FSECTION_ALL;
	$cond = "";
	$status_explaination = _AM_FSECTION_ALL_EXP;
	break;

	case _FSECTION_STATUS_SUBMITTED :
	$selectedtxt1 = "selected='selected'";
	$caption = _AM_FSECTION_SUBMITTED;
	$cond = " WHERE status = " . _FSECTION_STATUS_SUBMITTED . " ";
	$status_explaination = _AM_FSECTION_SUBMITTED_EXP;
	break;

	case _FSECTION_STATUS_PUBLISHED :
	$selectedtxt2 = "selected='selected'";
	$caption = _AM_FSECTION_PUBLISHED;
	$cond = " WHERE status = " . _FSECTION_STATUS_PUBLISHED . " ";
	$status_explaination = _AM_FSECTION_PUBLISHED_EXP;
	break;

	case _FSECTION_STATUS_OFFLINE :
	$selectedtxt3 = "selected='selected'";
	$caption = _AM_FSECTION_OFFLINE;
	$cond = " WHERE status = " . _FSECTION_STATUS_OFFLINE . " ";
	$status_explaination = _AM_FSECTION_OFFLINE_EXP;
	break;

	case _FSECTION_STATUS_REJECTED :
	$selectedtxt4 = "selected='selected'";
	$caption = _AM_FSECTION_REJECTED;
	$cond = " WHERE status = " . _FSECTION_STATUS_REJECTED . " ";
	$status_explaination = _AM_FSECTION_REJECTED_ITEM_EXP;
	break;
}

/* -- Code to show selected terms -- */
echo "<form name='pick' id='pick' action='" . $_SERVER['PHP_SELF'] . "' method='POST' style='margin: 0;'>";

echo "
	<table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-left: 1px solid silver; border-top: 1px solid silver; border-right: 1px solid silver;'>
		<tr>
			<td><span style='font-weight: bold; font-variant: small-caps;'>" . _AM_FSECTION_SHOWING . " " . $caption . "</span></td>
			<td align='right'>" . _AM_FSECTION_SELECT_SORT . "
				<select name='sortsel' onchange='submit()'>
					<option value='itemid' $sorttxtitemid>" . _AM_FSECTION_ID . "</option>
					<option value='title' $sorttxttitle>" . _AM_FSECTION_TITLE . "</option>
					<option value='datesub' $sorttxtcreated>" . _AM_FSECTION_CREATED . "</option>
					<option value='weight' $sorttxtweight>" . _AM_FSECTION_WEIGHT . "</option>
				</select>
				<select name='ordersel' onchange='submit()'>
					<option value='ASC' $ordertxtasc>" . _AM_FSECTION_ASC . "</option>
					<option value='DESC' $ordertxtdesc>" . _AM_FSECTION_DESC . "</option>
				</select>
			" . _AM_FSECTION_SELECT_STATUS . " :
				<select name='statussel' onchange='submit()'>
					<option value='0' $selectedtxt0>" . _AM_FSECTION_ALL . " [$totalitems]</option>
					<option value='1' $selectedtxt1>" . _AM_FSECTION_SUBMITTED . " [$totalsubmitted]</option>
					<option value='2' $selectedtxt2>" . _AM_FSECTION_PUBLISHED . " [$totalpublished]</option>
					<option value='3' $selectedtxt3>" . _AM_FSECTION_OFFLINE . " [$totaloffline]</option>
					<option value='4' $selectedtxt4>" . _AM_FSECTION_REJECTED . " [$totalrejected]</option>
				</select>
			</td>
		</tr>
	</table>
	</form>";


// Get number of entries in the selected state
$statusSelected = ($statussel == 0) ? -1 : $statussel;

$numrows = $freesection_item_handler->getItemsCount(-1, $statusSelected);
// creating the Q&As objects
$itemsObj = $freesection_item_handler->getItems($xoopsModuleConfig['perpage'], $startentry, $statusSelected, -1, $sortsel, $ordersel);

$totalItemsOnPage = count($itemsObj);

buildTable();

if ($numrows > 0) {

	for ( $i = 0; $i < $totalItemsOnPage; $i++ ) {
		// Creating the category object to which this item is linked
		$categoryObj =& $itemsObj[$i]->category();
		$approve = '';

		switch ($itemsObj[$i]->status()) {

			case _FSECTION_STATUS_SUBMITTED :
			$statustxt = _AM_FSECTION_SUBMITTED;
			$approve = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/approve.gif' title='" . _AM_FSECTION_SUBMISSION_MODERATE . "' alt='" . _AM_FSECTION_SUBMISSION_MODERATE . "' /></a>&nbsp;";
			$clone = '';
			$delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_FSECTION_DELETEITEM . "' alt='" . _AM_FSECTION_DELETEITEM . "' /></a>";
			$modify = "";
			break;

			case _FSECTION_STATUS_PUBLISHED :
			$statustxt = _AM_FSECTION_PUBLISHED;
			$approve = "";
			$clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/clone.gif' title='" . _AM_FSECTION_CLONE_ITEM . "' alt='" .  _AM_FSECTION_CLONE_ITEM  . "' /></a>&nbsp;";
			$modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_FSECTION_ITEM_EDIT . "' alt='" . _AM_FSECTION_ITEM_EDIT . "' /></a>&nbsp;";
			$delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_FSECTION_DELETEITEM . "' alt='" . _AM_FSECTION_DELETEITEM . "' /></a>";
			break;

			case _FSECTION_STATUS_OFFLINE :
			$statustxt = _AM_FSECTION_OFFLINE;
			$approve = "";
			$clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/clone.gif' title='" . _AM_FSECTION_CLONE_ITEM . "' alt='" .  _AM_FSECTION_CLONE_ITEM  . "' /></a>&nbsp;";
			$modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_FSECTION_ITEM_EDIT . "' alt='" . _AM_FSECTION_ITEM_EDIT . "' /></a>&nbsp;";
			$delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_FSECTION_DELETEITEM . "' alt='" . _AM_FSECTION_DELETEITEM . "' /></a>";
			break;

			case _FSECTION_STATUS_REJECTED :
			$statustxt = _AM_FSECTION_REJECTED;
			$approve = "";
			$clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/clone.gif' title='" . _AM_FSECTION_CLONE_ITEM . "' alt='" . _AM_FSECTION_CLONE_ITEM . "' /></a>&nbsp;";
			$modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_FSECTION_REJECTED_EDIT . "' alt='" . _AM_FSECTION_REJECTED_EDIT . "' /></a>&nbsp;";
			$delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_FSECTION_DELETEITEM . "' alt='" . _AM_FSECTION_DELETEITEM . "' /></a>";
			break;

			case "default" :
			default :
			$statustxt = _AM_FSECTION_STATUS0;
			$approve = "";
			$clone = '';
			$modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_FSECTION_REJECTED_EDIT . "' alt='" . _AM_FSECTION_REJECTED_EDIT . "' /></a>&nbsp;";
			$delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_FSECTION_DELETEITEM . "' alt='" . _AM_FSECTION_DELETEITEM . "' /></a>";
			break;
		}

		echo "<tr>";
		echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . "</td>";
		echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . "</td>";
		echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . "</td>";
		echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . "</td>";
		echo "<td class='even' align='center'>" . $statustxt . "</td>";
		echo "<td class='even' align='center'> ". $approve .$clone. $modify . $delete . "</td>";
		echo "</tr>";
	}
} else {
	// that is, $numrows = 0, there's no entries yet
	echo "<tr>";
	echo "<td class='head' align='center' colspan= '7'>" . _AM_FSECTION_NOITEMSSEL . "</td>";
	echo "</tr>";
}
echo "</table>\n";
echo "<span style=\"color: #567; margin: 3px 0 18px 0; font-size: small; display: block; \">$status_explaination</span>";
$pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startentry, 'startentry', "statussel=$statussel&amp;sortsel=$sortsel&amp;ordersel=$ordersel");

if ($xoopsModuleConfig['useimagenavpage'] == 1) {
	echo '<div style="text-align:right; background-color: white; margin: 10px 0;">' . $pagenav->renderImageNav() . '</div>';
} else {
	echo '<div style="text-align:right; background-color: white; margin: 10px 0;">' . $pagenav->renderNav() . '</div>';
}
// ENDs code to show active entries
freesection_close_collapsable('allitemstable', 'allitemsicon');
// Close the collapsable div



echo "</div>";

echo "</div>";


freesection_modFooter();

xoops_cp_footer();

?>