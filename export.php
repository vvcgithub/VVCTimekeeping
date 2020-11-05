<?php
$dbhost ="localhost";
$dbname = "time_keeping";
$dbuser = "arwin";
$dbpass = "winsers17";

$backupFile = $dbname . date("Y-m-d-H-i-s") . '.gz';
$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass $dbname | gzip > $backupFile";
system($command);



?>
