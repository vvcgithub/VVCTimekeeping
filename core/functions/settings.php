<?php
function settings($field) {
	include 'core/database/connect.php' ; 
	$query = mysqli_query($mysqli, "SELECT $field FROM settings");
	$row = mysqli_fetch_array($query);
	return $row["$field"];
	echo $field;
}
?>
