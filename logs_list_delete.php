<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['delete_all'])) {
		$employee_id_no = $employee_data['employee_id_no'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM logs WHERE log_employee_id_no = '$employee_id_no' AND log_check = 0");
		include 'core/database/close.php' ;
		header('Location: logs_list.php?page=1');
		exit();
	}
	
	if (isset($_POST['cancel'])) {
		header('Location: logs_list.php?page=1');
		exit();
	}
?>
	<div class="padding10" style="border:3px solid orange; background:moccasin;">
		<h1>Log List</h1>
		<form action="" method="post">	
			<p>Delete all unchecked logs?</p>
			<input type="submit" name="delete_all" value="Delete all" />
			<input type="submit" name="cancel" value="Cancel" />
		</form>
	</div>

<?php
	include 'includes/overall/footer.php' ; 
?>