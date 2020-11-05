<?php
function period_code_exists($period_code, $period_id) {
	include 'core/database/connect.php' ;
	$period_code = mysqli_real_escape_string($mysqli, $period_code);
	$period_id = mysqli_real_escape_string($mysqli, $period_id);
	$row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT COUNT(period_id) AS count_period FROM periods WHERE period_code = '$period_code' and period_id != '$period_id'"));
	return ($row['count_period'] == 1) ? true : false;
}

function check_date($day_value, $month_value, $year_value) {
	include 'core/database/connect.php' ;
	$year_value = mysqli_real_escape_string($mysqli, $year_value);
	$month_value = mysqli_real_escape_string($mysqli, $month_value);
	$day_value = mysqli_real_escape_string($mysqli, $day_value);
	return (checkdate($day_value, $month_value, $year_value)) ? true : false;	
	}

function period_from($period_code) {
	include 'core/database/connect.php' ;
	$period_code = mysqli_real_escape_string($mysqli, $period_code);
	$row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT period_from FROM periods WHERE period_code = '$period_code'"));
	return $row['period_from'];
}

function period_to($period_code) {
	include 'core/database/connect.php' ;
	$period_code = mysqli_real_escape_string($mysqli, $period_code);
	$row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT period_to FROM periods WHERE period_code = '$period_code'"));
	return $row['period_to'];
}

function period_active_last() {
	include 'core/database/connect.php' ;
	$row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT period_code FROM periods WHERE active = 1 ORDER BY period_id DESC"));
	return $row['period_code'];
}

function period_code($period_code) {
	include 'core/database/connect.php' ;
	$period_code = mysqli_real_escape_string($mysqli, $period_code);
	$row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT period_code FROM periods WHERE BINARY period_code = '$period_code'"));
	return $row['period_code'];
}

function period_interval($period_code) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$query = $mysqli->query("SELECT period_interval FROM periods WHERE period_code = '$period_code'");
	$rows = $query->fetch_assoc();
	return $rows['period_interval'];
	include 'core/database/close.php' ;
	
}
?>

