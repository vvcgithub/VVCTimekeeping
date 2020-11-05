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
		
	if (isset($_POST['refresh'])) {
		header('Location: logs_list.php?page=1');
		exit();
	}
	
	if (isset($_POST['add'])) {
		header('Location: logs_insert.php');
		exit();
	}
	
	if (isset($_POST['print'])) {
		header('Location: logs_list.php#logs_print');
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
		header('Location: logs_list.php#logs_import');
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
	
	if (isset($_POST['view_print'])) {
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors2[] = "Period is required!";
		}
		
		if (empty($errors2)) {
			$employee_id_no = $employee_data['employee_id_no'];
			header("Location: logs_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		} 
		
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
	
	
	
	if (isset($_POST['delete_selected']) && isset($_POST['unique_id'])) {
				$unique_id = implode(", ", $_POST['unique_id']);
				echo "<div class='padding10' style='border:3px solid orange; background:moccasin;'>";
				echo "<h1>Logs List</h1>";
				echo "<form action='logs_list.php?page=1' method='post'>";
				echo "<input type='hidden' name='unique_id' value='". $unique_id . "' />";
				echo "<p>Are you sure you want to delete selected records with id $unique_id?</p>";
				echo "<input type='submit' name='delete_selected_yes' value='Yes'>";
				echo "<input type='submit' name='delete_selected_no' value='No'>";
				echo "</form>";
				echo "</div>";
				include 'includes/overall/footer.php' ; 
				exit();
			
	}
	
	if (isset($_POST['delete_selected_yes'])) {
		include 'core/database/connect.php' ;
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			//echo $unique_id;
			$mysqli->query("DELETE FROM logs WHERE log_id IN ($unique_id) AND log_check = 0");
			header("Location: logs_list.php?page=$current_page");
			exit();
			
		}
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
	<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Logs List</h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1" method="post">
		<div style="overflow:auto;width:100%;height:100%;">
			<table class="table_list">
				<thead>
					<tr style="padding:0px;">
						<th colspan="22" class="left" style="border:none;">
								<input type="submit" name="add" value="Add" class="" />
								<input type="submit" name="template" value="Template" class="" />
								<input type="submit" name="print" value="Print" class="" />
								<input type="submit" name="refresh" value="Refresh" class="" />
								<input type="submit" name="delete_selected" value="Delete selected" class="" />
								<input type="submit" name="delete_all" value="Delete all" class="" />
						</th>
					</tr>
					<tr class="bg-color-dimgrey fg-color-white">
						<th><input type="checkbox" onclick="toggle_logs_list(this)" /></th>
						<th></th>
						<th></th>
						<th>Position</th>
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
						<th>G</th>
						<th>H</th>
						<th>OT</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
<?php	
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT log_id, log_employee_id_no, log_position_code, log_period_code, log_date, log_in, log_out, log_client_code, log_regular, log_late, log_undertime, log_a, log_b, log_c, log_d, log_e, log_f, log_g, log_h, log_description, log_check, log_check FROM logs WHERE log_employee_id_no = $employee_id_no ORDER BY log_date DESC LIMIT $limit ,$per_page");
					while($rows = $query->fetch_assoc()) {
						$log_id = $rows['log_id'];
						$log_employee_id_no =$rows['log_employee_id_no'];
						$log_position_code = $rows['log_position_code'];
						$log_period_code = $rows['log_period_code'];
						$log_date = date("m/d/y", strtotime($rows['log_date']));
						$log_date_biometrics = date("Y/m/d", strtotime($rows['log_date']));
						$log_weekday = date("D", strtotime($rows['log_date']));
						$log_in = ($rows['log_in'] == NULL) ? "" : $rows['log_in'];
						$query_log_in = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time ASC");
						$rows_in = mysqli_fetch_array($query_log_in);
						$log_in_biometrics = (mysqli_num_rows($query_log_in) > 0) ? $rows_in['biometrics_time'] : "null" ;
						$log_in_not_equal = ($log_in <> $log_in_biometrics) ? "red" : "green";
						$log_out =  ($rows['log_out'] == NULL) ? "" : $rows['log_out'];
						$query_log_out = mysqli_query($mysqli, "SELECT biometrics_time FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time DESC");
						$rows_out = mysqli_fetch_array($query_log_out);
						$log_out_biometrics = (mysqli_num_rows($query_log_out) > 0) ? $rows_out['biometrics_time'] : "null" ;
						$log_out_not_equal = ($log_out <> $log_out_biometrics) ? "red" : "green";
						$log_client_code = $rows['log_client_code'];
						$client_name_from_code = ($rows['log_client_code'] == NULL) ? "" : client_name_from_code($log_client_code);
						$log_late = ($rows['log_late'] == 0) ? "" : $rows['log_late'];
						$log_undertime = ($rows['log_undertime'] == 0) ? "" : $rows['log_undertime'];
						$log_a = ($rows['log_a'] == 0) ? "" : $rows['log_a'];
						$log_b = ($rows['log_b'] == 0) ? "" : $rows['log_b'];
						$log_c = ($rows['log_c'] == 0) ? "" : $rows['log_c'];
						$log_d = ($rows['log_d'] == 0) ? "" : $rows['log_d'];
						$log_e = ($rows['log_e'] == 0) ? "" : $rows['log_e'];
						$log_f = ($rows['log_f'] == 0) ? "" : $rows['log_f'];
						$log_g = ($rows['log_g'] == 0) ? "" : $rows['log_g'];
						$log_h = ($rows['log_h'] == 0) ? "" : $rows['log_h'];
						$log_overtime = $log_a + $log_b + $log_c + $log_d + $log_e + $log_f + $log_g + $log_h;
						$log_overtime = ($log_overtime == 0) ? "" : $log_overtime;
						$log_regular = ($rows['log_regular'] == 0) ? "" : $rows['log_regular'];
						$log_description = $rows['log_description'];
						$log_check = $rows['log_check'];
						$log_check = $rows['log_check'];
						
						 
						
?>				
						<tr id="<?php echo $log_id; ?>" 
							name="row_id[]" 
							style="<?php echo ($log_check == 1) ? 'background:lightgreen; color:grey;' : ''; ?>" 
							title="<?php echo $log_description; ?>" 
							class="alternate">
							<td class="center">
<?php
								if ($log_check == 0) {
?>						
									<input type="checkbox" name="unique_id[]" 
										   value="<?php echo $log_id; ?>" 
										   <?php echo ($log_check == 1) ? "disabled" : "" ?>  
												onclick="highlight(<?php echo $log_id; ?>, this, 
												<?php echo $log_check; ?>)"/>
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
								
								}
?>								
							</td>
							<td class="center">
<?php
								if ($log_check == 0) {
									
?>
									<a href="#" onclick="delete_confirm_for_logs('<?php echo $current_page; ?>', '<?php echo $log_id; ?>', '<?php echo $log_date. ", " . $log_weekday; ?>', '<?php echo $log_in; ?>', '<?php echo $log_out; ?>', '<?php echo $log_client_code; ?>')">Delete</a>
<?php								
								
								} 
?>
							</td>
							<td class="center"><?php echo $log_position_code; ?></td>
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
							<td class="center"><?php echo $log_g; ?></td>
							<td class="center"><?php echo $log_h; ?></td>
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
						<th colspan="22" class="right">
<?php
						include 'includes/page_number.php' ; 
?>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>
	<br />
	<div id="logs_import" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<h2>Import</h2>
			<form action="logs_import.php" method="post" enctype="multipart/form-data">
				<ul>
					<?php echo (isset($csv_empty) && !empty($csv_empty)) ? "<li class='fg-color-red'>$csv_empty</li>" : ""; ?>
					<?php echo (isset($file_type_not_allowed) && !empty($file_type_not_allowed)) ? "<li class='fg-color-red'>$file_type_not_allowed</li>" : ""; ?>
					<?php echo (isset($csv_size_invalid) && !empty($csv_size_invalid)) ? "<li class='fg-color-red'>$csv_size_invalid</li>" : ""; ?>
				</ul>
				<br />
				<label for="upfile">CSV File <span class="fg-color-red">*</span></label>
				<input type="file" id="upfile" name="upfile" accept="text/csv" class="span4" /><br /><br />
				<input type="submit" name="import" value="Import" />
			</form>
		</div>
	</div>
	
	<div id="logs_print" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<h2>Print</h2>
			<form action="" method="post">
<?php
				echo (isset($errors2) && !empty($errors2)) ? output_errors($errors2) : "";
?>
				<br />
				<label for="loa_period_code" class="span1">Period <span class="fg-color-red">*</span></label>
				<select name="period_code">
					<option class="span_auto" value="">- Select period -</option>
<?php
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT period_id, period_code FROM periods ORDER BY period_id DESC");
					while($rows = $query->fetch_assoc()) {
						$period_code = $rows['period_code'];
						
?>
						<option class="span_auto" value="<?php echo $period_code; ?>"><?php echo $period_code; ?></option>
<?php
					}
					include 'core/database/connect.php' ;
?>	
				</select><br /><br />
				<input type="submit" name="view_print" value="View" />
			</form>
		</div>
	</div>
<?php
	include 'includes/pagination_no_search.php' ; 
	include 'includes/overall/footer.php' ; 
?>