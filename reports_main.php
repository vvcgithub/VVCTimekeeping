<?php
	include 'core/init.php' ;
	include 'core/database/connect.php' ;	
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if (isset($_POST['view1'])) {
		$employee_id_no1 = $_POST['employee_id_no1'];
		$period_code1 = $_POST['period_code1'];
		
		if (empty($period_code1)) {
			$errors1[] = "Period is required!";
		}
		
		if (empty($employee_id_no1)) {
			$errors1[] = "Employee is required!";
		}
		
		if (empty($errors1)) {
			header("Location: reports_logs.php?employee_id_no=$employee_id_no1&period_code=$period_code1");
		}
		
	}
	
	if (isset($_POST['view2'])) {
		$employee_id_no2 = $_POST['employee_id_no2'];
		$period_code2 = $_POST['period_code2'];
		
		if (empty($period_code2)) {
			$errors2[] = "Period is required!";
		}
		
		if (empty($employee_id_no2)) {
			$errors2[] = "Employee is required!";
		}
		
		if (empty($errors2)) {
			header("Location: reports_logs_post.php?employee_id_no=$employee_id_no2&period_code=$period_code2");
		}
	}
	
	if (isset($_POST['view_summary'])) {
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors3[] = "Period is required!";
		}
		
		if (empty($errors3)) {
			header("Location: reports_logs_summary.php?period_code=$period_code");
			exit();
		}
	}
	
	if (isset($_POST['view_post_summary'])) {
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors4[] = "Period is required!";
		}
		
		if (empty($errors4)) {
			header("Location: reports_logs_post_summary.php?period_code=$period_code");
			exit();
		}
	}
	
	if (isset($_POST['view'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$date_from = date("Y-m-d", strtotime($from));
		$date_to = date("Y-m-d", strtotime($to));
		
		if (empty($from)) {
			$errors2[] = "Date from is required!";
		}
		
		if (empty($to)) {
			$errors2[] = "Date to is required!";
		}
		
		
		if (empty($errors2)) {
			header("Location: reports_logs_late.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
	
	if (isset($_POST['view_logs_overtime'])) {
		$period_code_logs_overtime = $_POST['period_code_logs_overtime'];
		
		if (empty($period_code_logs_overtime)) {
			$errors_logs_overtime[] = "Period is required!";
		}
		
		if (empty($errors_logs_overtime)) {
			header("Location: reports_logs_overtime.php?period_code=$period_code_logs_overtime");
			exit();
		}
	}
	
	if (isset($_POST['view_post_logs_overtime'])) {
		$period_code_post_logs_overtime = $_POST['period_code_post_logs_overtime'];
		
		if (empty($period_code_post_logs_overtime)) {
			$errors_post_logs_overtime[] = "Period is required!";
		}
		
		if (empty($errors_post_logs_overtime)) {
			header("Location: reports_logs_post_overtime.php?period_code=$period_code_post_logs_overtime");
			exit();
		}
	}
?>

	<h1><a href="index.php" style="text-decoration:none;">&#8656;</a> Reports</h1>
	<ul>
		<li><a href="#reports_logs_parameter" class="link_new padding10 span3" style="text-decoration:none;">Logs</a></li>
		<div id="reports_logs_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Logs</h2>
				<form action="" method="post">
<?php
					echo (isset($errors1) && !empty($errors1)) ? output_errors($errors1) : "";
?>
					<br />
					<label for="period_code1" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code1" id="period_code1">
						<option class="span_auto" value="" class="span4">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = $query->fetch_assoc()) {
							$code = $rows['period_code'];
?>
							<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code1) && ($period_code1 === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
						include 'core/database/close.php' ;
						}
?>	
					</select><br /><br />
					<label for="employee_id_no1" class="span1">Employee <span class="fg-color-red">*</span></label>
					<select name="employee_id_no1" id="employee_id_no1">
						<option class="span_auto" value="" class="span4">- Select employee -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM employees WHERE active = '1' ORDER BY employee_last_name ASC");
						while($rows = $query->fetch_assoc()) {
							$employee_id_no = $rows['employee_id_no'];
							
?>
							<option class="span_auto" value="<?php echo $employee_id_no; ?>" <?php if (isset($employee_id_no1) && ($employee_id_no1 === $employee_id_no)) { echo "selected"; } ?>><?php echo complete_name_from_id_no($employee_id_no); ?> </option>
<?php
						include 'core/database/close.php' ;
						}
?>	
					</select><br /><br />
					<input type="submit" name="view1" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_logs_post_parameter" class="link_new padding10 span3" style="text-decoration:none;">Post Logs</a></li>
		<div id="reports_logs_post_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Post Logs</h2>
				<form action="" method="post">
<?php
					echo (isset($errors2) && !empty($errors2)) ? output_errors($errors2) : "";
?>
					<br />
					<label for="period_code2" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code2" id="period_code2">
						<option class="span_auto" value="" class="span4">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = $query->fetch_assoc()) {
							$code = $rows['period_code'];
?>
							<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code2) && ($period_code2 === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
						include 'core/database/close.php' ;
						}
?>	
					</select><br /><br />
					<label for="employee_id_no2" class="span1">Employee <span class="fg-color-red">*</span></label>
					<select name="employee_id_no2" id="employee_id_no2">
						<option class="span_auto" value="" class="span4">- Select employee -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM employees WHERE active = '1' ORDER BY employee_last_name ASC");
						while($rows = $query->fetch_assoc()) {
							$employee_id_no = $rows['employee_id_no'];
							
?>
							<option class="span_auto" value="<?php echo $employee_id_no; ?>"><?php echo complete_name_from_id_no($employee_id_no); ?> </option>
<?php
						include 'core/database/close.php' ;
						}
?>	
					</select><br /><br />
					<input type="submit" name="view2" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_logs_summary_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Logs</a></li>
		<div id="reports_logs_summary_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Logs</h2>
				<form action="" method="post">
<?php
					echo (isset($errors3) && !empty($errors3)) ? output_errors($errors3) : "";
?>
					<br />
					<label for="period_code" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code" id="period_code">
						<option class="span_auto" value="">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = mysqli_fetch_array($query)) {
							$period_code = $rows['period_code'];
							$period_from = $rows['period_from'];
							$period_to = $rows['period_to'];
							
?>
							<option class="span_auto" value="<?php echo $period_code; ?>"><?php echo $period_code; ?></option>
<?php
						}
						include 'core/database/close.php' ;
?>	
					</select><br /><br />
					<input type="submit" name="view_summary" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_logs_post_summary_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Post Logs</a></li>
		<div id="reports_logs_post_summary_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Post Logs</h2>
				<form action="" method="post">
<?php
					echo (isset($errors4) && !empty($errors4)) ? output_errors($errors4) : "";
?>
					<br />
					<label for="period_code" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code" id="period_code">
						<option class="span_auto" value="">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = mysqli_fetch_array($query)) {
							$period_code = $rows['period_code'];
							$period_from = $rows['period_from'];
							$period_to = $rows['period_to'];
							
?>
							<option class="span_auto" value="<?php echo $period_code; ?>"><?php echo $period_code; ?></option>
<?php
						}
						include 'core/database/close.php' ;
?>	
					</select><br /><br />
					<input type="submit" name="view_post_summary" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_logs_late_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Logs Late</a></li>
		<div id="reports_logs_late_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Logs Late</h2>
				<form action="" method="post">
<?php
					echo (isset($errors2) && !empty($errors2)) ? output_errors($errors2) : "";
?>
					<br />
					<label for="date_from" class="span1">Date from <span class="fg-color-red">*</span></label>
					<input type="date" id="from" name="from" maxlength="10" class="span2" value="<?php echo (isset($from)) ? $from : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<label for="date_to" class="span1">Date to <span class="fg-color-red">*</span></label>
					<input type="date" id="to" name="to" maxlength="10" class="span2" value="<?php echo (isset($to)) ? $to : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<input type="submit" name="view" value="View" />
				</form>
			</div>
		</div>
		
		<li><a href="#reports_post_logs_late_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Post Logs Late</a></li>
		<div id="reports_post_logs_late_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Post Logs Late</h2>
				<form action="" method="post">
<?php
					echo (isset($errors_post_logs_late) && !empty($errors_post_logs_late)) ? output_errors($errors_post_logs_late) : "";
?>
					<br />
					<label for="period_code_post_logs_late" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code_post_logs_late" id="period_code_post_late">
						<option class="span_auto" value="">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = mysqli_fetch_array($query)) {
							$period_code_post_logs_late = $rows['period_code'];
							$period_from_post_logs_late = $rows['period_from'];
							$period_to_post_logs_late = $rows['period_to'];
							
?>
							<option class="span_auto" value="<?php echo $period_code_post_logs_late; ?>"><?php echo $period_code_post_logs_late; ?></option>
<?php
						}
						include 'core/database/close.php' ;
