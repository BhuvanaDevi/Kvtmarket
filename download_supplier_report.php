<?php
require_once "Classes/PHPExcel.php";

include("include/config.php");

$objPHPExcel = new PHPExcel();

// Set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

//Set the first row as the header row
//print_r($_REQUEST);

$user_role = $_REQUEST['user_role'];
$username = $_REQUEST['username'];
//exit;

//Rename the worksheet


$objPHPExcel->setActiveSheetIndex(0);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Supplier ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Supplier Name');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Contact Number');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Address');

$objPHPExcel->getActiveSheet()->setTitle("Get Active Supplier Report");
$sql="SELECT * FROM `sar_supplier` WHERE is_active=1";


$res=mysqli_query($con,$sql);
//echo mysqli_num_rows($res);
if(mysqli_num_rows($res)>0)
{
    $i=2;
    //$j=1;
    //while($approval_row = $res->fetch(PDO::FETCH_ASSOC)) {
    while($approval_row = mysqli_fetch_object($res)) {
        
        
        // $contact_no_exp = explode(",",$approval_row->contact_number);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$approval_row->id);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $approval_row->supplier_no);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i), $approval_row->contact_person);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i), $approval_row->contact_number1);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i), $approval_row->Address);
    
        $i++;
    }    
}


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();


$objPHPExcel->setActiveSheetIndex(1);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Customer ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Contact Number');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Address');

//Rename the worksheet
$objPHPExcel ->getActiveSheet()->setTitle("Get Inactive Supplier Report");
$sql="SELECT * FROM `sar_supplier` WHERE is_active=0";


$res=mysqli_query($con,$sql);
//echo mysqli_num_rows($res);
if(mysqli_num_rows($res)>0)
{
    $i=2;
    //$j=1;
    //while($approval_row = $res->fetch(PDO::FETCH_ASSOC)) {
    while($approval_row = mysqli_fetch_object($res)) {
        
        
        // $contact_no_exp = explode(",",$approval_row->contact_number);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$approval_row->id);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $approval_row->supplier_no);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i), $approval_row->contact_person);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i), $approval_row->contact_number1);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i), $approval_row->Address);
    
        $i++;
    }    
}

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
$objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("C2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("D2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("E2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("F2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("G2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("H2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("I2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("J2")->getFont()->setBold(true);


//exit;

$filename="Get Supplier Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");

$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
