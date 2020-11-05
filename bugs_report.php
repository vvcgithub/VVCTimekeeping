<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	
	
	if (isset($_POST['cancel'])) {
		header ("Location: index.php");
		exit();
	}
  
	if (isset($_POST['report'])) {
		$bug_employee_id_no = $employee_data['employee_id_no'];
		$bug_description = check_input($_POST['bug_description']);

			
		if (empty($bug_description) === true) {
			$errors[] = "Description is required!";
		}
		
		if (empty($errors)) {
				include 'core/database/connect.php' ;
				$mysqli->query("INSERT INTO bugs (bug_employee_id_no, bug_description) 
				VALUES (
				'$bug_employee_id_no', 
				'$bug_description'
				)"); 
				include 'core/database/close.php' ;
			
				header('Location: bugs_list.php');
				exit();
			} 
	}

?>
	<h1><a href="bugs_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Report Bug</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<textarea name="bug_description" id="bug_description" class="span8" maxlength="300" placeholder="Report a bug..." ></textarea>
		<br />
		<input type="submit" name="report" value="Report" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>