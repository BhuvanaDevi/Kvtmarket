<?php
require_once "Classes/PHPExcel.php";

include("include/config.php");

$objPHPExcel = new PHPExcel();

// Set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

//Set the first row as the header row
//print_r($_REQUEST);

//exit;

//Rename the worksheet


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1','Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Total Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Total Payment');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Total Balance');

/*****************  Fetching Data From Database  ********************/

$sales_no=$_REQUEST["sales_no"];
$data_sql=" SELECT * FROM `sar_sales_invoice` WHERE is_active=1 AND payment_status=0 GROUP BY sales_no";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
	   
    $sel_qry2 = " SELECT  sum(amount) as paid_amount,payment_date from sar_sales_payment WHERE customer_id = '".$data_row["sales_no"]."' AND is_revoked is NULL GROUP BY customer_id ";
    $data_qry2= $connect->prepare($sel_qry2);
    $data_qry2->execute();
    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
    
    $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
    $data_qry2= $connect->prepare($sel_qry2);
    $data_qry2->execute();
    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
    $balance = $data_row["total_bill_amount"] - $total_discount_on_sales - $data_row2["paid_amount"];
    
    $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
    $data[]=array(
	        "balance"=>$balance,
	        "amount"=>$data_row2["paid_amount"],
	        "customer_name"=>$data_row["customer_name"],
	        "sales_no"=>$data_row["sales_no"],
	        "total_bill_amount"=>$data_row["total_bill_amount"]
         );
}
 
//echo mysqli_num_rows($res);
// if(mysqli_num_rows($res)>0)
// {
    $i=2;
    //$j=1;
    
    for($j=0;$j<=count($data);$j++){
        $i=$j+2;
    //while($approval_row = $res->fetch(PDO::FETCH_ASSOC)) {
    //while($approval_row = mysqli_fetch_object($res)) {
      // echo $data[$j]['id'];
        // $contact_no_exp = explode(",",$approval_row->contact_number);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['customer_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),$data[$j]['amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['balance']);
     
        // $i++;
        
    }    
// }

    

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

$filename="Get Customer Wise Unsettled Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");
// header("Content-Type:text/html");	
// header("Content-Disposition:attachment; filename=".$filename.".txt");
$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
