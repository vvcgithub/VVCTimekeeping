<?php

function sanitize($data) {
	//return stripslashes(mysqli_real_escape_string($con, $data));
}

function toupper_trim($data) {
	return strtoupper(trim($data));
}

function tolower_trim($data) {
	return strtolower(trim($data));
}

function to_trim($data) {
	return trim($data);
}

function output_errors($errors) {
	return '<ul class="errors"><li>' . implode('</li><li>', $errors) . '</li></ul>';
}

function output_messages($message) {
	return '<ul class="message"><li>' . implode('</li><li>', $message) . '</li></ul>';
}

function javascript_setfocus() {
	return "onfocus='border_setfocus(this)' onblur='border_default(this)'";
}

function check_input($value) {
	include 'core/database/connect.php' ;
	// Stripslashes
	if (get_magic_quotes_gpc())
	  {
	  $value = stripslashes($value);
		
	  }
	// Quote if not a number
	if (!is_numeric($value))
	  {
	  $value = $mysqli->real_escape_string(stripslashes(str_replace(array("\r","\n",'\r','\n'),'', $value)));
	  
	  }
	return $value;
}

function check_input_file($value) {
	include 'core/database/connect.php' ;
	// Stripslashes
	if (get_magic_quotes_gpc())
	  {
	  $value = stripslashes($value);
		
	  }
	// Quote if not a number
	if (!is_numeric($value))
	  {
	  $value = $mysqli->real_escape_string(str_replace(array("\r","\n",'\r','\n'),'', $value));
	  
	  }
	return $value;
}
?>