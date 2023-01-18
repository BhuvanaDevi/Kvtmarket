<?php
require "header.php";
session_start();
$dbHost = "localhost";
    $dbUsername="lanecqgh_sar";
    $dbPassword="sar@123";
    $dbName="lanecqgh_sar_tomato_erp";
    
    $con=mysqli_connect( $dbHost,$dbUsername, $dbPassword,$dbName);
    
    if($con)
    {
        // echo "<script>alert('connected')</script>";
    }
    else
    {
        // echo "<script>alert('not connected')</script>";
    }


// $query = mysql_query("
//     SELECT sum(boxes_arrived) AS box FROM sar_sales_invoice WHERE DATE(boxes_arrived) = '$today'
// ");   
// $res5=mysqli_query($con,$query);
?>
    
          
           <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                   <?php
                   
 $sql="SELECT * FROM  tray_transactions ORDER BY id DESC LIMIT 1";
        $result=mysqli_query($con,$sql);
        $row=mysqli_fetch_assoc($result);

        //print_r($_REQUEST); 
        if($row)
        {
            $inhand=$row['inhand'];
        }
        else
        {
            echo mysqli_error($connect);
        }
        
        
        
        
        if(date("d")=="01")
        {
$yesterday_date=date("Y")."-".(date("m")-1)."-".date("d")-1;
            
        }
        else
        {
$yesterday_date=date("Y")."-".date("m")."-".date("d")-1;
}

$today_date = date('Y-m-d');

        $query="SELECT sum(credit) as credit_sum, sum(debit) as debit_sum FROM   financial_transactions WHERE DATE(date)=DATE(NOW())";
    // $query="SELECT credit,debit, balance FROM   financial_transactions WHERE DATE(date)=CURDATE();
$res=mysqli_query($con,$query);

/////////////////////////////PAYMENTS///////////////////////////////////
   $query1="SELECT ( SELECT SUM(`total_bill_amount`) FROM sar_sales_invoice WHERE payment_status = 0) - (SELECT SUM(`amount`) From sar_sales_payment WHERE is_revoked is NULL) as total_bill_amount_sum";
$res1=mysqli_query($con,$query1);

$query2="SELECT sum(net_bill_amount) as total_bill_amount_sum FROM sar_patti WHERE is_active=1 and payment_status=1";
// $query2="SELECT ( SELECT SUM(`net_bill_amount`) FROM sar_patti WHERE payment_status = 1) - 
// (SELECT SUM(`amount`) From sar_patti_payment WHERE is_revoked is NULL) as total_bill_amount_sum";
/////////////////////////////////////////////////////////////////////////   
$res2=mysqli_query($con,$query2);

$query3="SELECT (SELECT SUM(`total_trays`) FROM `sar_trays` ) as `total_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ) as `supplier_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ) as `customer_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_patti`) as `patti_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_sales_invoice`) as `sales_tray`,(SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`) as `supplier_received`,(SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`)as `customer_received`,(SELECT customer_tray +
    sales_tray - customer_received)as `CUSTOMER`,(SELECT supplier_tray - patti_tray - supplier_received) as `SUPPLIER`,(SELECT (total_tray - SUPPLIER - CUSTOMER) )AS inhand_tray";
   
$res3=mysqli_query($con,$query3);
$cust_sup_qry="SELECT (SELECT SUM(`total_trays`) FROM `sar_trays` ) as `total_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ) as `supplier_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ) as `customer_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_patti`) as `patti_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_sales_invoice`) as `sales_tray`,(SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`) as `supplier_received`,(SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`)as `customer_received`,(SELECT customer_tray +
    sales_tray - customer_received)as `CUSTOMER`,(SELECT supplier_tray - patti_tray - supplier_received) as `SUPPLIER`,(SELECT (total_tray - SUPPLIER - CUSTOMER) )AS inhand_tray";
   
$cus_sup_res=mysqli_query($con,$cust_sup_qry);

$query4="SELECT sum(inhand) as inhand_sum FROM tray_transactions WHERE category= 'Supplier'";
   
$res4=mysqli_query($con,$query4);

