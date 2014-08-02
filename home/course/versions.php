<?php
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2013                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('TR_INCLUDE_PATH', '../../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/ContentDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/Versioning/Versions.class.php');

$cid=$_REQUEST['_cid'];
$contentDAO = new ContentDAO();
$row=$contentDAO->get($cid);
$title=$row['title'];
//echo $title;
if(isset($cid)){
	$versions = new Versions();
	$versions->get_Versions($cid);
}
else{
	//TODO show warning
	header('Location: '.TR_BASE_HREF.'home/index.php');
}
$savant->assign('revision_info', $versions->meta);
$savant->assign('cid', $cid);
$savant->assign('title_content', $title);

require(TR_INCLUDE_PATH.'header.inc.php'); 
$savant->display('home/course/versions.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');

?>
