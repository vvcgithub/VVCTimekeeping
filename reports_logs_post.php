<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['employee_id_no']) && isset($_GET['period_code'])) {
			$employee_id_no = $_GET['employee_id_no'];
			$period_code = $_GET['period_code'];
	}
	
	$log_employee_id_no = $employee_data['employee_id_no'];
		
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM periods WHERE period_code = '$period_code'");
	while($rows = $query->fetch_assoc()) {
		$period_from = $rows['period_from'];
		$period_to = $rows['period_to'];
	}
	include 'core/database/close.php' ;
	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM logs_post WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
	
	if ($mysqli->affected_rows < 1) {
		echo "<h1><a href='reports_main.php#reports_logs_post_parameter' style='text-decoration:none;'>&#8656;</a> Preview Logs</h1>";
		$errors[] = "No record found!";
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
<?php
	} else {
?>
		<h1><a href="reports_main.php#reports_logs_post_parameter" style="text-decoration:none;">&#8656;</a> Post Logs</h1>
		<h3 class="print"><?php echo settings('company') . "<br />" . complete_name_from_id_no($employee_id_no); ?></h3>
		<h4 class="print"><?php echo "Period : $period_code - $period_from to $period_to"; ?></h4>
		<br />
		<form action="" method="post">
			<table class="print">
				<thead>
					<tr>
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
						<td class="center bold">OT</td>
						<td class="center bold">Reg</td>
						<td class="center bold">Description</td>
					</tr>
				</thead>
				<tbody>
<?php
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT * FROM logs_post WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
							
					while($rows = $query->fetch_assoc()) {
						$log_date = $rows['log_date'];
						$log_period_code = $rows['log_period_code'];
						$log_employee_id_no = $rows['log_employee_id_no'];
						$log_client_code = $rows['log_client_code'];
						$client_name_from_code = client_name_from_code($log_client_code);
						$log_in = ($rows['log_in'] === "00:00:00") ? "" : date("h:i A", strtotime($rows['log_in']));
						$log_out =  ($rows['log_out'] === "00:00:00") ? "" : date("h:i A", strtotime($rows['log_out']));
						$log_late = ($rows['log_late'] == 0) ? "" : $rows['log_late'];
						$log_undertime = ($rows['log_undertime'] == 0) ? "" : $rows['log_undertime'];
						$log_a = ($rows['log_a'] == 0) ? "" : $rows['log_a'];
						$log_b = ($rows['log_b'] == 0) ? "" : $rows['log_b'];
						$log_c = ($rows['log_c'] == 0) ? "" : $rows['log_c'];
						$log_d = ($rows['log_d'] == 0) ? "" : $rows['log_d'];
						$log_e = ($rows['log_e'] == 0) ? "" : $rows['log_e'];
						$log_f = ($rows['log_f'] == 0) ? "" : $rows['log_f'];
						$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f;
						$log_overtime = ($log_overtime == 0) ? "" : $log_overtime;
						$log_regular = ($rows['log_regular'] == 0) ? "" : $rows['log_regular'];
						$log_description = $rows['log_description'];
						$log_check = $rows['log_check'];
						$log_post = $rows['log_post'];
						
?>
						<tr id="<?php echo $rows['log_post_id']; ?>" name="row_id[]" style="<?php echo ($log_post == 1) ? 'background:lightgrey;' : ''; ?>" >
							<td class="center"><?php echo date("m/d/y", strtotime($log_date)); ?><sub><?php  echo date(" D", strtotime($log_date)); ?></sub></td>
							<td class="center"><?php echo $client_name_from_code; ?></td>
							<td class="center"><?php echo $log_in; ?></td>
							<td class="center"><?php echo $log_out; ?></td>
							<td class="center"><?php echo $log_late; ?></td>
							<td class="center"><?php echo $log_undertime; ?></td>
							<td class="center"><?php echo $log_a; ?></td>
							<td class="center"><?php echo $log_b; ?></td>
							<td class="center"><?php echo $log_c; ?></td>
							<td class="center"><?php echo $log_d; ?></td>
							<td class="center"><?php echo $log_e; ?></td>
							<td class="center"><?php echo $log_f; ?></td>
							<td class="center"><?php echo $log_overtime; ?></td>
							<td class="center"><?php echo $log_regular; ?></td>
							<td class="print"><?php echo $log_description; ?></td>
						</tr>
<?php
					}
					include 'core/database/close.php' ;

					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum, SUM(log_regular) as log_regular_sum FROM logs_post WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
					$rows = $query->fetch_assoc();
					$log_late_sum = $rows['log_late_sum'];
					$log_undertime_sum = $rows['log_undertime_sum'];
					$log_a_sum = $rows['log_a_sum'];
					$log_b_sum = $rows['log_b_sum'];
					$log_c_sum = $rows['log_c_sum'];
					$log_d_sum = $rows['log_d_sum'];
					$log_e_sum = $rows['log_e_sum'];
					$log_f_sum = $rows['log_f_sum'];
					$log_overtime_sum = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum;
					$log_regular_sum = $rows['log_regular_sum'];
					//$query_count_distinct_date = mysqli_fetch_array(mysqli_query($mysqli, "SELECT log_date, log_employee_id_no, SUM(log_count) AS sum_log_count FROM (SELECT * FROM logs WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' GROUP BY log_date ORDER BY log_date ASC) AS logs2"));
					include 'core/database/close.php' ;
?>				
					<tr style='background:white;'>
						<td colspan='4' style='text-align:right;'></td>
						<td class="center bold"><?php echo $log_late_sum ; ?></td>
						<td class="center bold"><?php echo $log_undertime_sum ; ?></td>
						<td class="center bold"><?php echo $log_a_sum ; ?></td>
						<td class="center bold"><?php echo $log_b_sum ; ?></td>
						<td class="center bold"><?php echo $log_c_sum ; ?></td>
						<td class="center bold"><?php echo $log_d_sum ; ?></td>
						<td class="center bold"><?php echo $log_e_sum ; ?></td>
						<td class="center bold"><?php echo $log_f_sum ; ?></td>
						<td class="center bold"><?php echo $log_overtime_sum ; ?></td>
						<td class="center bold"><?php echo $log_regular_sum ; ?></td>
						<td></td>
					</tr>
					<tr><td colspan='15'><br /></td></tr>
					<tr style='background:white;'>
						<td colspan='15'>&nbsp;A - Overtime rendered during regular working days (125%)
							<br>&nbsp;B - Overtime rendered after working in excess of 8 hours on a restday or on a special holiday (169%)
							<br>&nbsp;C - Work rendered during the 1st 8 hours on a rest day or on a special holiday (130%)
							<br>&nbsp;D - Overtime rendered on a regular day with night shift differential (between 10PM and 6AM) (137.5%)
							<br>&nbsp;E - Work rendered during 1st 8 hours on a regular holiday (200%)
							<br>&nbsp;F - Overtime rendered in excess of 8 hours on a rest day or on a special holiday with night differential (between 10PM to 6AM) (185%)
						</td>
					</tr>
<?php				
					//$period_interval = period_interval($log_period_code);
					$loa_sl = loa_sl_post($log_period_code, $log_employee_id_no);
					$loa_vl = loa_vl_post($log_period_code, $log_employee_id_no);
					$loa_lwop = loa_lwop_post($log_period_code, $log_employee_id_no);
					$total = count_distinct_days_post($log_period_code, $log_employee_id_no);
					$total_working_days_post = $total - $loa_sl - $loa_vl - $loa_lwop;
?>
					<tr><td colspan='15'><br /></td></tr>
					<tr style="background:white;">
						<td colspan="13" class="right bold">TOTAL WORKING DAYS</td><td class="bold center"><?php echo $total_working_days_post; ?></td><td colspan="2"></td>
					</tr>
					<tr style="background:white;">
						<td colspan="13" class="right bold">SICK LEAVE (SL)</td><td class="bold center"><?php echo $loa_sl; ?></td><td colspan="2"></td>
					</tr>
					<tr style="background:white;">
						<td colspan="13" class="right bold">VACATION LEAVE (VL)</td><td class="bold center"><?php echo $loa_vl; ?></td><td colspan="2"></td>
					</tr>
					<tr style="background:white;">
						<td colspan="13" class="right bold">LEAVE W/O PAY</td><td class="bold center"><?php echo $loa_lwop; ?></td><td colspan="2"></td>
					</tr>
					<tr style="background:white;">
						<td colspan="13" class="right bold">TOTAL</td><td class="center bold"><?php echo $total ; ?></td><td colspan="2"></td>
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
			<input type="button" value="Print" onclick="window.print();return false;" />
		</form>
<?php
	}
	include 'includes/overall/footer.php' ; 
?>