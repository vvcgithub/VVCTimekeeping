<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_clients($employee_data['employee_id']) != 2 && privilege_clients($employee_data['employee_id']) != 4) {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['client_id'])) {
		$client_id = $_GET['client_id'];
		$query = $mysqli->query("SELECT * FROM clients WHERE client_id=$client_id"); 
		$rows = $query->fetch_assoc();
		if($rows) { 
			$client_code = $rows['client_code'];
			$client_name = $rows['client_name'];
			$active = $rows['active'];
		}
	} else {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
  
	if (isset($_POST['update'])) {
		//$client_id = check_input($_POST['client_id']);
		$client_code = check_input(strtoupper($_POST['client_code']));
		$client_name = check_input($_POST['client_name']);
		$active = (empty($_POST['active'])) ? "0" : "1";

		if (empty($client_code) === true) {
			$errors[] = "Code is required!";
		}
		
		if (empty($client_name) === true) {
			$errors[] = "Name is required!";
		}
		
		if (client_code_exists($client_code, $client_id) === true) {
			$errors[] = "Code already exist!";
		}

		if (empty($errors)) {
			$mysqli->query("UPDATE clients SET client_code='$client_code'
			, client_name='$client_name'
			, active='$active'
			WHERE client_id='$client_id'"); 
			header ("Location: clients_list.php?page=$page&text_search=$text_search");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header ("Location: clients_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="clients_list.php" style="text-decoration:none;">&#8656;</a> Update Client</h1>
	<form action="" method="post">
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
		<label for="client_code" class="span1">Code <span class="fg-color-red">*</span></label>
		<input type="text" name="client_code" id="client_code" class="span2" value="<?php echo (isset($client_code)) ? $client_code : ""; ?>" /><br /><br />
		<label for="client_name" class="span1">Name <span class="fg-color-red">*</span></label>
		<input type="text" name="client_name" id="client_name" class="span4" value="<?php echo (isset($client_name)) ? $client_name : ""; ?>" /><br /><br />
		<label for="active" class="span1">Active</label>
		<input type="checkbox" name="active" id="active" <?php echo ($active==="1") ? "checked" : ""; ?> /><br /><br />
		<input type="submit" name="update" value="Update" />
	</form>
<?php
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>