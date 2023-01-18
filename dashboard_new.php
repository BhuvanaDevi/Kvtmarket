<?php require "header.php";

// $dbHost = "localhost";
//     $dbUsername="eezybifr_maha";
//     $dbPassword="maha@123";
//     $dbName="eezybifr_saimaha";
    
//     $con=mysqli_connect( $dbHost,$dbUsername, $dbPassword,$dbName);
    
//     if($con)
//     {
//         // echo "<script>alert('connected')</script>";
//     }
//     else
//     {
//         // echo "<script>alert('not connected')</script>";
//     }
?>
<?php
 $sql="SELECT * FROM  tray_transactions ORDER BY id DESC LIMIT 1";
        $result= $connect->prepare($sql);
        $result->execute();
        $row=$result->fetch(PDO::FETCH_ASSOC);
        // $result=mysqli_query($con,$sql);
        // $row=mysqli_fetch_assoc($result);

        //print_r($_REQUEST); 
        if($row)
        {
            $inhand=$row['inhand'];
        }
        else
        {
            // echo mysqli_error($connect);
        }
        
        
$today_date = date('Y-m-d');

        $query="SELECT sum(credit) as credit_sum, sum(debit) as debit_sum FROM   financial_transactions WHERE DATE(date)=DATE(NOW())";
        $res= $connect->prepare($query);
        $res->execute();
        // $res=mysqli_query($con,$query);

 $ob_total="select sum(amount) as `unsettled_ob`from ( select distinct `ob_customer_id`, `amount` from sar_ob_customer WHERE payment_status=0) t1";
 $res_total3= $connect->prepare($ob_total);
 $res_total3->execute();
 $rec3=$res_total3->fetch(PDO::FETCH_ASSOC);
  $total_ob_sum=$rec3["unsettled_ob"];
  
  $ob_payment="select sum(amount) as `payment_ob` from ( select distinct `ob_customer_id`, `amount` from sar_balance_payment WHERE payment_date='".$today_date."') t1
";
$res_payment1= $connect->prepare($ob_payment);
 $res_payment1->execute();
 $rec3=$res_payment1->fetch(PDO::FETCH_ASSOC);
$paid_ob_sum=$rec3["payment_ob"];
$total_unsettled_ob = $total_ob_sum - $paid_ob_sum;

  $invoice_total="select sum(bill_amount) as `unsettled_sales`from ( select distinct `sales_no`, `bill_amount` from sar_sales_invoice WHERE payment_status=0 and is_active=1) t1";
  $res_total= $connect->prepare($invoice_total);
 $res_total->execute();
 $rec1=$res_total->fetch(PDO::FETCH_ASSOC);
  $total_sum=$rec1["unsettled_sales"];
  
  $invoice_payment="select sum(amount) as `payment_sales`from ( select distinct `customer_id`, `amount` from sar_sales_payment WHERE payment_date='".$today_date."' AND is_revoked is NULL) t1
";
$res_payment= $connect->prepare($invoice_payment);
 $res_payment->execute();
 $rec1=$res_payment->fetch(PDO::FETCH_ASSOC);
$paid_sum=$rec1["payment_sales"];
$total_unsettled_sales = $total_sum-$paid_sum;
$unsettled_sales = $total_unsettled_sales + $total_unsettled_ob;

$patti_total="select sum(net_payable) as `unsettled_patti`from ( select distinct `patti_id`, `net_payable` from sar_patti WHERE payment_status=1 and is_active=1) t1";
$res_total2= $connect->prepare($patti_total);
 $res_total2->execute();
 $rec2=$res_total2->fetch(PDO::FETCH_ASSOC);
  
  $total_sum_patti=$rec2["unsettled_patti"];
  
  $patti_payment="select sum(amount) as `payment_patti`from ( select distinct `supplier_id`, `amount` from sar_patti_payment WHERE payment_date='".$today_date."' AND is_revoked is NULL) t1
";
$res_payment2= $connect->prepare($patti_payment);
 $res_payment2->execute();
 $rec2=$res_payment2->fetch(PDO::FETCH_ASSOC);
$paid_sum_patti=$rec2["payment_patti"];
$total_unsettled_patti = $total_sum_patti-$paid_sum_patti;

$query3="SELECT (SELECT SUM(`total_trays`) FROM `sar_trays` ) as `total_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ) as `supplier_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ) as `customer_tray`,(SELECT SUM(`quantity`) FROM `sar_patti` where is_active=1) as `patti_tray`,(SELECT SUM(`quantity`) FROM `sar_sales_invoice` where is_active=1) as `sales_tray`,(SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`) as `supplier_received`,(SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`)as `customer_received`,(SELECT customer_tray +
    sales_tray - customer_received)as `CUSTOMER`,(SELECT supplier_tray - patti_tray - supplier_received) as `SUPPLIER`,(SELECT (total_tray - SUPPLIER - CUSTOMER) )AS inhand_tray";
   
   $res3= $connect->prepare($query3);
 $res3->execute();
 $rec3=$res3->fetch(PDO::FETCH_ASSOC);


