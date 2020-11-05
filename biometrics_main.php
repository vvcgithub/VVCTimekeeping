<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
?>

<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Biometrics</h1>
<ul>
	<li><a href="biometrics_list_employee.php" class="link_new padding10 span3" style="text-decoration:none;">Your biometrics</a></li>
	<li><a href="biometrics_list_all.php?page=1&employee_id_no=<?php echo $employee_data['employee_id_no']; ?>" class="link_new padding10 span3" style="text-decoration:none;">Employees biometrics</a></li>
	<li><a href="biometrics_export_parameter.php" class="link_new padding10 span3" style="text-decoration:none;">Export all biometrics</a></li>
</ul>
</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>