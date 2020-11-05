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
	
	if (isset($_POST['back'])) {
		header("Location: costing_clients_parameter.php");
		exit();
	}
?>
	<h1><a href="costing_main.php#costing_clients_parameter" style="text-decoration:none;" title="Back">&#8656;</a> Client Costing</h1>
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
					<th colspan="15" class="left">
						<form action="crystalview.php" method="post">
							<button type="button" name="print" title="Print" class="button_text" onclick="window.print();return false;">Print</button>
							<input type="submit"  value="View" name ="view" class="button_text">
						</form>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">						
					<th></th>
					<th class="center">Client</th>
					<th class="center" style="min-width:50px;">Regular Hrs</th>
					<th class="center" style="min-width:50px;">Regular Cost</th>
					<th class="center" style="min-width:50px;">Overtime Cost</th>
					<th class="center" style="min-width:50px;">Total Cost</th>
					<th class="center" style="min-width:50px;">A</th>
					<th class="center" style="min-width:50px;">B</th>
					<th class="center" style="min-width:50px;">C</th>
					<th class="center" style="min-width:50px;">D</th>
					<th class="center" style="min-width:50px;">E</th>
					<th class="center" style="min-width:50px;">F</th>
					<th class="center" style="min-width:50px;">G</th>
					<th class="center" style="min-width:50px;">H</th>
					
				</tr>
			</thead>
			<tbody>			
<?php
			include 'core/database/connect.php' ;				
			$query = $mysqli->query("SELECT DISTINCT logs.log_client_code
									, clients.client_id
									, clients.client_code
									, clients.client_name 
									FROM clients 
									INNER JOIN logs 
									ON clients.client_code = logs.log_client_code 
									WHERE logs.log_date 
									BETWEEN '$date_from' AND '$date_to' 
									ORDER BY clients.client_name");
			$a = 1;
			while($rows = $query->fetch_assoc()) {
				$client_code = $rows['client_code'];	
				$client_name = $rows['client_name'];	
				$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum
											, log_position_code
											, SUM(log_a) as log_a_sum
											, SUM(log_b) as log_b_sum
											, SUM(log_c) as log_c_sum
											, SUM(log_d) as log_d_sum
											, SUM(log_e) as log_e_sum
											, SUM(log_f) as log_f_sum
											, SUM(log_g) as log_g_sum
											, SUM(log_h) as log_h_sum										
											FROM logs 
											WHERE log_client_code = '$client_code' 
											AND log_date 
											BETWEEN '$date_from' AND '$date_to'");
				$row_log = $query_log->fetch_assoc();
				if($row_log) { // get data from db
					$log_position_code = $row_log['log_position_code'];
					$sum_regular = $row_log['log_regular_sum'];
					$empty = 0; 
					$notempty = $sum_regular;
					$position_rate = position_rate($log_position_code);
					$rcost = 0;
					$sum_a = $row_log['log_a_sum'];
					$sum_b = $row_log['log_b_sum'];
					$sum_c = $row_log['log_c_sum'];
					$sum_d = $row_log['log_d_sum'];
					$sum_e = $row_log['log_e_sum'];
					$sum_f = $row_log['log_f_sum'];
					$sum_g = $row_log['log_g_sum'];
					$sum_h = $row_log['log_h_sum'];
					$otcost = 0;
					$tcost = 0;
				} else {
					$sum_regular = "";
					$sum_a = "";
					$sum_b = "";
					$sum_c = "";
					$sum_d = "";
					$sum_e = "";
					$sum_f = "";
					$sum_g = "";
					$sum_h = "";
				}
				
?>
				<tr class="alternate">
					<td class="right"><?php echo $a; ?></td>
					<td><a href="costing_clients_per_client.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&client_code=<?php echo $client_code; ?>"><?php echo $client_name; ?></a></td>
					<td class="right"><?php echo (!empty($sum_regular)) ? number_format($sum_regular, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_regular)) ? number_format($rcost, 2, '.', ',') : "-"; ?></td>
					<!--<td class="right"><?php echo $_SESSION["grand_total"];?></td>-->
					<td class="right"><?php echo (!empty($sum_regular)) ? number_format($otcost, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_regular)) ? number_format($tcost, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_a)) ? number_format($sum_a, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_b)) ? number_format($sum_b, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_c)) ? number_format($sum_c, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_d)) ? number_format($sum_d, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_e)) ? number_format($sum_e, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_f)) ? number_format($sum_f, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_g)) ? number_format($sum_g, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($sum_h)) ? number_format($sum_h, 2, '.', ',') : "-"; ?></td>
					
				</tr>					
<?php
				$a = $a + 1;
			}
			include 'core/database/close.php' ;
			
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular
									, SUM(log_a) AS total_a
									, SUM(log_b) AS total_b
									, SUM(log_c) AS total_c
									, SUM(log_d) AS total_d
									, SUM(log_e) AS total_e
									, SUM(log_f) AS total_f
									, SUM(log_g) AS total_g
									, SUM(log_h) AS total_h 
									FROM logs $date_sql");
			while($rows = $query->fetch_assoc()) {
				$total_regular = $rows['total_regular'];
				$rcostgtotal = 0;
				$otcostgtotal = 0;
				$overallgtotal = 0;
				$total_a = $rows['total_a'];
				$total_b = $rows['total_b'];
				$total_c = $rows['total_c'];
				$total_d = $rows['total_d'];
				$total_e = $rows['total_e'];
				$total_f = $rows['total_f'];
				$total_g = $rows['total_g'];
				$total_h = $rows['total_h'];
			}
			include 'core/database/close.php' ;
?>
				<tr class="bg-color-lightblue">
					<td class="right bold" colspan="2">Total</td>
					<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($total_regular, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($rcostgtotal, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($otcostgtotal, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_regular)) ?  number_format($overallgtotal, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_a)) ?  number_format($total_a, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_b)) ?  number_format($total_b, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_c)) ?  number_format($total_c, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_d)) ?  number_format($total_d, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_e)) ?  number_format($total_e, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_f)) ?  number_format($total_f, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_g)) ?  number_format($total_g, 2, '.', ',') : "-"; ?></td>
					<td class="right bold"><?php echo (!empty($total_h)) ?  number_format($total_h, 2, '.', ',') : "-"; ?></td>
					
				</tr>
			</tbody>
		</table>
	</div>
<?php
	include 'includes/overall/footer.php' ; 
?>