/* ---------------------- Income Inhand query Start------------------------ */
$tray_inhand_query="select sum(t1.total_bill_amount) as `sales`,sum(t2.total_bill_amount) as `cash`from ( select distinct `sales_no`, `total_bill_amount` from sar_sales_invoice WHERE date='".$today_date."' and is_active=0 ) t1,( select distinct `cash_no`, `total_bill_amount` from sar_cash_carry WHERE date='".$today_date."' and is_active=0) t2";
$tray_inhand_sql= $connect->prepare($tray_inhand_query);
 $tray_inhand_sql->execute();
 $tray_inhand_row=$tray_inhand_sql->fetch(PDO::FETCH_ASSOC);

$tray_inhand_rev_query="select sum(t1.amount) as `revenue`from ( select distinct `revenue_no`, `amount` from sar_miscellaneous_revenue WHERE date='".$today_date."' ) t1";
$tray_inhand_rev_sql= $connect->prepare($tray_inhand_rev_query);
 $tray_inhand_rev_sql->execute();
 $tray_inhand_rev_row=$tray_inhand_rev_sql->fetch(PDO::FETCH_ASSOC);

$tray_inhand=$tray_inhand_rev_row["revenue"]-$tray_inhand_row["sales"]+$tray_inhand_row["cash"]-$revenue_expenditure_row['expenditure'];
$yesterday_rev_query="SELECT sum(amount) as `yesterday_revenue` FROM sar_miscellaneous_revenue WHERE DATE(date) = DATE(NOW() - INTERVAL 1 DAY)";
$yesterday_rev_sql= $connect->prepare($yesterday_rev_query);
 $yesterday_rev_sql->execute();
 $yesterday_rev_row=$yesterday_rev_sql->fetch(PDO::FETCH_ASSOC);

$previous_revenue = $yesterday_rev_row["yesterday_revenue"];
// $yesterday_tray['balance'] = $previous_revenue - $tray_inhand_row["sales"] + $tray_inhand_row["cash"] - $revenue_expenditure_row['expenditure'] ;

/* ---------------------- Income Inhand query End------------------------ */


/* ------------Available with customer & Supplier query Start------------ */

$cust_sup_qry="SELECT (SELECT SUM(`total_trays`) FROM `sar_trays` ) as `total_tray`, 
coalesce((SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ),0) as `supplier_tray`,
coalesce((SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ),0) as `customer_tray`,
coalesce((SELECT SUM(`quantity`) FROM `sar_patti` where is_active=1),0) as `patti_tray`,
coalesce((SELECT SUM(`quantity`) FROM `sar_sales_invoice` WHERE is_active=1),0) as `sales_tray`,
coalesce((SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`),0) as `supplier_received`,
coalesce((SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`),0) as `customer_received`,
(SELECT customer_tray + sales_tray - customer_received) as `CUSTOMER`,

(SELECT supplier_tray - patti_tray - supplier_received) as `SUPPLIER`,

(SELECT (total_tray - SUPPLIER - CUSTOMER) ) AS inhand_tray";
   $cus_sup_res= $connect->prepare($cust_sup_qry);
 $cus_sup_res->execute();
 
// $cus_sup_res=mysqli_query($con,$cust_sup_qry);

$query4="SELECT sum(inhand) as inhand_sum FROM tray_transactions WHERE category= 'Farmer'";
$res4= $connect->prepare($query4);
 $res4->execute();
// $res4=mysqli_query($con,$query4);

/* ------------Available with customer & Supplier query Start------------ */

/* ---------------------- Today Revenue query Start------------------------ */

$today_rev_qry="SELECT sum(amount) as `today_revenue` FROM sar_miscellaneous_revenue WHERE DATE(date) = DATE(NOW())";
$today_rev_sql= $connect->prepare($today_rev_qry);
 $today_rev_sql->execute();
 $today_rev_row=$today_rev_sql->fetch(PDO::FETCH_ASSOC);

$today_revenue= $expendituere_rec['Amount'] + $net_row['sum'] - $sales_row['sales'] + $cash_row['cash'] + $miscellaneous_row['miscellaneous'];

/* ---------------------- Today Revenue query End------------------------ */


/* ---------------------- Stock Status query start------------------------ */

