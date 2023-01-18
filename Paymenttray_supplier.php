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

$pdf->SetY(30);
$pdf->SetFont('Arial','',8);

$from=$_REQUEST["from"];
$to=$_REQUEST["to"];
$supplier=$_REQUEST["supplier"];

if($supplier==""){
$sel_qryn = "SELECT * FROM `payment` WHERE (date >='$from' and date <='$to') and pattid like 'P%'";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
}
else{
   $sel_qryn = "SELECT * FROM `payment` WHERE supplierid='$supplier' and (date >='$from' and date <='$to') and pattid like 'P%'";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
}
// $val = $data_qryn->fetch(PDO::FETCH_ASSOC);
// print_r($val);die();

$pdf->Ln(5);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(180,10,'Tray Report - Supplier',0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);


  if($supplier==""){
      $pdf->SetFont('Arial','B',10);
        
      $pdf->Ln(10);
      $pdf->SetFillColor(40,48,77);
   $pdf->SetTextColor(255,255,255);
  

      //$pdf->Cell( 30,10,'Invoice',0,0,'L',true);
    //   $pdf->Cell(30,10,'Payment Id',0,0,'L',true);
    $pdf->Cell(25,10,'Group',0,0,'R',true);
    $pdf->Cell(35,10,'Supplier Name',0,0,'R',true);
      $pdf->Cell(35,10,'Date',0,0,'R',true);
      $pdf->Cell(35,10,'Bill',0,0,'R',true);
      $pdf->Cell(35,10,'Balance',0,0,'R',true);
      // $pdf->Cell(20,10,'S-Tray',0,0,'R',true);
      // $pdf->Cell(20,10,'B-Tray',0,0,'R',true);
      // $pdf->Cell(20,10,'Inhand',0,0,'R',true);
      }
      else{


         $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetch(PDO::FETCH_OBJ);

         // $pdf->SetFont('Arial','B',14);
         // $pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
         // $pdf->SetFont('Arial','',14);
         
         // $pdf->Cell(20,10,$results->supplier_no,0,0,false);
         // $pdf->SetFont('Arial','B',14);
         // $pdf->Cell(80,10,'Date :',0,0,'R',false);
         // $pdf->SetFont('Arial','',14);
         // // $pdf->SetTopMargin(50);
         // $pdf->Cell(10,10,$results->created_by,0,0,false);
         // $pdf->Ln();
         // $pdf->SetFont('Arial','B',14);
         //  $pdf->Cell(42,10,'Supplier Name  ',0,0,'L',false);
         // $pdf->SetFont('Arial','',14);
         // // $pdf->SetTopMargin(50);
         // $pdf->Cell(20,10,$results->contact_person,0,0,false);
         // $pdf->SetFont('Arial','B',14);
         // $pdf->Cell(80,10,'Group :',0,0,'R',false);
         // $pdf->SetFont('Arial','',14);
         // // $pdf->SetTopMargin(50);
         // $pdf->Cell(10,10,$results->group_name,0,0,false);
         // $pdf->Ln();
         // $pdf->SetFont('Arial','B',14);
         // $pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
         // $pdf->SetFont('Arial','',14);
         // // $pdf->SetTopMargin(50);
         // $pdf->Cell(20,10,$results->contact_number1,0,0,false);
         // $pdf->Ln();
         // $pdf->SetFont('Arial','B',14);
         // $pdf->Cell(42,10,'Address ',0,0,'L',false);
         // $pdf->SetFont('Arial','',14);
         // // $pdf->SetTopMargin(50);
         //  $pdf->Cell(20,10,$results->Address,0,0,false);
          
         $pdf->Ln(10);
         $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
     

           $pdf->SetFont('Arial','B',12);
         $pdf->Cell(30,10,'Group',0,0,'L',true);
         $pdf->Cell(30,10,'Supplier',0,0,'L',true);
         $pdf->Cell(15,10,'Date',0,0,'R',true);
         $pdf->Cell(30,10,'Bill',0,0,'R',true);
         $pdf->Cell(35,10,'Paid',0,0,'R',true);
         // $pdf->Cell(30,10,'Bill Amonut',0,0,'R',true);
         $pdf->Cell(30,10,'Balance',0,0,'R',true);
         // $pdf->Cell(40,10,'Inhand Trays',0,0,'R',true);
      }
    $pdf->Ln();
   
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {

        $cus="select * from sar_patti where supplier_id='$result[supplier_id]'";
        $exe=mysqli_query($con,$cus);
        $cusname=mysqli_fetch_assoc($exe);

      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',10);
   
        $supplier_id=$result['supplier_id'];
   $total=$result['total_bill_amount'];
   $remain=($result['remain']!=0)?$result['remain']:0;
   if($remain>0){
$remains=$remain;
   }
   else if($remain<0){
    $remains=abs($remain);
   }
   else{
    $remains=$total-abs($remain);
   }

   $patid=$cusname['pat_id'];
   $pay="select *,SUM(total_bill_amount) as tot from sar_patti where pat_id='$patid' group by pat_id";
   $pays= $connect->prepare($pay);
   $pays->execute();
   $payment = $pays->fetch(PDO::FETCH_ASSOC);
   // print_r($pay);die();
// $inhand=$intray['inhand'];

        // $pdf->Cell(10,10,$result['invoice_id'],0, 0, "R");
    //   $pdf->Cell(35,10,$result['patti_id'],0,0,'R');
    if($supplier==""){
      $pdf->Cell(30,10,$result['groupname'],0,0,'C');
      $pdf->Cell(40,10,$result['name'],0,0,'C');
       $pdf->Cell(25,10,$result['date'],0,0,'R');
       $pdf->Cell(30,10,$result['obal'],0,0,'R');
      //  $pdf->Cell(20,10,$result['pay'],0,0,'R');
       $pdf->Cell(38,10,$result['total'],0,0,'R');
      //  $pdf->Cell(20,10,$result['smalltray'],0,0,'R');
      //  $pdf->Cell(20,10,$result['bigtray'],0,0,'R');
      //  $pdf->Cell(20,10,$result['inhand'],0,0,'R');
    }
    else{

      
      $bal=$result['total_bill_amount']-$result['amount'];
      // $pdf->Cell(40,10,$cusname['groupname'],0,0,'C');
      // $pdf->Cell(40,10,$cusname['supplier_name'],0,0,'R');
      $pdf->Cell(30,10,$result['groupname'],0,0,'C');
      $pdf->Cell(30,10,$result['name'],0,0,'C');
        $pdf->Cell(20,10,$result['date'],0,0,'R');
      $pdf->Cell(25,10,$result['obal'],0,0,'R');
      $pdf->Cell(30,10,$result['pay'],0,0,'R');
      // $pdf->Cell(25,10,$remains,0,0,'R');
      $pdf->Cell(35,10,$result['total'],0,0,'R');
    }

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

<!--SELECT column_name(s)-->
<!--FROM table1-->
<!--INNER JOIN table2-->
<!--ON table1.column_name = table2.column_name;-->
