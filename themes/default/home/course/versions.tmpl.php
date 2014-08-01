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
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
<div class="input-form">
<fieldset class="group_form">
	<table id="page__revisions" class="form-data" align="center">
	<?php
	$variable=["temp","tempdata","tempdata","temp"];
	foreach ($variable as $key => $value) {
	?>
        <tr>
                <td align="left"><input type="checkbox" name="cid[]" value="1" onclick="maxTwoVersionSelection()"></td>
                <td align="left"><label for="copyright"><?php echo _AT('course_copyright'); ?></label></td>	  	
        </tr>
	<?php
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
