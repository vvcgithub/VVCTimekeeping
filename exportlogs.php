<?php
	

	require_once dirname(__FILE__) . '/PHPexcel/Classes/PHPExcel.php';
	
	
	//---INITIALIZE VALUE----//
	$periodcode = $_POST['csv_periodcode'];
	//$arr = explode(' ',trim($periodcode));
	//echo $arr[0]; // will print Test
	//$datefrom = $_POST['csv_datefrom'];
	//$dateto = $_POST['csv_dateto'];
	
	
	

	
	
	//---CREATE EXCEL FILE--//
	//if ($result = mysql_query($query) or die(mysql_error())) {
	$objPHPExcel = new PHPExcel(); 
    $objPHPExcel->getActiveSheet()->setTitle($periodcode);
		
	
	
	//---HEADER STYLE----//
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getStyle("A1:T1")->getFont()->setBold(true);
	//$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(16);
	//$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);*/
	$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFill()->getStartColor()->setARGB('FFC0C0C0');
	//$objPHPExcel->getActiveSheet()->getStyle('A1:R4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    //$objPHPExcel->getActiveSheet()->getStyle('A1:R4')->getFill()->getStartColor()->setARGB('FFFFFFFF');
	
	foreach(range('A','T') as $columnID)
	{
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}


	
	$objPHPExcel->getActiveSheet()->freezePane('A2');
	
	
	
	
	
    //$objPHPExcel->getActiveSheet()->getStyle('A6:N4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//---HEADER----//
	//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:R2');
	//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:R4');
	//$objPHPExcel->getActiveSheet()->setCellValue('A1', $client_code);
	//$objPHPExcel->getActiveSheet()->setCellValue('A3', $datefrom." To ".$dateto);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID#');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Employee name');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'TWD');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'SL');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'VL');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'LWOP');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Others');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'TOTAL');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Late');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Undertime');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'A(125%)');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'B(169%)');
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'C(130%)');
	$objPHPExcel->getActiveSheet()->setCellValue('N1', 'D(137.5%)');
	$objPHPExcel->getActiveSheet()->setCellValue('O1', 'E(200%)');
	$objPHPExcel->getActiveSheet()->setCellValue('P1', 'F(260%)');
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'G(338%)');
	$objPHPExcel->getActiveSheet()->setCellValue('R1', 'H(160%)');
	$objPHPExcel->getActiveSheet()->setCellValue('S1', 'I(160%)');
	$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Overtime');
	
	$row = 2;
	$colum = 'A';
	foreach($_POST['csv_emp_id'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'B';
	foreach($_POST['csv_emp_name'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'C';
	foreach($_POST['csv_TWD'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
		
	$row = 2;
	$colum = 'D';
	foreach($_POST['csv_SL'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'E';
	foreach($_POST['csv_VL'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'F';
	foreach($_POST['csv_LWOP'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'G';
	foreach($_POST['csv_others'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'H';
	foreach($_POST['csv_TOTAL'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'I';
	foreach($_POST['csv_late'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'J';
	foreach($_POST['csv_undertime'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'K';
	foreach($_POST['csv_a'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'L';
	foreach($_POST['csv_b'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'M';
	foreach($_POST['csv_c'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'N';
	foreach($_POST['csv_d'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'O';
	foreach($_POST['csv_e'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'P';
	foreach($_POST['csv_f'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'Q';
	foreach($_POST['csv_g'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'R';
	foreach($_POST['csv_h'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'S';
	foreach($_POST['csv_i'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	$row = 2;
	$colum = 'T';
	foreach($_POST['csv_overtime'] as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($colum.$row, $value);
			$row++;
		
	}
	


	header('Content-Type: application/vnd.ms-excel'); 
	header('Content-Disposition: attachment;filename= "Summary Logs.xls"'); 
	header('Cache-Control: max-age=0'); 
	
		
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
	$objWriter->save('php://output'); 
	
	
	exit(); 
?>



	
