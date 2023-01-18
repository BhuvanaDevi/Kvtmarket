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
$customer=$_REQUEST["customer"];
// print_r($customer);die();
// $tray=$_REQUEST["tray"];

// $data_sql=" SELECT * FROM `sar_sales_invoice` ";
// $data_qry= $connect->prepare($data_sql);
// $data_qry->execute();
// $data_row = $data_qry->fetch(PDO::FETCH_ASSOC);
	   
		// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	    // $data_qry2= $connect->prepare($sel_qry2);
	    // $data_qry2->execute();
	    // $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
// 	    $data[]=array(
//     	        "balance"=>$balance,
//     	        "paid_amount"=>$data_row2["paid_amount"],
//     	       // "discount"=>$data_row3["discount"],
//     	        "id"=>$data_row["id"],
//     	        "date"=>$data_row["date"],
//     	        "amount"=>$data_row["amount"],
//     	        "customer_name"=>$data_row["customer_name"],
//     	        "sales_no"=>$data_row["sales_no"],
//     	        "mobile_number"=>$data_row["mobile_number"],
//     	        "total_bill_amount"=>$data_row["total_bill_amount"],
//     	        "is_active"=>$data_row["is_active"],
//     	        "waiver_discount"=>$total_discount_on_sales,
//     	        "inhand_sum"=>$total_sum
// 	         );
// }

//code for print data

//print_r($customer);die();
$sql = "SELECT * FROM  sar_customer WHERE customer_no='$customer'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetch(PDO::FETCH_OBJ);
//print_r($results[0]->id);die();

$sel_qry2 = "SELECT * FROM `financial_transactions` WHERE ids='$customer' order by id desc limit 1";
$data_qry2= $connect->prepare($sel_qry2);
$data_qry2->execute();
$resultb=$data_qry2->fetch(PDO::FETCH_OBJ);

$grp = "SELECT * FROM `sar_sales_invoice` WHERE customer_id='$customer'";
$grpn= $connect->prepare($grp);
$grpn->execute();
$grop=$grpn->fetch(PDO::FETCH_OBJ);

//print_r($resultb->date);die();

$sel_qryn = "SELECT * FROM `financial_transactions` WHERE ids like 'CUS_%' AND (date >='$from' AND date<='$to')";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
//$resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
$no=$data_qryn->rowCount();
//print_r($no);die();

//print_r($result['date']);die();
// $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";
$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Date Wise Report - Customer',0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
       $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$results->customer_no,0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Date :',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$results->created_by,0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
         $pdf->Cell(42,10,'Customer Name  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
       $pdf->Cell(20,10,$results->customer_name,0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Group :',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$grop->groupname,0,0,false);
        $pdf->Ln();   $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$results->contact_number1,0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Address ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
         $pdf->Cell(20,10,$results->address,0,0,false);
        $pdf->Ln();
      //$pdf->Ln(20);  
      $pdf->Ln(10);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',16);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      $pdf->Cell(30,10,'Date',0,0,'L',true);
      // $pdf->Cell(30,10,'Category',0,0,'R',true);
      $pdf->Cell(50,10,'Credit',0,0,'R',true);
      $pdf->Cell(50,10,'Debit',0,0,'R',true);
      $pdf->Cell(40,10,'Balance',0,0,'R',true);
      $pdf->Ln();
        $total=0;$bal=0;$credit=0;
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     { 

      $credit+=$result['credit'];
      // print_r($result['patti_id']);die();
$fin = "SELECT * FROM `sar_sales_invoice`";
$finn= $connect->prepare($fin);
$finn->execute();
$finnt=$finn->fetch(PDO::FETCH_OBJ);
$saleid=$finnt->sale_id;
// print_r($finnt->sale_id);die();

$tot = "SELECT *,SUM(total_bill_amount) as tot FROM `sar_sales_invoice` WHERE sale_id='$saleid'";
$tota= $connect->prepare($tot);
$tota->execute();
$tots=$tota->fetch(PDO::FETCH_OBJ);
$tota=$tots->tot;
// print_r($tota);die();
      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
       // $pdf->Cell(10,10,$result['invoice_id'],0, 0, "R");
        $pdf->Cell(25,10,$result['date'],0,0,'R');
 //  $pdf->Cell(60,10,$result['description'],0,0,'R');
      // if(!empty($result['cash_carry_id'])){
      //   $pdf->Cell(45,10,$result['cash_carry_id'],0,0,'R');
      //   }
      //   else if(!empty($result['invoice_id'])){
      //     $pdf->Cell(40,10,$result['invoice_id'],0,0,'R');
      //   }
      //   else if(!empty($result['patti_id'])){
      //     $pdf->Cell(40,10,$result['patti_id'],0,0,'R');
      //   }
      //   else{
      //     $pdf->Cell(45,10,'-',0,0,'R');
      //   }

       $total=$result['balance']+$total;        
       $bal=$result['balance']-$bal;        
        $pdf->Cell(50,10,$result['credit'],0,0,'R');
        $pdf->Cell(40,10,$result['debit'],0,0,'R');
        $pdf->Cell(50,10,$result['balance'],0,0,'R');
        // $customer_name=$row->customer_name;
        // $cnt++;


    //   $customer_name=$row->customer_name;
//        // $cnt++;
// $pdf->Cell(47,10,$results->customer_name,0,0,false);
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
$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(47, 10, 'Total', "T", 0, "R");
//$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(30, 10, $total, 'T', 0, "R");
// $pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $bal, 'T', 1, "R");
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(30, 10, $credit-$tota, 'T', 0, "R");
//$pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);9
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
