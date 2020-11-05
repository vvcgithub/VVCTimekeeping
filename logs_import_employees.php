<?php
	include 'core/init.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;

	if (isset($_POST['import'])) {
		$employee_id_no = check_input($_POST['employee_id_no']);
		$temp = $_FILES['upfile']['tmp_name'];
		$file = check_input($temp);
		
		if (empty($employee_id_no) === true) {
			$errors[] = "Employee is required!";
		}
		
		if (empty($file) === true) {
			$errors[] = "CSV file is required!";
		}
		
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$mysqli->query("LOAD DATA INFILE '" . $file . "' REPLACE INTO TABLE logs FIELDS TERMINATED BY ',' IGNORE 1 LINES 
		(@log_period_code, @log_date, @log_in, @log_out, @log_client_code, @log_regular, @log_late, @log_undertime, @log_a, @log_b, @log_c, @log_d, @log_e, @log_f, @log_g, @log_h, @log_description) SET log_employee_id_no = $employee_id_no, log_period_code = @log_period_code, log_date = STR_TO_DATE(NULLIF(@log_date,''),'%c/%e/%Y'), log_in = TIME(STR_TO_DATE(NULLIF(@log_in,''),'%H:%i:%s')), log_out = TIME(STR_TO_DATE(NULLIF(@log_out,''),'%H:%i:%s')), log_client_code = NULLIF(@log_client_code,''), log_regular = NULLIF(@log_regular,''), log_late = NULLIF(@log_late,''), log_undertime = NULLIF(@log_undertime,''), log_a = NULLIF(@log_a,''), log_b = NULLIF(@log_b,''), log_c = NULLIF(@log_c,''), log_d = NULLIF(@log_d,''), log_e = NULLIF(@log_e,''), log_f = NULLIF(@log_f,''), log_g = NULLIF(@log_g,''), log_h = NULLIF(@log_h,''), log_i = NULLIF(@log_i,''), log_description = NULLIF(@log_description,'')"); // format for military time '%h:%i %p'
			include 'core/database/close.php' ;
			header('Location: maintain.php');
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: logs_main.php");
		exit();
	}
?>
	<h1><a href="logs_main.php" style="text-decoration:none;" title="Back">&#8656;</a> Import Logs</h1>
	<form action="" method="post" enctype="multipart/form-data">
	
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
		echo (isset($messages) && !empty($messages)) ? output_messages($messages) : "";
?>
		<br />
		<label for="employee_id_no" class="span1">Employee <span class="fg-color-red">*</span></label>
		<select name="employee_id_no" id="employee_id_no">
			<option value="">- Select employee -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM employees ORDER BY employee_last_name ASC");
			while($rows = $query->fetch_assoc()) {
				$id_no = $rows['employee_id_no'];
?>
				<option class="span_auto" value="<?php echo $id_no; ?>" <?php echo (isset($employee_id_no) && $employee_id_no === $id_no) ? 'selected' : '' ; ?>><?php echo complete_name_from_id_no($id_no); ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select>
		<br /><br />
		<label for="employee_id_no" class="span1">CSV file <span class="fg-color-red">*</span></label>
		<input type="file" id="upfile" name="upfile" accept="text/csv" class="span4" />
		<br /><br />
		<input type="submit" name="import" value="Import" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>