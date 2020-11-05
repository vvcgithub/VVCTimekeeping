<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if ($employee_data['employee_account_type'] <> 'Administrator' && (privilege_employees($employee_data['employee_id']) == 7 && privilege_biometrics($employee_data['employee_id']) == 7 && privilege_clients($employee_data['employee_id']) == 7 && privilege_positions($employee_data['employee_id']) == 7 && privilege_periods($employee_data['employee_id']) == 7)) {
		header('Location: index.php');
		exit();
	}
?>

	<h1><a href="others.php" style="text-decoration:none;">&#8656;</a> Maintain</h1>
	<ul>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_employees($employee_data['employee_id']) != 7) {
?>
		<li><a href="employees_list.php" class="link_new padding10 span3" style="text-decoration:none;">Employees</a></li>
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_biometrics($employee_data['employee_id']) != 7) {
?>
		<li><a href="biometrics_list.php" class="link_new padding10 span3" style="text-decoration:none;">Biometrics</a></li>
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_clients($employee_data['employee_id']) != 7) {
?>
		<li><a href="clients_list.php" class="link_new padding10 span3" style="text-decoration:none;">Clients</a></li>
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_positions($employee_data['employee_id']) != 7) {
?>
		<li><a href="positions_list.php" class="link_new padding10 span3" style="text-decoration:none;">Positions</a></li>
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_periods($employee_data['employee_id']) != 7) {
?>
		<li><a href="periods_list.php" class="link_new padding10 span3" style="text-decoration:none;">Periods</a></li>
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator') {
?>
		<li><a href="privileges_list.php" class="link_new padding10 span3" style="text-decoration:none;">Privileges</a></li>
<?php
	}
?>
	</ul>


<?php
	include 'includes/overall/footer.php' ; 
?>