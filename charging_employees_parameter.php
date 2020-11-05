<?php
	include 'core/init.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['view'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$date_from = date("Y-m-d", strtotime($from));
		$date_to = date("Y-m-d", strtotime($to));
		
		if (empty($from)) {
			$errors[] = "Date from is required!";
		}
		
		if (empty($to)) {
			$errors[] = "Date to is required!";
		}
		
		
		if (empty($errors)) {
			header("Location: charging_employees.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: charging_main.php");
		exit();
	}	
?>	
	<h1><a href="charging_main.php" style="text-decoration:none;" title="Back">&#8656;</a> Employee Charging</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="date_from" class="span1">Date from <span class="fg-color-red">*</span></label>
		<input type="date" id="from" name="from" maxlength="10" class="span2" value="<?php echo (isset($from)) ? $from : "" ; ?>" placeholder="mm/dd/yyyy" />
		<br /><br />
		<label for="date_to" class="span1">Date to <span class="fg-color-red">*</span></label>
		<input type="date" id="to" name="to" maxlength="10" class="span2" value="<?php echo (isset($to)) ? $to : "" ; ?>" placeholder="mm/dd/yyyy" />
		<br /><br />
		<input type="submit" name="view" value="View" />
	</form>
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>