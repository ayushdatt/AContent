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

define('TR_INCLUDE_PATH', '../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/Utility.class.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');
$dao = new DAO();
// make sure the user has author privilege
Utility::authenticate(TR_PRIV_ISAUTHOR);

// get a list of authors if admin is creating a lesson	

extract($_GET);
extract($_POST);

$dao = new DAO();
if( (isset($_POST['revoke'])) && ((!isset($_cid)) || ($_cid=='')) ){
    $msg->addError('NO_CONTENT_SELECTED_TO_REVOKE_ACCESS');
}
else if( (isset($_POST['revoke'])) && (!isset($_POST['revoke_group_name'])) && (!isset($_POST['revoke_user_id']))){
    $msg->addError('SELECT_USER_OR_GROUP_TO_REVOKE_ACCESS');
}

$session_user_id = $_SESSION['user_id'];
if( (isset($_cid)) && ( (isset($_POST['revoke_group_name'])) || (isset($_POST['revoke_user_id'])) ) ){
	if( (isset($_POST['revoke_group_name'])) ){
	    foreach($revoke_group_name as $sgn) {
			$sql="SELECT * FROM ".TABLE_PREFIX."shared_content_group WHERE content_id=$_cid AND group_name='$sgn' AND group_creator=$session_user_id";
			$result=$dao->execute($sql);
			if($result){
				$sql="DELETE FROM ".TABLE_PREFIX."shared_content_group WHERE content_id=$_cid AND group_name='$sgn' AND group_creator=$session_user_id";
				$dao->execute($sql);
			}
	    }
	}
	if( (isset($_POST['revoke_user_id'])) ){
	    foreach($revoke_user_id as $sui) {
			$sql="SELECT * FROM ".TABLE_PREFIX."shared_content WHERE content_id=$_cid AND user_id=$sui";
			$result=$dao->execute($sql);
			if($result){
				$sql="DELETE FROM ".TABLE_PREFIX."shared_content WHERE content_id=$_cid AND user_id=$sui";
				$dao->execute($sql);
			}
	    }
	}
        $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
}

if($_cid){
	$sql="SELECT user_id FROM ".TABLE_PREFIX."shared_content WHERE content_id=$_content_id AND content_author_id=$session_user_id";
	$result=$dao->execute($sql);
	if($result){
		$savant->assign('shared_users', $result);
	}
	$sql="SELECT group_name FROM ".TABLE_PREFIX."shared_content_group WHERE content_id=$_content_id AND group_creator=$session_user_id";
	$result=$dao->execute($sql);
	if($result){
		$savant->assign('shared_groups', $result);
	}
}
require(TR_INCLUDE_PATH.'header.inc.php');
$savant->assign('selected_course', $_course_id);
$savant->display('sharecontent/share_manage.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>