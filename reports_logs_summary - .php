<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['period_code'])) {
		$period_code = $_GET['period_code'];
	} else {
		$period_code = "";
	}
	
	if (isset($_GET['number_page'])) {
		$number_page = $_GET['number_page'];
		$current_page = 1;
	} else {
		$number_page = "100";
		$current_page = 1;
	}
	
	if (isset($_GET['level'])) {
		$level = $_GET['level'];
		$current_page = 1;
	} else {
		$level = "%";
		$current_page = 1;
	}

	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = check_input($current_page);
	} else {
		$current_page = 1;
		header("Location: reports_logs_summary.php?page=1&period_code=$period_code&number_page=$number_page&level=$level");
	}
	
	if(isset($_GET['export'])) {
		header("Location: reports_summary_export.php?period_code=$period_code");
		exit();
	}
	
	if(isset($_POST['post'])) {
		header("Location: reviews_main.php");
		exit();
	}
	

			
	include 'core/database/connect.php' ;
	$mysqli->query("SELECT DISTINCT logs.log_employee_id_no, logs.log_period_code, employees.employee_id_no, employees.employee_last_name FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no WHERE logs.log_period_code = '$period_code' ORDER BY employees.employee_last_name");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = $number_page;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1)* $per_page;
	
	if (isset($_POST['back'])) {
		header("Location: reports_logs_summary_parameter.php");
		exit();
	}
?>
<?php
$csv_output = array();
	$csv_output[] = '';
	$csv_hdr = "ID#, Name, TWD, SL, VL, LWOP, Others, TOTAL, Late, Undertime, A, B, C, D, E, F, G, H, I, Overtime, \n";
	
			$csv_periodcode = "";
			
			$csv_emp_id = array();
			$csv_emp_id[] ='';
			$csv_emp_name = array();
			$csv_emp_name[] ='';
			$csv_TWD= array();
			$csv_TWD[] ='';
			$csv_SL = array();
			$csv_SL[] ='';
			$csv_VL = array();
			$csv_VL[] ='';
			$csv_LWOP = array();
			$csv_LWOP[] ='';
			$csv_others = array();
			$csv_others[] ='';
			$csv_TOTAL = array();
			$csv_TOTAL[] ='';
			$csv_late = array();
			$csv_late[] ='';
			$csv_undertime = array();
			$csv_undertime[] ='';
			$csv_a = array();
			$csv_a[] ='';
			$csv_b = array();
			$csv_b[] ='';
			$csv_c = array();
			$csv_c[] ='';
			$csv_d = array();
			$csv_d[] ='';
			$csv_e = array();
			$csv_e[] ='';
			$csv_f = array();
			$csv_f[] ='';
			$csv_g = array();
			$csv_g[] ='';
			$csv_h = array();
			$csv_h[] ='';
			$csv_i = array();
			$csv_i[] ='';
			$csv_overtime = array();
			$csv_overtime[] ='';
?>



	<h1 class="print"><a href="reports_main.php#reports_logs_summary_parameter" style="text-decoration:none;" title="Back">&#8656;</a>  Logs Summary</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="hidden" name="sort" value="<?php echo (isset($sort)) ? $sort : ''; ?>" />
		<input type="hidden" name="order" value="<?php echo (isset($order)) ? $order : ''; ?>" />
		<input type="hidden" name="period_from" value="<?php echo (isset($period_from)) ? $period_from : ''; ?>" />
		<input type="hidden" name="period_to" value="<?php echo (isset($period_to)) ? $period_to : ''; ?>" />
	</form>
	<div style="overflow:auto;width:100%;height:100%;">
		<table class="print table_list">
			<thead class="bg-color-dimgrey fg-color-white">
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="20" class="left">
						<form action="" method="post">
							<input type="button" onclick="window.print();return false;"  value="Print" class="" />
							<?php
									if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
							?>
							<input type="submit" name="post" value="Post" class="" />
							
							
<?php
	}
