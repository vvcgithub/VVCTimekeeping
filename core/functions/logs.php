<?php

function log_count_checked($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_checked) AS count_log_checked FROM logs WHERE log_employee_id = '$employee_id' AND log_checked = '1'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_checked'];
	include 'core/database/close.php' ;
}

function log_count_checked_total($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_checked) AS count_log_checked_total FROM logs WHERE log_employee_id = '$employee_id'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_checked_total'];
	include 'core/database/close.php' ;
}

function log_count_approved($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_approved) AS count_log_approved FROM logs WHERE log_employee_id = '$employee_id' AND log_approved = '1'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_approved'];
	include 'core/database/close.php' ;
}

function log_count_approved_total($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_approved) AS count_log_approved_total FROM logs WHERE log_employee_id = '$employee_id'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_approved_total'];
	include 'core/database/close.php' ;
}

function log_checker($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_checker) AS count_log_checker FROM logs WHERE log_checker = '$employee_id' AND log_checked = '0'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_checker'];
	include 'core/database/close.php' ;
}

function log_approver($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT count(log_approver) AS count_log_approver FROM logs WHERE log_approver = '$employee_id' AND log_approved = '0'");
	$rows = $query->fetch_assoc();
	return $rows['count_log_approver'];
	include 'core/database/close.php' ;
}

function last_checker($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT log_checker FROM logs WHERE log_employee_id = '$employee_id' ORDER BY log_id DESC");
	$rows = $query->fetch_assoc();
	$last_checker = $rows['log_checker'];
	return $last_checker;
	include 'core/database/close.php' ;
}

function last_approver($employee_id) {
	include 'core/database/connect.php' ;
	$employee_id = mysqli_real_escape_string($mysqli, $employee_id);
	$query = $mysqli->query("SELECT log_approver FROM logs WHERE log_employee_id = '$employee_id' ORDER BY log_id DESC");
	$rows = $query->fetch_assoc();
	return $rows['log_approver'];
	include 'core/database/close.php' ;
}

function log_type($log_type) {
	$log_type = check_input($log_type);
	switch ($log_type) {
    case 1:
        $log_type = "p/h";
        break;
    case 2:
        $log_type = "sl";
        break;
    case 3:
        $log_type = "vl";
        break;
	case 4:
        $log_type = "lwop";
        break;
	case 5:
        $log_type = "n/a";
        break;
	}
	return $log_type;
}

function log_count($log_count) {
	$log_count = check_input($log_count);
	switch ($log_count) {
    case 0:
        $log_count = "ab/n/a";
        break;
    case .5:
        $log_count = "hd";
        break;
    case 1:
        $log_count = "wd";
        break;
	}
	return $log_count;
}

function log_sum_present($log_employee_id_no, $log_period_code) {
	$log_employee_id_no = check_input($log_employee_id_no);
	$log_period_code = check_input($log_period_code);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT log_date, log_employee_id_no, SUM(log_count) AS log_count_present FROM (SELECT * FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND log_type = 1 GROUP BY log_date ORDER BY log_date ASC) AS logs2");
	$rows = $query->fetch_assoc();
	$result = $rows['log_count_present'];
	return ($result <> 0) ? $result : 0 ;
	include 'core/database/close.php' ;
}

function log_sum_sl($log_employee_id_no, $log_period_code) {
	$log_employee_id_no = check_input($log_employee_id_no);
	$log_period_code = check_input($log_period_code);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT sum(log_count) AS log_count_sl FROM (SELECT * FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code= '$log_period_code' AND log_type = 2 GROUP BY log_date ORDER BY log_date ASC) AS logs2");
	$rows = $query->fetch_assoc();
	$result = $rows['log_count_sl'];
	return ($result <> 0) ? $result : 0 ;
	include 'core/database/close.php' ;
}

function log_sum_vl($log_employee_id_no, $log_period_code) {
	$log_employee_id_no = check_input($log_employee_id_no);
	$log_period_code = check_input($log_period_code);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT sum(log_count) AS log_count_vl FROM (SELECT * FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND log_type = 3 GROUP BY log_date ORDER BY log_date ASC) AS logs2");
	$rows = $query->fetch_assoc();
	$result = $rows['log_count_vl'];
	return ($result <> 0) ? $result : 0 ;
	include 'core/database/close.php' ;
}

