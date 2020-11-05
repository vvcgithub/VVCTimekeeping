<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;

	if(isset($_GET['start'])) {
		$start = $_GET['start'];
		$start = check_input($start);
	} else {
		$start = 'A';
		header("Location: clients_view_start.php?start=$start");
	}
?>


<?php
	include 'includes/overall/footer.php' ; 
?>