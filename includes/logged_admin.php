<?php
	if ($employee_data['employee_account_type'] <> 'Administrator') {
		header('Location: index.php');
		exit();
	}
?>