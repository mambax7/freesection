<?php

/**
* $Id: pw_delete_file.php,v 1.2 2005/10/11 00:22:38 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Credits : TinyContent developped by Tobias Liegl (AKA CHAPI) (http://www.chapi.de)
* Licence: GNU
*/

include_once "admin_header.php";

if (isset($_POST["op"]) && ($_POST["op"] == "delfileok")) {
	$dir = freesection_getUploadDir(true, 'content');
	@unlink($dir."/".$_POST["address"]);
	redirect_header($_POST['backto'],2,_AM_FSECTION_FDELETED);
} else {
	xoops_cp_header();
	xoops_confirm(array('backto' => $_POST['backto'], 'address' => $_POST["address"], 'op' => 'delfileok'), 'pw_delete_file.php', _AM_FSECTION_RUSUREDELF, _YES);
	xoops_cp_footer();
}
?>