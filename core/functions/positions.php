<?php
function position_count() {
	return mysql_result(mysql_query("SELECT count(position_id) FROM positions"), 0);
}

function position_code_exists($position_code, $position_id) {
	include 'core/database/connect.php' ;
	$position_code = check_input($position_code);
	$position_id = check_input($position_id);
	$query = $mysqli->query("SELECT COUNT(position_id) AS count_position FROM positions WHERE position_code = '$position_code' and position_id != '$position_id'");
	$rows = $query->fetch_assoc();
	return ($rows['count_position'] == 1) ? true : false;
	include 'core/database/close.php' ;
}

function position_name($position_id) {
	$position_id = mysql_real_escape_string($position_id);
	$row = mysql_fetch_array(mysql_query("SELECT position_name FROM positions WHERE position_id = '$position_id'")); 
	return $row['position_name'];
}

function position_id($position) {
	include 'core/database/connect.php' ;
	$position = check_input($position);
	$query = $mysqli->query("SELECT position_id FROM positions WHERE position_code LIKE '%$position%' OR position_name LIKE '%$position%' OR position_rate LIKE '%$position%'"); 
	$rows = $query->fetch_assoc();
	return $rows['position_id'];
	include 'core/database/close.php' ;
}

function position_rate($position_code) {
	include 'core/database/connect.php' ;
	$position_code = check_input($position_code);
	$query = $mysqli->query("SELECT position_rate FROM positions WHERE position_code = '$position_code'"); 
	$rows = $query->fetch_assoc();
	return $rows['position_rate'];
	include 'core/database/close.php' ;
}
?>