$stock_status_qry="select sum(t1.boxes_arrived) as `patti_box` from ( select distinct `patti_id`, `boxes_arrived` from sar_patti WHERE patti_date='".$today_date."' and is_active=1 ) t1";
$stock_status_sql= $connect->prepare($stock_status_qry);
 $stock_status_sql->execute();
 $stock_status_row=$stock_status_sql->fetch(PDO::FETCH_ASSOC);



$stock_sales_qry="select sum(t1.boxes_arrived) as `sales_box` from ( select distinct `sales_no`, `boxes_arrived` from sar_sales_invoice WHERE date='".$today_date."' and is_active=1 ) t1";
$stock_sales_sql= $connect->prepare($stock_sales_qry);
 $stock_sales_sql->execute();
 $stock_sales_row=$stock_sales_sql->fetch(PDO::FETCH_ASSOC);
// $stock_sales_sql=mysqli_query($con,$stock_sales_qry);
// $stock_sales_row=mysqli_fetch_assoc($stock_sales_sql);
//$stock_sales=$stock_sales_row['sales_box']+$stock_sales_row['cash_box'];

$stock_cash_qry="select sum(quantity) as `cash_box` from ( select distinct `cash_no`, `quantity` from sar_cash_carry WHERE date='".$today_date."' and cash_no LIKE '%CC_%' and is_active=1) t1";
$stock_cash_sql= $connect->prepare($stock_cash_qry);
 $stock_cash_sql->execute();
 $stock_cash_row=$stock_cash_sql->fetch(PDO::FETCH_ASSOC);

$stock_sales=$stock_sales_row['sales_box']+$stock_cash_row['cash_box'];

$stock_wastage_qry="select sum(total_quantity) as `wastage` from ( select distinct `wastage_id`, `total_quantity` from sar_wastage WHERE created_at='".$today_date."' ) t1";
$stock_wastage_sql= $connect->prepare($stock_wastage_qry);
 $stock_wastage_sql->execute();
 $stock_wastage_row=$stock_wastage_sql->fetch(PDO::FETCH_ASSOC);
// $stock_wastage_sql=mysqli_query($con,$stock_wastage_qry);
// $stock_wastage_row=mysqli_fetch_assoc($stock_wastage_sql);

$balance= $stock_status_row['patti_box'] - $stock_sales - $stock_wastage_row['wastage'];

////////////////////////////////Duplicate////////////////////////////////////

$stock_status_qry2="select sum(t1.boxes_arrived) as `patti_box` from ( select distinct `patti_id`, `boxes_arrived` from sar_patti WHERE is_active=1 ) t1";
$stock_status_sql2= $connect->prepare($stock_status_qry2);
 $stock_status_sql2->execute();
 $stock_status_row2=$stock_status_sql2->fetch(PDO::FETCH_ASSOC);
// $stock_status_sql2=mysqli_query($con,$stock_status_qry2);
// $stock_status_row2=mysqli_fetch_assoc($stock_status_sql2);

$stock_sales_qry2="select sum(t1.boxes_arrived) as `sales_box` from ( select distinct `sales_no`, `boxes_arrived` from sar_sales_invoice WHERE is_active=1 ) t1";
$stock_sales_sql2= $connect->prepare($stock_sales_qry2);
 $stock_sales_sql2->execute();
 $stock_sales_row2=$stock_sales_sql2->fetch(PDO::FETCH_ASSOC);
// $stock_sales_sql2=mysqli_query($con,$stock_sales_qry2);
// $stock_sales_row2=mysqli_fetch_assoc($stock_sales_sql2);
//$stock_sales=$stock_sales_row['sales_box']+$stock_sales_row['cash_box'];

$stock_cash_qry2="select sum(quantity) as `cash_box` from ( select distinct `cash_no`, `quantity` from sar_cash_carry WHERE cash_no LIKE '%CC_%' and is_active=1) t1";
$stock_cash_sql2= $connect->prepare($stock_cash_qry2);
 $stock_cash_sql2->execute();
 $stock_cash_row2=$stock_cash_sql2->fetch(PDO::FETCH_ASSOC);
// $stock_cash_sql2=mysqli_query($con,$stock_cash_qry2);
// $stock_cash_row2=mysqli_fetch_assoc($stock_cash_sql2);
$stock_sales2=$stock_sales_row2['sales_box']+$stock_cash_row2['cash_box'];

$stock_wastage_qry2="select sum(total_quantity) as `wastage` from ( select distinct `wastage_id`, `total_quantity` from sar_wastage ) t1";
$stock_wastage_sql2= $connect->prepare($stock_wastage_qry2);
 $stock_wastage_sql2->execute();
 $stock_wastage_row2=$stock_wastage_sql2->fetch(PDO::FETCH_ASSOC);
