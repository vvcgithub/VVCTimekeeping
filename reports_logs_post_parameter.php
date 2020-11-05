<?php
	include 'core/init.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['view'])) {
		$employee_id_no = $_POST['employee_id_no'];
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors[] = "Period is required!";
		}
		
		if (empty($employee_id_no)) {
			$errors[] = "Employee is required!";
		}
		
		if (empty($errors)) {
			header("Location: reports_logs_post.php?employee_id_no=$employee_id_no&period_code=$period_code");
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: index.php");
		exit();
	}	
?>	
	<h1><a href="reports_main.php" style="text-decoration:none;">&#8656;</a> Post Logs</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="id" class="span1">Period <span class="fg-color-red">*</span></label>
		<select name="period_code" id="period_code">
			<option class="span_auto" value="" class="span4">- Select period -</option>
<?php
			$query = $mysqli->query("SELECT * FROM periods ORDER BY period_code DESC");
			while($rows = $query->fetch_assoc()) {
				$code = $rows['period_code'];
?>
				<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code) && ($period_code === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
			}
?>	
		</select><br /><br />
		<label for="employee_id_no" class="span1">Employee <span class="fg-color-red">*</span></label>
		<select name="employee_id_no" id="employee_id_no">
			<option class="span_auto" value="" class="span4">- Select employee -</option>
<?php
			$query = $mysqli->query("SELECT * FROM employees ORDER BY employee_last_name ASC");
			while($rows = $query->fetch_assoc()) {
				$employee_id_no = $rows['employee_id_no'];
				
?>
				<option class="span_auto" value="<?php echo $employee_id_no; ?>"><?php echo complete_name_from_id_no($employee_id_no); ?> </option>
<?php
			}
?>	
		</select><br /><br />
		<input type="submit" name="view" value="View" />
	</form>
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>