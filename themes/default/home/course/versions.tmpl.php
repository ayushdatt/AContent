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
global $languageManager;

require_once(TR_INCLUDE_PATH.'classes/CoursesUtility.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');
?>

<form method="post" action="<?php echo TR_BASE_HREF.'home/course/versions_difference.php'; ?>" name="form">
<div class="input-form">
<fieldset class="group_form">
	<table id="page__revisions" class="form-data" align="center">
	<?php
        $url=TR_BASE_HREF."home/editor/edit_content.php?_cid=".$this->cid;
	//print_r($this->revision_info);
	$users = new UsersDAO();
        $checkIfFirst=0;
	foreach ($this->revision_info as $key => $value) {
		//echo "<br>";
		//print_r($value);
              $url1=$url;
            if($checkIfFirst===1){
//                //if the latest version is not selected then send the version id of the pervious version
                  $url1.="&_vid=".$value[0];
            }
	?>
        <tr>
                <td align="left"><input type="checkbox" name="vid[]" value="<?php echo $this->cid.'_'.$value[0]; ?>" onclick="maxTwoVersionSelection()"><?php
                //echo $value[0]."\t";
                echo date('d-m-Y', $value[0])."\t";
                echo date('H:i:s', $value[0])."\t";
                ?><a href='<?php echo $url1; ?>'><?php echo $this->title_content."\t";?></a><?php
                if($value[2]=='A'){
                    echo _AT('created_by').$users->getUserName($value[1]);
                }
                else if($value[2]=='E'){
                    echo _AT('edited_by').$users->getUserName($value[1]);
                }
                else if($value[2]=='R'){
                    echo _AT('edited_by').$users->getUserName($value[1])."\t";
                    if($value[3]){
                        echo _AT('reverted_from');
                        echo date('d-m-Y', $value[3])."\t";
                        echo date('H:i:s', $value[3]);
                    }
                }
                if($checkIfFirst===0){
                    echo "<strong>\t"._AT('current')."</strong>";
                }
                ?></td>
        </tr>
	<?php
        $checkIfFirst=1;
	}
	?>
    </table>
    <table id="page__revisions__submit" class="form-data" align="center">
        <tr>
	        <td colspan="1">
	            <p class="submit_button">
	                <input type="submit" name="submit" value="<?php echo _AT('show_differences_between_verions'); ?>" class="submit" disabled/>
	            </p>
	        </td>
        </tr>
	</table>

</fieldset>
</div>
</form>

<script type="text/javascript">
function maxTwoVersionSelection(){
    var $checked = jQuery('#page__revisions input[type=checkbox]:checked');
    var $all     = jQuery('#page__revisions input[type=checkbox]');
    if($checked.length < 2){
        $all.attr('disabled',false);
        jQuery('#page__revisions__submit input[type=submit]').attr('disabled',true);
    }else{
        $all.attr('disabled',true);
        jQuery('#page__revisions__submit input[type=submit]').attr('disabled',false);
        for(var i=0; i<$checked.length; i++){
            $checked[i].disabled = false;
            if(i>1){
                $checked[i].checked = false;
            }
        }
    }
}
</script>
