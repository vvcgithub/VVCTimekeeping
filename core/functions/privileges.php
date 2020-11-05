<?php

	function privilege_count($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT count(privilege_employee_id) as count_privilege_employee_id FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['count_privilege_employee_id'] : 0;
		include 'core/database/close.php' ;
	}
	
	function privilege_employees($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_employees FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_employees'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_biometrics($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_biometrics FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_biometrics'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_time_logs($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_time_logs FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_time_logs'] : 7;
		include 'core/database/close.php' ;
	}
	
	function privilege_import_logs($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_import_logs FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_import_logs'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_clients($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_clients FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_clients'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_departments($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_departments FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_departments'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_positions($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_positions FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_positions'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_periods($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_periods FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_periods'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_leaves($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_leaves FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_leaves'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_announcements($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_announcements FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_announcements'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_profile_uploader($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_profile_uploader FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_profile_uploader'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_biometrics_all($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_biometrics_all FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_biometrics_all'] : 7;
		include 'core/database/close.php' ;
	}

	function privilege_reset_password($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_reset_password FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_reset_password'] : 7;
		include 'core/database/close.php' ;
	}
	
	function privilege_charging($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_charging FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_charging'] : 7;
		include 'core/database/close.php' ;
	}
	
	function privilege_costing($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT privilege_costing FROM privileges where privilege_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($query->num_rows > 0) ? $rows['privilege_costing'] : 7;
		include 'core/database/close.php' ;
	}

	function background_color($privileges) {
		if ($privileges === "2") {
			return 'green';
		} else if ($privileges === "3") {
			return 'DeepSkyBlue  ';
		} else if ($privileges === "4") {
			return 'Aquamarine ';
		} else if ($privileges === "5") {
			return 'LightCoral';
		} else if ($privileges === "6") {
			return 'Azure';
		} else if ($privileges == "7") {
			return 'orange';
		} else {
			return 'white';
		}
	}

	function privileges_name($privileges) {
		if ($privileges === "2") {
			echo 'Full access';
		} else if ($privileges === "3") {
			echo 'Add/Upload';
		} else if ($privileges === "4") {
			echo 'Edit/Reset';
		} else if ($privileges === "5") {
			echo 'Delete/Delete all';
		} else if ($privileges === "6") {
			echo 'View only';
		} else if ($privileges === "7") {
			echo 'No access';
		} else {
			echo '';
		}
	}
	
	function privilege_id($privilege) {
		include 'core/database/connect.php' ;
		$privilege = check_input($privilege);
		$query = $mysqli->query("SELECT employee_id, employee_last_name, employee_first_name, employee_middle_name FROM employees WHERE employee_last_name LIKE '%$privilege%' OR employee_first_name LIKE '%$privilege%' OR employee_middle_name LIKE '%$privilege%'"); 
		$rows = $query->fetch_assoc();
		return $rows['employee_id'];
		include 'core/database/close.php' ;
	}
?>