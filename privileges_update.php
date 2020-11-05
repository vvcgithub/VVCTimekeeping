<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/logged_in.php' ;
	include 'includes/logged_admin.php' ; 
	
	if (isset($_GET['privilege_id'])) {
		$privilege_id = $_GET['privilege_id'];
		$query = $mysqli->query("SELECT * FROM privileges WHERE privilege_id=$privilege_id"); 
		$rows = $query->fetch_assoc();
		if($rows) { 
			$privilege_employee_id = $rows['privilege_employee_id'];
			$employee_name = complete_name_from_id($privilege_employee_id);
			$privilege_employees = $rows['privilege_employees'];
			$privilege_biometrics = $rows['privilege_biometrics'];
			$privilege_clients = $rows['privilege_clients'];
			$privilege_positions = $rows['privilege_positions'];
			$privilege_periods = $rows['privilege_periods'];
			$privilege_costing = $rows['privilege_costing'];
			$privilege_import_logs = $rows['privilege_import_logs'];
		}
	} else {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
  
	if (isset($_POST['update'])) {
		$privilege_id = check_input($_POST['privilege_id']);
		$privilege_employees = check_input($_POST['privilege_employees']);
		$privilege_biometrics = check_input($_POST['privilege_biometrics']);
		$privilege_clients = check_input($_POST['privilege_clients']);
		$privilege_positions = check_input($_POST['privilege_positions']);
		$privilege_periods = check_input($_POST['privilege_periods']);
		$privilege_costing = check_input($_POST['privilege_costing']);
		$privilege_import_logs = check_input($_POST['privilege_import_logs']);

		if (empty($errors)) {
			$mysqli->query("UPDATE privileges SET 
			privilege_employees='$privilege_employees'
			, privilege_biometrics='$privilege_biometrics'
			, privilege_clients='$privilege_clients'
			, privilege_positions='$privilege_positions'
			, privilege_periods='$privilege_periods'
			, privilege_costing='$privilege_costing'
			, privilege_import_logs='$privilege_import_logs'
			WHERE privilege_id='$privilege_id'"); 
			header ("Location: privileges_list.php?page=$page&text_search=$text_search");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header ("Location: clients_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="privileges_list.php" style="text-decoration:none;">&#8656;</a> Update Privilege</h1>
	<h2><?php echo $employee_name; ?></h2>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<input type="hidden" name="privilege_id" value="<?php echo $privilege_id; ?>"  />
		<label for="privilege_employees" class="span1">Employees <span class="fg-color-red">*</span></label>
		<select name="privilege_employees" id="privilege_employees">
			<option class="span_auto" value="2" <?php echo ($privilege_employees == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo ($privilege_employees == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo ($privilege_employees == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo ($privilege_employees == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo ($privilege_employees == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_employees == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_biometrics" class="span1">Biometrics <span class="fg-color-red">*</span></label>
		<select name="privilege_biometrics" id="privilege_biometrics">
			<option class="span_auto" value="2" <?php echo ($privilege_biometrics == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo ($privilege_biometrics == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="5" <?php echo ($privilege_biometrics == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo ($privilege_biometrics == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_biometrics == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_clients" class="span1">Clients <span class="fg-color-red">*</span></label>
		<select name="privilege_clients" id="privilege_clients">
			<option class="span_auto" value="2" <?php echo ($privilege_clients == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo ($privilege_clients == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo ($privilege_clients == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo ($privilege_clients == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo ($privilege_clients == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_clients == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_positions" class="span1">Positions <span class="fg-color-red">*</span></label>
		<select name="privilege_positions" id="privilege_positions">
			<option class="span_auto" value="2" <?php echo ($privilege_positions == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo ($privilege_positions == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo ($privilege_positions == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo ($privilege_positions == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo ($privilege_positions == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_positions == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_periods" class="span1">Periods <span class="fg-color-red">*</span></label>
		<select name="privilege_periods" id="privilege_periods">
			<option class="span_auto" value="2" <?php echo ($privilege_periods == "2" ) ? "selected" : ""; ?>><?php echo privileges_name("2"); ?></option>
			<option class="span_auto" value="3" <?php echo ($privilege_periods == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="4" <?php echo ($privilege_periods == "4" ) ? "selected" : ""; ?>><?php echo privileges_name("4"); ?></option>
			<option class="span_auto" value="5" <?php echo ($privilege_periods == "5" ) ? "selected" : ""; ?>><?php echo privileges_name("5"); ?></option>
			<option class="span_auto" value="6" <?php echo ($privilege_periods == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_periods == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_costing" class="span1">Costing <span class="fg-color-red">*</span></label>
		<select name="privilege_costing" id="privilege_costing">
			<option class="span_auto" value="6" <?php echo ($privilege_costing == "6" ) ? "selected" : ""; ?>><?php echo privileges_name("6"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_costing == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<label for="privilege_import_logs" class="span1">Import logs <span class="fg-color-red">*</span></label>
		<select name="privilege_import_logs" id="privilege_periods">
			<option class="span_auto" value="3" <?php echo ($privilege_import_logs == "3" ) ? "selected" : ""; ?>><?php echo privileges_name("3"); ?></option>
			<option class="span_auto" value="7" <?php echo ($privilege_import_logs == "7" ) ? "selected" : ""; ?>><?php echo privileges_name("7"); ?></option>	
		</select><br /><br />
		<input type="submit" name="update" value="Update" />
	</form>
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>