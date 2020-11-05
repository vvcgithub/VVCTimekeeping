<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && (int)privilege_clients($employee_data['employee_id']) === 7) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['close'])) {
		header ("Location: clients_list.php");
		exit();
	}
  
	if (isset($_POST['insert'])) {
		$client_code = check_input(strtoupper($_POST['client_code']));
		$client_name = check_input($_POST['client_name']);

		if (empty($client_code) === true) {
			$errors[] = "Code is required!";
		}
		
		if (empty($client_name) === true) {
			$errors[] = "Name is required!";
		}
		
		if (client_code_exists($client_code, "") === true) {
			$errors[] = "Code already exist!";
		}

		if (empty($errors)) {
			$mysqli->query("INSERT INTO clients (client_code, client_name) 
			VALUES (
			'$client_code', 
			'$client_name'
			)"); 
			header('Location: clients_list.php');
			exit();
		} 
	}

?>
	<h1><a href="clients_list.php" style="text-decoration:none;">&#8656;</a> Insert Client</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<br />
		<label for="client_code" class="span1">Code <span class="fg-color-red">*</span></label>
		<input type="text" name="client_code" id="client_code" class="span2" value="<?php echo (isset($client_code)) ? $client_code : ""; ?>" /><br /><br />
		<label for="client_name" class="span1">Name <span class="fg-color-red">*</span></label>
		<input type="text" name="client_name" id="client_name" class="span4" value="<?php echo (isset($client_name)) ? $client_name : ""; ?>" /><br /><br />
		<input type="submit" name="insert" value="Insert" />
	</form>

<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>