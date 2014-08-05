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
<form method="post" action="<?php echo TR_BASE_HREF.'home/course/versions_difference.php?_cid='.$this->cid; ?>" name="form">
<div class="input-form">
<fieldset class="group_form">
<?php 

$vid1=$_POST['vid'][0];
$vid2=$_POST['vid'][1];
$cid=$_GET['_cid'];
if(isset($vid1) && isset($vid2) && isset($cid)){
    html_diff($cid,$vid2,$vid1); 
}

?>
</fieldset>
</div>
</form>