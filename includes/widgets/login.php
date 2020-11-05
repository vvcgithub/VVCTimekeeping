<div class="widget">  
    <h2>Log in</h2>
        <form action="login.php" method="post">
<?php
			echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
			<br />
			<label for="employee_username" class="span1">Username</label>
			<input type="text" name="employee_username" id="employee_username" class="span2" required="required"><br /><br />
			<label for="employee_password" class="span1">Password</label>
			<input type="password" name="employee_password" id="employee_password" class="span2" required="required"><br /><br />
			<input type="submit" value="Log in">
		</form>
		
</div>