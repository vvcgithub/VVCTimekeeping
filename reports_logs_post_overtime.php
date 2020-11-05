<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['period_code'])) {
		$period_code = $_GET['period_code'];
	}
	
	if (isset($_POST['detail'])) {
	header("Location: reports_logs_post_overtime_detail.php?period_code=$period_code");
	}
	
?>
		<h1><a href="reports_main.php#reports_post_logs_overtime_parameter" style="text-decoration:none;" title="Back">&#8656;</a> Logs Post Overtime</h1>
		<h3><?php echo "Period code: " . $period_code . " (" . period_from($period_code) . " to " .  period_to($period_code) . ")"; ?></h3>
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
				<th colspan="9" class="left">
					<form action="" method="post">
						<input type="button" onclick="window.print();return false;"  value="Print" class="button_text" />
						<input type="submit" name="detail" value="Detail" class="button_text" />
					</form>
				</th>
			</tr>
			<tr class="bg-color-dimgrey fg-color-white">						
				<th></th>
				<th class="center" style="min-width:200px;">Employee</th>
				<th class="center" style="min-width:80px;">Client</th>
				<th class="center" style="min-width:50px;">A</th>
				<th class="center" style="min-width:50px;">B</th>
				<th class="center" style="min-width:50px;">C</th>
				<th class="center" style="min-width:50px;">D</th>
				<th class="center" style="min-width:50px;">E</th>
				<th class="center" style="min-width:50px;">Total</th>
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query1 = $mysqli->query("SELECT logs_post.log_employee_id_no, logs_post.log_period_code, employees.employee_id_no, employees.employee_last_name FROM employees INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no WHERE logs_post.log_period_code = '$period_code' AND (logs_post.log_a > 0 OR logs_post.log_b > 0 OR logs_post.log_c > 0 OR logs_post.log_d > 0 OR logs_post.log_e > 0 OR logs_post.log_f > 0) GROUP BY logs_post.log_employee_id_no ORDER BY employees.employee_last_name");
		$a = 1;
		while($rows1 = $query1->fetch_assoc()) {
			$employee_id_no1 = $rows1['employee_id_no'];	
			$query = $mysqli->query("SELECT		logs_post.log_post_id,
												logs_post.log_employee_id_no,
												logs_post.log_client_code,
												sum(logs_post.log_a) AS log_a,
												sum(logs_post.log_b) AS log_b,
												sum(logs_post.log_c) AS log_c,
												sum(logs_post.log_d) AS log_d,
												sum(logs_post.log_e) AS log_e,
												sum(logs_post.log_f) AS log_f,
												employees.employee_id_no,
												employees.employee_last_name,
												logs_post.log_client_code
									FROM employees 
									INNER JOIN logs_post ON employees.employee_id_no = logs_post.log_employee_id_no 
									WHERE logs_post.log_employee_id_no = '$employee_id_no1' AND logs_post.log_period_code = '$period_code'  AND (logs_post.log_a > 0 OR logs_post.log_b > 0 OR logs_post.log_c > 0 OR logs_post.log_d > 0 OR logs_post.log_e > 0 OR logs_post.log_f > 0) GROUP BY logs_post.log_employee_id_no ORDER BY logs_post.log_client_code");
			
			
			while($rows = $query->fetch_assoc()) {
				$log_client_code = "***";
				$log_a = $rows['log_a'];
				$log_b = $rows['log_b'];
				$log_c = $rows['log_c'];
				$log_d = $rows['log_d'];
				$log_e = $rows['log_e'];
				$log_f = $rows['log_f'];
				$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e;
				$log_post_id = $rows['log_post_id'];	
				$employee_id_no = $rows['employee_id_no'];	
				$employee_name = last_name_from_id_no($rows['employee_id_no']) .  ", " . first_name_from_id_no($rows['employee_id_no']);	
				
?>
				<tr>
					<td class="right"><?php echo $a; ?></td>
					<td><?php echo $employee_name; ?></td>
					<td><?php echo $log_client_code; ?></td>
					<td class="right"><?php echo (!empty($log_a)) ? number_format($log_a, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_b)) ? number_format($log_b, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_c)) ? number_format($log_c, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_d)) ? number_format($log_d, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_e)) ? number_format($log_e, 2, '.', ',') : "-"; ?></td>
					<td class="right"><?php echo (!empty($log_overtime)) ? number_format($log_overtime, 2, '.', ',') : "-"; ?></td>
				</tr>					
<?php
			$a = $a + 1;	
			}
			
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_regular) AS total_regular, SUM(log_a) AS total_a, SUM(log_b) AS total_b, SUM(log_c) AS total_c, SUM(log_d) AS total_d, SUM(log_e) AS total_e, SUM(log_f) AS total_f FROM logs_post WHERE log_period_code = '$period_code'");
		while($rows = $query->fetch_assoc()) {
			$total_regular = $rows['total_regular'];
			$total_a = $rows['total_a'];
			$total_b = $rows['total_b'];
			$total_c = $rows['total_c'];
			$total_d = $rows['total_d'];
			$total_e = $rows['total_e'];
			$total_f = $rows['total_f'];
			$total_overtime = $total_a + $total_b + $total_c + $total_d + $total_e;
		}
		include 'core/database/close.php' ;
?>
			<tr class="bg-color-lightblue">
				<td class="right bold" colspan="3">Grand Total</td>
				<td class="right bold"><?php echo (!empty($total_a)) ?  number_format($total_a, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_b)) ?  number_format($total_b, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_c)) ?  number_format($total_c, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_d)) ?  number_format($total_d, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_e)) ?  number_format($total_e, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"><?php echo (!empty($total_overtime)) ?  number_format($total_overtime, 2, '.', ',') : "-"; ?></td>
			</tr>
		</tbody>
	</table>
<?php
	include 'includes/overall/footer.php' ; 
?>