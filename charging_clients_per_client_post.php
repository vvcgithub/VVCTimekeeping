<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['date_from']) && isset($_GET['date_to']) && isset($_GET['client_code'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$client_code = $_GET['client_code'];
		$date_from = date("Y-m-d", strtotime($date_from));
		$date_to = date("Y-m-d", strtotime($date_to));
	}
	
	if (isset($_POST['detail'])) {
	header("Location: charging_clients_per_client_detail_post.php?date_from=$date_from&date_to=$date_to&client_code=$client_code");
	}
	
	if (isset($_POST['back'])) {
		header("Location: charging_clients_post.php?date_from=$date_from&date_to=$date_to");
		exit();
	}
?>
		<h1><a href='<?php echo "charging_clients_post.php?date_from=$date_from&date_to=$date_to"; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Client Charging per Client Post</h1>
		<h2><?php echo client_name_from_code($client_code); ?></h2>
		<h3><?php echo date("M-j-Y", strtotime($date_from)) . " to " . date("M-j-Y", strtotime($date_to)); ?></h3>
<?php		
			
			if (empty($date_from) && empty($date_to)) {
				$date_sql = "";
			} else if (!empty($date_from) && !empty($date_to)) {
				$date_sql = "WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code='$client_code'";
			}		
?>									
	<table class="table_list print">					
		<thead>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="9" class="left">
					<form action="" method="post">
						<button type="button" name="print" title="Print" class="button_text" onclick="window.print();return false;">Print</button>
						<button type="submit" name="detail" title="Detail" class="button_text">Detail</button>
					</form>
				</th>
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">						
				<th></th>
				<th class="center">Employee</th>
				<th class="center" style="min-width:50px;">Regular</th>
				<th class="center" style="min-width:50px;">A</th>
				<th class="center" style="min-width:50px;">B</th>
				<th class="center" style="min-width:50px;">C</th>
				<th class="center" style="min-width:50px;">D</th>
				<th class="center" style="min-width:50px;">E</th>
				<th class="center" style="min-width:50px;">F</th>
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT logs_post.log_employee_id_no, employees.employee_id_no, employees.employee_last_name, logs_post.log_client_code FROM employees INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no WHERE logs_post.log_date BETWEEN '$date_from' AND '$date_to' AND logs_post.log_client_code = '$client_code' GROUP BY logs_post.log_employee_id_no ORDER BY employees.employee_last_name");
		$a = 1;
		while($rows = $query->fetch_assoc()) {
			$employee_id_no = $rows['employee_id_no'];	
			$employee_name = complete_name_from_id_no($rows['employee_id_no']);	
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
			<tr class="alternate">
				<td class="right"><?php echo $a; ?></td>
				<td><?php echo $employee_name; ?></td>
				<td class="right"><?php echo (!empty($sum_regular)) ? number_format($sum_regular, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_a)) ? number_format($sum_a, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_b)) ? number_format($sum_b, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_c)) ? number_format($sum_c, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_d)) ? number_format($sum_d, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_e)) ? number_format($sum_e, 2, '.', ',') : "-"; ?></td>
				<td class="right"><?php echo (!empty($sum_f)) ? number_format($sum_f, 2, '.', ',') : "-"; ?></td>
			</tr>					
<?php
			$a = $a + 1;
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular, SUM(log_a) AS total_a, SUM(log_b) AS total_b, SUM(log_c) AS total_c, SUM(log_d) AS total_d, SUM(log_e) AS total_e, SUM(log_f) AS total_f FROM logs_post $date_sql");
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
				<td class="right bold"><?php echo (!empty($total_f)) ?  number_format($total_f, 2, '.', ',') : "-"; ?></td>
			</tr>
		</tbody>
	</table>
<?php
	include 'includes/overall/footer.php' ; 
?>