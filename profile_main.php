<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
?>

	<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Profile</h1>
	<ul>
		<li><a href="profile.php" class="link_new padding10 span3" style="text-decoration:none;">Edit profile</a></li>
		<li><a href="change_password.php" class="link_new padding10 span3" style="text-decoration:none;">Change password</a></li>
	</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>