<?php
function schedule_name($schedule_id) {
	$schedule_id = mysql_real_escape_string($schedule_id);
	$row = mysql_fetch_array(mysql_query("SELECT schedule_name FROM schedules WHERE schedule_id = '$schedule_id'"));
	return $row['schedule_name'];
}
?>
