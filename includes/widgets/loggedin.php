<?php
	if (isset($_POST['change'])) {
		$current_password_query = $employee_data['employee_password'];
		$employee_id_query = $employee_data['employee_id'];
		$current_password = check_input($_POST['current_password']);
		$new_password = check_input($_POST['new_password']);
		$new_password_again = check_input($_POST['new_password_again']);
	
		if (empty($current_password) === true) {
			$errors[] = "Current password is required!";
		}
		
		if (md5($current_password) != $current_password_query && empty($new_password) === false) {
			$errors[] = "Wrong current password!";
		}
		
		if (empty($new_password) === true) {
			$errors[] = "New password is required!";
		}
		
		if (strlen($new_password) < 6 && empty($new_password) === false) {
			$errors[] = "Minimum of 6 characters!";
		}
		
		if ($new_password != $new_password_again) {
			$errors[] = "Password not match!";
		}
		
		if (empty($errors)) {
			include 'core/database/connect.php' ;
			$mysqli->query("UPDATE employees SET employee_password='" . md5($new_password) ."' WHERE employee_id='$employee_id_query'"); 
			include 'core/database/close.php' ;
			header("Location: index.php#close");
			exit();
		}
	}
?>

<div class = "widget">
	<h2>Profile</h2>
	<div class = "inner">
		<ul>
			<li><a href="profile.php" class="link_new_light padding10" style="text-decoration:none; width:90%;">Edit Profile</a></li>
			<li><a href="change_password.php" class="link_new_light padding10" style="text-decoration:none; width:90%;">Change Password</a></li>
		</ul>
	</div>
</div>
<div id="change_password" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h2>Change Password</h2>
		<form action="" method="post">
<?php
			echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
			<br />
			<label for="current_password" class="span2">Current password <span class="fg-color-red">*</span></label>
			<input type="password" name="current_password" id="current_password" class="span2" /><br /><br />
			<label for="new_password" class="span2">New password <span class="fg-color-red">*</span></label>
			<input type="password" name="new_password" id="new_password" class="span2" /><br /><br />
			<label for="new_password_again" class="span2">New password again <span class="fg-color-red">*</span></label>
			<input type="password" name="new_password_again" id="new_password_again" class="span2" /><br /><br />
			<input type="submit" name="change" value="Change" />
		</form>
	</div>
</div>

