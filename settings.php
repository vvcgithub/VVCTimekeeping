<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	include 'includes/logged_admin.php' ; 
	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM settings"); 
	$rows = $query->fetch_assoc();

	// check that the 'id' matches up with a rows in the databse
	if($rows) { // get data from db
		$company = $rows['company'];
		$company_email = $rows['company_email'];
		$main_home = $rows['main_home'];
		$log_in = $rows['log_in'];
		$log_out = $rows['log_out'];
		$calendar = $rows['calendar'];
		$time_logs_timezone = $rows['time_logs_timezone'];
		$facebook = $rows['facebook'];	
		$log_regular = $rows['log_regular'];
	}
	include 'core/database/close.php' ;
	
	if (isset($_POST['close'])) {
		header ("Location: index.php");
		exit();
	}
  
	if (isset($_POST['update'])) {
		$company = check_input($_POST['company']);
		$company_email = check_input($_POST['company_email']);
		$main_home = check_input($_POST['main_home']);
		
		$log_in = check_input($_POST['log_in']);
		$log_out = check_input($_POST['log_out']);
		$log_regular = check_input($_POST['log_regular']);
		$calendar = check_input($_POST['calendar']);
		$time_logs_timezone = check_input($_POST['time_logs_timezone']);
		$facebook = check_input($_POST['facebook']);

		if (empty($company) === true) {
			$errors[] = "Company name is required!";
		}
			
		if (empty($company_email) === true) {
			$errors[] = "Company email is required!";
		}
		
		if (empty($main_home) === true) {
			$errors[] = "Main home is required!";
		}
		
		if (empty($time_logs_timezone) === true) {
			$errors[] = "Time zone is required!";
		}
		
		if (filter_var($company_email, FILTER_VALIDATE_EMAIL) === false && !empty($company_email)) {
			$errors[] = "A valid company email address is required!";
		}

		if (empty($errors)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "UPDATE settings SET company='$company'
			, company_email='$company_email'
			, main_home='$main_home'
			, log_in='$log_in'
			, log_out='$log_out'
			, log_regular='$log_regular'
			, calendar='$calendar'
			, time_logs_timezone='$time_logs_timezone'
			, facebook='$facebook'
			"); 
			include 'core/database/close.php' ;
			// once saved, redirect back to the view page
			header("Location: index.php");
			exit();
		}
	}
?>
	<h1><a href="others.php" style="text-decoration:none;" title="Back">&#8656;</a> Settings</h1>
<?php
	echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>	
	<form action="settings.php" method="post">
		<label for="company" class="span2">Company name <span class="fg-color-red">*</span></label>
		<input type="text" name="company" id="company" class="span4" value="<?php echo $company; ?>" /><br /><br />
		<label for="company_email" class="span2">Company email <span class="fg-color-red">*</span></label>
		<input type="text" name="company_email" id="company_email" class="span3" value="<?php echo $company_email; ?>" /><br /><br />
		<label for="main_home" class="span2">Main home <span class="fg-color-red">*</span></label>
		<input type="text" name="main_home" id="main_home" class="span2" value="<?php echo $main_home; ?>" /><br /><br />
		<label for="log_in" class="span2">In</label>
		<input type="time" name="log_in" id="log_in" value="<?php echo $log_in; ?>" /><br /><br />
		<label for="log_out" class="span2">Out</label>
		<input type="time" name="log_out" id="log_out" value="<?php echo $log_out; ?>" /><br /><br />
		<label for="company" class="span2">Log regular hour <span class="fg-color-red">*</span></label>
		<input type="number" name="log_regular" class="span0" value="<?php echo $log_regular; ?>" /><br /><br />
		<label for="company" class="span2">Calendar <span class="fg-color-red">*</span></label>
		<textarea name="calendar_" class="span8"><?php echo $calendar; ?></textarea><br /><br />
		<label for="company" class="span2">Time zone <span class="fg-color-red">*</span></label>
		<input type="text" name="time_logs_timezone" class="span2" value="<?php echo $time_logs_timezone; ?>" /><br /><br />
		<label for="company" class="span2">Facebook <span class="fg-color-red">*</span></label>
		<input type="text" name="facebook" class="span7" value="<?php echo $facebook; ?>" /><br /><br />
		<input type="submit" name="update" value="Update" />
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>