?>	
					</select><br /><br />
					<input type="submit" name="view_post_logs_late" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_logs_overtime_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Logs Overtime</a></li>
		<div id="reports_logs_overtime_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Logs Overtime</h2>
				<form action="" method="post">
<?php
					echo (isset($errors_logs_overtime) && !empty($errors_logs_overtime)) ? output_errors($errors_logs_overtime) : "";
?>
					<br />
					<label for="period_code_logs_overtime" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code_logs_overtime" id="period_code_logs_overtime">
						<option class="span_auto" value="">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = mysqli_fetch_array($query)) {
							$period_code_logs_overtime = $rows['period_code'];
							$period_from_logs_overtime = $rows['period_from'];
							$period_to_logs_overtime = $rows['period_to'];
							
?>
							<option class="span_auto" value="<?php echo $period_code_logs_overtime; ?>"><?php echo $period_code_logs_overtime; ?></option>
<?php
						}
						include 'core/database/close.php' ;
?>	
					</select><br /><br />
					<input type="submit" name="view_logs_overtime" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#reports_post_logs_overtime_parameter" class="link_new padding10 span3" style="text-decoration:none;">Summary Post Logs Overtime</a></li>
		<div id="reports_post_logs_overtime_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Summary Post Logs Overtime</h2>
				<form action="" method="post">
