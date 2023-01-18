<?php
require_once "Classes/PHPExcel.php";

include("include/config.php");

$objPHPExcel = new PHPExcel();

// Set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

//Set the first row as the header row
//print_r($_REQUEST);
$from = $_REQUEST["from"];
$to = $_REQUEST["to"];
$user_role = $_REQUEST['user_role'];
$username = $_REQUEST['username'];
//exit;

//Rename the worksheet
$objPHPExcel ->getActiveSheet()->setTitle("Get Tray Inventory Report");

$objPHPExcel->setActiveSheetIndex(0);

/*****************  Fetching Data From Database  ********************/
$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');

$objPHPExcel->getActiveSheet()->setCellValue('B1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('C1','User Name');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Category');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Inward');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Outward');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Inhand');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Description');


$data_sql="SELECT * FROM `tray_transactions` WHERE category='Customer' AND (date>='".$from."' AND date<='".$to."')";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
    	$select_qry6= "SELECT sum(inward) as inward,sum(outward) as outward FROM `tray_transactions` WHERE name='".$data_row["name"]."' AND category='Customer' AND date='".$data_row["date"]."' group by name,date ORDER BY id  DESC" ;
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	//$customer_sum=$select_row6["inhand_sum"];
        
        
        $previous_balance_qry="SELECT sum(outward) - sum(inward) as balance FROM tray_transactions WHERE name='".$data_row["name"]."' AND category='Customer' AND date<'".$data_row["date"]."' GROUP BY name ORDER BY id  DESC";
  	    $previous_balance_sql=$connect->prepare($previous_balance_qry);
  	    $previous_balance_sql->execute();
  	    $previous_balance_row=$previous_balance_sql->fetch(PDO::FETCH_ASSOC);
  	    $previous_days_balance_amount=$previous_balance_row["balance"];
  	    
  	    $inhand_sum =$previous_days_balance_amount + $select_row6["outward"] - $select_row6["inward"];
	   //$data_row["inhand"] = $balanceTray;
	   $data[]=array(
	        "rowIndex"=>$data_row["rowIndex"],
	        "date"=>$data_row["date"],
	        "name"=>$data_row["name"],
	        "category"=>$data_row["category"],
	        "inward"=>$select_row6["inward"],
	        "outward"=>$select_row6["outward"],
	        "description"=>$data_row["description"],
	        "updated_by"=>$data_row["updated_by"],
	        "inhand"=>$inhand_sum,
	        "balance"=>$previous_days_balance_amount
	    );
	}

    $i=2;
    //$j=1;
    
    for($j=0;$j<=count($data);$j++){
        $i=$j+2;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['rowIndex']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $data[$j]['date']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),$data[$j]['updated_by']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i), $data[$j]['name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['category']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['inward']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['outward']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['inhand']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i), $data[$j]['description']);
        // $i++;
        
    }    

//exit;

/*
$objPHPExcel->getActiveSheet()->getStyle(
    'A1:' . 
    $objPHPExcel->getActiveSheet()->getHighestColumn() . 
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
*/
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("K1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("U1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("V1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("W1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("Y1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("Z1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AA1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AB1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AC1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AD1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AE1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AF1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AG1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AH1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AI1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AJ1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AK1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AL1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AM1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AN1")->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle("AO1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AP1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AQ1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AR1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AS1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AT1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AU1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AV1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AW1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AX1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AY1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("AZ1")->getFont()->setBold(true);



//exit;
$filename="Get Tray Inventory Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");

$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
