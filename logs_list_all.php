<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
		
	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = mysql_real_escape_string($current_page);
	} else {
		$current_page = 1;
		header('Location: logs_list.php?page=1');
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_POST['refresh'])) {
		header('Location: logs_list.php?page=1');
		exit();
	}
	
	if (isset($_POST['add'])) {
		header('Location: logs_insert.php');
		exit();
	}
	
	if (isset($_POST['print'])) {
		header('Location: logs_print_parameter.php');
		exit();
	}
	
	if (isset($_POST['template'])) {
		header('Location: logs_template.php');
		exit();
	}
	
	if (isset($_GET['upload'])) {
		header('Location: logs_uploader.php');
		exit();
	}
	
	if (isset($_GET['download'])) {
		$employee_id = $employee_data['employee_id'];
		header("Location: logs_report_to_csv_final.php?employee_id=$employee_id&date_from_final=&date_to_final=");
		exit();
	}
	
	if (isset($_GET['message'])) {
		header('Location: logs_message.php');
		exit();
	}
	
	if (isset($_GET['template'])) {
		header('Location: logs_template.php');
		exit();
	}
	
	if (isset($_POST['import'])) {
		header('Location: logs_import.php');
		exit(); 
	}
	
	if (isset($_POST['delete_all'])) {
		header('Location: logs_list_delete.php');
		exit(); 
	}
	
	if (isset($_GET['close'])) {
		header('Location: index.php');
		exit();
	}
	
	$employee_id = $employee_data['employee_id'];
	$employee_id_no = $employee_data['employee_id_no'];
	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_check = 0");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 30;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1)* $per_page;
	
	
	
	if (isset($_POST['delete_selected'])) {
		include 'core/database/connect.php' ;
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			$mysqli->autocommit(FALSE);
			foreach ($unique_id as $id => $value) {
				$mysqli->query("DELETE FROM logs WHERE log_id = '$value' AND log_check = 0");
			}
			header("Location: logs_list.php?page=$current_page");
			exit();
		}
		include 'core/database/close.php' ;
	}

	if(isset($_GET['delete'])) { 
		$log_id = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM logs WHERE log_id='$log_id'");
		include 'core/database/close.php' ;
		header('Location: logs_list.php?page=' . $current_page);
		exit();
	}
	
