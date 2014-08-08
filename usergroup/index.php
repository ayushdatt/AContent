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
include_once(TR_INCLUDE_PATH.'classes/DAO/GroupsUsersDAO.class.php');
include_once(TR_INCLUDE_PATH.'classes/DAO/SharedContentDAO.class.php');
unset($_SESSION['course_id']);

// initialize constants
$results_per_page = 50;
$dao = new DAO();
$groupsUsersDAO = new GroupsUsersDAO();
$group_creator=$_SESSION['user_id'];

if ( (isset($_GET['edit']) || isset($_GET['view'])) && (isset($_GET['id']) && count($_GET['id']) > 1) ) {
	$msg->addError('SELECT_ONE_ITEM');
}
else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: edit_user_group.php?id='.$_GET['id'][0]);
	exit;
} else if (isset($_GET['view'], $_GET['id'])) {
	header('Location: view_user_group.php?id='.$_GET['id'][0]);
	exit;
} else if ( isset($_GET['delete'], $_GET['id'])) {
	$ids = implode(',', $_GET['id']);
	header('Location: usergroup_delete.php?id='.$ids);
	exit;
}
else if( (isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['view'])) && (!isset($_GET['id']))){
	$msg->addError('NO_ITEM_SELECTED');
}

// page initialize
if ($_GET['reset_filter']) {
	unset($_GET);
}

$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('group_name' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 'group_name';
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 'group_name';
} else {
	// no order set
	$order = 'asc';
	$col   = 'group_name';
}

if ($_GET['search']) {
	$page_string .= htmlspecialchars(SEP).'search='.urlencode($stripslashes($_GET['search']));
	$search = $addslashes($_GET['search']);
	$sql = '';
	$term = trim($search);
        $term = str_replace(array('%','_'), array('\%', '\_'), $term);
        if ($term) {
			$term = '%'.$term.'%';
			$sql .= "(group_name LIKE '$term')";
		}
	$search = $sql;
} else {
	$search = '1';
}

$rows = $groupsUsersDAO->getGroupCount($group_creator,$search);
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

$sql = "SELECT distinct(group_name)
          FROM ".TABLE_PREFIX."group_users
          WHERE $search AND group_creator=$group_creator ORDER BY $col $order LIMIT $offset, $results_per_page";

$user_rows = $dao->execute($sql);
                
$savant->assign('user_rows', $user_rows);
$savant->assign('all_user_groups', $groupsUsersDAO->getAll());
$savant->assign('results_per_page', $results_per_page);
$savant->assign('num_results', $num_results);
$savant->assign('page',$page);
$savant->assign('page_string', $page_string);
$savant->assign('orders', $orders);
$savant->assign('order', $order);
$savant->assign('col', $col);

$savant->display('usergroup/index.tmpl.php');

?>