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
$customer=$_REQUEST['customer'];

if($customer==""){
$sel_qryn = "SELECT * from payment_sale where saleid like 'C_%' and date>='$from' and date<='$to'";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
// $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// $paid=$resultn['paid'];
$no=$data_qryn->rowCount();
}
else{
  $sel_qryn = "SELECT * from payment_sale where customerid='$customer' and saleid like 'C_%' and (date>='$from' and date<='$to')";
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
        $pdf->Cell(180,10,'Balance - Customer',0,0,'C');
      $pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
$pdf->Ln(10);     

$sql = "SELECT * FROM  sar_customer WHERE customer_no='$customer'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetch(PDO::FETCH_OBJ);
if($customer!=""){
        //  $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(42,10,'Credit ID  ',0,0,'L',false);
        //  $pdf->SetFont('Arial','',14);
         
        //  $pdf->Cell(20,10,$results->customer_no,0,0,false);
        //  $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(80,10,'Date :',0,0,'R',false);
        //  $pdf->SetFont('Arial','',14);
        //  // $pdf->SetTopMargin(50);
        //  $pdf->Cell(10,10,$results->created_by,0,0,false);
        //  $pdf->Ln();
        //  $pdf->SetFont('Arial','B',14);
        //   $pdf->Cell(42,10,'Supplier Name  ',0,0,'L',false);
        //  $pdf->SetFont('Arial','',14);
        //  // $pdf->SetTopMargin(50);
        //  $pdf->Cell(20,10,$results->customer_name,0,0,false);
        //  $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(80,10,'Group :',0,0,'R',false);
        //  $pdf->SetFont('Arial','',14);
        //  // $pdf->SetTopMargin(50);
        //  $pdf->Cell(10,10,$results->grp_cust_name,0,0,false);
        //  $pdf->Ln();
        //  $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(42,10,'Mobile Number ',0,0,'L',false);
        //  $pdf->SetFont('Arial','',14);
        //  // $pdf->SetTopMargin(50);
        //  $pdf->Cell(20,10,$results->contact_number1,0,0,false);
        //  $pdf->Ln();
        //  $pdf->SetFont('Arial','B',14);
        //  $pdf->Cell(42,10,'Address ',0,0,'L',false);
        //  $pdf->SetFont('Arial','',14);
        //  // $pdf->SetTopMargin(50);
        //   $pdf->Cell(20,10,$results->address,0,0,false);
          
         $pdf->Ln(10);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      if($payment!="Balance Only"){
        $pdf->Cell(25,10,'Grp Name ',0,0,'L',true);
        $pdf->Cell(30,10,'Customer ',0,0,'L',true);
       $pdf->Cell(15,10,'Date',0,0,'R',true);
      //  $pdf->Cell(30,10,'Pay Mode',0,0,'R',true);
       $pdf->Cell(20,10,'Total',0,0,'R',true);
      //  $pdf->Cell(30,10,'Given',0,0,'R',true);
       $pdf->Cell(20,10,'Balance',0,0,'R',true);
       $pdf->Cell(20,10,'S-Tray',0,0,'R',true);
       $pdf->Cell(20,10,'B-Tray',0,0,'R',true);
       $pdf->Cell(20,10,'Inhand',0,0,'R',true);
       //  $pdf->Cell(25,10,'Discount',0,0,'R',true);
      //  $pdf->Cell(30,10,'Disc.Type',0,0,'R',true);
      }
    }     else{
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
           $pdf->Cell(40,10,'Group Name ',0,0,'L',true);
       $pdf->Cell(30,10,'Customer ',0,0,'L',true);
       $pdf->Cell(30,10,'Date',0,0,'R',true);
      //  $pdf->Cell(30,10,'Pay Mode',0,0,'R',true);
       $pdf->Cell(35,10,'Balance',0,0,'R',true);
       $pdf->Cell(35,10,'Inhand Trays',0,0,'R',true);
      //  $pdf->Cell(20,10,'Given',0,0,'R',true);
      //  $pdf->Cell(20,10,'Balance',0,0,'R',true);
      //  $pdf->Cell(25,10,'Discount',0,0,'R',true);
      //  $pdf->Cell(30,10,'Discount Type',0,0,'R',true);
      }

      $pdf->Ln();
        $total=0;$bal=0;
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {
$cus_id=$result['customer_id'];

$sqlname="select * from sar_customer where customer_no='$cus_id'";
$data_qryname= $connect->prepare($sqlname);
$data_qryname->execute();
$resultname = $data_qryname->fetch(PDO::FETCH_ASSOC);

$sqlsup="select customer_id,SUM(total_bill_amount) as total,type from sar_sales_invoice where is_active!=0 AND customer_id='$cus_id' GROUP BY customer_id";
$data_qryup= $connect->prepare($sqlsup);
$data_qryup->execute();
$resultup = $data_qryup->fetch(PDO::FETCH_ASSOC);
// print_r($resultup['total']);die();

if($customer!=""){
$grp="select * from sar_sales_payment where customer_id='$customer'";
$grop= $connect->prepare($grp);
$grop->execute();
$group = $grop->fetch(PDO::FETCH_ASSOC);

$grp1="select * from sar_sales_invoice where customer_id='$customer'";
$grop1= $connect->prepare($grp1);
$grop1->execute();
$group1 = $grop1->fetch(PDO::FETCH_ASSOC);
$type=$group1['type'];
$saleid=$group1['sale_id'];
}
else{
  $cus=$result['customer_id'];
  $grp="select * from sar_sales_payment where customer_id='$cus'";
$grop= $connect->prepare($grp);
$grop->execute();
$group = $grop->fetch(PDO::FETCH_ASSOC);


$grp1="select * from sar_sales_invoice where customer_id='$cus'";
$grop1= $connect->prepare($grp1);
$grop1->execute();
$group1 = $grop1->fetch(PDO::FETCH_ASSOC);
$type=$group1['type'];
$saleid=$group1['sale_id'];
}


$tot="select *,SUM(total_bill_amount) as amt from sar_sales_invoice where sale_id='$saleid'";
$tota= $connect->prepare($tot);
$tota->execute();
$totab = $tota->fetch(PDO::FETCH_ASSOC);
$amt = $totab['amt'];


$totamt="select *,SUM(amount) as amot from sar_sales_payment where saleid='$saleid'";
$totamt1= $connect->prepare($totamt);
$totamt1->execute();
$totaamts = $totamt1->fetch(PDO::FETCH_ASSOC);
$balance = $totaamts['amot'];
// print_r($balance);die();
$bal=$amt-$balance;

// $amt=$totab->amt;

$sqltray="select * from trays where name='$cus_id' and type='$type' order by id desc limit 1";
$datatray= $connect->prepare($sqltray);
$datatray->execute();
$resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

// $bal=$resultup['total']-$result['paid'];
$tray=isset($resulttray['inhand'])?$resulttray['inhand']:0;

      $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
        if($payment!="Balance Only"){
          if($customer!=""){
          $pdf->Cell(20,10,$result['groupname'],0,0,'R');
          $pdf->Cell(20,10,$result['name'],0,0,'R');
       $pdf->Cell(30,10,$result['date'],0,0,'R');
        // $pdf->Cell(30,10,$result['payment_mode'],0,0,'R');
        $pdf->Cell(20,10,$result['pay'],0,0,'R');
        // $pdf->Cell(10,10,$result['paid'],0,0,'R');
        $pdf->Cell(20,10,$result['total'],0,0,'R');
        $pdf->Cell(20,10,$result['smalltray'],0,0,'R');
        $pdf->Cell(20,10,$result['bigtray'],0,0,'R');
        $pdf->Cell(20,10,$result['inhand'],0,0,'R');
        // $pdf->Cell(20,10,$result['discount_type'],0,0,'R');
             }
             else{
              
              $pdf->Cell(30,10,$result['groupname'],0,0,'R');
              $pdf->Cell(30,10,$result['name'],0,0,'R');
           $pdf->Cell(40,10,$result['date'],0,0,'R');
            // $pdf->Cell(30,10,$result['payment_mode'],0,0,'R');
            // $pdf->Cell(10,10,$resultup['total'],0,0,'R');
          //  $pdf->Cell(10,10,$result['paid'],0,0,'R');
            $pdf->Cell(32,10,$result['total'],0,0,'R');
            // $pdf->Cell(20,10,$resulttray['smalltray'],0,0,'R');
            // $pdf->Cell(20,10,$resulttray['bigtray'],0,0,'R');
            $pdf->Cell(35,10,$result['inhand'],0,0,'R');
             }
               }
     else{
      $pdf->Cell(20,10,$result['groupname'],0,0,'R');
      $pdf->Cell(30,10,$result['name'],0,0,'R');
      $pdf->Cell(30,10,$result['date'],0,0,'R');
      //  $pdf->Cell(30,10,$result['payment_mode'],0,0,'R');
      //  $pdf->Cell(15,10,$resultup['total'],0,0,'R');
      // $pdf->Cell(20,10,$result['paid'],0,0,'R');
       $pdf->Cell(25,10,$result['total'],0,0,'R');
       $pdf->Cell(30,10,$result['inhand'],0,0,'R');
      //  $pdf->Cell(25,10,$result['discount'],0,0,'R');
      //  $pdf->Cell(20,10,$result['discount_type'],0,0,'R');
     } 
      

    //   $customer_name=$row->customer_name;
//        // $cnt++;
// $pdf->Cell(47,10,$results->customer_name,0,0,false);
$pdf->Ln();
     }
 $pdf->Ln();
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
