<?php
	include 'core/init.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['employee_id_no']) && isset($_GET['period_code'])) {
			$employee_id_no = $_GET['employee_id_no'];
			$period_code = $_GET['period_code'];
	}
		
	$log_employee_id_no = $employee_data['employee_id_no'];
	
	if (isset($_POST['close'])) {
		header('Location: reports_print_parameter.php');
		exit();
	}
	
	if (isset($_POST['post'])) {
		if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
			$log_id = $_POST['log_id'];
			foreach ($log_id as $id => $value) {
				mysqli_query($mysqli, "UPDATE logs SET log_post = 1 WHERE log_id='$value'"); 
			}
		}
		header("Location: reports_logs.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['unpost'])) {
		if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
			$log_id = $_POST['log_id'];
			foreach ($log_id as $id => $value) {
				mysqli_query($mysqli, "UPDATE logs SET log_post = 0 WHERE log_id='$value'"); 
			}
		}
		header("Location: reports_logs.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: reports_logs.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
		
?>
	<h1>Preview Logs</h1>
	<h3 class="print"><?php echo settings('company') . "<br />" . complete_name_from_id_no($employee_id_no); ?></h3>
	<h4 class="print"><?php echo "Period : $period_code"; ?></h4>
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
				$query = $mysqli->query("SELECT * FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
				
				while($rows = $query->fetch_assoc()) {
					$log_id = $rows['log_id'];
					$log_employee_id_no = $rows['log_employee_id_no'];
					$log_period_code = $rows['log_period_code'];
					$log_date = $rows['log_date'];
					$log_client_code = $rows['log_client_code'];
					$client_name_from_code = client_name_from_code($log_client_code);
					$log_in = ($rows['log_in'] == NULL) ? "" : date("h:i A", strtotime($rows['log_in']));
					$log_out =  ($rows['log_out'] == NULL) ? "" : date("h:i A", strtotime($rows['log_out']));
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
?>
					<tr id="<?php echo $rows['log_id']; ?>" name="row_id[]" style="<?php echo ($log_check == 1) ? 'background:lightgreen;' : ''; ?>" >
						<td class="center"><?php echo date("m/d/y", strtotime($log_date)); ?></td>
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
				$query = $mysqli->query("SELECT SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum, SUM(log_regular) as log_regular_sum FROM logs WHERE log_employee_id_no='$employee_id_no' AND log_period_code = '$period_code' ORDER BY log_date ASC");
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
				$period_interval = (isset($log_period_code)) ? period_interval($log_period_code) : 0;
				$loa_sl = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_sl_check($log_period_code, $log_employee_id_no) : 0;
				$loa_vl = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_vl_check($log_period_code, $log_employee_id_no) : 0;
				$loa_lwop = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_lwop_check($log_period_code, $log_employee_id_no) : 0;
				$total_working_days = $period_interval - $loa_sl - $loa_vl - $loa_lwop;
				$loa_check = (isset($log_period_code) && isset($log_employee_id_no)) ? loa_check($log_period_code, $log_employee_id_no) : 0;
?>
				<tr><td colspan='17'><br /></td></tr>
				<tr style="background:white;">
					<td colspan="13" class="right bold">TOTAL WORKING DAYS</td><td class="bold center"><?php echo $total_working_days; ?></td><td colspan="2"></td>
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
					<td colspan="13" class="right bold">TOTAL</td><td class="center bold"><?php echo $period_interval; ?></td><td colspan="2"></td>
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
		<input type="submit" name="close" value="Close" />
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>