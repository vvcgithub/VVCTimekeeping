<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ; 
	
	$ip = 'google.com'; //some ip
	exec("ping -n 4 $ip 2>&1", $output, $retval);
	if ($retval != 0) { 
		echo "No internet connection to view Villaruz Calendar!";
	} else {
?>
	<iframe src="<?php echo settings('calendar'); ?>" width="800" height="400" frameborder="0" scrolling="no"></iframe>
<?php
	}
	include 'includes/overall/footer.php' ; 
?>