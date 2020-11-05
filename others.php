<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
?>	


	<h1><a href="index.php" style="text-decoration:none;">&#8656;</a> Others</h1>
	<ul>
	
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>		
		<li><a href="reviews_main.php" title="Review" class="link_new padding10 span2" style="text-decoration:none;">Review</a></li>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator' || privilege_employees($employee_data['employee_id']) != 7 || privilege_biometrics($employee_data['employee_id']) != 7 || privilege_clients($employee_data['employee_id']) != 7 || privilege_positions($employee_data['employee_id']) != 7 || privilege_periods($employee_data['employee_id']) != 7) {
?>	
		<li><a href="maintain.php" title="Maintain" class="link_new padding10 span2" style="text-decoration:none;">Maintain</a></li>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
		<li><a href="settings.php" title="Settings" class="link_new padding10 span2" style="text-decoration:none;">Settings</a></li>
<?php
		}
	}
?>

<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator' || privilege_import_logs($employee_data['employee_id']) != 7) {
?>

		<li><a href="tools.php" title="Settings" class="link_new padding10 span2" style="text-decoration:none;">Tools</a></li>
<?php
		}
	}
?>
		
		<li><a href="links_main.php" title="Links" class="link_new padding10 span2" style="text-decoration:none;">Links</a>&nbsp;&nbsp;&nbsp;</li>
	</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>