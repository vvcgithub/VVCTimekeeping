<aside>
	<?php
	if (logged_in() === true ) {
		include 'includes/widgets/loggedin.php' ;
		include 'includes/widgets/template_reference.php' ;
		include 'includes/widgets/employees_count.php' ;
	} else {
		include 'includes/widgets/login.php' ;
		include 'includes/widgets/employees_count.php' ;
	}
	
	?>
</aside>