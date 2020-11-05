<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['post'])) {
		$period_code = $_POST['period_code'];
		$empty_period = (empty($period_code)) ? "Period is required!" : "" ;
		
		if (empty($period_code)) {
			$errors[] = "Period is required!";
		}
		
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT COUNT(log_check) AS count_log_check FROM logs WHERE log_check = 1");
		$rows = $query->fetch_assoc();
		
		if ($rows['count_log_check'] == 0 && !empty($period_code)) {
			$errors[] = "Empty checked logs!";
		}
		include 'core/database/close.php' ;
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$mysqli->query("INSERT INTO logs_post (log_employee_id_no, log_period_code, log_date, log_in, log_out, log_client_code, log_regular, log_late, log_undertime, log_a, log_b, log_c, log_d, log_e, log_f, log_g, log_h, log_i, log_description, log_check) SELECT log_employee_id_no, log_period_code, log_date, log_in, log_out, log_client_code, log_regular, log_late, log_undertime, log_a, log_b, log_c, log_d, log_e, log_f, log_g, log_h, log_i log_description, log_check FROM logs WHERE log_period_code = '$period_code' AND log_check = 1");
			$mysqli->query("INSERT INTO loa_post (loa_employee_id_no, loa_period_code, loa_sl, loa_vl, loa_lwop, loa_mpaternity, loa_check) SELECT loa_employee_id_no, loa_period_code, loa_sl, loa_vl, loa_lwop, loa_mpaternity, loa_check FROM loa WHERE loa_period_code = '$period_code' AND loa_check = 1");			
			$mysqli->query("DELETE FROM logs WHERE log_period_code = '$period_code' AND log_check = 1");
			$mysqli->query("DELETE FROM loa WHERE loa_period_code = '$period_code' AND loa_check = 1");
			include 'core/database/close.php' ;
			header("Location: index.php");
			exit();
		} 
	}
?>	
	<div class="bg-color-lightgreen padding10">
		<h1><a href="reviews_main.php" style="text-decoration:none;">&#8656;</a> Post Logs</h1>
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
				$query = $mysqli->query("SELECT * FROM periods WHERE active = 1 ORDER BY period_code DESC");
				while($rows = $query->fetch_assoc()) {
					$code = $rows['period_code'];
?>
					<option class="span_auto" value="<?php echo $code; ?>" <?php if (isset($period_code) && ($period_code === $code)) { echo "selected"; } ?>><?php echo $code; ?></option>
<?php
				}
				include 'core/database/close.php' ;
?>	
			</select><br /><br />
			<span class="bold">Note: Clicking the Post button after selecting the period will transfer checked logs to logs_post.</span><br /><br />
			<input type="submit" name="post" value="Post" />
		</form>
	</div>
<?php
	include 'includes/overall/footer.php' ; 
?>