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
$pdf->Cell(30,20,'90421 94877',0,0,'C');

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


$sel_qryb = "SELECT * FROM `customer_table` order by id desc limit 1";
//print_r($sel_qry2);die();
$data_qryb= $connect->prepare($sel_qryb);
$data_qryb->execute();
$resultb=$data_qryb->fetch(PDO::FETCH_ASSOC);
//print_r($resultb['balance']);die();
if($from!="" && $to!=""){
    $sel_qry = "SELECT * FROM `customer_table` where (date >='$from' AND date<='$to')";
    //print_r($sel_qry2);die();
    $data_qry= $connect->prepare($sel_qry);
    $data_qry->execute();
}
else{
        $sel_qry2 = "SELECT * FROM `customer_table`";
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

        $pdf->Ln();
      //$pdf->Ln(20);  
    //  $pdf->Ln(20);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',16);
        $pdf->Cell(50,10,'Customer Name',0,0,'R',true);
        $pdf->Cell(40,10,'Date',0,0,'R',true);
        $pdf->Cell(40,10,'Chit ID',0,0,'R',true);
        $pdf->Cell(40,10,'Amount',0,0,'R',true);
        // $pdf->Cell(30,10,'Balance',0,0,'R',true);
        $pdf->Ln();
        
      $amt=0;
    while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC))
     {
        $sel_qry2 = "SELECT  *,sum(chitamt) as total_amount from chit where chitid = '".$data_row["customer_id"]."' group by chitid ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    // $tot+=$data_row["total_bill_amount"];
	    $data[]=array(
	        "total_amount"=>$data_row2["total_amount"],
	        "customer_name"=>$data_row["customer_name"],
	        "id"=>$data_row["id"],

	        "customer_id"=>$data_row['customer_id'],	        
	        "date"=>$data_row["date"]
	    );
            $pdf->SetTextColor(1, 0, 4);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(50,10,$data_row['customer_name'],0, 0, "R");
            $pdf->Cell(40,10,$data_row2['chitdate'],0,0,'R');
            $pdf->Cell(40,10,$data_row2['chitid'],0,0,'R');
            $pdf->Cell(40,10,$data_row2['total_amount'],0,0,'R');
            // $pdf->Cell(30,10,$balance,0,0,'R');
    
    //   $customer_name=$row->customer_name;
    //        // $cnt++;
    // $pdf->Cell(47,10,$results->customer_name,0,0,false);
    $pdf->Ln();
     }


// $pdf->SetFont('Arial','B',16);
// $pdf->Cell(87 ,6,'','T',0);

// $pdf->SetDash(2,2);

// $pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');

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
