<?php
$serverName = "SAPSERVER"; //serverName\instanceName
$connectionInfo = array( "Database"=>"time_keeping", "UID"=>"user1", "PWD"=>"Password1");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>