<?php
/**
* $Id: functions.php,v 1.68 2007/02/03 16:23:18 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/modules/freesection/include/common.php';

function freesection_isXoops22() {
	$xoops22 = false;
	$xv=str_replace('XOOPS ','',XOOPS_VERSION);
	if(substr($xv,2,1)=='2') {
		$xoops22=true;
	}
	return $xoops22;
}

function freesection_getAllCategoriesObj() {

	static $freesection_allCategoriesObj;
	if (!isset($freesection_allCategoriesObj)) {

		global $freesection_category_handler;

		if (!isset($freesection_category_handler)) {
			$freesection_category_handler =& xoops_getmodulehandler('category', 'freesection');
		}
		$freesection_allCategoriesObj = $freesection_category_handler->getObjects(null, true);
	}
	return $freesection_allCategoriesObj;
}

function freesection_xoops_cp_header()
{
	xoops_cp_header();

	?>
	<script type='text/javascript' src='funcs.js'></script>
	<script type='text/javascript' src='cookies.js'></script>
	<?php

}

function freesection_getOrderBy($sort)
{
	if ($sort == "datesub") {
		return "DESC";
	} elseif ($sort == "counter") {
		return "DESC";
	} elseif ($sort == "weight") {
		return "ASC";
	}
}
/**
 * Detemines if a table exists in the current db
 *
 * @param string $table the table name (without XOOPS prefix)
 * @return bool True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function freesection_TableExists($table)
{

	$bRetVal = false;
	//Verifies that a MySQL table exists
	$xoopsDB =& Database::getInstance();
	$realname = $xoopsDB->prefix($table);
	$ret = mysql_list_tables(XOOPS_DB_NAME, $xoopsDB->conn);
	while (list($m_table)=$xoopsDB->fetchRow($ret)) {

		if ($m_table ==  $realname) {
			$bRetVal = true;
			break;
		}
	}
	$xoopsDB->freeRecordSet($ret);
	return ($bRetVal);
}
/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param string $key
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function freesection_GetMeta($key)
{
	$xoopsDB =& Database::getInstance();
	$sql = sprintf("SELECT metavalue FROM %s WHERE metakey=%s", $xoopsDB->prefix('freesection_meta'), $xoopsDB->quoteString($key));
	$ret = $xoopsDB->query($sql);
	if (!$ret) {
		$value = false;
	} else {
		list($value) = $xoopsDB->fetchRow($ret);

	}
	return $value;
}

/**
 * Sets a value for a key in the xhelp_meta table
 *
 * @param string $key
 * @param string $value
 * @return bool TRUE if success, FALSE if failure
 *
 * @access public
 * @author xhelp development team
 */
function freesection_SetMeta($key, $value)
{
	$xoopsDB =& Database::getInstance();
	if($ret = freesection_GetMeta($key)){
		$sql = sprintf("UPDATE %s SET metavalue = %s WHERE metakey = %s", $xoopsDB->prefix('freesection_meta'), $xoopsDB->quoteString($value), $xoopsDB->quoteString($key));
	} else {
		$sql = sprintf("INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)", $xoopsDB->prefix('freesection_meta'), $xoopsDB->quoteString($key), $xoopsDB->quoteString($value));
	}
	$ret = $xoopsDB->queryF($sql);
	if (!$ret) {
		return false;
	}
	return true;
}

function freesection_highlighter ($matches) {

	$smartConfig =& freesection_getModuleConfig();
	$color = $smartConfig['highlight_color'];
	if(substr($color,0,1)!='#') {
		$color='#'.$color;
	}
	return '<span style="font-weight: bolder; background-color: '.$color.';">' . $matches[0] . '</span>';
}

// Thanks to Mithrandir :-)
function freesection_substr($str, $start, $length, $trimmarker = '...')
{
	// if the string is empty, let's get out ;-)
	if ($str == '') {
		return $str;
	}

	// reverse a string that is shortened with '' as trimmarker
	$reversed_string = strrev(xoops_substr($str, $start, $length, ''));

	// find first space in reversed string
	$position_of_space = strpos($reversed_string, " ", 0);

	// truncate the original string to a length of $length
	// minus the position of the last space
	// plus the length of the $trimmarker
	$truncated_string = xoops_substr($str, $start, $length-$position_of_space+strlen($trimmarker), $trimmarker);

	return $truncated_string;
}

function freesection_getConfig($key)
{
	$configs = freesection_getModuleConfig();
	return $configs[$key];
}

