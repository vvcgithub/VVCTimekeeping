<?php
	if ($employee_data['employee_account_type'] <> 'Administrator' && privilege_clients($employee_data['employee_id']) == 7) {
		header('Location: index.php');
		exit();
	}
?>