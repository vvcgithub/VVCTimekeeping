<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	if(isset($_GET['period_code'])) { 
		$period_code = $_GET['period_code'];
	}
	
	if (isset($_POST['export'])) {
		$period_code = $_POST['period_code'];
		$date_from = period_from($period_code);
		$date_to = period_to($period_code);
		
		if (empty($period_code)) {
			$errors[] = "Period is required!";
		}
		
		if (empty($date_from) && !empty($period_code)) {
			$errors[] = "Null date from from selected period!";
		}
		
		if (empty($date_from) && !empty($period_code)) {
			$errors[] = "Null date to from selected period!";
		}
		
		if (empty($errors)) {
			header("Location: biometrics_export.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: biometrics_main.php");
		exit();
	}	
?>	
	<h1><a href="biometrics_main.php" style="text-decoration:none;" title="Back">&#8656;</a> Export Biometrics</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="period_code" class="span1">Period <span class="fg-color-red">*</span></label>
		<select name="period_code" id="period_code">
			<option value="" class="span_auto">- Select period -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM periods ORDER BY period_id DESC");
			while($rows = mysqli_fetch_array($query)) {
				$code = $rows['period_code'];
?>
				<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code) && ($period_code === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
			}
			
			include 'core/database/close.php' ;
?>		
		</select><br /><br />
		<input type="submit" name="export" value="Export" />
		<input type="submit" name="close" value="Close" />
	</form>
<?php
	
	include 'includes/overall/footer.php' ; 
?>