<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	$employee_id = $employee_data['employee_id'];
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM employees WHERE employee_id=$employee_id"); 
	$rows = $query->fetch_assoc();

	// check that the 'id' matches up with a rows in the databse
	if($rows) { // get data from db
		$employee_id_no = $rows['employee_id_no'];
		$employee_username = $rows['employee_username'];
		$employee_last_name = $rows['employee_last_name'];
		$employee_first_name = $rows['employee_first_name']; 
		$employee_middle_name = $rows['employee_middle_name']; 
		$employee_address = $rows['employee_address']; 
		$employee_email = $rows['employee_email'];
		$employee_email2 = $rows['employee_email2'];
		$employee_home_phone = $rows['employee_home_phone'];
		$employee_work_phone = $rows['employee_work_phone'];
		$employee_mobile_phone = $rows['employee_mobile_phone'];
		$employee_contact_name = $rows['employee_contact_name'];
		$employee_contact_relationship = $rows['employee_contact_relationship'];
		$employee_contact_phone1 = $rows['employee_contact_phone1'];
		$employee_contact_phone2 = $rows['employee_contact_phone2'];
		$employee_birthdate = ($rows['employee_birthdate'] === NULL) ? '' : date("m/d/Y", strtotime($rows['employee_birthdate']));
		$employee_marital_status = $rows['employee_marital_status'];
		$employee_gender = $rows['employee_gender'];
		$employee_date_hired = ($rows['employee_date_hired'] === NULL) ? '' : date("m/d/Y", strtotime($rows['employee_date_hired']));
		$employee_position = $rows['employee_position'];
		$employee_department = $rows['employee_department'];
		$employee_group = $rows['employee_group'];
		$employee_status = $rows['employee_status'];
		$employee_schedule = $rows['employee_schedule'];
		$employee_account_type = $rows['employee_account_type'];
		$active = $rows['active'];
	}
	include 'core/database/close.php' ;

	
	if (isset($_POST['ok'])) {
		header('Location: employees_list.php');
	}
	if (isset($_GET['success'])) {
?>
		<div class="page secondary" style="min-height:400px;">
			<div style="padding:5px; margin:5px;">
				<div class="page-header">
					<div class="page-header-content">
						<h1>Employee<small>update</small></h1>
					</div>
				</div>
				<div class="page-region">
					<div class="page-region-content">
						<ul class="unstyled">
							<li>Employee was successfully updated!</li>
							<br />
							<li class="as-inline-block">
								<form action="employees_update.php" method="post">
								<input type="submit" name="ok" value="Ok" <?php echo "class='shortcut fg-color-$fgcolor bg-color-$bgcolor'" ?> />
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
<?php
		include("footer.php");
		exit();
	}
 
	if (isset($_POST['close'])) {
		header ("Location: index.php");
		exit();
	}
  
	if (isset($_POST['update'])) {
		$employee_id = check_input($_POST['employee_id']);
		$employee_last_name = check_input($_POST['employee_last_name']);
		$employee_first_name = check_input($_POST['employee_first_name']);
		$employee_middle_name = check_input($_POST['employee_middle_name']);
		$employee_address = check_input($_POST['employee_address']);
		$employee_email = check_input(tolower_trim($_POST['employee_email']));
		$employee_email2 = check_input(tolower_trim($_POST['employee_email2']));
		$employee_home_phone = check_input($_POST['employee_home_phone']);
		$employee_work_phone = check_input($_POST['employee_work_phone']);
		$employee_mobile_phone = check_input($_POST['employee_mobile_phone']);
		$employee_contact_name = check_input($_POST['employee_contact_name']);
		$employee_contact_relationship = check_input($_POST['employee_contact_relationship']);
		$employee_contact_phone1 = check_input($_POST['employee_contact_phone1']);
		$employee_contact_phone2 = check_input($_POST['employee_contact_phone2']);
		$employee_birthdate = check_input($_POST['employee_birthdate']);
		$birthdate_final = check_input($_POST['employee_birthdate']);
		$employee_marital_status = check_input($_POST['employee_marital_status']);
		$employee_gender = check_input($_POST['employee_gender']);
		$employee_date_hired = check_input($_POST['employee_date_hired']);
		$employee_position = check_input($_POST['employee_position']);
		$employee_department = check_input($_POST['employee_department']);
		$employee_group = check_input($_POST['employee_group']);
		$employee_schedule = check_input($_POST['employee_schedule']);
		$employee_status = check_input($_POST['employee_status']);
		
		if (empty($employee_id_no) === true) {
			$errors[] = "ID number is required!";
		}
		
		if (empty($employee_username) === true) {
			$errors[] = "Username is required!";
		}
		
		if (empty($employee_last_name) === true) {
			$errors[] = "Last name is required!";
		}
		
		if (empty($employee_first_name) === true) {
			$errors[] = "First name is required!";
		}
		
		if (empty($employee_middle_name) === true) {
			$errors[] = "Middle name is required!";
		}
		
		if (empty($employee_account_type) === true)  {
			$errors[] = "Account type is required!";
		}
		
		if (employee_id_no_exists($employee_id_no, $employee_id) === true)  {
			$errors[] = "ID number already exist!";
		}
		
		if (employee_username_exists($employee_username, $employee_id) === true)  {
			$errors[] = "Username already exist!";
		}
		
		if (employee_email_exists($employee_email, $employee_id) === true)  {
			$errors[] = "Email already exist!";
		}
		
		if (filter_var($employee_email, FILTER_VALIDATE_EMAIL) === false && !empty($employee_email))  {
			$errors[] = "A valid email address is required!";
		}
		
		if (filter_var($employee_email2, FILTER_VALIDATE_EMAIL) === false && !empty($employee_email2))  {
			$errors[] = "A valid email2 address is required!";
		}
		
		if ($active ==="0" && $employee_data['employee_id'] === $employee_id)  {
			$errors[] = "You cannot deactivate while logged in!";
		}
		
		if (empty($errors)) {
			$employee_birthdate = (empty($employee_birthdate))? 'NULL' : "'". date("Y-m-d", strtotime($employee_birthdate)) . "'";
			$employee_date_hired = (empty($employee_date_hired))? 'NULL' : "'". date("Y-m-d", strtotime($employee_date_hired)) . "'";
			include 'core/database/connect.php' ;
			$query = $mysqli->query("UPDATE employees SET employee_last_name='$employee_last_name'
			, employee_first_name='$employee_first_name'
			, employee_middle_name='$employee_middle_name'
			, employee_email='$employee_email'
			, employee_email2='$employee_email2'
			, employee_address='$employee_address'
			, employee_home_phone='$employee_home_phone'
			, employee_work_phone='$employee_work_phone'
			, employee_mobile_phone='$employee_mobile_phone'
			, employee_contact_name='$employee_contact_name'
			, employee_contact_relationship='$employee_contact_relationship'
			, employee_contact_phone1='$employee_contact_phone1'
			, employee_contact_phone2='$employee_contact_phone2'
			, employee_birthdate=$employee_birthdate
			, employee_marital_status='$employee_marital_status'
			, employee_gender='$employee_gender'
			, employee_date_hired=$employee_date_hired
			, employee_position='$employee_position'
			, employee_department='$employee_department'
			, employee_group='$employee_group'
			, employee_schedule='$employee_schedule'
			, employee_status='$employee_status' 
			WHERE employee_id='$employee_id'"); 
			include 'core/database/close.php' ;
			// once saved, redirect back to the view page
			header ("Location: index.php");
			exit();
		}
	}
?>
	<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Profile</h1>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
	<fieldset>
		<legend id="gene"><strong>General</strong> | <a href="#emer">Emergency Contact</a> | <a href="#demo">Demographic Info</a> | <a href="#empl">Employment Details</a></legend>
		<br />
		<input type="hidden" name="employee_id" id="employee_id" value="<?php echo (isset($employee_id)) ? $employee_id : '' ; ?>"/>
		<label for="employee_last_name" class="span2">Last name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_last_name" id="employee_last_name" value="<?php echo (isset($employee_last_name)) ?  $employee_last_name : '' ; ?>" /><br /><br />
		<label for="employee_first_name" class="span2">First name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_first_name" id="employee_first_name" value="<?php echo (isset($employee_first_name)) ?  $employee_first_name : '' ; ?>" /><br /><br />
		<label for="employee_middle_name" class="span2">Middle name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_middle_name" id="employee_middle_name" value="<?php echo (isset($employee_middle_name)) ?  $employee_middle_name : '' ; ?>" ><br /><br />
		<label for="employee_address" class="span2">Address</label>
		<textarea name="employee_address" id="employee_address"><?php echo (isset($employee_address)) ? $employee_address : '' ; ?></textarea><br /><br />
		<label for="employee_email" class="span2">Email</label>
		<input type="text" name="employee_email" id="employee_email" class="span3" value="<?php echo (isset($employee_email)) ?  $employee_email : '' ; ?>" /><br /><br />
		<label for="employee_email2" class="span2">Email 2</label>
		<input type="text" name="employee_email2" id="employee_email2" class="span3" value="<?php echo (isset($employee_email2)) ?  $employee_email2 : '' ; ?>" /><br /><br />
		<label for="employee_home_phone" class="span2">Home Phone</label>
		<input type="text" name="employee_home_phone" id="employee_home_phone" value="<?php echo (isset($employee_home_phone)) ?  $employee_home_phone : '' ; ?>" /><br /><br />
		<label for="employee_work_phone" class="span2">Work Phone</label>
		<input type="text" name="employee_work_phone" id="employee_work_phone" value="<?php echo (isset($employee_work_phone)) ?  $employee_work_phone : '' ; ?>" /><br /><br />
		<label for="employee_mobile_phone" class="span2">Mobile Phone</label>
		<input type="text" name="employee_mobile_phone" id="employee_mobile_phone" value="<?php echo (isset($employee_mobile_phone)) ?  $employee_mobile_phone : '' ; ?>" /><br /><br />
	</fieldset>
	<br />
	<fieldset>
		<legend id="emer"><a href="#gene">General</a> | <strong>Emergency Contact</strong> | <a href="#demo">Demographic Info</a> | <a href="#empl">Employment Details</a></legend>
		<br />
		<label for="employee_contact_name" class="span2">Contact Name</label>
		<input type="text" name="employee_contact_name" id="employee_contact_name" value="<?php echo (isset($employee_contact_name)) ?  $employee_contact_name : '' ; ?>" /><br /><br />
		<label for="employee_contact_relationship" class="span2">Relationship</label>
		<input type="text" name="employee_contact_relationship" id="employee_contact_relationship" value="<?php echo (isset($employee_contact_relationship)) ?  $employee_contact_relationship : '' ; ?>" /><br /><br />
		<label for="employee_contact_phone1" class="span2">Phone 1</label>
		<input type="text" name="employee_contact_phone1" id="employee_contact_phone1" value="<?php echo (isset($employee_contact_phone1)) ?  $employee_contact_phone1 : '' ; ?>" /><br /><br />
		<label for="employee_contact_phone2" class="span2">Phone 2</label>
		<input type="text" name="employee_contact_phone2" id="employee_contact_phone2" value="<?php echo (isset($employee_contact_phone2)) ?  $employee_contact_phone2 : '' ; ?>" /><br /><br />
	</fieldset>
	<br />
	<fieldset>
		<legend id="demo"><a href="#gene">General</a> | <a href="#emer">Emergency Contact</a> | <strong>Demographic Info</strong> | <a href="#empl">Employment Details</a></legend>
		<br />
		<label for="employee_birthdate" class="span2">Birthdate <i>(mm/dd/yyyy)</i></label>
		<input type="date" id="datepicker" name="employee_birthdate" id="employee_birthdate" class="span2" value="<?php echo (isset($employee_birthdate) && $employee_birthdate <> NULL) ?  date("Y-m-d", strtotime($employee_birthdate)) : '' ; ?>" placeholder="yyyy-mm-dd" /><br /><br />
		<label for="employee_marital_status" class="span2">Marital status</label>
		<select name="employee_marital_status" id="employee_marital_status">
			<option class="span_auto" value="">- Select marital status -</option>
			<option class="span_auto" value="Married" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Married") ? "selected" : "" ; ?>>Married</option>
			<option class="span_auto" value="Single" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Single") ? "selected" : "" ; ?>>Single</option>
			<option class="span_auto" value="Widowed" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Widowed") ? "selected" : "" ; ?>>Widowed</option>
			<option class="span_auto" value="Other" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Other") ? "selected" : "" ; ?>>Other</option>
		</select><br /><br />
		<label for="employee_gender" class="span2">Gender</label>
		<select name="employee_gender" id="employee_gender">
			<option class="span_auto" value="">- Select gender -</option>
			<option class="span_auto" value="Male" <?php echo (isset($employee_gender) && $employee_gender === "Male") ? "selected" : "" ; ?>>Male</option>
			<option class="span_auto" value="Female" <?php echo (isset($employee_gender) && $employee_gender === "Female") ? "selected" : "" ; ?>>Female</option>
		</select><br /><br />
	</fieldset>
	<br />
	<fieldset>
		<legend id="empl"><a href="#gene">General</a> | <a href="#emer">Emergency Contact</a> | <a href="#demo">Demographic Info</a> | <strong>Employment Details</strong></legend>
		<br />
	   <label for="employee_date_hired" class="span2">Date hired</label>
		<input type="date" name="employee_date_hired" id="employee_date_hired" class="span2" value="<?php echo (isset($employee_date_hired) && $employee_date_hired <> NULL) ? date("Y-m-d", strtotime($employee_date_hired)) : '' ; ?>" placeholder="yyyy-mm-dd" /><br /><br />
		<label for="employee_position" class="span2">Position</label>
		<select name="employee_position" id="employee_position">
			<option class="span_auto" value="">- Select position -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM positions WHERE active = 1 ORDER BY position_level ASC");
			while($rows = $query->fetch_assoc()) {
?>
				<option class="span_auto" value="<?php echo $rows['position_code']; ?>" <?php echo (isset($employee_position) && $employee_position === $rows['position_code']) ? "selected" : "" ; ?>><?php echo $rows['position_code'] . " - ". $rows['position_name']; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_department" class="span2">Department</label>
		<select name="employee_department" id="employee_department">
			<option class="span_auto" value="">- Select department -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM departments WHERE active = 1");
			while($rows = $query->fetch_assoc()) {
?>
				<option class="span_auto" value="<?php echo $rows['department_id']; ?>" <?php echo (isset($employee_department) && $employee_department === $rows['department_id']) ? "selected" : "" ; ?>><?php echo $rows['department_name']; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_group" class="span2">Group</label>
		<select name="employee_group" id="employee_group">
			<option class="span_auto" value="">- Select group -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM groups WHERE active = 1 ");
			while($rows = $query->fetch_assoc()) {
?>
				<option class="span_auto" value="<?php echo $rows['group_id']; ?>" <?php echo (isset($employee_group) && $employee_group === $rows['group_id']) ? "selected" : "" ; ?>><?php echo $rows['group_name']; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_schedule" class="span2">Schedules</label>
		<select name="employee_schedule" id="employee_schedule">
			<option class="span_auto" value="">- Select schedule -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM schedules");
			while($rows = $query->fetch_assoc()) {
				$schedule_id = $rows['schedule_id'];
				$schedule_name = $rows['schedule_name'];
			
?>
				<option class="span_auto" value="<?php echo $schedule_id; ?>" <?php echo (isset($employee_schedule) && ($employee_schedule === $schedule_id)) ? "selected" : "" ; ?>><?php echo $schedule_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select><br /><br />
		<label for="employee_status" class="span2">Status</label>
		<select name="employee_status" id="employee_status">
			<option class="span_auto" value="">- Select status -</option>
			<option class="span_auto" value="Probationary" <?php echo (isset($employee_status) && $employee_status === "Probationary") ? "selected" : "" ; ?>>Probationary</option>
			<option class="span_auto" value="Regular" <?php echo (isset($employee_status) && $employee_status === "Regular") ? "selected" : "" ; ?>>Regular</option>
			<option class="span_auto" value="Permanent" <?php echo (isset($employee_status) && $employee_status === "Permanent") ? "selected" : "" ; ?>>Permanent</option>
		</select><br /><br />
		
    </fieldset>
	<br />
	<input type="submit" name="update" value="Update" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>