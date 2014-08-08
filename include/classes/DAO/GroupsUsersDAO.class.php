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
 * DAO for "group_users" table
 * @access	public
 * @author	Ayush Datta
 * @package	DAO
 */

if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class GroupsUsersDAO extends DAO {

	/**
	 * Create a new user group
	 * @access  public
	 * @param   group_name: Name of the group
	 *          group_creator: Current loggedin user
         *          user_id: user to be inserted into the group
	 * @return  true if successful
         *          error message array if failed; false if update db failed
	 * @author  Ayush Datta
	 */
        public function insertUsers($group_name, $group_creator, $id){
            $sql="INSERT INTO ".TABLE_PREFIX."group_users (group_name, group_creator, user_id)
                                                    VALUES ('".$group_name."',".$group_creator.",".$id.")";
            return $this->execute($sql);
        }
	
        
	/**
	 * Update an existing user groupname 
	 * @access  public
	 * @param   GroupName: name of the user group to be updated
	 *          fieldName: the name of the table field to update
	 *          fieldValue: the value to update
	 * @return  true if successful
	 *          error message array if failed; false if update db failed
	 * @author  Ayush Datta
	 */
	public function UpdateField($GroupName, $fieldName, $fieldValue)
	{
		global $addslashes;
                $group_creator=$_SESSION['user_id'];
		// check if the required fields are filled
		if ($fieldName == 'title' && $fieldValue == '') return array(_AT('TR_ERROR_EMPTY_FIELD'));
		
		$sql = "UPDATE ".TABLE_PREFIX."group_users 
		           SET ".$addslashes($fieldName)."='".$addslashes($fieldValue)."'
		         WHERE group_name = ".$GroupName." AND group_creator=".$group_creator;
		
		return $this->execute($sql);
	}
	
	/**
	 * delete user group by given group name created by logged in user
	 * @access  public
	 * @param   user group_name
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
	 * @return  group_users rows
	 * @author  Ayush Datta
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'group_users ORDER BY group_name';
		return $this->execute($sql);
	}

        /**
	 * Return if the user group is existing or not
	 * @access  public
	 * @param   group_name: the name of the group user wants to create
         *          group_creator: presently loggid in user
	 * @return  true if user group exits and false if user group does not exist
	 * @author  Ayush Datta
	 */
        public function getUsersofGroup($group_name,$group_creator)
        {
            $sql = "SELECT * FROM ".TABLE_PREFIX."group_users WHERE group_name='$group_name' AND group_creator=$group_creator";
            if ($result=$this->execute($sql))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
        /**
	 * Return if the user group is existing or not
	 * @access  public
	 * @param   group_creator: presently loggid in user
         *          search: search tags searched by user
	 * @return  count of rows
	 * @author  Ayush Datta
	 */
        public function getGroupCount($group_creator,$search)
        {
            $sql = "SELECT COUNT(distinct(group_name)) as cnt FROM ".TABLE_PREFIX."group_users WHERE group_creator=$group_creator AND $search";
            return $this->execute($sql);
        }
}
?>