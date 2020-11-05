<?php
	if (logged_in() === true) {
?>
<div class="home">
	<figure><a href="biometrics_main.php"><img src="icons/clock-64.png" class="no-border cursor-pointer"/><figcaption>Biometrics</figcaption></a></figure>
	<figure><a href="logs_list.php"><img src="icons/list-64.png" class="no-border cursor-pointer"/><figcaption>Logs</figcaption></a></figure>
	<figure><a href="reports_main.php"><img src="icons/note-64.png" class="no-border cursor-pointer"/><figcaption>Reports</figcaption></a></figure>
	<figure><a href="forms.php"><img src="icons/forms.png" class="no-border cursor-pointer"/><figcaption>Forms</figcaption></a></figure>
	<figure><a href="loa_list.php"><img src="icons/guestion-64.png" class="no-border cursor-pointer"/><figcaption>Leave</figcaption></a></figure>
	<figure><a href="profile_main.php"><img src="icons/user-64.png" class="no-border cursor-pointer"/><figcaption>Profile</figcaption></a></figure>
	
<?php
	}
?>
	<figure><a href="#"><img src="icons/help-64.png" class="no-border cursor-pointer"/><figcaption>Help</figcaption></a></figure>	
<?php
	if (logged_in() === true) {
?>
	<figure><a href="bugs_list.php"><img src="icons/bug-64.png" class="no-border cursor-pointer"/><figcaption>Bug</figcaption></a></figure>
	<figure><a href="links_main.php"><img src="icons/external_link-64.png" class="no-border cursor-pointer"/><figcaption>Links</figcaption></a></figure>
<?php
	}
?>	

<?php
if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
	<figure><a href="settings.php"><img src="icons/settings-64.png" class="no-border cursor-pointer"/><figcaption>Settings</figcaption></a></figure>
	<figure><a href="maintain.php"><img src="icons/database-64.png" class="no-border cursor-pointer"/><figcaption>Maintain</figcaption></a></figure>
	<figure><a href="tools.php"><img src="icons/settings2-64.png" class="no-border cursor-pointer"/><figcaption>Tools</figcaption></a></figure>
<?php
		}
	}
?>
</div>
	