$revenue_qry="SELECT (SELECT SUM(`total_bill_amount`) FROM `sar_sales_invoice` WHERE date='".$today_date."' ) as `SALES_BOX`,(SELECT SUM(`total_bill_amount`) FROM `sar_cash_carry` WHERE date='".$today_date."' ) as `CASH_BOX`,(SELECT SALES_BOX + CASH_BOX) as `SALES`,(SELECT SUM(net_bill_amount) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `net_patti`,(SELECT SUM(commision) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `commision`,(SELECT SUM(box_charge) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `box_charge`,(SELECT SUM(cooli) FROM sar_patti WHERE patti_date='".$today_date."')as `cooli`,(SELECT SUM(lorry_hire) FROM sar_patti WHERE patti_date='".$today_date."')as `lorry_hire`,(SELECT SUM(amount) FROM sar_expenditure WHERE date='".$today_date."') as `expenditure_amount`,(SELECT SALES  +  commision - net_patti - cooli - box_charge - lorry_hire - expenditure_amount) as`profit_loss`";
$revenue_sql=mysqli_query($con,$revenue_qry);

$revenue_qry1="SELECT (SELECT SUM(`total_bill_amount`) FROM `sar_sales_invoice` WHERE date='".$today_date."' ) as `SALES_BOX`,(SELECT SUM(`total_bill_amount`) FROM `sar_cash_carry` WHERE date='".$today_date."' ) as `CASH_BOX`,(SELECT SALES_BOX + CASH_BOX) as `SALES`,(SELECT SUM(net_bill_amount) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `net_patti`,(SELECT SUM(commision) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `commision`,(SELECT SUM(box_charge) FROM `sar_patti` WHERE patti_date = '".$today_date."') AS `box_charge`,(SELECT SUM(cooli) FROM sar_patti WHERE patti_date='".$today_date."')as `cooli`,(SELECT SUM(lorry_hire) FROM sar_patti WHERE patti_date='".$today_date."')as `lorry_hire`,(SELECT SUM(amount) FROM sar_expenditure WHERE date='".$today_date."') as `expenditure_amount`,(SELECT SALES  +  commision - net_patti - cooli - box_charge - lorry_hire - expenditure_amount) as`profit`";
$revenue_sql1=mysqli_query($con,$revenue_qry1);

$query6="SELECT (SELECT SUM(`boxes_arrived`) FROM `sar_sales_invoice` WHERE date='".$today_date."' ) as `SALES_BOX`,(SELECT SUM(`quantity`) FROM `sar_cash_carry` WHERE date='".$today_date."' ) as `CASH_BOX`,(SELECT SUM(boxes_arrived) FROM `sar_patti` WHERE DATE(patti_date) = DATE(NOW() - INTERVAL 1 DAY)) AS `old_stock`,(SELECT SUM(`boxes_arrived`) FROM `sar_patti` WHERE patti_date='".$today_date."') as `PATTI_BOX`,(SELECT PATTI_BOX + old_stock) as `received_box`,(SELECT SALES_BOX + CASH_BOX) AS TOTAL_SALE_BOX,(SELECT received_box - TOTAL_SALE_BOX) AS BALANCE";
   
