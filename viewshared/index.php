<?php

//review***************** this file is copied from create_course.php and then changes are made to it..


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

define('TR_INCLUDE_PATH', '../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/Utility.class.php');
include(TR_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');
include(TR_INCLUDE_PATH.'classes/DAO/ContentDAO.class.php');
$dao = new DAO();

require(TR_INCLUDE_PATH.'header.inc.php');

$dao = new DAO();
$session_user_id = $_SESSION['user_id'];
$current_share_content = array();

if( isset($session_user_id) ){
	$sql="SELECT DISTINCT(content_id), gu.group_creator AS content_author_id FROM ".TABLE_PREFIX."shared_content_group scg, ".TABLE_PREFIX."group_users gu WHERE scg.group_name=gu.group_name AND scg.group_creator=gu.group_creator AND gu.user_id=$session_user_id ORDER BY content_id";
	$current_share_content_group=$dao->execute($sql);
	//find all the content shared with the current user
	$sql="SELECT DISTINCT(content_id), content_author_id FROM ".TABLE_PREFIX."shared_content WHERE user_id=$session_user_id ORDER BY content_id";
	$current_share_content_group=$dao->execute($sql);
	if( is_array($current_share_content_group) )
		$current_share_content=array_merge($current_share_content, $current_share_content_group);
}

$savant->assign('current_share_content', $current_share_content);
$savant->display('sharecontent/current_share_content.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>
