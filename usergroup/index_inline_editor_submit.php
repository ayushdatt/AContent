<?php
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2010                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('TR_INCLUDE_PATH', '../include/');
include(TR_INCLUDE_PATH.'vitals.inc.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/GroupsUsersDAO.class.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/SharedContentDAO.class.php');

redirectNotLoggedinUsers();

if ($_POST['value'] == '')
{
	$rtn['status'] = 'fail';
	$rtn['error'][] = _AT('TR_ERROR_EMPTY_FIELD');
}

if (isset($_POST['field']) && isset($_POST['value']) && $_POST['value'] <> '')
{
	$groupsusersDAO = new GroupsUsersDAO();
	$sharedcontentDAO = new SharedContentDAO();
        
	// Format of $_POST['field']: [fieldName]|[user_id]
	$pieces = explode('-', $_POST['field']);
        $pieces[1]="'$pieces[1]'";
	$status = $groupsusersDAO->UpdateField($pieces[1], $pieces[0], $_POST['value']);
	$status = $sharedcontentDAO->UpdateField($pieces[1], $pieces[0], $_POST['value']);
	if(is_array($status) && is_array($status))
	{
		$rtn['status'] = 'fail';
		foreach ($status as $err)
			$rtn['error'][] = $err;
	}
	else
	{
		$rtn['status'] = 'success';
		$rtn['success'][] = _AT('TR_FEEDBACK_ACTION_COMPLETED_SUCCESSFULLY');
	}
}
    
echo json_encode($rtn);
?>
