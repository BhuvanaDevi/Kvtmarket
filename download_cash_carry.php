<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
// $pdf->Image('images/ab_pdf.png',50,2,100);

// $pdf->Ln();
// $pdf->SetFont('Arial','B',18);
// $pdf->Cell(180,100,'A108 Adam Street New York, Us',0,0,'C');

// $pdf->SetFont('Arial','B',18);
// $pdf->Cell(180,10,'Date Wise Report - Customer',0,0,'C');
// $pdf->Ln();

   // Logo
  
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
   $pdf -> Line(10, 30, 200,30);
   // $pdf->Cell(80);
   // $pdf->Cell(80,10,'',0,0,'R');

   // $pdf -> Line(0, 90, 200, 90); 
// $pdf->Ln();
// $pdf->SetFont('Arial','B',18);
// $pdf->Cell(180,10,'90421 94877',0,0,'C');


// $pdf = new FPDF('p','in', [4.1,2.9]);
// $pdf->SetTopMargin(50);
// code for print Heading of tables
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$pdf->SetY(60);
$pdf->SetFont('Arial','',16);

//code for print data

$cash_no=$_REQUEST["cash_no"];
$sql = "SELECT * from  sar_cash_carry where cash_no='$cash_no'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
// print_r($results);die();
$cnt=1;
$total_quantity=0;

// $pdf->Ln();
// $row;

 if($cnt == 1){
    $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Cash & Carry',0,0,'C');
        
        $pdf->Ln(20);
        $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45,10,'Cash & Carry ID  :',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            // $pdf->SetTopMargin(50);
            $pdf->Cell(20,10,$cash_no,0,0,false);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(80,10,'Date :',0,0,'R',false);
            $pdf->SetFont('Arial','',14);
            // $pdf->SetTopMargin(50);
            $pdf->Cell(10,10,$results[0]->date,0,0,false);
            $pdf->Ln();
    
        $pdf->Ln(10);
        $pdf->SetFillColor(40,48,77);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(30,10,'SI NO.',0,0,'L',true);
        $pdf->Cell(30,10,'Quality Name',0,0,'R',true);
        $pdf->Cell(30,10,'Quantity',0,0,'R',true);
        $pdf->Cell(30,10,'Rate',0,0,'R',true);
        $pdf->Cell(60,10,'Bill Amount',0,0,'R',true);
        $pdf->Ln();
    }
foreach($results as $row) {
   
        $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(30,10,$cnt,0,0,'C');
        $pdf->Cell(30,10,$row->quality_name ,0, 0, "R");
        $pdf->Cell(30,10,$row->quantity ,0, 0, "R");
        $pdf->Cell(30,10,$row->rate,0,0,'R');
        $pdf->Cell(55,10,$row->bill_amount,0,1,'R');
        $total_quantity = $total_quantity + $row->quantity;
        $cnt++;
        
}
//$pdf->Cell(47,10,$row->quantity ,0, 0, "C");
$pdf->Ln(3);


$pdf->SetFont('Arial','',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(67, 10, ' Total Qty', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(10, 10, $total_quantity, 'T', 0, "R");


$pdf->SetFont('Arial','B',16);
$pdf->Cell(30 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(38, 10, 'Sub Total', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(30, 10, $row->total_bill_amount, 'T', 0, "R");

$pdf->Ln(15);
$pdf->Cell(96 ,6,'',0,0);
$pdf->SetDash(2,2);

$pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(47, 10, 'Total Bill Amount', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(32, 10, $row->total_bill_amount, 'T', 0, "R");
$pdf->Ln(60);
$pdf->Cell(20,10,'Note :',0,0,'L');
$pdf->SetFont('Arial','I',14);
$pdf->Cell(20,10,'Goods once sold will not be taken back or exchanged.',0,0);
$pdf->SetDash();
$pdf -> Line(0, 280, 210, 280);
$pdf->Output();
?>