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
   $pdf -> Line(10, 30, 200, 30);
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

$from=$_REQUEST["from"];
$to=$_REQUEST["to"];
$payment=$_REQUEST["payment"];
$customer=$_REQUEST['customer'];

if($customer==""){
$sel_qryn = "SELECT * from payment_sale where date>='$from' and date<='$to' and saleid like 'C_%'";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
// $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// $paid=$resultn['paid'];
$no=$data_qryn->rowCount();
}
else{
  $sel_qryn = "SELECT * from payment_sale where customerid='$customer' and date>='$from' and date<='$to' and saleid like 'C_%'";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
$no=$data_qryn->rowCount();
}

//print_r($resultn['supplier_id']);die();

//print_r($result['date']);die();
// $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";

$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Payment Wise Report - Customer',0,0,'C');
      $pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      if($customer!=""){
        $pdf->Cell(25,10,'Group',0,0,'L',true);
        $pdf->Cell(25,10,'Customer ',0,0,'L',true);
       $pdf->Cell(30,10,'Date',0,0,'R',true);
       //  $pdf->Cell(30,10,'Pay Mode',0,0,'R',true);
       $pdf->Cell(30,10,'Total',0,0,'R',true);
       $pdf->Cell(30,10,'Pay',0,0,'R',true);
      //  $pdf->Cell(30,10,'Given',0,0,'R',true);
       $pdf->Cell(30,10,'Balance',0,0,'R',true);
      //  $pdf->Cell(25,10,'Inhand',0,0,'R',true);
       //  $pdf->Cell(25,10,'Discount',0,0,'R',true);
      //  $pdf->Cell(30,10,'Disc.Type',0,0,'R',true);
      }
      else{
        $pdf->Cell(30,10,'Group',0,0,'L',true);
       $pdf->Cell(30,10,'Customer ',0,0,'R',true);
       $pdf->Cell(60,10,'Date',0,0,'R',true);
      //  $pdf->Cell(30,10,'Pay Mode',0,0,'R',true);
       $pdf->Cell(50,10,'Balance',0,0,'R',true);
      //  $pdf->Cell(35,10,'Inhand Trays',0,0,'R',true);
      //  $pdf->Cell(20,10,'Given',0,0,'R',true);
      //  $pdf->Cell(20,10,'Balance',0,0,'R',true);
      //  $pdf->Cell(25,10,'Discount',0,0,'R',true);
      //  $pdf->Cell(30,10,'Discount Type',0,0,'R',true);
      }
      
      $pdf->Ln();
        $total=0;$bal=0;
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {
      
      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
        if($customer!=""){
          $pdf->Cell(20,10,$result['groupname'],0,0,'L');
          $pdf->Cell(20,10,$result['name'],0,0,'R');
       $pdf->Cell(40,10,$result['date'],0,0,'R');
        // $pdf->Cell(30,10,$result['payment_mode'],0,0,'R');
        $pdf->Cell(30,10,$result['obal'],0,0,'R');
       $pdf->Cell(30,10,$result['pay'],0,0,'R');
        $pdf->Cell(30,10,$result['total'],0,0,'R');
      //  $pdf->Cell(23,10,$result['inhand'],0,0,'R');
        // $pdf->Cell(20,10,$result['discount_type'],0,0,'R');
               }
     else{

      $pdf->Cell(10,10,$result['groupname'],0,0,'L');
      $pdf->Cell(50,10,$result['name'],0,0,'R');
      $pdf->Cell(60,10,$result['date'],0,0,'R');
      //  $pdf->Cell(30,10,$result['payment_mode'],0,0,'R');
      //  $pdf->Cell(15,10,$resultup['total'],0,0,'R');
      // $pdf->Cell(20,10,$result['paid'],0,0,'R');
       $pdf->Cell(50,10,$result['total'],0,0,'R');
      //  $pdf->Cell(35,10,$result['inhand'],0,0,'R');
      //  $pdf->Cell(25,10,$result['discount'],0,0,'R');
      //  $pdf->Cell(2010,$result['discount_type'],0,0,'R');
     } 
      
     
 $pdf->Ln();
     }
$pdf->Cell(170 ,100,'','T',0);
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
