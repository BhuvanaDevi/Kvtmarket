<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
$date = date("Y-m-d");
// print_r($date);die();
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
$pdf->SetFont('Arial','',12);

$supplier_name=$_REQUEST["supplier_name"];
$supplier_id=$_REQUEST["supplier_id"];
$patti_date=$_REQUEST["date"];
$farmer=$_REQUEST["farmer"];
$patti_id=$_REQUEST["patti_id"];
// $select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='$patti_id' AND is_revoked is NULL GROUP BY supplier_id";
        
//         $select_sql6 = $connect->prepare($select_qry6);
//         $select_sql6->execute();
//         $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    
  
$sel_qry2 = " SELECT  * from sar_patti_payment where supplier_id = '$supplier_id' AND is_revoked=0 and patti_id='$patti_id'";
// print_r($sel_qry2);die();
$data_qry2= $connect->prepare($sel_qry2);
$data_qry2->execute();
$data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);

//code for print data

$sql = "SELECT * from  sar_patti where supplier_id='$supplier_id' and pat_id='$patti_id' and patti_date='$patti_date' and farmer_name='$farmer'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
 
$cnt=1;
$total_quantity=0;
// $pdf->Ln(40);
foreach($results as $row) {
    if($cnt == 1){

        $paid=$row->patti_id;
// $select_qry4= "SELECT * FROM `trays` WHERE ids='$paid' order by id desc limit 1";
// $select_sql4=$connect->prepare($select_qry4);
// $select_sql4->execute();
// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
// // print_r($select_qry4);die();
// $outward_sum=$select_row4["inhand"];
// $smalltray=$select_row4["smalltray"];
// $bigtray=$select_row4["bigtray"];
// $inhand=$select_row4["inhand"];
      
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(45 ,10,'Invoice Id',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(47 ,10,$row->patti_id,0,0,'L',false);
        
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(45,10,'Farmer Name  ',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        // $pdf->SetTopMargin(50);
        $pdf->Cell(20,10,$row->farmer_name,0,0,false);
       
        $pdf->Ln();
         
           
        $pdf->SetFont('Arial','B',14);
           $pdf->Cell(45,10,'Date ',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(47,10,$row->patti_date,0,0,false);
            
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45 ,10,'Supplier Name ',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(45 ,10,$row->supplier_name,0,0,'L',false);
            $pdf->Ln();
           
           
            // $pdf->SetFont('Arial','B',14);
            // $pdf->Cell(69,10,'Small Tray :',0,0,'R',false);
            // $pdf->SetFont('Arial','',14);
            // $pdf->Cell(10,10,$smalltray,0,0,false);
            // $pdf->Ln();
           
            // $pdf->SetFont('Arial','B',14);
            // $pdf->Cell(47 ,10,'Address',0,0,'L',false);
            // $pdf->SetFont('Arial','',14);
            // $pdf->Cell(47 ,10,$row->supplier_address,0,0,'L',false);
            // $pdf->Ln();
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45 ,10,'Mobile Number',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(45 ,10,$row->mobile_number,0,0,'L',false);
            
             
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45 ,10,'Boxes Arrived',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(47 ,10,$row->boxes_arrived,0,0,'L',false);
           
                
            // $pdf->SetFont('Arial','B',14);
            // $pdf->Cell(69,10,'Big Tray :',0,0,'R',false);
            // $pdf->SetFont('Arial','',14);
            // $pdf->Cell(10,10,$bigtray,0,0,false);
            $pdf->Ln();
           
            // $pdf->SetFont('Arial','B',14);
            // $pdf->Cell(69,10,'Inhand :',0,0,'R',false);
            // $pdf->SetFont('Arial','',14);
            // $pdf->Cell(10,10,$inhand,0,0,false);
            
            // $pdf->Ln();
            
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45 ,10,'Lorry No',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(45 ,10,$row->lorry_no,0,0,'L',false);
            
           
            $pdf->Ln(10);
            $pdf->SetFillColor(40,48,77);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',16);
           // $pdf->Cell(30,10,'SI NO.',0,0,'L',true);
            $pdf->Cell(30,10,'Q.Name',0,0,'L',true);
            $pdf->Cell(47,10,'Quantity',0,0,'R',true);
            $pdf->Cell(47,10,'Rate',0,0,'R',true);
            $pdf->Cell(56,10,'Bill Amount',0,0,'R',true);
            $pdf->Ln();
              
    }
       
            $pdf->SetTextColor(1, 0, 4);
            $pdf->SetFont('Arial','',14);
           // $pdf->Cell(30,10,$cnt,0,0,'C');
           $pdf->Cell(30,10,$row->quality_name,0,0,'L');
            $pdf->Cell(47,10,$row->quantity ,0, 0, "R");
            $pdf->Cell(47,10,$row->rate,0,0,'R');
            $pdf->Cell(56,10,$row->bill_amount,0,1,'R');
            
            $supplier_name=$row->supplier_name;
            
           $total_quantity = $total_quantity + $row->quantity;
           $cnt++;
}

// $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Supplier' AND name='$supplier_name' ";
// 	    $select_sql3=$connect->prepare($select_qry3);
//     	$select_sql3->execute();
//     	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
//     	$inward_sum=$select_row3["inward_sum"];
    	
//     	$select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Supplier' AND name='$supplier_name' ";
// 	    $select_sql4=$connect->prepare($select_qry4);
//     	$select_sql4->execute();
//     	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
//     	$outward_sum=$select_row4["outward_sum"];
//     	$total_sum=$outward_sum-$inward_sum;

     

        //echo $select_qry4;
        $balance =  $row->net_bill_amount - $sel_row6["paid_amount"];

            $pdf->SetFont('Arial','',14);
            $pdf->Cell(56, 10, ' Total Qty', "T", 0, "C");
            $pdf->Cell(35, 10, $total_quantity, 'T', 0, "C");
            //$pdf->Ln15);
            $pdf->Cell(18 ,6,'','T',0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(40, 10, 'Sub Total',1,0, "C");
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(32 ,10,$row->net_bill_amount,1,0,'R');
            $pdf->Ln(10);
            
            $pdf->Cell(35, 15, ' ', "T", 0, "R");
            $pdf->Cell(25, 15, '', 'T', 0, "R");
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(42 ,10,'',"T",0);
            $pdf->Cell(20 ,10,'Commision',"T",0,'C');
            $pdf->SetFont('Arial','',14);

            
            
            $pdf->Cell(27 ,10,$row->commision,"R",0,'R');
            $pdf->Cell(50 ,10,'',0,0);
            $pdf->Ln();
            $pdf->Cell(110 ,10,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(13 ,10,'Cooli',0,0,'C');
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(26 ,10,$row->cooli,"R",0,'R');
            $pdf->Ln();
            
            $pdf->Cell(99 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(25 ,10,'Box Charge',0,0,'C');
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(25 ,10,$row->box_charge,"R",0,'R');
            $pdf->Ln();
            
            $pdf->Cell(100 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(25 ,10,'Lorry Hire',0,0,'C');
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(24 ,10,$row->lorry_hire,"R",0,'R');
            
               
            $pdf->Cell(119 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(36 ,10,'Deduction',0,0,'C');
          
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(30 ,10,$row->total_deduction,0,1,'C');
           
            
            $pdf->Cell(113 ,6,'',"T",0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(40 ,1,'','T',0,'C');
            $pdf->Cell(30 ,1,'','T',1,'R');
            
            $old="select * from payment where supplierid = '$supplier_id' and date<'$date' order by id desc limit 1";
    $oldsql=$connect->prepare($old);
$oldsql->execute();
$oldbal = $oldsql->fetch(PDO::FETCH_ASSOC);
$olds=$oldbal['obal'];
// print_r($olds);die();
    $pdf->Ln();
        

                          $old1="select * from payment where supplierid = '$supplier_id' and date='$date' and (paymentmode!='cash' or paymentmode!='percentage' or paymentmode!='-' or paymentmode!='') order by id desc limit 1";
    $oldsql1=$connect->prepare($old1);
$oldsql1->execute();
$oldbal1 = $oldsql1->fetch(PDO::FETCH_ASSOC);
$olds1=$oldbal1['obal'];
// print_r($olds1);die();
          
          
          
                                            $old4="select *,SUM(sale) as sales from payment where supplierid = '$supplier_id' and (date>='$date' or date<='$date') order by id desc limit 1";
    $oldsql4=$connect->prepare($old4);
$oldsql4->execute();
$oldbal4 = $oldsql4->fetch(PDO::FETCH_ASSOC);

              $pdf->Cell(95 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(50 ,10,'Today Patti',0,0,'R');
             if($oldbal4['sales'] > 0){
          $pdf->Cell(38 ,10,$oldbal4['sales'],0,1,'R');
             }
             else{
          $pdf->Cell(38 ,10,0,0,1,'R');
            }
             
             
            $pdf->Cell(95 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(50 ,10,'Old Balance',0,0,'R');
             if($oldbal['obal']>0){
        $pdf->Cell(38 ,10,$oldbal['total'],0,1,'R');
             }
             else{
        $pdf->Cell(38 ,10,0,0,1,'R');
                 
             }
             
                                            $old2="select * from payment where supplierid = '$supplier_id' and (date<'$date' or date='$date') and (paymentmode='cash' or paymentmode='percentage') order by id desc limit 1";
    $oldsql2=$connect->prepare($old2);
$oldsql2->execute();
$oldbal2 = $oldsql2->fetch(PDO::FETCH_ASSOC);

               $pdf->Cell(95 ,6,'',0,0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(50 ,10,'Discount',0,0,'R');
             if($oldbal2['dis']>0){
          $pdf->Cell(38 ,10,$oldbal2['dis'],0,1,'R');
             }
             else{
          $pdf->Cell(38 ,10,0,0,1,'R');
                 
             }
             
               $old3="select * from payment where supplierid = '$supplier_id' or date<'$date' order by id desc limit 1";
    $oldsql3=$connect->prepare($old3);
$oldsql3->execute();
$oldbal3 = $oldsql3->fetch(PDO::FETCH_ASSOC);
            
       $pdf->Cell(95 ,6,'',"T",0);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(50 ,10,'Balance',"T",0,'R');
           if($oldbal3['total']>0){
               $pdf->Cell(38 ,10,$oldbal3['total'],'T',1,'R');
           }
           else if($oldbal1['tpay']==""){
               $pdf->Cell(38 ,10,$oldbal['tpay'],'T',1,'R');
           }
           else{
               $pdf->Cell(38 ,10,0,'T',1,'R');
               
           }
            $pdf->Ln();
          
            $pdf->Cell(20,10,'Note  :',0,0,'');
            $pdf->SetFont('Arial','I',14);
            $pdf->Cell(37,10,'Goods once sold will not be taken back or exchanged.',0,0,'L');
            $pdf->SetDash(0,0);
            $pdf -> Line(0, 280, 210, 280);

$pdf->Output();
?>