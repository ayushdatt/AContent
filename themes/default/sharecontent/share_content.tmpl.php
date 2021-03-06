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
    	extract($_GET);
    ?>
<div class="input-form">
    <form id="share_content" action="<?php echo $_SERVER['PHP_SELF']."?_course_id=$_course_id"; ?>" method="POST">
        <div class="input-form" style="margin-top: 20px;">
            <h3><u><?php echo _AT('select_content_to_share');?></u></h3>
            <br>
                    <?php
                        $userCoursesDAO = new UserCoursesDAO();
                        $output = '';
                        $session_user_id = $_SESSION['user_id'];
                        // retrieve data to display
                        if($this->selected_course)
                        	$my_courses = $userCoursesDAO->get($session_user_id, $this->selected_course); 
                        else
                        	$my_courses = false;
                        if (!is_array($my_courses)) {
                        	$num_of_courses = 0;
                        	$output = _AT('none_found');
                        } else {
                        	$num_of_courses = count($my_courses);
                        	$coursesDao = new CoursesDAO();
                        	$courseDetails = $coursesDao->get($my_courses['course_id']);
                        	$row = $my_courses;
                        	$courseContentDAO = new ContentDAO();
                        	$num_spaces=array();//tells the number of spaces to be inserted according to the content parent id
                        	$num_spaces[0]=0;
                        	// only display the first 200 character of course description
                        	if ($row['role'] == TR_USERROLE_AUTHOR) {
                        		$output .= "<div><input id=\"-1\" type=\"checkbox\" onclick=\"recursiveSelect(-1)\"><strong>".$courseDetails['title']."</strong>";
                        		$contents=$courseContentDAO->getContentByCourseID($courseDetails['course_id']);
                        
                        		function getHtmlTree($contentStructure, $parent, &$output, &$num_spaces){
                        			//print_r($contentStructure);
                        			foreach($contentStructure as $content){
                        				if($content['content_parent_id']===$parent){
                                            $output .= "<div>";
                        					for($i=0; $i<$num_spaces[$content['content_parent_id']];$i++){
                        						$output .= "<img src='".$_base_path."images/tree/tree_space.gif'>";
                        					}
                        					$output .= "
                        						<img src='".$_base_path."images/tree/tree_end.gif'>
                        						<img src='".$_base_path."images/tree/tree_horizontal.gif'>
                        					";
                        					if($content['content_type']==CONTENT_TYPE_CONTENT){
                        						$output .= "<input name=\"share_content_id[]\" value=\"".$content['content_id']."\" type=\"checkbox\">".$content['title'];
                                            }
                        					else if($content['content_type']==CONTENT_TYPE_FOLDER){
                                                $output .= "<input id=\"".$content['content_id']."\" type=\"checkbox\" onclick=\"recursiveSelect(".$content['content_id'].")\">";
                        						$output .= "<strong>".$content['title']."</strong>";
                        						$num_spaces[$content['content_id']]=$num_spaces[$content['content_parent_id']]+1;
                                                getHtmlTree($contentStructure, $content['content_id'], $output, $num_spaces);
                        					}
                                            $output .= "</div>";
                        				}
                        			}
                        		}
                        		getHtmlTree($contents, "0", $output, $num_spaces);
                        	} else {
                        		echo "You Are Not the Author Of this course";
                        	}
                        }
                        echo $output;
                        echo "</div>";
                        ?>
        </div>
        <div class="input-form" style="margin-bottom: 10px">
            <table>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <div class="input-form" style="border-radius: 5px;">
                                <h3><u><?php echo _AT('select_groups_to_share_with'); ?></u></h3>
                                <br>
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
                                            			$output .= "<tr><td><input name=\"share_group_name[]\" value=\"".$row['group_name']."\" type=\"checkbox\"></td><td><strong>$cur_group</strong></td></tr>";
                                            			$prev_group=$cur_group;
                                            		}
                                            		if($prev_group !== $cur_group){
                                            			$output .= "<tr><td><input name=\"share_group_name[]\" value=\"".$row['group_name']."\" type=\"checkbox\"></td><td><strong>$cur_group</strong></td></tr>";
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
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <div class="input-form" style="border-radius: 5px;">
                                <h3><u><?php echo _AT('select_users_to_share_with'); ?></u></h3>
                                <br>
                                <table>
                                    <tbody>
                                        <?php
                                            $output='';
                                            $result=$usersDAO->getAll();
                                            $flagFoundUsers = 0;
                                            foreach ($result as $row) {
                                            	if($row['user_id']===$session_user_id){
                                            		continue;
                                            	}
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
                                            
                                            	$output .= "<tr><td><input name=\"share_user_id[]\" value=\"".$row['user_id']."\" type=\"checkbox\"></td><td>$user_name</td></tr>";								
                                            }
                                            if($flagFoundUsers === 0){
                                            	echo "No Users Found";
                                            }
                                            echo $output;
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <input type="submit" name="share_submit" value="Share Content" style="margin-left:20px">
    </form>
</div>
<script language="javascript" type="text/javascript">
    function recursiveSelect(id){
        // Whenever the parent checkbox is checked/unchecked
        // save state of parent
        c = $("#"+id).is(':checked');
        console.log($("#"+id).parent().find("input[type=checkbox]"));
        $("#"+id).parent().find("input[type=checkbox]").each(function() {
            // set state of siblings
            $(this).attr('checked', c);
        });
    }
</script>
<?php 
    }
?>