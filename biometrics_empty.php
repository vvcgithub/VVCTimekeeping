<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/logged_in.php' ;

	if (isset($_POST['empty'])) {
		include 'core/database/connect.php' ;
		$mysqli->query("TRUNCATE TABLE biometrics");
		include 'core/database/close.php' ;
		header("Location: biometrics_list.php");
		exit();
	}
	
	if (isset($_POST['cancel'])) {
		header("Location: biometrics_list.php");
		exit();
	}
?>
	<div class="bg-color-salmon padding10">
		<h1>Empty Biometrics</h1>
		<p>Are you sure you want to empty biometrics table? If yes, select the [Empty] button.</p>
		<form action="" method="post" enctype="multipart/form-data">
			<input type="submit" name="empty" value="Empty" />
			<input type="submit" name="cancel" value="Cancel" />
		</form>
	</div>
<?php
	include 'includes/overall/footer.php' ; 
?>