function freesection_metagen_html2text($document)
{
	// PHP Manual:: function preg_replace
	// $document should contain an HTML document.
	// This will remove HTML tags, javascript sections
	// and white space. It will also convert some
	// common HTML entities to their text equivalent.
	// Credits : newbb2
	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
	"'<img.*?/>'si",       // Strip out img tags
	"'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
	"'([\r\n])[\s]+'",                // Strip out white space
	"'&(quot|#34);'i",                // Replace HTML entities
	"'&(amp|#38);'i",
	"'&(lt|#60);'i",
	"'&(gt|#62);'i",
	"'&(nbsp|#160);'i",
	"'&(iexcl|#161);'i",
	"'&(cent|#162);'i",
	"'&(pound|#163);'i",
	"'&(copy|#169);'i",
	"'&#(\d+);'e");                    // evaluate as php

	$replace = array ("",
	"",
	"",
	"\\1",
	"\"",
	"&",
	"<",
	">",
	" ",
	chr(161),
	chr(162),
	chr(163),
	chr(169),
	"chr(\\1)");

	$text = preg_replace($search, $replace, $document);
	return $text;
}

function freesection_getAllowedImagesTypes()
{
	return array('jpg/jpeg', 'image/bmp', 'image/gif', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/pjpeg');
}


function freesection_module_home($withLink=true)
{
	global $freesection_moduleName, $xoopsModuleConfig;
	if(!$xoopsModuleConfig['show_mod_name_breadcrumb']){
		return	'';
	}
	if (!$withLink)	{
		return $freesection_moduleName;
	} else {
		return '<a href="' . FREESECTION_URL . '">' . $freesection_moduleName . '</a>';
	}
}

/**
 * Copy a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.0
 * @param       string   $source    The source
 * @param       string   $dest      The destination
 * @return      bool     Returns true on success, false on failure
 */
function freesection_copyr($source, $dest)
{
	// Simple copy for a file
	if (is_file($source)) {
		return copy($source, $dest);
	}

	// Make destination directory
	if (!is_dir($dest)) {
		mkdir($dest);
	}

	// Loop through the folder
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}

		// Deep copy directories
		if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
			copyr("$source/$entry", "$dest/$entry");
		} else {
			copy("$source/$entry", "$dest/$entry");
		}
	}

	// Clean up
	$dir->close();
	return true;
}

function freesection_getEditor($caption, $name, $value, $dhtml = true)
{
	$smartConfig =& freesection_getModuleConfig();

	global $xoops22;
	if (!isset($xoops22)) {
		$xoops22 = freesection_isXoops22();
	}

  	$editor_configs=array();
    $editor_configs["name"] = $name;
    $editor_configs["value"] = $value;
    $editor_configs['caption'] = $caption;
    $editor_configs["rows"] = 35;
    $editor_configs["cols"] = 60;
    $editor_configs["width"] = "100%";
    $editor_configs["height"] = "400px";

	switch ($smartConfig['use_wysiwyg']) {
		case 'tiny' :
		if (!$xoops22) {

			if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php");
				$editor = new XoopsFormTinyeditorTextArea(array('caption'=>$caption, 'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'400px'));
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}
		} else {
			$editor = new XoopsFormEditor($caption, "tinyeditor", $editor_configs);
		}
		break;

		case 'inbetween' :
		if (!$xoops22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/inbetween/forminbetweentextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/inbetween/forminbetweentextarea.php");
				$editor = new XoopsFormInbetweenTextArea(array('caption'=> $caption, 'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'300px'),true);
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}
		} else {
			$editor = new XoopsFormEditor($caption, "inbetween", $editor_configs);
		}
		break;

		case 'fckeditor' :
		if (!$xoops22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php");
				$editor = new XoopsFormFckeditor($editor_configs,true);
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}
		} else {
			$editor = new XoopsFormEditor($caption, "fckeditor", $editor_configs);
		}
		break;

		case 'koivi' :
		if (!$xoops22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php");
				$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px');
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}
		} else {
			$editor = new XoopsFormEditor($caption, "koivi", $editor_configs);
		}
		break;

		case "spaw":
		if(!$xoops22) {
			if (is_readable(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php");
				$editor = new XoopsFormSpaw($caption, $name, $value);
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}

		} else {
			$editor = new XoopsFormEditor($caption, "spaw", $editor_configs);
		}
		break;

		case "htmlarea":
		if(!$xoops22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php");
				$editor = new XoopsFormHtmlarea($caption, $name, $value);
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
				}
			}
		} else {
			$editor = new XoopsFormEditor($caption, "htmlarea", $editor_configs);
		}
		break;

		default :
		if ($dhtml) {
			$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
		} else {
			$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
		}

		break;
	}

	return $editor;
}

