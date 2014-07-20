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

global $_custom_css;
$_custom_css = TR_BASE_HREF."include/jscripts/infusion/components/inlineEdit/css/InlineEdit.css";

include(TR_INCLUDE_PATH.'header.inc.php');
?>

<div class="input-form">
	<form name="filter_form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<fieldset class="group_form"><legend class="group_form"><?php echo _AT("filter"); ?></legend>
		<table class="filter">
		<tr>
			<td colspan="2"><h2><?php echo _AT('present_group_name', $this->group_name); ?></h2></td>
		</tr>
                
                <tr>
			<td colspan="2"><h2><?php echo _AT('results_found', $this->num_results); ?></h2></td>
		</tr>

		<tr>
			<th><label for="search"><?php echo _AT('search'); ?>:</label></th>
			<td><input type="text" name="search" id="search" size="40" value="<?php echo htmlspecialchars($_GET['search']); ?>" /><br /><small>&middot; <?php echo _AT('first_name').', '._AT('last_name') .', '._AT('email'); ?></small></td>
		</tr>

		<tr>
			<td colspan="2" align="center">
			<input type="radio" name="include" value="all" id="match_all" <?php echo $this->checked_include_all; ?> /><label for="match_all"><?php echo _AT('match_all_words'); ?></label> 
			<input type="radio" name="include" value="one" id="match_one" <?php echo $this->checked_include_one; ?> /><label for="match_one"><?php echo _AT('match_any_word'); ?></label>
			</td>
		</tr>

		<tr>
			<td colspan="2"><p class="submit_button">
			<input type="submit" name="filter" value="<?php echo _AT('filter'); ?>" />
			<input type="submit" name="reset_filter" value="<?php echo _AT('reset_filter'); ?>" />
			</p></td>
		</tr>
		</table>
	</fieldset>
</form>
</div>
	
<div id="output_div" class="input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AT("users"); ?></legend>
<?php print_paginator($this->page, $this->num_results, $this->page_string . htmlspecialchars(SEP) . $this->order .'='. $this->col, $this->results_per_page); ?>

<form name="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <table class="filter">  
    <tr>
			<th><label for="group_name"><?php echo _AT('group_name'); ?>:</label></th>
			<td><input type="text" name="group_name" id="group_name" size="40" value="<?php echo htmlspecialchars($_GET['group_name']); ?>" /></td>
    </tr>
  </table>  
<br>
<input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>" />
<input type="hidden" name="include" value="<?php echo htmlspecialchars($_GET['include']); ?>" />

<table summary="<?php echo _AT('user_table_summary'); ?>" class="data" rules="rows">
<colgroup>
	<?php if($this->col == 'public_field'): ?>
		<col span="<?php echo 1 + $this->col_counts; ?>" />
		<col class="sort" />
		<col span="6" />
	<?php elseif($this->col == 'first_name'): ?>
		<col span="<?php echo 2 + $this->col_counts; ?>" />
		<col class="sort" />
		<col span="5" />
	<?php elseif($this->col == 'last_name'): ?>
		<col span="<?php echo 3 + $this->col_counts; ?>" />
		<col class="sort" />
		<col span="4" />
	<?php elseif($this->col == 'email'): ?>
		<col span="<?php echo 5 + $this->col_counts; ?>" />
		<col class="sort" />
		<col span="2" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col" align="left" width="5%"><input type="checkbox" value="<?php echo _AT('select_all'); ?>" id="all" title="<?php echo _AT('select_all'); ?>" name="selectall" onclick="CheckAll();" /></th>

	<th scope="col" width="15%"><a href="usergroup/create_author_user_group.php?<?php echo $this->orders[$this->order]; ?>=first_name<?php echo $page_string; ?>"><?php echo _AT('first_name'); ?></a></th>
	<th scope="col" width="10%"><a href="usergroup/create_author_user_group.php?<?php echo $this->orders[$this->order]; ?>=last_name<?php echo $page_string; ?>"><?php echo _AT('last_name');   ?></a></th>
	<th scope="col" width="15%"><a href="usergroup/create_author_user_group.php?<?php echo $this->orders[$this->order]; ?>=email<?php echo $page_string; ?>"><?php echo _AT('email');           ?></a></th>
</tr>

</thead>
<?php if ($this->num_results > 0): ?>
	<tfoot>
	<tr>
		<td>
                    <input type="submit" name="submitusergroup" value="<?php echo _AT('create_group'); ?>" /> 
		</td>
	</tr>
	</tfoot>
	<tbody>
		<?php if (is_array($this->user_rows)){ foreach ($this->user_rows as $row) {?>
			<tr onmousedown="document.form['m<?php echo $row['user_id']; ?>'].checked = !document.form['m<?php echo $row['user_id']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['user_id']; ?>');" 
			    onkeydown="document.form['m<?php echo $row['user_id']; ?>'].checked = !document.form['m<?php echo $row['user_id']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['user_id']; ?>');"
			    id="rm<?php echo $row['user_id']; ?>">
				<td><input type="checkbox" name="id[]" value="<?php echo $row['user_id']; ?>" id="m<?php echo $row['user_id']; ?>" 
				           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
				<td><?php echo $row['first_name']; ?></td>
				<td><?php echo $row['last_name']; ?></td>
				<td><?php echo $row['email']; ?></td>
			</tr>
		<?php }} ?>
	</tbody>
<?php else: ?>
	<tr>
		<td colspan="<?php echo 8 + $this->col_counts; ?>"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
</table><br />
<small class="data-table-tip"><?php echo _AT('inline_editor_tip'); ?></small>


</form>
</fieldset>
</div>

<script language="JavaScript" type="text/javascript">
//<!--
function CheckAll() {
	for (var i=0;i<document.form.elements.length;i++)	{
		var e = document.form.elements[i];
		if ((e.name == 'id[]') && (e.type=='checkbox')) {
			e.checked = document.form.selectall.checked;
			togglerowhighlight(document.getElementById("r" + e.id), e.id);
		}
	}
}

function togglerowhighlight(obj, boxid) {
	if (document.getElementById(boxid).checked) {
		obj.className = 'selected';
	} else {
		obj.className = '';
	}
}

//-->
</script>
<?php require(TR_INCLUDE_PATH.'footer.inc.php'); ?>