function log_sum_lwop($log_employee_id_no, $log_period_code) {
	$log_employee_id_no = check_input($log_employee_id_no);
	$log_period_code = check_input($log_period_code);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT sum(log_count) AS log_count_lwop FROM (SELECT * FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND log_type = 4 GROUP BY log_date ORDER BY log_date ASC) AS logs2");
	$rows = $query->fetch_assoc();
	$result = $rows['log_count_lwop'];
	return ($result <> 0) ? $result : 0 ;
	include 'core/database/close.php' ;
}

function log_check($log_employee_id_no) {
	$log_employee_id_no = check_input($log_employee_id_no);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT count(log_check) AS log_count_check FROM logs WHERE log_employee_id_no = '$log_employee_id_no'");
	$rows1 = $query->fetch_assoc();
	$row1_count = $rows1['log_count_check'];
	$query = $mysqli->query("SELECT count(log_check) AS log_count_check FROM logs WHERE log_employee_id_no = '$log_employee_id_no' AND log_check = 1");
	$rows2 = $query->fetch_assoc();
	$row2_count = $rows2['log_count_check'];
	return ($row1_count == $row2_count && $row1_count > 0) ? "Y" : "N";
	include 'core/database/close.php' ;
}

function total_working_days($log_period_code, $log_employee_id_no) {
	$log_period_code = check_input($log_period_code);
	$log_employee_id_no = check_input($log_employee_id_no);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT COUNT(DISTINCT log_date) AS total_working_days FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND log_client_code IS NOT NULL");
	$rows = $query->fetch_assoc();
	$total_working_days = $rows['total_working_days'];
	return ($total_working_days <> 0) ? $total_working_days : 0 ;
	include 'core/database/close.php' ;
}

function total_working_days_post($log_period_code, $log_employee_id_no) {
	$log_period_code = check_input($log_period_code);
	$log_employee_id_no = check_input($log_employee_id_no);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT COUNT(DISTINCT log_date) AS total_working_days FROM logs_post WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND log_client_code IS NOT NULL");
	$rows = $query->fetch_assoc();
	$total_working_days = $rows['total_working_days'];
	return ($total_working_days <> 0) ? $total_working_days : 0 ;
	include 'core/database/close.php' ;
}

function date_interval($period_from, $period_to) {
	$datetime1 = new DateTime($period_from);
	$datetime2 = new DateTime($period_to);
	$interval = $datetime1->diff($datetime2);
	$woweekends = 0;
	for($i=0; $i<$interval->d; $i++){
		$modify = $datetime1->modify('+1 day');
		$weekday = $datetime1->format('w');
		
		if($weekday != 0){ // 0 for Sunday and 6 for Saturday
			$woweekends++;  
		}
	}
}

function count_distinct_days($log_period_code, $log_employee_id_no) {
	$log_period_code = check_input($log_period_code);
	$log_employee_id_no = check_input($log_employee_id_no);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT COUNT(DISTINCT log_date) AS aaa FROM logs WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND DAYNAME(log_date) <> 'Sunday'");
	$rows = $query->fetch_assoc();
	$aaa = $rows['aaa'];
	return ($aaa <> 0) ? $aaa : 0 ;
	include 'core/database/close.php' ;
}


function count_distinct_days_post($log_period_code, $log_employee_id_no) {
	$log_period_code = check_input($log_period_code);
	$log_employee_id_no = check_input($log_employee_id_no);
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT COUNT(DISTINCT log_date) AS aaa FROM logs_post WHERE log_employee_id_no='$log_employee_id_no' AND log_period_code = '$log_period_code' AND DAYNAME(log_date) <> 'Sunday'");
	$rows = $query->fetch_assoc();
	$aaa = $rows['aaa'];
	return ($aaa <> 0) ? $aaa : 0 ;
	include 'core/database/close.php' ;
}


?>

