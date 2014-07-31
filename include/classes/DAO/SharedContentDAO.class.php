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

/**
 * DAO for "user_groups" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class SharedContentDAO extends DAO {

	/**
	 * Create a new user group
	 * @access  public
	 * @param   title
	 *          description
	 * @return  user id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($title, $description)
	{
		global $addslashes, $msg;

		$missing_fields = array();
		
		//$title = $addslashes(trim($title));
		//$description = $addslashes(trim($description));
		
		if ($title == '')
		{
			$missing_fields[] = _AT('title');
		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}

		if (!$msg->containsErrors())
		{
			/* insert into the db */
			$sql = "INSERT INTO ".TABLE_PREFIX."user_groups
			              (title,
			               description,
			               create_date
			               )
			       VALUES ('".$title."',
			               '".$description."',
			               now()
			              )";

			if (!$this->execute($sql))
			{
				$msg->addError('DB_NOT_UPDATED');
				return false;
			}
			else
			{
				return mysql_insert_id();
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Update an existing user group
	 * @access  public
	 * @param   user_group_id
	 *          title
	 *          description
	 * @return  user id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Update($user_group_id, $title, $description)
	{
		global $addslashes, $msg;

		$missing_fields = array();

		/* email check */
		$user_group_id = intval($user_group_id);
		$title = $addslashes(trim($title));
		$description = $addslashes(trim($description));
		
		/* login name check */
		if ($title == '')
		{
			$missing_fields[] = _AT('title');
		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}

		if (!$msg->containsErrors())
		{
			/* insert into the db */
			$sql = "UPDATE ".TABLE_PREFIX."user_groups
			           SET title = '".$title."',
			               description = '".$description."',
			               last_update = now()
			         WHERE user_group_id = ".$user_group_id;

			return $this->execute($sql);
		}
	}

	/**
	 * Update an existing user group record
	 * @access  public
	 * @param   userGroupID: user group ID
	 *          fieldName: the name of the table field to update
	 *          fieldValue: the value to update
	 * @return  true if successful
	 *          error message array if failed; false if update db failed
	 * @author  Cindy Qi Li
	 */
	public function UpdateField($userGroupID, $fieldName, $fieldValue)
	{
		global $addslashes;
                $group_creator=$_SESSION['user_id'];
		// check if the required fields are filled
		if ($fieldName == 'title' && $fieldValue == '') return array(_AT('TR_ERROR_EMPTY_FIELD'));
		
		$sql = "UPDATE ".TABLE_PREFIX."shared_content_group 
		           SET ".$addslashes($fieldName)."='".$addslashes($fieldValue)."'
		         WHERE group_name = ".$userGroupID." AND group_creator=".$group_creator;
		
		return $this->execute($sql);
	}
	
	/**
	 * delete user group by given user id
	 * @access  public
	 * @param   user group id
	 * @return  true / false
	 * @author  Cindy Qi Li
	 */
	public function Delete($Group_name)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'group_users WHERE group_name = '.$Group_name.' AND group_creator='.$_SESSION['user_id'];	
		return $this->execute($sql);
	}
	
	/**
	 * Return all user groups' information
	 * @access  public
	 * @param   none
	 * @return  usergroup rows
	 * @author  Ayush Datta
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'group_users ORDER BY group_name';
		return $this->execute($sql);
	}

	/**
	 * Return user information by given user id
	 * @access  public
	 * @param   user group id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getUserGroupByID($user_group_id)
		{
		$user_group_id = intval($user_group_id);
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_groups WHERE user_group_id='.$user_group_id;
		if ($rows = $this->execute($sql))
		{
			return $rows[0];
		}
	}

	public function isShared($user_id, $cid)
		{
		$user_id = intval($user_id);
		$cid = intval($cid);
		$sql="SELECT content_id FROM ".TABLE_PREFIX."shared_content_group scg, ".TABLE_PREFIX."group_users gu WHERE scg.group_name=gu.group_name AND scg.group_creator=gu.group_creator AND gu.user_id=$user_id AND scg.content_id=$cid";
		$current_share_content_group=$this->execute($sql);
		//find all the content shared with the current user
		$sql="SELECT content_id FROM ".TABLE_PREFIX."shared_content WHERE user_id=$user_id AND content_id=$cid";
		$current_share_content=$this->execute($sql);
		if($current_share_content || $current_share_content_group){
			return true;
		}
		else{
			return false;
		}
	}
}
?>