<?php
function available_of_leave_sick($employee_id, $date) {
	$employee_id = mysql_real_escape_string($employee_id);
	$date_from = mysql_real_escape_string(date("Y-01-01", strtotime($date)));
	$date_to = mysql_real_escape_string(date("Y-12-31", strtotime($date)));
	$row_leaves = mysql_fetch_array(mysql_query("SELECT SUM(leave_sick) as leave_sick_sum  FROM leaves WHERE leave_employee_id = '$employee_id' AND leave_date_given BETWEEN '$date_from' AND '$date_to' AND active = 1"));
	$row_loa = mysql_fetch_array(mysql_query("SELECT SUM(loa_used) as loa_used_sum  FROM loa WHERE loa_employee_id = '$employee_id' AND loa_date BETWEEN '$date_from' AND '$date_to' AND loa_type = 'sl' AND loa_checked = 1 AND loa_approved = 1"));
	$leave_sick_sum = $row_leaves['leave_sick_sum'];
	$loa_used_sum = $row_loa['loa_used_sum'];
	$sick_leave_remaining = $leave_sick_sum - $loa_used_sum;
	return ($sick_leave_remaining > 0) ? $sick_leave_remaining : 0;
}


function total_available_of_leave_sick($employee_id) {
	$employee_id = mysql_real_escape_string($employee_id);
	//$leave_id = mysql_real_escape_string($leave_id);
	$query_leaves = mysql_query("SELECT leave_id, SUM(leave_sick) as leave_sick_sum FROM leaves WHERE leave_employee_id = '$employee_id' AND active = 1");
	$row_leaves = mysql_fetch_array($query_leaves);
	$leave_sick_sum = $row_leaves['leave_sick_sum'];
	$leave_id = $row_leaves['leave_id'];
	$query_loa = mysql_query("SELECT SUM(loa_used) as loa_used_sum FROM loa WHERE loa_employee_id = '$employee_id' AND loa_checked = 1 AND loa_approved = 1 AND loa_type = 'sl' AND active = 1");
	$row_loa = mysql_fetch_array($query_loa);
	$loa_used_sum = $row_loa['loa_used_sum'];
	$sick_leave_remaining = $leave_sick_sum - $loa_used_sum ;
	return ($sick_leave_remaining > 0) ? $sick_leave_remaining : 0;
}

function available_of_leave_vacation($employee_id, $date) {
	$employee_id = mysql_real_escape_string($employee_id);
	$date_from = mysql_real_escape_string(date("Y-01-01", strtotime($date)));
	$date_to = mysql_real_escape_string(date("Y-12-31", strtotime($date)));
	$row_leaves = mysql_fetch_array(mysql_query("SELECT SUM(leave_vacation) as leave_vacation_sum  FROM leaves WHERE leave_employee_id = '$employee_id' AND leave_date_given BETWEEN '$date_from' AND '$date_to' AND active = 1"));
	$row_loa = mysql_fetch_array(mysql_query("SELECT SUM(loa_used) as loa_used_sum  FROM loa WHERE loa_employee_id = '$employee_id' AND loa_date BETWEEN '2013-01-01' AND '2013-12-31' AND loa_type = 'vl' AND loa_checked = 1 AND loa_approved = 1"));
	$leave_vacation_sum = $row_leaves['leave_vacation_sum'];
	$loa_used_sum = $row_loa['loa_used_sum'];
	$vacation_leave_remaining = $leave_vacation_sum - $loa_used_sum;
	return ($vacation_leave_remaining > 0) ? $vacation_leave_remaining : 0;
}

function total_available_of_leave_vacation($employee_id) {
	$employee_id = mysql_real_escape_string($employee_id);
	//$leave_id = mysql_real_escape_string($leave_id);
	$query_leaves = mysql_query("SELECT leave_id, SUM(leave_vacation) as leave_vacation_sum FROM leaves WHERE leave_employee_id = '$employee_id' AND active = 1");
	$row_leaves = mysql_fetch_array($query_leaves);
	$leave_vacation_sum = $row_leaves['leave_vacation_sum'];
	$leave_id = $row_leaves['leave_id'];
	$query_loa = mysql_query("SELECT SUM(loa_used) as loa_used_sum FROM loa WHERE loa_employee_id = '$employee_id' AND loa_checked = 1 AND loa_approved = 1 AND loa_type = 'vl' AND active = 1");
	$row_loa = mysql_fetch_array($query_loa);
	$loa_used_sum = $row_loa['loa_used_sum'];
	$sick_leave_remaining = $leave_vacation_sum - $loa_used_sum ;
	return ($sick_leave_remaining > 0) ? $sick_leave_remaining : 0;
}

function leaves_employees($employee) {
	$employee = mysql_real_escape_string($employee);
	$query = mysql_query("SELECT employee_id FROM employees WHERE employee_last_name LIKE '%$employee%' OR employee_first_name LIKE '%$employee%' OR employee_middle_name LIKE '%$employee%'");
	$final ="";
	while($row = mysql_fetch_array($query)) { 
		$str = $row['employee_id'];
		$str = "leave_employee_id = $str OR ";
		$final .= $str;
	}
	return $final;
}
?>