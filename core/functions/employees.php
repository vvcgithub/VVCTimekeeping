<?php

	function employee_count() {
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT count(employee_id) AS count_employee FROM employees WHERE active = 1");
		$rows = $query->fetch_assoc();
		return $rows['count_employee'];
		include 'core/database/close.php' ;
	}

	function employee_data($employee_id) {
		include 'core/database/connect.php' ;
		$data = array();
		$employee_id = (int)$employee_id;
		
		$func_num_args = func_num_args();
		$func_get_args = func_get_args();
			
		if ($func_num_args > 1) {
			unset($func_get_args[0]);
			$fields = implode(', ', $func_get_args);
			$query = $mysqli->query("SELECT $fields FROM employees WHERE employee_id = '$employee_id'");
			$data = $query->fetch_assoc();
			//$data = mysqli_fetch_array($query);
			return $data;
		}
		include 'core/database/close.php' ;
	}

	//This function is used to test if the user is logged or not.
	function logged_in() {
		return (isset($_SESSION['employee_id']) || (isset($_COOKIE['employee_username']) && isset($_COOKIE['employee_password']))) ? true : false; 
	}

	function checker_last_name($employee_id_no) {
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_last_name FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_last_name'];
		include 'core/database/close.php' ;
	}

	function checker_first_name($employee_id_no) {
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_first_name FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_first_name'];
		include 'core/database/close.php' ;
	}


	//This function is used to test if the id number exist.
	function employee_id_no_exists($employee_id_no, $employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT COUNT(employee_id) AS count_employee FROM employees WHERE employee_id_no = '$employee_id_no' AND employee_id != '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($rows['count_employee'] == 1) ? true : false;
		include 'core/database/close.php' ;
	}

	//This function is used to test if the username exist.
	function employee_username_exists($employee_username, $employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$employee_username = check_input($employee_username);
		$query = $mysqli->query("SELECT COUNT(employee_id) AS count_employee FROM employees WHERE employee_username = '$employee_username' AND employee_id != '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($rows['count_employee'] == 1) ? true : false;
		include 'core/database/close.php' ;
	}

	//This function is used to test if the email exist.
	function employee_email_exists($employee_email, $employee_id ) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$employee_email = check_input($employee_email);
		$query = $mysqli->query("SELECT COUNT(employee_id) AS count_employee FROM employees WHERE employee_email = '$employee_email' AND employee_id != '$employee_id'");
		$rows = $query->fetch_assoc();
		return ($rows['count_employee'] == 1) ? true : false;
		include 'core/database/close.php' ;
	}

	function employee_id_number($employee_id) {    //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_id_no FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();	
		return $rows['employee_id_no'];
		include 'core/database/close.php' ;
	}

	function employee_id_from_id_no($employee_id_no) {
		include 'core/database/connect.php' ;
		set_time_limit(0);
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_id FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_id'];
		include 'core/database/close.php' ;
	}

	function last_name_from_id_no($employee_id_no) {  //usable
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_last_name FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_last_name'];
		include 'core/database/close.php' ;
	}
	
	function last_name_from_id($employee_id) {  //usable
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_last_name FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_last_name'];
		include 'core/database/close.php' ;
	}

	function first_name_from_id_no($employee_id_no) {  //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_first_name FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_first_name'];
		include 'core/database/close.php' ;
	}
	
	function first_name_from_id($employee_id) {  //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_first_name FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_first_name'];
		include 'core/database/close.php' ;
	}

	function middle_name_from_id($employee_id) {  //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_middle_name FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_middle_name'];
		include 'core/database/close.php' ;
	}
	

	function complete_name_from_id($employee_id) {  //usable
		$complete_name = ucwords(strtolower(last_name_from_id($employee_id))) . ", " . ucwords(strtolower(first_name_from_id($employee_id))) . " " . ucwords(strtolower(middle_name_from_id($employee_id)));
		return $complete_name;
	}

	function complete_name_from_id_no($employee_id_no) {
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_last_name, employee_first_name, employee_middle_name FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_last_name'] . ", " . $rows['employee_first_name'] . " " . $rows['employee_middle_name'];
		include 'core/database/close.php' ;
	}

	function complete_name_from_username($employee_username) {
		include 'core/database/connect.php' ;
		$employee_username = check_input($employee_username);
		$query = $mysqli->query("SELECT employee_last_name, employee_first_name, employee_middle_name FROM employees WHERE employee_username = '$employee_username'");
		$rows = $query->fetch_assoc();
		return $rows['employee_last_name'] . ", " . $rows['employee_first_name'] . " " . $rows['employee_middle_name'];
		include 'core/database/close.php' ;
	}

	function employee_initials($employee_id) {
		include 'core/database/connect.php' ;
		$initials = substr(first_name_from_id($employee_id),0,1) . substr(middle_name_from_id($employee_id),0,1) . substr(last_name_from_id($employee_id),0,1);
		return strtoupper($initials);
		include 'core/database/close.php' ;
	}

	function employee_name($employee_id) {
		$name = ucwords(strtolower(first_name_from_id($employee_id))) . " " . ucwords(strtolower(middle_name_from_id($employee_id))) . " " . ucwords(strtolower(last_name_from_id($employee_id)));
		return $name;
	}

	function employee_id($employee) {
		include 'core/database/connect.php' ;
		$employee = check_input($employee);
		$query = $mysqli->query("SELECT employee_id FROM employees WHERE employee_last_name LIKE '%$employee%' OR employee_first_name LIKE '%$employee%' OR employee_middle_name LIKE '%$employee%'");
		$rows = $query->fetch_assoc();
		return $rows['employee_id'];
		include 'core/database/close.php' ;
	}

	function username_from_id($employee_id) {  //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_username FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_username'];
		include 'core/database/close.php' ;
	}

	function profile_from_id($employee_id) {  //usable
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_profile FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_profile'];
		include 'core/database/close.php' ;
	}

	//This function is used to test if the user is active or not.
	function employee_active($employee_username) {  //usable
		include 'core/database/connect.php' ;
		$employee_username = check_input($employee_username);
		$query = $mysqli->query("SELECT COUNT(employee_id) AS count_employee_id FROM employees WHERE employee_username = '$employee_username' AND active = 1");
		$rows = $query->fetch_assoc();	
		return ($rows['count_employee_id'] == 1) ? true : false; // ==1 mean if COUNT('employee_id') equals to 1
		include 'core/database/close.php' ;
	}

	//This function is used to get the value of employee_id from a given username
	function employee_id_from_username($employee_username) {
		include 'core/database/connect.php' ;
		$employee_username = check_input($employee_username);
		$query = $mysqli->query("SELECT employee_id FROM employees WHERE employee_username = '$employee_username'");
		$rows = $query->fetch_assoc();
		return $rows['employee_id'];
		include 'core/database/close.php' ;
	}



	//This function is used to get the username AND password.
	function login($employee_username, $employee_password) {
		include 'core/database/connect.php' ;
		$employee_id = employee_id_from_username($employee_username);
		$employee_username = check_input($employee_username);
		$employee_password = md5($employee_password);
		$query = $mysqli->query("SELECT COUNT(employee_id) AS count_employee_id FROM employees WHERE employee_username = '$employee_username' AND employee_password = '$employee_password'");
		$rows = $query->fetch_assoc();
		return ($rows['count_employee_id'] == 1) ? true: false;
		include 'core/database/close.php' ;
	}

	function employee_account_type($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_account_type FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_account_type'];
		include 'core/database/close.php' ;
	}

	function schedules($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_schedule FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_schedule'];
		include 'core/database/close.php' ;
	}

	function employee_email($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_email FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_email'];
		include 'core/database/close.php' ;
	}
	
	function employee_position($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = check_input($employee_id);
		$query = $mysqli->query("SELECT employee_position FROM employees WHERE employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		return $rows['employee_position'];
		include 'core/database/close.php' ;
	}
	
	function employee_position_id_no($employee_id_no) {
		include 'core/database/connect.php' ;
		$employee_id_no = check_input($employee_id_no);
		$query = $mysqli->query("SELECT employee_position FROM employees WHERE employee_id_no = '$employee_id_no'");
		$rows = $query->fetch_assoc();
		return $rows['employee_position'];
		include 'core/database/close.php' ;
	}
?>