<?php
	if (logged_in() == false) {
		header('Location: index.php');
		exit();
	}
?>