// $stock_wastage_sql2=mysqli_query($con,$stock_wastage_qry2);
// $stock_wastage_row2=mysqli_fetch_assoc($stock_wastage_sql2);

$balance2= $stock_status_row2['patti_box'] - $stock_sales2 - $stock_wastage_row2['wastage'];



$old_stock_qry2="select sum(quantity) as patti_old from sar_patti WHERE is_active=1 AND DATE(patti_date) = DATE(NOW() - INTERVAL 1 DAY)";
$old_stock_sql2= $connect->prepare($old_stock_qry2);
 $old_stock_sql2->execute();
 $old_stock_row2=$old_stock_sql2->fetch(PDO::FETCH_ASSOC);
// $old_stock_sql2=mysqli_query($con,$old_stock_qry2);
// $old_stock_row2=mysqli_fetch_assoc($old_stock_sql2);
$old_stock2 = $old_stock_row2['patti_old'];
$current_stock2= $stock_status_row2['patti_box']+$old_stock2;
////////////////////////////////////Duplicate//////////////////////////////////

/* ---------------------- Stock Status query End------------------------ */
                           

/* ---------------------- Revenue Status query start------------------------ */


$net_query="select sum(net_bill_amount) as `sum`,SUM(commision)as `commision`,sum(cooli) as `cooli`,sum(lorry_hire) as `lorry`,sum(box_charge)as `box`from ( select distinct `patti_id`, `net_bill_amount`,`commision`,`cooli`,`lorry_hire`,`box_charge` from sar_patti WHERE patti_date='".$today_date."' and is_active=1) t1"   ;
$net_sql= $connect->prepare($net_query);
 $net_sql->execute();
 $net_row=$net_sql->fetch(PDO::FETCH_ASSOC);
// $net_sql=mysqli_query($con,$net_query);
// $net_row=mysqli_fetch_assoc($net_sql);

$net_query2="select sum(net_bill_amount) as `sum`,SUM(commision)as `commision`,sum(cooli) as `cooli`,sum(lorry_hire) as `lorry`,sum(box_charge)as `box`from ( select distinct `patti_id`, `net_bill_amount`,`commision`,`cooli`,`lorry_hire`,`box_charge` from sar_patti WHERE patti_date='".$today_date."' and is_active=1) t1"   ;
$net_sql2= $connect->prepare($net_query2);
 $net_sql2->execute();
 $net_row2=$net_sql2->fetch(PDO::FETCH_ASSOC);
// $net_sql2=mysqli_query($con,$net_query2);
// $net_row2=mysqli_fetch_assoc($net_sql2);

$sales_query="select sum(bill_amount) as `sales` from ( select distinct `sales_no`, `bill_amount` from sar_sales_invoice WHERE date='".$today_date."' and is_active=1) t1";
$sales_sql= $connect->prepare($sales_query);
 $sales_sql->execute();
 $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
// $sales_sql=mysqli_query($con,$sales_query);
// $sales_row=mysqli_fetch_assoc($sales_sql);
$cash_query="select sum(bill_amount) as `cash` from ( select distinct `cash_no`, `bill_amount` from sar_cash_carry WHERE date='".$today_date."' and cash_no LIKE '%CC_%' and is_active=1) t1";
$cash_sql= $connect->prepare($cash_query);
 $cash_sql->execute();
 $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
// $cash_sql=mysqli_query($con,$cash_query);
// $cash_row=mysqli_fetch_assoc($cash_sql);
$sales=$sales_row['sales'] + $cash_row['cash'];

$revenue_expenditure_qry="select sum(amount) as `expenditure` from ( select distinct `id`, `amount` from sar_expenditure WHERE date='".$today_date."' ) t1";
$revenue_expenditure_sql= $connect->prepare($revenue_expenditure_qry);
 $revenue_expenditure_sql->execute();
 $revenue_expenditure_row=$revenue_expenditure_sql->fetch(PDO::FETCH_ASSOC);
// $revenue_expenditure_sql=mysqli_query($con,$revenue_expenditure_qry);
// $revenue_expenditure_row=mysqli_fetch_assoc($revenue_expenditure_sql);
$miscellaneous_query="select sum(amount) as `miscellaneous` from ( select distinct `revenue_no`, `amount` from sar_miscellaneous_revenue WHERE date='".$today_date."' ) t1";
$miscellaneous_sql= $connect->prepare($miscellaneous_query);
 $miscellaneous_sql->execute();
 $miscellaneous_row=$miscellaneous_sql->fetch(PDO::FETCH_ASSOC);
// $miscellaneous_sql=mysqli_query($con,$miscellaneous_query);
// $miscellaneous_row=mysqli_fetch_assoc($miscellaneous_sql);

