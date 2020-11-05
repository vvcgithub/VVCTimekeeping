<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if ($employee_data['employee_account_type'] <> 'Administrator' && privilege_import_logs($employee_data['employee_id']) == 7) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['reset'])) {
		$employee_id_no = check_input($_POST['employee_id_no']);
		$new_password = check_input($_POST['new_password']);
		$new_password_again = check_input($_POST['new_password_again']);
		
		if (empty($employee_id_no) === true) {
			$errors1[] = "Employee is required!";
		}
		
		if (empty($new_password) === true) {
			$errors1[] = "New password is required!";
		}
		
		if (strlen($new_password) < 6 && empty($new_password) === false) {
			$errors1[] = "Minimum of 6 characters!";
		}
		
		if ($new_password != $new_password_again) {
			$errors1[] = "Password not match!";
		}
		
		if (empty($errors1)) {
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT employee_username FROM employees WHERE employee_id_no=$employee_id_no");
			$rows = $query->fetch_assoc();
			$mysqli->query("UPDATE employees SET employee_password='" . md5($new_password) . "' WHERE employee_id_no='$employee_id_no'"); 
			include 'core/database/close.php' ;
			header("Location: tools.php#");
			exit();
		}
	}
	
	if (isset($_POST['import_biometrics'])) {
		$temp_import_biometrics = $_FILES['upfile_import_biometrics']['tmp_name'];
		$file_import_biometrics = check_input_file($temp_import_biometrics);
		
		
		if (empty($file_import_biometrics) === true) {
			$errors_import_biometrics[] = "CSV file is required!";
		}
		
		if (empty($errors_import_biometrics)) {
			include 'core/database/connect.php' ;
			$mysqli->query("LOAD DATA INFILE '" . $file_import_biometrics . "' IGNORE INTO TABLE biometrics FIELDS TERMINATED BY ',' IGNORE 1 LINES 
			(@biometrics_id, @biometrics_date) SET biometrics_id = concat(STR_TO_DATE(@biometrics_date,'%c/%e/%Y %H:%i'), '-', @biometrics_id), biometrics_employee_id_no = @biometrics_id, biometrics_date = STR_TO_DATE(@biometrics_date,'%c/%e/%Y'), biometrics_time = TIME(STR_TO_DATE(@biometrics_date,'%c/%e/%Y %H:%i'))"); // format for military time '%h:%i %p'
			include 'core/database/close.php' ;
			header('Location: biometrics_list.php');
			exit();
		}
	}
	
	if (isset($_POST['import_logs_employees'])) {
		$employee_id_no_logs_employees = check_input($_POST['employee_id_no']);
		$temp_import_logs_employees = $_FILES['upfile_import_logs_employees']['tmp_name'];
		$file_import_logs_employees = check_input_file($temp_import_logs_employees);
		
		if (empty($employee_id_no_logs_employees) === true) {
			$errors_import_logs_employees[] = "Employee is required!";
		}
		
		if (empty($file_import_logs_employees) === true) {
			$errors_import_logs_employees[] = "CSV file is required!";
		}
		
		
		if (empty($errors_import_logs_employees)) {
			include 'core/database/connect.php' ;
			$mysqli->query("LOAD DATA INFILE '" . $file_import_logs_employees . "' REPLACE INTO TABLE logs FIELDS TERMINATED BY ',' IGNORE 1 LINES 
		(@log_position_code, @log_period_code, @log_date, @log_in, @log_out, @log_client_code, @log_regular, @log_late, @log_undertime, @log_a, @log_b, @log_c, @log_d, @log_e, @log_f, @log_g, @log_h, @log_description) SET log_employee_id_no = $employee_id_no_logs_employees, log_position_code = @log_position_code, log_period_code = @log_period_code, log_date = STR_TO_DATE(NULLIF(@log_date,''),'%c/%e/%Y'), log_in = TIME(STR_TO_DATE(NULLIF(@log_in,''),'%H:%i:%s')), log_out = TIME(STR_TO_DATE(NULLIF(@log_out,''),'%H:%i:%s')), log_client_code = NULLIF(@log_client_code,''), log_regular = NULLIF(@log_regular,''), log_late = NULLIF(@log_late,''), log_undertime = NULLIF(@log_undertime,''), log_a = NULLIF(@log_a,''), log_b = NULLIF(@log_b,''), log_c = NULLIF(@log_c,''), log_d = NULLIF(@log_d,''), log_e = NULLIF(@log_e,''), log_f = NULLIF(@log_f,''), log_g = NULLIF(@log_g,''), log_e = NULLIF(@log_e,''), log_h = NULLIF(@log_h,''), log_description = NULLIF(@log_description,'')"); // format for military time '%h:%i %p'
			include 'core/database/close.php' ;
			header("Location: tools.php#");
			exit();
		}
	}
	
	if (isset($_POST['empty'])) {
		include 'core/database/connect.php' ;
		$mysqli->query("TRUNCATE TABLE logs");
		include 'core/database/close.php' ;
		header("Location: tools.php#");
		exit();
	}
?>

	<h1><a href="others.php" style="text-decoration:none;">&#8656;</a> Tools</h1>
	<ul>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
			<li><a href="#reset_password" class="link_new padding10 span2" style="text-decoration:none;">Reset password</a></li>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator' || privilege_import_logs($employee_data['employee_id']) != 7) {
?>
			<li><a href="#logs_import_employees" class="link_new padding10 span2" style="text-decoration:none;">Import logs employees</a></li>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
			<li><a href="#import_biometrics" class="link_new padding10 span2" style="text-decoration:none;">Import biometrics</a></li>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
			<!----<li><a href="#logs_empty" class="link_new padding10 span2" style="text-decoration:none;">Empty logs</a></li>--->
<?php
		}
	}
