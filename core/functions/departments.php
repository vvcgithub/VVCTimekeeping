<?php
function department_code_exists($department_code, $department_id) {
	$department_code = mysql_real_escape_string($department_code);
	$department_id = mysql_real_escape_string($department_id);
	$row = mysql_fetch_array(mysql_query("SELECT COUNT(department_id) AS count_department FROM departments WHERE department_code = '$department_code' and department_id != '$department_id'"));
	return ($row['count_department'] == 1) ? true : false;
}

function department_code($department_id) {
	$department_id = mysql_real_escape_string($department_id);
	$row = mysql_fetch_array(mysql_query("SELECT department_code FROM departments WHERE department_id = '$department_id'"));
	return $row['department_code'];
}

function department_name($department_id) {
	$department_id = mysql_real_escape_string($department_id);
	$row = mysql_fetch_array(mysql_query("SELECT department_name FROM departments WHERE department_id = '$department_id'"));
	return $row['department_name'];
}

function department_count() {
	$row = mysql_fetch_array(mysql_query("SELECT count(department_id) AS count_department FROM departments"));
	return $row['count_department'];
}
?>
