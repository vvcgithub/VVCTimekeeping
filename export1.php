
<?php
	
	$out = '';

//Next let's initialize a variable for our filename prefix (optional).
	$filename_prefix = 'csv';
	$clientcode = $_POST['csv_clientcode'];
	$datefrom = $_POST['csv_datefrom'];
	$dateto = $_POST['csv_dateto'];
	
//Next we'll check to see if our variables posted and if they did we'll simply append them to out.
if (isset($_POST['csv_hdr'])) {
$out .= $_POST['csv_hdr'];
$out .= "";
}

if (isset($_POST['csv_output'])) {
$out .= $_POST['csv_output'];
$out .= "";
}
$contents ="#, Lastname, Firstname, Date, Regular, Rate, RC+OTC, Regular Cost, Overtime Cost, A, B, C, D, E, F, Description, \n";
			
//Now we're ready to create a file. This method generates a filename based on the current date & time.
$filename = $filename_prefix."_".date("Y-m-d_H-i",time());


//Generate the CSV file header
header("Content-type: application/vnd.ms-excel");
header("Content-Encoding: UTF-8");
header("Content-type: text/csv; charset=UTF-8");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");


echo "\xEF\xBB\xBF"; // UTF-8 BOM
//Print the contents of out to the generated file.

print $clientcode."\n\n";
print $datefrom." To ".$dateto."\n";
print $contents."\n";
print $out;

//Exit the script
exit;

?>

