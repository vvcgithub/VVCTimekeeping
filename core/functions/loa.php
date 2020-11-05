<?php
function loa_period_code_exists_insert($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT COUNT(loa_period_code) AS count_loa_period_code FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return ($rows['count_loa_period_code'] > 0 ) ? true : false;
	include 'core/database/close.php' ;
}

function loa_period_code_exists_update($period_code, $loa_id) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$loa_id = check_input($loa_id);
	$query = $mysqli->query("SELECT COUNT(loa_period_code) AS count_loa_period_code FROM loa WHERE loa_period_code = '$period_code' AND loa_id != '$loa_id'");
	$rows = $query->fetch_assoc();
	return ($rows['count_loa_period_code'] > 0 ) ? true : false;
	include 'core/database/close.php' ;
}

function loa_id($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_id FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return ($rows['loa_id']);
	include 'core/database/close.php' ;
}

function loa_sl_check($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_sl FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_sl'];
	include 'core/database/close.php' ;
}

function loa_vl_check($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_vl FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_vl'];
	include 'core/database/close.php' ;
}

function loa_lwop_check($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_lwop FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_lwop'];
	include 'core/database/close.php' ;
}

function loa_mpaternity_check($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_mpaternity FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_mpaternity'];
	include 'core/database/close.php' ;
}

function loa_sl_post($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_sl FROM loa_post WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_sl'];
	include 'core/database/close.php' ;
}

function loa_vl_post($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_vl FROM loa_post WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_vl'];
	include 'core/database/close.php' ;
}

function loa_lwop_post($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_lwop FROM loa_post WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return $rows['loa_lwop'];
	include 'core/database/close.php' ;
}

function loa_check($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_check FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return ($rows['loa_check'] == 1) ? 1 : 0;
	include 'core/database/close.php' ;
}

function loa_check_post($period_code, $employee_id_no) {
	include 'core/database/connect.php' ;
	$period_code = check_input($period_code);
	$employee_id_no = check_input($employee_id_no);
	$query = $mysqli->query("SELECT loa_check FROM loa_post WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
	$rows = $query->fetch_assoc();
	return ($rows['loa_check'] == 1) ? 1 : 0;
	include 'core/database/close.php' ;
}


?>