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
	
	if (isset($_POST['back'])) {
		header("Location: costing_clients_per_client.php?date_from=$date_from&date_to=$date_to&client_code=$client_code");
		exit();
	}
?>
<?php
			$csv_hdr = "#, Lastname, Firstname, Date, Regular, Rate, RC+OTC, Regular Cost, Overtime Cost, A, B, C, D, E, F, Description, \n";
			$csv_output="";
			$csv_clientcode = "";
			$csv_datefrom = "";
			$csv_dateto = "";
?>

		<h1><a href='<?php echo "costing_clients_per_client.php?date_from=$date_from&date_to=$date_to&client_code=$client_code"; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Client Costing per Client</h1>
		<h2><?php $csv_clientcode = client_name_from_code($client_code); echo $csv_clientcode;?></h2>
		<h3><?php $csv_datefrom = date("M-j-Y", strtotime($date_from)); $csv_dateto = date("M-j-Y", strtotime($date_to)); echo $csv_datefrom." To ".$csv_dateto; ?></h3>
<?php		
			
			if (empty($date_from) && empty($date_to)) {
				$date_sql = "";
			} else if (!empty($date_from) && !empty($date_to)) {
				$date_sql = "WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code='$client_code'";
			}		
?>

	<table class="print" >					
		<thead>
			<tr class="bg-color-dimgrey fg-color-white">						
				<th>#</th>
				<th class="center" style="min-width:200px;">Employee</th>
				<th class="center" style="min-width:80px;">Date</th>
				<th class="center" style="min-width:50px;">Regular</th>
				<th class="center" style="min-width:50px;">Rate</th>
				<th class="center" style="min-width:75px;">REG + OT Cost</th>
				<th class="center" style="min-width:75px;">Regular Cost</th>
				<th class="center" style="min-width:75px;">Overtime Cost</th>
				<th class="center" style="min-width:40px;">A</th>
				<th class="center" style="min-width:40px;">B</th>
				<th class="center" style="min-width:40px;">C</th>
				<th class="center" style="min-width:40px;">D</th>
				<th class="center" style="min-width:40px;">E</th>
				<th class="center" style="min-width:40px;">F</th>
				<th class="center" style="min-width:200px;">Description</th>
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query1 = $mysqli->query("SELECT DISTINCT logs.log_employee_id_no, employees.employee_id_no, employees.employee_last_name, logs.log_client_code 
		FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no 
		WHERE logs.log_date BETWEEN '$date_from' AND '$date_to' AND logs.log_client_code = '$client_code' 
		ORDER BY employees.employee_last_name");
		$grand_total =0;
		$overall_total =0;
		$overallot_total =0;
		while($rows1 = $query1->fetch_assoc()) {
			$employee_id_no1 = $rows1['employee_id_no'];	
			$query = $mysqli->query("SELECT logs.log_id, logs.log_employee_id_no, employees.employee_id_no, employees.employee_last_name, logs.log_client_code 
			FROM employees INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no 
			WHERE logs.log_employee_id_no = '$employee_id_no1' 
			AND logs.log_date BETWEEN '$date_from' AND '$date_to' 
			AND logs.log_client_code = '$client_code' 
			ORDER BY logs.log_date");
			$a = 1;
			$total_cost = 0;
			$gtotal_cost = 0;
			$ototal_cost = 0;
			while($rows = $query->fetch_assoc()) {
				$log_id = $rows['log_id'];	
				$employee_id_no = $rows['employee_id_no'];	
				$employee_name = last_name_from_id_no($rows['employee_id_no']) .  ", " . first_name_from_id_no($rows['employee_id_no']);	
				$query_log = $mysqli->query("SELECT log_position_code, log_regular, log_date, log_a, log_b, log_c, log_d, log_e, log_f, log_description 
				FROM logs WHERE log_id = '$log_id'");
				$row_log = $query_log->fetch_assoc();
				if($row_log) { // get data from db
					$log_position_code = $row_log['log_position_code'];
					$log_regular = $row_log['log_regular'];
					$empty = 0; 
					$notempty = $log_regular; 
					$position_rate = position_rate($log_position_code);
					$rcost = $log_regular * $position_rate;
					$log_a_c = 1.25 * $position_rate;
					$log_b_c = 1.69 * $position_rate;
					$log_c_c = 1.30 * $position_rate;
					$log_d_c = 1.375 * $position_rate;
					$log_e_c = 2.00 * $position_rate;
					$log_f_c = 1.85 * $position_rate;
					$log_date = $row_log['log_date'];
					$log_a_m = $row_log['log_a'];
					$log_b_m = $row_log['log_b'];
					$log_c_m = $row_log['log_c'];
					$log_d_m = $row_log['log_d'];
					$log_e_m = $row_log['log_e'];
					$log_f_m = $row_log['log_f'];
					$log_a = $row_log['log_a'] * $log_a_c;
					$log_b = $row_log['log_b'] * $log_b_c;
					$log_c = $row_log['log_c'] * $log_c_c;
					$log_d = $row_log['log_d'] * $log_d_c;
					$log_e = $row_log['log_e'] * $log_e_c;
					$log_f = $row_log['log_f'] * $log_f_c;
					$log_description = $row_log['log_description'];
					$otcost = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f;
					$tcost = $rcost + $otcost;
					$space = "";
				}
					
					
				 else {
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
					<td class="right"><?php echo $a; $csv_output .= $a. ", "; ?></td>
					<td><?php echo $employee_name; $csv_output .= $employee_name. ", ";?></td>
					<td class="center"><?php echo $log_date; $csv_output .= $log_date. ", " ;?></td>
					
					<td class="right"><?php if(empty($log_regular)){ 
													echo number_format($empty,2,'.', ','); 
													$log_reg_final = $empty;
												}
											else{
													echo number_format($log_regular,2,'.', ',');
													$log_reg_final = $log_regular;
												} 
												
											$csv_output .= $log_reg_final. ", "; ?></td>
												
					<td class="right"><?php echo number_format($position_rate, 2, '.', ',');$csv_output .= $position_rate. ", ";?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($tcost, 2, '.', ',') :number_format($tcost) ; $csv_output .= $tcost. ", "; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($rcost, 2, '.', ',') :number_format($rcost) ; $csv_output .= $rcost. ", "; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($otcost, 2, '.', ',') : number_format($otcost) ; $csv_output .= $otcost. ", "; ?></td>
					<td class="right"><?php echo (!empty($log_a)) ? number_format($log_a_m, 2, '.', ',') : "-"; $csv_output .= $log_a_m. ", "; ?></td>
					<td class="right"><?php echo (!empty($log_b)) ? number_format($log_b_m, 2, '.', ',') : "-"; $csv_output .= $log_b_m. ", ";?></td>
					<td class="right"><?php echo (!empty($log_c)) ? number_format($log_c_m, 2, '.', ',') : "-"; $csv_output .= $log_c_m. ", ";?></td>
					<td class="right"><?php echo (!empty($log_d)) ? number_format($log_d_m, 2, '.', ',') : "-"; $csv_output .= $log_d_m. ", ";?></td>
					<td class="right"><?php echo (!empty($log_e)) ? number_format($log_e_m, 2, '.', ',') : "-"; $csv_output .= $log_e_m. ", ";?></td>
					<td class="right"><?php echo (!empty($log_e)) ? number_format($log_f_m, 2, '.', ',') : "-"; $csv_output .= $log_f_m. ", ";?></td>
					<td><?php echo $log_description; $csv_output .= $log_description."\n";?> </td>
					
				</tr>					
<?php
				$a = $a + 1;
				$total_cost = $total_cost + $rcost;
				$gtotal_cost = $gtotal_cost + $tcost;
				$ototal_cost = $ototal_cost + $otcost;				
			}	
			$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum, SUM(log_a) as log_a_sum, SUM(log_b) as log_b_sum, SUM(log_c) as log_c_sum, SUM(log_d) as log_d_sum, SUM(log_e) as log_e_sum, SUM(log_f) as log_f_sum FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code = '$client_code'");
			$row_log = $query_log->fetch_assoc();
			if($row_log) { // get data from db
				$sub_total = "Sub-Total";
				$space = "";
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
				<td class="right bold"><?php echo $space; $csv_output .= $space. ", "; ?></td>
				<td class="right bold"><?php echo $sub_total; $csv_output .= $space. ", " ;?></td>
				<td class="right bold"><?php  $csv_output .= $sub_total. ", "; ?></td>
				<td class="right bold"><?php echo (!empty($sum_regular)) ?  number_format($sum_regular, 2, '.', ',') : "-"; $csv_output .= $space. ", "; ?></td>
				<td class="right bold"><?php $csv_output .= $sum_regular. ", " ;?></td>
				<td class="right bold"><?php $csv_output .= $space. ", "; echo (!empty($total_cost)) ?  number_format($gtotal_cost, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($total_cost, 2, '.', ',') : "-";$csv_output .= $gtotal_cost. ", " ; ?></td>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($ototal_cost, 2, '.', ',') : "-"; $csv_output .= $total_cost. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_a)) ?  number_format($sum_a, 2, '.', ',') : "-"; $csv_output .= $ototal_cost. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_b)) ?  number_format($sum_b, 2, '.', ',') : "-"; $csv_output .= $sum_a. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_c)) ?  number_format($sum_c, 2, '.', ',') : "-"; $csv_output .= $sum_b. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_d)) ?  number_format($sum_d, 2, '.', ',') : "-"; $csv_output .= $sum_c. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_e)) ?  number_format($sum_e, 2, '.', ',') : "-"; $csv_output .= $sum_d. ", " ;?></td>
				<td class="right bold"><?php echo (!empty($sum_e)) ?  number_format($sum_f, 2, '.', ',') : "-"; $csv_output .= $sum_e. ", " ;?></td>
				<td class="right bold"><?php  $csv_output .= $sum_f. ", " ."\n";?></td>
				
				
			</tr>
	
