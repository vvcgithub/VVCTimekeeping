<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$date_from = date("Y-m-d", strtotime($date_from));
		$date_to = date("Y-m-d", strtotime($date_to));
	}
	
	if (isset($_POST['back'])) {
		header("Location: charging_clients_parameter.php");
		exit();
	}
?>
	<h1><a href="charging_main.php#charging_employees_parameter" style="text-decoration:none;" title="Back">&#8656;</a> Employee Charging</h1>
	<h3><?php echo date("M-j-Y", strtotime($date_from)) . " to " . date("M-j-Y", strtotime($date_to)); ?></h3>
<?php			
	if (empty($date_from) && empty($date_to)) {
		$date_sql = "";
	} else if (!empty($date_from) && !empty($date_to)) {
		$date_sql = "WHERE logs.log_date BETWEEN '$date_from' AND '$date_to'";
	}					
?>								
	<table class="table_list print">					
		<thead>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="9" class="left white_border">
					<form action="" method="post">
						<input type="button" onclick="window.print();return false;"  value="Print" class="button_text" />
					</form>
				</th>
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">						
				<th></th>
				<th class="center">Employees</th>
				<th class="center" style="min-width:50px;">Regular</th>
				<th class="center" style="min-width:50px;">A</th>
				<th class="center" style="min-width:50px;">B</th>
				<th class="center" style="min-width:50px;">C</th>
				<th class="center" style="min-width:50px;">D</th>
				<th class="center" style="min-width:50px;">E</th>
			
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT logs.log_employee_id_no, logs.log_period_code, employees.employee_id_no, employees.employee_last_name FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no WHERE logs.log_date BETWEEN '$date_from' AND '$date_to' GROUP BY logs.log_employee_id_no ORDER BY employees.employee_last_name");
		$a = 1;	
		while($rows = $query->fetch_assoc()) {
			$employee_id_no = $rows['employee_id_no'];	
			
			$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum, SUM(log_late) as log_late_sum, SUM(log_undertime) as log_undertime_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum FROM logs WHERE log_employee_id_no = $employee_id_no AND log_date BETWEEN '$date_from' AND '$date_to'");
			$row_log = $query_log->fetch_assoc();
			if($row_log) { // get data from db
				$log_regular_sum = $row_log['log_regular_sum'];
				$log_late_sum = $row_log['log_late_sum'] / 60;
				$log_undertime_sum = $row_log['log_undertime_sum'];
				$log_a_sum = $row_log['log_a_sum'];
				$log_b_sum = $row_log['log_b_sum'];
				$log_c_sum = $row_log['log_c_sum'];
				$log_d_sum = $row_log['log_d_sum'];
				$log_e_sum = $row_log['log_e_sum'];
				$log_f_sum = $row_log['log_f_sum'];
				$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum;
			} else {
				$log_late_sum = "";
				$log_undertime_sum = "";
				$log_a_sum = "";
				$log_b_sum = "";
				$log_c_sum = "";
				$log_d_sum = "";
				$log_e_sum = "";
				$log_f_sum = "";
				$overtime = $log_a_sum + $log_b_sum + $log_c_sum + $log_d_sum + $log_e_sum + $log_f_sum;
			}

			
?>
			<tr class="alternate">
				<td class="right"><?php echo $a; ?></td>
				<td><a href="charging_employees_per_employee.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&employee_id_no=<?php echo $employee_id_no; ?>"><?php echo complete_name_from_id_no($employee_id_no); ?></a></td>
				<td class="right"><?php echo (!empty($log_regular_sum)) ? number_format($log_regular_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($log_a_sum)) ? number_format($log_a_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($log_b_sum)) ? number_format($log_b_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($log_c_sum)) ? number_format($log_c_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($log_d_sum)) ? number_format($log_d_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($log_e_sum)) ? number_format($log_e_sum, 2, '.', ',') : "-"; ?></td>
			
			</tr>					
<?php
			$a = $a + 1;
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular, SUM(log_a) AS total_a, SUM(log_b) AS total_b, SUM(log_c) AS total_c, SUM(log_d) AS total_d, SUM(log_e) AS total_e, SUM(log_f) AS total_f FROM logs $date_sql");
		while($rows = $query->fetch_assoc()) {
			$total_regular = $rows['total_regular'];
			$total_a = $rows['total_a'];
			$total_b = $rows['total_b'];
			$total_c = $rows['total_c'];
			$total_d = $rows['total_d'];
			$total_e = $rows['total_e'];
			$total_f = $rows['total_f'];
		}
		include 'core/database/close.php' ;
?>
			<tr class="bg-color-lightblue">
				<td class="right bold" colspan="2">Total</td>
				<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($total_regular, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_a)) ?  number_format($total_a, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_b)) ?  number_format($total_b, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_c)) ?  number_format($total_c, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_d)) ?  number_format($total_d, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_e)) ?  number_format($total_e, 2, '.', ',') : "-"; ?></td>
		
			</tr>
		</tbody>
	</table>
<?php
	include 'includes/overall/footer.php' ; 
?>