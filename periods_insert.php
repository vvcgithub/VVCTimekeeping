<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
  
	if (isset($_POST['insert'])) {
		$period_code = check_input(strtoupper($_POST['period_code']));
		$period_from = check_input($_POST['period_from']);
		$period_to = check_input($_POST['period_to']);
		$from =  date("Y-m-d", strtotime($period_from));
		$to = (empty($period_to)) ? "" : date("Y-m-d", strtotime($period_to));
		$datetime1 = new DateTime($period_from);
		$datetime2 = new DateTime($period_to);
		$interval = $datetime1->diff($datetime2);
		$interval = $interval->format('%a');
		$active = (empty($_POST['active'])) ? "0" : "1";
		
		if (empty($period_code) === true) {
			$errors[] = "Period code is required!";
		}
		
		if (date("Y-m-d", strtotime($period_from)) > date("Y-m-d", strtotime($period_to)) && !empty($date_from) && !empty($period_to)) {
			$errors[] = "Invalid range period!";
		}
		
		if (period_code_exists($period_code, "") === true) {
			$errors[] = "Period code already exist!";
		}
		
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			mysqli_query($mysqli, "INSERT INTO periods (period_code, period_from, period_to, period_interval) 
			VALUES (
			'$period_code', 
			'" . date("Y-m-d", strtotime($period_from)) . "',
			'" . date("Y-m-d", strtotime($period_to)) . "',
			'" . $interval . "'
			)"); 
			include 'core/database/close.php' ;
			header('Location: periods_list.php');
			exit(); 
		}  
	}
	
	if (isset($_POST['close'])) {
		header ("Location: periods_list.php?page=1&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="periods_list.php" style="text-decoration:none;">&#8656;</a> Insert Period</h1>
	<form action="" method="post">
<?php
	echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="period_code" class="span3">Period code <i>(mmAyyyy or mmByyyy)</i> <span class="fg-color-red">*</span></label>
		<input type="text" name="period_code" id="period_code" class="span1" value="<?php echo (isset($period_code)) ? $period_code : ""; ?>" placeholder="mmAorByyyy" required="required" /><br /><br />
		<label for="period_from" class="span3">Period from <i>(mm/dd/yyy)</i> <span class="fg-color-red">*</span></label>
		<input type="date" name="period_from" id="period_from" class="span2" value="<?php echo (isset($period_from)) ? $period_from : "" ; ?>" placeholder="mm/dd/yyyy" required="required" /><br /><br />
		<label for="period_to" class="span3">Period to <i>(mm/dd/yyy)</i> <span class="fg-color-red">*</span></label>
		<input type="date" name="period_to" id="period_to" class="span2" value="<?php echo (isset($period_to)) ? $period_to : "" ; ?>" placeholder="mm/dd/yyyy" required="required" /><br /><br />
		<input type="submit" name="insert" value="Insert" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>