/**
* Thanks to the NewBB2 Development Team
*/
function &freesection_admin_getPathStatus($item, $getStatus=false)
{
	if ($item == 'root') {
		$path = '';
	} else {
		$path = $item;
	}

	$thePath = freesection_getUploadDir(true, $path);

	if(empty($thePath)) return false;
	if(@is_writable($thePath)){
		$pathCheckResult = 1;
		$path_status = _AM_FSECTION_AVAILABLE;
	}elseif(!@is_dir($thePath)){
		$pathCheckResult = -1;
		$path_status = _AM_FSECTION_NOTAVAILABLE." <a href=index.php?op=createdir&amp;path=$item>"._AM_FSECTION_CREATETHEDIR.'</a>';
	}else{
		$pathCheckResult = -2;
		$path_status = _AM_FSECTION_NOTWRITABLE." <a href=index.php?op=setperm&amp;path=$item>"._AM_SCS_SETMPERM.'</a>';
	}
	if (!$getStatus) {
		return $path_status;
	} else {
		return $pathCheckResult;
	}
}




/**
* Thanks to the NewBB2 Development Team
*/
function freesection_admin_mkdir($target)
{
	// http://www.php.net/manual/en/function.mkdir.php
	// saint at corenova.com
	// bart at cdasites dot com
	if (is_dir($target) || empty($target)) {
		return true; // best case check first
	}

	if (file_exists($target) && !is_dir($target)) {
		return false;
	}

	if (freesection_admin_mkdir(substr($target,0,strrpos($target,'/')))) {
		if (!file_exists($target)) {
			$res = mkdir($target, 0777); // crawl back up & create dir tree
			freesection_admin_chmod($target);
			return $res;
		}
	}
	$res = is_dir($target);
	return $res;
}

/**
* Thanks to the NewBB2 Development Team
*/
function freesection_admin_chmod($target, $mode = 0777)
{
	return @chmod($target, $mode);
}


function freesection_getUploadDir($local=true, $item=false)
{
	if ($item) {
		if ($item=='root') {
			$item = '';
		} else {
			$item = $item . '/';
		}
	} else {
		$item = '';
	}

	if ($local) {
		return XOOPS_ROOT_PATH . "/uploads/freesection/$item";
	} else {
		return XOOPS_URL . "/uploads/freesection/$item";
	}
}

function freesection_getImageDir($item='', $local=true)
{
	if ($item) {
		$item = "images/$item";
	} else {
		$item = 'images';
	}

	return freesection_getUploadDir($local, $item);
}

function freesection_imageResize($src, $maxWidth, $maxHeight)
{
	$width = '';
	$height = '';
	$type = '';
	$attr = '';

	if (file_exists($src)) {
		list($width, $height, $type, $attr) = getimagesize($src);
		if ($width > $maxWidth) {
			$originalWidth = $width;
			$width = $maxWidth;
			$height = $width * $height / $originalWidth;
		}

		if ($height > $maxHeight) {
			$originalHeight = $height;
			$height = $maxHeight;
			$width = $height * $width / $originalHeight;
		}

		$attr = " width='$width' height='$height'";
	}
	return array($width, $height, $type, $attr);
}

function freesection_getHelpPath()
{
	$smartConfig =& freesection_getModuleConfig();
	switch ($smartConfig['helppath_select'])
	{
		case 'docs.xoops.org' :
		return 'http://docs.xoops.org/help/ssectionh/index.htm';
		break;

		case 'inside' :
		return XOOPS_URL . "/modules/freesection/doc/";
		break;

		case 'custom' :
		return $smartConfig['helppath_custom'];
		break;
	}
}

function &freesection_getModuleInfo()
{
	static $smartModule;
	if (!isset($smartModule)) {
		global $xoopsModule;
		if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == 'freesection') {
			$smartModule =& $xoopsModule;
		}
		else {
			$hModule = &xoops_gethandler('module');
			$smartModule = $hModule->getByDirname('freesection');
		}
	}
	return $smartModule;
}

function &freesection_getModuleConfig()
{
	static $smartConfig;
	if (!$smartConfig) {
		global $xoopsModule;
		if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == 'freesection') {
			global $xoopsModuleConfig;
			$smartConfig =& $xoopsModuleConfig;
		}
		else {
			$smartModule =& freesection_getModuleInfo();
			$hModConfig = &xoops_gethandler('config');
			$smartConfig = $hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));
		}
	}
	return $smartConfig;
}


function freesection_deleteFile($dirname)
{
	// Simple delete for a file
	if (is_file($dirname)) {
		return unlink($dirname);
	}
}

