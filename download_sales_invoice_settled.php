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
   $pdf -> Line(10, 30, 200,30);
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

$cash_no=$_REQUEST["cash_no"];

$saleid=$_REQUEST["saleid"];
$customer_name=$_REQUEST["customer_name"];


$datasql=" SELECT * FROM  sar_cash_carry where cash_no='$cash_no'";
$dataqry= $connect->prepare($datasql);
$dataqry->execute();
$datarow = $dataqry->fetch(PDO::FETCH_ASSOC);
$customer=$datarow['customer_id'];
$saleid=$datarow['saleid'];

$datasql1=" SELECT * FROM  sar_customer where customer_no='$customer'";
$dataqry1= $connect->prepare($datasql1);
$dataqry1->execute();
$datarow1 = $dataqry1->fetch(PDO::FETCH_ASSOC);
// print_r($datarow1['customer_name']);die();
// print_r($customer);die();
// while($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)){
	


$sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '$cash_no' AND is_revoked is NULL group by customer_id ";
	    
     $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$cash_no' GROUP BY sales_no";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	   $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
// 	    $data[]=array(
// 	        "balance"=>$balance,
// 	        "paid_amount"=>$data_row2["paid_amount"],
// 	        "id"=>$data_row["id"],
// 	        "date"=>$data_row["date"],
// 	       "amount"=>$data_row["amount"],
// 	        "customer_name"=>$data_row["customer_name"],
// 	        "cash_no"=>$data_row["cash_no"],
// 	        "updated_by"=>$data_row["updated_by"],
// 	         "mobile_number"=>$data_row["mobile_number"],
// 	         "total_bill_amount"=>$data_row["total_bill_amount"],
// 	         "is_active"=>$data_row["is_active"],
// 	         "waiver_discount"=>$total_discount_on_sales
	         
// 	    );
// 	}

//code for print data

$sql = "SELECT * FROM  sar_sales_invoice WHERE sale_id='$saleid'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);


$select_qry3= "SELECT * FROM `trays` WHERE ids='$saleid' ";
$select_sql3=$connect->prepare($select_qry3);
$select_sql3->execute();
$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
$inhand=$select_row3["inhand"];
        
$cnt=1;

$pdf->Ln();
$row;

$pdf->Ln(1);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(180,10,'Credit Settled',0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(20,10,$cash_no,0,0,false);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(80,10,'Date :',0,0,'R',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(10,10,$datarow1['created_by'],0,0,false);
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(42,10,'Customer Name  ',0,0,'L',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(20,10,$datarow1['customer_name'],0,0,false);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(95,10,'Small Tray :',0,0,'R',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(10,10,$select_row3['smalltray'],0,0,false); $pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
// $pdf->SetTopMargin(50);
$pdf->Cell(20,10,$datarow1['contact_number1'],0,0,false);
$pdf->Cell(95,10,'Big Tray :',0,0,'R',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(10,10,$select_row3['bigtray'],0,0,false);
$pdf->SetFont('Arial','',14);
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(42,10,'Address ',0,0,'L',false);
$pdf->SetFont('Arial','B',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(20,10,$datarow1['address'],0,0,false);
$pdf->Cell(91,10,'Inhand :',0,0,'R',false);
$pdf->SetFont('Arial','',14);
// $pdf->SetTopMargin(50);
$pdf->Cell(10,10,$inhand,0,0,false);
$pdf->Ln();
// $pdf->SetFont('Arial','B',14);
// $pdf->Cell(42,10,'Boxes Arrived ',0,0,'L',false);
// $pdf->SetFont('Arial','',14);
// $pdf->Cell(20,10,$results['boxes_arrived'],0,0,false);
     

if($cnt == 1){

       
    // $pdf->SetTopMargin(50);
    
    $pdf->Ln(20);
    
    $pdf->SetFillColor(40,48,77);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(30,10,'SI NO.',0,0,'L',true);
    $pdf->Cell(40,10,'Quality Name',0,0,'R',true);
    $pdf->Cell(35,10,'Quantity',0,0,'R',true);
    $pdf->Cell(20,10,'Rate',0,0,'R',true);
    $pdf->Cell(50,10,'Bill Amount',0,0,'R',true);
    $pdf->Ln();
}
$i=0;
foreach($results as $row) {
$i+=1;
        $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(30,10,$i,0,0,'C');
        $pdf->Cell(40,10,$row->quality_name ,0, 0, "R");
        $pdf->Cell(35,10,$row->quantity ,0, 0, "R");
        $pdf->Cell(20,10,$row->rate,0,0,'R');
        $pdf->Cell(50,10,$row->total_bill_amount,0,1,'R');
}
    	
    	// $select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
	    // $select_sql4=$connect->prepare($select_qry4);
    	// $select_sql4->execute();
    	// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["outward_sum"];
    	// $total_sum=$outward_sum-$inward_sum;
        //echo $select_qry3;
        // $balance =  $row->total_bill_amount - $total_discount_on_sales - $data_row2["paid_amount"];


    	$select_qry4= "SELECT *,SUM(total_bill_amount) as tot FROM `sar_sales_invoice` WHERE sale_id='$saleid'";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);

        $pay= "SELECT *,SUM(amount) as tot FROM `sar_sales_payment` WHERE saleid='$saleid'";
	    $pays=$connect->prepare($pay);
    	$pays->execute();
    	$paid = $pays->fetch(PDO::FETCH_ASSOC);

$pdf->SetFont('Arial','B',16);
$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
$pdf->Cell(47, 10, 'Total Bill Amount', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(40, 10, $select_row4['tot'], 'T', 0, "R");
$pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Payment', 0, 0, "R");
// // $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $data_row2["paid_amount"], 0, 0, "R");
// $pdf->Ln(10);
$pdf->Cell(87 ,6,'',0,0);
$pdf->Cell(47, 10, 'Discount', 0, 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(40, 10, $total_discount_on_sales, 0, 0, "R");
$pdf->Ln(10);
$pdf->Cell(87 ,6,'',0,0);
$pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
$pdf->Cell(40, 10, $select_row4['tot']-$paid['tot'], 'T', 0, "R");
$pdf->Ln(15);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Inhand Trays', "T", 0, "R");
// $pdf->Cell(40, 10, $inhand, 'T', 0, "R");
// $pdf->Ln(10);
$pdf->Cell(96 ,6,'',0,0);
$pdf->SetDash(2,2);

$pdf->SetFont('Arial','B',16);

// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');

$pdf->Ln(20);
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
