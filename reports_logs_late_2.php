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
	<h1><a href="reports_main.php#reports_logs_late_parameter" style="text-decoration:none;" title="Back">&#8656;</a> Summary Logs Late</h1>
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
				<th class="center" style="min-width:50px;">Clients</th>
				<th class="center" style="min-width:50px;">Lates</th>
				
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query1 = $mysqli->query("SELECT    logs.log_employee_id_no, 
											logs.log_period_code, 
											employees.employee_id_no, 
											employees.employee_last_name 
									FROM employees 
									INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no 
									WHERE logs.log_date BETWEEN '$date_from' AND '$date_to' 
									GROUP BY logs.log_employee_id_no ORDER BY employees.employee_last_name");
		$a = 1;
		while($rows1 = $query1->fetch_assoc()) {
			$employee_id_no1 = $rows1['employee_id_no'];	
			$query = $mysqli->query("SELECT		SUM(log_late) as sum_log_late,
												logs.log_id,
												logs.log_employee_id_no,
												logs.log_client_code,
												employees.employee_id_no,
												employees.employee_last_name,
												logs.log_client_code
									FROM employees 
									INNER JOIN logs ON employees.employee_id_no = logs.log_employee_id_no 
									WHERE logs.log_employee_id_no = '$employee_id_no1' 
									AND logs.log_date BETWEEN '$date_from' AND '$date_to'
									GROUP BY logs.log_employee_id_no 
									ORDER BY logs.log_client_code");
			
			
			while($rows = $query->fetch_assoc()) {
				$log_client_code = "***";
				$employee_id_no = $rows['employee_id_no'];
				$sum_log_late = $rows['sum_log_late'] / 60;
				$employee_name = last_name_from_id_no($rows['employee_id_no']) .  ", " . first_name_from_id_no($rows['employee_id_no']);
				
				
				
?>
			<tr class="alternate">
				<td class="right"><?php echo $a; ?></td>
				<td><a href="reports_logs_late_detail.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&employee_id_no=<?php echo $employee_id_no; ?>"><?php echo complete_name_from_id_no($employee_id_no); ?></a></td>
				<td class="right"><?php echo $log_client_code;?></td>
				<td class="right"><?php echo (!empty($sum_log_late)) ?  number_format($sum_log_late, 2, '.', ',') : "-"; ?></td>
				
			</tr>					
<?php	
			$a = $a + 1;	
			}
			
		}
		include 'core/database/close.php' ;
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT SUM(log_late) AS total_late FROM logs WHERE log_date BETWEEN '$date_from' AND '$date_to'");
		while($rows = $query->fetch_assoc()) {
			$total_late = $rows['total_late'] / 60;
		}
		include 'core/database/close.php' ;
?>
			<tr class="bg-color-lightblue">
				<td class="right bold" colspan="3">Total Late: </td>
				
				<td class="right bold"><?php echo (!empty($total_late)) ?  number_format($total_late, 2, '.', ',') : "-"; ?></td>
				
				
			</tr>
		</tbody>
	</table>
<?php
	include 'includes/overall/footer.php' ; 
?>