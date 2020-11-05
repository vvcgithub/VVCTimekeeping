<?php
function group_code($group_id) {
	$group_id = mysql_real_escape_string($group_id);
	$row = mysql_fetch_array(mysql_query("SELECT group_code FROM groups WHERE group_id = '$group_id'"));
	return $row['group_code'];
}

function group_name($group_id) {
	$group_id = mysql_real_escape_string($group_id);
	$row = mysql_fetch_array(mysql_query("SELECT group_name FROM groups WHERE group_id = '$group_id'"));
	return $row['group_name'];
}
?>
