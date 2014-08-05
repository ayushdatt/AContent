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

global $_current_user;
global $_base_path;

if ( isset($_current_user) )
{
?>
	<div class="input-form">
		<form id="share_content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<h3><u>Shared Content with you</u></h3><br>
		<table><tbody>
		<?php
		$output = '';
		$session_user_id = $_SESSION['user_id'];
		// retrieve data to display
		$courseContentDAO = new ContentDAO();
		$usersDao = new UsersDAO();
		foreach ($this->current_share_content as $row) {
			if($row['content_author_id']===$session_user_id){
				continue;
			}
			$output .= "<tr><td>";
			$currCourse = $courseContentDAO->get($row['content_id']);
			$output .= "<a href='".TR_BASE_HREF."home/course/content.php?_cid=".$currCourse['content_id']."'>".$currCourse['title']."</a>";
                        $output .= " by ".$usersDao->getUserName($row['content_author_id']);
			$output .= "&nbsp;&nbsp; <input type='button' value='Edit' onclick=\"document.location.href='".TR_BASE_HREF."home/editor/edit_content.php?_cid=".$currCourse['content_id']."';\"/>";
			$output .= "</td></tr>";
		}
		echo $output;
		?>
		</tbody></table>
		</form>
	</div>

<script language="javascript" type="text/javascript">
function openWindow(page) {
	newWindow = window.open(page, "progWin", "width=400,height=200,toolbar=no,location=no");
	newWindow.focus();
}
</script>

<?php
}
?>