function freesection_formatErrors($errors=array())
{
	$ret = '';
	foreach ($errors as $key=>$value)
	{
		$ret .= "<br /> - " . $value;
	}
	return $ret;
}

/*function freesection_addCategoryOption($categoryObj, $selectedid=0, $level = 0, $ret='')
{
// Creating the category handler object
$category_handler =& freesection_gethandler('category');
$spaces = '';
for ( $j = 0; $j < $level; $j++ ) {
$spaces .= '--';
}

$ret .= "<option value='" . $categoryObj->categoryid() . "'";
if ($selectedid == $categoryObj->categoryid()) {
$ret .= " selected='selected'";
}
$ret .= ">" . $spaces . $categoryObj->name() . "</option>\n";

$subCategoriesObj = $category_handler->getCategories(0, 0, $categoryObj->categoryid());
if (count($subCategoriesObj) > 0) {
$level++;
foreach ( $subCategoriesObj as $catID => $subCategoryObj) {
$ret .= freesection_addCategoryOption($subCategoryObj, $selectedid, $level);
}
}
return $ret;
}*/

function freesection_createCategoryOptions($selectedid=0, $parentcategory=0, $allCatOption=true)
{
	$ret = "";
	if ($allCatOption) {
		$ret .= "<option value='0'";
		$ret .= ">" . _MB_FSECTION_ALLCAT . "</option>\n";
	}

	// Creating the category handler object
	$category_handler =& freesection_gethandler('category');

	// Creating category objects
	$categoriesObj = $category_handler->getCategories(0, 0, $parentcategory);
	if (count($categoriesObj) > 0) {
		foreach ( $categoriesObj as $catID => $categoryObj) {
			$ret .= freesection_addCategoryOption($categoryObj, $selectedid);
		}
	}
	return $ret;
}


function freesection_getStatusArray ()
{
	$result = array("1" => _AM_FSECTION_STATUS1,
	"2" => _AM_FSECTION_STATUS2,
	"3" => _AM_FSECTION_STATUS3,
	"4" => _AM_FSECTION_STATUS4,
	"5" => _AM_FSECTION_STATUS5,
	"6" => _AM_FSECTION_STATUS6,
	"7" => _AM_FSECTION_STATUS7,
	"8" => _AM_FSECTION_STATUS8);
	return $result;
}

function freesection_moderator ()
{
	global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsUser;

	if (!$xoopsUser) {
		$result = false;
	} else {
		$hModule = &xoops_gethandler('module');
		$hModConfig = &xoops_gethandler('config');

		if ($smartModule = &$hModule->getByDirname('freesection')) {
			$module_id = $smartModule->getVar('mid');
		}

		$module_name = $smartModule->getVar('dirname');
		$smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

		$gperm_handler = &xoops_gethandler('groupperm');

		$categories = $gperm_handler->getItemIds('category_moderation', $xoopsUser->getVar('uid'), $module_id);
		if (count($categories) == 0) {
			$result = false;
		} else {
			$result = true;
		}
	}
	return $result;
}

function freesection_modFooter ()
{
	global $xoopsUser, $xoopsDB, $xoopsConfig;

	$hModule = &xoops_gethandler('module');
	$hModConfig = &xoops_gethandler('config');

	$smartModule = &$hModule->getByDirname('freesection');
	$module_id = $smartModule->getVar('mid');

	$module_name = $smartModule->getVar('dirname');
	$smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

	$module_id = $smartModule->getVar('mid');

	$versioninfo = &$hModule->get($smartModule->getVar('mid'));
	$modfootertxt = "Module " . $versioninfo->getInfo('name') . " - Version " . $versioninfo->getInfo('version') . "";
	if (!defined('_AM_FSECTION_XOOPS_PRO')) {
		define("_AM_FSECTION_XOOPS_PRO", "Do you need help with this module ?<br />Do you need new features not yet availale ?");
	}

	echo "<div style='padding-top: 8px; padding-bottom: 10px; text-align: center;'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='_blank'><img src='" . XOOPS_URL . "/modules/freesection/images/sscssbutton.gif' title='" . $modfootertxt . "' alt='" . $modfootertxt . "'/></a></div>";
	echo '<div style="border: 2px solid #C2CDD6">';
	echo '<div style="font-weight:bold; padding-top: 5px; text-align: center;">' . _AM_FSECTION_XOOPS_PRO . '<br /><a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/INBOXsign150_noslogan.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
<a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/xoops_services_pro_english.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
</div>';
	echo '</div>';

}

