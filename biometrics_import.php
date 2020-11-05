<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;

	if (isset($_POST['import'])) {
		$temp = $_FILES['upfile']['tmp_name'];
		$file = check_input_file($temp);
		include 'core/database/connect.php' ;
		$mysqli->query("LOAD DATA INFILE '" . $file . "' IGNORE INTO TABLE biometrics FIELDS TERMINATED BY ',' IGNORE 1 LINES 
		(@biometrics_id, @biometrics_date) SET biometrics_id = concat(STR_TO_DATE(@biometrics_date,'%c/%e/%Y %H:%i'), '-', @biometrics_id), biometrics_employee_id_no = @biometrics_id, biometrics_date = STR_TO_DATE(@biometrics_date,'%c/%e/%Y'), biometrics_time = TIME(STR_TO_DATE(@biometrics_date,'%c/%e/%Y %H:%i'))"); // format for military time '%h:%i %p'
		include 'core/database/close.php' ;
		header('Location: biometrics_list.php');
		exit();
	}
	
	if (isset($_POST['close'])) {
		header("Location: biometrics_list.php");
		exit();
	}
?>
	<h1><a href="biometrics_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Import Biometrics</h1>
	<form action="" method="post" enctype="multipart/form-data">
		<ul class="unstyled">
			<li>Browse .csv file type</li>
			<li class="input-control text span5">
				<input type="file" id="upfile" name="upfile" accept="text/csv" class="span4" />
			</li>
			<br />
			<li class="as-inline-block">
				<input type="submit" name="import" value="Import" />
			</li>
		</ul>
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>