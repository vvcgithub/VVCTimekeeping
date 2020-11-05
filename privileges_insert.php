<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'core/database/connect.php' ;
	include 'includes/logged_in.php' ;
	include 'includes/logged_admin.php' ; 
	
	if (isset($_POST['close'])) {
		header ("Location: insert_list.php");
		exit();
	}
  
	if (isset($_POST['insert'])) {
		
		$privilege_employee_id = check_input($_POST['privilege_employee_id']);
		$privilege_employees = check_input($_POST['privilege_employees']);
		$privilege_biometrics = check_input($_POST['privilege_biometrics']);
		$privilege_clients = check_input($_POST['privilege_clients']);
		$privilege_positions = check_input($_POST['privilege_positions']);
		$privilege_periods = check_input($_POST['privilege_periods']);
		$privilege_costing = check_input($_POST['privilege_costing']);
		$privilege_import_logs = check_input($_POST['privilege_import_logs']);

		if (empty($privilege_employee_id) === true) {
			$errors[] = "Employee is required!";
		}			

		if (empty($errors)) {
			$mysqli->query("INSERT INTO privileges (privilege_employee_id, privilege_employees, privilege_biometrics, privilege_clients, privilege_positions, privilege_periods, privilege_costing, privilege_import_logs) 
			VALUES (
			'$privilege_employee_id', 
			'$privilege_employees', 
			'$privilege_biometrics', 
			'$privilege_clients', 
			'$privilege_positions', 
			'$privilege_periods', 
			'$privilege_costing',
			'$privilege_import_logs'
			)"); 
			header('Location: privileges_list.php');
			exit();
		} 
	}

?>
	<h1><a href="privileges_list.php" style="text-decoration:none;">&#8656;</a> Insert Privilege</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="privilege_employee_id" class="span1">Employee</label>
		<select name="privilege_employee_id" id="privilege_employee_id">
			<option class="span_auto" value="" >- Select employee -</option>
<?php	
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT employee_id, employee_last_name FROM employees WHERE active = 1 ORDER BY employee_last_name ASC");
			while($rows = $query->fetch_assoc()) {
				$employee_id = $rows['employee_id'];
?>
			<option style="width:auto;" value="<?php echo $employee_id; ?>" <?php if (isset($privilege_employee_id) && ($privilege_employee_id === $employee_id)) { echo "selected"; } ?>><?php echo complete_name_from_id($employee_id); ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select><br /><br />
		
		<label for="privilege_employees" class="span1">Employees <span class="fg-color-red">*</span></label>
		<select name="privilege_employees" id="privilege_employees">
			<option class="span_auto" value="2" <?php echo (isset($privilege_employees) && $privilege_employees == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo (isset($privilege_employees) && $privilege_employees == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo (isset($privilege_employees) && $privilege_employees == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo (isset($privilege_employees) && $privilege_employees == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo (isset($privilege_employees) && $privilege_employees == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_employees) && $privilege_employees == "7" || empty($privilege_employees)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_biometrics" class="span1">Biometrics <span class="fg-color-red">*</span></label>
		<select name="privilege_biometrics" id="privilege_biometrics">
			<option class="span_auto" value="2" <?php echo (isset($privilege_biometrics) && $privilege_biometrics == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo (isset($privilege_biometrics) && $privilege_biometrics == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="5" <?php echo (isset($privilege_biometrics) && $privilege_biometrics == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo (isset($privilege_biometrics) && $privilege_biometrics == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_biometrics) && $privilege_biometrics == "7" || empty($privilege_biometrics) ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_clients" class="span1">Clients <span class="fg-color-red">*</span></label>
		<select name="privilege_clients" id="privilege_clients">
			<option class="span_auto" value="2" <?php echo (isset($privilege_clients) && $privilege_clients == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo (isset($privilege_clients) && $privilege_clients == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo (isset($privilege_clients) && $privilege_clients == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo (isset($privilege_clients) && $privilege_clients == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo (isset($privilege_clients) && $privilege_clients == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_clients) && $privilege_clients == "7" || empty($privilege_clients)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_positions" class="span1">Positions <span class="fg-color-red">*</span></label>
		<select name="privilege_positions" id="privilege_positions">
			<option class="span_auto" value="2" <?php echo (isset($privilege_positions) && $privilege_positions == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo (isset($privilege_positions) && $privilege_positions == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo (isset($privilege_positions) && $privilege_positions == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo (isset($privilege_positions) && $privilege_positions == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo (isset($privilege_positions) && $privilege_positions == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_positions) && $privilege_positions == "7" || empty($privilege_positions)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_periods" class="span1">Periods <span class="fg-color-red">*</span></label>
		<select name="privilege_periods" id="privilege_periods">
			<option class="span_auto" value="2" <?php echo (isset($privilege_positions) && $privilege_periods == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo (isset($privilege_positions) && $privilege_periods == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo (isset($privilege_positions) && $privilege_periods == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo (isset($privilege_positions) && $privilege_periods == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo (isset($privilege_positions) && $privilege_periods == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_positions) && $privilege_periods == "7" || empty($privilege_periods)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_costing" class="span1">Costing <span class="fg-color-red">*</span></label>
		<select name="privilege_costing" id="privilege_costing">
			<option class="span_auto" value="6" <?php echo (isset($privilege_costing) && $privilege_costing == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_costing) && $privilege_costing == "7" || empty($privilege_costing)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_import_logs" class="span1">Import logs <span class="fg-color-red">*</span></label>
		<select name="privilege_import_logs" id="privilege_import_logs">
			<option class="span_auto" value="3" <?php echo (isset($privilege_import_logs) && $privilege_import_logs == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="7" <?php echo (isset($privilege_import_logs) && $privilege_import_logs == "7" || empty($privilege_import_logs)) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<input type="submit" name="insert" value="Insert" />
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>