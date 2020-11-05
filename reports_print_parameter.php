<?php
	include 'core/init.php' ; 
	include 'core/database/connect.php' ;
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;

	if (isset($_POST['view'])) {
		$period_code = $_POST['period_code'];
		
		if (empty($period_code)) {
			$errors[] = "Period is required!";
		}
		
		if (empty($errors)) {
			$employee_id_no = $employee_data['employee_id_no'];
			header("Location: reports_print.php?employee_id_no=$employee_id_no&period_code=$period_code");
		}
	}
	
	if (isset($_POST['close'])) {
		header("Location: index.php");
		exit();
	}	
?>	
	<h1>Print Report</h1>
	<form action="" method="post">
<?php
	echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="period_code" class="span1">Period  <span class="fg-color-red">*</span></label>
		<select name="period_code" id="period_code">
			<option class="span_auto" value="">- Select period -</option>
<?php
			$query = $mysqli->query("SELECT period_id, period_code FROM periods ORDER BY period_id DESC");
			while($rows = $query->fetch_assoc()) {
				$code = $rows['period_code'];
				
?>
				<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code) && ($period_code === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
			}
?>	
		</select>
		<br /><br />
		<input type="submit" name="view" value="View" />
		<input type="submit" name="close" value="Close" />
	</form>
	
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>