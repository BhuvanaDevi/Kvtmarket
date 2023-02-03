<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
//$pdf->Image('images/sar-pdf2.jpg',15,2,170);
$pdf->Image('images/ab_pdf.png',50,2,100);
// Arial bold 15
$pdf->Ln(45);
$pdf->SetFont('Arial','B',24);
// Move to the right
$pdf->Cell(80);
// Title
$pdf->Cell(30,10,'A108 Adam Street New York, Us',0,0,'C');
// Line break
$pdf->Ln(10);
$pdf->Cell(80);
$pdf->Cell(30,20,'90421 94877',0,0,'C');

$pdf -> Line(0, 90, 200, 90); 

//$pdf -> Line(0, 100, 210, 100);
// $pdf = new FPDF('p','in', [4.1,2.9]);
// $pdf->SetTopMargin(50);
// code for print Heading of tables
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$pdf->SetY(30);
$pdf->SetFont('Arial','',8);

$purchase_id=$_REQUEST["purchase_id"];
$supplier_name=$_REQUEST["supplier_name"];
// $data_sql=" SELECT * FROM `sar_sales_invoice` ";
// $data_qry= $connect->prepare($data_sql);
// $data_qry->execute();
// $data_row = $data_qry->fetch(PDO::FETCH_ASSOC);
	   
	    // $sel_qry2 = " SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '$sales_no' AND is_revoked is NULL group by customer_id ";
	    // $data_qry2= $connect->prepare($sel_qry2);
	    // $data_qry2->execute();
	    // $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
	    // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no' GROUP BY sales_no";
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

$sql = "SELECT * FROM  sar_stock WHERE purchase_id='$purchase_id'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);


$cnt=1;

$pdf->Ln();
$row;
foreach($results as $row) {

    $sql1 = "SELECT * FROM  sar_supplier WHERE contact_person='$row->supplier_name'";
    $query1 = mysqli_query($con,$sql1);
    $results1=mysqli_fetch_row($query1);
  
    if($cnt == 1){
        
        $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Stock Purchase',0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Purchase ID  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->purchase_id,0,0,false);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80,10,'Date :',0,0,'R',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(10,10,$row->date,0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Supplier Name  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->supplier_name,0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Supplier Number ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$results1[5],0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Address ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$results1[6],0,0,false);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(42,10,'Group Name ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->group_name,0,0,false);
    
    
        $pdf->Ln(20);
        
        $pdf->SetFillColor(40,48,77);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(30,10,'Q.Name',0,0,'L',true);
        $pdf->Cell(47,10,'Quantity',0,0,'R',true);
        $pdf->Cell(47,10,'Rate',0,0,'R',true);
        $pdf->Cell(50,10,'Stock Amount',0,0,'R',true);
        $pdf->Ln();
        
    }
        $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(30,10,$row->quality_name,0,0,'C');
        $pdf->Cell(47,10,$row->quantity ,0, 0, "R");
        $pdf->Cell(47,10,$row->rate,0,0,'R');
        $pdf->Cell(50,10,$row->stock_amount,0,1,'R');
        $supplier_name=$row->supplier_name;
        $cnt++;
 
}
$select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Customer' AND name='$customer_name' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
        //echo $select_qry3;
        $balance =  $row->total_bill_amount - $total_discount_on_sales - $data_row2["paid_amount"];

//$pdf->SetFont('Arial','B',16);
//$pdf->Cell(87 ,6,'','T',0);
// $pdf->Cell(45 ,10,'Sub Total',1,0,'C');
//$pdf->Cell(47, 10, 'Total Bill Amount', "T", 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
//$pdf->Cell(40, 10, $row->stock_amount, 'T', 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Payment', 0, 0, "R");
// $pdf->Cell(47,10,$row->total_bill_amount,1,0,'C');
// $pdf->Cell(40, 10, $row->stock_payment, 0, 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Discount', 0, 0, "R");
// $pdf->Cell(40, 10, "-", 0, 0, "R");
// $pdf->Ln(10);
// $pdf->Cell(87 ,6,'',0,0);
// $pdf->Cell(47, 10, 'Balance', "T", 0, "R");
// $pdf->Cell(40, 10, $balance, 'T', 0, "R");
// $pdf->Ln(10);
$pdf->Cell(105 ,10,'',0,0);
$pdf->Cell(40, 10, 'Total Bill Amount', "T", 0, "R");
$pdf->Cell(28, 10, $row->stock_amount, 'T', 0, "R");
$pdf->Ln(10);
$pdf->Cell(96 ,6,'',0,0);

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
