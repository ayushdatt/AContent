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
 * @author	Ayush Datta
 * @package	DAO
 */

if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class SharedContentDAO extends DAO {

	/**
	 * Update an existing user group record
	 * @access  public
	 * @param   userGroupID: user group ID
	 *          fieldName: the name of the table field to update
	 *          fieldValue: the value to update
	 * @return  true if successful
	 *          error message array if failed; false if update db failed
	 * @author  Ayush Datta
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
	 * @param   group name
	 * @return  true / false
	 * @author  Ayush Datta
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
	 * Validate if the content is shared with the logged in user.
	 * @access  public
	 * @param   user id, content id
	 * @return  Returns true if content is shared with logged in user
	 * @author  Ayush Datta
	 */
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
        
        /**
	 * Returns all the content shared with the logged in user in any group
	 * @access  public
	 * @param   logged in user_id
	 * @return  Returns all the content shared with the logged in user
	 * @author  Ayush Datta
	 */
	public function getAllContentSharedGroup($session_user_id)
	{
            $sql="SELECT DISTINCT(content_id), gu.group_creator AS content_author_id FROM ".TABLE_PREFIX."shared_content_group scg, ".TABLE_PREFIX."group_users gu WHERE scg.group_name=gu.group_name AND scg.group_creator=gu.group_creator AND gu.user_id=$session_user_id ORDER BY content_id";
            return $this->execute($sql);
	}
        
        /**
	 * Returns all the content shared with the logged in user
	 * @access  public
	 * @param   logged in user_id
	 * @return  Returns all the content shared with the logged in user
	 * @author  Ayush Datta
	 */
	public function getAllContentShared($session_user_id)
	{
            $sql="SELECT DISTINCT(content_id), content_author_id FROM ".TABLE_PREFIX."shared_content WHERE user_id=$session_user_id ORDER BY content_id";
            return $this->execute($sql);
	}
}
?>