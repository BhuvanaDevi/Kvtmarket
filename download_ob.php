<?php
require_once "Classes/PHPExcel.php";

include("include/config.php");

$objPHPExcel = new PHPExcel();

// Set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

//Set the first row as the header row
//print_r($_REQUEST);
$from = $_REQUEST["from_ob"];
$to = $_REQUEST["to_ob"];
$user_role = $_REQUEST['user_role'];
$username = $_REQUEST['username'];

//exit;

//Rename the worksheet


$objPHPExcel->setActiveSheetIndex(0);

/*****************  Fetching Data From Database  ********************/

$objPHPExcel->getActiveSheet()->setCellValue('A1','S NO');
$objPHPExcel->getActiveSheet()->setCellValue('B1','Balance ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1','Date');
$objPHPExcel->getActiveSheet()->setCellValue('D1','Group Name');
$objPHPExcel->getActiveSheet()->setCellValue('E1','Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Amount');
$objPHPExcel->getActiveSheet()->setCellValue('G1','Payment');
$objPHPExcel->getActiveSheet()->setCellValue('H1','Balance');
$objPHPExcel->getActiveSheet()->setCellValue('I1','Username');
$objPHPExcel->getActiveSheet()->setTitle("Get OB Report");

$data_sql=" SELECT * FROM `sar_opening_balance` WHERE (date>='".$from."' AND date<='".$to."') AND payment_status=0 GROUP BY balance_id ";
$data_qry= $connect->prepare($data_sql);
$data_qry->execute();
$data = array();
while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
    $open_balance_pay_qry = "SELECT  sum(amount) as pay_amount from sar_balance_payment where balance_id = '".$data_row["balance_id"]."' group by balance_id ";
    $open_balance_pay_sql= $connect->prepare($open_balance_pay_qry);
    $open_balance_pay_sql->execute();
    $open_balance_pay_row = $open_balance_pay_sql->fetch(PDO::FETCH_ASSOC);
    $balance_ob = $data_row["amount"] - $open_balance_pay_row["pay_amount"];
    
    $data[]=array(
	        "id"=>$data_row["id"], 
	        "date"=>$data_row["date"],
	        "balance"=>$balance_ob,
	        "pay_amount"=>$open_balance_pay_row["pay_amount"],
	        "customer_name"=>$data_row["name"],
	        "balance_id"=>$data_row["balance_id"],
	        "group_name"=>$data_row["group_name"],
	        "total_bill_amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
         );
}
 
    $i=2;
    for($j=0;$j<=count($data);$j++){
        $i=$j+2;

        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$data[$j]['id']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),$data[$j]['balance_id']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),$data[$j]['date']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$data[$j]['group_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$data[$j]['customer_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),$data[$j]['total_bill_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),$data[$j]['pay_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.($i),$data[$j]['balance']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.($i),$data[$j]['updated_by']);
        
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

$filename="Get OB Report";
header("Content-Type:application/vnd.ms-excel");	
header("Content-Disposition:attachment; filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Pragma: no-cache");

$objWriter=PHPExcel_IOFactory::createwriter($objPHPExcel,"Excel5");
$objWriter->save("php://output");
?>
