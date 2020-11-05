<?php
	include 'core/init.php' ; 
	protected_page();
	include 'includes/overall/header.php' ; 

if (empty($_POST) === false) { //means form has been submitted
	// array('username', 'password', ...) means the name of all textbox required
	$required_fields = array('id_no', 'username', 'password', 'password_again', 'last_name', 'first_name', 'middle_name', 'email', 'account_type');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}
	}	
	
	if (empty($errors) === true){
		if (user_exists($_POST['username']) === true) {
			$errors[] = 'Sorry, the username \'' . $_POST['username'] . '\' is already taken.';
		} 
		if (preg_match("/\\s/", $_POST['username']) == true) {
			$errors[] = 'Your username must not contain any spaces';
		}
		if (strlen($_POST['password']) < 6) {
			$errors[] = 'Your password must be at least 6 characters';
		}
		if ($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Your password do not match';
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid email address is required';
		}
		if (email_exists($_POST['email']) === true) {
			$errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use.';
		} 
	}
}
?>

<h1>Register</h1>

<?php
	if (isset($_GET['success']) && empty($_GET['success'])) {  //sucess='anything' means it is not empty
		echo 'You\'ve been registered successfully';
		include 'includes/overall/footer.php' ;
		die();
	} else {
		if (empty($_POST) === false && empty($errors) === true) {
			$register_data = array(
				'id_no' 		 => $_POST['id_no'],
				'username' 		 => $_POST['username'],
				'password' 		 => $_POST['password'],	
				'last_name' 	 => $_POST['last_name'],
				'first_name' 	 => $_POST['first_name'],
				'middle_name'	 => $_POST['middle_name'],
				'email'			 => $_POST['email'],
				'account_type'	 => $_POST['account_type']
			);
			register_user($register_data);
			header('Location: register.php?success');
			exit();
			
		} else {
			echo output_errors($errors);
		}	
?>
		<form action="" method="post">
			<ul>
				<li>
					ID number*:<br />
					<input type="text" name="id_no">
				</li>
				<li>
					Username*:<br />
					<input type="text" name="username">
				</li>
				<li>
					Password*:<br />
					<input type="password" name="password">
				</li>
				<li>
					Password again*:<br />
					<input type="password" name="password_again">
				</li>
				<li>
					Last name*:<br />
					<input type="text" name="last_name">
				</li>
				<li>
					First name*:<br />
					<input type="text" name="first_name">
				</li>
				<li>
					Middle name*:<br />
					<input type="text" name="middle_name">
				</li>
				<li>
					Email*:<br />
					<input type="text" name="email">
				</li>
				<li>
					Account type*:<br />
					<select name="account_type">
						<option value="">- Select account type -</option>
						<option value="administrator">Administrator</option>
						<option value="manager">Manager</option>
						<option value="user">User</option>
					</select>
				</li>
				<li>
					<input type="submit" value="Register">
				</li>
			</ul>
		</form>
<?php 
		}
include 'includes/overall/footer.php'; ?>