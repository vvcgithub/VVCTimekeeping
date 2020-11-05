<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_costing($employee_data['employee_id']) != 6) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$date_from = date("Y-m-d", strtotime($date_from));
		$date_to = date("Y-m-d", strtotime($date_to));
	}
	
?>
	<h1><a href="costing_main.php#costing_clients_parameter_post" style="text-decoration:none;" title="Back">&#8656;</a> Client Costing Post</h1>
	<h3><?php echo date("M-j-Y", strtotime($date_from)) . " to " . date("M-j-Y", strtotime($date_to)); ?></h3>
<?php			
	if (empty($date_from) && empty($date_to)) {
		$date_sql = "";
	} else if (!empty($date_from) && !empty($date_to)) {
		$date_sql = "WHERE log_date BETWEEN '$date_from' AND '$date_to'";
	}					
?>	
	<div style="overflow:auto;width:100%;height:100%;">
		<table class="table_list print">					
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="8" class="left">
						<form action="" method="post">
							<button type="button" name="print" title="Print" class="button_text" onclick="window.print();return false;">Print</button>
						</form>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">						
					<th></th>
					<th class="center">Client</th>
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
			$query = $mysqli->query("SELECT DISTINCT logs_post.log_client_code, clients.client_id, clients.client_code, clients.client_name FROM clients INNER JOIN logs_post ON clients.client_code = logs_post.log_client_code WHERE logs_post.log_date BETWEEN '$date_from' AND '$date_to' ORDER BY clients.client_code");
			$a = 1;
			while($rows = $query->fetch_assoc()) {
				$client_code = $rows['client_code'];	
				$client_name = $rows['client_name'];	
				$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum FROM logs_post WHERE log_client_code = '$client_code' AND log_date BETWEEN '$date_from' AND '$date_to'");
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
					<td><a href="costing_clients_per_client_post.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&client_code=<?php echo $client_code; ?>"><?php echo $client_name; ?></a></td>
					<td class="right"><?php echo (!empty($sum_regular)) ? number_format($sum_regular, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_a)) ? number_format($sum_a, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_b)) ? number_format($sum_b, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_c)) ? number_format($sum_c, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_d)) ? number_format($sum_d, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_e)) ? number_format($sum_e, 2, '.', ',') : "-"; ?></td>
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
	</div>
<?php
	include 'includes/overall/footer.php' ; 
?>