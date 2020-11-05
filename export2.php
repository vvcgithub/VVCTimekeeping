<?php
	

	require_once dirname(__FILE__) . '/PHPexcel/Classes/PHPExcel.php';
	
	
	//---INITIALIZE VALUE----//
	$client_code = $_POST['csv_clientcode'];
	$arr = explode(' ',trim($client_code));
	//echo $arr[0]; // will print Test
	$datefrom = $_POST['csv_datefrom'];
	$dateto = $_POST['csv_dateto'];
	
	
	

	
	
	//---CREATE EXCEL FILE--//
	//if ($result = mysql_query($query) or die(mysql_error())) {
	$objPHPExcel = new PHPExcel(); 
    $objPHPExcel->getActiveSheet()->setTitle($arr[0]);
		
	
	
	//---HEADER STYLE----//
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getStyle("A5:O5")->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(16);
	$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:Q5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A5:Q5')->getFill()->getStartColor()->setARGB('FFC0C0C0');
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:Q4')->getFill()->getStartColor()->setARGB('FFFFFFFF');
	
	foreach(range('A','R') as $columnID)
	{
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}


	
	//$objPHPExcel->getActiveSheet()->freezePane('O6');
	
	
	
	
	
    //$objPHPExcel->getActiveSheet()->getStyle('A6:N4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//---HEADER----//
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Q2');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:Q4');
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $client_code);
	$objPHPExcel->getActiveSheet()->setCellValue('A3', $datefrom." To ".$dateto);
	$objPHPExcel->getActiveSheet()->setCellValue('A5', '#');
	$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Employee name');
	$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Date');
	$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Regular');
	$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Rate');
	$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Total Cost');
	$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Regular Cost');
	$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Overtime Cost');
	$objPHPExcel->getActiveSheet()->setCellValue('I5', 'A(125%)');
	$objPHPExcel->getActiveSheet()->setCellValue('J5', 'B(169%)');
	$objPHPExcel->getActiveSheet()->setCellValue('K5', 'C(130%)');
	$objPHPExcel->getActiveSheet()->setCellValue('L5', 'D(137.5%)');
	$objPHPExcel->getActiveSheet()->setCellValue('M5', 'E(200%)');
	$objPHPExcel->getActiveSheet()->setCellValue('N5', 'F(260%)');
	$objPHPExcel->getActiveSheet()->setCellValue('O5', 'G(338%)');
	$objPHPExcel->getActiveSheet()->setCellValue('P5', 'H(160%)');
	$objPHPExcel->getActiveSheet()->setCellValue('Q5', 'Description');
	
	
	$row = 6;
	$colum = 'B';
	foreach($_POST['csv_emp'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	$row = 6;
	$colum = 'A';
	foreach($_POST['csv_id'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	$row = 6;
	$colum = 'C';
	foreach($_POST['csv_date'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
		
	$row = 6;
	$colum = 'D';
	foreach($_POST['csv_regular'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	$row = 6;
	$colum = 'E';
	foreach($_POST['csv_rate'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 6;
	$colum = 'F';
	foreach($_POST['csv_rcotc'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'G';
	foreach($_POST['csv_rc'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'H';
	foreach($_POST['csv_otc'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'I';
	foreach($_POST['csv_a'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'J';
	foreach($_POST['csv_b'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'K';
	foreach($_POST['csv_c'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'L';
	foreach($_POST['csv_d'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	$row = 6;
	$colum = 'M';
	foreach($_POST['csv_e'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	$row = 6;
	$colum = 'N';
	foreach($_POST['csv_f'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'O';
	foreach($_POST['csv_g'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'P';
	foreach($_POST['csv_h'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;}
		
	}
	$row = 6;
	$colum = 'Q';
	foreach($_POST['csv_desc'] as $value){
	if($row < 65000) {
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++; }
		
	}
	


	header('Content-Type: application/vnd.ms-excel'); 
	header('Content-Disposition: attachment;filename= "Client_Details.xls"'); 
	header('Cache-Control: max-age=0'); 
	
		
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
	$objWriter->save('php://output'); 
	
	
	exit(); 
?>



	
