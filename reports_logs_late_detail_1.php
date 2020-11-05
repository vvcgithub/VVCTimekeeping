<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_GET['date_from']) && isset($_GET['date_to']) && isset($_GET['employee_id_no'])) {
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
		$employee_id_no = $_GET['employee_id_no'];
		$date_from = date("Y-m-d", strtotime($date_from));
		$date_to = date("Y-m-d", strtotime($date_to));
	}
	
	if (isset($_POST['back'])) {
		header("Location: reports_logs_late.php?date_from=$date_from&date_to=$date_to");
		exit();
	}
?>
		<h1><a href='<?php echo "reports_logs_late.php?date_from=$date_from&date_to=$date_to"; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Log Late Per Employee</h1>
		<h2><?php echo complete_name_from_id_no($employee_id_no); ?></h2>
		<h3><?php echo date("M-j-Y", strtotime($date_from)) . " to " . date("M-j-Y", strtotime($date_to)); ?></h3>
<?php		
			
			if (empty($date_from) && empty($date_to)) {
				$date_sql = "";
			} else if (!empty($date_from) && !empty($date_to)) {
				$date_sql = "WHERE log_date BETWEEN '$date_from' AND '$date_to' AND log_employee_id_no='$employee_id_no'";
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
				<th class="center" style="min-width:200px;">Clients</th>
				<th class="center" style="min-width:50px;">Date</th>
				<th class="center" style="min-width:50px;">Late</th>
				<th class="center" style="min-width:50px;">Description</th>
			</tr>
		</thead>
		<tbody>			
<?php
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT DISTINCT logs.log_late, logs.log_description, logs.log_date, logs.log_client_code, logs.log_employee_id_no, clients.client_id, clients.client_code, clients.client_name FROM clients INNER JOIN logs ON clients.client_code = logs.log_client_code WHERE logs.log_date BETWEEN '$date_from' AND '$date_to' AND logs.log_employee_id_no = '$employee_id_no' ORDER BY clients.client_code");
		$a = 1;
		while($rows = $query->fetch_assoc()) {
			$client_code = $rows['client_code'];	
			$client_name = $rows['client_name'];
            $log_date = date("m/d/y", strtotime($rows['log_date']));
			$log_weekday = date("D", strtotime($rows['log_date']));
			$log_late = $rows['log_late'] / 60;
			$log_description = $rows['log_description'];
			
?>
			<tr class="alternate">
				<td class="right"><?php echo $a; ?></td>
				<td class="span: 200px;"><?php echo $client_name; ?></td>
				<td class="center"><?php echo $log_date . "<br><span style='font-size:.8em;'>" . $log_weekday . "</span>"; ?></td>
				<td class="center"><?php echo (!empty($log_late)) ?  number_format($log_late, 2, '.', ',') : "-"; ?></td>
				<td class="left"><?php echo $log_description; ?></td>
<?php
			$a = $a + 1;
		}
	
		
		$query_log = $mysqli->query("SELECT SUM(log_late) AS log_late_sum, log_employee_id_no, clients.client_name  FROM logs JOIN clients WHERE log_date  BETWEEN '$date_from' AND '$date_to' AND client_name='$client_name' AND log_employee_id_no='$employee_id_no'");
		while($rows_log = $query_log->fetch_assoc()) {
			$log_late_sum = $rows_log['log_late_sum'] / 60;
		}
		include 'core/database/close.php' ;
?>
			<tr class="bg-color-lightblue">
				<td class="right bold" colspan="3">Total Late: </td>
				<td class="center bold"><?php echo (!empty($log_late_sum)) ?  number_format($log_late_sum, 2, '.', ',') : "-"; ?></td>
				<td class="right bold"></td>
			
				
		</tbody>
	</table>
<?php
	include 'includes/overall/footer.php' ; 
?>