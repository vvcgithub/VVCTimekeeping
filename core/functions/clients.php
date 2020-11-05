<?php
function client_count() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT count(client_id) AS count_client FROM clients WHERE active = 1");
	$rows = $query->fetch_assoc();
	return $rows['count_client'];
	include 'core/database/close.php' ;
}

function client_code_exists($client_code, $client_id) {
	include 'core/database/connect.php' ;
	$client_code = check_input($client_code);
	$client_id = check_input($client_id);
	$query = $mysqli->query("SELECT COUNT(client_id) AS count_client FROM clients WHERE client_code = '$client_code' and client_id != '$client_id'");
	$rows = $query->fetch_assoc();
	return ($rows['count_client'] == 1) ? true : false;
	include 'core/database/close.php' ;
}

function client_list() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT client_name FROM clients");
	$rows = $query->fetch_assoc();
	return $rows['client_name'];	
	include 'core/database/close.php' ;
}

function client_name($client_id) {
	include 'core/database/connect.php' ;
	$client_id = check_input($client_id);
	$query = $mysqli->query("SELECT client_name FROM clients WHERE client_id = '$client_id'");
	$rows = $query->fetch_assoc();
	return $rows['client_name'];
	include 'core/database/close.php' ;
}

function client_name_from_code($client_code) {
	include 'core/database/connect.php' ;
	$client_code = check_input($client_code);
	$query = $mysqli->query("SELECT client_name FROM clients WHERE BINARY client_code = '$client_code'");
	$rows = $query->fetch_assoc();
	return $rows['client_name'];
	include 'core/database/close.php' ;
}

function client_code($client_id) {
	include 'core/database/connect.php' ;
	$client_id = check_input($client_id);
	$query = $mysqli->query("SELECT client_code FROM clients WHERE client_id = '$client_id'");
	$rows = $query->fetch_assoc();
	return $rows['client_code'];	
	include 'core/database/close.php' ;
}

function client_id($client) {
	include 'core/database/connect.php' ;
	$client = check_input($client);
	$query = $mysqli->query("SELECT client_id FROM clients WHERE client_code LIKE '%$client%' OR client_name LIKE '%$client%'"); 
	$rows = $query->fetch_assoc();
	return $rows['client_id'];
	include 'core/database/close.php' ;
}
?>