<?php
					echo (isset($errors_post_logs_overtime) && !empty($errors_post_logs_overtime)) ? output_errors($errors_post_logs_overtime) : "";
?>
					<br />
					<label for="period_code_post_logs_overtime" class="span1">Period <span class="fg-color-red">*</span></label>
					<select name="period_code_post_logs_overtime" id="period_code_post_logs_overtime">
						<option class="span_auto" value="">- Select period -</option>
<?php
						include 'core/database/connect.php' ;
						$query = $mysqli->query("SELECT * FROM periods WHERE active = '1' ORDER BY period_id DESC");
						while($rows = mysqli_fetch_array($query)) {
							$period_code_post_logs_overtime = $rows['period_code'];
							$period_from_post_logs_overtime = $rows['period_from'];
							$period_to_post_logs_overtime = $rows['period_to'];
							
?>
							<option class="span_auto" value="<?php echo $period_code_post_logs_overtime; ?>"><?php echo $period_code_post_logs_overtime; ?></option>
<?php
						}
						include 'core/database/close.php' ;
?>	
					</select><br /><br />
					<input type="submit" name="view_post_logs_overtime" value="View" />
				</form>
			</div>
		</div>
		<li><a href="charging_main.php" class="link_new padding10 span3" style="text-decoration:none;">Clients and employees charging</a></li>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_costing($employee_data['employee_id']) != 7) {
?>
		<li><a href="costing_main.php" class="link_new padding10 span3" style="text-decoration:none;">Clients Costing</a></li>
<?php
	}
?>
	</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>