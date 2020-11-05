<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['employee_id_no']) && isset($_GET['period_code'])) {
		$employee_id_no = $_GET['employee_id_no'];
		$period_code = $_GET['period_code'];
		$log_employee_id_no = $employee_data['employee_id_no'];	
	}
		
	if (isset($_POST['check_report'])) {
		include 'core/database/connect.php' ;
		$mysqli->query("UPDATE logs SET log_check = 1 WHERE log_employee_id_no='$employee_id_no' AND log_period_code='$period_code'"); 
		$mysqli->query("UPDATE loa SET loa_check = 1 WHERE loa_employee_id_no='$employee_id_no' AND loa_period_code='$period_code'"); 
		include 'core/database/close.php' ;
		header("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['uncheck_report'])) {
		include 'core/database/connect.php' ;
		$mysqli->query("UPDATE logs SET log_check = 0 WHERE log_employee_id_no='$employee_id_no' AND log_period_code='$period_code'"); 
		$mysqli->query("UPDATE loa SET loa_check = 0 WHERE loa_employee_id_no='$employee_id_no' AND loa_period_code='$period_code'"); 
		include 'core/database/close.php' ;
		header("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if(isset($_GET['delete'])) { 
		$log_id = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM logs WHERE log_id='$log_id'");
		include 'core/database/close.php' ;
		header("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: logs_insert_check.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
	
	if ($mysqli->affected_rows < 1) {
		echo "<h1>Check Logs</h1>";
		$errors[] = "No record found!";
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<p>
		<form action="" method="post">
			<input type="submit" name="close" value="Back" />
		</form>
		</p>
<?php
	} else {
?>
		<h1><a href="reviews_main.php#reviews_check_report_parameter" style="text-decoration:none;" title="Back">&#8656;</a> Check Logs</h1>
		<h3 class="print"><?php echo settings('company') . "<br />" . complete_name_from_id_no($employee_id_no); ?></h3>
		<h4 class="print"><?php echo "Period : $period_code"; ?></h4>
		<br />
		<form action="" method="post">
			<table class="print">
				<thead>
					<tr>
						<td colspan="2" class="center"><a href='<?php echo "logs_insert_check.php?employee_id_no=$employee_id_no&period_code=$period_code"; ?>'>Add</a></td>
						<td class="center bold">Date</td>
						<td class="center bold">Client</td>
						<td class="center bold">In</td>
						<td class="center bold">Out</td>
						<td class="center bold">Late</td>
						<td class="center bold">UT</td>
						<td class="center bold">A</td>
						<td class="center bold">B</td>
						<td class="center bold">C</td>
						<td class="center bold">D</td>
						<td class="center bold">E</td>
						<td class="center bold">F</td>
						<td class="center bold">G</td>
						<td class="center bold">H</td>
						<td class="center bold">OT</td>
						<td class="center bold">Reg</td>
						<td class="center bold">Description</td>
					</tr>
				</thead>
				<tbody>
<?php
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT * FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
					
					while($rows = $query->fetch_assoc()) {
						$log_id = $rows['log_id'];
						$log_employee_id_no = $rows['log_employee_id_no'];
						$log_period_code = $rows['log_period_code'];
						$log_date = $rows['log_date'];
						$log_client_code = $rows['log_client_code'];
						$client_name_from_code = client_name_from_code($log_client_code);
						$log_in = ($rows['log_in'] == NULL) ? "" : date("h:i A", strtotime($rows['log_in']));
						$query_log_in = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$log_employee_id_no' AND biometrics_date = '$log_date' ORDER BY biometrics_time ASC");
						$rows_in = mysqli_fetch_array($query_log_in);
						$log_in_biometrics = (mysqli_num_rows($query_log_in) > 0) ? date("h:i A", strtotime($rows_in['biometrics_time'])) : "null" ;
						$log_in_not_equal = ($log_in <> $log_in_biometrics) ? "red" : "green";
						$log_out =  ($rows['log_out'] == NULL) ? "" : date("h:i A", strtotime($rows['log_out']));
						$query_log_out = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$log_employee_id_no' AND biometrics_date = '$log_date' ORDER BY biometrics_time DESC");
						$rows_out = mysqli_fetch_array($query_log_out);
						$log_out_biometrics = (mysqli_num_rows($query_log_out) > 0) ? date("h:i A", strtotime($rows_out['biometrics_time'])) : "null" ;
						$log_out_not_equal = ($log_out <> $log_out_biometrics) ? "red" : "green";
						$log_late = ($rows['log_late'] == 0) ? "" : $rows['log_late'];
						$log_undertime = ($rows['log_undertime'] == 0) ? "" : $rows['log_undertime'];
						$log_a = ($rows['log_a'] == 0) ? "" : $rows['log_a'];
						$log_b = ($rows['log_b'] == 0) ? "" : $rows['log_b'];
						$log_c = ($rows['log_c'] == 0) ? "" : $rows['log_c'];
						$log_d = ($rows['log_d'] == 0) ? "" : $rows['log_d'];
						$log_e = ($rows['log_e'] == 0) ? "" : $rows['log_e'];
						$log_f = ($rows['log_f'] == 0) ? "" : $rows['log_f'];
						$log_g = ($rows['log_g'] == 0) ? "" : $rows['log_g'];
						$log_h = ($rows['log_h'] == 0) ? "" : $rows['log_h'];
						$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f + $log_g +$log_h;
						$log_overtime = ($log_overtime == 0) ? "" : $log_overtime;
						$log_regular = ($rows['log_regular'] == 0) ? "" : $rows['log_regular'];
						$log_description = $rows['log_description'];
						$log_check = $rows['log_check'];
?>
					
						<tr id="<?php echo $rows['log_id']; ?>" name="row_id[]" style="<?php echo ($log_check == 1) ? 'background:lightgreen;' : ''; ?>" >
							<td><a href="logs_update_check.php?log_id=<?php echo $log_id ; ?>">Edit</a></td>
							<td><a href="#" onclick="delete_confirm_for_logs_check('<?php echo $log_id; ?>', '<?php echo $log_date; ?>', '<?php echo $log_in; ?>', '<?php echo $log_out; ?>', '<?php echo $log_client_code; ?>', '<?php echo $log_employee_id_no; ?>', '<?php echo $log_period_code; ?>')">Delete</a></td>
							<td class="center"><?php echo date("m/d/y", strtotime($log_date)); ?></td>
							<td class="center"><?php echo $client_name_from_code; ?></td>
							<td class="center"><?php echo $log_in . "<br>" . "<span style='color:$log_in_not_equal; font-size:.8em;'><sup>b</sup>" . $log_in_biometrics . "</span>"; ?></td>
							<td class="center"><?php echo $log_out . "<br>" . "<span style='color:$log_out_not_equal; font-size:.8em;'><sup>b</sup>" . $log_out_biometrics . "</span>"; ?></td>
							<td class="center"><?php echo $log_late; ?></td>
							<td class="center"><?php echo $log_undertime; ?></td>
							<td class="center"><?php echo $log_a; ?></td>
							<td class="center"><?php echo $log_b; ?></td>
							<td class="center"><?php echo $log_c; ?></td>
							<td class="center"><?php echo $log_d; ?></td>
							<td class="center"><?php echo $log_e; ?></td>
							<td class="center"><?php echo $log_f; ?></td>
							<td class="center"><?php echo $log_g; ?></td>
							<td class="center"><?php echo $log_h; ?></td>
							<td class="center"><?php echo $log_overtime; ?></td>
							<td class="center"><?php echo $log_regular; ?></td>
							<td class="print"><?php echo $log_description; ?></td>
						</tr>
<?php
					}
					include 'core/database/close.php' ;
					
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum, SUM(log_g) as log_g_sum, SUM(log_h) as log_h_sum, SUM(log_regular) as log_regular_sum FROM logs WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
					$rows = $query->fetch_assoc();
					$log_late_sum = $rows['log_late_sum'];
					$log_undertime_sum = $rows['log_undertime_sum'];
					$log_a_sum = $rows['log_a_sum'];
					$log_b_sum = $rows['log_b_sum'];
					$log_c_sum = $rows['log_c_sum'];
					$log_d_sum = $rows['log_d_sum'];
					$log_e_sum = $rows['log_e_sum'];
					$log_f_sum = $rows['log_f_sum'];
					$log_g_sum = $rows['log_g_sum'];
					$log_h_sum = $rows['log_h_sum'];
					
					$log_overtime_sum = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum + $log_g_sum + $log_h_sum;
					$log_regular_sum = $rows['log_regular_sum'];
					include 'core/database/close.php' ;
?>				
					<tr style='background:white;'>
						<td colspan='6' style='text-align:right;'></td>
						<td class="center bold"><?php echo $log_late_sum ; ?></td>
						<td class="center bold"><?php echo $log_undertime_sum ; ?></td>
						<td class="center bold"><?php echo $log_a_sum ; ?></td>
						<td class="center bold"><?php echo $log_b_sum ; ?></td>
						<td class="center bold"><?php echo $log_c_sum ; ?></td>
						<td class="center bold"><?php echo $log_d_sum ; ?></td>
						<td class="center bold"><?php echo $log_e_sum ; ?></td>
						<td class="center bold"><?php echo $log_f_sum ; ?></td>
						<td class="center bold"><?php echo $log_g_sum ; ?></td>
						<td class="center bold"><?php echo $log_h_sum ; ?></td>
						<td class="center bold"><?php echo $log_overtime_sum ; ?></td>
						<td class="center bold"><?php echo $log_regular_sum ; ?></td>
						<td colspan="2"></td>
					</tr>
					<tr><td colspan='22'><br /></td></tr>
					<tr style='background:white;'>
						<td colspan='19'>&nbsp;A - Overtime rendered during regular working days (125%)
							<br>&nbsp;B - Overtime rendered after working in excess of 8 hours on a restday or on a special holiday (169%)
							<br>&nbsp;C - Work rendered during the 1st 8 hours on a rest day or on a special holiday (130%)
							<br>&nbsp;D - Overtime rendered on a regular day with night shift differential (between 10PM and 6AM) (137.5%)
							<br>&nbsp;E - Work rendered during 1st 8 hours on a regular holiday (100%)
							<br>&nbsp;F - Regular Holiday falling on rest day (260%)
							<br>&nbsp;G - In excess of 8 hrs on a Regular Holiday falling on rest day (338%)
							<br>&nbsp;H - In excess of 8 hrs on a regular holiday (160%)
							
						</td>
					</tr>
<?php
					$period_interval = (isset($log_period_code)) ? period_interval($log_period_code) : 0;
					$loa_sl = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_sl_check($log_period_code, $log_employee_id_no) : 0;
					$loa_vl = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_vl_check($log_period_code, $log_employee_id_no) : 0;
					$loa_lwop = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_lwop_check($log_period_code, $log_employee_id_no) : 0;
					$loa_mpaternity = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_mpaternity_check($log_period_code, $log_employee_id_no) : 0;
					$loa_id = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_id($log_period_code, $log_employee_id_no) : 0;
					$total_working_days = total_working_days($log_period_code, $log_employee_id_no);;
					//$total = $total_working_days + $loa_sl + $loa_vl + $loa_lwop;
					$total = count_distinct_days($log_period_code, $log_employee_id_no);
					$total_working_days = $total - $loa_sl - $loa_vl - $loa_lwop- $loa_mpaternity;
					$loa_check = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_check($log_period_code, $log_employee_id_no) : 0;
?>
					<tr><td colspan='19'><br /></td></tr>
					<tr style="background:white;">
						<td colspan="16" class="right bold">TOTAL WORKING DAYS</td><td class="bold center"><?php echo $total_working_days; ?></td><td colspan="2"></td>
					</tr>
					<tr style="<?php echo ($loa_check == 1) ? 'background:lightgreen;' : ''; ?>">
						<td rowspan="4">
<?php
							if ($loa_id == "" ) {
?>
								<a href="loa_insert_check.php?employee_id_no=<?php echo $log_employee_id_no; ?>&period_code=<?php echo $log_period_code; ?>">Add</a>
<?php
							} else {
?>
								<a href="loa_update_check.php?employee_id_no=<?php echo $log_employee_id_no; ?>&period_code=<?php echo $log_period_code; ?>">Edit</a>
<?php
							}
?>
							
						</td>
						<td colspan="15" class="right bold">SICK LEAVE (SL)</td><td class="bold center"><?php echo $loa_sl; ?></td><td colspan="2"></td>
					</tr>
					<tr style="<?php echo ($loa_check == 1) ? 'background:lightgreen;' : ''; ?>">
						<td colspan="15" class="right bold">VACATION LEAVE (VL)</td><td class="bold center"><?php echo $loa_vl; ?></td><td colspan="2"></td>
					</tr>
					<tr style="<?php echo ($loa_check == 1) ? 'background:lightgreen;' : ''; ?>">
						<td colspan="15" class="right bold">LEAVE W/O PAY</td><td class="bold center"><?php echo $loa_lwop; ?></td><td colspan="2"></td>
					</tr>
					<tr style="<?php echo ($loa_check == 1) ? 'background:lightgreen;' : ''; ?>">
						<td colspan="15" class="right bold">LEAVE (OTHERS)</td><td class="bold center"><?php echo $loa_mpaternity; ?></td><td colspan="2"></td>
					</tr>
					<tr style="background:white;">
						<td colspan="16" class="right bold">TOTAL</td><td class="center bold"><?php echo $total; ?></td><td colspan="2"></td>
					</tr>
				</tbody>
			</table>
			<br /><br /><br />
			<table style="border:1px solid white;" class="print">
				<tfoot>
					<tr>
						<td class="span4 center" style="border:0;">Prepared by:</td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0;">Reviewed by:</td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0;">Approved by:</td>
					</tr>
					<tr>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
					</tr>
					<tr>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
					</tr>
					<tr>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
					</tr>
					<tr>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
						<td style="border:0;"></td>
					</tr>
					<tr>
						<td class="span4 center" style="border:0;"><?php echo complete_name_from_id_no($employee_id_no); ?></td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0;"></td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0;"></td>
					</tr>
					<tr>
						<td class="span4 center" style="border:0; border-top:1px solid;">Employee's signature/Date</td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0; border-top:1px solid;">Signature over printed name/Date</td>
						<td style="border:0;"></td>
						<td class="span4 center" style="border:0; border-top:1px solid;">Signature over printed name/Date</td>
					</tr>
				</tfoot>
			</table>
			<br />
			<input type="submit" name="check_report" value="Check" />
			<input type="submit" name="uncheck_report" value="Uncheck" />
			<input type="submit" name="refresh" value="Refresh" />
			<input type="button" value="Print" onclick="window.print();return false;" />
		</form>
<?php
	}
	include 'includes/overall/footer.php' ; 
?>