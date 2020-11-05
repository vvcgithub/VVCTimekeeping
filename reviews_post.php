<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['employee_id_no']) && isset($_GET['period_code'])) {
		$employee_id_no = $_GET['employee_id_no'];
		$period_code = $_GET['period_code'];
		
		$log_employee_id_no = $employee_data['employee_id_no'];
		$query_log = mysql_query("SELECT * FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
		$row_sum = mysql_fetch_array(mysql_query("SELECT SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum, SUM(log_g) as log_g_sum, SUM(log_h) as log_h_sum, SUM(log_i) as log_i_sum, SUM(log_regular) as log_regular_sum FROM logs WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC"));
		$log_late_sum = $row_sum['log_late_sum'];
		$log_undertime_sum = $row_sum['log_undertime_sum'];
		$log_a_sum = $row_sum['log_a_sum'];
		$log_b_sum = $row_sum['log_b_sum'];
		$log_c_sum = $row_sum['log_c_sum'];
		$log_d_sum = $row_sum['log_d_sum'];
		$log_e_sum = $row_sum['log_e_sum'];
		$log_f_sum = $row_sum['log_f_sum'];
		$log_f_sum = $row_sum['log_g_sum'];
		$log_f_sum = $row_sum['log_h_sum'];
		$log_f_sum = $row_sum['log_i_sum'];
		$log_overtime_sum = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum + $log_g_sum + $log_h_sum + $log_i_sum;
		$log_regular_sum = $row_sum['log_regular_sum'];
		$query_count_distinct_date = mysql_fetch_array(mysql_query("SELECT log_date, log_employee_id_no, SUM(log_count) AS sum_log_count FROM (SELECT * FROM logs WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' GROUP BY log_date ORDER BY log_date ASC) AS logs2"));
		
		$query = mysql_query("SELECT * FROM periods WHERE period_code = '$period_code'");
		while($row = mysql_fetch_array($query)) {
			$period_from = $row['period_from'];
			$period_to = $row['period_to'];
		}
	}
		
	if (isset($_POST['close'])) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['post'])) {
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			foreach ($unique_id as $id => $value) {
				mysql_query("UPDATE logs SET log_post = 1 WHERE log_id='$value'"); 
			}
		}
		header("Location: logs_report_post.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['unpost'])) {
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			foreach ($unique_id as $id => $value) {
				mysql_query("UPDATE logs SET log_post = 0 WHERE log_id='$value'"); 
			}
		}
		header("Location: logs_report_post.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: logs_report_post.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
		
?>
	<h1>Post Logs</h1>
	<h3 class="print"><?php echo settings('company') . "<br />" . complete_name_from_id_no($employee_id_no); ?></h3>
	<h4 class="print"><?php echo "Period : $period_code - $period_from to $period_to"; ?></h4>
	<br />
	<table class="print">
		<thead>
					<tr>
						<td class="center bold">Date</td>
						<td class="center bold">Type</td>
						<td class="center bold">Count</td>
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
						<td class="center bold">I</td>
						<td class="center bold">OT</td>
						<td class="center bold">Reg</td>
						<td class="center bold">Description</td>
						<td class="center bold print"><input type="checkbox" onclick="toggle_logs_list(this)" /></td>
					</tr>
		</thead>
		<tbody>
<?php
					while($row_log = mysql_fetch_array($query_log)) { 
						$log_date = $row_log['log_date'];
						$log_type = log_type($row_log['log_type']);
						$log_count = log_count($row_log['log_count']);
						$log_client_code = $row_log['log_client_code'];
						$client_name_from_code = client_name_from_code($log_client_code);
						$log_in = ($row_log['log_in'] === "00:00:00") ? "" : date("h:i A", strtotime($row_log['log_in']));
						$log_out =  ($row_log['log_out'] === "00:00:00") ? "" : date("h:i A", strtotime($row_log['log_out']));
						$log_late = ($row_log['log_late'] == 0) ? "" : $row_log['log_late'];
						$log_undertime = ($row_log['log_undertime'] == 0) ? "" : $row_log['log_undertime'];
						$log_a = ($row_log['log_a'] == 0) ? "" : $row_log['log_a'];
						$log_b = ($row_log['log_b'] == 0) ? "" : $row_log['log_b'];
						$log_c = ($row_log['log_c'] == 0) ? "" : $row_log['log_c'];
						$log_d = ($row_log['log_d'] == 0) ? "" : $row_log['log_d'];
						$log_e = ($row_log['log_e'] == 0) ? "" : $row_log['log_e'];
						$log_f = ($row_log['log_f'] == 0) ? "" : $row_log['log_f'];
						$log_g = ($row_log['log_g'] == 0) ? "" : $row_log['log_g'];
						$log_h = ($row_log['log_h'] == 0) ? "" : $row_log['log_h'];
						$log_i = ($row_log['log_i'] == 0) ? "" : $row_log['log_i'];
						$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f + $log_g + $log_h + $log_i;
						$log_overtime = ($log_overtime == 0) ? "" : $log_overtime;
						$log_regular = ($row_log['log_regular'] == 0) ? "" : $row_log['log_regular'];
						$log_description = $row_log['log_description'];
						$log_checked = $row_log['log_checked'];
						$log_checker = $row_log['log_checker'];
						$log_checker_remarks = $row_log['log_checker_remarks'];
						$log_approved = $row_log['log_approved'];
						$log_approver = $row_log['log_approver'];
						$log_approver_remarks = $row_log['log_approver_remarks'];
						$log_post = $row_log['log_post'];
?>
					<form action="" method="post">
						<tr id="<?php echo $row_log['log_id']; ?>" name="row_id[]" style="<?php echo ($log_post == 1) ? 'background:lightgrey;' : ''; ?>" >
							<td class="center"><?php echo date("m/d/y", strtotime($log_date)); ?></td>
							<td class="center"><?php echo $log_type; ?></td>
							<td class="center"><?php echo $log_count; ?></td>
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
							<td class="center"><?php echo $log_g; ?></td>
							<td class="center"><?php echo $log_h; ?></td>
							<td class="center"><?php echo $log_i; ?></td>
							<td class="center"><?php echo $log_overtime; ?></td>
							<td class="center"><?php echo $log_regular; ?></td>
							<td class="print"><?php echo $log_description; ?></td>
							<td class="center"><input type="checkbox" name="unique_id[]" value="<?php echo $row_log['log_id']; ?>"  onclick="highlight(<?php echo $row_log['log_id']; ?>, this, <?php echo $log_post; ?>)"/></td>
						</tr>
<?php
					}
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
							<td class="center bold"><?php echo $log_i_sum ; ?></td>
							<td class="center bold"><?php echo $log_overtime_sum ; ?></td>
							<td class="center bold"><?php echo $log_regular_sum ; ?></td>
							<td colspan="2"></td>
						</tr>
						<tr><td colspan='18'><br /></td></tr>
						<tr style='background:white;'>
							<td colspan='18'>&nbsp;A - Overtime rendered during regular working days (125%)
								<br>&nbsp;B - Overtime rendered after working in excess of 8 hours on a restday or on a special holiday (169%)
								<br>&nbsp;C - Work rendered during the 1st 8 hours on a rest day or on a special holiday (130%)
								<br>&nbsp;D - Overtime rendered on a regular day with night shift differential (between 10PM and 6AM) (137.5%)
								<br>&nbsp;E - Work rendered during 1st 8 hours on a regular holiday (200%)
								<br>&nbsp;F - Overtime rendered in excess of 8 hours on a rest day or on a special holiday with night differential (between 10PM to 6AM) (185%)
							</td>
						</tr>
<?php
						$total_working_days = log_sum_present($employee_id_no, $period_code);
						$sick_leave = log_sum_sl($employee_id_no, $period_code);
						$vacation_leave = log_sum_vl($employee_id_no, $period_code);
						$leave_without_pay = log_sum_lwop($employee_id_no, $period_code);
						$total = $total_working_days + $sick_leave + $vacation_leave + $leave_without_pay;
?>
						<tr><td colspan='18'><br /></td></tr>
						<tr style="background:white;">
							<td colspan="5"></td>
							<td colspan="10" class="right bold">TOTAL WORKING DAYS</td><td class="bold center"><?php echo $total_working_days; ?></td><td colspan="2"></td>
						</tr>
						<tr style="background:white;">
							<td colspan="5"></td>
							<td colspan="10" class="right bold">SICK LEAVE (SL)</td><td class="bold center"><?php echo $sick_leave; ?></td><td colspan="2"></td>
						</tr>
						<tr style="background:white;">
							<td colspan="5"></td>
							<td colspan="10" class="right bold">VACATION LEAVE (VL)</td><td class="bold center"><?php echo $vacation_leave; ?></td><td colspan="2"></td>
						</tr>
						<tr style="background:white;">
							<td colspan="5"></td>
							<td colspan="10" class="right bold">LEAVE W/O PAY</td><td class="bold center"><?php echo $leave_without_pay; ?></td><td colspan="2"></td>
						</tr>
						<tr style="background:white;">
							<td colspan="5"></td>
							<td colspan="10" class="right bold">TOTAL</td><td class="center bold"><?php echo $total; ?></td><td colspan="2"></td>
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
			</td>
		</tr>
	</table>
	<br />


		<input type="submit" name="post" value="Post" />
		<input type="submit" name="unpost" value="Unpost" />
		<input type="submit" name="refresh" value="Refresh" />
		<input type="button" value="Print" onclick="window.print();return false;" />
		<input type="submit" name="close" value="Close" />
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>