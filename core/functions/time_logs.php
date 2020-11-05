<?php
function time_log_employees($employee) {
	$employee = mysql_real_escape_string($employee);
	$query = mysql_query("SELECT employee_id FROM employees WHERE employee_last_name LIKE '%$employee%' OR employee_first_name LIKE '%$employee%' OR employee_middle_name LIKE '%$employee%'");
	$final ="";
	while($row = mysql_fetch_array($query)) { 
		$str = $row['employee_id'];
		$str = "time_log_employee_id = $str OR ";
		$final .= $str;
	}
	return $final;
}
?>