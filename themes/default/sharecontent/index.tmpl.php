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

if (isset($_current_user) && ($_current_user->isAuthor() || $_current_user->isAdmin()))
{
?>
	<div class="input-form">
		<form id="share_content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <h3><u><?php echo _AT('select_content_to_share');?></u></h3><br>
		<table><tbody>
		<?php
		$userCoursesDAO = new UserCoursesDAO();
		$output = '';
		$session_user_id = $_SESSION['user_id'];
		// retrieve data to display
		$my_courses = $userCoursesDAO->getByUserID($session_user_id); 

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
					$output .= "<tr><td><h4>".$row['title']."</h4></td></tr>";
					$contents=$courseContentDAO->getContentByCourseID($row['course_id']);
					foreach($contents as $content){
						$output .= "<tr><td>";
						for($i=0; $i<$num_spaces[$content['content_parent_id']];$i++){
							$output .= "<img src='".$_base_path."images/tree/tree_space.gif'>";
						}
						$output .= "
							<img src='".$_base_path."images/tree/tree_end.gif'>
							<img src='".$_base_path."images/tree/tree_horizontal.gif'>
						";
						if($content['content_type']==CONTENT_TYPE_CONTENT){
							$output .= "<input name=\"share_content_id[]\" value=".$content['content_id']." type=\"checkbox\">".$content['title'];
						}
						else if($content['content_type']==CONTENT_TYPE_FOLDER){
							$output .= "<strong>".$content['title']."</strong>";
							$num_spaces[$content['content_id']]=$num_spaces[$content['content_parent_id']]+1;
						}
						$output .= "</td></tr>";
					}
				} else {
					echo "in else";
				}
			} // end of foreach; 
		}
		echo $output;
		?>
		</tbody></table>
		<br>
		<table border="1"><tbody><tr>
		<td style="vertical-align: top;">
                    <h3><u><?php echo _AT('select_groups_to_share_with'); ?></u></h3><br>
			<table>
				<tbody>
					<?php
						$output='';
						$dao = new DAO();
						$usersDAO = new UsersDAO();
						$sql="SELECT * FROM ".TABLE_PREFIX."group_users WHERE group_creator=$session_user_id ORDER BY group_name";
						//echo $sql;
						$result=$dao->execute($sql);
						if($result){
							//print_r($result);
							$cur_group='';
							$prev_group='';
							foreach ($result as $row) {
								$cur_group=$row['group_name'];
								if($prev_group === ''){
									$output .= "<tr><td><input name=\"share_group_name[]\" value=".$row['group_name']." type=\"checkbox\"></td><td><strong>$cur_group</strong></td></tr>";
									$prev_group=$cur_group;
								}
								if($prev_group !== $cur_group){
									$output .= "<tr><td><input name=\"share_group_name[]\" value=".$row['group_name']." type=\"checkbox\"></td><td><strong>$cur_group</strong></td></tr>";
									$prev_group=$cur_group;
								}
								$user_id=$usersDAO->getUserName($row['user_id']);
								//$user_id = $row['user_id'];
								if($user_id)//means that the user exists still
									$output .= "<tr><td>
										<img src='".$_base_path."images/tree/tree_space.gif'>
										<img src='".$_base_path."images/tree/tree_end.gif'>
										<img src='".$_base_path."images/tree/tree_horizontal.gif'>
									</td><td>$user_id</td></tr>";
							}
							//do nothing duplicate entry
						}
						else{
							echo "No Groups Found";
						}
						echo $output;
					?>
				</tbody>
			</table>
		</td>
		<td style="vertical-align: top;">
			<h3><u><?php echo _AT('select_users_to_share_with'); ?></u></h3><br>
			<table>
				<tbody>
					<?php
						$output='';
						$result=$usersDAO->getAll();
						$flagFoundUsers = 0;
						foreach ($result as $row) {
							$flagFoundUsers = 1;
							$cur_group=$row['group_name'];
							$user_name='';

							if ($row['first_name'] <> '' && $row['last_name'] <> '')
							{
								$user_name = $row['first_name']. ' '.$row['last_name'];
							}
							else if ($row['first_name'] <> '')
							{
								$user_name = $row['first_name'];
							}
							else if ($row['last_name'] <> '')
							{
								$user_name = $row['last_name'];
							}
							else
							{
								$user_name = $row['login'];
							}

							$output .= "<tr><td><input name=\"share_user_id[]\" value=".$row['user_id']." type=\"checkbox\"></td><td>$user_name</td></tr>";								
						}
						if($flagFoundUsers === 0){
							echo "No Users Found";
						}

						echo $output;
					?>
				</tbody>
			</table>		
		</td>
		</tr></tbody></table>
		<br>
		<input type="submit" value="Share Content">
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