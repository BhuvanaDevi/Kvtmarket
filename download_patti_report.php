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

$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Patti ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Supplier Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Mobile Number');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Supplier Address');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Boxes Arrived');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Lorry NO.');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Total Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('J1','Commision');
$objPHPExcel->getActiveSheet()->setCellValue('K1','Lorry Hire');
$objPHPExcel->getActiveSheet()->setCellValue('L1','Box Charge');
$objPHPExcel->getActiveSheet()->setCellValue('M1','Cooli');
$objPHPExcel->getActiveSheet()->setCellValue('N1','Total Deduction');
$objPHPExcel->getActiveSheet()->setCellValue('O1','Net Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('P1','Net Payable');
$objPHPExcel->getActiveSheet()->setCellValue('Q1','Inhand Trays');
$objPHPExcel->getActiveSheet()->setCellValue('R1','Username');

$objPHPExcel->getActiveSheet()->setTitle("Get Active Patti Report");
$data_sql=" SELECT * FROM `sar_patti` WHERE (patti_date>='".$from."' AND patti_date<='".$to."') AND is_active=1 AND payment_status=1 GROUP BY patti_id ";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
    
    $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
    $data[]=array(
	        "id"=>$data_row["id"], 
	        "patti_date"=>$data_row["patti_date"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "supplier_address"=>$data_row["supplier_address"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "patti_id"=>$data_row["patti_id"],
	        "lorry_no"=>$data_row["lorry_no"],
	        "commision"=>$data_row["commision"],
	        "lorry_hire"=>$data_row["lorry_hire"],
	        "box_charge"=>$data_row["box_charge"],
	        "cooli"=>$data_row["cooli"],
	        "patti_id"=>$data_row["patti_id"],
	        "mobile_number"=>$data_row["mobile_number"],
	        "total_bill_amount"=>$data_row["total_bill_amount"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row["net_bill_amount"],
	        "net_payable"=>$data_row["net_payable"],
	        "is_active"=>$data_row["is_active"],
	        "updated_by"=>$data_row["updated_by"],
	        "inhand_sum"=>$total_sum
         );
}
 
// if(mysqli_num_rows($res)>0)
// {
    $i=2;
    //$j=1;
    for($j=0;$j<=count($data);$j++){
        $i=$j+2;
    //while($approval_row = $res->fetch(PDO::FETCH_ASSOC)) {
    //while($approval_row = mysqli_fetch_object($res)) {
        
        
        // $contact_no_exp = explode(",",$approval_row->contact_number);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['id']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),$data[$j]['patti_id']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),$data[$j]['patti_date']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['supplier_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['supplier_address']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['boxes_arrived']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['lorry_no']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.($i),$data[$j]['commision']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.($i),$data[$j]['lorry_hire']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.($i),$data[$j]['box_charge']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.($i),$data[$j]['cooli']);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.($i),$data[$j]['total_deduction']);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.($i),$data[$j]['net_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.($i),$data[$j]['net_payable']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.($i),$data[$j]['inhand_sum']);
        $objPHPExcel->getActiveSheet()->setCellValue('R'.($i),$data[$j]['updated_by']);
        
        $i++;
    }    



$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();


$objPHPExcel->setActiveSheetIndex(1);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Patti ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Supplier Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Mobile Number');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Supplier Address');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Boxes Arrived');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Lorry NO.');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Total Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('J1','Commision');
$objPHPExcel->getActiveSheet()->setCellValue('K1','Lorry Hire');
$objPHPExcel->getActiveSheet()->setCellValue('L1','Box Charge');
$objPHPExcel->getActiveSheet()->setCellValue('M1','Cooli');
$objPHPExcel->getActiveSheet()->setCellValue('N1','Total Deduction');
$objPHPExcel->getActiveSheet()->setCellValue('O1','Net Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('P1','Net Payable');
$objPHPExcel->getActiveSheet()->setCellValue('Q1','Inhand Trays');
$objPHPExcel->getActiveSheet()->setCellValue('R1','Username');

$objPHPExcel->getActiveSheet()->setTitle("Get Inactive Patti Report");
$data_sql=" SELECT * FROM `sar_patti` WHERE (patti_date>='".$from."' AND patti_date<='".$to."') AND is_active=0 GROUP BY patti_id ";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
    
    $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
    $data[]=array(
	        "id"=>$data_row["id"], 
	        "patti_date"=>$data_row["patti_date"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "supplier_address"=>$data_row["supplier_address"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "patti_id"=>$data_row["patti_id"],
	        "lorry_no"=>$data_row["lorry_no"],
	        "commision"=>$data_row["commision"],
	        "lorry_hire"=>$data_row["lorry_hire"],
	        "box_charge"=>$data_row["box_charge"],
	        "cooli"=>$data_row["cooli"],
	        "patti_id"=>$data_row["patti_id"],
	        "mobile_number"=>$data_row["mobile_number"],
	        "total_bill_amount"=>$data_row["total_bill_amount"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row["net_bill_amount"],
	        "net_payable"=>$data_row["net_payable"],
	        "is_active"=>$data_row["is_active"],
	        "updated_by"=>$data_row["updated_by"],
	        "inhand_sum"=>$total_sum
         );
}
    $i=2;
    //$j=1;
    for($j=0;$j<=count($data);$j++){
        $i=$j+2;
    //while($approval_row = $res->fetch(PDO::FETCH_ASSOC)) {
    //while($approval_row = mysqli_fetch_object($res)) {
        
        
        // $contact_no_exp = explode(",",$approval_row->contact_number);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['id']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),$data[$j]['patti_id']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),$data[$j]['patti_date']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['supplier_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['supplier_address']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['boxes_arrived']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['lorry_no']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.($i),$data[$j]['commision']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.($i),$data[$j]['lorry_hire']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.($i),$data[$j]['box_charge']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.($i),$data[$j]['cooli']);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.($i),$data[$j]['total_deduction']);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.($i),$data[$j]['net_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.($i),$data[$j]['net_payable']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.($i),$data[$j]['inhand_sum']);
        $objPHPExcel->getActiveSheet()->setCellValue('R'.($i),$data[$j]['updated_by']);
        
        $i++;
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

$filename="Get Patti Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");

$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
