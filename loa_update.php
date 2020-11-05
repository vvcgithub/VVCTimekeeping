<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	
	if (isset($_GET['loa_id'])) {
		$loa_id = check_input( $_GET['loa_id']);
		$loa_employee_id_no = $employee_data['employee_id_no'];
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT * FROM loa WHERE loa_id = $loa_id AND loa_employee_id_no = $loa_employee_id_no"); 
		$rows = $query->fetch_assoc();

		// check that the 'id' matches up with a rows in the databse
		if($rows) { // get data from db
			$loa_id = $rows['loa_id'];
			$loa_employee_id_no = $rows['loa_employee_id_no'];
			$loa_period_code = $rows['loa_period_code'];
			$loa_sl = $rows['loa_sl']; 
			$loa_vl= $rows['loa_vl']; 
			$loa_lwop = $rows['loa_lwop']; 
			$loa_mpaternity = $rows['loa_mpaternity']; 
		}
		include 'core/database/close.php' ;
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
  
	if (isset($_POST['update'])) {
		$loa_period_code = check_input($_POST['loa_period_code']);
		$loa_employee_id_no = $employee_data['employee_id_no'];
		$loa_sl = check_input($_POST['loa_sl']);
		$loa_vl = check_input($_POST['loa_vl']);
		$loa_lwop = check_input($_POST['loa_lwop']);
		$loa_mpaternity = check_input($_POST['loa_mpaternity']);

		if (empty($loa_period_code) === true) {
			$errors[] = "Period is required!";
		}
		
		//if (loa_period_code_exists_update($loa_period_code, $loa_id) === true && !empty($loa_period_code))  {
		//	$errors[] = "Period already exist!";
		//}

		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$mysqli->query("UPDATE loa SET loa_period_code='$loa_period_code'
			, loa_employee_id_no='$loa_employee_id_no'
			, loa_sl='$loa_sl'
			, loa_vl='$loa_vl'
			, loa_lwop='$loa_lwop'
			, loa_mpaternity='$loa_mpaternity'
			WHERE loa_id='$loa_id'"); 
			include 'core/database/close.php' ;
			header('Location: loa_list.php');
			exit();
		} 
	}

	if (isset($_POST['close'])) {
		header ("Location: loa_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="loa_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Update Leave</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="loa_period_code" class="span1">Period <span class="fg-color-red">*</span></label>
		<select name="loa_period_code" id="loa_period_code">
			<option class="span_auto" value="" class="span4">- Select period -</option>
<?php
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM periods ORDER BY period_id DESC");
			while($rows = $query->fetch_assoc()) {
				$period_code = $rows['period_code'];
				$period_from = $rows['period_from'];
				$period_to = $rows['period_to'];
				
?>
				<option class="span_auto" value="<?php echo $period_code; ?>" <?php if (isset($loa_period_code) && ($loa_period_code === $period_code)) { echo "selected"; } ?>><?php echo $period_code; ?></option>
<?php
			}
			include 'core/database/close.php' ;
?>	
		</select><br /><br />
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
	include 'includes/overall/footer.php' ; 
?>