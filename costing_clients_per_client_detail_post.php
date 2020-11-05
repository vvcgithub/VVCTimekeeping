<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_costing($employee_data['employee_id']) != 6) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['date_from']) && isset($_GET['date_to']) && isset($_GET['client_code'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$client_code = $_GET['client_code'];
		$date_from = date("Y-m-d", strtotime($date_from));
		$date_to = date("Y-m-d", strtotime($date_to));
	}
?>
		<h1><a href='<?php echo "costing_clients_per_client_post.php?date_from=$date_from&date_to=$date_to&client_code=$client_code"; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Client Costing per Client Post</h1>
		<h2><?php echo client_name_from_code($client_code); ?></h2>
		<h3><?php echo date("M-j-Y", strtotime($date_from)) . " to " . date("M-j-Y", strtotime($date_to)); ?></h3>
<?php		
			
			if (empty($date_from) && empty($date_to)) {
				$date_sql = "";
			} else if (!empty($date_from) && !empty($date_to)) {
				$date_sql = "WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code='$client_code'";
			}		
?>									
	<table class="print">					
		<thead>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="11" class="left">
					<form action="" method="post">
						<input type="button" onclick="window.print();return false;"  value="Print" class="button_text" />
					</form>
				</th>
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">						
				<th></th>
				<th class="center" style="min-width:200px;">Employee</th>
				<th class="center" style="min-width:80px;">Date</th>
				<th class="center" style="min-width:50px;">Regular</th>
				<th class="center" style="min-width:50px;">Rate</th>
				<th class="center" style="min-width:50px;">Cost</th>
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
		$query1 = $mysqli->query("SELECT DISTINCT logs_post.log_employee_id_no, employees.employee_id_no, employees.employee_last_name, logs_post.log_client_code FROM employees INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no WHERE logs_post.log_date BETWEEN '$date_from' AND '$date_to' AND logs_post.log_client_code = '$client_code' ORDER BY employees.employee_last_name");
		$grand_total =0;
		while($rows1 = $query1->fetch_assoc()) {
			$employee_id_no1 = $rows1['employee_id_no'];	
			$query = $mysqli->query("SELECT logs_post.log_post_id, logs_post.log_employee_id_no, employees.employee_id_no, employees.employee_last_name, logs_post.log_client_code FROM employees INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no WHERE logs_post.log_employee_id_no = '$employee_id_no1' AND logs_post.log_date BETWEEN '$date_from' AND '$date_to' AND logs_post.log_client_code = '$client_code' ORDER BY logs_post.log_date");
			$a = 1;
			$total_cost = 0;
			while($rows = $query->fetch_assoc()) {
				$log_post_id = $rows['log_post_id'];	
				$employee_id_no = $rows['employee_id_no'];	
				$employee_name = last_name_from_id_no($rows['employee_id_no']) .  ", " . first_name_from_id_no($rows['employee_id_no']);	
				$query_log = $mysqli->query("SELECT log_position_code, log_regular, log_date, log_a, log_b, log_c, log_d, log_e, log_f, log_description FROM logs_post WHERE log_post_id = '$log_post_id'");
				$row_log = $query_log->fetch_assoc();
				if($row_log) { // get data from db
					$log_position_code = $row_log['log_position_code'];
					$log_regular = $row_log['log_regular'];
					$position_rate = position_rate($log_position_code);
					$cost = $log_regular * $position_rate;
					$log_date = $row_log['log_date'];
					$log_a = $row_log['log_a'];
					$log_b = $row_log['log_b'];
					$log_c = $row_log['log_c'];
					$log_d = $row_log['log_d'];
					$log_e = $row_log['log_e'];
					$log_f = $row_log['log_f'];
					$log_description = $row_log['log_description'];
				} else {
					$log_position_code = "";
					$log_regular = "";
					$log_a = "";
					$log_b = "";
					$log_c = "";
					$log_d = "";
					$log_e = "";
					$log_f = "";
				}			
?>
				<tr>
					<td class="right"><?php echo $a; ?></td>
					<td><?php echo $employee_name; ?></td>
					<td class="center"><?php echo $log_date; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($log_regular, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($position_rate, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($cost, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_a)) ? number_format($log_a, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_b)) ? number_format($log_b, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_c)) ? number_format($log_c, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_d)) ? number_format($log_d, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_e)) ? number_format($log_e, 2, '.', ',') : "-"; ?></td>
				</tr>					
<?php
				$a = $a + 1;
				$total_cost = $total_cost + $cost;	
			}	
			$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum FROM logs_post WHERE log_employee_id_no = '$employee_id_no' AND log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code = '$client_code'");
			$row_log = $query_log->fetch_assoc();
			if($row_log) { // get data from db
				$sum_regular = $row_log['log_regular_sum'];
				$sum_a = $row_log['log_a_sum'];
				$sum_b = $row_log['log_b_sum'];
				$sum_c = $row_log['log_c_sum'];
				$sum_d = $row_log['log_d_sum'];
				$sum_e = $row_log['log_e_sum'];
				$sum_f = $row_log['log_f_sum'];
			} else {
				$sum_regular = "";
				$sum_a = "";
				$sum_b = "";
				$sum_c = "";
				$sum_d = "";
				$sum_e = "";
				$sum_f = "";
			}			
?>
			<tr class="bg-color-lightgreen">
				<td class="right bold" colspan="3">Sub-Total</td>
				<td class="right bold"><?php echo (!empty($sum_regular)) ?  number_format($sum_regular, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"></td>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($total_cost, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($sum_a)) ?  number_format($sum_a, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($sum_b)) ?  number_format($sum_b, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($sum_c)) ?  number_format($sum_c, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($sum_d)) ?  number_format($sum_d, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($sum_e)) ?  number_format($sum_e, 2, '.', ',') : "-"; ?></td>
			</tr>
	
<?php
			$grand_total = $grand_total + $total_cost;
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular, SUM(log_a) AS total_a, SUM(log_b) AS total_b, SUM(log_c) AS total_c, SUM(log_d) AS total_d, SUM(log_e) AS total_e, SUM(log_f) AS total_f FROM logs_post WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code = '$client_code'");
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
				<td class="right bold" colspan="3">Grand Total</td>
				<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($total_regular, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"></td>
				<td class="right bold"><?php echo (!empty($grand_total)) ?  number_format($grand_total, 2, '.', ',') : "-"; ?></td>
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