?>
	<h1>Logs List</h1>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="21" class="left">
						<button type="submit" name="add" title="Add"><img src="icons/add.png" /></button>
						<button type="submit" name="template" title="Template"><img src="icons/csv.png" /></button>
						<button type="submit" name="import" title="Import"><img src="icons/import.png" /></button>
						<button type="submit" name="print" title="Print"><img src="icons/print.png" /></button>
						<button type="submit" name="refresh" title="Refresh"><img src="icons/refresh.png" /></button>
						<button type="submit" name="delete_selected" title="Delete selected"><img src="icons/delete.png" /></button>
						<button type="submit" name="delete_all" title="Delete all"><img src="icons/empty_trash.png" /></button>
						
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th><input type="checkbox" onclick="toggle_logs_list(this)" /></th>
					<th></th>
					<th></th>
					<th>Period</th>
					<th>Date</th>
					<th style="width:60px;">In</th>
					<th style="width:60px;">Out</th>
					<th>Client</th>
					<th>Reg</th>
					<th>Late</th>
					<th>UT</th>
					<th>A</th>
					<th>B</th>
					<th>C</th>
					<th>D</th>
					<th>E</th>
					<th>F</th>
					<th>OT</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT log_id, log_employee_id_no, log_period_code, log_date, log_in, log_out, log_client_code, log_regular, log_late, log_undertime, log_a, log_b, log_c, log_d, log_e, log_f, log_description, log_check, log_check FROM logs WHERE log_employee_id_no = $employee_id_no ORDER BY log_period_code DESC, log_date DESC LIMIT $limit ,$per_page");
				while($rows = $query->fetch_assoc()) {
					$log_id = $rows['log_id'];
					$log_employee_id_no =$rows['log_employee_id_no'];
					$log_period_code = period_code($rows['log_period_code']);
					$log_date = date("m/d/y", strtotime($rows['log_date']));
					$log_date_biometrics = date("Y/m/d", strtotime($rows['log_date']));
					$log_weekday = date("D", strtotime($rows['log_date']));
					$log_in = ($rows['log_in'] === "00:00:00") ? "" : date("h:i A", strtotime($rows['log_in']));
					$query_log_in = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time ASC");
					$rows_in = mysqli_fetch_array($query_log_in);
					$log_in_biometrics = (mysqli_num_rows($query_log_in) > 0) ? date("h:i A", strtotime($rows_in['biometrics_time'])) : "null" ;
					$log_in_not_equal = ($log_in <> $log_in_biometrics) ? "red" : "green";
					$log_out =  ($rows['log_out'] === "00:00:00") ? "" : date("h:i A", strtotime($rows['log_out']));
					$query_log_out = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time DESC");
					$rows_out = mysqli_fetch_array($query_log_out);
					$log_out_biometrics = (mysqli_num_rows($query_log_out) > 0) ? date("h:i A", strtotime($rows_out['biometrics_time'])) : "null" ;
					$log_out_not_equal = ($log_out <> $log_out_biometrics) ? "red" : "green";
					$log_client_code = $rows['log_client_code'];
					$client_name_from_code = client_name_from_code($log_client_code);
					$log_late = ($rows['log_late'] == 0) ? "" : $rows['log_late'];
					$log_undertime = ($rows['log_undertime'] == 0) ? "" : $rows['log_undertime'];
					$log_a = ($rows['log_a'] == 0) ? "" : $rows['log_a'];
					$log_b = ($rows['log_b'] == 0) ? "" : $rows['log_b'];
					$log_c = ($rows['log_c'] == 0) ? "" : $rows['log_c'];
					$log_d = ($rows['log_d'] == 0) ? "" : $rows['log_d'];
					$log_e = ($rows['log_e'] == 0) ? "" : $rows['log_e'];
					$log_f = ($rows['log_f'] == 0) ? "" : $rows['log_f'];
					$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f;
					$log_overtime = ($log_overtime == 0) ? "" : $log_overtime;
					$log_regular = ($rows['log_regular'] == 0) ? "" : $rows['log_regular'];
					$log_description = $rows['log_description'];
					$log_check = $rows['log_check'];
					$log_check = $rows['log_check'];
					
					 
					
?>				
					<tr id="<?php echo $log_id; ?>" name="row_id[]" style="<?php echo ($log_check == 1) ? 'background:lightgreen; color:grey;' : ''; ?>" title="<?php echo $log_description; ?>" class="alternate">
						<td class="center">
<?php
							if ($log_check == 0) {
?>						
								<input type="checkbox" name="unique_id[]" value="<?php echo $log_id; ?>" <?php echo ($log_check == 1) ? "disabled" : "" ?>  onclick="highlight(<?php echo $log_id; ?>, this, <?php echo $log_check; ?>)"/>
<?php
							}
?>						
						</td>
						<td class="center">
<?php
							if ($log_check == 0) {
?>
							<a href="logs_update.php?log_id=<?php echo $log_id ; ?>&page=<?php echo $current_page ; ?>">Edit</a>

<?php
							} else {
?>
								Edit
<?php
							}
?>								
						</td>
						<td class="center">
<?php
							if ($log_check == 0) {
								
?>
								<a href="#" onclick="delete_confirm_for_logs('<?php echo $current_page; ?>', '<?php echo $log_id; ?>', '<?php echo $log_date. ", " . $log_weekday; ?>', '<?php echo $log_in; ?>', '<?php echo $log_out; ?>', '<?php echo $log_client_code; ?>')">Delete</a>
<?php								
							
							} else {
?>
									Delete
<?php
							}
?>
						</td>
						<td class="center"><?php echo $log_period_code; ?></td>
						<td class="center"><?php echo $log_date . "<br><span style='font-size:.8em;'>" . $log_weekday . "</span>"; ?></td>
						<td class="center"><?php echo $log_in . "<br>" . "<span style='color:$log_in_not_equal; font-size:.8em;'><sup>b</sup>" . $log_in_biometrics . "</span>"; ?></td>
						<td class="center"><?php echo $log_out . "<br>" . "<span style='color:$log_out_not_equal; font-size:.8em;'><sup>b</sup>" . $log_out_biometrics . "</span>"; ?></td>
						<td class="center"><?php echo $client_name_from_code; ?></td>
						<td class="center"><?php echo $log_regular; ?></td>
						<td class="center"><?php echo $log_late; ?></td>
						<td class="center"><?php echo $log_undertime; ?></td>
						<td class="center"><?php echo $log_a; ?></td>
						<td class="center"><?php echo $log_b; ?></td>
						<td class="center"><?php echo $log_c; ?></td>
						<td class="center"><?php echo $log_d; ?></td>
						<td class="center"><?php echo $log_e; ?></td>
						<td class="center"><?php echo $log_f; ?></td>
						<td class="center"><?php echo $log_overtime; ?></td>
						<td><?php echo $log_description; ?></td>
					</tr>
<?php
				}
				include 'core/database/close.php' ;	
?>					
			</tbody>
			<tfoot>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="21" class="right">
<?php
					include 'includes/page_number.php' ; 
?>
					</th>
				</tr>
			</tfoot>
		</table>
	</form>
	<br />

<?php
	include 'includes/pagination_no_search.php' ; 
	include 'includes/overall/footer.php' ; 
?>