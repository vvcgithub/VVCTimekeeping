<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_positions($employee_data['employee_id']) != 2 && privilege_positions($employee_data['employee_id']) != 4) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['close'])) {
		header ("Location: positions_list.php");
		exit();
	}
  
	if (isset($_POST['insert'])) {
		$position_code = check_input(strtoupper($_POST['position_code']));
		$position_name = check_input($_POST['position_name']);
		$position_rate = check_input($_POST['position_rate']);

		if (empty($position_code) === true) {
			$errors[] = "Code is required!";
		}
		
		if (empty($position_name) === true) {
			$errors[] = "Name is required!";
		}
				
		if (position_code_exists($position_code, "") === true) {
			$errors[] = "Code already exist!";
		}

		if (empty($errors)) {
			$mysqli->query("INSERT INTO positions (position_code, position_name, position_rate) 
			VALUES (
			'$position_code', 
			'$position_name',
			'$position_rate'
			)"); 
			header('Location: positions_list.php');
			exit();
		} 
	}

?>
	<h1><a href="positions_list.php" style="text-decoration:none;">&#8656;</a> Insert Position</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="position_code" class="span1">Code <span class="fg-color-red">*</span></label>
		<input type="text" name="position_code" id="position_code" class="span2" value="<?php echo (isset($position_code)) ? $position_code : ""; ?>" /><br /><br />
		<label for="position_name" class="span1">Name <span class="fg-color-red">*</span></label>
		<input type="text" name="position_name" id="position_name" class="span4" value="<?php echo (isset($position_name)) ? $position_name : ""; ?>" /><br /><br />
		<label for="position_rate" class="span1">Rate <span class="fg-color-red">*</span></label>
		<input type="number" name="position_rate" id="position_rate" class="span1" value="<?php echo (isset($position_rate)) ? $position_rate : ""; ?>" /><br /><br />
		<input type="submit" name="insert" value="Insert" />
	</form>

<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>