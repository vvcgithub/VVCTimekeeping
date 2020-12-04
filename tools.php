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
			
			if (($handle = fopen($file_import_biometrics, "r")) !== FALSE){
			fgets($handle);
			while(($row = fgetcsv($handle, 0, ',')) !== FALSE){
				$mysqli->query('INSERT INTO `biometrics`
								SET
								`biometrics_id` = concat(STR_TO_DATE("'.$row[1].'", "%c/%e/%Y %H:%i"), "-", "'.$row[0].'")
								, `biometrics_employee_id_no` = "'.$row[0].'"
								, `biometrics_date` = STR_TO_DATE("'.$row[1].'","%c/%e/%Y")
								, `biometrics_time` = TIME(STR_TO_DATE("'.$row[1].'","%c/%e/%Y %H:%i"))');
				}
				fclose($handle);
			}
			include 'core/database/close.php' ;
			header('Location: biometrics_list.php');
			exit();
		}
	}
	
	if (isset($_POST['import_logs_employees'])) {
	    
		$employee_id_no_logs_employees = check_input($_POST['employee_id_no']); //To Check the employee id #
		$temp_import_logs_employees = $_FILES['upfile_import_logs_employees']['tmp_name'];// Target_file
		$file_import_logs_employees = check_input_file($temp_import_logs_employees);
		
		
		
		if (empty($employee_id_no_logs_employees) === true) {
			$errors_import_logs_employees[] = "Employee is required!";
		}
		
		if (empty($file_import_logs_employees) === true) {
			$errors_import_logs_employees[] = "CSV file is required!";
		}

		if (empty($errors_import_logs_employees)) {
			include 'core/database/connect.php' ;
		    if (($handle = fopen($file_import_logs_employees, "r")) !== FALSE){
		        fgets($handle);
		        while(($row = fgetcsv($handle, 1000, ',')) !== FALSE){
		            $mysqli->query('INSERT IGNORE INTO `logs` 
		                            SET 
		                            `log_employee_id_no` = "'.$employee_id_no_logs_employees.'"
		                            , `log_position_code` = "'.$row[0].'"
		                            , `log_period_code` = "'.$row[1].'"
		                            , `log_date` = STR_TO_DATE(NULLIF("'.$row[2].'",""), "%c/%e/%Y")
		                            , `log_in` = TIME(STR_TO_DATE(NULLIF("'.$row[3].'", ""), "%H:%i:%s"))
		                            , `log_out` = TIME(STR_TO_DATE(NULLIF("'.$row[4].'", ""), "%H:%i:%s"))
		                            , `log_client_code` = NULLIF("'.$row[5].'", "")
		                            , `log_regular` = NULLIF("'.$row[6].'", "")
		                            , `log_late` = NULLIF("'.$row[7].'", "")
		                            , `log_undertime` = NULLIF("'.$row[8].'", "")
		                            , `log_a` = NULLIF("'.$row[9].'", "")
		                            , `log_b` = NULLIF("'.$row[10].'", "")
		                            , `log_c` = NULLIF("'.$row[11].'", "")
		                            , `log_d` = NULLIF("'.$row[12].'", "")
		                            , `log_e` = NULLIF("'.$row[13].'", "")
		                            , `log_f` = NULLIF("'.$row[14].'", "")
		                            , `log_g` = NULLIF("'.$row[15].'", "")
		                            , `log_h` = NULLIF("'.$row[16].'", "")
		                            , `log_i` = NULLIF("'.$row[17].'", "")
		                            , `log_description` = NULLIF("'.$row[18].'", "")');
		        }
		        fclose($handle);
		    }
			include 'core/database/close.php' ;
			header("Location: logs_list.php#");
			
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
			<li><a href="#logs_empty" class="link_new padding10 span2" style="text-decoration:none;">Empty logs</a></li>
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
	 if (logged_in() === true) {
		 if ($employee_data['employee_account_type'] === 'Administrator') {
?>
	
			<div id="logs_empty" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Empty logs</h2>
					<p>Are you sure you want to empty logs table? If yes, select the <strong>empty</strong> button.</p>
					<form action="" method="post" enctype="multipart/form-data">
						<input type="submit" name="empty" value="Empty" />
					</form>
				</div>
			</div>
<?php
		 }
	 }
?>
<?php
	include 'includes/overall/footer.php' ; 
?>