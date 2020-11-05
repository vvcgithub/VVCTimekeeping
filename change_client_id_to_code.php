<?php
$query = mysql_query("UPDATE logs INNER JOIN employees ON logs.log_employee_id = employees.employee_id SET logs.log_employee_id = employees.employee_id_no");
?>