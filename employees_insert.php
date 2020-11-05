<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if (isset($_POST['ok'])) {
		header('Location: employees_list.php');
	}
	if (isset($_GET['success'])) {
?>
		<div class="page secondary" style="min-height:700px;">
			<div style="padding:5px; margin:5px;">
				<div class="page-header">
					<div class="page-header-content">
						<h1>Employee<small>insert</small></h1>
					</div>
				</div>
				<div class="page-region">
					<div class="page-region-content">
						<ul class="unstyled">
							<li>Employee was successfully inserted!</li>
							<br />
							<li class="as-inline-block">
								<form action="employees_insert.php" method="post">
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
		header ("Location: employees_list.php");
		exit();
	}
	
	if (isset($_POST['insert'])) {
		$employee_id_no = check_input(to_trim($_POST['employee_id_no']));
		$employee_username = check_input(tolower_trim($_POST['employee_username']));
		$employee_password = check_input($_POST['employee_password']);
		$employee_password_again = check_input($_POST['employee_password_again']);
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
		$birthdate_final = (empty($employee_birthdate)) ? 'null' : "'" . date("Y-m-d", strtotime($employee_birthdate)) . "'" ;
		$employee_marital_status = check_input($_POST['employee_marital_status']);
		$employee_gender = check_input($_POST['employee_gender']);
		$employee_date_hired = check_input($_POST['employee_date_hired']);
		$date_hired_final = (empty($employee_date_hired)) ? 'null' : "'" . date("Y-m-d", strtotime($employee_date_hired)) . "'" ;
		$employee_position = check_input($_POST['employee_position']);
		$employee_department = check_input($_POST['employee_department']);
		$employee_group = check_input($_POST['employee_group']);
		$employee_schedule = check_input($_POST['employee_schedule']);
		$employee_status = check_input($_POST['employee_status']);
		$employee_account_type = check_input($_POST['employee_account_type']);
		$active = (empty($_POST['active'])) ? "0" : "1";

		$employee_id_no_empty = (empty($employee_id_no) === true) ? "ID number is required!" : "";
		$employee_username_empty = (empty($employee_username) === true) ? "Username is required!" : "";
		$employee_last_name_empty = (empty($employee_last_name) === true) ? "Last name is required!" : "";
		$employee_first_name_empty = (empty($employee_first_name) === true) ? "First name is required!" : "";
		$employee_middle_name_empty = (empty($employee_middle_name) === true) ? "Middles name is required!" : "";
		$employee_position_empty = (empty($employee_position) === true) ? "Position is required!" : "";
		$employee_status_empty = (empty($employee_status) === true) ? "Status is required!" : "";
		$employee_account_type_empty = (empty($employee_account_type) === true) ? "Account type is required!" : "";
		
		if (empty($employee_id_no) === true) {
			$errors[] = "ID number is required!";
		}
		
		if (empty($employee_username) === true) {
			$errors[] = "Username is required!";
		}
		
		if (empty($employee_password) === true) {
			$errors[] = "Password is required!";
		}
		
		if ($employee_password != $employee_password_again) {
			$errors[] = "Password do not match!";
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
		
		if (employee_id_no_exists($employee_id_no, "") === true)  {
			$errors[] = "ID number already exist!";
		}
		
		if (employee_username_exists($employee_username, "") === true)  {
			$errors[] = "Username already exist!";
		}
		
		if (employee_email_exists($employee_email, "") === true)  {
			$errors[] = "Email already exist!";
		}
		
		if (filter_var($employee_email, FILTER_VALIDATE_EMAIL) === false && !empty($employee_email))  {
			$errors[] = "A valid email address is required!";
		}
		
		if (filter_var($employee_email2, FILTER_VALIDATE_EMAIL) === false && !empty($employee_email2))  {
			$errors[] = "A valid email2 address is required!";
		}
		
		$birthdate = explode("/", $employee_birthdate);
		if (@checkdate($birthdate[0], $birthdate[1], $birthdate[2]) === false && !empty($employee_birthdate ))  {
			$errors[] = "Birthdate invalid!";
		}
		
		$date_hired = explode("/", $employee_date_hired);
		if (@checkdate($date_hired[0], $date_hired[1], $date_hired[2]) === false && !empty($employee_date_hired))  {
			$errors[] = "Date hired invalid!";
		}
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "INSERT INTO employees (
			employee_profile, 
			employee_id_no, 
			employee_username, 
			employee_password, 
			employee_last_name, 
			employee_first_name, 
			employee_middle_name, 
			employee_address, 
			employee_email, 
			employee_email2, 
			employee_home_phone, 
			employee_work_phone, 
			employee_mobile_phone, 
			employee_contact_name, 
			employee_contact_relationship, 
			employee_contact_phone1, 
			employee_contact_phone2, 
			employee_birthdate, 
			employee_marital_status, 
			employee_gender, 
			employee_date_hired, 
			employee_position, 
			employee_department, 
			employee_group, 
			employee_schedule, 
			employee_status, 
			employee_account_type
			) 
			VALUES (
			'images/profile/nopicture.png', 
			'$employee_id_no', 
			'$employee_username', 
			md5('$employee_password'),
			'$employee_last_name', 
			'$employee_first_name', 
			'$employee_middle_name', 
			'$employee_address', 
			'$employee_email', 
			'$employee_email2', 
			'$employee_home_phone', 
			'$employee_work_phone', 
			'$employee_mobile_phone', 
			'$employee_contact_name', 
			'$employee_contact_relationship', 
			'$employee_contact_phone1', 
			'$employee_contact_phone2', 
			 $birthdate_final, 
			'$employee_marital_status', 
			'$employee_gender', 
			 $date_hired_final, 
			'$employee_position', 
			'$employee_department', 
			'$employee_group', 
			'$employee_schedule', 
			'$employee_status', 
			'$employee_account_type'
			)"); 
			include 'core/database/close.php' ;
			header ("Location: employees_list.php");
			exit();  
		} 
	}

