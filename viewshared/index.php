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
$dao = new DAO();
// make sure the user has author privilege
Utility::authenticate(TR_PRIV_ISAUTHOR);

// get a list of authors if admin is creating a lesson	

require(TR_INCLUDE_PATH.'header.inc.php');


$dao = new DAO();
$session_user_id = $_SESSION['user_id'];
if( (isset($_POST['share_content_id'])) && ( (isset($_POST['share_group_name'])) || (isset($_POST['share_user_id'])) ) ){
	extract($_POST);
	if( (isset($_POST['share_group_name'])) ){
	    foreach($share_content_id as $sci) {
		    foreach($share_group_name as $sgn) {
				$sql="SELECT * FROM ".TABLE_PREFIX."shared_content_group WHERE content_id=$sci AND group_name='$sgn' AND group_creator=$session_user_id";
				$result=$dao->execute($sql);
				if($result){
					//do nothing duplicate entry
				}
				else{
					$sql="INSERT INTO ".TABLE_PREFIX."shared_content_group (content_id, group_name, group_creator)
			     				        VALUES (".$sci.",'".$sgn."',".$session_user_id.")";
					$dao->execute($sql);
				}
		    }
	    }
	}
	if( (isset($_POST['share_user_id'])) ){
	    foreach($share_content_id as $sci) {
		    foreach($share_user_id as $sui) {
				$sql="SELECT * FROM ".TABLE_PREFIX."shared_content WHERE content_id=$sci AND user_id=$sui";
				$result=$dao->execute($sql);
				if($result){
					//do nothing duplicate entry
				}
				else{		        
					$sql="INSERT INTO ".TABLE_PREFIX."shared_content (content_id, user_id, content_author_id)
 +			     				        VALUES ($sci, $sui, $session_user_id)";
					$dao->execute($sql);
				}
		    }
	    }
	}
}
else{
	//echo "things not set";
}
extract($_GET);
$savant->assign('selected_course', $_course_id);
$savant->display('sharecontent/share_content.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>