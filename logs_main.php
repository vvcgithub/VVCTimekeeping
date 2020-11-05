<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['back'])) {
		header("Location: maintain.php");
		exit();
	}
?>

	<h1><a href="tools.php" style="text-decoration:none;" title="Back">&#8656;</a> Log</h1>
	<form action="" method="post">
		<ul>
			<li><a href="logs_import_employees.php" class="link_new padding10 span2" style="text-decoration:none;">Import logs employees</a></li>
			<li><a href="logs_empty.php" class="link_new padding10 span2" style="text-decoration:none;">Empty logs</a></li>
			<br />
		</ul>
	</form>
<?php
	include 'includes/overall/footer.php' ; 
?>