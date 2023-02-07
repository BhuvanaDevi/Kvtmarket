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
// code for print Heading of tabl

$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$description=$_REQUEST["dropdown"];
// $supplier=$_REQUEST['supplier'];
if($description=="Supplier"){
$sel_qryn = "SELECT * from trays where category!='Admin' and category='Supplier' group by name";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
// $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// $paid=$resultn['paid'];
$no=$data_qryn->rowCount();
}
else if($description=="Customer"){
	$sel_qryn = "SELECT * from trays where category!='Admin' and category='Customer' group by name";
$data_qryn= $connect->prepare($sel_qryn);
$data_qryn->execute();
// $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
// $paid=$resultn['paid'];
$no=$data_qryn->rowCount();
}
else{
		$sel_qryn = "SELECT * from trays where category!='Admin' group by name";
	$data_qryn= $connect->prepare($sel_qryn);
	$data_qryn->execute();
	// $resultn = $data_qryn->fetch(PDO::FETCH_ASSOC);
	// $paid=$resultn['paid'];
	$no=$data_qryn->rowCount();
}
$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Tray Summary Report - '.$description.'',0,0,'C');
        $pdf->Ln(10);
        // $pdf->SetFont('Arial','B',12);
        // $pdf->Cell(180,10,"From = ".$from."       To = ".$to,0,0,'C');
        // $pdf->Ln(10);
        
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
   
	  $pdf->Cell(25,10,'S.No',0,0,'L',true);
      $pdf->Cell(35,10,'Name',0,0,'L',true);
        $pdf->Cell(25,10,'Category',0,0,'L',true);
       $pdf->Cell(20,10,'Inhand',0,0,'R',true);
       $pdf->Cell(35,10,'Big Tray',0,0,'R',true);
       $pdf->Cell(36,10,'Small Tray',0,0,'R',true);
      
      $pdf->Ln();
        $total=0;$bal=0;$i=0;
    while($result = $data_qryn->fetch(PDO::FETCH_ASSOC))
     {
$id=$result['name'];
if($description=="Customer" || $result['category']=="Customer"){
$name = "SELECT * from sar_customer where customer_no='$id'";
$name1= $connect->prepare($name);
$name1->execute();
$namee = $name1->fetch(PDO::FETCH_ASSOC);
$names=$namee['customer_name'];
}
if($description=="Supplier" || $result['category']=="Supplier"){
	$name = "SELECT * from sar_supplier where supplier_no='$id'";
$name1= $connect->prepare($name);
$name1->execute();
$namee = $name1->fetch(PDO::FETCH_ASSOC);
$names=$namee['contact_person'];
}
// 9710946477
$sel_qry1 = "SELECT * from trays where name='$id' order by id desc limit 1";
$data_qry1= $connect->prepare($sel_qry1);
$data_qry1->execute();
$resultn = $data_qry1->fetch(PDO::FETCH_ASSOC);

$btray = "SELECT * from trays where name='$id' and type='Big Tray' order by id desc limit 1";
$btray1= $connect->prepare($btray);
$btray1->execute();
$bigtray = $btray1->fetch(PDO::FETCH_ASSOC);

$stray = "SELECT * from trays where name='$id' and type='Small Tray' order by id desc limit 1";
$stray1= $connect->prepare($stray);
$stray1->execute();
$smalltray = $stray1->fetch(PDO::FETCH_ASSOC);
// $paid=$resultn['paid'];
// $no=$data_qryn->rowCount();

$smalltray=isset($smalltray['inhand'])?$smalltray['inhand']:0;
$bigtray=isset($bigtray['inhand'])?$bigtray['inhand']:0;
$pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
          $pdf->Cell(30,10,$i+1,0,0,'L');
          $pdf->Cell(10,10,$names,0,0,'R');
       $pdf->Cell(40,10,$result['category'],0,0,'R');
        $pdf->Cell(25,10,$resultn['inhand'],0,0,'R');
        $pdf->Cell(25,10,$bigtray,0,0,'R');
        $pdf->Cell(35,10,$smalltray,0,0,'R');
        // $pdf->Cell(25,10,$result['balance'],0,0,'R');
     $pdf->Ln();
 	}

     $pdf->SetFont('Arial','B',16);
$pdf->Cell(172 ,100,'','T',0);

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


