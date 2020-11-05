<?php
	if (isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from']) && !empty($_GET['date_to'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$contents="ID#, Last name, First name, Middle name, Date , Time\n";
		//Mysql query to get records from datanbase
		//You can customize the query to filter from particular date and month etc...Which will depends your database structure.
		
		$mysqli = mysqli_connect("localhost","root","","time_keeping");
		$query = $mysqli->query("SELECT biometrics.biometrics_id, biometrics.biometrics_employee_id_no, employees.employee_id_no, employees.employee_last_name, employees.employee_first_name, employees.employee_middle_name, biometrics.biometrics_date,
		 biometrics.biometrics_time FROM biometrics LEFT JOIN employees ON biometrics.biometrics_employee_id_no=employees.employee_id_no WHERE biometrics.biometrics_date BETWEEN '$date_from' AND '$date_to' ORDER BY employees.employee_last_name ASC, employees.employee_first_name ASC, employees.employee_middle_name ASC, employees.employee_id_no ASC, biometrics.biometrics_date ASC, biometrics.biometrics_time ASC");
		//While loop to fetch the records
		while($rows = $query->fetch_assoc()) {
			$contents.=$rows['biometrics_employee_id_no'].",";
			$contents.=$rows['employee_last_name'].",";
			$contents.=$rows['employee_first_name'].",";
			$contents.=$rows['employee_middle_name'].",";
			$contents.=date("n/j/Y", strtotime($rows['biometrics_date'])).",";
			$contents.=date("H:i", strtotime($rows['biometrics_time']))."\n";
			
		}
		mysqli_close($mysqli);

		// remove html and php tags etc.
		$contents = strip_tags($contents); 

		//header to make force download the file
		header("Content-Disposition: attachment; filename=Biometrics".date('d-m-Y').".csv");
		print $contents;
	}	
?>