?>
						</form>

					</th>
				</tr>
				<tr>
					<th colspan="10" class="left">
						<span><?php echo "Period code: $period_code"; ?></span>
					</th>
					<th colspan="10" class="left">
						<form action="" method="get">
							<select name="number_page" onchange="window.location='reports_logs_summary.php?page=1&period_code=<?php echo $period_code; ?>&' + this.name + '=' + this.value + '&level=<?php echo $level; ?>'">
								<option value="110" <?php echo(isset($number_page) && $number_page == 100) ? 'selected' : ''; ?>>100</option>
								<option value="150" <?php echo(isset($number_page) && $number_page == 150) ? 'selected' : ''; ?>>150</option>
								<option value="200" <?php echo(isset($number_page) && $number_page == 200) ? 'selected' : ''; ?>>200</option>
								<option value="250" <?php echo(isset($number_page) && $number_page == 250) ? 'selected' : ''; ?>>250</option>
								<option value="300" <?php echo(isset($number_page) && $number_page == 300) ? 'selected' : ''; ?>>300</option>
								<option value="350" <?php echo(isset($number_page) && $number_page == 350) ? 'selected' : ''; ?>>350</option>
								<option value="400" <?php echo(isset($number_page) && $number_page == 400) ? 'selected' : ''; ?>>400</option>
							</select>
	<!--
							</span><span>records per page</span>
							<select name="level" onchange="window.location='reports_logs_summary.php?page=1&period_code=<?php echo $period_code; ?>&number_page=<?php echo $number_page; ?>&' + this.name + '=' + this.value	">
								<option value="%" <?php echo(isset($level) && $level == 1) ? 'selected' : ''; ?>>All</option>
								<option value="1" <?php echo(isset($level) && $level == 1) ? 'selected' : ''; ?>>Officer</option>
								<option value="2" <?php echo(isset($level) && $level == 2) ? 'selected' : ''; ?>>Rank and File</option>
							</select>
							</span><span>level</span>
	-->
						</form>
					</th>
				</tr>
				<tr>
					<th rowspan="2">ID#</th>
					<th rowspan="2">Name</th>
					<th rowspan="2">TWD</th>
					<th rowspan="2">SL</th>
					<th rowspan="2">VL</th>
					<th rowspan="2">LWOP</th>
					<th rowspan="2">Others</th>
					<th rowspan="2">TOTAL</th>
					<th rowspan="2">Late</th>
					<th rowspan="2">Undertime</th>
					<th>A</th>
					<th>B</th>
					<th>C</th>
					<th>D</th>
					<th>E</th>
					<th>F</th>
					<th>G</th>
					<th>H</th>
					<th>I</th>
					<th rowspan="2" style="min-width:60px;">Overtime</th>
				</tr>
				<tr>
					<th>125%</th>
					<th>169%</th>
					<th>130%</th>
					<th>137.5%</th>
					<th>100%</th>
					<th>260%</th>
					<th>338%</th>
					<th>160%</th>
					<th>160%</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				//$query = $mysqli->query("SELECT DISTINCT logs.log_employee_id_no, logs.log_position_code, positions.position_code, positions.position_level, logs.log_period_code, employees.employee_id_no, employees.employee_position, employees.employee_last_name FROM logs INNER JOIN employees ON logs.log_employee_id_no = employees.employee_id_no WHERE logs.log_period_code = '$period_code' AND positions.position_level LIKE '%' ORDER BY employees.employee_last_name LIMIT $limit ,$per_page");
				$query = $mysqli->query("SELECT DISTINCT logs.log_employee_id_no, logs.log_period_code, employees.employee_id_no, employees.employee_position, employees.employee_last_name FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no WHERE logs.log_period_code = '$period_code' ORDER BY employees.employee_last_name LIMIT $limit ,$per_page");
				while($rows = $query->fetch_assoc()) {
					$period_code = $rows['log_period_code'];	
					$employee_id_no = $rows['employee_id_no'];	
					
					
					$query_log = $mysqli->query("SELECT SUM(log_late) as log_late_sum
												, SUM(log_undertime) as log_undertime_sum
												, SUM(log_a) as log_a_sum
												, SUM(log_b) as log_b_sum
												, SUM(log_c) as log_c_sum
												, SUM(log_d) as log_d_sum
												, SUM(log_e) as log_e_sum
												, SUM(log_f) as log_f_sum
												, SUM(log_g) as log_g_sum
												, SUM(log_h) as log_h_sum
												, SUM(log_i) as log_i_sum
												FROM logs 
												WHERE log_employee_id_no = $employee_id_no 
												AND log_period_code = '$period_code'");
					$row_log = $query_log->fetch_assoc();
					if($row_log) { // get data from db
					    
						$log_late_sum = $row_log['log_late_sum'];
						$log_undertime_sum = $row_log['log_undertime_sum'];
						$log_a_sum = $row_log['log_a_sum'];
						$log_b_sum = $row_log['log_b_sum'];
						$log_c_sum = $row_log['log_c_sum'];
						$log_d_sum = $row_log['log_d_sum'];
						$log_e_sum = $row_log['log_e_sum'];
						$log_f_sum = $row_log['log_f_sum'];
						$log_g_sum = $row_log['log_g_sum'];
						$log_h_sum = $row_log['log_h_sum'];
						$log_i_sum = $row_log['log_i_sum'];
						$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum + $log_g_sum + $log_h_sum + $log_i_sum;
					} else {
						$log_late_sum = "";
						$log_undertime_sum = "";
						$log_a_sum = "";
						$log_b_sum = "";
						$log_c_sum = "";
						$log_d_sum = "";
						$log_e_sum = "";
						$log_f_sum = "";
						$log_g_sum = "";
						$log_h_sum = "";
						$log_i_sum = "";
						$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum + $log_g_sum + $log_h_sum + $log_i_sum;
					}

					$period_interval = (isset($period_code)) ? period_interval($period_code) : 0;
					
					$query_loa = $mysqli->query("SELECT loa_sl, loa_vl, loa_lwop, loa_mpaternity FROM loa WHERE loa_period_code = '$period_code' AND loa_employee_id_no = '$employee_id_no'");
					$rows_loa = $query_loa->fetch_assoc();
					if($rows_loa) { // get data from db
						$loa_sl = $rows_loa['loa_sl'];
						$loa_vl = $rows_loa['loa_vl'];
						$loa_lwop = $rows_loa['loa_lwop'];
						$loa_mpaternity = $rows_loa['loa_mpaternity'];
						$total = count_distinct_days($period_code, $employee_id_no);
						$total_working_days = $total - $loa_sl - $loa_vl - $loa_lwop - $loa_mpaternity;
					} else {
						$loa_sl = "";
						$loa_vl = "";
						$loa_lwop = "";
						$loa_mpaternity = "";
						$total = count_distinct_days($period_code, $employee_id_no);
						$total_working_days = $total - $loa_sl - $loa_vl - $loa_lwop - $loa_mpaternity;
					}
?>								
				<tr class="alternate">
					<td class="right"><?php echo $employee_id_no; $csv_emp_id = $employee_id_no;?></td>
					<td><a href='<?php echo "reviews_logs_check.php?employee_id_no=$employee_id_no&period_code=$period_code" ; ?>'><?php echo complete_name_from_id_no($employee_id_no); ?></a></td>
					<td class="right"><?php echo (!empty($total_working_days)) ?  number_format($total_working_days, 2, '.', ',') : "-"; $csv_TWD = $total_working_days; ?></td>
					<td class="right"><?php echo (!empty($loa_sl)) ?  number_format($loa_sl, 2, '.', ',') : "-"; $csv_SL = $loa_sl;?></td>
					<td class="right"><?php echo (!empty($loa_vl)) ?  number_format($loa_vl, 2, '.', ',') : "-"; $csv_VL = $loa_vl;?></td>
					<td class="right"><?php echo (!empty($loa_lwop)) ?  number_format($loa_lwop, 2, '.', ',') : "-"; $csv_LWOP = $loa_lwop;?></td>
					<td class="right"><?php echo (!empty($loa_mpaternity)) ?  number_format($loa_mpaternity, 2, '.', ',') : "-"; $csv_others = $loa_mpaternity;?></td>
					<td class="right"><?php echo (!empty($period_interval)) ?  number_format($total, 2, '.', ',') : "-"; $csv_TOTAL = $period_interval;?></td>
					<td class="right"><?php echo (!empty($log_late_sum)) ?  number_format($log_late_sum, 2, '.', ',') : "-"; $csv_late = $log_late_sum;?></td>
					<td class="right"><?php echo (!empty($log_undertime_sum)) ?  number_format($log_undertime_sum, 2, '.', ',') : "-"; $csv_undertime = $log_undertime_sum;?></td>
					<td class="right"><?php echo (!empty($log_a_sum)) ?  number_format($log_a_sum, 2, '.', ',') : "-"; $csv_a = $log_a_sum;?></td>
					<td class="right"><?php echo (!empty($log_b_sum)) ?  number_format($log_b_sum, 2, '.', ',') : "-"; $csv_b = $log_b_sum;?></td>
					<td class="right"><?php echo (!empty($log_c_sum)) ?  number_format($log_c_sum, 2, '.', ',') : "-"; $csv_c = $log_c_sum;?></td>
					<td class="right"><?php echo (!empty($log_d_sum)) ?  number_format($log_d_sum, 2, '.', ',') : "-"; $csv_d = $log_d_sum;?></td>
					<td class="right"><?php echo (!empty($log_e_sum)) ?  number_format($log_e_sum, 2, '.', ',') : "-"; $csv_e = $log_e_sum;?></td>
					<td class="right"><?php echo (!empty($log_f_sum)) ?  number_format($log_f_sum, 2, '.', ',') : "-"; $csv_f = $log_f_sum;?></td>
					<td class="right"><?php echo (!empty($log_g_sum)) ?  number_format($log_g_sum, 2, '.', ',') : "-"; $csv_g = $log_g_sum;?></td>
					<td class="right"><?php echo (!empty($log_h_sum)) ?  number_format($log_h_sum, 2, '.', ',') : "-"; $csv_h = $log_h_sum;?></td>
					<td class="right"><?php echo (!empty($log_i_sum)) ?  number_format($log_i_sum, 2, '.', ',') : "-"; $csv_i = $log_i_sum;?></td>
					<td class="right"><?php echo (!empty($overtime)) ?  number_format($overtime, 2, '.', ',') : "-"; $csv_overtime = $overtime;?></td>
				</tr>
			
<?php
				}
				include 'core/database/close.php' ;
				
				include 'core/database/connect.php' ;
				$query_log = $mysqli->query("SELECT SUM(log_late) as log_late_sum
											, SUM(log_undertime) as log_undertime_sum
											, SUM(log_a) as log_a_sum
											, SUM(log_b) as log_b_sum
											, SUM(log_c) as log_c_sum
											, SUM(log_d) as log_d_sum
											, SUM(log_e) as log_e_sum
											, SUM(log_f) as log_f_sum
											, SUM(log_g) as log_g_sum
											, SUM(log_h) as log_h_sum
											, SUM(log_i) as log_i_sum											
											FROM logs 
											WHERE log_period_code = '$period_code'");
				$row_log = $query_log->fetch_assoc();
				if($row_log) { // get data from db
					$log_late_sum = $row_log['log_late_sum'];
					$log_undertime_sum = $row_log['log_undertime_sum'];
					$log_a_sum = $row_log['log_a_sum'];
					$log_b_sum = $row_log['log_b_sum'];
					$log_c_sum = $row_log['log_c_sum'];
					$log_d_sum = $row_log['log_d_sum'];
					$log_e_sum = $row_log['log_e_sum'];
					$log_f_sum = $row_log['log_f_sum'];
					$log_g_sum = $row_log['log_g_sum'];
					$log_h_sum = $row_log['log_h_sum'];
					$log_i_sum = $row_log['log_i_sum'];
					$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum + $log_g_sum + $log_h_sum + $log_i_sum;
				}
?>
				<tr class="bg-color-lightblue">
					<td colspan="8" style='text-align:right;font-weight:bold;'>Total</td>
					<td class="right bold"><?php echo (isset($log_late_sum) && !empty($log_late_sum)) ?  number_format($log_late_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_undertime_sum) && !empty($log_undertime_sum)) ?  number_format($log_undertime_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_a_sum) && !empty($log_a_sum)) ?  number_format($log_a_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_b_sum) && !empty($log_b_sum)) ?  number_format($log_b_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_c_sum) && !empty($log_c_sum)) ?  number_format($log_c_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_d_sum) && !empty($log_d_sum)) ?  number_format($log_d_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_e_sum) && !empty($log_e_sum)) ?  number_format($log_e_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_f_sum) && !empty($log_f_sum)) ?  number_format($log_f_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_g_sum) && !empty($log_g_sum)) ?  number_format($log_g_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_h_sum) && !empty($log_h_sum)) ?  number_format($log_h_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($log_i_sum) && !empty($log_i_sum)) ?  number_format($log_i_sum, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (isset($overtime) && !empty($overtime)) ?  number_format($overtime, 2, '.', ',') : "-"; ?></td>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="20" class="left">
					
						<form action="exportlogs.php" name="exportlogs" method="post">
						<input type = "button" onclick="window.print();return false;"  value="Print" class="button_text">
						<input type="submit"  value="Generate" name ="generate"class="button_text">
						<input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr">					
						<input type="hidden" value="<?php echo $csv_periodcode; ?>" name="csv_periodcode">
						
						
						
							
							
						</form>
					</th>
				</tr>
			</tbody>
			<tfoot>
			
				
			</tfoot>
		</table>
	</div>
	<br />
		
<?php
	include 'includes/pagination_number_page.php' ; 
	include 'includes/overall/footer.php' ; 
?>