/**
* Checks if a user is admin of FreeSection
*
* freesection_userIsAdmin()
*
* @return boolean : array with userids and uname
*/
function freesection_userIsAdmin()
{
	global $xoopsUser;

	static $freesection_isAdmin;

	if (isset($freesection_isAdmin)) {
		return $freesection_isAdmin;
	}

	if (!$xoopsUser) {
		$freesection_isAdmin = false;
		return $freesection_isAdmin;
	}

	$freesection_isAdmin = false;

	$smartModule = freesection_getModuleInfo();
	$module_id = $smartModule->getVar('mid');
	$freesection_isAdmin = $xoopsUser->isAdmin($module_id);

	return $freesection_isAdmin;
}

/**
* Checks if a user has access to a selected item. if no item permissions are
* set, access permission is denied. The user needs to have necessary category
* permission as well.
*
* freesection_itemAccessGranted()
*
* @param integer $itemid : itemid on which we are setting permissions
* @param integer $ categoryid : categoryid of the item
* @return boolean : TRUE if the no errors occured
*/

// TODO : Move this function to FreesectionItem class
function freesection_itemAccessGranted($itemid, $categoryid)
{
	Global $xoopsUser;

	if (freesection_userIsAdmin()) {
		$result = true;
	} else {
		$result = false;

		$groups = ($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

		$gperm_handler = &xoops_gethandler('groupperm');
		$hModule = &xoops_gethandler('module');
		$hModConfig = &xoops_gethandler('config');

		$smartModule = &$hModule->getByDirname('freesection');

		$module_id = $smartModule->getVar('mid');
		// Do we have access to the parent category
		if ($gperm_handler->checkRight('category_read', $categoryid, $groups, $module_id)) {
			// Do we have access to the item ?
			if ($gperm_handler->checkRight('item_read', $itemid, $groups, $module_id)) {
				$result = true;
			} else { // No we don't !
			$result = false;
			}
		} else { // No we don't !
		$result = false;
		}
	}

	return $result;
}

/**
* Override ITEMs permissions of a category by the category read permissions
*
*   freesection_overrideItemsPermissions()
*
* @param array $groups : group with granted permission
* @param integer $categoryid :
* @return boolean : TRUE if the no errors occured
*/
function freesection_overrideItemsPermissions($groups, $categoryid)
{
	Global $xoopsDB;

	$result = true;
	$hModule = &xoops_gethandler('module');
	$smartModule = &$hModule->getByDirname('freesection');

	$module_id = $smartModule->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');

	$sql = "SELECT itemid FROM " . $xoopsDB->prefix("freesection_items") . " WHERE categoryid = '$categoryid' ";
	$result = $xoopsDB->query($sql);

	if (count($result) > 0) {
		while (list($itemid) = $xoopsDB->fetchrow($result)) {
			// First, if the permissions are already there, delete them
			$gperm_handler->deleteByModule($module_id, 'item_read', $itemid);
			// Save the new permissions
			if (count($groups) > 0) {
				foreach ($groups as $group_id) {
					$gperm_handler->addRight('item_read', $itemid, $group_id, $module_id);
				}
			}
		}
	}

	return $result;
}

/**
* Saves permissions for the selected item
*
*   freesection_saveItemPermissions()
*
* @param array $groups : group with granted permission
* @param integer $itemID : itemid on which we are setting permissions
* @return boolean : TRUE if the no errors occured

*/
function freesection_saveItemPermissions($groups, $itemID)
{
	$result = true;
	$hModule = &xoops_gethandler('module');
	$smartModule = &$hModule->getByDirname('freesection');

	$module_id = $smartModule->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	// First, if the permissions are already there, delete them
	$gperm_handler->deleteByModule($module_id, 'item_read', $itemID);
	// Save the new permissions
	if (count($groups) > 0) {
		foreach ($groups as $group_id) {
			$gperm_handler->addRight('item_read', $itemID, $group_id, $module_id);
		}
	}
	return $result;
}

/**
* Saves permissions for the selected category
*
*   freesection_saveCategory_Permissions()
*
* @param array $groups : group with granted permission
* @param integer $categoryid : categoryid on which we are setting permissions
* @param string $perm_name : name of the permission
* @return boolean : TRUE if the no errors occured
*/

function freesection_saveCategory_Permissions($groups, $categoryid, $perm_name)
{
	$result = true;
	$hModule = &xoops_gethandler('module');
	$smartModule = &$hModule->getByDirname('freesection');

	$module_id = $smartModule->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	// First, if the permissions are already there, delete them
	$gperm_handler->deleteByModule($module_id, $perm_name, $categoryid);

	// Save the new permissions
	if (count($groups) > 0) {
		foreach ($groups as $group_id) {
			$gperm_handler->addRight($perm_name, $categoryid, $group_id, $module_id);
		}
	}
	return $result;
}

/**
* Saves permissions for the selected category
*
*   freesection_saveModerators()
*
* @param array $moderators : moderators uids
* @param integer $categoryid : categoryid on which we are setting permissions
* @return boolean : TRUE if the no errors occured
*/

function freesection_saveModerators($moderators, $categoryid)
{
	$result = true;
	$hModule = &xoops_gethandler('module');
	$smartModule = &$hModule->getByDirname('freesection');
	$module_id = $smartModule->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	// First, if the permissions are already there, delete them
	$gperm_handler->deleteByModule($module_id, 'category_moderation', $categoryid);
	// Save the new permissions
	if (count($moderators) > 0) {
		foreach ($moderators as $uid) {
			$gperm_handler->addRight('category_moderation', $categoryid, $uid, $module_id);
		}
	}
	return $result;
}

/**
* freesection_getLinkedUnameFromId()
*
* @param integer $userid Userid of poster etc
* @param integer $name :  0 Use Usenamer 1 Use realname
* @return
*/
function freesection_getLinkedUnameFromId($userid = 0, $name = 0, $users = array())
{
	if (!is_numeric($userid)) {
		return $userid;
	}

	$userid = intval($userid);
	if ($userid > 0) {
		if ($users == array()) {
			//fetching users
			$member_handler = &xoops_gethandler('member');
			$user = &$member_handler->getUser($userid);
		}
		else {
			if (!isset($users[$userid])) {
				return $GLOBALS['xoopsConfig']['anonymous'];
			}
			$user =& $users[$userid];
		}

		if (is_object($user)) {
			$ts = &MyTextSanitizer::getInstance();
			$username = $user->getVar('uname');
			$fullname = '';

			$fullname2 = $user->getVar('name');

			if (($name) && !empty($fullname2)) {
				$fullname = $user->getVar('name');
			}
			if (!empty($fullname)) {
				$linkeduser = "$fullname [<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . $ts->htmlSpecialChars($username) . "</a>]";
			} else {
				$linkeduser = "<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . ucwords($ts->htmlSpecialChars($username)) . "</a>";
			}
			return $linkeduser;
		}
	}
	return $GLOBALS['xoopsConfig']['anonymous'];
}

