<?php 


//review***************** this file is copied from create_course.tmpl.php and my_course.inc.php and then changes are made to it..

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

if (isset($_current_user) && ($_current_user->isAuthor() || $_current_user->isAdmin()))
{
?>
	<div class="input-form">
		<form id="share_content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<table><tbody>
		<?php
		$userCoursesDAO = new UserCoursesDAO();
		$output = '';
		// retrieve data to display
		$my_courses = $userCoursesDAO->getByUserID($_SESSION['user_id']); 

		if (!is_array($my_courses)) {
			$num_of_courses = 0;
			$output = _AT('none_found');
		} else {
			$num_of_courses = count($my_courses);
			$courseContentDAO = new ContentDAO();
			$num_spaces=array();//tells the number of spaces to be inserted according to the content parent id
			$num_spaces[0]=0;
		    foreach ($my_courses as $row) {
				// only display the first 200 character of course description
				if ($row['role'] == TR_USERROLE_AUTHOR) {
					$output .= "<tr><td><strong>Course Title:- ".$row['title']."</strong></td></tr>";
					$contents=$courseContentDAO->getContentByCourseID($row['course_id']);
					foreach($contents as $content){
						$output .= "<tr>";
						for($i=0; $i<$num_spaces[$content['content_parent_id']];$i++){
							$output .= "<td></td>";
						}
						if($content['content_type']==CONTENT_TYPE_CONTENT){
							$output .= "<td><input name=\"share_content_id\" type=\"checkbox\">".$content['title']." ".$content['content_parent_id']." spaces ".$num_spaces[$content['content_parent_id']]."</td>";
						}
						else if($content['content_type']==CONTENT_TYPE_FOLDER){
							$output .= "<td>Folder Title:- ".$content['title']." ".$content['content_parent_id']." spaces ".$num_spaces[$content['content_parent_id']]."</td>";
							$num_spaces[$content['content_id']]=$num_spaces[$content['content_parent_id']]+1;
						}
						$output .= "</tr>";
					}
				} else {
					echo "in else";
					//$output .= ' <li class="theirs" title="'. _AT('others_course').': '. $row['title'].'">'."\n";
				}
				// $output .= '    <a href="'. TR_BASE_HREF.'home/course/index.php?_course_id='. $row['course_id'].'"'.(($_course_id == $row['course_id']) ? ' class="selected-sidemenu"' : '').'>'.$row['title'].'</a>'."\n";
				// if ($row['role'] == TR_USERROLE_VIEWER) {
				// 	$output .= '    <a href="'. TR_BASE_HREF.'home/'. $caller_url.'action=remove'.SEP.'cid='. $row['course_id'].'">'."\n";
		  		// $output .= '      <img src="'. TR_BASE_HREF.'themes/'. $_SESSION['prefs']['PREF_THEME'].'/images/bookmark_remove.png" alt="'. htmlspecialchars(_AT('remove_from_list')).'" title="'. htmlspecialchars(_AT('remove_from_list')).'" border="0" class="shortcut_icon"/>'."\n";
				// 	$output .= '    </a>'."\n";
				// } 
				// if ($row['role'] == NULL && $_SESSION['user_id']>0) {
				// 	$output .= '    <a href="'. TR_BASE_HREF.'home/'. $caller_url.'action=add'.SEP.'cid='. $row['course_id'].'">'."\n";
				// 	$output .= '      <img src="'. TR_BASE_HREF.'themes/'. $_SESSION['prefs']['PREF_THEME'].'/images/bookmark_add.png" alt="'. htmlspecialchars(_AT('add_into_list')).'" title="'. htmlspecialchars(_AT('add_into_list')).'" border="0"  class="shortcut_icon"/>'."\n";
				// 	$output .= '    </a>'."\n";
				// }

				//already commented before
				//$output .= '    <a href="'. TR_BASE_HREF.'home/ims/ims_export.php?course_id='. $row['course_id'].'">'."\n";
				//$output .= '      <img src="'. TR_BASE_HREF.'themes/'. $_SESSION['prefs']['PREF_THEME'].'/images/export.png" alt="'. _AT('download_content_package').'" title="'. _AT('download_content_package').'" border="0" />'."\n";
				//$output .= '    </a>'."\n";
				//if ($row['role'] == TR_USERROLE_AUTHOR) {
					//$output .= '    <a href="'. TR_BASE_HREF.'home/imscc/ims_export.php?course_id='. $row['course_id'].'">'."\n";
					//$output .= '      <img src="'. TR_BASE_HREF.'themes/'. $_SESSION['prefs']['PREF_THEME'].'/images/export_cc.png" alt="'. _AT('download_common_cartridge').'" title="'. _AT('download_common_cartridge').'" border="0" />'."\n";
					//$output .= '    </a>'."\n";
				//}
			} // end of foreach; 
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