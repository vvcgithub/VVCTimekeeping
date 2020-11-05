<?php
function news_count() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT count(news_id) AS news_count FROM news");
	$rows = $query->fetch_assoc();
	return $rows['news_count'];
	include 'core/database/close.php' ;
}

function news_date() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT news_date FROM news ORDER BY news_id DESC LIMIT 1"); 
	$rows = $query->fetch_assoc();
	return $rows['news_date'];
	include 'core/database/close.php' ;
}

function news_title() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT news_title FROM news ORDER BY news_id DESC LIMIT 1"); 
	$rows = $query->fetch_assoc();
	return $rows['news_title'];
	include 'core/database/close.php' ;
}		

function news_description() {
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT news_description FROM news ORDER BY news_id DESC LIMIT 1"); 
	$rows = $query->fetch_assoc();
	return $rows['news_description'];
	include 'core/database/close.php' ;
}	

?>

