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
include_once(TR_INCLUDE_PATH.'classes/DAO/ContentDAO.class.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/SharedContentLockingDAO.class.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/SharedContentDAO.class.php');

$dao = new DAO();
$sharedContentDAO=new SharedContentDAO();
$session_user_id = $_SESSION['user_id'];
$current_share_content = array();

if( isset($session_user_id) ){
        $current_share_content_group = $sharedContentDAO->getAllContentSharedGroup($session_user_id);
	$current_share_content = $sharedContentDAO->getAllContentShared($session_user_id);
	
        //remove duplicate entries
	if(is_array($current_share_content)){
		if( is_array($current_share_content_group) ){
			foreach ($current_share_content_group as $key => $value) {
				$flag = 0;//does not match
				foreach ($current_share_content as $keymatch => $valuematch) {
					if($value === $valuematch){
						$flag=1;//matched
						break;
					}
				}
				if($flag===0){
					array_push($current_share_content, $value);
				}
			}
		}
	}
	else{
		$current_share_content = $current_share_content_group;
	}
}

if(isset($_GET['cannotOpen']) && $_GET['cannotOpen']==="true"){
	$msg->addError('CONTENT_LOCKED');
}
require(TR_INCLUDE_PATH.'header.inc.php');
$savant->assign('current_share_content', $current_share_content);
$savant->display('viewshared/index.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>
