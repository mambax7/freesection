<?php

/**
* $Id: pw_upload_file.php,v 1.6 2006/09/12 13:09:58 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Credits : TinyContent developped by Tobias Liegl (AKA CHAPI) (http://www.chapi.de)
* Licence: GNU
*/

include_once('admin_header.php');

function freesection_pagewrap_upload(&$errors)
{
	include_once(FREESECTION_ROOT_PATH . "class/uploader.php");

	global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModule, $xoopsModuleConfig;
    include_once (FREESECTION_ROOT_PATH.'class/uploader.php');

    $config =& freesection_getModuleConfig();

	$post_field = 'fileupload';

	//$allowed_mimetypes = '';
	// TODO : this needs to be managed by the MimeType section but we need a new parameter for allowed mimetype for pagewrap
/*	if(!isset($allowed_mimetypes)){
        $hMime =& xoops_getmodulehandler('mimetype');
        $allowed_mimetypes = $hMime->checkMimeTypes($post_field);
        if(!$allowed_mimetypes){
        	$errors[] = _FREESECTION_MESSAGE_WRONG_MIMETYPE;
            return false;
        }
    }*/

    /*$maxfilesize = $config['xhelp_uploadSize'];
    $maxfilewidth = $config['xhelp_uploadWidth'];
    $maxfileheight = $config['xhelp_uploadHeight'];*/


    $max_size = $xoopsModuleConfig['maximum_filesize'];
	$max_imgwidth = $xoopsModuleConfig['maximum_image_width'];
	$max_imgheight = $xoopsModuleConfig['maximum_image_height'];

    if(!is_dir(freesection_getUploadDir(true, 'content'))){
        mkdir(freesection_getUploadDir(true, 'content'), 0757);
    }
    $allowed_mimetypes = array('text/html','text/plain','application/xhtml+xml');
	$uploader = new XoopsMediaUploader(freesection_getUploadDir(true, 'content').'/', $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);
    if ($uploader->fetchMedia($post_field)) {
        $uploader->setTargetFileName($uploader->getMediaName());
        if ($uploader->upload()) {
            return true;
        } else {
             $errors = array_merge($errors, $uploader->getErrors(false));
        	return false;
        }

    } else {
        $errors = array_merge($errors, $uploader->getErrors(false));
        return false;
    }
}

$errors = array();

if (freesection_pagewrap_upload($errors)) {
	redirect_header($_POST['backto'], 2, _AM_FSECTION_FILEUPLOAD_SUCCESS);
} else {
	$errorstxt = implode('<br />', $errors);
    $message = sprintf(_FREESECTION_MESSAGE_FILE_ERROR, $errorstxt);
     redirect_header($_POST['backto'], 5, $message);
}

?>