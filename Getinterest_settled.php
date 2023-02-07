<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
//$pdf->Image('images/sar-pdf2.jpg',15,2,170);

$pdf->Image('images/ab-tomato.png',10,5,35);
// Arial bold 15
// $pdf->Ln(45);
$pdf->SetFont('Arial','B',10);
// Move to the right
$pdf->Cell(80);
// Title
$pdf->Cell(110,3,"KARPAGAMBAL VEGETABLE TRADERS",0,0,'C');
$pdf->Ln();
$pdf->Cell(260,8,"AU - 6 D BLOCK, PERIYAR VEGETABLE MARKET COMPLEX,",0,0,'C');
$pdf->Ln();
$pdf->Cell(260,2,"CH - 600 090.",0,0,'C');
$pdf->Ln();
$pdf->Cell(260,8,"7667871022 / 8122294561. ",0,0,'C');

$pdf -> Line(0, 90, 200, 90); 

//$pdf -> Line(0, 100, 210, 100);
// $pdf = new FPDF('p','in', [4.1,2.9]);
// $pdf->SetTopMargin(50);
// code for print Heading of tables
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$pdf->SetY(30);
$pdf->SetFont('Arial','',8);

$from=$_REQUEST["from"];
$to=$_REQUEST["to"];


$sel_qryb = "SELECT * FROM `sar_interest` order by id desc limit 1";
//print_r($sel_qry2);die();
$data_qryb= $connect->prepare($sel_qryb);
$data_qryb->execute();
$resultb=$data_qryb->fetch(PDO::FETCH_ASSOC);
//print_r($resultb['balance']);die();
if($from!="" && $to!=""){
    $sel_qry = "SELECT * FROM `sar_interest` WHERE (date >='$from' AND date<='$to') AND payment_status=1";
    //print_r($sel_qry2);die();
    $data_qry= $connect->prepare($sel_qry);
    $data_qry->execute();
}
else{
        $sel_qry2 = "SELECT * FROM `sar_interest` WHERE payment_status=1";
        //print_r($sel_qry2);die();
        $data_qry2= $connect->prepare($sel_qry2);
        $data_qry2->execute();

}
//print_r($resultn['inhand']);die();

//print_r($result['date']);die();
// $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";

$pdf->Ln();
$row;
        
        $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Report',0,0,'C');
        $pdf->Ln(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(15);

      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',16);
        $pdf->Cell(30,10,'Interest ID',0,0,'R',true);
        $pdf->Cell(30,10,'Date',0,0,'R',true);
        $pdf->Cell(30,10,'Total',0,0,'R',true);
        $pdf->Cell(30,10,'Payment',0,0,'R',true);
        $pdf->Cell(30,10,'Balance',0,0,'R',true);
        $pdf->Ln();
        
      $amt=0;
    while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC))
     {
        $sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_interest_payment where interest_id = '".$data_row["interest_id"]."' AND is_revoked is NULL group by interest_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
        $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
			"payment_status"=>$data_row["payment_status"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "interest_id"=>$data_row["interest_id"],
	        "date"=>$data_row["date"],
	        "client_name"=>$data_row["client_name"],
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
            $pdf->SetTextColor(1, 0, 4);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(30,10,$data_row['interest_id'],0, 0, "R");
            $pdf->Cell(30,10,$data_row['date'],0,0,'R');
            $pdf->Cell(30,10,$data_row['amount'],0,0,'R');
            $pdf->Cell(30,10,$data_row2['paid_amount'],0,0,'R');
            $pdf->Cell(30,10,$balance,0,0,'R');
            $pdf->Ln();
     }

$pdf->Ln(40);
$pdf->Cell(20,10,'Note :',0,0,'L');
$pdf->SetFont('Arial','I',14);
$pdf->Cell(20,10,'Goods once sold will not be taken back or exchanged.',0,0);
$pdf->SetDash();
$pdf -> Line(0, 280, 210, 280);
$pdf->Output();
?>

<!--SELECT column_name(s)-->
<!--FROM table1-->
<!--INNER JOIN table2-->
<!--ON table1.column_name = table2.column_name;-->
