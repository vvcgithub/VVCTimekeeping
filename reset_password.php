<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	include 'includes/logged_admin.php' ; 
	
	if (isset($_POST['reset'])) {
		$employee_id_no = check_input($_POST['employee_id_no']);
		
		if (empty($employee_id_no) === true) {
			$errors[] = "Employee is required!";
		}
		$employee_id_no_empty = (empty($employee_id_no) === true) ? "Employee name is required!" : "";
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT employee_username FROM employees WHERE employee_id_no=$employee_id_no");
			$rows = $query->fetch_assoc();
			$reset_password = strtolower($rows['employee_username']);
			$mysqli->query("UPDATE employees SET employee_password='" . md5($reset_password) . "' WHERE employee_id_no='$employee_id_no'"); 
			include 'core/database/close.php' ;
			header("Location: index.php");
			exit();
		}
	}
	
	if (isset($_POST['cancel'])) {
		header("Location: maintain.php");
		exit();
	}
?>
	<h1><a href="tools.php" style="text-decoration:none;" title="Back">&#8656;</a> Reset Password</h1>
		<form action="" method="post">	
<?php
			echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
				<br />
				<label for="employee_id_no" class="span1">Employee <span class="fg-color-red">*</span></label>
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
				<input type="submit" name="reset" value="Reset" />
		</form>
<?php
	include 'includes/overall/footer.php' ; 
?>