?>
<form action="" method="post">
	<h1><a href="employees_list.php" style="text-decoration:none;">&#8656;</a> Insert Employee</h1>
<?php
	echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
	<br />
	<fieldset>
		<legend id="gene"><strong>General</strong> | <a href="#emer">Emergency Contact</a> | <a href="#demo">Demographic Info</a> | <a href="#empl">Employment Details</a> | <a href="#addi">Additional Info</a></legend>
		<br />
		<label for="employee_id_no" class="span2">ID number <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_id_no" id="employee_id_no" value="<?php echo (isset($employee_id_no)) ? $employee_id_no : '' ; ?>" /><br /><br />
		<label for="employee_username" class="span2">Username <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_username" id="employee_username" value="<?php echo (isset($employee_username)) ? $employee_username : '' ; ?>" /><br /><br />
		<label for="employee_password" class="span2">Password <span class="fg-color-red">*</span></label>
		<input type="password" name="employee_password" id="employee_password" value="" /><br /><br />
		<label for="employee_password_again" class="span2">Password again</label>
		<input type="password" name="employee_password_again" id="employee_password_again" value="" /><br /><br />
		<label for="employee_last_name" class="span2">Last name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_last_name" id="employee_last_name" value="<?php echo (isset($employee_last_name)) ?  $employee_last_name : '' ; ?>" /><br /><br />
		<label for="employee_password" class="span2">First name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_first_name" id="employee_first_name" value="<?php echo (isset($employee_first_name)) ?  $employee_first_name : '' ; ?>" /><br /><br />
		<label for="employee_middle_name" class="span2">Middle name <span class="fg-color-red">*</span></label>
		<input type="text" name="employee_middle_name" is="employee_middle_name" value="<?php echo (isset($employee_middle_name)) ?  $employee_middle_name : '' ; ?>" ><br /><br />
		<label for="employee_address" class="span2">Address</label>
		<textarea name="employee_address" id="employee_address" class="span6"><?php echo (isset($employee_address)) ? $employee_address : '' ; ?></textarea><br /><br />
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
		<legend id="emer"><a href="#gene">General</a> | <strong>Emergency Contact</strong> | <a href="#demo">Demographic Info</a> | <a href="#empl">Employment Details</a> | <a href="#addi">Additional Info</a></legend>
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
		<legend id="demo"><a href="#gene">General</a> | <a href="#emer">Emergency Contact</a> | <strong>Demographic Info</strong> | <a href="#empl">Employment Details</a> | <a href="#addi">Additional Info</a></legend>
		<br />
		<label for="employee_birthdate" class="span2">Birthdate <i>(mm/dd/yyyy)</i></label>
		<input type="date" name="employee_birthdate" id="employee_birthdate" class="span2" value="<?php echo (isset($employee_birthdate)) ?  $employee_birthdate : '' ; ?>" placeholder="mm/dd/yyyy" /><br /><br />
		<label for="employee_marital_status" class="span2">Marital status</label>
		<select name="employee_marital_status" id="employee_marital_status">
			<option value="">- Select marital status -</option>
			<option value="Married" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Married") ? "selected" : "" ; ?>>Married</option>
			<option value="Single" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Single") ? "selected" : "" ; ?>>Single</option>
			<option value="Widowed" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Widowed") ? "selected" : "" ; ?>>Widowed</option>
			<option value="Other" <?php echo (isset($employee_marital_status) && $employee_marital_status === "Other") ? "selected" : "" ; ?>>Other</option>
		</select><br /><br />
		<label for="employee_gender" class="span2">Gender</label>
		<select name="employee_gender" id="employee_gender">
			<option value="">- Select gender -</option>
			<option value="Male" <?php echo (isset($employee_gender) && $employee_gender === "Male") ? "selected" : "" ; ?>>Male</option>
			<option value="Female" <?php echo (isset($employee_gender) && $employee_gender === "Female") ? "selected" : "" ; ?>>Female</option>
		</select><br /><br />
	</fieldset>
	<br />
	<fieldset>
		<legend id="empl"><a href="#gene">General</a> | <a href="#emer">Emergency Contact</a> | <a href="#demo">Demographic Info</a> | <strong>Employment Details</strong> | <a href="#addi">Additional Info</a></legend>
		<br />
		<label for="employee_date_hired" class="span2">Date hired</label>
		<input type="date" name="employee_date_hired" id="employee_date_hired" class="span2" value="<?php echo (isset($employee_date_hired)) ? $employee_date_hired : '' ; ?>" placeholder="mm/dd/yyyy" /><br /><br />
		<label for="employee_position" class="span2">Position</label>
		<select name="employee_position" id="employee_position">
			<option value="">- Select position -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM positions WHERE active = 1 ORDER BY position_level ASC");
			while($rows = $query->fetch_assoc()) {
				$position_id = $rows['position_id'];
				$position_name = $rows['position_name'];
?>
				<option value="<?php echo $position_id; ?>" <?php echo (isset($employee_position) && $employee_position === $position_id) ? "selected" : "" ; ?>><?php echo $position_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_department" class="span2">Department</label>
		<select name="employee_department" id="employee_department">
			<option value="">- Select department -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM departments WHERE active = 1");
			while($rows = $query->fetch_assoc()) {
				$department_id = $rows['department_id'];
				$department_name = $rows['department_name'];
?>
				<option value="<?php echo $department_id; ?>" <?php echo (isset($employee_department) && $employee_department === $department_id) ? "selected" : "" ; ?>><?php echo $department_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_group" class="span2">Group</label>
		<select name="employee_group" id="employee_group" class="select">
			<option value="">- Select group -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM groups WHERE active = 1 ");
			while($rows = $query->fetch_assoc()) {
				$group_id = $rows['group_id'];
				$group_name = $rows['group_name'];
?>
				<option value="<?php echo $group_id; ?>" <?php echo (isset($employee_group) && $employee_group === $group_id) ? "selected" : "" ; ?>><?php echo $group_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>										
		</select><br /><br />
		<label for="employee_schedule" class="span2">Schedules</label>
		<select name="employee_schedule" id="employee_schedule">
			<option value="">- Select schedule -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM schedules");
			while($rows = $query->fetch_assoc()) {
				$schedule_id = $rows['schedule_id'];
				$schedule_name = $rows['schedule_name'];
			
?>
				<option value="<?php echo $schedule_id; ?>" <?php echo (isset($employee_schedule) && ($employee_schedule === $schedule_id)) ? "selected" : "" ; ?>><?php echo $schedule_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select><br /><br />
		<label for="employee_status" class="span2">Status</label>
		<select name="employee_status" id="employee_status">
			<option value="">- Select status -</option>
			<option value="Probationary" <?php echo (isset($employee_status) && $employee_status === "Probationary") ? "selected" : "" ; ?>>Probationary</option>
			<option value="Regular" <?php echo (isset($employee_status) && $employee_status === "Regular") ? "selected" : "" ; ?>>Regular</option>
			<option value="Permanent" <?php echo (isset($employee_status) && $employee_status === "Permanent") ? "selected" : "" ; ?>>Permanent</option>
		</select><br /><br />
	</fieldset>
	<br />
	<fieldset>
		<legend id="addi"><a href="#gene">General</a> | <a href="#emer">Emergency Contact</a> | <a href="#demo">Demographic Info</a> | <a href="#empl">Employment Details</a> | <strong>Additional Info</strong></legend>					
		<br />
		<label for="employee_account_type" class="span2">Account type <span class="fg-color-red">*</span></label>
		<select name="employee_account_type" id="employee_account_type">		
			<option value="">- Select account type -</option>
			<option value="Administrator" <?php if (!empty($employee_account_type) && $employee_account_type==="Administrator") {  echo "selected"; } ?>>Administrator</option>
			<option value="User" <?php if (!empty($employee_account_type) && $employee_account_type==="User") {  echo "selected"; } ?>>User</option>
		</select><br /><br />
	</fieldset>
	<br /><br />
	<span class="fg-color-red">&nbsp;All fields marked with an asterisk (*) are required.</span>
	<br /><br />
	<input type="submit" name="insert" value="Insert" />
</form>

<?php
	include 'includes/overall/footer.php' ; 
?>