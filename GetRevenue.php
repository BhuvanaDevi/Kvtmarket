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
$category=$_REQUEST["category"];
//$purchased=$_REQUEST["purchased"];

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
// $sql = "SELECT * FROM  sar_expenditure WHERE purchased_from='$purchased'";
// $query = $connect -> prepare($sql);
// $query->execute();
// $results=$query->fetch(PDO::FETCH_ASSOC);
//print_r($results['purchased_from']);die();


$sel_qryb = "SELECT * FROM `sar_revenue` order by id desc limit 1";
//print_r($sel_qry2);die();
$data_qryb= $connect->prepare($sel_qryb);
$data_qryb->execute();
$resultb=$data_qryb->fetch(PDO::FETCH_ASSOC);
//print_r($resultb['balance']);die();
if($category!="" && $from!="" && $to!=""){
    $sel_qry2 = "SELECT * FROM `sar_revenue` WHERE (date >='$from' AND date<='$to') AND type='$category'";
//print_r($sel_qry2);die();
$data_qry2= $connect->prepare($sel_qry2);
$data_qry2->execute();
}
else{
        $sel_qry2 = "SELECT * FROM `sar_revenue`";
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

      //   $pdf->SetFont('Arial','B',14);
      //   $pdf->Cell(42,10,'Purchased From  ',0,0,'L',false);
      //  $pdf->SetFont('Arial','',14);
      //   // $pdf->SetTopMargin(50);
      //   $pdf->Cell(20,10,$results['purchased_from'],0,0,false);
      //   $pdf->SetFont('Arial','B',14);
      //   $pdf->Cell(80,10,'Date :',0,0,'R',false);
      //   $pdf->SetFont('Arial','',14);
      //   // $pdf->SetTopMargin(50);
      //   $pdf->Cell(10,10,$results['date'],0,0,false);
      //   $pdf->Ln();
      //   $pdf->SetFont('Arial','B',14);
      //    $pdf->Cell(42,10,'Particulars  ',0,0,'L',false);
      //   $pdf->SetFont('Arial','',14);
      //   // $pdf->SetTopMargin(50);
      //   $pdf->Cell(20,10,$results['particulars'],0,0,false);
      //   $pdf->Ln();
      //   $pdf->SetFont('Arial','B',14);
      //   $pdf->Cell(42,10,'Category ',0,0,'L',false);
      //   $pdf->SetFont('Arial','',14);
      //   // $pdf->SetTopMargin(50);
      //   $pdf->Cell(20,10,$category,0,0,false);
      //   $pdf->Ln();
        // $pdf->SetFont('Arial','B',14);
        // $pdf->Cell(42,10,'{Address} ',0,0,'L',false);
        // $pdf->SetFont('Arial','',14);
        // // $pdf->SetTopMargin(50);
        //  $pdf->Cell(20,10,$results->payment_mode,0,0,false);
        $pdf->Ln();
      //$pdf->Ln(20);  
    //  $pdf->Ln(20);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
        // $pdf->Cell(15,10,'Exp No',0,0,'L',true);
        $pdf->Cell(20,10,'Date',0,0,'R',true);
        $pdf->Cell(20,10,'Group',0,0,'R',true);
        $pdf->Cell(20,10,'Name',0,0,'R',true);
        $pdf->Cell(20,10,'Category',0,0,'R',true);
        $pdf->Cell(20,10,'Type',0,0,'R',true);
        $pdf->Cell(20,10,'Amount',0,0,'R',true);
        $pdf->Cell(20,10,'Balance',0,0,'R',true);
        $pdf->Cell(20,10,'Bal Type',0,0,'R',true);
        $pdf->Cell(20,10,'Remarks',0,0,'R',true);
     
      // $pdf->Cell(40,10,'Balance',0,0,'R',true);
      $pdf->Ln();
        
      $amt=0;
    while($result = $data_qry2->fetch(PDO::FETCH_ASSOC))
     {
      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',11);
        // $pdf->Cell(36,10,$result['exp_no'],0, 0, "R");
        $pdf->Cell(20,10,$result['date'],0,0,'R');
            $pdf->Cell(20,10,$result['grp'],0,0,'R');
            $pdf->Cell(20,10,$result['purchased_from'],0,0,'R');
            $pdf->Cell(20,10,$result['type'],0,0,'R');
             $pdf->Cell(20,10,$result['particulars'],0,0,'R');
            $pdf->Cell(20,10,$result['amount'],0,0,'R');
            $pdf->Cell(20,10,$result['balance'],0,0,'R');
            $pdf->Cell(20,10,$result['payment_mode'],0,0,'R');
            $pdf->Cell(20,10,$result['remarks'],0,0,'R');
           
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

$pdf->SetFont('Arial','B',12);
$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(77, 10, 'Total Amount', "T", 0, "R");
//$pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(15, 10, $resultb['balance'], 'T', 0, "R");
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
