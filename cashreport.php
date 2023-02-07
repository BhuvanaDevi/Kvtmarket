<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);

 
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

   $pdf -> Line(10, 30, 200, 30);
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$from=$_REQUEST["from"];
$to=$_REQUEST["to"];
$supplier=$_REQUEST["supplier"];

$sql = "SELECT * FROM  sar_cash_carry WHERE (date >= '$from' and date <= '$to')";
$data_qryn = $connect -> prepare($sql);
$data_qryn->execute();
// $results=$query->fetch(PDO::FETCH_OBJ);


$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Cash & Carry - Customer',0,0,'C');
        $pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);

    
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      $pdf->Cell(20,10,'Group',0,0,'L',true);
      $pdf->Cell(20,10,'Customer',0,0,'R',true);
      // $pdf->Cell(20,10,'Type',0,0,'R',true);
      $pdf->Cell(50,10,'Quality Name',0,0,'R',true);
      $pdf->Cell(30,10,'Quantity',0,0,'R',true);
      $pdf->Cell(20,10,'Rate',0,0,'R',true);
      $pdf->Cell(30,10,'Bill Amount',0,0,'R',true);
      $pdf->Ln();
        $total=0;$bal=0;$credit=0;
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {


      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',10);
        // $pdf->SetFillColor(40,48,77);
        // $pdf->SetTextColor(255,255,255);
        // $pdf->SetFont('Arial','B',12);

        $pdf->Cell(15,10,$result['groupname'],0,0,'R');
        $pdf->Cell(25,10,$result['customer_name'],0,0,'R');
        // $pdf->Cell(20,10,$result['type'],0,0,'R');
        $pdf->Cell(50,10,$result['quality_name'],0,0,'R');
        $pdf->Cell(20,10,$result['quantity'],0,0,'R');
        $pdf->Cell(25,10,$result['rate'],0,0,'R');
        $pdf->Cell(30,10,$result['total_bill_amount'],0,0,'R');
        // $customer_name=$row->customer_name;
        // $cnt++;


    //   $customer_name=$row->customer_name;
//        // $cnt++;
// $pdf->Cell(47,10,$results->customer_name,0,0,false);
$pdf->Ln();
     }

$pdf->SetDash(2,2);

$pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');

$pdf->Ln(10);
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
