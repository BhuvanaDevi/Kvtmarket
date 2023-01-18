<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
$date = date("Y-m-d");

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

$customer_id=$_REQUEST["customer_id"];
$dates=$_REQUEST["date"];
$customer_name=$_REQUEST["customer_name"];

$saleid=$_REQUEST["saleid"];
// $data_sql=" SELECT * FROM `sar_sales_invoice` ";
// $data_qry= $connect->prepare($data_sql);
// $data_qry->execute();
// $data_row = $data_qry->fetch(PDO::FETCH_ASSOC);
	   
	    $sel_qry2 = " SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '$sales_no' AND is_revoked=0 group by customer_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
	    $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
	    
	    
    	
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

$sql = "SELECT * FROM  sar_sales_invoice WHERE sale_id='$saleid' and date='$dates'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cusid=$results[0]->customer_id;


// $select_qry4= "SELECT * FROM `trays` WHERE ids='$saleid' order by id desc limit 1";
// $select_sql4=$connect->prepare($select_qry4);
// $select_sql4->execute();
// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
// print_r($select_qry4);die();

// print_r($results[0]->customer_id);die();
$cnt=1;

$pdf->Ln();
$row;
foreach($results as $row) {
    if($cnt == 1){
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Sales Invoice',0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->sales_no,0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Date ',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$row->date,0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Customer Name  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->customer_name,0,0,false);
        // $pdf->Cell(80,10,'Small Tray ',0,0,'R',false);
        // $pdf->SetFont('Arial','',14);
        // // $pdf->SetTopMargin(50);
        // $pdf->Cell(10,10,$select_row4['smalltray'],0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Mobile Number ',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$row->mobile_number,0,0,false);
        $pdf->Ln();
        //  $pdf->Cell(80,10,'Big Tray ',0,0,'R',false);
        // $pdf->SetFont('Arial','',14);
        // // $pdf->SetTopMargin(50);
        // $pdf->Cell(10,10,$select_row4['bigtray'],0,0,false);
        // $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Address ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->customer_address,0,0,false);
        //  $pdf->Cell(80,10,'Inhand Tray ',0,0,'R',false);
        // $pdf->SetFont('Arial','',14);
        // // $pdf->SetTopMargin(50);
        // $pdf->Cell(10,10,$select_row4['inhand'],0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Boxes Arrived ',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$row->boxes_arrived,0,0,false);
    
    $pdf->Ln();
        
        // $pdf->Ln(20);
        
        $pdf->SetFillColor(40,48,77);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(30,10,'SI NO.',0,0,'L',true);
        $pdf->Cell(50,10,'Quality Name',0,0,'R',true);
        $pdf->Cell(35,10,'Quantity',0,0,'R',true);
        $pdf->Cell(30,10,'Rate',0,0,'R',true);
        $pdf->Cell(30,10,'Bill Amount',0,0,'R',true);
        $pdf->Ln();
        
    }
        $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(30,10,$cnt,0,0,'C');
        $pdf->Cell(50,10,$row->quality_name,0, 0, "R");
        $pdf->Cell(35,10,$row->quantity ,0, 0, "R");
        $pdf->Cell(30,10,$row->rate,0,0,'R');
        $pdf->Cell(30,10,$row->bill_amount,0,1,'R');
        $customer_name=$row->customer_name;
        $cnt++;
 
}
// $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
// 	    $select_sql3=$connect->prepare($select_qry3);
//     	$select_sql3->execute();
//     	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
//     	$inward_sum=$select_row3["inward_sum"];
    // 	print_r($cusid);die();
    
    	$select_qry4= "SELECT * FROM `trays` WHERE category='Customer' AND name='$cusid' order by id desc limit 1";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
        // print_r($select_qry4);die();
    	$outward_sum=$select_row4["inhand"];
    	$total_sum=$outward_sum;
        //echo $select_qry3;
        $balance =  $row->total_bill_amount - $total_discount_on_sales - $data_row2["paid_amount"];

        $old="select * from payment_sale where customerid = '$cusid' and date<'$date' order by id desc limit 1";
 $oldsql=$connect->prepare($old);
$oldsql->execute();
$oldbal = $oldsql->fetch(PDO::FETCH_ASSOC);
$olds=$oldbal['total'];
// print_r($old);die();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');


                                            $old4="select *,SUM(sale) as sales from payment_sale where customerid = '$cusid' and (date>='$date' or date<='$date') order by id desc limit 1";
    $oldsql4=$connect->prepare($old4);
$oldsql4->execute();
$oldbal4 = $oldsql4->fetch(PDO::FETCH_ASSOC);


$pdf->Cell(47, 10, 'Today Sales', "T", 0, "R");


 $old1="select * from payment_sale where customerid = '$cusid' and date='$date' and (paymentmode!='cash' or paymentmode!='percentage' or paymentmode!='-' or paymentmode!='') order by id desc limit 1";
$oldsql1=$connect->prepare($old1);
$oldsql1->execute();
$oldbal1 = $oldsql1->fetch(PDO::FETCH_ASSOC);
$olds1=$oldbal1['obal'];


// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
  if($oldbal4['sales']>0){
$pdf->Cell(40, 10, $oldbal4['sales'], 'T', 0, "R");
}
else{
$pdf->Cell(40, 10, 0, 'T', 0, "R");    
}
$pdf->Ln(10);

$pdf->Cell(87 ,6,'',0,0);
$pdf->Cell(47, 10, 'Old Balance', 0, 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
       if($oldbal['obal']>0){
   $pdf->Cell(40, 10,$oldbal['total'], 0, 0, "R");
}
else{
$pdf->Cell(40, 10,0, 0, 0, "R");
    }
    $pdf->Ln(10);
    
    
       $old2="select * from payment_sale where customerid = '$cusid' and (date<'$date' or date='$date') and (paymentmode='cash' or paymentmode='percentage') order by id desc limit 1";
    $oldsql2=$connect->prepare($old2);
$oldsql2->execute();
$oldbal2 = $oldsql2->fetch(PDO::FETCH_ASSOC);


$pdf->Cell(87 ,6,'',0,0);
$pdf->Cell(47, 10, 'Discount', 0, 0, "R");
if($oldbal2['dis']>0){
    $pdf->Cell(40, 10,$oldbal2['dis'], 0, 0, "R");
}
else{
    $pdf->Cell(40, 10,0, 0, 0, "R");
    }
    $pdf->Ln(10);
    
       $old3="select * from payment_sale where customerid = '$cusid' or date<'$date' order by id desc limit 1";
    $oldsql3=$connect->prepare($old3);
$oldsql3->execute();
$oldbal3 = $oldsql3->fetch(PDO::FETCH_ASSOC);


$pdf->Cell(87 ,6,'',0,0);
$pdf->Cell(47, 10, 'Balance', "T", 0, "R");
   if($oldbal3['total']>0){
        $pdf->Cell(40, 10, $oldbal3['total'], 'T', 0, "R");
}
else if($oldbal1['tpay']==""){
$pdf->Cell(40, 10,$oldbal['tpay'], 'T', 0, "R");
}
else{
$pdf->Cell(40, 10,0, 'T', 0, "R");

}
$pdf->Ln(10);
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
