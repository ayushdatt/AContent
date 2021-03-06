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
<br>
<input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>" />
<input type="hidden" name="include" value="<?php echo htmlspecialchars($_GET['include']); ?>" />

<table summary="<?php echo _AT('user_table_summary'); ?>" class="data" rules="rows">
<colgroup>
	<?php if($this->col == 'first_name'): ?>
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
	<th scope="col" width="15%"><a href="usergroup/view_user_group.php?<?php echo $this->orders[$this->order]; ?>=first_name<?php echo $page_string; ?>"><?php echo _AT('first_name'); ?></a></th>
	<th scope="col" width="10%"><a href="usergroup/view_user_group.php?<?php echo $this->orders[$this->order]; ?>=last_name<?php echo $page_string; ?>"><?php echo _AT('last_name');   ?></a></th>
	<th scope="col" width="15%"><a href="usergroup/view_user_group.php?<?php echo $this->orders[$this->order]; ?>=email<?php echo $page_string; ?>"><?php echo _AT('email');           ?></a></th>
</tr>

</thead>
<?php if ($this->num_results > 0): ?>
	<tbody>
		<?php if (is_array($this->user_rows)){ foreach ($this->user_rows as $row) {?>
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


</form>
</fieldset>
</div>

<?php require(TR_INCLUDE_PATH.'footer.inc.php'); ?>