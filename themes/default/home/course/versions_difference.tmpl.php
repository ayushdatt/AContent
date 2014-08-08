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
?>
<form method="post" action="<?php echo TR_BASE_HREF.'home/course/versions_difference.php?_cid='.$_GET['_cid']; ?>" name="form">
<input type="hidden" name="vid[]" value="<?php echo $_POST['vid'][0] ;?>"/>    
<input type="hidden" name="vid[]" value="<?php echo $_POST['vid'][1] ;?>"/>    
<div class="input-form">
<fieldset class="group_form">
<?php 

$vid1=$_POST['vid'][0];
$vid2=$_POST['vid'][1];
$cid=$_GET['_cid'];
if(isset($vid1) && isset($vid2) && isset($cid));
else{
    header('Location: index.php');
}

?>
View Differences:
<select name="difftype" onchange="javascript: submit()">
<option value="side"<?php if(($_POST['difftype']=="side") || (!$_POST['difftype'])){
echo "selected='selected'";
}?>><?php echo _AT('side_by_side'); ?></option>
<option value="inline" <?php if($_POST['difftype']=="inline") echo "selected='selected'";?>><?php echo _AT('inline'); ?></option>
</select>
<?php
   if(isset($_POST['difftype'])){
       html_diff($cid,$vid2,$vid1,$_POST['difftype']); 
   }
   else{
       html_diff($cid,$vid2,$vid1); 
   }
?>
</fieldset>
</div>
</form>