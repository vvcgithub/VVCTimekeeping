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
			$csv_output = array();
			$csv_output[] = '';
			$csv_hdr = "#, Lastname, Firstname, Date, Regular, Rate, RC+OTC, Regular Cost, Overtime Cost, A, B, C, D, E, F, G, H, Description, \n";
		
		
			$csv_clientcode = "";
			$csv_datefrom = "";
			$csv_dateto = "";
			
			$csv_emp = array();
			$csv_emp[] ='';
			$csv_id = array();
			$csv_id[] ='';
			$csv_date= array();
			$csv_date[] ='';
			$csv_Regular = array();
			$csv_Regular[] ='';
			$csv_Rate = array();
			$csv_Rate[] ='';
			$csv_RCOTC = array();
			$csv_RCOTC[] ='';
			$csv_RC = array();
			$csv_RC[] ='';
			$csv_OTC = array();
			$csv_OTC[] ='';
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
			$csv_desc = array();
			$csv_desc[] ='';
			
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
				<th class="center" style="min-width:30px;">A</th>
				<th class="center" style="min-width:30px;">B</th>
				<th class="center" style="min-width:30px;">C</th>
				<th class="center" style="min-width:30px;">D</th>
				<th class="center" style="min-width:30px;">E</th>
				<th class="center" style="min-width:30px;">F</th>
				<th class="center" style="min-width:30px;">G</th>
				<th class="center" style="min-width:30px;">H</th>				
				<th class="center" style="min-width:200px;">Description</th>
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query1 = $mysqli->query("SELECT DISTINCT logs.log_employee_id_no
								, employees.employee_id_no
								, employees.employee_last_name
								, logs.log_client_code 
								FROM employees 
								INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no 
								WHERE logs.log_date 
								BETWEEN '$date_from' AND '$date_to' 
								AND logs.log_client_code = '$client_code' 
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
				$query_log = $mysqli->query("SELECT log_position_code, log_regular, log_date, log_a, log_b, log_c, log_d, log_e, log_f, log_g, log_h, log_description 
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
					$log_e_c = 1.00 * $position_rate;
					$log_f_c = 2.65 * $position_rate;
					$log_g_c = 3.38 * $position_rate;
					$log_h_c = 1.60 * $position_rate;
					$log_date = $row_log['log_date'];
					$log_a_m = $row_log['log_a'];
					$log_b_m = $row_log['log_b'];
					$log_c_m = $row_log['log_c'];
					$log_d_m = $row_log['log_d'];
					$log_e_m = $row_log['log_e'];
					$log_f_m = $row_log['log_f'];
					$log_g_m = $row_log['log_g'];
					$log_h_m = $row_log['log_h'];
					$log_a = $row_log['log_a'] * $log_a_c;
					$log_b = $row_log['log_b'] * $log_b_c;
					$log_c = $row_log['log_c'] * $log_c_c;
					$log_d = $row_log['log_d'] * $log_d_c;
					$log_e = $row_log['log_e'] * $log_e_c;
					$log_f = $row_log['log_f'] * $log_f_c;
					$log_g = $row_log['log_g'] * $log_g_c;
					$log_h = $row_log['log_h'] * $log_h_c;
					$log_description = $row_log['log_description'];
					$otcost = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f + $log_g + $log_h;
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
					$log_g = "";
					$log_h = "";
				}				
				
?>
				<tr>
				
					<td class="right"><?php echo $a; $csv_id[] = $a; ?></td>
					<td><?php echo $employee_name; $csv_emp[] = $employee_name ;?></td>
					<td class="center"><?php echo $log_date; $csv_date[] = $log_date ;?></td>
					
					<td class="right"><?php if(empty($log_regular)){ 
													echo number_format($empty,2); 
													$log_reg_final = $empty;
												}
											else{
													echo number_format($log_regular,2);
													$log_reg_final = $log_regular;
												} 
												
											$csv_Regular[] = $log_reg_final; ?></td>
												
					<td class="right"><?php echo number_format($position_rate, 2, '.', ',');$csv_Rate[] = $position_rate;?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($tcost, 2, '.', ',') :number_format($tcost) ; $csv_RCOTC[] = $tcost; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($rcost, 2, '.', ',') :number_format($rcost) ; $csv_RC[] = $rcost; ?></td>
					<td class="right"><?php echo (!empty($log_regular)) ? number_format($otcost, 2, '.', ',') : number_format($otcost) ; $csv_OTC[] = $otcost; ?></td>
					<td class="right"><?php echo (!empty($log_a)) ? number_format($log_a_m, 2, '.', ',') : "-"; $csv_a[] = $log_a_m ; ?></td>
					<td class="right"><?php echo (!empty($log_b)) ? number_format($log_b_m, 2, '.', ',') : "-"; $csv_b[] = $log_b_m ;?></td>
					<td class="right"><?php echo (!empty($log_c)) ? number_format($log_c_m, 2, '.', ',') : "-"; $csv_c[] = $log_c_m ;?></td>
					<td class="right"><?php echo (!empty($log_d)) ? number_format($log_d_m, 2, '.', ',') : "-"; $csv_d[] = $log_d_m ;?></td>
					<td class="right"><?php echo (!empty($log_e)) ? number_format($log_e_m, 2, '.', ',') : "-"; $csv_e[] = $log_e_m ;?></td>
					<td class="right"><?php echo (!empty($log_f)) ? number_format($log_f_m, 2, '.', ',') : "-"; $csv_f[] = $log_f_m ;?></td>
					<td class="right"><?php echo (!empty($log_g)) ? number_format($log_g_m, 2, '.', ',') : "-"; $csv_g[] = $log_g_m ;?></td>
					<td class="right"><?php echo (!empty($log_h)) ? number_format($log_h_m, 2, '.', ',') : "-"; $csv_h[] = $log_h_m ;?></td>
					
					<td><?php echo $log_description ;?></td>
					<?php $csv_desc[] = $log_description."\n";?>
					
				</tr>					
<?php
				$a = $a + 1;
				$total_cost = $total_cost + $rcost;
				$gtotal_cost = $gtotal_cost + $tcost;
				$ototal_cost = $ototal_cost + $otcost;				
			}	
			$query_log = $mysqli->query("SELECT SUM(log_regular) as log_regular_sum
										, SUM(log_a) as log_a_sum
										, SUM(log_b) as log_b_sum
										, SUM(log_c) as log_c_sum
										, SUM(log_d) as log_d_sum
										, SUM(log_e) as log_e_sum
										, SUM(log_f) as log_f_sum
										, SUM(log_g) as log_g_sum
										, SUM(log_h) as log_h_sum										
										FROM logs 
										WHERE log_employee_id_no = '$employee_id_no' AND log_date 
										BETWEEN '$date_from' AND '$date_to' 
										AND log_client_code = '$client_code'");
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
				$sum_g = $row_log['log_g_sum'];
				$sum_h = $row_log['log_h_sum'];
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
			<tr class="bg-color-lightgreen">
				<td class="right bold"><?php echo $space; $csv_id[] = $space;  ?></td>
				<td class="right bold"><?php echo $sub_total;?></td>
				<td class="right bold"><?php  $csv_emp[] = $sub_total; ?></td>
				<td class="right bold"><?php echo (!empty($sum_regular)) ?  number_format($sum_regular, 2, '.', ',') : "-";  ?></td>
				<?php  $csv_date[] = $space; ?>
				<td class="right bold"><?php $csv_Regular[] = $sum_regular ;?></td>
				<?php  $csv_Rate[] = $space; ?>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($gtotal_cost, 2, '.', ',') : "-";$csv_RCOTC[] = $gtotal_cost  ; ?></td>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($total_cost, 2, '.', ',') : "-";$csv_RC[] = $total_cost  ; ?></td>
				<td class="right bold"><?php echo (!empty($total_cost)) ?  number_format($ototal_cost, 2, '.', ',') : "-";$csv_OTC[] = $ototal_cost  ; ?></td>
				<td class="right bold"><?php echo (!empty($sum_a)) ?  number_format($sum_a, 2, '.', ',') : "-"; $csv_a[] = $sum_a  ; ?></td>
				<td class="right bold"><?php echo (!empty($sum_b)) ?  number_format($sum_b, 2, '.', ',') : "-"; $csv_b[] = $sum_b  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_c)) ?  number_format($sum_c, 2, '.', ',') : "-"; $csv_c[] = $sum_c  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_d)) ?  number_format($sum_d, 2, '.', ',') : "-"; $csv_d[] = $sum_d  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_e)) ?  number_format($sum_e, 2, '.', ',') : "-"; $csv_e[] = $sum_e  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_f)) ?  number_format($sum_f, 2, '.', ',') : "-"; $csv_f[] = $sum_f  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_g)) ?  number_format($sum_g, 2, '.', ',') : "-"; $csv_g[] = $sum_g  ;?></td>
				<td class="right bold"><?php echo (!empty($sum_h)) ?  number_format($sum_h, 2, '.', ',') : "-"; $csv_h[] = $sum_h ."\n";?></td>
				<td class="right bold"><?php echo $space; $csv_desc[] = $space;?></td>
				
				
			</tr>
	
