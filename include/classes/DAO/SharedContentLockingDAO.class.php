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
 * DAO for "shared_content_locking" table
 * @access	public
 * @author	Ayush Datt
 * @package	DAO
 */

if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class SharedContentLockingDAO extends DAO {

	/**
	 * Inserts a new entry if does not exist or update the entry
	 * @access  public
	 * @param   content_id
	 *          user_id
	 * @return  true, if successful
	 *          false, if unsuccessful
	 * @author  Ayush Datt
	 */
	public function insertUpdate($content_id, $user_id)
	{
		$date = new DateTime("now");
		$date->setTimezone(new DateTimeZone('UTC'));
		$sql = "SELECT * FROM ".TABLE_PREFIX."shared_content_locking WHERE content_id=$content_id";
		$result=$this->execute($sql);
		if( $result ){//content id does not exist so insert
			if($this->canEdit($content_id, $user_id, $date->date)){
				$sql = "UPDATE ".TABLE_PREFIX."shared_content_locking SET user_id=$user_id, last_modified='".$date->format('Y-m-d H:i:s')."' WHERE content_id=$content_id";
				$result=$this->execute($sql);
				if($result){
					return true;//updated
				}
				else{
					return false;//could not update
				}
			}
			else{
				return false;//could not update
			}
		}
		else{
			$sql = "INSERT INTO ".TABLE_PREFIX."shared_content_locking(
				content_id,
				user_id,
				last_modified
				)
				VALUES(
					$content_id,
					$user_id,
					'$date->date'
				)";
			$result=$this->execute($sql);
			if($result){
				return true;//inserted
			}
			else{
				return false;//could not insert
			}
		}
	}

	/**
	 * Checks if the user can edit the content id
	 * @access  public
	 * @param   content_id
	 *          user_id
	 *			time
	 * @return  true, if possible
	 *          false, if not possible
	 * @author  Ayush Datt
	 */
	public function canEdit($content_id, $user_id, $time)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."shared_content_locking WHERE content_id=$content_id";
		$result=$this->execute($sql);
		if($result){
			if($result[0]['user_id'] == $user_id){//the current user is the last user who was editing the document so he can edit the document again
				return true;
			}
			else{//this is a new user who is asking for permission
				$date1 = new DateTime("now");
				$date1->setTimezone(new DateTimeZone('UTC'));
				$date2 = date_create($result[0]['last_modified']);
				$sec = strtotime($date1->format('Y-m-d H:i:s'))-strtotime($date2->format('Y-m-d H:i:s'));
				if($sec > 15*60){//15 minutes of editing are over
					return true;
				}
				else{//15 minutes of editing are still left so the current user requesting cannot edit
					return false;
				}
			}
		}
		else{
			return false;
		}
	}

	/**
	 * Deletes the entry
	 * @access  public
	 * @param   content_id
	 *          user_id
	 * @return  true, if successful
	 *          false, if unsuccessful
	 * @author  Ayush Datt
	 */
	public function Delete($content_id, $user_id)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."shared_content_locking WHERE content_id=$content_id AND user_id=$user_id";	
		return $this->execute($sql);
	}
}
?>