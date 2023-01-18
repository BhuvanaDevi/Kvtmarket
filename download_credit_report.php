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


$objPHPExcel->setActiveSheetIndex(0);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S.NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Credit ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Mobile Number');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Customer Address');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Boxes Arrived');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Total Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Username');
$objPHPExcel->getActiveSheet()->setCellValue('J1','Payment');
$objPHPExcel->getActiveSheet()->setCellValue('K1','Payment Date');
$objPHPExcel->getActiveSheet()->setCellValue('L1','Balance');
$objPHPExcel->getActiveSheet()->setCellValue('M1','Inhand Sum');

$objPHPExcel->getActiveSheet()->setTitle("Get Active Credit Report");
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
	        "payment_date"=>$data_row2["payment_date"],
	        "id"=>$data_row["id"], 
	        "date"=>$data_row["date"],
	        "customer_name"=>$data_row["customer_name"],
	        "customer_address"=>$data_row["customer_address"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "sales_no"=>$data_row["sales_no"],
	        "mobile_number"=>$data_row["mobile_number"],
	        "total_bill_amount"=>$data_row["total_bill_amount"],
	        "is_active"=>$data_row["is_active"],
	        "updated_by"=>$data_row["updated_by"],
	        "waiver_discount"=>$total_discount_on_sales,
	        "inhand_sum"=>$total_sum
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
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['id']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $data[$j]['sales_no']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i), $data[$j]['date']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['customer_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['customer_address']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['boxes_arrived']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i), $data[$j]['updated_by']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.($i),$data[$j]['amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.($i),$data[$j]['payment_date']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.($i), $data[$j]['balance']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.($i), $data[$j]['inhand_sum']);
        // $i++;
        
    }    
// }


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();


$objPHPExcel->setActiveSheetIndex(1);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S.NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Credit ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Mobile Number');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Customer Address');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Boxes Arrived');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Total Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Username');
$objPHPExcel->getActiveSheet()->setCellValue('J1','Payment');
$objPHPExcel->getActiveSheet()->setCellValue('K1','Payment Date');
$objPHPExcel->getActiveSheet()->setCellValue('L1','Balance');
$objPHPExcel->getActiveSheet()->setCellValue('M1','Inhand Sum');
//Rename the worksheet
$objPHPExcel ->getActiveSheet()->setTitle("Get Inactive Credit Report");

// $sql="SELECT * FROM `sar_sales_invoice` WHERE sales_no='$sales_no' and is_active=0 group by sales_no desc";
$sales_no=$_REQUEST["sales_no"];
$data_sql=" SELECT * FROM `sar_sales_invoice` WHERE is_active=0 AND payment_status=0 GROUP BY sales_no ";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
	   
    $sel_qry2 = " SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '".$data_row["sales_no"]."' AND is_revoked is NULL group by customer_id ";
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
	        "payment_date"=>$data_row2["payment_date"],
	        "id"=>$data_row["id"],
	        "date"=>$data_row["date"],
	        "customer_name"=>$data_row["customer_name"],
	        "customer_address"=>$data_row["customer_address"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "sales_no"=>$data_row["sales_no"],
	        "mobile_number"=>$data_row["mobile_number"],
	        "total_bill_amount"=>$data_row["total_bill_amount"],
	        "is_active"=>$data_row["is_active"],
	        "updated_by"=>$data_row["updated_by"],
	        "updated_date"=>$data_row["updated_date"],
	        "waiver_discount"=>$total_discount_on_sales,
	        "inhand_sum"=>$total_sum
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
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['id']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $data[$j]['sales_no']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i), $data[$j]['date']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['customer_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['customer_address']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['boxes_arrived']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i), $data[$j]['updated_by']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.($i),$data[$j]['amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.($i),$data[$j]['payment_date']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.($i), $data[$j]['balance']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.($i), $data[$j]['inhand_sum']);
        // $i++;
        
    }    
// }


$objPHPExcel->createSheet();


$objPHPExcel->setActiveSheetIndex(2);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S.NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Discount');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Username');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Updated Date');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Credit ID');


//Rename the worksheet
$objPHPExcel ->getActiveSheet()->setTitle("Get Waiver Credit Report");

// $sql="SELECT * FROM `sar_sales_invoice` WHERE sales_no='$sales_no' and is_active=0 group by sales_no desc";
$sql=" SELECT * FROM `sar_waiver` WHERE (waiver_date>='".$from."' AND waiver_date<='".$to."')  GROUP BY sales_no ";

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
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i), $approval_row->waiver_date);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i), $approval_row->waiver_discount);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i), $approval_row->username);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i), $approval_row->updated_date);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i), $approval_row->sales_no);
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

$filename="Get Credit Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");
// header("Content-Type:text/html");	
// header("Content-Disposition:attachment; filename=".$filename.".txt");
$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
