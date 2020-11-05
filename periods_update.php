<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if (isset($_GET['period_id'])) {	
		// query db
		$period_id = $_GET['period_id'];
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT * FROM periods WHERE period_id=$period_id"); 
		$rows = $query->fetch_assoc();

		// check that the 'id' matches up with a rows in the databse
		if($rows) { // get data from db
			$period_id = $rows['period_id'];
			$period_code = $rows['period_code'];
			$period_from = date("Y-m-d", strtotime($rows['period_from']));
			$period_to = (empty($rows['period_to'])) ? "" : date("Y-m-d", strtotime($rows['period_to']));
			$active = $rows['active'];
		} else { // if no match, display result
			$errors[] = "No results!";
			echo "<p>". output_errors($errors);
			header('Location: periods_list.php');
			die();
		}
		include 'core/database/close.php' ;
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
	
	if (isset($_POST['interval'])) {
		$period_from = check_input($_POST['period_from']);
		$period_to = check_input($_POST['period_to']);
		
		$datetime1 = new DateTime($period_from);
		$datetime2 = new DateTime($period_to);
		$interval = $datetime1->diff($datetime2);
		$woweekends = 0;
		for($i=0; $i<$interval->d; $i++){
			$modify = $datetime1->modify('+1 day');
			$weekday = $datetime1->format('w');
			
			if($weekday != 0){ // 0 for Sunday and 6 for Saturday
				$woweekends++;  
			}
		}
		$messages[] = "<span class='fg-color-green bold'>" . $woweekends . "</span>";
	}
  
	if (isset($_POST['update'])) {
		$period_id = check_input($_POST['period_id']);
		$period_code = check_input(strtoupper($_POST['period_code']));
		$period_from = check_input($_POST['period_from']);
		$period_to = check_input($_POST['period_to']);
		$from =  date("Y-m-d", strtotime($period_from));
		$to = (empty($period_to)) ? "" : date("Y-m-d", strtotime($period_to));
		$datetime1 = new DateTime($period_from);
		$datetime2 = new DateTime($period_to);
		$interval = $datetime1->diff($datetime2);
		$woweekends = 0;
		for($i=0; $i<$interval->d; $i++){
			$modify = $datetime1->modify('+1 day');
			$weekday = $datetime1->format('w');
			
			if($weekday != 0){ // 0 for Sunday and 6 for Saturday
				$woweekends++;  
			}
		}
		$active = (empty($_POST['active'])) ? "0" : "1";
	
		if (empty($period_code) === true) {
			$errors[] = "Period code is required!";
		}
		
		if (date("Y-m-d", strtotime($period_from)) > date("Y-m-d", strtotime($period_to)) && !empty($date_from) && !empty($period_to)) {
			$errors[] = "Invalid range period!";
		}
		
		if (period_code_exists($period_code, $period_id) === true) {
			$errors[] = "Period code already exist!";
		}
		
		
		if (empty($errors) && empty($from)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "UPDATE periods SET period_code='$period_code', period_from=NULL, period_to='$to', active='" . $active . "' WHERE period_id='$period_id'"); 
			include 'core/database/close.php' ;
			header ("Location: periods_list.php?page=$page&text_search=$text_search");
			exit();
		}
		
		if (empty($errors) && empty($to)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "UPDATE periods SET period_code='$period_code', period_from='$from', period_to=NULL, active='" . $active . "' WHERE period_id='$period_id'"); 
			include 'core/database/close.php' ;
			header ("Location: periods_list.php?page=$page&text_search=$text_search");
			exit();
		}
		
		if (empty($errors) && !empty($from) && !empty($to)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "UPDATE periods SET period_code='$period_code', period_from='$from', period_to='$to', period_interval='$woweekends', active='" . $active . "' WHERE period_id='$period_id'"); 
			include 'core/database/close.php' ;
			header ("Location: periods_list.php?page=$page&text_search=$text_search");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header ("Location: periods_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="periods_list.php" style="text-decoration:none;">&#8656;</a> Update Period</h1>
	<form action="" method="post">
<?php
	echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
	echo (isset($messages) && !empty($messages)) ? output_messages($messages) : "";
?>
		<input type="hidden" name="period_id" class="text" value="<?php echo $period_id; ?>" />
		<input type="hidden" name="page" class="text" value="<?php echo $page; ?>" />
		<input type="hidden" name="text_search" class="text" value="<?php echo $text_search; ?>" />
		<label for="period_code" class="span3">Period code <i>(mmAyyyy or mmByyyy)</i> <span class="fg-color-red">*</span></label>
		<input type="text" name="period_code" id="period_code" class="span1" value="<?php echo (isset($period_code)) ? $period_code : ""; ?>" placeholder="mmAorByyyy" required="required" /><br /><br />
		<label for="period_from" class="span3">Period from <i>(mm/dd/yyy)</i> <span class="fg-color-red">*</span></label>
		<input type="date" name="period_from" id="period_from" class="span2" value="<?php echo (isset($period_from)) ? $period_from : ""; ?>" placeholder="mm/dd/yyyy" required="required" /><br /><br />
		<label for="period_to" class="span3">Period to <i>(mm/dd/yyy)</i> <span class="fg-color-red">*</span></label>
		<input type="date" name="period_to" id="period_to" class="span2" value="<?php echo (isset($period_to)) ? $period_to : ""; ?>" placeholder="mm/dd/yyyy" required="required" /><br /><br />
		<label for="active" class="span3">Active</label>
		<input type="checkbox" name="active" id="active" <?php echo ($active==="1") ? "checked" : ""; ?> /><br /><br /><input type="submit" name="update" value="Update" />
		<input type="submit" name="interval" value="Interval" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>