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
echo _AT('view_differences');
?>
<select name="difftype" onchange="javascript: submit()">
<option value="side"<?php if(($_POST['difftype']=="side") || (!$_POST['difftype'])){
echo "selected='selected'";
}?>><?php echo _AT('side_by_side'); ?></option>
<option value="inline" <?php if($_POST['difftype']=="inline") echo "selected='selected'";?>><?php echo _AT('inline'); ?></option>
</select>

<select name="difftype1" onchange="javascript: submit()">
<option value="html"<?php if(($_POST['difftype1']=="html") || (!$_POST['difftype1'])){
echo "selected='selected'";
}?>><?php echo _AT('html'); ?></option>

<option value="text" <?php if($_POST['difftype1']=="text") echo "selected='selected'";?>><?php echo _AT('text'); ?></option>
</select>

<?php
   if(isset($_POST['difftype'])){
       if(isset($_POST['difftype1'])){
           html_diff($cid,$vid2,$vid1,$_POST['difftype'],$_POST['difftype1']); 
       }
       else{
           html_diff($cid,$vid2,$vid1,$_POST['difftype']); 
       }
   }
   else{
       if(isset($_POST['difftype1'])){
           html_diff($cid,$vid2,$vid1,null,$_POST['difftype1']); 
       }
       else{
           html_diff($cid,$vid2,$vid1); 
       }
   }
?>
</fieldset>
</div>
</form>