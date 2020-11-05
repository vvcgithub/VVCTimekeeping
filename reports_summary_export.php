<?php
	if (isset($_GET['period_code'])) {
		$period_code = $_GET['period_code'];
		$contents="Name, TWD, SL, VL , LWOP, TOTAL, Late, Undertime, A, B, C, D, E, F, Overtime\n";
		//Mysql query to get records from datanbase
		//You can customize the query to filter from particular date and month etc...Which will depends your database structure.
		
		$mysqli = mysqli_connect("localhost","vvcadmin","password","time_keeping");
		$query = $mysqli->query("SELECT DISTINCT logs_post.log_employee_id_no, logs_post.log_period_code, employees.employee_id_no, employees.employee_last_name FROM employees INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no WHERE logs_post.log_period_code = '$period_code' ORDER BY employees.employee_last_name");
		//While loop to fetch the records
		
		while($rows = $query->fetch_assoc()) {
				$employee_id_no = $rows['employee_id_no'];	
				
				$query_log = $mysqli->query("SELECT SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum FROM logs_post WHERE log_employee_id_no = $employee_id_no AND log_period_code = '$period_code'");
				$row_log = $query_log->fetch_assoc();
				if($row_log) { // get data from db
					$log_late_sum = $row_log['log_late_sum'] / 60;
					$log_undertime_sum = $row_log['log_undertime_sum'];
					$log_a_sum = $row_log['log_a_sum'];
					$log_b_sum = $row_log['log_b_sum'];
					$log_c_sum = $row_log['log_c_sum'];
					$log_d_sum = $row_log['log_d_sum'];
					$log_e_sum = $row_log['log_e_sum'];
					$log_f_sum = $row_log['log_f_sum'];
					$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum;
					
					$contents.=$rows['employee_last_name'].",";
					$contents.=$rows['employee_first_name'].",";
					$contents.=$rows['employee_middle_name'].",";
					$contents.=date("n/j/Y", strtotime($rows['biometrics_date'])).",";
					$contents.=date("h:i A", strtotime($rows['biometrics_time'])).",";
					$contents.=$rows['biometrics_employee_id_no']."\n";
				} else {
					$log_late_sum = "";
					$log_undertime_sum = "";
					$log_a_sum = "";
					$log_b_sum = "";
					$log_c_sum = "";
					$log_d_sum = "";
					$log_e_sum = "";
					$log_f_sum = "";
					$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum;
				}
		while($rows = $query->fetch_assoc()) {
			$contents.=$rows['employee_last_name'].",";
			$contents.=$rows['employee_first_name'].",";
			$contents.=$rows['employee_middle_name'].",";
			$contents.=date("n/j/Y", strtotime($rows['biometrics_date'])).",";
			$contents.=date("h:i A", strtotime($rows['biometrics_time'])).",";
			$contents.=$rows['biometrics_employee_id_no']."\n";
		}
		mysqli_close($mysqli);

		// remove html and php tags etc.
		$contents = strip_tags($contents); 

		//header to make force download the file
		header("Content-Disposition: attachment; filename=Biometrics".date('d-m-Y').".csv");
		print $contents;
	}	
	}
?>