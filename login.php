<?php
	include 'core/init.php';

	if (empty($_POST) === false) {
		$employee_username = check_input($_POST['employee_username']);
		$employee_password = check_input($_POST['employee_password']);
		
		if (empty($employee_username) === true || empty($employee_password) === true) {
			$errors[] = 'You need to enter a username and password my friend!';
		} else if (employee_username_exists($employee_username, "") === false) {
			$errors[] = 'I cannot find that username! Have you registered?';
		} else if (login($employee_username, $employee_password) === false && !empty($employee_username) && !empty($employee_password)) {
			$errors[] = 'Combination is incorrect!';
		} else if (employee_active($employee_username) === false && !empty($employee_username) && !empty($employee_password)) {
			$errors[] = 'Your account is inactive!';
		} else {    
			$login = employee_id_from_username($employee_username);
			$_SESSION['employee_id'] = $login;
			header('Location: index.php');
			exit();
		}
	} else {
		$error[] = 'No data received';
	}
	include 'includes/overall/header.php';
	include 'includes/aside.php';
	include 'includes/home.php' ;
	include 'includes/overall/footer.php';
?>	

