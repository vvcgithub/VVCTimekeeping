<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['delete_selected'])) {
		include 'core/database/connect.php' ;
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			$mysqli->autocommit(FALSE);
			foreach ($unique_id as $id => $value) {
				$mysqli->query("DELETE FROM logs WHERE log_id = '$value' AND log_check = 0");
			}
			header("Location: logs_list.php?page=$current_page");
			exit();
		}
		include 'core/database/close.php' ;
	}
	
	if (isset($_POST['cancel'])) {
		header('Location: logs_list.php?page=1');
		exit();
	}
?>
	<div class="bg-color-salmon padding10">
		<h1>Log List</h1>
		<form action="" method="post">
			<input type="text" name="delete_all" value="Delete all" />
			<p class="bold">Delete all unchecked logs?</p>
			<input type="submit" name="delete_all" value="Delete all" />
			<input type="submit" name="cancel" value="Cancel" />
		</form>
	</div>

<?php
	include 'includes/overall/footer.php' ; 
?>