<?php
			$grand_total = $grand_total + $total_cost;
			$overall_total = $overall_total + $gtotal_cost;
			$overallot_total = $overallot_total + $ototal_cost;
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
								FROM logs WHERE log_date 
								BETWEEN '$date_from' AND '$date_to' 
								AND log_client_code = '$client_code'");
		while($rows = $query->fetch_assoc()) {
			$sgrand_total = "Grand-Total";
			$total_regular = $rows['total_regular'];
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
				<td class="right bold"><?php echo $space;$csv_id[] = $space;?></td>
				<td class="right bold"><?php echo $sgrand_total;?></td>
				<td class="right bold"><?php echo $space;?></td>
				<?php $csv_emp[] = $sgrand_total; ?>
				<td class="right bold"><?php  echo (!empty($total_regular)) ?  number_format($total_regular, 2, '.', ',') : "-";$csv_Regular[] = $total_regular  ; ?></td>
				<td class="right bold"><?php echo $space;?></td>
				<td class="right bold"><?php  echo (!empty($grand_total)) ?  number_format($overall_total, 2, '.', ',') : "-";$csv_RCOTC[] = $overall_total;?></td>
				<td class="right bold"><?php  echo (!empty($grand_total)) ?  number_format($grand_total, 2, '.', ',') : "-";$csv_RC[] = $grand_total;?></td>
				<td class="right bold"><?php  echo (!empty($grand_total)) ?  number_format($overallot_total, 2, '.', ',') : "-";$csv_OTC[] .= $overallot_total ;?></td>
				<td class="right bold"><?php  echo (!empty($total_a)) ?  number_format($total_a, 2, '.', ',') : "-";$csv_a[] = $total_a ;?></td>
				<td class="right bold"><?php  echo (!empty($total_b)) ?  number_format($total_b, 2, '.', ',') : "-";$csv_b[] = $total_b ;?></td>
				<td class="right bold"><?php  echo (!empty($total_c)) ?  number_format($total_c, 2, '.', ',') : "-";$csv_c[] = $total_c;?></td>
				<td class="right bold"><?php  echo (!empty($total_d)) ?  number_format($total_d, 2, '.', ',') : "-";$csv_d[] = $total_d ;?></td>
				<td class="right bold"><?php  echo (!empty($total_e)) ?  number_format($total_e, 2, '.', ',') : "-";$csv_e[] = $total_e ;?></td>
				<td class="right bold"><?php  echo (!empty($total_f)) ?  number_format($total_f, 2, '.', ',') : "-";$csv_f[] = $total_f ;?></td>
				<td class="right bold"><?php  echo (!empty($total_g)) ?  number_format($total_g, 2, '.', ',') : "-";$csv_g[] = $total_g ;?></td>
				<td class="right bold"><?php  echo (!empty($total_h)) ?  number_format($total_h, 2, '.', ',') : "-";$csv_h[] = $total_h ."\n";?></td>
				
				<td class="right bold"></td>
				
				
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="18" class="left">
					<form action = "export2.php" name="export2" method="post" > 
						<input type = "button" onclick="window.print();return false;"  value="Print" class="button_text">
						<input type="submit"  value="Generate" name ="generate"class="button_text">
						<input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr">					
						<input type="hidden" value="<?php echo $csv_clientcode; ?>" name="csv_clientcode">
						<input type="hidden" value="<?php echo $csv_datefrom; ?>" name="csv_datefrom">
						<input type="hidden" value="<?php echo $csv_dateto; ?>" name="csv_dateto">
						
						<?php
							$ctr = 0;
							foreach($csv_emp as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_emp[$ctr];?>" name = "csv_emp[<?php echo $ctr; ?>]">
								
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_id as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_id[$ctr];?>" name = "csv_id[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							
							<?php
							$ctr = 0;
							foreach($csv_date as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_date[$ctr];?>" name = "csv_date[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							
							<?php
							$ctr = 0;
							foreach($csv_Regular as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_Regular[$ctr];?>" name = "csv_regular[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_Rate as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_Rate[$ctr];?>" name = "csv_rate[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_RCOTC as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_RCOTC[$ctr];?>" name = "csv_rcotc[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							
							<?php
							$ctr = 0;
							foreach($csv_RC as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_RC[$ctr];?>" name = "csv_rc[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr =0;
							foreach($csv_OTC as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_OTC[$ctr];?>" name = "csv_otc[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_a as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_a[$ctr];?>" name = "csv_a[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_b as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_b[$ctr];?>" name = "csv_b[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_c as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_c[$ctr];?>" name = "csv_c[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_d as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_d[$ctr];?>" name = "csv_d[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_e as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_e[$ctr];?>" name = "csv_e[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_f as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_f[$ctr];?>" name = "csv_f[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_g as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_g[$ctr];?>" name = "csv_g[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_h as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_h[$ctr];?>" name = "csv_h[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
							<?php
							$ctr = 0;
							foreach($csv_desc as $value){ 
								if($ctr != 0){
							?>
								<input type="hidden" value="<?php echo $csv_desc[$ctr];?>" name = "csv_desc[<?php echo $ctr; ?>]">
								<?php 
								} $ctr++; }
							?>
					
					
				
						
					</form>
				</th>
			</tr>

		</tbody>
		
					
	</table>
	
				
			


<?php

	include 'includes/overall/footer.php' ; 
?>