function freesection_getxoopslink($url = '')
{
	$xurl = $url;
	if (strlen($xurl) > 0) {
		if ($xurl[0] = '/') {
			$xurl = str_replace('/', '', $xurl);
		}
		$xurl = str_replace('{SITE_URL}', XOOPS_URL, $xurl);
	}
	$xurl = $url;
	return $xurl;
}

function freesection_adminMenu ($currentoption = 0, $breadcrumb = '' ) {

	include_once XOOPS_ROOT_PATH . '/class/template.php';

	// global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	global $xoopsModule, $xoopsConfig;

	if (file_exists(FREESECTION_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php')) {
		include_once FREESECTION_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
	} else {
		include_once FREESECTION_ROOT_PATH . 'language/english/modinfo.php';
	}
	if (file_exists(FREESECTION_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php')) {
		include_once FREESECTION_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php';
	} else {
		include_once FREESECTION_ROOT_PATH . 'language/english/admin.php';
	}
	include FREESECTION_ROOT_PATH . 'admin/menu.php';

	$tpl =& new XoopsTpl();
	$tpl->assign( array(
	'headermenu'	=> $headermenu,
	'adminmenu'		=> $adminmenu,
	'current'		=> $currentoption,
	'breadcrumb'	=> $breadcrumb,
	'headermenucount' => count($headermenu)
	) );
	$tpl->display( 'db:freesection_admin_menu.html' );
	echo "<br />\n";
}

function freesection_collapsableBar($tablename = '', $iconname = '', $tabletitle = '', $tabledsc='')
{

	global $xoopsModule;
	echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "')\";>";
	echo "<img id='$iconname' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . $tabletitle . "</h3>";
	echo "<div id='$tablename'>";
	if ($tabledsc != '') {
		echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $tabledsc . "</span>";
	}
}

function freesection_openclose_collapsable($name, $icon)
{
	$urls = freesection_getCurrentUrls();
	$path = $urls['phpself'];

	$cookie_name = $path . '_freesection_collaps_' . $name;
	$cookie_name = str_replace('.', '_', $cookie_name);
	$cookie = freesection_getCookieVar($cookie_name, '');

	if ($cookie == 'none') {
		echo '
		<script type="text/javascript"><!--
		toggle("' . $name . '"); toggleIcon("' . $icon . '");
			//-->
		</script>
		';
	}
}

function freesection_close_collapsable($name, $icon)
{
	echo "</div>";
	freesection_openclose_collapsable($name, $icon);
}

function freesection_setCookieVar($name, $value, $time=0)
{
	if ($time == 0) {
		$time = time()+3600*24*365;
		//$time = '';
	}
	setcookie($name, $value, $time, '/');
}

function freesection_getCookieVar($name, $default='')
{
	if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
		return 	$_COOKIE[$name];
	} else {
		return	$default;
	}
}

function freesection_getCurrentUrls(){
	$http = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
	$phpself = $_SERVER['PHP_SELF'];
	$httphost = $_SERVER['HTTP_HOST'];
	$querystring = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

	if ( $querystring != '' ) {
		$querystring = '?' . $querystring;
	}

	$currenturl = $http . $httphost . $phpself . $querystring;

	$urls = array();
	$urls['http'] = $http;
	$urls['httphost'] = $httphost;
	$urls['phpself'] = $phpself;
	$urls['querystring'] = $querystring;
	$urls['full'] = $currenturl;

	return $urls;
}

function freesection_getCurrentPage()
{
	$urls = freesection_getCurrentUrls();
	return $urls['full'];
}

function &freesection_gethandler($name)
{
	static $freesection_handlers;

	if (!isset($freesection_handlers[$name])) {
		$freesection_handlers[$name] =& xoops_getmodulehandler($name, 'freesection');
	}
	return $freesection_handlers[$name];
}

function freesection_addCategoryOption($categoryObj, $selectedid=0, $level = 0, $ret='')
{
	// Creating the category handler object
	$category_handler =& freesection_gethandler('category');

	$spaces = '';
	for ( $j = 0; $j < $level; $j++ ) {
		$spaces .= '--';
	}

	$ret .= "<option value='" . $categoryObj->categoryid() . "'";
	if ($selectedid == $categoryObj->categoryid()) {
		$ret .= " selected='selected'";
	}
	$ret .= ">" . $spaces . $categoryObj->name() . "</option>\n";

	$subCategoriesObj = $category_handler->getCategories(0, 0, $categoryObj->categoryid());
	if (count($subCategoriesObj) > 0) {
		$level++;
		foreach ( $subCategoriesObj as $catID => $subCategoryObj) {
			$ret .= freesection_addCategoryOption($subCategoryObj, $selectedid, $level);
		}
	}
	return $ret;
}

function freesection_createCategorySelect($selectedid=0, $parentcategory=0, $allCatOption=true)
{
	$ret = "" . _MB_FSECTION_SELECTCAT . "&nbsp;<select name='options[]'>";
	if ($allCatOption) {
		$ret .= "<option value='0'";
		$ret .= ">" . _MB_FSECTION_ALLCAT . "</option>\n";
	}

	// Creating the category handler object
	$category_handler =& freesection_gethandler('category');

	// Creating category objects
	$categoriesObj = $category_handler->getCategories(0, 0, $parentcategory);

	if (count($categoriesObj) > 0) {
		foreach ( $categoriesObj as $catID => $categoryObj) {
			$ret .= freesection_addCategoryOption($categoryObj, $selectedid);
		}
	}
	$ret .= "</select>\n";
	return $ret;
}

function freesection_renderErrors(&$err_arr, $reseturl = '')
{

	if (is_array($err_arr) && count($err_arr) > 0) {
		echo '<div id="readOnly" class="errorMsg" style="border:1px solid #D24D00; background:#FEFECC url('. FREESECTION_URL.'images/important-32.png) no-repeat 7px 50%;color:#333;padding-left:45px;">';

		echo '<h4 style="text-align:left;margin:0; padding-top:0">'._AM_FSECTION_MSG_SUBMISSION_ERR;

		if ($reseturl) {
			echo ' <a href="' . $reseturl . '">[' . _AM_FSECTION_TEXT_SESSION_RESET . ']</a>';
		}

		echo '</h4><ul>';

		foreach($err_arr as $key=>$error) {
			if (is_array($error)) {
				foreach ($error as $err) {
					echo '<li><a href="#'. $key .'" onclick="var e = xoopsGetElementById(\''.$key.'\'); e.focus();">' . htmlspecialchars($err) . '</a></li>';
				}
			} else {
				echo '<li><a href="#'. $key .'" onclick="var e = xoopsGetElementById(\''.$key.'\'); e.focus();">' . htmlspecialchars($error) . '</a></li>';
			}
		}
		echo "</ul></div><br />";
	}
}

/**
 * Generate freesection URL
 *
 * @param string $page
 * @param array $vars
 * @return
 *
 * @access public
 * @credit : xHelp module, developped by 3Dev
 */
function freesection_makeURI($page, $vars = array(), $encodeAmp = true)
{
	$joinStr = '';

	$amp = ($encodeAmp ? '&amp;': '&');

	if (! count($vars)) {
		return $page;
	}
	$qs = '';
	foreach($vars as $key=>$value) {
		$qs .= $joinStr . $key . '=' . $value;
		$joinStr = $amp;
	}
	return $page . '?'. $qs;
}
function freesection_tag_module_included() {
	static 	$freesection_tag_module_included;

	if (!isset($freesection_tag_module_included)) {
		$modules_handler = xoops_gethandler('module');
		$tag_mod = $modules_handler->getByDirName('tag');
		if (!$tag_mod) {
			$tag_mod = false;
		} else {
			$freesection_tag_module_included = $tag_mod->getVar('isactive') == 1;
		}
	}
	return $freesection_tag_module_included;
}

function freesection_upload_file($another = false, $withRedirect=true, &$itemObj)
{
	include_once(FREESECTION_ROOT_PATH . "class/uploader.php");

	global $freesection_isAdmin, $xoopsModuleConfig, $freesection_item_handler, $freesection_file_handler, $xoopsUser;

	$itemid = isset($_POST['itemid']) ? intval($_POST['itemid']) : 0;
	$uid = is_object($xoopsUser) ? $xoopsUser->uid() : 0;
	$session = FreesectionSession::singleton();
	$session->set('freesection_file_filename', isset($_POST['name']) ? $_POST['name'] : '');
	$session->set('freesection_file_description', isset($_POST['description']) ? $_POST['description'] : '');
	$session->set('freesection_file_status', $_POST['status']);
	$session->set('freesection_file_uid', $uid);
	$session->set('freesection_file_itemid', $itemid);

	if (!is_object($itemObj)) {
		$itemObj = $freesection_item_handler->get($itemid);
	}

	$max_size = $xoopsModuleConfig['maximum_filesize'];
	$max_imgwidth = $xoopsModuleConfig['maximum_image_width'];
	$max_imgheight = $xoopsModuleConfig['maximum_image_height'];

	$fileObj = $freesection_file_handler->create();
	$fileObj->setVar('name', isset($_POST['name']) ? $_POST['name'] : '');
	$fileObj->setVar('description', isset($_POST['description']) ? $_POST['description'] : '');
	$fileObj->setVar('status', isset($_POST['file_status']) ? intval($_POST['file_status']) : 1);
	$fileObj->setVar('uid', $uid);
	$fileObj->setVar('itemid', $itemObj->getVar('itemid'));

    // Get available mimetypes for file uploading
/*    $hMime =& xoops_getmodulehandler('mimetype');
    if ($freesection_isAdmin) {
        $crit = new Criteria('mime_admin', 1);
    } else {
        $crit = new Criteria('mime_user', 1);
    }
    $mimetypes =& $hMime->getObjects($crit);
    // TODO : display the available mimetypes to the user
	*/
    if($xoopsModuleConfig['allowupload'] && is_uploaded_file($_FILES['userfile']['tmp_name'])){
        if (!$ret = $fileObj->checkUpload('userfile', $allowed_mimetypes, $errors)) {
            $errorstxt = implode('<br />', $errors);

            $message = sprintf(_FREESECTION_MESSAGE_FILE_ERROR, $errorstxt);
            if ($withRedirect) {
            	redirect_header("file.php?op=mod&itemid=" . $itemid, 5, $message);
            } else {
            	return $message;
        	}
        }
    }

	// Storing the file
	if ( !$fileObj->store($allowed_mimetypes) ) {
		if ($withRedirect) {
			redirect_header("file.php?op=mod&itemid=" . $fileObj->itemid(), 3, _AM_FSECTION_FILEUPLOAD_ERROR . freesection_formatErrors($fileObj->getErrors()));
			exit;
		}else {
			return _AM_FSECTION_FILEUPLOAD_ERROR . freesection_formatErrors($fileObj->getErrors());
		}
	}
	if ($withRedirect) {
		$redirect_page = $another ? 'file.php' : 'item.php';
		redirect_header($redirect_page . "?op=mod&itemid=" . $fileObj->itemid(), 2, _AM_FSECTION_FILEUPLOAD_SUCCESS);
	} else {
		return true;
	}
}

function freesection_new_feature_tag() {
	$ret = '<span style="padding-right: 4px; font-weight: bold; color: red;">' . _AM_FSECTION_NEW_FEATURE . '</span>';
	return $ret;
}
?>