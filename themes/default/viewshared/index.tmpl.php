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
		$session_user_id = $_SESSION['user_id'];
		// retrieve data to display
		$courseContentDAO = new ContentDAO();
		$usersDao = new UsersDAO();
		foreach ($this->current_share_content as $row) {
			if($row['content_author_id']===$session_user_id){
				continue;
			}
			echo "<tr><td>";
			$currCourse = $courseContentDAO->get($row['content_id']);
			echo "<a href='".TR_BASE_HREF."home/course/content.php?_cid=".$currCourse['content_id']."'>".$currCourse['title']."</a>";
                        echo $output;
                        echo _AT('shared_with_me',$usersDao->getUserName($row['content_author_id']));
			echo "&nbsp;&nbsp; <input type='button' value='"._AT('edit')."' onclick=\"document.location.href='".TR_BASE_HREF."home/editor/edit_content.php?_cid=".$currCourse['content_id']."';\"/>";
			echo "</td></tr>";
		}
		
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