$res6=mysqli_query($con,$query6);
$expenditure_qry="SELECT SUM(amount) AS Amount from sar_expenditure where date='".$today_date."'";
$expenditure_res=mysqli_query($con,$expenditure_qry);
?>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card-body">
                                <div class="d-flex d-flex align-items-center justify-content-between">
                                   <div>
                                       <h2><?php 
                                        $rec3=mysqli_fetch_assoc($res3);
                                           echo $rec3['inhand_tray'];                                 
                                        ?></h2>
                                       <p class="fontsize-sm m-0">Tray Inhand</p>
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
                                          $revenue_rec1=mysqli_fetch_assoc($revenue_sql1);
                                            if($revenue_rec1['profit']=="" || $revenue_rec1['profit']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec1['profit'];
                                       }
                                       ?></h2>
                                  <p class="fontsize-sm m-0">Today Revenue</p>
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
                                      ₹.
                                        <?php  
                                         $expendituere_rec=mysqli_fetch_assoc($expenditure_res);
                                              echo $expendituere_rec['Amount']
                                         ?></h2>
                                  </h2>
                                  <p class="fontsize-sm m-0">Expenditure</p>
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
                                  <h2>27h</h2>
                                  <p class="fontsize-sm m-0">Payment Delay</p>
                              </div>
                              <div class="rounded-circle iq-card-icon iq-bg-info "><i class="ri-refund-line"></i></div>
                           </div>
                       </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <!--<div class="col-md-6 col-lg-7">-->
                  <!--   <div class="iq-card iq-card-block iq-card-stretch iq-card-height overflow-hidden">-->
                  <!--      <div class="iq-card-header d-flex justify-content-between">-->
                  <!--         <div class="iq-header-title">-->
                  <!--            <h4 class="card-title">Invoice Stats</h4>-->
                  <!--         </div>-->
                  <!--         <div class="iq-card-header-toolbar d-flex align-items-center">-->
                  <!--            <div class="dropdown">-->
                  <!--               <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">-->
                  <!--               <i class="ri-more-fill"></i>-->
                  <!--               </span>-->
                  <!--               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" >-->
                  <!--                  <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>-->
                  <!--                  <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>-->
                  <!--                  <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>-->
                  <!--                  <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>-->
                  <!--                  <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>-->
                  <!--               </div>-->
                  <!--            </div>-->
                  <!--         </div>-->
                  <!--      </div>-->
                  <!--      <div class="iq-card-body">-->
                  <!--      <div id="home-chart-02"></div>-->
                  <!--   </div>-->
                  <!--   </div>-->
                  <!--</div>-->
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                  <h2 style="color:#ff2929;" class="text-center">Stock Status</h2>
                              <p class="text-center">Received Today</p>
                                <h4 style="color:black;" class="text-center">
                                      <?php  
                                         $rec6=mysqli_fetch_assoc($res6);
                                              if($rec6['received_box']=="" || $rec6['received_box']==NULL){
                                           echo "0";
                                       } else {
                                           echo $rec6['received_box'];
                                       }
                                    ?> 
                                </h4>
                                 
                                    <p class="text-center">Sold Today</p>
                           <h4 style="color:black;" class="text-center">
                                      <?php  
                                         if($rec6['TOTAL_SALE_BOX']=="" || $rec6['TOTAL_SALE_BOX']==NULL){
                                           echo "0";
                                       } else {
                                           echo $rec6['TOTAL_SALE_BOX'];
                                       }
                                    ?> </h4>
                          <p class="text-center">Balance Stock Inhand</p>
                           <h4 style="color:black;" class="text-center">
                                      <?php  
                                         
                                             if($rec6['BALANCE']=="" || $rec6['BALANCE']==NULL){
                                           echo "0";
                                       } else {
                                           echo $rec6['BALANCE'];
                                       }
                                    ?> </h4>
                              </div>
                             
                           </div>
                         </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="iq-card-body rounded p-0" style="background:  no-repeat;background-size: cover; height: fit-content;">
                              <div>
                                  <h2 style="color:#ff2929;" class="text-center">Revenue </h2>
                                  <p class="text-center">Net Patti</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                          $revenue_rec=mysqli_fetch_assoc($revenue_sql);
                                            if($revenue_rec['net_patti']=="" || $revenue_rec['net_patti']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['net_patti'];
                                       }
                                    ?> /-</h4>
                                            <p class="text-center">Cooli</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                               if($revenue_rec['cooli']=="" || $revenue_rec['cooli']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['cooli'];
                                       }
                                    ?> /-</h4>
                                     <p class="text-center">Commision</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                         
                                             if($revenue_rec['commision']=="" || $revenue_rec['commision']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['commision'];
                                       }
                                    ?> /-</h4>
                                     <p class="text-center">Box Charge</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                         
                                             if($revenue_rec['box_charge']=="" || $revenue_rec['box_charge']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['box_charge'];
                                       }
                                    ?> /-</h4>
                                     <p class="text-center">Lorry Hire </p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                          if($revenue_rec['lorry_hire']=="" || $revenue_rec['lorry_hire']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['lorry_hire'];
                                       }
                                    ?> /-</h4>
                              <p class="text-center">Sales</p>
                           <h4 style="color:black;" class="text-center">
                                      <?php  
                                       
                                       if($revenue_rec['SALES']=="" || $revenue_rec['SALES']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['SALES'];
                                       }
                                    ?> /-</h4>
                                    
                                      
                               
                                      
                                       <p class="text-center">Expenditure</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                            
                                            if($revenue_rec['expenditure_amount']=="" || $revenue_rec['expenditure_amount']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['expenditure_amount'];
                                       }
                                    ?> /-</h4>
                                       <p class="text-center">P&L</p>
                           <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                    
                                            if($revenue_rec['profit_loss']=="" || $revenue_rec['profit_loss']==NULL){
                                           echo "0";
                                       } else {
                                           echo $revenue_rec['profit_loss'];
                                       }
                                    ?> /-</h4>
                           </div>
                       </div>
                     </div>
                  </div>
                  </div>
                 <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="d-flex d-flex align-items-center justify-content-between">
                              <div>
                                   <h2 style="color:#00cfde;" class="text-center"><i class="ri-bank-card-line"></i>&nbsp;Payments</h2>
                             <p style="color:black;" class="text-center">Unsettled Sales Invoice</p>
                             <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                         $rec1=mysqli_fetch_assoc($res1);
                                              echo $rec1['total_bill_amount_sum'];
                                    ?> /-</h4>
                                    <br>
                             <p style="color:black;" class="text-center">Unsettled Patti</p>
                             <h4 style="color:black;" class="text-center">₹.
                                      <?php  
                                         $rec2=mysqli_fetch_assoc($res2);
                                              echo $rec2['total_bill_amount_sum'];
                                    ?> /-</h4>
                                         
                                  
                              </div>
                             
                           </div>
                         </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-3">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        
                        <div class="iq-card-body">
                           <div class="d-flex d-flex align-items-center justify-content-between">
                              <div>
                                  <h2 style="color:#fad02e;" class="text-center">Tray Status</h2>
                             <p style="color:black;" class="text-center">Customer</p>
                             <h4 style="color:black;" class="text-center">
                                      <?php  
                                        $cus_sup_rec=mysqli_fetch_assoc($cus_sup_res);
                                           echo $cus_sup_rec['CUSTOMER'];                                 
                                    ?> </h4>
                                    <br>
                             <p style="color:black;" class="text-center">Supplier</p>
                             <h4 style="color:black;" class="text-center">
                                      <?php  
                                         
                                            
                                           echo $cus_sup_rec['SUPPLIER'];                              
                                    ?> </h4>
                                         
                                  
                              </div>
                             
                           </div>
                         </div>
                     </div>
                  </div>
                  <div class="col-md-3 col-lg-3">
                    
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-8">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Open Invoices</h4>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton5" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <div class="table-responsive">
                              <table class="table mb-0 table-borderless">
                                 <thead>
                                    <tr>
                                       <th scope="col">Client</th>
                                       <th scope="col">Date</th>
                                       <th scope="col">Invoice</th>
                                       <th scope="col" class="text-right">Amount</th>
                                       <th scope="col" class="text-center">Status</th>
                                       <th scope="col">Action</th>

                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td>Ira Membrit</td>
                                       <td>18/10/2019</td>
                                       <td>20156</td>
                                       <td class="text-right">$1500.00</td>
                                       <td class="text-center"><div class="badge badge-pill iq-bg-success">Paid</div></td>
                                       <td>
                                          <i class="ri-ball-pen-fill text-success pr-1"></i>
                                          <i class="ri-delete-bin-5-line text-danger"></i>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td>Pete Sariya</td>
                                       <td>26/10/2019</td>
                                       <td>7859</td>
                                       <td class="text-right">$2000.00</td>
                                       <td class="text-center"><div class="badge badge-pill iq-bg-success">Paid</div></td>
                                       <td>
                                          <i class="ri-ball-pen-fill text-success pr-1"></i>
                                          <i class="ri-delete-bin-5-line text-danger"></i>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td>Cliff Hanger</td>
                                       <td>18/11/2019</td>
                                       <td>6396</td>
                                       <td class="text-right">$2500.00</td>
                                       <td class="text-center"><div class="badge badge-pill iq-bg-danger">Past Due</div></td>
                                       <td>
                                          <i class="ri-ball-pen-fill text-success pr-1"></i>
                                          <i class="ri-delete-bin-5-line text-danger"></i>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td>Terry Aki</td>
                                       <td>14/12/2019</td>
                                       <td>7854</td>
                                       <td class="text-right">$5000.00</td>
                                       <td class="text-center"><div class="badge badge-pill iq-bg-success">Paid</div></td>
                                       <td>
                                          <i class="ri-ball-pen-fill text-success pr-1"></i>
                                          <i class="ri-delete-bin-5-line text-danger"></i>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td>Anna Mull</td>
                                       <td>24/12/2019</td>
                                       <td>568569</td>
                                       <td class="text-right">$10000.00</td>
                                       <td class="text-center"><div class="badge badge-pill iq-bg-success">Paid</div></td>
                                       <td>
                                          <i class="ri-ball-pen-fill text-success pr-1"></i>
                                          <i class="ri-delete-bin-5-line text-danger"></i>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Monthly Invoices</h4>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown" >
                                 <i class="ri-more-fill"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" >
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="iq-card-body">
                          <ul class="suggestions-lists m-0 p-0">
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Camelun ios</h6>
                                 <p class="mb-0 fontsize-sm"><span class="text-success">17/23</span> months paid</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6><span class="text-secondary">$</span><b> 16,634.00</b></h6>
                                 <p class="mb-0 d-flex justify-content-end">per month</p>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon iq-bg-warning"><span><i class="ri-check-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>React</h6>
                                 <p class="mb-0 fontsize-sm"><span class="text-warning">12 weeks </span>Due</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6><span class="text-secondary">$</span><b> 6,000.00</b></h6>
                                 <p class="mb-0 d-flex justify-content-end">per month</p>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Camelun ios</h6>
                                 <p class="mb-0 fontsize-sm"><span class="text-success">16/23</span> months paid</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6><span class="text-secondary">$</span><b> 11,230.00</b></h6>
                                 <p class="mb-0 d-flex justify-content-end">per month</p>
                              </div>
                           </li>
                           <li class="d-flex mb-1 align-items-center">
                              <div class="profile-icon iq-bg-danger"><span><i class="ri-check-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Camelun ios</h6>
                                 <p class="mb-0 fontsize-sm"><span class="text-danger">15/23</span> months paid</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6><span class="text-secondary">$</span><b> 10,050.00</b></h6>
                                 <p class="mb-0 d-flex justify-content-end">per month</p>
                              </div>
                           </li>
                        </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-8 row m-0 p-0">
                     <div class="col-md-12">
                        <div class="iq-card iq-card-block iq-card-stretch ">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Exchange Rates</h4>
                              </div>
                              <div class="iq-card-header-toolbar d-flex align-items-center">
                                 <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton-one" data-toggle="dropdown" >
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-one" >
                                       <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                       <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                       <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                       <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                       <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="iq-card-body">
                              <div id="home-chart-01"></div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="iq-card iq-card-block iq-card-stretch ">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Last costs</h4>
                              </div>
                              <div class="iq-card-header-toolbar d-flex align-items-center">
                                 <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton-two" data-toggle="dropdown" >
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-two" >
                                       <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                       <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                       <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                       <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                       <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="iq-card-body ">
                              <div id="home-chart-05"></div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="iq-card iq-card-block iq-card-stretch ">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Efficiency</h4>
                              </div>
                              <div class="iq-card-header-toolbar d-flex align-items-center">
                                 <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton-three" data-toggle="dropdown" >
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-three" >
                                       <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                       <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                       <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                       <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                       <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="iq-card-body ">
                              <div id="home-chart-11"></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Payment History</h4>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton-four" data-toggle="dropdown" >
                                 <i class="ri-more-fill"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-four" >
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="iq-card-body">
                          <ul class="suggestions-lists m-0 p-0">
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon bg-secondary"><span><i class="ri-refresh-line"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Deposit from ATL</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-info">- $1,470</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon dark-icon bg-primary"><span><i class="ri-paypal-line"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Deposit PayPal</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-primary">+ $2,220</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon icon dark-icon bg-primary"><span><i class="ri-check-line"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Deposit from ATL</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-primary">+ $250</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon bg-info"><span><i class="ri-close-line"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Cancelled</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-info">$0</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon bg-info"><span><i class="ri-arrow-go-back-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Refund</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-info">- $500</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon bg-secondary"><span><i class="ri-bar-chart-grouped-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Credit from ATL</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-primary">+ $169</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-4 align-items-center">
                              <div class="profile-icon bg-warning"><span><i class="ri-qr-scan-line"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Deposit from Paypal</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-info">- $1,470</h6>
                              </div>
                           </li>
                           <li class="d-flex mb-0 align-items-center">
                              <div class="profile-icon bg-danger"><span><i class="ri-mail-send-fill"></i></span></div>
                              <div class="media-support-info ml-3">
                                 <h6>Refund Amount</h6>
                                 <p class="mb-0 fontsize-sm">5 march, 18:33</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <h6 class="text-primary">+ $9,480</h6>
                              </div>
                           </li>
                        </ul>
                        </div>
                     </div>
                  </div>
                  </div>
                 
               </div>
              
               </div>
              
            

 <?php
require "footer.php";
?>