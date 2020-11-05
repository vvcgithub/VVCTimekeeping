<?php
function bug_id($bug) {
	include 'core/database/connect.php' ;
	$bug = check_input($bug);
	$query = $mysqli->query("SELECT bug_id FROM bugs WHERE bug_id LIKE '%$bug%'"); 
	$rows = $query->fetch_assoc();
	return $rows['bug_id'];
	include 'core/database/close.php' ;
}
?>
