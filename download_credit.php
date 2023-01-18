<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
//$pdf->Image('images/patti-for-sar2.jpg',60,10,60);

$pdf->Image('images/ab_pdf.png',50,2,100);
// Arial bold 15
$pdf->Ln(45);
$pdf->SetFont('Arial','B',24);
// Move to the right
$pdf->Cell(80);
// Title
$pdf->Cell(30,10,'A108 Adam Street New York, Us',0,0,'C');
// Line break
$pdf->Ln(10);
$pdf->Cell(80);
$pdf->Cell(30,20,'90421 94877',0,0,'C');

//$pdf -> Line(0, 90, 200, 90); 

$pdf->Line(0, 45, 290-70, 45);

$pdf->SetFont('Arial','B',16);
$pdf->SetXY(80,20);

$pdf->SetY(30);
$pdf->SetFont('Arial','',16);

//code for print data
$cash_no=$_REQUEST["cash_no"];
$sql = "SELECT * from  sar_cash_carry where cash_no='$cash_no'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;

$pdf->Ln();
$row;
foreach($results as $row) {
    if($cnt == 1){
        
        $pdf->Ln(20);
        $pdf->Cell(50,4,$row->customer_name,0,0,'L',false);
        $pdf->Ln();
        $pdf->Cell(50,4,$row->mobile_number,0,0,'L',false);
        $pdf->Ln();
        $cnt++;
    
        $pdf->Ln(10);
        
        $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(47,7,'Quality Name',1,0,'C');
        $pdf->Cell(47,7,'Quantity',1,0,'C');
        $pdf->Cell(47,7,'Rate',1,0,'C');
        $pdf->Cell(47,7,'Bill Amount',1,0,'C');
        $pdf->Ln();
    }
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(47,10,$row->quality_name,1,0,'C');
        $pdf->Cell(47,10,$row->quantity,1,0,'C');
        $pdf->Cell(47,10,$row->rate,1,0,'C');
        $pdf->Cell(47,10,$row->bill_amount,1,1,'C');
        
        
        
 //$pdf->Cell(180,20,'TOMATO COMMISSION MUNDY',0,0,'C',false);
}
$pdf->Cell(96 ,6,'',0,0);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(45 ,10,'Sub Total',0,0);
$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Ln();


$pdf->Output();
?>