<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;

	
	
	if (isset($_POST['view'])) {
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors[] = "Period is required!";
		}
		
		if (empty($errors)) {
			header("Location: reports_summary.php?period_code=$period_code");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: reports_main.php");
		exit();
	}	
?>	
	<h1>View Time Report</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="period_code" class="span1">Period <span class="fg-color-red">*</span></label>
		<select name="period_code" id="period_code">
			<option class="span_auto" value="">- Select period -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM periods ORDER BY period_code DESC");
			while($rows = mysqli_fetch_array($query)) {
				$period_code = $rows['period_code'];
				$period_from = $rows['period_from'];
				$period_to = $rows['period_to'];
				
?>
				<option class="span_auto" value="<?php echo $period_code; ?>"><?php echo $period_code; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>	
		</select><br /><br />
		<input type="submit" name="view" value="View" />
		<input type="submit" name="close" value="Close" />
	</form>
	
<?php
	
	include 'includes/overall/footer.php' ; 
?>