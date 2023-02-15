<?php
require_once("include/config.php");
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(15);
$date = date("Y-m-d");

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
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(80,20);

$supplier_id=$_REQUEST["supplier_id"];

$sql = "SELECT * FROM  sar_patti WHERE  is_active=1 and nullify=0";
$data_qryn = $connect -> prepare($sql);
$data_qryn->execute();
// $results=$query->fetch(PDO::FETCH_OBJ);


$sqls = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_id'";
$sqlexe = $connect -> prepare($sqls);
$sqlexe->execute();
$sup=$sqlexe->fetch(PDO::FETCH_OBJ);


$pdf->Ln();
$row;
        
        // $pdf->Ln(44);
        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(180,10,'Patti Bill',0,0,'C');
        $pdf->Ln(10);

     $pdf->SetFont('Arial','B',14);
        $pdf->Cell(45 ,10,'Invoice Id',0,0,'L',false);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(47 ,10,$supplier_id,0,0,'L',false);
        // $pdf->Ln();
        
                      $pdf->Cell(50,10,'Date',0,0,'R',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(25,10,$date,0,0,false);
            $pdf->Ln();
           
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(45 ,10,'Supplier Name ',0,0,'L',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(45 ,10,$sup->contact_person,0,0,'L',false);
           
                    $pdf->Cell(50,10,'Group Name',0,0,'R',false);
            $pdf->SetFont('Arial','',14);
            $pdf->Cell(25,10,$sup->group_name,0,0,'L',false);
           
            $pdf->Ln();
           
      $pdf->SetFillColor(40,48,77);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      //$pdf->Cell(30,10,'Invoice',0,0,'L',true);
      $pdf->Cell(80,10,'Balance Type',0,0,'R',true);
      $pdf->Cell(100,10,'Amount',0,0,'C',true);
     
      $pdf->Ln();
      
         $pdf->SetTextColor(1, 0, 4);
        $pdf->SetFont('Arial','',12);
        
            $old="select * from payment where supplierid = '$supplier_id' and date<'$date' order by id desc limit 1";
    $oldsql=$connect->prepare($old);
$oldsql->execute();
$oldbal = $oldsql->fetch(PDO::FETCH_ASSOC);
$olds=$oldbal['total'];
    
    
    $today="select SUM(pay) as pay from payment where supplierid = '$supplier_id' and date='$date' and (paymentmode='' or paymentmode='-') and pattid!='OB'";
    $todaysql=$connect->prepare($today);
$todaysql->execute();
$oldbal1 = $todaysql->fetch(PDO::FETCH_ASSOC);
$old1=$oldbal1['pay'];

    $today1="select SUM(dis) as dis from payment where supplierid = '$supplier_id' and date='$date' and (paymentmode='cash' or paymentmode='percentage')";
    $todaysql1=$connect->prepare($today1);
$todaysql1->execute();
$oldbal11 = $todaysql1->fetch(PDO::FETCH_ASSOC);
// print_r($oldbal11);die();
// $old11=$oldbal11['pay'];
$dis=isset($oldbal11['dis'])?$oldbal11['dis']:0;

$today2="select SUM(pay) as pay from payment where supplierid = '$supplier_id' and date='$date' and (pattid='OB')";
    $todaysql2=$connect->prepare($today2);
$todaysql2->execute();
$oldbal12 = $todaysql2->fetch(PDO::FETCH_ASSOC);
// print_r($oldbal12);die();
// $old11=$oldbal11['pay'];
$obal=isset($oldbal12['pay'])?$oldbal12['pay']:0;
    

         $pdf->Cell(80,10,"Old Balance",0,0,'R');
        // $pdf->Cell(100,10,"100",0,0,'C');
    if($olds>0){
        $pdf->Cell(100 ,10,$olds,0,0,'C');
             }
        //          else if($oldbal['obal'] > 0){
        //   $pdf->Cell(38 ,10,$oldbal['total'],0,1,'R');
        //      }
           else{
      $pdf->Cell(100 ,10,0,0,0,'C');
                  
             }
  
      $pdf->Ln();
     
      $todayp="select SUM(total_bill_amount) as tot from sar_patti where supplier_id = '$supplier_id' and patti_date='$date'";
    $todaysqlp=$connect->prepare($todayp);
$todaysqlp->execute();
$oldbal1p = $todaysqlp->fetch(PDO::FETCH_ASSOC);
// print_r($oldbal11);die();
// $old11=$oldbal11['pay'];
$pat=isset($oldbal1p['tot'])?$oldbal1p['tot']:0;

       $pdf->Cell(80,10,"Patti",0,0,'R');
        $pdf->Cell(100,10,$pat,0,0,'C');
      $pdf->Ln();
     
       $pdf->Cell(80,10,"Payment",0,0,'R');
        $pdf->Cell(100,10,$old1,0,0,'C');
      $pdf->Ln();
      
         $pdf->Cell(80,10,"Discount",0,0,'R');
        $pdf->Cell(100,10,$dis,0,0,'C');
      $pdf->Ln();
      
       $pdf->Cell(80,10,"Opening Balance",0,0,'R');
        $pdf->Cell(100,10,$obal,0,0,'C');
      $pdf->Ln();
      
      
    $bal="select * from payment where supplierid = '$supplier_id' order by id desc limit 1";
    $balsql=$connect->prepare($bal);
$balsql->execute();
$oldbal2 = $balsql->fetch(PDO::FETCH_ASSOC);
$balance=$oldbal2['total'];


    //   $pdf->Cell(80,10,"Balance",0,0,'R');
    //     $pdf->Cell(100,10,"100",0,0,'C');
    //   $pdf->Ln();
   
      
            //  $pdf->SetFont('Arial','',14);
            // $pdf->Cell(56, 10, ' Total', "T", 0, "C");
            // $pdf->Cell(35, 10, 0, 'T', 0, "C");
            // //$pdf->Ln15);
            // $pdf->Cell(18 ,6,'','T',0);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(80, 10, 'Balance',1,0, "R");
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(100 ,10,$balance,1,0,'C');
            $pdf->Ln(10);
     
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
