<?php

/**
* $Id: blocks.php,v 1.13 2007/02/03 16:23:42 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
/*
global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
if (isset($xoopsModuleConfig) && isset($xoopsModule) && $xoopsModule->getVar('dirname') == 'freesection') {
	$itemType = $xoopsModuleConfig['itemtype'];
} else {
	$hModule = &xoops_gethandler('module');
	$hModConfig = &xoops_gethandler('config');
	if ($freesection_Module = &$hModule->getByDirname ('freesection')) {
		$module_id = $freesection_Module->getVar('mid');
		$freesection_Config = &$hModConfig->getConfigsByCat(0, $freesection_Module->getVar('mid'));
		$itemType = $freesection_Config['itemtype'];
	} else {
		$itemType = 'article';
	}
}

include_once (XOOPS_ROOT_PATH . "/modules/freesection/language/" . $xoopsConfig['language'] . "/plugin/" . $itemType . "/blocks.php");
*/
// Blocks

define ("_MB_FSECTION_ALLCAT", "Toutes les cat&eacute;gories");
define ("_MB_FSECTION_AUTO_LAST_ITEMS", "Afficher automatiquement les items r&eacute;cents?");
define ("_MB_FSECTION_CATEGORY", "Cat&eacute;gories");
define ("_MB_FSECTION_CHARS", "Longeur du titre");
define ("_MB_FSECTION_COMMENTS", "Commentaire (s)");
define ("_MB_FSECTION_DATE", "Date de publication");
define ("_MB_FSECTION_DISP", "Afficher");
define ("_MB_FSECTION_DISPLAY_CATEGORY", "Afficher les noms des cat&eacute;gories?");
define ("_MB_FSECTION_DISPLAY_COMMENTS", "Afficher le compte des commentaires");
define ("_MB_FSECTION_DISPLAY_TYPE", "Type d'affichage:");
define ("_MB_FSECTION_DISPLAY_TYPE_BLOCK", "Chaque item est un bloc");
define ("_MB_FSECTION_DISPLAY_TYPE_BULLET", "Chaque item est une puce");
define ("_MB_FSECTION_DISPLAY_WHO_AND_WHEN", "Afficher auteur et date?");
define ("_MB_FSECTION_FULLITEM", "Lire l'article au complet");
define ("_MB_FSECTION_HITS", "Nombre de clics");
define ("_MB_FSECTION_ITEMS", "Articles");
define ("_MB_FSECTION_LAST_ITEMS_COUNT", "Si oui, enafficher combien?");
define ("_MB_FSECTION_LENGTH", " caract&egrave;res");
define ("_MB_FSECTION_ORDER", "Afficher l'ordre");
define ("_MB_FSECTION_POSTEDBY", "Affich&eacute; par");
define ("_MB_FSECTION_READMORE", "Lire la suite...");
define ("_MB_FSECTION_READS", "Lus");
define ("_MB_FSECTION_SELECT_ITEMS", "sinon, selectionner les articles &agrave; afficher:");
define ("_MB_FSECTION_SELECTCAT", "Afficher les articles concernant:");
define ("_MB_FSECTION_VISITITEM", "Visitez le");
define ("_MB_FSECTION_WEIGHT", "Listage par poids");
define ("_MB_FSECTION_WHO_WHEN", "Affich&eacute; par %s le %s");
define("_MB_FSECTION_LEVELS", "Niveaux");
define("_MB_FSECTION_CURRENTCATEGORY", "Catégorie courante");
define("_MB_FSECTION_ASC", "ASC");
define("_MB_FSECTION_DESC", "DESC");
define("_MB_FSECTION_SHOWITEMS", "Afficher les Items");

define("_MB_FSECTION_FILES", "fichiers");
define("_MB_FSECTION_DIRECTDOWNLOAD", "Lien direct pour télécharger le fichier au lieu d'un lien vers l'article?");
?>