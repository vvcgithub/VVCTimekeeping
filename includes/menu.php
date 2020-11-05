<a href="index.php" id="logo"></a>
<nav>
	<a href="#" id="menu-icon"></a>
<?php
	if (logged_in() === true) {
?>
	<ul>
		<li><a href="index.php" title="Home">Home</a></li>
		<li><a href="biometrics_main.php" title="Biometrics">Biometrics</a></li>
		<li><a href="logs_list.php" title="Logs">Logs</a></li>
		<li><a href="loa_list.php" title="Loa">Leave</a></li>
		<li><a href="reports_main.php" title="Reports">Reports</a></li>
		<li><a href="others.php" title="Review">Others</a></li>

		<li><a href="profile_main.php" title="Profile"><?php if (logged_in() == true) { echo "Hi, " . first_name_from_id_no($employee_data['employee_id_no']) ; } ?></a>&nbsp;<a href="logout.php" style="font-weight:normal;" title="Log out"><?php if (logged_in() == true) { echo "<small>Logout</small>"; } ?></a></li>
		<li></li>
	</ul>
<?php
	}
?>
</nav>

