<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	include 'includes/logged_admin.php' ;
	
	if(isset($_GET['period_code'])) { 
		$period_code = $_GET['period_code'];
	}
	
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
			header("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
			exit();
		}
	}
?>
	<h1><a href="others.php" style="text-decoration:none;" title="Back">&#8656;</a> Reviews</h1>
	<ul>
		<li><a href="#reviews_check_report_parameter" class="link_new padding10 span2" style="text-decoration:none;">Check Report</a></li>
		<div id="reviews_check_report_parameter" class="modalDialog">
				<div>
					<a href="#close" title="Close" class="close">X</a>
					<h2>Check Logs</h2>
				<form action="" method="post">
<?php
				echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
				<br />
				<label for="period_code" class="span1">Period <span class="fg-color-red">*</span></label>
				<select name="period_code" id="period_code" onchange="window.location='reviews_main.php?period_code=' + this.value + '#reviews_check_report_parameter'">
					<option value="" class="span_auto">- Select period -</option>
<?php
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT * FROM periods WHERE active = 1 ORDER BY period_id desc");
					while($rows = mysqli_fetch_array($query)) {
						$code = $rows['period_code'];
?>
						<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code) && ($period_code === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
					}
					
					include 'core/database/close.php' ;
?>	
				</select><br /><br />
				<label for="employee_id_no" class="span1">Employee <span class="fg-color-red">*</span></label>
				<select name="employee_id_no" id="employee_id_no">
					<option value="" class="span_auto">- Select employee -</option>
<?php
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT DISTINCT logs.log_employee_id_no, employees.employee_id_no, employees.employee_last_name FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no WHERE logs.log_period_code = '$period_code' ORDER BY employees.employee_last_name ASC");
					while($rows = mysqli_fetch_array($query)) { 
						$log_employee_id_no = $rows['log_employee_id_no'];
?>
						<option class="span_auto" value="<?php echo $log_employee_id_no; ?>" <?php if (isset($employee_id_no) && ($employee_id_no === $log_employee_id_no)) { echo "selected"; } ?>><?php echo complete_name_from_id_no($log_employee_id_no); ?></option>
<?php
					}
					include 'core/database/close.php' ;
					
?>	
				</select><br /><br />
				<input type="submit" name="view" value="View" />
			</form>
		</div>
	</div>
		<li><a href="reviews_post_report_parameter.php" class="link_new padding10 span2" style="text-decoration:none;">Post Report</a></li>
	</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>