?>
	</ul>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
			<div id="reset_password" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Reset Password</h2>
					<form action="" method="post">	
<?php
						echo (isset($errors1) && !empty($errors1)) ? output_errors($errors1) : "";
?>
							<br />
							<label for="employee_id_no" class="span2">Employee <span class="fg-color-red">*</span></label>
							<select name="employee_id_no" id="employee_id_no">
								<option value="">- Select employee -</option>
<?php
								include 'core/database/connect.php' ;
								$query = $mysqli->query("SELECT employee_id_no, employee_last_name FROM employees ORDER BY employee_last_name ASC");
								while($rows = mysqli_fetch_array($query)) {
									$row_employee_id_no = $rows['employee_id_no'];
?>
								<option value="<?php echo $row_employee_id_no; ?>" <?php echo (isset($employee_id_no) && $employee_id_no === $row_employee_id_no) ? 'selected' : '' ; ?>><?php echo complete_name_from_id_no($row_employee_id_no); ?></option>
<?php
								}
								include 'core/database/close.php' ;
?>
							</select>
							<br /><br />
							<label for="new_password" class="span2">New password <span class="fg-color-red">*</span></label>
							<input type="password" name="new_password" id="new_password" />
							<br /><br />
							<label for="new_password_again" class="span2">New password again <span class="fg-color-red">*</span></label>
							<input type="password" name="new_password_again" id="new_password_again" />
							<br /><br />
							<input type="submit" name="reset" value="Reset" />
					</form>
				</div>
			</div>
<?php
		}
	}
?>
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator' || privilege_import_logs($employee_data['employee_id']) != 7) {
?>	
			<div id="logs_import_employees" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Import logs employees</h2>
					<form action="" method="post" enctype="multipart/form-data">
					
<?php
						echo (isset($errors_import_logs_employees) && !empty($errors_import_logs_employees)) ? output_errors($errors_import_logs_employees) : "";
						echo (isset($messages) && !empty($messages)) ? output_messages($messages) : "";
?>
						<br />
						<label for="employee_id_no" class="span1">Employee <span class="fg-color-red">*</span></label>
						<select name="employee_id_no" id="employee_id_no">
							<option value="">- Select employee -</option>
<?php
							include 'core/database/connect.php' ;
							$query = $mysqli->query("SELECT * FROM employees ORDER BY employee_last_name ASC");
							while($rows = $query->fetch_assoc()) {
								$id_no = $rows['employee_id_no'];
?>
								<option class="span_auto" value="<?php echo $id_no; ?>" <?php echo (isset($employee_id_no) && $employee_id_no === $id_no) ? 'selected' : '' ; ?>><?php echo complete_name_from_id_no($id_no); ?></option>
<?php
							}
							include 'core/database/close.php' ;
?>
						</select>
						<br /><br />
						<label for="employee_id_no" class="span1">CSV file <span class="fg-color-red">*</span></label>
						<input type="file" id="upfile" name="upfile_import_logs_employees" accept="text/csv" class="span4" />
						<br /><br />
						<input type="submit" name="import_logs_employees" value="Import" />
					</form>
				</div>
			</div>
<?php
		}
	}
?>	
<?php
	if (logged_in() === true) {
		if ($employee_data['employee_account_type'] === 'Administrator') {
?>
			<div id="import_biometrics" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Import Biometrics</h2>
					<form action="" method="post" enctype="multipart/form-data">
<?php
						echo (isset($errors_import_biometrics) && !empty($errors_import_biometrics)) ? output_errors($errors_import_biometrics) : "";
						echo (isset($messages) && !empty($messages)) ? output_messages($messages) : "";
?>
						<ul class="unstyled">
							<li>Browse .csv file type</li>
							<li class="input-control text span5">
								<input type="file" id="upfile" name="upfile_import_biometrics" accept="text/csv" class="span4" />
							</li>
							<br />
							<li class="as-inline-block">
								<input type="submit" name="import_biometrics" value="Import" />
							</li>
						</ul>
					</form>
				</div>
			</div>
<?php
		}
	}
?>
<?php
	// if (logged_in() === true) {
		// if ($employee_data['employee_account_type'] === 'Administrator') {
?>
	
			<!---<div id="logs_empty" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Empty logs</h2>
					<p>Are you sure you want to empty logs table? If yes, select the <strong>empty</strong> button.</p>
					<form action="" method="post" enctype="multipart/form-data">
						<input type="submit" name="empty" value="Empty" />
					</form>
				</div>
			</div> --->
<?php
		// }
	// }
?>
<?php
	include 'includes/overall/footer.php' ; 
?>