/* ---------------------- Revenue Status query end------------------------ */

/* ---------------------- Expenditure query start----------------------- */

$expenditure_qry="SELECT SUM(amount) AS Amount from sar_expenditure where date='".$today_date."'";
$expenditure_res= $connect->prepare($expenditure_qry);
 $expenditure_res->execute();
 $expendituere_rec=$expenditure_res->fetch(PDO::FETCH_ASSOC);
// $expenditure_res=mysqli_query($con,$expenditure_qry);
// $expendituere_rec=mysqli_fetch_assoc($expenditure_res);
$expenditure_qry2="SELECT SUM(amount) AS Amount from sar_expenditure ";
$expenditure_res2= $connect->prepare($expenditure_qry2);
 $expenditure_res2->execute();
 $expendituere_rec2=$expenditure_res2->fetch(PDO::FETCH_ASSOC);
// $expenditure_res2=mysqli_query($con,$expenditure_qry2);
// $expendituere_rec2=mysqli_fetch_assoc($expenditure_res2);

/* ---------------------- Expenditure query end------------------------ */

/* ---------------------- Actual Patti query start------------------------ */

$query7="SELECT sum(bill_amount) as total_bill_amount FROM `sar_patti` WHERE is_active=1 AND patti_date='".$today_date."' and is_active=1";
$res7= $connect->prepare($query7);
 $res7->execute();
 $rec7=$res7->fetch(PDO::FETCH_ASSOC);
// $res7=mysqli_query($con,$query7);
// $rec7=mysqli_fetch_assoc($res7);

/* ---------------------- Actual Patti query End------------------------ */

/* ---------------------- Profit & Loss query Start------------------------ */

$result=$sales_row['sales'] + $cash_row['cash'] + $net_row['commision'] + $miscellaneous_row['miscellaneous'] - $net_row['sum'] - $net_row['cooli'] - $net_row['lorry'] - $net_row['box'] - $revenue_expenditure_row['amount'] ;

/* ---------------------- Profit & Loss query End------------------------ */

/* ---------------------- No of boxes arrived query End---------------------- */

$query9="SELECT sum(quantity)as boxes_arrived FROM sar_patti WHERE is_active=1 AND patti_date='".$today_date."'";
$res9= $connect->prepare($query9);
 $res9->execute();
 $rec9=$res9->fetch(PDO::FETCH_ASSOC);
// $res9=mysqli_query($con,$query9);
// $rec9=mysqli_fetch_assoc($res9);

/* ---------------------- No of boxes arrived query End---------------------- */

/*----------------------Incoming Revenue query Start--------------------------*/


$incoming_revenue_qry="select sum(amount) as `sales` from ( select distinct `customer_id`, `amount` from sar_sales_payment WHERE is_revoked IS NULL ) t1";
$incoming_revenue_sql= $connect->prepare($incoming_revenue_qry);
 $incoming_revenue_sql->execute();
 $incoming_revenue_row=$incoming_revenue_sql->fetch(PDO::FETCH_ASSOC);
// $incoming_revenue_sql=mysqli_query($con,$incoming_revenue_qry);
// $incoming_revenue_row=mysqli_fetch_assoc($incoming_revenue_sql);
$incoming_revenue2=$incoming_revenue_row['sales'] + $cash_row['cash'] + $miscellaneous_row['miscellaneous'];


$incoming_qry="select sum(amount) as `sales` from ( select distinct `customer_id`, `amount` from sar_sales_payment WHERE payment_date='".$today_date."' and is_revoked IS NULL ) t1";
$incoming_qry_sql= $connect->prepare($incoming_qry);
 $incoming_qry_sql->execute();
 $incoming_qry_row=$incoming_qry_sql->fetch(PDO::FETCH_ASSOC);
// $incoming_qry_sql=mysqli_query($con,$incoming_qry);
// $incoming_qry_row=mysqli_fetch_assoc($incoming_qry_sql);

$incoming_revenue=$incoming_qry_row['sales'] + $cash_row['cash'] + $miscellaneous_row['miscellaneous'];

/*------------------------Incoming Revenue query End--------------------------*/

/*----------------------Outgoing Revenue query Start--------------------------*/
 $outgoing_qry="select sum(amount) as `patti` from ( select distinct `supplier_id`, `amount` from sar_patti_payment WHERE payment_date='".$today_date."' and is_revoked IS NULL) t1";
 $outgoing_res= $connect->prepare($outgoing_qry);
 $outgoing_res->execute();
 $outgoing_row=$outgoing_res->fetch(PDO::FETCH_ASSOC);
// $outgoing_res=mysqli_query($con,$outgoing_qry);
// $outgoing_row=mysqli_fetch_assoc($outgoing_res);
$outgoing_revenue= $expendituere_rec['Amount']+$outgoing_row['patti'];

