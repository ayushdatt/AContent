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
			<td colspan="2"><h2><?php echo _AT('usergroups_found', $this->num_results); ?></h2></td>
		</tr>

		<tr>
			<th><label for="search"><?php echo _AT('search'); ?>:</label></th>
			<td><input type="text" name="search" id="search" size="40" value="<?php echo htmlspecialchars($_GET['search']); ?>" /><br /></td>
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

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>" />

<table summary="<?php echo _AT('user_table_summary'); ?>" class="data" rules="rows" id="editable_table">
<colgroup>
	<?php if ($this->col == 'group_name'): ?>
		<col />
		<col class="sort" />
		<col span="<?php echo 2 + $this->col_counts; ?>" />
	<?php elseif($this->col == 'public_field'): ?>
		<col span="<?php echo 2 + $this->col_counts; ?>" />
		<col class="sort" />
		<col span="1" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col" align="left" width="5%"><input type="checkbox" value="<?php echo _AT('select_all'); ?>" id="all" title="<?php echo _AT('select_all'); ?>" name="selectall" onclick="CheckAll();" /></th>
	<th scope="col" width="15%"><a href="usergroup/index.php?<?php echo $this->orders[$this->order]; ?>=group_name<?php echo $page_string; ?>"><?php echo _AT('groupname');      ?></a></th>
</tr>

</thead>
<?php if ($this->num_results > 0): ?>
	<tfoot>
	<tr>
		<td colspan="<?php echo 3 + $this->col_counts; ?>">
			<input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" /> 
			<input type="submit" name="view" value="<?php echo _AT('view'); ?>" />
			<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
		</td>
	</tr>
	</tfoot>
	<tbody>
		<?php if (is_array($this->user_rows)){ foreach ($this->user_rows as $row) {?>
			<tr onmousedown="document.form['m<?php echo $row['group_name']; ?>'].checked = !document.form['m<?php echo $row['group_name']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['group_name']; ?>');" 
			    onkeydown="document.form['m<?php echo $row['group_name']; ?>'].checked = !document.form['m<?php echo $row['group_name']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['group_name']; ?>');"
			    id="rm<?php echo $row['group_name']; ?>">
				<td><input type="checkbox" name="group_name[]" value="<?php echo $row['group_name']; ?>" id="m<?php echo $row['group_name']; ?>" 
				           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
				<td><label for="m<?php echo $row['group_name']; ?>"><span class="inlineEdits" id="<?php echo "group_name-".$row['group_name']; ?>"><?php echo $row['group_name']; ?></span></label></td>
			</tr>
		<?php }} ?>
	</tbody>
<?php else: ?>
	<tr>
		<td colspan="<?php echo 2 + $this->col_counts; ?>"><?php echo _AT('none_found'); ?></td>
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
		if ((e.name == 'group_name[]') && (e.type=='checkbox')) {
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

jQuery(document).ready(function () {
	var tableEdit = fluid.inlineEdits("#editable_table", {
		selectors : {
			text : ".inlineEdits",
			editables : "td:has(span.inlineEdits)"
		},
		defaultViewText: "",
		useTooltip: true,
		listeners: {
			afterFinishEdit : function (newValue, oldValue, editNode, viewNode) {
				if (newValue != oldValue)
					rtn = jQuery.post("<?php echo TR_BASE_HREF; ?>usergroup/index_inline_editor_submit.php", { "field":viewNode.id, "value":newValue }, 
				          function(data) { handleAjaxResponse(data, viewNode, oldValue); }, "json");
			}
		}
	});
});

//-->
</script>
<?php require(TR_INCLUDE_PATH.'footer.inc.php'); ?>