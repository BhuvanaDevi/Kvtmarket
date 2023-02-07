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
// $supplier=$_REQUEST["supplier"];
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

$sel_qryn = "SELECT * FROM `sar_wastage` WHERE (created_at >='$from' AND created_at<='$to')";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();

// ids='$supplier' AND
//$resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
$no=$data_qryn->rowCount();
//print_r($no);die();

//print_r($result['date']);die();
// $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";

$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Date Wise Report - Wastage',0,0,'C');
        $pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);

    //     $pdf->SetFont('Arial','B',14);
    //     $pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
    //    $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        // $pdf->Cell(20,10,$results->supplier_no,0,0,false);
        // $pdf->SetFont('Arial','B',14);
        // $pdf->Cell(80,10,'Date :',0,0,'R',false);
        // $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        // $pdf->Cell(10,10,$results->created_by,0,0,false);
        // $pdf->Ln();
        // $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(42,10,'Supplier Name  ',0,0,'L',false);
        // $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        // $pdf->Cell(20,10,$results->contact_person,0,0,false);
        // $pdf->SetFont('Arial','B',14);
        // $pdf->Cell(80,10,'Group :',0,0,'R',false);
        // $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        // $pdf->Cell(10,10,$grop->groupname,0,0,false);$pdf->Ln();
        //  $pdf->SetFont('Arial','B',14);
        // $pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
        // $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        // $pdf->Cell(20,10,$results->contact_number1,0,0,false);
        // $pdf->Ln();
        // $pdf->SetFont('Arial','B',14);
        // $pdf->Cell(42,10,'Address ',0,0,'L',false);
        // $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        //  $pdf->Cell(20,10,$results->Address,0,0,false);
        // $pdf->Ln();
      //$pdf->Ln(20);  
      $pdf->Ln(10);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      $pdf->Cell(30,10,'Date',0,0,'L',true);
      $pdf->Cell(50,10,'Quality Name',0,0,'R',true);
      // $pdf->Cell(30,10,'Category',0,0,'R',true);
      $pdf->Cell(40,10,'Quantity',0,0,'R',true);
      $pdf->Cell(50,10,'Total',0,0,'R',true);
      $pdf->Ln();
        $total=0;

    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {
        $total+=$result['total_quantity'];
      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
       // $pdf->Cell(10,10,$result['invoice_id'],0, 0, "R");
        $pdf->Cell(30,10,$result['created_at'],0,0,'L');
      $pdf->Cell(50,10,$result['quality_name'],0,0,'R');
       $pdf->Cell(40,10,$result['quantity'],0,0,'R');
        $pdf->Cell(50,10,$result['total_quantity'],0,0,'R');
     
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
$pdf->Cell(35, 10, $total, 'T', 0, "R");
// $pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $bal, 'T', 1, "R");
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
// $pdf->Ln();
// $pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// //$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(35, 10, $credit-$tota, 'T', 0, "R");
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
