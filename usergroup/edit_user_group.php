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
include(TR_INCLUDE_PATH.'vitals.inc.php');
include(TR_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');
include(TR_INCLUDE_PATH.'classes/DAO/GroupsUsersDAO.class.php');
unset($_SESSION['course_id']);

// initialize constants
$results_per_page = 50;
$dao = new DAO();
$groups_users=new GroupsUsersDAO();

// page initialize
if ($_GET['reset_filter']) {
	unset($_GET);
}
if(isset($_GET['id']))
{
    $group_name=$_GET['id'];
    $_SESSION['group_name']=$group_name;
}
else
{
    $group_name=$_SESSION['group_name'];
}
$group_creator=$_SESSION['user_id'];


if( (isset($_POST['modify_group'])) && (isset($_POST['id'])) && (isset($_SESSION['user_id'])) && (count($_POST['id']) > 0) ){
	extract($_POST);
	$group_creator=$_SESSION['user_id'];
        
        $sql="DELETE FROM ".TABLE_PREFIX."group_users WHERE group_name='$group_name' AND group_creator=$group_creator";
        $result=$dao->execute($sql);
        
            for($i=0;$i<count($_POST['id']);$i++){
                    $groups_users->insertUsers($group_name, $group_creator, $id[$i]);
            }
        $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: index.php');
	exit;
        
}




$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('first_name' => 1, 'last_name' => 1,'email' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 'first_name';
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 'first_name';
} else {
	// no order set
	$order = 'asc';
	$col   = 'first_name';
}

if (isset($_GET['include']) && $_GET['include'] == 'one') {
	$checked_include_one = ' checked="checked"';
	$page_string .= htmlspecialchars(SEP).'include=one';
} else {
	$_GET['include'] = 'all';
	$checked_include_all = ' checked="checked"';
	$page_string .= htmlspecialchars(SEP).'include=all';
}

if ($_GET['search']) {
	$page_string .= htmlspecialchars(SEP).'search='.urlencode($stripslashes($_GET['search']));
	$search = $addslashes($_GET['search']);
	$search = explode(' ', $search);

	if ($_GET['include'] == 'all') {
		$predicate = 'AND ';
	} else {
		$predicate = 'OR ';
	}

	$sql = '';
	foreach ($search as $term) {
		$term = trim($term);
		$term = str_replace(array('%','_'), array('\%', '\_'), $term);
		if ($term) {
			$term = '%'.$term.'%';
			$sql .= "((first_name LIKE '$term') OR (last_name LIKE '$term') OR (email LIKE '$term')) $predicate";
		}
	}
	$sql = '('.substr($sql, 0, -strlen($predicate)).')';
	$search = $sql;
} else {
	$search = '1';
}

$sql	= "SELECT COUNT(GU.user_id) AS cnt FROM ".TABLE_PREFIX."users U , ".TABLE_PREFIX."group_users GU"
        . "  WHERE U.user_id=GU.user_id AND $search AND GU.group_creator=$group_creator AND GU.group_name='$group_name'";

$rows = $dao->execute($sql);
$num_results_selected = $rows[0]['cnt'];


$sql	= "SELECT COUNT(user_id) AS cnt FROM ".TABLE_PREFIX."users WHERE $search AND user_id!=$group_creator";

$rows = $dao->execute($sql);
$num_results = $rows[0]['cnt'];

$num_pages = max(ceil($num_results / $results_per_page), 1);
$page = intval($_GET['p']);
if (!$page) {
	$page = 1;
}	
$count  = (($page-1) * $results_per_page) + 1;
$offset = ($page-1)*$results_per_page;

if ( isset($_GET['apply_all']) && $_GET['change_status'] >= -1) {
	$offset = 0;
	$results_per_page = 999999;
}

$sql = "SELECT U.user_id, U.first_name, U.last_name, U.email
          FROM ".TABLE_PREFIX."users U,".TABLE_PREFIX."group_users GU"
          . " WHERE U.user_id=GU.user_id AND GU.group_creator=$group_creator AND GU.group_name='$group_name' AND $search ORDER BY $col $order LIMIT $offset, $results_per_page";

$user_rows_selected = $dao->execute($sql);

$sql = "SELECT user_id, first_name, last_name, email
          FROM ".TABLE_PREFIX."users
          WHERE $search AND user_id!=$group_creator ORDER BY $col $order LIMIT $offset, $results_per_page";

$user_rows = $dao->execute($sql);


if ( isset($_GET['apply_all']) && $_GET['change_status'] >= -1) {
	$ids = '';
	while ($row = mysql_fetch_assoc($result)) {
		$ids .= $row['user_id'].','; 
	}
	$ids = substr($ids,0,-1);
	$status = intval($_GET['change_status']);

	if ($status==-1) {
		header('Location: user_delete.php?id='.$ids);
		exit;
	} else {
		header('Location: user_status.php?ids='.$ids.'&status='.$status);
		exit;
	}
}

$userGroupsDAO = new UserGroupsDAO();

$savant->assign('user_rows', $user_rows);
$savant->assign('user_rows_selected', $user_rows_selected);
$savant->assign('all_user_groups', $userGroupsDAO->getAll());
$savant->assign('results_per_page', $results_per_page);
$savant->assign('num_results', $num_results);
$savant->assign('num_results_selected', $num_results_selected);
$savant->assign('checked_include_all', $checked_include_all);
$savant->assign('col_counts', $col_counts);
$savant->assign('page',$page);
$savant->assign('page_string', $page_string);
$savant->assign('orders', $orders);
$savant->assign('order', $order);
$savant->assign('col', $col);
$savant->assign('group_name', $group_name);

$savant->display('usergroup/edit_user_group.tmpl.php');

?>
