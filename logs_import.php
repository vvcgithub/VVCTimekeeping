<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;

	if (isset($_POST['import'])) {
		$employee_id_no = $employee_data['employee_id_no'];
		$temp = $_FILES['upfile']['tmp_name'];
		$file = check_input($temp);
		include 'core/database/connect.php' ;
		$mysqli->query("LOAD DATA INFILE '" . $file . "' REPLACE INTO TABLE logs FIELDS TERMINATED BY ',' IGNORE 1 LINES 
		(@log_position_code, @log_period_code, @log_date, @log_in, @log_out, @log_client_code, @log_regular, @log_late, @log_undertime, @log_a, @log_b, @log_c, @log_d, @log_e, @log_description) SET log_employee_id_no = $employee_id_no, log_position_code = @log_position_code, log_period_code = @log_period_code, log_date = STR_TO_DATE(NULLIF(@log_date,''),'%c/%e/%Y'), log_in = TIME(STR_TO_DATE(NULLIF(@log_in,''),'%H:%i:%s')), log_out = TIME(STR_TO_DATE(NULLIF(@log_out,''),'%H:%i:%s')), log_client_code = NULLIF(@log_client_code,''), log_regular = NULLIF(@log_regular,''), log_late = NULLIF(@log_late,''), log_undertime = NULLIF(@log_undertime,''), log_a = NULLIF(@log_a,''), log_b = NULLIF(@log_b,''), log_c = NULLIF(@log_c,''), log_d = NULLIF(@log_d,''), log_e = NULLIF(@log_e,''), log_description = NULLIF(@log_description,'')"); // format for military time '%h:%i %p'
		include 'core/database/close.php' ;
		header('Location: logs_list.php');
	}
	
	if (isset($_POST['close'])) {
		header("Location: logs_list.php");
		exit();
	}
?>
	<div id="logs_import" class="modalDialog">
		<div>
		<h1><a href="logs_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Import Logs</h1>
		<form action="logs_import.php" method="post" enctype="multipart/form-data">
			<ul>
				<?php echo (isset($csv_empty) && !empty($csv_empty)) ? "<li class='fg-color-red'>$csv_empty</li>" : ""; ?>
				<?php echo (isset($file_type_not_allowed) && !empty($file_type_not_allowed)) ? "<li class='fg-color-red'>$file_type_not_allowed</li>" : ""; ?>
				<?php echo (isset($csv_size_invalid) && !empty($csv_size_invalid)) ? "<li class='fg-color-red'>$csv_size_invalid</li>" : ""; ?>
			</ul>
			<br />
			<label for="upfile" class="span1">CSV File <span class="fg-color-red">*</span></label>
			<input type="file" id="upfile" name="upfile" accept="text/csv" class="span4" /><br /><br />
			<input type="submit" name="import" value="Import" />
		</form>
		</div>
	</div>
<?php
	include 'includes/overall/footer.php' ; 
?>