<?php
			$grand_total = $grand_total + $total_cost;
			$overall_total = $overall_total + $gtotal_cost;
			$overallot_total = $overallot_total + $ototal_cost;
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular, SUM(log_a) AS total_a, SUM(log_b) AS total_b, SUM(log_c) AS total_c, SUM(log_d) AS total_d, SUM(log_e) AS total_e, SUM(log_f) AS total_f FROM logs WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_client_code = '$client_code'");
		while($rows = $query->fetch_assoc()) {
			$sgrand_total = "Grand-Total";
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
				<td class="right bold"><?php echo $space; $csv_output .= $space. ", "; ?></td>
				<td class="right bold"><?php echo $sgrand_total; $csv_output .= $space. ", " ;?></td>
				<td class="right bold"><?php  $csv_output .= $sgrand_total. ", "; ?></td>
				<td class="right bold"><?php  echo (!empty($total_regular)) ?  number_format($total_regular, 2, '.', ',') : "-";$csv_output .= $space. ", "; ?></td>
				<td class="right bold"><?php $csv_output .= $total_regular. ", " ;?></td>
				<td class="right bold"><?php  $csv_output .= $space. ", ";echo (!empty($grand_total)) ?  number_format($overall_total, 2, '.', ',') : "-";?></td>
				<td class="right bold"><?php  echo (!empty($grand_total)) ?  number_format($grand_total, 2, '.', ',') : "-";$csv_output .= $overall_total. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($grand_total)) ?  number_format($overallot_total, 2, '.', ',') : "-";$csv_output .= $grand_total. ", ";?></td>
				
				<td class="right bold"><?php  echo (!empty($total_a)) ?  number_format($total_a, 2, '.', ',') : "-";$csv_output .= $overallot_total. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($total_b)) ?  number_format($total_b, 2, '.', ',') : "-";$csv_output .= $total_a. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($total_c)) ?  number_format($total_c, 2, '.', ',') : "-";$csv_output .= $total_b. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($total_d)) ?  number_format($total_d, 2, '.', ',') : "-";$csv_output .= $total_c. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($total_e)) ?  number_format($total_e, 2, '.', ',') : "-";$csv_output .= $total_d. ", ";?></td>
				<td class="right bold"><?php  echo (!empty($total_e)) ?  number_format($total_f, 2, '.', ',') : "-";$csv_output .= $total_e. ", ";?></td>
				<td class="right bold"><?php  $csv_output .= $total_f. ", "."\n";?></td>
				
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="15" class="left">
					<form action = "export2.php" name="export2" method="post" > 
						<input type = "button" onclick="window.print();return false;"  value="Print" class="button_text">
						<input type="submit"  value="Generate" name ="generate"class="button_text">
						<input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr">
						<input type="hidden" value="<?php echo $csv_output; ?>" name="csv_output">
						<input type="hidden" value="<?php echo $csv_clientcode; ?>" name="csv_clientcode">
						<input type="hidden" value="<?php echo $csv_datefrom; ?>" name="csv_datefrom">
						<input type="hidden" value="<?php echo $csv_dateto; ?>" name="csv_dateto">
						
					</form>
				</th>
			</tr>

		</tbody>
					
	</table>
	
				
			


<?php
	include 'includes/overall/footer.php' ; 
?>