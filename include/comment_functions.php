<?php

/**
* $Id: comment_functions.php,v 1.3 2004/11/10 21:37:58 malanciault Exp $
* Module: FreeSection
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function freesection_com_update($item_id, $total_num)
{
    $db = &Database::getInstance();
    $sql = 'UPDATE ' . $db->prefix('freesection_items') . ' SET comments = ' . $total_num . ' WHERE itemid = ' . $item_id;
    $db->query($sql);
} 

function freesection_com_approve(&$comment)
{ 
    // notification mail here
} 

?>