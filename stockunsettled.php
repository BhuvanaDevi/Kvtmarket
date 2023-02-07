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
// $customer=$_REQUEST['customer'];

// if($customer==""){
$sel_qryn = "SELECT * FROM `sar_stock` WHERE (date >='$from' AND date<='$to') and return_status=0";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
// }

// $val = $data_qryn->fetch(PDO::FETCH_ASSOC);
// print_r($val);die();
$pdf->Ln(15);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(180,10,'Stock Unsettled',0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);
 
   $pdf->SetFillColor(40,48,77);
               $pdf->SetTextColor(255,255,255);
     
               $pdf->SetFont('Arial','B',10);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      // $pdf->Cell(30,10,'Payment Id',0,0,'L',true);
      $pdf->Cell(30,10,'Group',0,0,'L',true);
      $pdf->Cell(30,10,'Supplier',0,0,'L',true);
      $pdf->Cell(20,10,'Date',0,0,'L',true);
      $pdf->Cell(25,10,'Quality',0,0,'C',true);
      $pdf->Cell(20,10,'Quantity',0,0,'L',true);
      $pdf->Cell(20,10,'Rate',0,0,'L',true);
      $pdf->Cell(25,10,'Stock Amount',0,0,'L',true);
         $pdf->Ln(10);
         $pdf->SetFillColor(40,48,77);
               $pdf->SetTextColor(255,255,255);

            //    $pdf->Ln();
   $amt=0;
   $pdf->SetTextColor(1, 0, 4);
   $pdf->SetFont('Arial','',12);

   while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {
    $pdf->Cell(20,10,$result['group_name'],0,0,'L');
         $pdf->Cell(25,10,$result['supplier_name'],0,0,'R');
       $pdf->Cell(30,10,$result['date'],0,0,'R');
       $pdf->Cell(30,10,$result['quality_name'],0,0,'R');
       $pdf->Cell(15,10,$result['quantity'],0,0,'R');
       $pdf->Cell(15,10,$result['rate'],0,0,'R');
       $pdf->Cell(30,10,$result['stock_amount'],0,0,'R');
    
       $pdf->Ln();
     }
// $pdf->SetFont('Arial','B',14);
// $pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
// $pdf->SetFont('Arial','',14);


// $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
// 	    $select_sql3=$connect->prepare($select_qry3);
//     	$select_sql3->execute();
//     	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
//     	$inward_sum=$select_row3["inward_sum"];
    	
//     	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
// 	    $select_sql4=$connect->prepare($select_qry4);
//     	$select_sql4->execute();
//     	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
//     	$outward_sum=$select_row4["outward_sum"];
//     	$total_sum=$outward_sum-$inward_sum;
//         //echo $select_qry3;
//         $balance =  $row->total_bill_amount - $total_discount_on_sales - $data_row2["paid_amount"];

$pdf->SetFont('Arial','B',16);
//$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
//$pdf->Cell(47, 10, 'Total', "T", 0, "R");
//$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
//$pdf->Cell(40, 10, $total, 'T', 0, "R");
// $pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $bal, 'T', 1, "R");
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Total Amount', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $resultb->balance, 'T', 0, "R");
//$pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Payment', 0, 0, "R");
// // $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $data_row2["paid_amount"], 0, 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Discount', 0, 0, "R");
// $pdf->Cell(40, 10, $total_discount_on_sales, 0, 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// $pdf->Cell(40, 10, $balance, 'T', 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Inhand Trays', "T", 0, "R");
// $pdf->Cell(40, 10, $total_sum, 'T', 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(96 ,6,'',0,0);

$pdf->SetDash(2,2);

$pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');

// $pdf->Ln(20);
$pdf->Cell(20,10,'Note :',0,0,'L');
$pdf->SetFont('Arial','I',14);
$pdf->Cell(20,10,'Goods once sold will not be taken back or exchanged.',0,0);
$pdf->SetDash();
$pdf -> Line(0, 280, 210, 280);
$pdf->Output();
?>