$outgoing_qry2="select sum(amount) as `patti` from ( select distinct `supplier_id`, `amount` from sar_patti_payment WHERE is_revoked IS NULL) t1";
$outgoing_res2= $connect->prepare($outgoing_qry2);
 $outgoing_res2->execute();
 $outgoing_row2=$outgoing_res2->fetch(PDO::FETCH_ASSOC);
// $outgoing_res2=mysqli_query($con,$outgoing_qry2);
// $outgoing_row2=mysqli_fetch_assoc($outgoing_res2);
$outgoing_revenue2= $expendituere_rec2['Amount']+$outgoing_row2['patti'];
/*------------------------Outgoing Revenue query End--------------------------*/

/*----------------------- No of Patti Query Start ---------------------------*/
$no_of_patti_qry="SELECT COUNT(DISTINCT patti_id) as `patti_count` FROM sar_patti WHERE patti_date='".$today_date."' and is_active=1";
$no_of_patti_sql= $connect->prepare($no_of_patti_qry);
 $no_of_patti_sql->execute();
 $rowcount=$no_of_patti_sql->fetch(PDO::FETCH_ASSOC);
// $no_of_patti_sql=mysqli_query($con,$no_of_patti_qry);
// $rowcount=mysqli_fetch_assoc($no_of_patti_sql);


