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
                    <h3><u><?php echo _AT('pages_shared_with_me')?></u></h3><br>
		<table><tbody>
		<?php
                if(is_array($this->current_share_content)){
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
                        //echo $output;
                        echo _AT('under_lesson',$row['course_title']);
                        echo _AT('shared_with_me',$usersDao->getUserName($row['content_author_id']));
                        echo "&nbsp;&nbsp; <input type='button' value='"._AT('edit')."' onclick=\"document.location.href='".TR_BASE_HREF."home/editor/edit_content.php?_cid=".$currCourse['content_id']."';\"/>";
                        echo "</td></tr>";
                    }    
                }
                else{
                    echo _AT('none_found');
                }
		?>
		</tbody></table>
		</form>
	</div>
<?php
}
?>