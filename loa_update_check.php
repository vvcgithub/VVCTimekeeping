<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	
	if (isset($_GET['employee_id_no'])) {
		$employee_id_no = $_GET['employee_id_no'];
	}
	
	if (isset($_GET['period_code'])) {
		$period_code = $_GET['period_code'];
	}
	
	if (isset($_POST['close'])) {
		header ("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
		exit();
	}
  
	if (isset($_POST['update'])) {
		$employee_id_no = check_input($_POST['employee_id_no']);
		$period_code = check_input($_POST['period_code']);
		$loa_sl = check_input($_POST['loa_sl']);
		$loa_vl = check_input($_POST['loa_vl']);
		$loa_lwop = check_input($_POST['loa_lwop']);
		$loa_mpaternity = check_input($_POST['loa_mpaternity']);
		

		if (empty($employee_id_no) === true) {
			$errors[] = "Period is required!";
		}
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$mysqli->query("UPDATE loa SET loa_period_code='$period_code'
			, loa_employee_id_no='$employee_id_no'
			, loa_sl='$loa_sl'
			, loa_vl='$loa_vl'
			, loa_lwop='$loa_lwop'
			, loa_mpaternity='$loa_mpaternity'
			WHERE loa_employee_id_no = '$employee_id_no' AND loa_period_code = '$period_code'"); 
			include 'core/database/close.php' ;
			header ("Location: reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code");
			exit();
		} 
	}
?>

<?php

	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM loa WHERE loa_employee_id_no = '$employee_id_no' AND loa_period_code = '$period_code'"); 
		
	if ($mysqli->affected_rows < 1) {
		echo "<h1>Preview Logs</h1>";
		$errors[] = "No record found!";
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<p>
		<form action="" method="post">
			<input type="submit" name="close" value="Back" />
		</form>
		</p>
<?php
	} else {
		$rows = $query->fetch_assoc();
		if($rows) { // get data from db
			$loa_sl = $rows['loa_sl'];
			$loa_vl = $rows['loa_vl'];
			$loa_lwop = $rows['loa_lwop'];
			$loa_mpaternity = $rows['loa_mpaternity'];
		}
		include 'core/database/close.php' ;
?>

		<h1><a href='<?php echo "reviews_check_report.php?employee_id_no=$employee_id_no&period_code=$period_code"; ?>' style="text-decoration:none;" title="Back">&#8656;</a> Update Leave</h1>
		<h2><?php echo complete_name_from_id_no($employee_id_no); ?></h2>
		<form action="" method="post">
<?php
			echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
			<br />
			<input type="hidden" name="employee_id_no" class="text" value="<?php echo $employee_id_no; ?>" />
			<input type="hidden" name="period_code" class="text" value="<?php echo $period_code; ?>" />
			<label for="loa_sl" class="span1">SL</label>
			<input type="number" step="any" name="loa_sl" id="loa_sl" class="span0" value="<?php echo (isset($loa_sl)) ? $loa_sl : ""; ?>" /><br /><br />
			<label for="loa_vl" class="span1">VL</label>
			<input type="number" step="any" name="loa_vl" id="loa_vl" class="span0" value="<?php echo (isset($loa_vl)) ? $loa_vl : ""; ?>" /><br /><br />
			<label for="loa_lwop" class="span1">LWOP</label>
			<input type="number" step="any" name="loa_lwop" id="loa_lwop" class="span0" value="<?php echo (isset($loa_lwop)) ? $loa_lwop : ""; ?>" /><br /><br />
			<label for="loa_mpaternity" class="span1">M/P-aternity</label>
			<input type="number" step="any" name="loa_mpaternity" id="loa_mpaternity" class="span0" value="<?php echo (isset($loa_mpaternity)) ? $loa_mpaternity : ""; ?>" /><br /><br />
			<input type="submit" name="update" value="Update" />
		</form>

<?php
	}
	include 'includes/overall/footer.php' ; 
?>