<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	
	
	if (isset($_GET['log_id'])) {
		$log_id = check_input( $_GET['log_id']);
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT * FROM logs WHERE log_id = $log_id"); 
		$rows = $query->fetch_assoc();

		if($rows) {
			$log_id = $rows['log_id'];
			$log_employee_id_no = $rows['log_employee_id_no'];
			$log_position_code = $rows['log_position_code'];
			$log_period_code = $rows['log_period_code'];
			$log_date = date("m/d/Y", strtotime($rows['log_date']));
			$log_in = $rows['log_in']; 
			$log_out = $rows['log_out']; 
			$log_client_code = $rows['log_client_code'];
			$log_regular = $rows['log_regular'];
			$log_late = $rows['log_late'];
			$log_undertime = $rows['log_undertime'];
			$log_a = $rows['log_a'];
			$log_b = $rows['log_b'];
			$log_c = $rows['log_c'];
			$log_d = $rows['log_d'];
			$log_e = $rows['log_e'];
			$log_f = $rows['log_f'];
			$log_g = $rows['log_g'];
			$log_h = $rows['log_h'];
			$log_description = $rows['log_description'];
			$log_check = $rows['log_check'];
			$log_post = $rows['log_post'];

			//special for log_in
			$log_in_hour = ($log_in === "00:00:00") ? "" : date("h", strtotime($log_in));
			$log_in_minute = ($log_in === "00:00:00") ? "" : date("i", strtotime($log_in));
			$log_in_meridiem = ($log_in === "00:00:00") ? "" : date("A", strtotime($log_in));

			//special for log_out
			$log_out_hour = ($log_out === "00:00:00") ? "" : date("h", strtotime($log_out));
			$log_out_minute = ($log_out === "00:00:00") ? "" : date("i", strtotime($log_out));
			$log_out_meridiem = ($log_out === "00:00:00") ? "" : date("A", strtotime($log_out));
		} else {
			header("Location: logs_update_check.php?employee_id_no=$log_employee_id_no&period_code=$log_period_code");
			exit();
		}
		include 'core/database/connect.php' ;
	}
	
	if (isset($_POST['fieldwork']) || isset($_POST['biometrics']) || isset($_POST['regular']) || isset($_POST['update']) || isset($_POST['late']) || isset($_POST['undertime'])) { 
		$log_id = check_input($_POST['log_id']);
		$log_employee_id_no = check_input($_POST['log_employee_id_no']);
		$log_position_code = check_input($_POST['log_position_code']);
		$log_period_code = check_input($_POST['log_period_code']);
		$log_date = check_input($_POST['log_date']);
		$log_in = check_input($_POST['log_in']);
		$log_out = check_input($_POST['log_out']);
		$log_client_code = check_input($_POST['log_client_code']);
		$log_regular = check_input($_POST['log_regular']);
		$log_late = check_input($_POST['log_late']);
		$log_undertime = check_input($_POST['log_undertime']);
		$log_a = check_input($_POST['log_a']);
		$log_b = check_input($_POST['log_b']);
		$log_c = check_input($_POST['log_c']);
		$log_d = check_input($_POST['log_d']);
		$log_e = check_input($_POST['log_e']);
		$log_f = check_input($_POST['log_f']);
		$log_g = check_input($_POST['log_g']);
		$log_h = check_input($_POST['log_h']);
		$log_description = check_input($_POST['log_description']);
		
		if (isset($_POST['late'])) {
			$late_start_time = new DateTime(settings("log_in"));
			$late_end_time = new DateTime($log_in);
			$interval = $late_start_time->diff($late_end_time);
			$hours   = $interval->format('%h');
			$minutes = $interval->format('%i');

			$log_late = ($late_start_time < $late_end_time && !empty($log_in)) ? $hours * 60 + $minutes : "";
		}
		
		if (isset($_POST['undertime'])) {
			$undertime_start_time = new DateTime($log_out);
			$undertime_end_time = new DateTime(settings("log_out"));
			$interval = $undertime_start_time->diff($undertime_end_time);
			$hours   = $interval->format('%h');
			$minutes = $interval->format('%i');

			$log_undertime = ($undertime_start_time < $undertime_end_time && !empty($log_out)) ? $hours * 60 + $minutes : "";
		}
		
		if (isset($_POST['biometrics'])) {
			$log_in = "";
			$log_out = "";
			$log_date_biometrics = date("Y-m-d", strtotime($log_date));
			
			$employee_id_no = $employee_data['employee_id_no'];

			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT DISTINCT * FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time ASC");
			while($rows = $query->fetch_assoc()) {
				$log_in =$rows['biometrics_time'];
				break;
			} 
			
			$query = $mysqli->query("SELECT DISTINCT * FROM biometrics WHERE biometrics_employee_id_no = '$employee_id_no' AND biometrics_date = '$log_date_biometrics' ORDER BY biometrics_time DESC");
			while($rows = $query->fetch_assoc()) {
				$log_out = $rows['biometrics_time'];
				break;
			}
			$messages[] = (empty($log_in) && empty($log_out)) ? "<ul class='errors'><li>Biometrics is set to nothing!</li></ul>" : "<ul class='messages'><li>Biometrics is set $log_in to $log_out.</li></ul>";
			include 'core/database/close.php' ;
		}
		
		if (isset($_POST['fieldwork'])) {
			$log_in = settings("log_in");
			$log_out = settings("log_out");
			$fieldwork_in = $log_in;
			$fieldwork_out = $log_out;
			$messages[] = (empty($fieldwork_in) && empty($fieldwork_out)) ? "<ul class='errors'><li>Fieldwork is set to nothing!</li></ul>" : "<ul class='messages'><li>Fieldwork is set $log_in to $log_out.</li></ul>";
		}
		
		if (isset($_POST['regular'])) {
			$log_date_for_log_regular = date("Y-m-d", strtotime($log_date));
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT sum(log_regular) as total_log_regular FROM logs WHERE log_employee_id_no = $log_employee_id_no AND log_date = '$log_date_for_log_regular'");
			$rows = $query->fetch_assoc();
			$total_log_regular = $rows['total_log_regular'];
			$messages[] = (empty($total_log_regular)) ? "<span class='fg-color-red bold'>Regular entered is nothing!</span>" : "<span class='fg-color-green bold'>Regular entered total is $total_log_regular.</span>";
			include 'core/database/close.php' ;
		}
		
		if (isset($_POST['update'])) { 
			if (empty($log_position_code) === true) {
				$errors[] = "Position is required!";
			}
		
			if (empty($log_period_code) === true) {
				$errors[] = "Period is required!";
			}
			
			if (empty($log_date) === true) {
				$errors[] = "Date is required!";
			}
			
			
			if (empty($errors)) {
				$log_in = (empty($log_in))? 'NULL' : "'$log_in'";
				$log_out = (empty($log_out))? 'NULL' : "'$log_out'";
				$log_client_code = (empty($log_client_code))? 'NULL' : "'$log_client_code'";
				$log_regular = (empty($log_regular))? 'NULL' : "'$log_regular'";
				$log_late = (empty($log_late))? 'NULL' : "'$log_late'";
				$log_undertime = (empty($log_undertime))? 'NULL' : "'$log_undertime'";
				$log_a = (empty($log_a))? 'NULL' : "'$log_a'";
				$log_b = (empty($log_b))? 'NULL' : "'$log_b'";
				$log_c = (empty($log_c))? 'NULL' : "'$log_c'";
				$log_d = (empty($log_d))? 'NULL' : "'$log_d'";
				$log_e = (empty($log_e))? 'NULL' : "'$log_e'";
				$log_f = (empty($log_f))? 'NULL' : "'$log_f'";
				$log_g = (empty($log_g))? 'NULL' : "'$log_g'";
				$log_h = (empty($log_h))? 'NULL' : "'$log_h'";
				$log_description = (empty($log_description))? 'NULL' : "'$log_description'";
				include 'core/database/connect.php' ;
				$mysqli->query("UPDATE logs SET log_employee_id_no='$log_employee_id_no', 
				log_position_code='$log_position_code', 
				log_period_code='$log_period_code', 
				log_date='" . date("Y-m-d", strtotime($log_date)) . "', 
				log_in=$log_in, 
				log_out=$log_out, 
				log_client_code=$log_client_code, 
				log_regular=$log_regular, 
				log_late=$log_late, 
				log_undertime=$log_undertime, 
				log_a=$log_a, 
				log_b=$log_b, 
				log_c=$log_c, 
				log_d=$log_d, 
				log_e=$log_e,
				log_f=$log_f,
				log_g=$log_g,
				log_h=$log_h,
				log_description=$log_description
				WHERE log_id='$log_id'");
				include 'core/database/close.php' ;
				header("Location: reviews_check_report.php?employee_id_no=$log_employee_id_no&period_code=$log_period_code");
				exit();
			}
		}	
	}

	if (isset($_POST['close'])) {
		header("Location: reviews_check_report.php?employee_id_no=$log_employee_id_no&period_code=$log_period_code");
		exit();
	}

?>
	<h1><a href='<?php echo "reviews_check_report.php?employee_id_no=$log_employee_id_no&period_code=$log_period_code" ; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Update Log</h1>
	<h2><?php echo (isset($log_employee_id_no)) ? complete_name_from_id_no($log_employee_id_no) : ""; ?></h2>
	<h3><?php echo (isset($log_period_code)) ? $log_period_code : ""; ?></h3>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
		echo (isset($messages) && !empty($messages)) ? output_messages($messages) : "";
?>
		
		<input type="hidden" name="log_id" value="<?php echo $log_id; ?>"  />
		<input type="hidden" name="log_employee_id_no" value="<?php echo $log_employee_id_no; ?>"  />
		<label for="log_position_code" class="span2">Position <span class="fg-color-red">*</span></label>
		<select name="log_position_code" id="log_position_code">
			<option class="span_auto" value="" >- Select position -</option>
<?php			
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT position_code, position_name FROM positions ORDER BY position_id DESC");
			while($rows = $query->fetch_assoc()) {
				$position_code = $rows['position_code'];
				$position_name = $rows['position_name'];
?>
			<option class="span_auto" value="<?php echo $position_code; ?>" <?php if (isset($log_position_code) && ($log_position_code === $position_code)) { echo "selected"; } ?>><?php echo $position_code . " - " . $position_name; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select><br /><br />
		<label for="log_period_code" class="span2">Period <span class="fg-color-red">*</span></label>
		<select name="log_period_code" id="log_period_code">
			<option class="span_auto" value="" >- Select period -</option>
<?php			
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT period_id, period_code FROM periods ORDER BY period_id DESC");
			while($rows = $query->fetch_assoc()) {
				$period_id = $rows['period_id'];
				$period_code = $rows['period_code'];
?>
			<option class="span_auto" value="<?php echo $period_code; ?>" <?php if (isset($log_period_code) && ($log_period_code === $period_code)) { echo "selected"; } ?>><?php echo $period_code; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>
		</select><br /><br />
		<label for="date" class="span2">Date<i> (yyyy-mm-dd)</i> <span class="fg-color-red">*</span></label>
		<?php date_default_timezone_set(settings("time_logs_timezone")); ?>
		<input type="date" id="date" name="log_date" maxlength="10" class="span2" style="font-family:arial;" value="<?php echo (isset($log_date)) ? date("Y-m-d", strtotime($log_date)) : "" ; ?>" placeholder="yyyy-mm-dd" required="required" />
		<br /><br />
		<label for="log_in" class="span2">In<i> (hh:mm AM/PM)</i></label>
		<input type="time" name="log_in" value="<?php echo (isset($log_in)) ? $log_in : ""; ?>" style="font-family:arial;" placeholder="hh:mm AM/PM"/>
		<br /><br />
		<label for="log_out" class="span2">Out<i> (hh:mm AM/PM)</i></label>
		<input type="time" name="log_out" value="<?php echo (isset($log_out)) ? $log_out : ""; ?>" style="font-family:arial;" placeholder="hh:mm AM/PM"/>
		<br /><br />
		<label for="log_client_code" class="span2">Client</label>
			<select name="log_client_code" id="log_client_code">
				<option class="span_auto" value="" >- Select client -</option>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT client_code, client_name FROM clients WHERE active = 1 ORDER BY client_name ASC");
				while($rows = $query->fetch_assoc()) {
					$client_code = $rows['client_code'];
					$client_name = $rows['client_name'];
?>
				<option style="width:auto;" value="<?php echo $client_code; ?>" <?php if (isset($log_client_code) && ($log_client_code === $client_code)) { echo "selected"; } ?>><?php echo $client_code . " - " . $client_name; ?></option>
<?php
				}
				include 'core/database/close.php' ;
?>
			</select>
		<br /><br />
		<label for="log_regular" class="span2">Regular<i> (hour)</i></label>
			<input type="number" step="any" name="log_regular" id="log_regular" class="span0" value="<?php if (isset($log_regular)) { echo $log_regular; } ?>" onkeyup="float_numbers(this)" maxlength="5" />
		<br /><br />
		<label for="log_late" class="span2">Late<i> (minute)</i></label>
			<input type="number" step="any" name="log_late" id="log_late" class="span0" value="<?php if (isset($log_late)) { echo $log_late; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
		<br /><br />
		<label for="log_undertime" class="span2">Undertime<i> (minute)</i></label>
			<input type="number" step="any" name="log_undertime" id="log_undertime" class="span0" value="<?php if (isset($log_undertime)) { echo $log_undertime; } ?>" maxlength="5" onkeyup="float_numbers(this)" oninput="log_overtime_total()"  />
		<br /><br />
		<label for="log_a" class="span2">Overtime<i> (hour)</i></label>
			<input type="number" step="any" name="log_a" class="span0" id="log_a" placeholder="A" value="<?php if (isset($log_a)) { echo $log_a; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>a</sup>
			
			<input type="number" step="any" name="log_b" class="span0" id="log_b" placeholder="B" value="<?php if (isset($log_b)) { echo $log_b; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>b</sup>
			
			<input type="number" step="any" name="log_c" class="span0" id="log_c" placeholder="C" value="<?php if (isset($log_c)) { echo $log_c; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>c</sup>	
			
			<input type="number" step="any" name="log_d" class="span0" id="log_d" placeholder="D" class="text_medium" value="<?php if (isset($log_d)) { echo $log_d; } ?>" maxlength="5" onkeyup="float_numbers(this) " oninput="log_overtime_total()" />
			<sup>d</sup>	
			
			<input type="number" step="any" name="log_e" class="span0" id="log_e" placeholder="E" value="<?php if (isset($log_e)) { echo $log_e; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>e</sup>
			
			<input type="number" step="any" name="log_f" class="span0" id="log_f" placeholder="F" value="<?php if (isset($log_f)) { echo $log_f; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>f</sup>
			
			<input type="number" step="any" name="log_g" class="span0" id="log_g" placeholder="G" value="<?php if (isset($log_g)) { echo $log_g; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>g</sup>
			
			<input type="number" step="any" name="log_h" class="span0" id="log_h" placeholder="H" value="<?php if (isset($log_h)) { echo $log_h; } ?>" maxlength="5" onkeyup="float_numbers(this)" />
			<sup>h</sup>			
		<br /><br />
		<label for="log_description" class="span2">Description<i> (300 char)</i></label>
			<textarea name="log_description" id="log_description" class="span8" maxlength="300" placeholder="Things to do..." ><?php if (isset($log_description)) { echo $log_description; } ?></textarea>
		<br /><br />
		<span class="fg-color-red">&nbsp;All fields marked with an asterisk (*) are required.</span>
		<br /><br />
		<input type="submit" name="update" value="Update" />
		<input type="submit" name="biometrics" value="Biometrics" />
		<input type="submit" name="fieldwork" value="Fieldwork" />
		<input type="submit" name="late" value="Late" />
		<input type="submit" name="undertime" value="Undertime" />
		<input type="submit" name="regular" value="Regular" />
		<input type="submit" name="close" value="Close" />
			
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>