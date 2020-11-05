<?php
function count_comments() {
	$query = mysql_query("SELECT COUNT(comment_id) as count_comment_id FROM comments");
	$row = mysql_fetch_array($query);
	$count_comment_id = $row['count_comment_id'];
	return ($count_comment_id > 0) ? $count_comment_id : 0;
}
?>