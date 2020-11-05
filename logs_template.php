<?php
	session_start();
	if (isset($_SESSION['employee_id'])) {
		$contents="Position, Period, Date, In, Out, Client Code, Regular, Late, Undertime, A, B, C, D, E, F, G, H, Description\n";
		//$contents = strip_tags($contents); 
		//header to make force download the file
		header("Content-Disposition: attachment; filename=Logs_import".date('d-m-Y').".csv");
		print $contents;
	} else {
		header("Location: index.php");
		exit();
	}
	
 ?>