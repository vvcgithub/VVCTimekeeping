<?php
	function biometrics_count1() {
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT COUNT(biometrics_id) AS count_biometrics FROM biometrics");
		$rows = $query->fetch_assoc();
		$result = $rows['count_biometrics'];
		return $result;
		include 'core/database/close.php' ;
	}

	function biometrics_count($employee_id) {
		include 'core/database/connect.php' ;
		$employee_id = mysql_real_escape_string($employee_id);
		$query = $mysqli->query("SELECT count(biometrics_employee_id) as biometrics_count FROM biometrics WHERE biometrics_employee_id = '$employee_id'");
		$rows = $query->fetch_assoc();
		$biometrics_count = $rows['biometrics_count'];
		return $biometrics_count;
		include 'core/database/close.php' ;
	}

	function biometrics_employees($employee) {
		include 'core/database/connect.php' ;
		$employee = mysql_real_escape_string($employee);
		$query = $mysqli->query("SELECT employee_id_no FROM employees WHERE employee_last_name LIKE '%$employee%' OR employee_first_name LIKE '%$employee%' OR employee_middle_name LIKE '%$employee%'");
		$rows = $query->fetch_assoc();
		$final ="";
		$str = $rows['employee_id_no'];
		$str = "biometrics_employee_id_no = $str OR ";
		$final .= $str;
			return $final;
		include 'core/database/close.php' ;
	}
?>