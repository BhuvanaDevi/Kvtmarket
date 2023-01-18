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
   $pdf->Image('images/ab_pdf.png',10,2,50);
   // Arial bold 15
   // $pdf->Ln(45);
   $pdf->SetFont('Arial','B',12);
   // Move to the right
   $pdf->Cell(80);
   // Title
   $pdf->Cell(100,15,'A108 Adam Street New York, Us - 9042194877',0,0,'R');
   // Line break
   // $pdf->Ln(10);
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
$customer=($_REQUEST["customer"])?$_REQUEST["customer"]:"";

// if($customer!=""){
// $sel_qryn = "SELECT * from sar_sales_payment where customer_id='$customer' ";
// $data_qryn= $connect->prepare($sel_qryn);
// $data_qryn->execute();
// // $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// // $paid=$resultn['paid'];
// $no=$data_qryn->rowCount();
// }
// else{
//     $sel_qryn = "SELECT * from sar_sales_payment";
// $data_qryn= $connect->prepare($sel_qryn);
// $data_qryn->execute();
// // $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// // $paid=$resultn['paid'];
// $no=$data_qryn->rowCount();
// }

if($customer!=""){
$sqlsal="select * from payment_sale where customerid='$customer' and saleid like 'C_%' and (date>='$from' AND date<='$to')";
$data_sal= $connect->prepare($sqlsal);
$data_sal->execute();
}
else{
    $sqlsal="select * from payment_sale where saleid like 'C_%' and (date>='$from' AND date<='$to')";
$data_sal= $connect->prepare($sqlsal);
$data_sal->execute();
}
// $resultsal = $data_sal->fetch(PDO::FETCH_ASSOC);

// print_r($);die();

//print_r($result['date']);die();
// $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";

$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Customer Statements',0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
        $pdf->Ln(10);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',10);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      $pdf->Cell(12,10,'Grp',0,0,'L',true);
      $pdf->Cell(26,10,'Customer',0,0,'L',true);
      $pdf->Cell(18,10,'Date',0,0,'L',true);
      $pdf->Cell(15,10,'O.Bal',0,0,'R',true);
      $pdf->Cell(10,10,'Sale',0,0,'R',true);
      $pdf->Cell(15,10,'Pay',0,0,'R',true);
      // $pdf->Cell(15,10,'T. Pay',0,0,'R',true);
      $pdf->Cell(10,10,'Dis',0,0,'R',true);
      $pdf->Cell(15,10,'C. Bal',0,0,'R',true);
      $pdf->Cell(50,10,'Product',0,0,'R',true);
      
      $pdf->Ln();
    while($result = $data_sal->fetch(PDO::FETCH_ASSOC))
     {


if($result["saleid"]){
$sqlval="select * from sar_sales_invoice where sale_id='$result[saleid]'";
$sqlvals= $connect->prepare($sqlval);
$sqlvals->execute();
// $sqlvalw = $sqlvals->fetch(PDO::FETCH_ASSOC);
}

      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(12,10,$result['groupname'],0,0,'R');
        $pdf->Cell(15,10,$result['name'],0,0,'R');
       $pdf->Cell(25,10,$result['date'],0,0,'R');
        $pdf->Cell(18,10,$result['obal'],0,0,'R');
        $pdf->Cell(12,10,$result['sale'],0,0,'R');
        $pdf->Cell(12,10,$result['pay'],0,0,'R');
      //   $pdf->Cell(14,10,$result['tpay'],0,0,'R');
        $pdf->Cell(14,10,$result['dis'],0,0,'R');
        $pdf->Cell(14,10,$result['total'],0,0,'R');
        // $pdf->Cell(60,10,$result['total'],0,0,'R');
     if($result["saleid"]){
    while($sqlvalw = $sqlvals->fetch(PDO::FETCH_ASSOC)){
      $pdf->SetFont('Arial','',8);
   $pdf->SetX(150);
   $pdf->MultiCell(60,9, $sqlvalw['quality_name']."-".$sqlvalw['quantity']."*".$sqlvalw['rate']."=".($sqlvalw['bill_amount']),0,'L',false);      
}
     }
else{
    $pdf->Cell(40,10,'',0,0,'R');
}
$pdf->Ln();
     }

$pdf->Cell(170 ,50,'','T',0);
$pdf->SetDash(2,2);

$pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Ln();
// $pdf->Ln(10);
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
