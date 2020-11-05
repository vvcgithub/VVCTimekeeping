<?php
	session_start();
	//error_reporting(0);

	ob_start();

if (isset($_COOKIE['employee_id']) && isset($_COOKIE['employee_username']) && isset($_COOKIE['employee_password'])) {
	$_SESSION['employee_id'] = $_COOKIE['employee_id'];
	$_SESSION['employee_username'] = $_COOKIE['employee_username'];
	$_SESSION['employee_password'] = $_COOKIE['employee_password'];
}

	//require 'database/connect.php';
	require 'core/functions/general.php';
	require 'core/functions/employees.php';
	require 'core/functions/clients.php';
	require 'core/functions/departments.php';
	require 'core/functions/groups.php';
	require 'core/functions/schedules.php';
	require 'core/functions/news.php';
	require 'core/functions/positions.php';
	require 'core/functions/periods.php';
	require 'core/functions/logs.php';
	require 'core/functions/biometrics.php';
	require 'core/functions/leaves.php';
	require 'core/functions/loa.php';
	require 'core/functions/privileges.php';
	require 'core/functions/settings.php';
	require 'core/functions/messages.php';
	require 'core/functions/comments.php';
	require 'core/functions/time_logs.php';
	require 'core/functions/bugs.php';

	date_default_timezone_set(settings("time_logs_timezone"));

	$bgcolor = settings('bgcolor');
	$fgcolor = settings('fgcolor');

	if (logged_in() === true) {
		$session_employee_id = $_SESSION['employee_id'];
		$employee_data = employee_data($session_employee_id, 'employee_id', 'employee_id_no', 'employee_username', 'employee_password', 'employee_last_name', 'employee_first_name', 'employee_middle_name', 'employee_address', 'employee_email', 'employee_email2', 'employee_date_hired', 'employee_status', 'employee_position', 'employee_account_type', 'employee_department', 'employee_group', 'active', 'employee_profile', 'employee_schedule', 'employee_home_phone', 'employee_work_phone', 'employee_mobile_phone', 'employee_contact_name', 'employee_contact_relationship', 'employee_contact_phone1', 'employee_contact_phone2', 'employee_birthdate', 'employee_marital_status', 'employee_gender');
		
		if (employee_active($employee_data['employee_username']) === false) {
			session_destroy;
			header('Location: index.php');
			exit();
		}
	}
	
	$errors = array();
	$messages = array();
?>