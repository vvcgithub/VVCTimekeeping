<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_positions($employee_data['employee_id']) != 2 && privilege_positions($employee_data['employee_id']) != 4) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['position_id'])) {
		$position_id = $_GET['position_id'];
		$query = $mysqli->query("SELECT * FROM positions WHERE position_id=$position_id"); 
		$rows = $query->fetch_assoc();
		if($rows) { 
			$position_code = $rows['position_code'];
			$position_name = $rows['position_name'];
			$position_rate = $rows['position_rate'];
			$active = $rows['active'];
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
		//$position_id = check_input($_POST['position_id']);
		$position_code = check_input(strtoupper($_POST['position_code']));
		$position_name = check_input($_POST['position_name']);
		$position_rate = check_input($_POST['position_rate']);
		$active = (empty($_POST['active'])) ? "0" : "1";

		if (empty($position_code) === true) {
			$errors[] = "Code is required!";
		}
		
		if (empty($position_name) === true) {
			$errors[] = "Name is required!";
		}
		
		if (position_code_exists($position_code, $position_id) === true) {
			$errors[] = "Code already exist!";
		}

		if (empty($errors)) {
			$mysqli->query("UPDATE positions SET position_code='$position_code'
			, position_name='$position_name'
			, position_rate='$position_rate'
			, active='$active'
			WHERE position_id='$position_id'"); 
			header ("Location: positions_list.php?page=$page&text_search=$text_search");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header ("Location: positions_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="positions_list.php" style="text-decoration:none;">&#8656;</a> Update Position</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<label for="position_code" class="span1">Code <span class="fg-color-red">*</span></label>
		<input type="text" name="position_code" id="position_code" class="span2" value="<?php echo (isset($position_code)) ? $position_code : ""; ?>" /><br /><br />
		<label for="position_name" class="span1">Name <span class="fg-color-red">*</span></label>
		<input type="text" name="position_name" id="position_name" class="span4" value="<?php echo (isset($position_name)) ? $position_name : ""; ?>" /><br /><br />
		<label for="position_rate" class="span1">Rate <span class="fg-color-red">*</span></label>
		<input type="text" name="position_rate" id="position_rate" class="span1" value="<?php echo (isset($position_rate)) ? $position_rate : ""; ?>" /><br /><br />
		<label for="active" class="span1">Active</label>
		<input type="checkbox" name="active" id="active" <?php echo ($active==="1") ? "checked" : ""; ?> /><br /><br />
		<input type="submit" name="update" value="Update" />
	</form>
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>