?>

  <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                
                   

                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card-body">
                                <div class="d-flex d-flex align-items-center justify-content-between">
                                   <div>
                                       <h2><?php 
                                        // $rec3=mysqli_fetch_assoc($res3);
                                          if($previous_revenue== 0 ||$previous_revenue== NULL)
                                          {
                                              echo "0";
                                              
                                          }
                                          else{
                                              echo $previous_revenue;
                                          }
                                        ?></h2>
                                       <p class="fontsize-sm m-0">Previous Balance</p>
                                   </div>
                                   <div class="rounded-circle iq-card-icon dark-icon-light iq-bg-primary "> <i class="ri-inbox-fill"></i></div>
                                </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="d-flex d-flex align-items-center justify-content-between">
                              <div>
                                  <h2>
                                       <?php  
                                        if($incoming_revenue == 0 && $incoming_revenue == NULL)
                                        {
                                            echo "0";
                                        }                                  
                                        else
                                        {
                                            echo $incoming_revenue;
                                        }
                                       ?></h2>
                                  <p class="fontsize-sm m-0">Incoming Revenue</p>
                              </div>
                              <div class="rounded-circle iq-card-icon iq-bg-danger"><i class="ri-radar-line"></i></div>
                           </div>
                         </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card-body">
                           <div class="d-flex d-flex align-items-center justify-content-between">
                              <div>
                                  <h2>
                                        <?php  
                                        if($outgoing_revenue == 0 && $outgoing_revenue == NULL)
                                        {
                                            echo "0";
                                        }                                  
                                        else
                                        {
                                            echo $outgoing_revenue;
                                        }
                                       ?>
                                  </h2>
                                  <p class="fontsize-sm m-0">Outgoing Revenue</p>
                              </div>
                              <div class="rounded-circle iq-card-icon iq-bg-warning "><i class="ri-price-tag-3-line"></i></div>
                           </div>
                       </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card-body">
                           <div class="d-flex d-flex align-items-center justify-content-between">
                              <div>
                                  <h2>
                                      <?php
                                     echo $incoming_revenue2 - $outgoing_revenue2;
                                      ?>
                                  </h2>
                                  <p class="fontsize-sm m-0">Cash Inhand</p>
                              </div>
                              <div class="rounded-circle iq-card-icon iq-bg-info "><i class="ri-refund-line"></i></div>
                           </div>
                       </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                
                  <div class="col-lg-4">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                  <h2 style="color:#a5aab5;" class="text-center">Stock Status</h2><br>
                                   <ul class="suggestions-lists m-0 p-0">
                              <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon " style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Received today</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         
                                              if($stock_status_row['patti_box']=="" || $stock_status_row['patti_box']==NULL){
                                           echo "0";
                                       } else {
                                           echo $stock_status_row['patti_box'];
                                       }
                                    ?> </h6></div></li>
                                    <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Old Stock</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         if($old_stock2=="" || $old_stock2==NULL){
                                           echo "0";
                                       } else {
                                           echo $old_stock2;
                                       }
                                    ?> </h6></div>
                                    </li>
                                 <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Today Stock</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         if($current_stock2=="" || $current_stock2==NULL){
                                           echo "0";
                                       } else {
                                           echo $current_stock2;
                                       }
                                    ?> </h6></div></li>
                                    
                                    
                                <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Sold Today</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         if($stock_sales=="" || $stock_sales==NULL){
                                           echo "0";
                                       } else {
                                           echo $stock_sales;
                                       }
                                    ?> </h6></div></li>
                                     <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Wastage</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         
                                             if($stock_wastage_row['wastage']=="" || $stock_wastage_row['wastage']==NULL){
                                           echo "0";
                                       } else {
                                           echo $stock_wastage_row['wastage'];
                                       }
                                    ?> </h6></div></li>
                          
                                    <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#e6d5f4;color:#8852b6;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Balance</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         
                                             if($balance2 =="" || $balance2==NULL){
                                           echo "0";
                                       } else {
                                           echo $balance2;
                                       }
                                    ?> </h6></div>
                                    </li>
                              </div>
                             
                           </div>
                         </div>
                     </div>
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                  <h2 style="color:#a5aab5;" class="text-center">Patti Details</h2><br>
                              <ul class="suggestions-lists m-0 p-0">
                              <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2b7c6;color:#d65073;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Actual Patti</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                     ₹. <?php  
                                      if($rec7['total_bill_amount']=="" || $rec7['total_bill_amount']==NULL){
                                           echo "0";
                                       } else {
                                           echo $rec7['total_bill_amount'];
                                       }
                                    ?> /-
                                        
                                    </h6></div></li>
                                 
                                    <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2b7c6;color:#d65073;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>No of Patti</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                        echo $rowcount['patti_count'];
                                    ?> </h6></div></li>
                          <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2b7c6;color:#d65073;"
                         ><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>No of Boxes Arrived</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">
                                       <?php  
                                      if($rec9['boxes_arrived']=="" || $rec9['boxes_arrived']==NULL){
                                           echo "0";
                                       } else {
                                           echo $rec9['boxes_arrived'];
                                       }
                                    ?> </h6></div></li>
                              </div>
                             
                           </div>
                         </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                             
                                  <h2 style="color:#a5aab5;" class="text-center">Revenue </h2><br>
                                  
                                  <ul class="suggestions-lists m-0 p-0">
                           <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success" ><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                                  <h6>Net Patti</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800;" class="text-center">₹.
                                      <?php  
                                        
                                            if($net_row['sum']=="" || $net_row['sum']==NULL){
                                           echo "0";
                                       } else {
                                           echo $net_row['sum'];
                                       }
                                    ?> /-</h6>
                                    </div>
                                     </li>
                                   <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Cooli</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                               if($net_row['cooli']=="" || $net_row['cooli']==NULL){
                                           echo "0";
                                       } else {
                                           echo $net_row['cooli'];
                                       }
                                    ?> /-</h6>
                                    </div>
                                    </li>
                                    
                         <!--           <li class="d-flex mb-4 align-items-center">-->
                         <!--<div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>-->
                         <!-- <div class="media-support-info ml-3">-->
                         <!--     <h6>Commision</h6>-->
                         <!--         </div>-->
                                  
                         <!--         <div class="media-support-amount ml-3">-->
                         <!--  <h6 style="font-weight:800" class="text-center">₹.-->
                         <!--           
                                         
                         <!--                    if($net_row['commision']=="" || $net_row['commision']==NULL){-->
                         <!--                  echo "0";-->
                         <!--              } else {-->
                         <!--                  echo $net_row['commision'];-->
                         <!--              }-->
                         <!--           ?> /-</h6>-->
                         <!--           </div>-->
                         <!--           </li>-->
                                    
                                          <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Box Charge</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                         
                                             if($net_row['box']=="" || $net_row['box']==NULL){
                                           echo "0";
                                       } else {
                                           echo $net_row['box'];
                                       }
                                    ?> /-</h6></div>
                                    </li>
                                    
                                           <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Lorry Hire</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                          if($net_row['lorry']=="" || $net_row['lorry']==NULL){
                                           echo "0";
                                       } else {
                                           echo $net_row['lorry'];
                                       }
                                    ?> /-</h6></div>
                                    </li>
                                    
                                         <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Sales</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                       
                                       if($sales_row['sales']=="" || $sales_row['sales']==NULL ){
                                           echo "0";
                                       } else {
                                           echo $sales;
                                       }
                                    ?> /-</h6></div></li>
                                    
                                      
                                      <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Expenditure</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                            
                                            if($revenue_expenditure_row['expenditure']=="" || $revenue_expenditure_row['expenditure']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_expenditure_row['expenditure'];
                                       }
                                    ?> /-</h6></div></li>
                                    
                                       <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Miscellaneous Revenue</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                    
                                            if($miscellaneous_row['miscellaneous']=="" || $miscellaneous_row['miscellaneous']==NULL){
                                           echo "0";
                                       } else {
                                           echo $miscellaneous_row['miscellaneous'];
                                       }
                                    ?> /-</h6></div></li>
                                    </ul>
                                     <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>P&L</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                           <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                            
                                           
                                           echo $result;
                                       
                                    ?> /-</h6></div></li>
                                   </div>
                           </div>
                       </div>
                       
                     </div>
                 
                 <div class=" col-lg-4">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                   <h2 style="color:#a5aab5;" class="text-center">Payments</h2><br>
                              <ul class="suggestions-lists m-0 p-0">
                              <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#bcd6f6;color:#5c9ff1;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Unsettled sales</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                             <h6 style="font-weight:800" class="text-center">₹.
                               
                                      <?php  
                                         
                                           echo $total_unsettled_sales;
                                       
                                    ?> /-</h6></div></li>
                                    
                              <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#bcd6f6;color:#5c9ff1;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Unsettled Patti</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                             <h6 style="font-weight:800" class="text-center">₹.
                                      <?php  
                                         echo $total_unsettled_patti;
                                    ?> /-</h6></div></li>
                                         
                                  
                              </div>
                             
                           </div>
                         </div>
                     </div>
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                  <h2 style="color:#a5aab5;" class="text-center">Tray Status</h2><br>
                              <ul class="suggestions-lists m-0 p-0">
                              <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2d0b7;color:#f29a59;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Available with Customers</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                             <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                        $cus_sup_rec=$cus_sup_res->fetch(PDO::FETCH_ASSOC);
                                        if($cus_sup_rec['CUSTOMER']== 0 )
                                        {
                                            echo "0";
                                        }
                                        else
                                        {
                                           echo $cus_sup_rec['CUSTOMER'];       }                          
                                    ?> </h6></div></li>
                                    <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2d0b7;color:#f29a59;"><span><i class="ri-check-fill"></i></span></div>
                          <div class="media-support-info ml-3">
                              <h6>Available with Suppliers</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                             <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                         if($cus_sup_rec['SUPPLIER']== 0 && $cus_sup_rec['SUPPLIER']== NULL)
                                           {
                                               echo "0";
                                           } 
                                           else
                                           {
                                           echo $cus_sup_rec['SUPPLIER'];      
                                           }
                                    ?> </h6></div></li>
                                     <li class="d-flex mb-4 align-items-center">
                         <div class="profile-icon" style="background:#f2d0b7;color:#f29a59;"><span><i class="ri-check-fill"></i></span></div>
                         <div class="media-support-info ml-3">
                              <h6>Inhand Trays</h6>
                                  </div>
                                  
                                  <div class="media-support-amount ml-3">
                             <h6 style="font-weight:800" class="text-center">
                                      <?php  
                                          if($cus_sup_rec['inhand_tray']== 0 && $cus_sup_rec['inhand_tray']== NULL)
                                          {
                                              echo "0";
                                          }  
                                          else
                                          {
                                          echo $cus_sup_rec['inhand_tray'];      }                         
                                    ?> </h6></div></li>
                             
                           </div>
                         </div>
                     </div>
                  </div>
                  
               </div>
              <div class="container">
                      <div class="row">
                  <div class="col-lg-12">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Daywise Report</h4>
                           </div>
                           
                        </div>
                        <div class="iq-card-body">
                           <div class="table-responsive">
                              <table id="example"  class="table mb-0 table-borderless">
                                  <thead>

                    <tr>

                        <th scope="col">Date</th>

                        <th  scope="col">Spent</th>

                        <th  scope="col">Revenue</th>

                        <th  scope="col">P/L</th>

                    </tr>

                </thead>
                                
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
                  
               </div>
              </div>
                     
               
               </div>
              
               </div>
              
            

 <?php
require "footer.php";
?>
<script>

//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){

       var table=$('#example').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_daywise_report",

                "type": "POST",

                "data": {
                    from: $("#from").val(),
                    to: $("#to").val()
                }

            },

            "columns": [

                { "data": "dateOnly" },

                { "data": "sum_debit" },

                { "data": "sum_credit" },

                { "data": "profit" }

              

            ],

             "order": [[ 1, 'asc' ]]

             

        });

        $("#submit").on("click",function(){

                var from=$("#from").val();

                var to=$("#to").val();

                if(from!="" && to!=""){

                    table.ajax.url("forms/ajax_request.php?action=view_daywise_report&from="+from+'&to='+to).load();

                    table.ajax.reload();

                } else {

                    table.ajax.url("forms/ajax_request.php?action=view_daywise_report").load();

                    table.ajax.reload();

                }

        });

        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_day_wise_report.php?from="+from+'&to='+to;
        });
    });

</script>