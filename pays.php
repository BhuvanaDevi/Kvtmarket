<?php require "header.php";
$date = date("Y-m-d");

// $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
// $CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  
// //echo $CurPageURL;exit;
// //echo "http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&supplier=$_GET[supplier]&customer=$_GET[customer]&submit=submit";exit;
// if($CurPageURL=="http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&supplier=$_GET[supplier]&customer=$_GET[customer]&submit=submit")
// {
// header("Location:http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&customer=$_GET[customer]&submit=submit");
// }
if (isset($_REQUEST['req']) != "") {
    $req = $_REQUEST["req"];
} else {
    $req = "";
}
if (isset($_REQUEST['id']) != "") {
    $id = $_REQUEST["id"];
} else {
    $id = "";
}
if (isset($_REQUEST['patti_id']) != "") {
$patti_id = $_REQUEST["patti_id"];
} else {
$patti_id = "";
}   

if($req=="enabled")
{
    $update="UPDATE `sar_patti` SET is_active=0 WHERE patti_id=:patti_id";
    $update_sql= $connect->prepare($update);
    $update_sql->execute(array(':patti_id' => $patti_id));
    $insert="INSERT INTO sar_patti_nullify_records(patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,net_payable,payment_status,is_active,updated_by,supplier_id )
SELECT patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,payment_status,is_active,updated_by,supplier_id 
FROM 
   sar_patti
WHERE
   patti_id='".$patti_id."'";
    $insert_sql= $connect->prepare($insert);
        $insert_sql->execute();
    header("location:view_patti.php");
    // if($insert)
    // {
    // $delete="DELETE FROM `sar_patti` WHERE patti_id=:patti_id";
    // $delete_sql= $connect->prepare($delete);
    // $delete_sql->execute(array(':patti_id' => $patti_id));
    // }
}

if($req=="disabled")
{
    $delete="UPDATE `sar_patti` SET is_active=1 WHERE patti_id=:patti_id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':patti_id' => $patti_id));
    header("location:view_patti.php");
}
?>
<!-- 
<div id="content-page" class="content-page">
    <br>
    <div class="container-fluid">
     <select id="invoice" name="invoice">
     <option value="">Please Select Invoice</option>
     <option value="Patti Invoice">Patti Invoice</option>
        <option value="Sales Invoice">Sales Invoice</option>
     </select>
     <select class="form-control" id="supplier" name="supplier" style="width:200px;" >
                      <option value="">Search Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"].'</option>';
            	           }
            	           ?>
                      
    
                    </select>
                    <select class="form-control" id="customer" name="customer" style="width:200px;" >
                      <option value="">Search Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
                        	           }
                        	           ?>
                    </select>
    <p id="nodata"></p>
     
     <table id="list" class="table table-bordered">
     
<thead>
<tr><td>Supplier Name :</td><td id="sup_name"></td></tr>
<tr><td>Mobile :</td><td id="sup_mobile"></td></tr>
<tr><td>Address :</td><td id="sup_address"></td></tr>
     <tr>
    <th>Id</th>
        <th>Date</th>
        <th>Bill Amount</th>
        <th>Remaining</th>
    </tr>
</thead>
    <tbody id="list_body"> 
   </tbody>

     </table>

     <form method="POST" action="" id="pay">
     <input type="text" name="supname" id="supname" value="">
     <input type="text" name="net_bill_amount" id="net" value="">
     <input type="text" name="amount" value="">
        <input type="date" name="payment_date" value="<?= $date ?>">
       <select id="payment_mode" name="payment_mode">
        <option disabled>Select Payment Mode</option>
        <option value="NEFT">NEFT</option>
        <option value="Cash">Cash</option>
        <option value="Online">Online</option>
        <option value="DD">DD</option>
       </select> 
       <select id="discount_type" name="discount_type">
        <option disabled selected>Select Discount Type

        </option>
        <option value="Percentage">Percentage</option>
        <option value="Cash">Cash</option>
       </select> 
       <input type="text" name="discount" value="">
       <input type="submit" name="add_payment" value="Payment">
     </form>
</div>
</div> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div id="content-page" class="content-page">
        <div class="container-fluid">
          <div class="row col-lg-12">
              <div class=" col-lg-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Invoice</h4>
                          </div>
                        </div>
                        <form method="GET">
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
              <div class="row col-md-12 mt-3">   
                 <div class="col-md-6">Choose Invoice</div>   
                 <div class="col-md-6">
            <?php 
            $invoice=($_GET['invoice']) ? $_GET['invoice'] : "";
            if($invoice=="") {
            ?>
                 <select id="invoice" class="form-control" name="invoice">
     <option value="">Please Select Invoice</option>
     <option value="Patti">Patti Invoice</option>
        <option value="Sales">Sales Invoice</option>
     </select>
     <?php } else { ?>
     <select id="invoice" class="form-control" name="invoice">
     <option value="Patti" <?=$invoice == 'Patti' ? ' selected="selected"' : '';?>>Patti Invoice</option>
        <option value="Sales" <?=$invoice == 'Sales' ? ' selected="selected"' : '';?>>Sales Invoice</option>
     </select>
     <?php } ?>
                 </div>
              </div>
              <div class="row col-md-12 mt-3" id="supp">   
              <div class="col-md-6">
              Supplier</div>
              <div class="col-md-6">
       <?php $sup=$_GET['supplier']; 
             $sqla="select * from sar_supplier where contact_person='$sup'";
             $exea=mysqli_query($con,$sqla);
             $rowa=mysqli_fetch_assoc($exea);
              $sid=$rowa['supplier_no'];

         $sql1="select * from sar_patti where supplier_name='$sup' and is_active!=0 and nullify=0 order by total_bill_amount asc";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
    $sup_id=$row1['supplier_id'];


 
       if($sup=="") { ?>
              <select class="form-control" id="supplier" name="supplier">
                      <option value="">Choose Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"].'</option>';
            	           }
            	           ?>
                      
    
     </select>
     <?php } else { ?>
        <select class="form-control" id="supplier" name="supplier">
                      <option value="">Choose Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	             ?>   
            	                <option value="<?= $sel_row["supplier_name"] ?>" <?= ($sel_row["supplier_name"] == $sup) ? 'selected="selected"' : "" ?>><?= ($sel_row["supplier_name"] == $sup) ? $sup : $sel_row["supplier_name"] ?></option>
            	           <?php }
            	           ?>
                      
    
     </select>
        <?php } ?>
                 </div>
              </div>
              <div class="row col-md-12 mt-3" id="custom">  
              <div class="col-md-6">
              Customer</div> 
                 <div class="col-md-6">
<?php $cus=$_GET['customer'];

$sqlc="select * from sar_customer where customer_name='$cus'";
$exec=mysqli_query($con,$sqlc);
$rowc=mysqli_fetch_assoc($exec);

 $cid=$rowc['customer_no'];
 $cus_id=$rowc['customer_no'];
 
 //print_r($rowc['customer_no']);die();

      $sql1="select *,sum(total_bill_amount) as total from sar_sales_invoice where customer_name='$cus' and is_active!=0 order by total_bill_amount asc";
        $exe1=mysqli_query($con,$sql1);
       $row1=mysqli_fetch_assoc($exe1);
                                                                  

if($cus=="") { 
?>
                 <select class="form-control" id="customer" name="customer" >
                      <option value="">Choose Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"].'">'.$sel_row["customer_name"].'</option>';
                        	           }
                        	           ?>
                    </select>
              <?php } else { ?>
                <select class="form-control" id="customer" name="customer">
                      <option value="">Choose Supplier Name </option>
                       <?php
                              $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                              $sel_sql= $connect->prepare($sel_qry);
                              $sel_sql->execute();
                             while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                             ?>   
            	            <option value="<?= $sel_row["customer_name"] ?>" <?= ($sel_row["customer_name"] == $cus) ? 'selected="selected"' : "" ?>><?= ($sel_row["customer_name"] == $sup) ? $sup : $sel_row["customer_name"] ?></option>
            	               <?php }
            	           ?>
                      
    
     </select>
     <?php } ?>
                </div>
                 </div>
                 <div class="row col-md-12" style="position: relative;left:200px;top:10px">
            
<input type="submit" name="submit" value="submit" class="btn btn-success">
</div>
    <?php
    $invoice = $_GET['invoice'];
   if(!empty($_GET['supplier'])){
    
    $revoke="select * from sar_patti_payment where supplier_id='$sid' and is_revoked=1";
    $reexe=mysqli_query($con,$revoke);
    $noo=mysqli_num_rows($reexe);
   //  $revo=mysqli_fetch_assoc($reexe);
   //  print_r($noo);die();
    $bq=0;

    $supplier = $_GET['supplier'];
   } else{
    $customer = $_GET['customer'];
   }

    if(!empty($invoice) && !empty($supplier) && $invoice=="Patti"){
    $sql="select * from sar_patti where supplier_name='$supplier' and is_active!=0 and nullify=0 order by total_bill_amount asc";
	$exe=mysqli_query($con,$sql);

    $sql1="select * from sar_patti where supplier_name='$supplier' and is_active!=0 and nullify=0 order by total_bill_amount asc";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
   $no=mysqli_num_rows($exe1);
  // print_r($no);die();
    $patti_id=$row1['patti_id'];

    $amot=$row1['total_bill_amount'];
    
    $remain=0;
    if($no>0)
    {
    echo "<div class='row col-md-12'><div class='col-md-6' style='margin-top:50px'>Supplier Name : $supplier</div></div>
    <table class='table table-bordered mt-4'>
    <thead>
    <th>Patti Id</th>
    <th>Patti Date</th>
    <th>Patti Amount</th>
    <th>Remaining</th>
    </thead>";
    $s=0;
         while($emp=mysqli_fetch_assoc($exe)){
        $s=$s+$emp['total_bill_amount'];
        
       if($emp['remain']==0){
                $tot=$emp['total_bill_amount'];
                $remain+=$tot;
        }
        else if($emp['remain']<0){
            $tot=$emp['total_bill_amount']-(abs($emp['remain']));
            $remain+=$tot;
        }
        else{
            $tot=$emp['remain'];
            $remain+=$tot;
        }
     
echo "<tr><td>$emp[patti_id]</td>
<td>$emp[patti_date]</td>
<td>$emp[total_bill_amount]</td>
<td>$tot</td></tr>
</tr>";

   }
   echo"<tr><td></td><td></td><td>Total</td>
   <td>$remain</td></tr>";
      echo "</table>";
    }
else{
  echo '<div class="alert alert-danger mt-4">No data found</div>';
 
}

    if($noo>0){
     ?>
     <table class='table table-bordered mt-4'>
       <?php
        while($revo=mysqli_fetch_assoc($reexe))
    {
     $bq+=$revo['amount'];
     if($revo['is_revoked']==1){
      echo "<tr><td></td><td></td>";
       echo "<td align='center' class='pl-5'>$revo[description]</td><td align='left'>$revo[amount]</td></tr>";?>
   <?php
  }
 }
  $balanced=$remain+$bq;
  echo "<tr><td></td><td></td><td align='center' class='pl-5'>Balance</td>
   <td align='left'>$balanced</td></tr></table>";
  
   }

    else{
$sqlup="update sar_patti_payment set is_active=1 where supplier_id='$sid' and patti_id='$patti_id'";
//print_r($sqlup);die();
$exe=mysqli_query($con,$sqlup);

//  echo '<div class="alert alert-danger mt-4">No data found</div>';
  }

  
}
  else if(!empty($invoice) && !empty($customer) && $invoice=="Sales"){
    $sql="select * from sar_sales_invoice where customer_name='$customer' and is_active!=0 order by total_bill_amount asc";
	$exe=mysqli_query($con,$sql);

    $sql1="select * from sar_sales_invoice where customer_name='$customer' and is_active!=0 order by total_bill_amount asc";
	$exe1=mysqli_query($con,$sql1);
  $no=mysqli_num_rows($exe1);
  $row1=mysqli_fetch_assoc($exe1);
    $customer_id=$row1['sales_no'];
    $remain=$row1['remain'];

    $amot=$row1['total_bill_amount'];
    if($no>0){
    $remain=0;
    echo "<hr class='mt-4'><div class='row col-md-12'><div class='col-md-6'>Customer Name : $customer</div></div>
    <table class='table table-bordered' style='margin-top:20px'>
    <thead>
    <th>Sales Id</th>
    <th>Sales Date</th>
    <th>Sales Amount</th>
    <th>Remaining</th>
    </thead>";
    $s=0;
       while($emp=mysqli_fetch_assoc($exe)){
        $s=$s+$emp['total_bill_amount'];
        
       if($emp['remain']==0){
                $tot=$emp['total_bill_amount'];
                $remain+=$tot;
        }
        else if($emp['remain']<0){
            $tot=$emp['total_bill_amount']-(abs($emp['remain']));
            $remain+=$tot;
        }
       else{
            $tot=$emp['remain'];
            $remain+=$tot;
        }
     
echo "<tr><td>$emp[sales_no]</td>
<td>$emp[date]</td>
<td>$emp[total_bill_amount]</td>
<td>$tot</td></tr>";
   }
   echo "<tr><td></td><td></td><td>Total Amount</td>
   <td>$remain</td></tr></table>";
}
  else{
//     $sqlup="update sar_sales_payment set is_active=1 where customer_id='$cus_id'";
// //print_r($sqlup);die();
// $exe=mysqli_query($con,$sqlup);

$sqlex="select * from sar_sales_invoice where customer_id='$cus_id' and is_active=0";
$exeamt=mysqli_query($con,$sqlex);
$no=mysqli_num_rows($exe);
$val=mysqli_fetch_assoc($exeamt);
//print_r($no);die();
if($no==0){

  $sqlex1="select * from sar_sales_invoice where customer_id='$cus_id' and is_active=0";
$exeamt1=mysqli_query($con,$sqlex1);
$val1=mysqli_fetch_all($exeamt1);
$no1=mysqli_num_rows($exeamt1);
// print_r($no1.$no);die();
//var_dump($val1);die();
// for($i=0;$i<$no1;$i++){
  $cash_qry="SELECT id FROM sar_cash_carry ORDER BY id DESC LIMIT 1 ";
  $cash_sql=$connect->prepare("$cash_qry");
  $cash_sql->execute();
  $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
  $Last_id_cash=$cash_row["id"]+1;
  $cash_no = "CR_".date("Ym")."0".$Last_id_cash;
  
  $sqlamt="select *,sum(total_bill_amount) as total from sar_sales_invoice where customer_id='$cus_id' and is_active=0";
  $exeamt=mysqli_query($con,$sqlamt);
 $rowamt=mysqli_fetch_assoc($exeamt);
$am=$rowamt["total_bill_amount"];
$salei=$rowamt['sale_id'];

$sqlam="select *,sum(amount) as total from sar_sales_payment where customer_id='$cus_id' and is_active=0";
$sqlamt=mysqli_query($con,$sqlam);
$sqlamts=mysqli_fetch_assoc($sqlamt);
// print_r($sqlamts["total"].$am.$salei);die();

 $quality_name=$val1[$i][8];
 $quantity=$val1[$i][9];
 $rate=$val1[$i][10];
 $bill_amount=$val1[$i][11];
 $total_bill_amount=$val1[$i][12];
 $payment=$val1[$i][12];
//     print_r($rowamt);die();
 
  $cash_id=strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6));
  $date=$date;
  
  $add_sales_query1="INSERT INTO `sar_cash_carry` SET
  cash_id='$cash_id',
  cash_no='$cash_no',
  date='$date',
  quality_name='$quality_name',
  quantity='$quantity',
  rate='$rate',
  bill_amount='$sqlamts[total]',
  total_bill_amount='$sqlamts[total]',
   payment='$payment',
  updated_by='Admin',
  customer_id='$cus_id',
  saleid='$val[sale_id]',
  is_active=1";
  //print_r($add_sales_query1);die();
  $res1=mysqli_query($con,$add_sales_query1);
  //echo $add_sales_query1;
// }
  }
// echo '<div class="alert alert-danger mt-4">No data found</div>';
   }
  }
 $remainp=$remain;
 
 $revoke="select * from sar_sales_payment where customer_id='$cid' and is_revoked=1";
    $reexe=mysqli_query($con,$revoke);
    $no=mysqli_num_rows($reexe);
    $bq=0;
    if($no > 0) {
      echo "<table class='table table-bordered'>";
    while($revo=mysqli_fetch_assoc($reexe))
   {
 
     $bq+=$revo['amount'];
      if($revo['is_revoked']==1){
       echo "<tr><td></td><td></td>";
        echo "<td align='center'>$revo[description]</td>
    <td align='left'>$revo[amount]";?>
    <!-- <form action="POST">
      <input type="payid" value="<?=$revo['payment_id']?>" name="revsid"/>
      <input type="submit" class="btn btn-info px-2" name="revokesa" value="Pay"/>
    </form> -->
    <?php
   }
 }
   $balanced=$remain+$bq;
   echo "</td></tr><tr><td></td><td></td><td align='center'>Balance</td>
    <td align='left'>$balanced</td></tr></table>";
    }   
?>
     </div>   </div>
                        </form>
                  </div>
                  <div class="col-lg-6">
                  <div class="col-md-12">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Payment</h4>
                          </div>
                        </div>
                        <form method="POST">
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
                        <input type="hidden" class="form-control" name="patti_id" id="patti_id" value="<?=$patti_id?>">
             <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="<?=$customer_id?>">
             <!-- <input type="hidden"  class="form-control"  name="amot" value="<?=$amot?>"> -->
             <input type="hidden" class="form-control" name="supplierid" id="supplierid" value="<?=$sup_id?>">
             <input type="hidden" class="form-control" name="customerid" id="customerid" value="<?=$cus_id?>">
             <div class="row col-md-12">
             <div class="col-md-6 mt-4">
                 <input type="text"  class="form-control" name="amount" value="" placeholder="Enter Amount Here">
             </div>
             <div class="col-md-6 mt-4">
              <input type="date" class="form-control" name="payment_date" value="<?= $date ?>">
             </div>
             <div class="col-md-6 mt-4">
                <select id="payment_mode"  class="form-control" name="payment_mode">
        <option disabled>Select Payment Mode</option>
        <option value="NEFT">NEFT</option>
        <option value="Cash">Cash</option>
        <option value="Online">Online</option>
        <option value="DD">DD</option>
       </select> 
             </div>
                   </div>
                  <div class="row col-md-12">
            <div class="col-md-1"></div>
      <div class="col-md-10"> 
         <p class="mt-3">
  If you need discount, click here&nbsp;&nbsp;<a class="btn btn-primary mt-3" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Discount
  </a>
  </p>
      </div>
  </div> 
   <div class="collapse" id="collapseExample">
  <div class="card card-body">
 <div class="row col-md-12">
     <div class="col-md-6">
        <select id="discount_type" class="form-control" name="amttype">
        <option disabled selected>Select Discount Type   </option>
        <option value="percentage">Percentage</option>
        <option value="cash">Cash</option>
       </select></div>
       <div class="col-md-6"> 
       <input type="text" name="dis" placeholder="Enter Amount Here" class="form-control" value="">
       </div>
       </div>
  </div>
  </div>
                        </div>
                        <div class="col-md-12 mt-4" style="text-align: center;">
                   <input type="submit" name="remainings" class="btn btn-success" value="submit">
             </div>
                        </form>
                  </div>
            </div>
               </div>

          </div>
          <div class="row col-md-12">
          <!-- <div class="col-lg-12"> -->
          <?php               if($sup!="") {
               ?>
          <!-- <div class="row col-md-12"> -->
                <div class="col-md-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Patti Payment</h4>
                          </div>
                        </div>
                        <br/>
                        <?php 
                         $sql="select * from sar_patti_payment where supplier_id='$sid' and is_revoked=0";
                     // print_r($sql);die();
                         $exe1=mysqli_query($con,$sql);
                   // $rowq=mysqli_fetch_assoc($exe1);
                            $no1=mysqli_num_rows($exe1);
                            if($no1 > 0){
                 ?>
                        <table class="table table-bordered">
                          <thead>
                            <!-- <th>Pay Id</th> -->
                            <th>Pay Date</th>
                            <th>Amount</th>
                            <!-- <th>Payment Mode</th>
                            <th>Discount</th>
                            <th>Discount Type</th> -->
                            <th>Action</th>
                            </thead>
                  <?php    //   $row1=mysqli_fetch_assoc($exe1);
                      // print_r($row1);die();
                     while($rowt=mysqli_fetch_assoc($exe1))
                        { ?>
                          <tr>
                            <!-- <td><?=$rowt['payment_id']?></td> -->
                            <td><?=$rowt['payment_date']?></td>
                            <td><?=$rowt['amount']?></td>
                            <!-- <td><?=$rowt['payment_mode']?></td>
                            <td><?=$rowt['discount']?></td>
                            <td><?=$rowt['discount_type']?></td> -->
                            <td><form method="POST" action="">
                              <input type="hidden" name="revoke_id" value="<?=$rowt['payment_id']?>">
                              <input type="hidden" name="pattiid" value="<?=$rowt['patti_id']?>">
                              <input type="hidden" name="sid" value="<?=$rowt['supplier_id']?>">
                              <input type="hidden" name="remamount" value="<?=$rowt['amount']!=0?$rowt['amount']:$rowt['discount']?>">
                              <input type="hidden" class="form-control" name="pay_date" value="<?= $date ?>">
             <input type="submit" name="revoke_sub" class="btn btn-danger" value="Revoke">
                            </form>
                          </td>
                          </tr>
                       <?php } ?>
                        </table>
                        <?php } else {?>
                          <div class="row col-md-12">
                            <div class="col-md-4"></div>
                          <div class="alert alert-danger col-md-3">No data found</div>
                          </div>
                 
<?php } ?>
</div>
                </div>
                <?php
}
  else if($cus!=""){
               ?>
          <!-- <div class="row col-md-12"> -->
                <div class="col-md-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Sales Payment</h4>
                          </div>
                        </div>
                        <br/>
                        <?php 
                         $sqls="select * from sar_sales_payment where customer_id='$cus_id' and is_revoked=0";
                      //print_r($sqls);die();
                         $exes=mysqli_query($con,$sqls);
                    //$rows=mysqli_fetch_assoc($exes);
                            $nos=mysqli_num_rows($exes);
                            // print_r($nos);die();
                            if($nos > 0){
                 ?>
                        <table class="table table-bordered">
                          <thead>
                            <!-- <th>Pay Id</th> -->
                            <th>Pay Date</th>
                            <th>Amount</th>
                            <!-- <th>Payment Mode</th>
                            <th>Discount</th>
                            <th>Discount Type</th> -->
                            <th>Action</th>
                            </thead>
                  <?php    //   $row1=mysqli_fetch_assoc($exe1);
                      // print_r($row1);die();
                     while($rowl=mysqli_fetch_assoc($exes))
                        { ?>
                          <tr>
                            <!-- <td><?=$rowl['payment_id']?></td> -->
                            <td><?=$rowl['payment_date']?></td>
                            <td><?=$rowl['amount']?></td>
                            <!-- <td><?=$rowl['payment_mode']?></td>
                            <td><?=$rowl['discount']?></td>
                            <td><?=$rowl['discount_type']?></td> -->
                            <td><form method="POST" action="">
                            <input type="hidden" name="revokecus_id" value="<?=$rowl['payment_id']?>">
                            <input type="hidden" name="cid" value="<?=$rowl['customer_id']?>">
                              <input type="hidden" name="salesid" value="<?=$rowl['sales_no']?>">
                              <input type="hidden" name="cusamount" value="<?=$rowl['amount']!=0?$rowl['amount']:$rowl['discount']?>">
                              <input type="hidden" class="form-control" name="revoke_date" value="<?= $date ?>">
            <input type="submit" name="revoke_cus" class="btn btn-danger" value="Revoke"></form></td>
                          </tr>
                       <?php } ?>
                        </table>
                        <?php } else {?>
                          <div class="row col-md-12">
                            <div class="col-md-4"></div>
                          <div class="alert alert-danger col-md-3">No Data Found</div>
                          </div>
                         
                          
<?php } ?>
</div>
                </div><?php
} ?>
          <!-- </div> -->
         
 <?php       
 if($sid!=""){
 ?>
  <div class="col-md-6">
          <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Revoke Payment</h4>
                          </div>
                        </div>
                        <?php
                          $revoke="select * from sar_patti_payment where supplier_id='$sid' and is_revoked=1";
   $reexe=mysqli_query($con,$revoke);
   $noo=mysqli_num_rows($reexe);

     $bq=0;
   if($noo>0){
      ?>
        <table class='table table-bordered mt-4'>
      <thead><th>Description</th>
      <th>Amount</th>
      <th>Action</th>
      </thead>
      <tbody> 
    <?php  while($revo=mysqli_fetch_assoc($reexe))
  {
?>      <tr><td><?=$revo['description']?></td>
      <td><?=$revo['amount']?></td>
      <td><form method="POST" action="">
        <input type="hidden" value="<?=$revo['payment_id']?>" name="revid"/> 
        <input type="hidden" name="revdate" value="<?=$revo['payment_date']?>"/>
        <input type="hidden" name="revpati" value="<?=$revo['patti_id']?>" />
        <input type="hidden" value="<?=$revo['supplier_id']?>" name="revsup" />
        <input type="hidden" value="<?=$revo['amount']?>" name="revamt"/> 
        <input type="submit" name="revokec" class="btn btn-success" value="Pay"/></form>
        </td>
        </tbody>
        <?php } 
        }
        else{
          ?>
            <div class="row col-md-12">
                            <div class="col-md-4"></div>
                          <div class="alert alert-danger col-md-3">No Data Found</div>
                          </div> <?php
        } ?>
         </table>
          </div>
  </div>
       <?php
         }  
         else if($cid!="")
{
  ?>
   <div class="col-md-6">
          <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Revoke Payment</h4>
                          </div>
                        </div>
                        <?php
   $revoke="select * from sar_sales_payment where customer_id='$cid' and is_revoked=1";
    $reexe=mysqli_query($con,$revoke);
    $no=mysqli_num_rows($reexe);
    $bq=0;
    if($no > 0) {
      // echo "<table class='table table-bordered'>";
    ?>
    <table class='table table-bordered mt-4'>
    <thead><th>Description</th>
    <th>Amount</th>
    <th>Action</th>
    </thead>
    <tbody> 
    <?php while($revo=mysqli_fetch_assoc($reexe))
   { ?> 
      
    <tr><td><?=$revo['description']?></td>
    <td><?=$revo['amount']?></td>
    <td><form method="POST" action="">
      <input type="hidden" value="<?=$revo['payment_id']?>" name="revsid"/> 
      <input type="hidden" name="revsdate" value="<?=$revo['payment_date']?>"/>
      <input type="hidden" name="revsale" value="<?=$revo['sales_no']?>" />
      <input type="hidden" value="<?=$revo['customer_id']?>" name="revcus" />
      <input type="hidden" value="<?=$revo['amount']?>" name="revsamt"/> 
      <input type="submit" name="revokes" class="btn btn-success" value="Pay"/></form>
      </td>
      </tbody>
      <?php } 
      }
      else{
        ?>
          <div class="row col-md-12">
                          <div class="col-md-4"></div>
                        <div class="alert alert-danger col-md-3">No Data Found</div>
                        </div> <?php
      } ?>
       </table>
       </div>
          </div>
          <?php }    ?>
      
              <?php
if(isset($_POST['remainings'])){
    $patid=isset($_POST['patti_id'])?$_POST['patti_id']:"";
    $customer_id=isset($_POST['customer_id'])?$_POST['customer_id']:"";

    $supplierid=isset($_POST['supplierid'])?$_POST['supplierid']:"";
    $customerid=isset($_POST['customerid'])?$_POST['customerid']:"";

    $amount=$_POST['amount'];
    $dis=($_POST['dis']!="")?$_POST['dis']:0;
    $amttype=($_POST['amttype']!="")?$_POST['amttype']:"-";

    if($amttype=="cash"){
        $amt=$amount+$dis;
    }
    else if($amttype=="percentage"){
        $amt=$amount-(($dis*$amount)/100);
    }
    if($amt){
        $amt=$amt;
    }
    else{
    $amt=$amount;
    }
    // print_r($_POST);die();
if($patid!=""){    
    $sql="select * from sar_patti where supplier_id='$supplierid' and is_active!=0 and nullify=0 order by total_bill_amount asc";
    $exe=mysqli_query($con,$sql);
    $no=mysqli_num_rows($exe);
    $remain=0; $am=0; $f=0; $rems=0;
    
    $select_qry6 ="select * from sar_patti_payment order by id desc limit 1";
    $exesel=mysqli_query($con,$select_qry6);
    $no=mysqli_num_rows($exesel);
    $exes=mysqli_fetch_assoc($exesel);
    $Last_id = $exes["id"] + 1;
    $pay_id = "PAY_" . date("Ym") . "0" . $Last_id;
    $pay_date=$_POST['payment_date'];
    $payment_mode=$_POST['payment_mode'];
    $is_revoked=0;

    $sqlt="select *,SUM(total_bill_amount) as tot from sar_patti where supplier_id='$supplierid' and is_active=1";
    $exet=mysqli_query($con,$sqlt);
    $fet=mysqli_fetch_assoc($exet);
    $amu=$fet['tot'];
// print_r($amu.$amount);die();

if($amu==$amount){
  $upt="update sar_patti set is_active=0 where supplier_id='$supplierid'";
  $exe=mysqli_query($con,$upt);


  $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
  $pay1=$connect->prepare("$pay");
  $pay1->execute();
  $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
if($balan!=""){
  $balc= $balan['balance']-$amount;
}
else{
  $balc=$amu-$amount;
}   
 $balc=abs($balc);

 $tray="SELECT * FROM trays where name='$supplierid' and type='$fet[type]' ORDER BY id DESC LIMIT 1 ";
 $tray1=$connect->prepare("$tray");
 $tray1->execute();
 $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
  
$ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$fet[supplier_id]','$fet[patti_id]','$fet[pat_id]',$small,$big,$inhand)";           
$exeins=mysqli_query($con,$ins);           
        
$balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
$balance_sql1=$connect->prepare("$balance_qry1");
$balance_sql1->execute();
$bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
if($bal_row1["balance"]!=""){ 
$balance1 = abs($bal_row1["balance"] - $amt);
}
else{
$balance1 = $ba;
}
// print_r($balance1."n");die();

$fin_trans_qry = "INSERT INTO financial_transactions SET 
date = '$pay_date',
debit= '$amt',
balance='$balance1',
description = 'Payment for Patti $fet[supplier_name]',
patti_id = '$fet[pat_id]',
payment_id = '6',
ids='$fet[supplier_id]'
";
$res2=mysqli_query($con,$fin_trans_qry);

header("location:pays.php?invoice=Patti&supplier=$supplier");
     
}
else {
    while($row=mysqli_fetch_assoc($exe)){
        $f+=1; 
        if($row['remain']!=0){
            $totpati=$row['remain'];
        }
        else{
            $totpati=$row['total_bill_amount'];
        }

        if($amt<$totpati && $amt!=$totpati) {
      // print_r("hello");die();
            if($row['remain']==0){
            if($rems==0){
                            $ba = $totpati-$amt;
            }
            else{
                $ba = $totpati-$remain;
            }
            $upsbal="update sar_patti set remain='$ba' where patti_id='$row[patti_id]'";
              // print_r($upsbal."a".$amt.$totpati.$remain);die();
              $exesbal=mysqli_query($con,$upsbal);

              // print_r($ba);die();              
              $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
              $pay1=$connect->prepare("$pay");
              $pay1->execute();
              $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            if($balan!=""){
              $balc= $balan['balance']-$amount;
            }
            else{
              $balc=$amu-$amount;
            }   
             $balc=abs($balc);

             $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            //  $balc=abs($balc);
            $small=$tray['smalltray'];
            $big=$tray['bigtray'];
            $inhand=$tray['inhand'];
    $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
  // print_r($ins."d");die();
    $exeins=mysqli_query($con,$ins);           
                    
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql1=$connect->prepare("$balance_qry1");
    $balance_sql1->execute();
    $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
    if($bal_row1["balance"]!=""){ 
    $balance1 = abs($bal_row1["balance"] - $amt);
    }
    else{
    $balance1 = $ba;
    }
    // print_r($balance1."n");die();
     
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
    date = '$pay_date',
    debit= '$amt',
    balance='$balance1',
    description = 'Payment for Patti : $row[supplier_name]',
    patti_id = '$row[pat_id]',
    payment_id = '6',
    ids='$row[supplier_id]'
    ";
    $res2=mysqli_query($con,$fin_trans_qry);
    
            //    else{
            //     $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
            //     // print_r($upsbal."a1");die();
            //     $exesbal=mysqli_query($con,$upsbal);
                  
            //    }  
                $remain=0;

            //   $totpati=$row['total_bill_amount'];
          
              header("location:pays.php?invoice=Patti&supplier=$supplier");
            }
      
            // }
              else{
                // print_r($totpati > $amt);die();
                if($totpati > $amt){
                    $b=$totpati-$amt;
                        $upsbal="update sar_patti set remain='$b' where patti_id='$row[patti_id]'";
                        $exesbal=mysqli_query($con,$upsbal);

                        $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
                        $pay1=$connect->prepare("$pay");
                        $pay1->execute();
                        $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
                      //  $balc= $balan['balance']-$amount;
                      if($balan!=""){
                        $balc= $balan['balance']-$amount;
                      }
                      else{
                        $balc=$amu-$amount;
                      }   
                       $balc=abs($balc);
          
                       $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
                       $tray1=$connect->prepare("$tray");
                       $tray1->execute();
                       $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
                      //  $balc= $balan['balance']-$amount;
                      //  $balc=abs($balc);
                      $small=$tray['smalltray'];
                      $big=$tray['bigtray'];
                      $inhand=$tray['inhand'];
                    
                        $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
                    //  print_r($ins."ok");die();
                        $exeins=mysqli_query($con,$ins);           
                                        
                        $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
                        $balance_sql1=$connect->prepare("$balance_qry1");
                        $balance_sql1->execute();
                        $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
                        if($bal_row1["balance"]!=""){ 
                        $balance1 = abs($bal_row1["balance"] - $amt);
                        }
                        else{
                        $balance1 = $b;
                        }
                        // print_r($balance1."n");die();
                         
                        $fin_trans_qry = "INSERT INTO financial_transactions SET 
                        date = '$pay_date',
                        debit= '$amt',
                        balance='$balance1',
                        description = 'Payment for Patti : $row[supplier_name]',
                        patti_id = '$row[pat_id]',
                        payment_id = '6',
                        ids='$row[supplier_id]'
                        ";
                        $res2=mysqli_query($con,$fin_trans_qry);
                  
                  
                        header("location:pays.php?invoice=Patti&supplier=$supplier");
                    }
                    
                if($amt==$remain){
            // echo $row['total_bill_amount'];
            }
            else if($remain==0){
                // echo $row['total_bill_amount'];
            }
            else{
                
                if($remain < $totpati){
                 $bal2= $am-abs($amt)+abs($totpati);
            
            $upsbal="update sar_patti set remain='$bal2' where patti_id='$row[patti_id]'";
            // print_r($upsbal."c");die();
            $exesbal=mysqli_query($con,$upsbal);

            $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];

            $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
  //  print_r($ins."we");die();
            $exeins=mysqli_query($con,$ins);           
                    
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql1=$connect->prepare("$balance_qry1");
    $balance_sql1->execute();
    $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
    if($bal_row1["balance"]!=""){ 
    $balance1 = abs($bal_row1["balance"] - $amt);
    }
    else{
    $balance1 = $bal2;
    }
    // print_r($balance1."n");die();
     
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
    date = '$pay_date',
    debit= '$amt',
    balance='$balance1',
    description = 'Payment for Patti : $row[supplier_name]',
    patti_id = '$row[pat_id]',
    payment_id = '6',
    ids='$row[supplier_id]'";
    $res2=mysqli_query($con,$fin_trans_qry);

            header("location:pays.php?invoice=Patti&supplier=$supplier");
       
            }
            else if($remain > $totpati){
                $bal3 = $am-abs($amt)+abs($totpati);
                  
            $upsbal="update sar_patti set remain='$bal3' where patti_id='$row[patti_id]'";
            // print_r($upsbal."d");die();
            $exesbal=mysqli_query($con,$upsbal);

            $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];

            $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
//  print_r($ins."lo");die();
            $exeins=mysqli_query($con,$ins);           
                    
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql1=$connect->prepare("$balance_qry1");
    $balance_sql1->execute();
    $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
    if($bal_row1["balance"]!=""){ 
    $balance1 = abs($bal_row1["balance"] - $amt);
    }
    else{
    $balance1 = $bal3;
    }
    // print_r($balance1."n");die();
     
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
    date = '$pay_date',
    debit= '$amt',
    balance='$balance1',
    description = 'Payment for Patti : $row[supplier_name]',
    patti_id = '$row[pat_id]',
    payment_id = '6',
    ids='$row[supplier_id]'
    ";
    $res2=mysqli_query($con,$fin_trans_qry);

            header("location:pays.php?invoice=Patti&supplier=$supplier");
        }
            else{
                // echo 0;
  
                $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
                // print_r($upsbal."e");die();
                // $exesbal=mysqli_query($con,$upsbal);
                // header("location:pays.php?invoice=Patti&supplier=$supplier");
                       }
            }
            
        }
    break;
    }
    else{
 
        $rem=$totpati-$amt;
        $remain-=$rem;
        $am+=$totpati;
        if($amt==$remain){
        //  echo $rem;
        // echo 0;
          
        $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
        // print_r($upsbal."w");die();
        $exesbal=mysqli_query($con,$upsbal);

        // header("location:pays.php?invoice=Patti&supplier=$supplier");
       
        // echo $am-$amt;    
    }
    else if(($totpati<$amt)){
        $val = $am-$amt;
        if($val<0) {
            // echo 0;
              
            $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
              // print_r($upsbal."q");die();
              $exesbal=mysqli_query($con,$upsbal);

              $v=$row['remain'];
              if($v!=0){
                $rems+=$v;
              }
              else{
                $rems=$totpati;
              }

            //   header("location:pays.php?invoice=Patti&supplier=$supplier");
       
    }
        else {
            // echo $val;  
              
            $upsbal="update sar_patti set remain='$val' where patti_id='$row[patti_id]'";
              // print_r($upsbal."h");die();
              $exesbal=mysqli_query($con,$upsbal);

              $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
              $pay1=$connect->prepare("$pay");
              $pay1->execute();
              $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            if($balan!=""){
              $balc= $balan['balance']-$amount;
            }
            else{
              $balc=$amu-$amount;
            }   
             $balc=abs($balc);

             $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            //  $balc=abs($balc);
            $small=$tray['smalltray'];
            $big=$tray['bigtray'];
            $inhand=$tray['inhand'];

              $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
//  print_r($ins."sd");die();
              $exeins=mysqli_query($con,$ins);           
                    
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql1=$connect->prepare("$balance_qry1");
    $balance_sql1->execute();
    $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
    if($bal_row1["balance"]!=""){ 
    $balance1 = abs($bal_row1["balance"] - $amt);
    }
    else{
    $balance1 = $val;
    }
    // print_r($balance1."n");die();
     
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
    date = '$pay_date',
    debit= '$amt',
    balance='$balance1',
    description = 'Payment for Patti : $row[supplier_name]',
    patti_id = '$row[pat_id]',
    payment_id = '6',
    ids='$row[supplier_id]'
    ";
    $res2=mysqli_query($con,$fin_trans_qry);

              header("location:pays.php?invoice=Patti&supplier=$supplier");
       
            }
}
        //  else if($row['total_bill_amount']<$amt && $row['total_bill_amount']>abs($rem)){
        //     // echo $row['total_bill_amount']-$amt;
        //     echo $am-$amt;
        //  }
        else if($remain > $totpati){
            // echo $am;
           if($amt==$totpati)
           { 
            $am-=$totpati;
            $bal4= abs($amt-$am);
              
            $upsbal="update sar_patti set remain='$bal4' where patti_id='$row[patti_id]'";
              // print_r($upsbal."m");die();
              $exesbal=mysqli_query($con,$upsbal);

              $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
              $pay1=$connect->prepare("$pay");
              $pay1->execute();
              $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            if($balan!=""){
              $balc= $balan['balance']-$amount;
            }
            else{
              $balc=$amu-$amount;
            }   
             $balc=abs($balc);

             $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            //  $balc=abs($balc);
            $small=$tray['smalltray'];
            $big=$tray['bigtray'];
            $inhand=$tray['inhand'];

              $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`,`patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
//  print_r($ins."su");die();
              $exeins=mysqli_query($con,$ins);           
                    
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql1=$connect->prepare("$balance_qry1");
    $balance_sql1->execute();
    $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
    if($bal_row1["balance"]!=""){ 
    $balance1 = abs($bal_row1["balance"] - $amt);
    }
    else{
    $balance1 = $bal4;
    }
    // print_r($balance1."n");die();
     
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
    date = '$pay_date',
    debit= '$amt',
    balance='$balance1',
    description = 'Payment for Patti : $row[supplier_name]',
    patti_id = '$row[pat_id]',
    payment_id = '6',
    ids='$row[supplier_id]'
    ";
    $res2=mysqli_query($con,$fin_trans_qry);

              header("location:pays.php?invoice=Patti&supplier=$supplier");
       
        }
    else{
        // $am-=$row['total_bill_amount'];
        // echo $row['total_bill_amount'];
    }
    }
        else{
            if($amt==$totpati){
                // echo 0;
                  
            $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
            // print_r($upsbal."a");die();
            $exesbal=mysqli_query($con,$upsbal);
          
            $pay="SELECT * FROM sar_patti_payment where supplier_id='$supplierid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$supplierid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];

            $ins="INSERT INTO `sar_patti_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `supplier_id`,`patti_id`, `patid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',0,$is_revoked,$dis,'$amttype','$row[supplier_id]','$row[patti_id]','$row[pat_id]',$small,$big,$inhand)";           
        // print_r($ins."df");die();
            $exeins=mysqli_query($con,$ins);           
                            
            $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
            $balance_sql1=$connect->prepare("$balance_qry1");
            $balance_sql1->execute();
            $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
            if($bal_row1["balance"]!=""){ 
            $balance1 = abs($bal_row1["balance"] - $amt);
            }
            else{
            $balance1 = 0;
            }
            // print_r($balance1."n");die();
             
            $fin_trans_qry = "INSERT INTO financial_transactions SET 
            date = '$pay_date',
            debit= '$amt',
            balance='$balance1',
            description = 'Payment for Patti : $row[supplier_name]',
            patti_id = '$row[pat_id]',
            payment_id = '6',
            ids='$row[supplier_id]'
            ";
            $res2=mysqli_query($con,$fin_trans_qry);
        
                      header("location:pays.php?invoice=Patti&supplier=$supplier");
                 // header("location:pays.php?invoice=Patti&supplier=$supplier");
       
                break;
            } else{
            // echo $row['total_bill_amount'];
        }
    }
    //    echo $am;
    }
    
    }
  }
}

if($customer_id!=""){   
    $sql="select * from sar_sales_invoice where customer_id='$customerid' and is_active!=0 order by total_bill_amount asc";
    $exe=mysqli_query($con,$sql);
    $no=mysqli_num_rows($exe);
    $remain=0; $am=0; $f=0; $rems=0;
  
    $select_qry6 ="select * from sar_sales_payment order by id desc limit 1";
    $exesel=mysqli_query($con,$select_qry6);
    $no=mysqli_num_rows($exesel);
    $exes=mysqli_fetch_assoc($exesel);
    $Last_id = $exes["id"] + 1;
    $pay_id = "PAY_" . date("Ym") . "0" . $Last_id;
    $pay_date=$_POST['payment_date'];
    $payment_mode=$_POST['payment_mode'];
    $is_revoked=0;

    $sqlt="select *,SUM(total_bill_amount) as tot from sar_sales_invoice where customer_id='$customerid' and is_active=1";
    $exet=mysqli_query($con,$sqlt);
    $fet=mysqli_fetch_assoc($exet);
    $amu=$fet['tot'];
// print_r($amu.$amount);die();
if($amu==$amount){
  $upt="update sar_sales_invoice set is_active=0 where customer_id='$customerid'";
  $exe=mysqli_query($con,$upt);

  
  $pay="SELECT * FROM trays where name='$customerid' and type='$fet[type]' ORDER BY id DESC LIMIT 1 ";
  $pay1=$connect->prepare("$pay");
  $pay1->execute();
  $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$balan['smalltray'];
$big=$balan['bigtray'];
$inhand=$balan['inhand'];

  $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amu,'$payment_mode',0,$is_revoked,$dis,'$amttype','$fet[customer_id]','$fet[sales_no]','$fet[sale_id]',$small,$big,$inhand)";           
  // print_r($ins);die();
  $exeins=mysqli_query($con,$ins);           
    
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amu);
  }
  else{
   $balance1 = $ba;
  }

  
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $fet[customer_name]',
  patti_id = '$fet[sale_id]',
  payment_id = '6',
  ids='$fet[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  header("location:pays.php?invoice=Sales&customer=$customer");
        
}
else{
    while($row=mysqli_fetch_assoc($exe)){
       $f+=1; 
        if($row['remain']!=0){
            $totpati=$row['remain'];
        }
        else{
            $totpati=$row['total_bill_amount'];
        }
        if($amt<$totpati && $amt!=$totpati) {
             
        if($row['remain']==0){
            if($rems==0){
                $ba = $totpati-$amt;
}
else{
    $ba = $totpati-$remain;
}
            $upsbal="update sar_sales_invoice set remain='$ba' where sales_no='$row[sales_no]'";
            //   print_r($upsbal."a");die();
              $exesbal=mysqli_query($con,$upsbal);
            //    else{
            //     $upsbal="update sar_patti set is_active=0,remain=0 where patti_id='$row[patti_id]'";
            //     // print_r($upsbal."a1");die();
            //     $exesbal=mysqli_query($con,$upsbal);
                  
            //    }  

         
  $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
  $pay1=$connect->prepare("$pay");
  $pay1->execute();
  $balan=$pay1->fetch(PDO::FETCH_ASSOC);
  if($balan!=""){
    $balc= $balan['balance']-$amount;
  }
  else{
    $balc=$amu-$amount;
  }   
 $balc=abs($balc);

 $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
 $tray1=$connect->prepare("$tray");
 $tray1->execute();
 $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];


  $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
  $exeins=mysqli_query($con,$ins);           
  
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amt);
  }
  else{
   $balance1 = $ba;
  }
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $row[customer_name]',
  patti_id = '$row[sale_id]',
  payment_id = '6',
  ids='$row[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);
  
                $remain=0;

            //   $totpati=$row['total_bill_amount'];
          
              header("location:pays.php?invoice=Sales&customer=$customer");
              }
         
              else{
                if($totpati > $amt){
                    $b=$totpati-$amt;
               
                $upbal="update sar_sales_invoice set remain='$b',is_active=1 where sales_no='$row[sales_no]'";
                $balexe=mysqli_query($con,$upbal);
   
                $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
                $pay1=$connect->prepare("$pay");
                $pay1->execute();
                $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
                if($balan!=""){
                  $balc= $balan['balance']-$amount;
                }
                else{
                  $balc=$amu-$amount;
                }   
                      //  $balc= $balan['balance']-$amount;
               $balc=abs($balc);
               
               $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
              //  $balc= $balan['balance']-$amount;
              //  $balc=abs($balc);
              $small=$tray['smalltray'];
              $big=$tray['bigtray'];
              $inhand=$tray['inhand'];
              
              
               $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
                  $exeins=mysqli_query($con,$ins);           
                  
                  
                  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
                  $balance_sql1=$connect->prepare("$balance_qry1");
                  $balance_sql1->execute();
                  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
                  if($bal_row1["balance"]!=""){ 
                  $balance1 = abs($bal_row1["balance"] + $amt);
                  }
                  else{
                   $balance1 = $b;
                  }
                   $fin_trans_qry = "INSERT INTO financial_transactions SET 
                  date = '$pay_date',
                  credit= '$amt',
                  balance='$balance1',
                  description = 'Payment for Sales : $row[customer_name]',
                  patti_id = '$row[sale_id]',
                  payment_id = '6',
                  ids='$row[customer_id]'
                  ";
                  $res2=mysqli_query($con,$fin_trans_qry);
                
                  
                            header("location:pays.php?invoice=Sales&customer=$customer");
                }
                if($amt==$remain){
            // echo $row['total_bill_amount'];
            }
            else if($remain==0){
                // echo $row['total_bill_amount'];
            }
            // if($remain < $totpati){
            //     $ba1= $am-abs($amt)+abs($totpati);
                
            //     $upbal="update sar_sales_payment set remain='$ba1',is_active=1 where sales_no='$row[sales_no]'";
            //     $balexe=mysqli_query($con,$upbal);
   
                // $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$bal1,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]')";           
                //   $exeins=mysqli_query($con,$ins);           
                  
                  
                //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
                //   $balance_sql1=$connect->prepare("$balance_qry1");
                //   $balance_sql1->execute();
                //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
                //   if($bal_row1["balance"]!=""){ 
                //   $balance1 = abs($bal_row1["balance"] - $amt);
                //   }
                //   else{
                //    $balance1 = $bal1;
                //   }
                //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
                //   date = '$pay_date',
                //   debit= '$amt',
                //   balance='$balance1',
                //   description = 'Payment for Sales Id $row[sales_no]',
                //   patti_id = '$row[sales_no]',
                //   payment_id = '6',
                //   ids='$row[customer_id]'
                //   ";
                //   $res2=mysqli_query($con,$fin_trans_qry);
                
                  
                    //         header("location:pays.php?invoice=Sales&customer=$customer");
                       
                    //   }
            else{
                if($remain < $totpati){
                 $bal2= $am-abs($amt)+abs($totpati);

            $upsbal="update sar_sales_invoice set remain='$bal2' where sales_no='$row[sales_no]'";
            // print_r($upsbal."c");die();
            $exesbal=mysqli_query($con,$upsbal);

            $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];
          
  $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
  $exeins=mysqli_query($con,$ins);           
  
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amt);
  }
  else{
   $balance1 = $bal2;
  }
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $row[customer_name]',
  patti_id = '$row[sale_id]',
  payment_id = '6',
  ids='$row[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  
            header("location:pays.php?invoice=Sales&customer=$customer");
       
            }
            else if($remain > $totpati){
                $bal3 = $am-abs($amt)+abs($totpati);
                  
            $upsbal="update sar_sales_invoice set remain='$bal3' where sales_no='$row[sales_no]'";
            // print_r($upsbal."d");die();
            $exesbal=mysqli_query($con,$upsbal);

            $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];
        
  $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
  $exeins=mysqli_query($con,$ins);           
  
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amt);
  }
  else{
   $balance1 = $bal3;
  }
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $row[customer_name]',
  patti_id = '$row[sale_id]',
  payment_id = '6',
  ids='$row[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  
            header("location:pays.php?invoice=Sales&customer=$customer");
        }
            else{
                // echo 0;
  
                $upsbal="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$row[sales_no]'";
                // print_r($upsbal);die();
                $exesbal=mysqli_query($con,$upsbal);
                // header("location:pays.php?invoice=Patti&supplier=$supplier");
                       }
            }
        }
    break;
    }
    else{
        $rem=$totpati-$amt;
        $remain-=$rem;
        $am+=$totpati;
        if($amt==$remain){
        //  echo $rem;
        // echo 0;
          
        $upsbal="update sar_sales_invoce set is_active=0,remain=0 where sales_no='$row[sales_no]'";
        // print_r($upsbal);die();
        $exesbal=mysqli_query($con,$upsbal);

        // header("location:pays.php?invoice=Patti&supplier=$supplier");
       
        // echo $am-$amt;    
    }
    else if(($totpati<$amt)){
        $val = $am-$amt;
        if($val<0) {
            // echo 0;
              
            $upsbal="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$row[sales_no]'";
              // print_r($upsbal);die();
              $exesbal=mysqli_query($con,$upsbal);

            //   header("location:pays.php?invoice=Patti&supplier=$supplier");
       
            $v=$row['remain'];
            if($v!=0){
              $rems+=$v;
            }
            else{
              $rems=$totpati;
            }

        }
        else {
            // echo $val;  
              
            $upsbal="update sar_sales_invoice set remain='$val' where sales_no='$row[sales_no]'";
            //   print_r($upsbal."h");die();
              $exesbal=mysqli_query($con,$upsbal);

              
              $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
              $pay1=$connect->prepare("$pay");
              $pay1->execute();
              $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            if($balan!=""){
              $balc= $balan['balance']-$amount;
            }
            else{
              $balc=$amu-$amount;
            }   
             $balc=abs($balc);

             $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            //  $balc=abs($balc);
            $small=$tray['smalltray'];
            $big=$tray['bigtray'];
            $inhand=$tray['inhand'];
             
             $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
  $exeins=mysqli_query($con,$ins);           
  
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amt);
  }
  else{
   $balance1 = $val;
  }
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $row[customer_name]',
  patti_id = '$row[sale_id]',
  payment_id = '6',
  ids='$row[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  
              header("location:pays.php?invoice=Sales&customer=$customer");
       
            }
}
        //  else if($row['total_bill_amount']<$amt && $row['total_bill_amount']>abs($rem)){
        //     // echo $row['total_bill_amount']-$amt;
        //     echo $am-$amt;
        //  }
        else if($remain > $totpati){
            // echo $am;
           if($amt==$totpati)
           { 
            $am-=$totpati;
            $bal4= abs($amt-$am);
              
            $upsbal="update sar_sales_invoice set remain='$bal4' where sales_no='$row[sales_no]'";
            //   print_r($upsbal."h");die();
              $exesbal=mysqli_query($con,$upsbal);

              $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
              $pay1=$connect->prepare("$pay");
              $pay1->execute();
              $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            if($balan!=""){
              $balc= $balan['balance']-$amount;
            }
            else{
              $balc=$amu-$amount;
            }   
             $balc=abs($balc);

             $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
            //  $balc= $balan['balance']-$amount;
            //  $balc=abs($balc);
            $small=$tray['smalltray'];
            $big=$tray['bigtray'];
            $inhand=$tray['inhand'];
   
  $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
  $exeins=mysqli_query($con,$ins);           
  
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $amt);
  }
  else{
   $balance1 = $bal4;
  }
   $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$pay_date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales : $row[customer_name]',
  patti_id = '$row[sale_id]',
  payment_id = '6',
  ids='$row[customer_id]'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  
              header("location:pays.php?invoice=Sales&customer=$customer");
       
        }
    else{
        // $am-=$row['total_bill_amount'];
        // echo $row['total_bill_amount'];
    }
    }
        else{
            if($amt==$totpati){
                // echo 0;
                  
            $upsbal="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$row[sales_no]'";
            // print_r($upsbal);die();
            $exesbal=mysqli_query($con,$upsbal);
            // header("location:pays.php?invoice=Patti&supplier=$supplier");
            $pay="SELECT * FROM sar_sales_payment where customer_id='$customerid' ORDER BY id DESC LIMIT 1 ";
            $pay1=$connect->prepare("$pay");
            $pay1->execute();
            $balan=$pay1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          if($balan!=""){
            $balc= $balan['balance']-$amount;
          }
          else{
            $balc=$amu-$amount;
          }   
           $balc=abs($balc);

           $tray="SELECT * FROM trays where name='$customerid' and type='$row[type]' ORDER BY id DESC LIMIT 1 ";
           $tray1=$connect->prepare("$tray");
           $tray1->execute();
           $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
          //  $balc= $balan['balance']-$amount;
          //  $balc=abs($balc);
          $small=$tray['smalltray'];
          $big=$tray['bigtray'];
          $inhand=$tray['inhand'];
          
            $ins="INSERT INTO `sar_sales_payment`( `payment_id`, `payment_date`, `amount`, `payment_mode`, `balance`, `is_revoked`, `discount`, `discount_type`, `customer_id`,`sales_no`,`saleid`,`smalltray`,`bigtray`,`inhand`) VALUES('$pay_id','$pay_date',$amt,'$payment_mode',$balc,$is_revoked,$dis,'$amttype','$row[customer_id]','$row[sales_no]','$row[sale_id]',$small,$big,$inhand)";           
            $exeins=mysqli_query($con,$ins);           
            
            
            $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
            $balance_sql1=$connect->prepare("$balance_qry1");
            $balance_sql1->execute();
            $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
            if($bal_row1["balance"]!=""){ 
            $balance1 = abs($bal_row1["balance"] + $amt);
            }
            else{
             $balance1 = 0;
            }
             $fin_trans_qry = "INSERT INTO financial_transactions SET 
            date = '$pay_date',
            credit= '$amt',
            balance='$balance1',
            description = 'Payment for Sale : $row[customer_name]',
            patti_id = '$row[sale_id]',
            payment_id = '6',
            ids='$row[customer_id]'
            ";
            $res2=mysqli_query($con,$fin_trans_qry);
          
            
                        header("location:pays.php?invoice=Sales&customer=$customer");
                 
                 
                break;
            } else{
            // echo $row['total_bill_amount'];
        }
    }
    //    echo $am;
    }
    
    }
  }
}
}
              ?>
        <!-- </div> -->
</div>

<?php
if(isset($_GET['submit'])){
    $invoice=$_GET['invoice'];
    $supplier=$_GET['supplier'];
    if($invoice=="Patti") 
    {
        $inv="Patti";
    }
    else if($invoice=="Sales"){
$inv="Sales";
    }
}
?>
<?php
    require "footer.php";
?>
<?php


if(isset($_POST['revoke_sub'])){
  $revoke_id=$_POST['revoke_id'];
$sid=$_POST['sid'];
$amt=$_POST['remamount'];
$patid=$_POST['pattiid'];
$revoke=$_POST['revoke_id'];
$pay_date=$_POST['pay_date'];

 $upbal="update sar_patti_payment set is_revoked=1,description='Revoked' where payment_id='$revoke'";
 $balexe=mysqli_query($con,$upbal);

$balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
$balance_sql1=$connect->prepare("$balance_qry1");
$balance_sql1->execute();
$bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
if($bal_row1["balance"]!=""){ 
$balance1 = abs($bal_row1["balance"] + $amt);
}

$fin_trans_qry = "INSERT INTO financial_transactions SET 
date = '$pay_date',
debit= '$amt',
balance='$balance1',
description = 'Revoked for Patti Id $patid',
patti_id = '$patid',
payment_id = '6',
ids='$sid'
";
$res2=mysqli_query($con,$fin_trans_qry);

header("location:pays.php?invoice=Patti&supplier=$supplier");

// $sql="select * from sar_patti where supplier_id='$sid' order by total_bill_amount asc";
// $exe=mysqli_query($con,$sql);
// $no=mysqli_num_rows($exe);
// if($no==0){
//     $sql="select * from sar_patti where supplier_id='$sid' and is_active=0 and remain!='' order by total_bill_amount asc";
//     $exe=mysqli_query($con,$sql);
//     $no=mysqli_num_rows($exe);
// }
// $remain=0; $am=0; $f=0; $rems=0; $i=0;
    
//  $upbal="update sar_patti_payment set is_revoked=1 where payment_id='$revoke'";
//  $balexe=mysqli_query($con,$upbal);

// while($row=mysqli_fetch_assoc($exe)){
//     $i=$i+1;
//         $f+=1; 
//     if($row['remain']!=0){
//         $totpati=$row['remain'];
//     }
//     else{
//         $totpati=$row['total_bill_amount'];
//     }

// if($amt<$totpati) {
//     if($remain==0){
//             $ba = $totpati-$amt;
// $upbal="update sar_patti set remain='$ba',is_active=1 where patti_id='$row[patti_id]'";
// $balexe=mysqli_query($con,$upbal);
// $remain=0;

// // $balance_qry1="SELECT balance FROM financial_transactions where patti_id='$row[patti_id]' ORDER BY id DESC LIMIT 1 ";
// // $balance_sql1=$connect->prepare("$balance_qry1");
// // $balance_sql1->execute();
// // $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// // if($bal_row1["balance"]!=""){ 
// // $balance1 = abs($bal_row1["balance"] + $amt);
// // }
// // else{
// // $balance1 = $ba;
// // }
// // // print_r($balance1."n");die();
 
// // $fin_trans_qry = "INSERT INTO financial_transactions SET 
// // date = '$pay_date',
// // debit= '$amt',
// // balance='$balance1',
// // description = 'Revoked for Patti Id $row[patti_id]',
// // patti_id = '$row[patti_id]',
// // payment_id = '6',
// // ids='$row[supplier_id]'
// // ";
// // $res2=mysqli_query($con,$fin_trans_qry);


// header("location:pays.php?invoice=Patti&supplier=$supplier");

// }
//    else{
//         if($amt==$remain){
//         // echo $row['total_bill_amount'];
//         }
//         else if($remain==0){
//             // echo $row['total_bill_amount'];
//         }
//         else{
//             if($remain < $totpati){
//              $ba1= $am-abs($amt)+abs($totpati);

//              $upbal="update sar_patti set remain='$ba1',is_active=1 where patti_id='$row[patti_id]'";
// $balexe=mysqli_query($con,$upbal);

// // $balance_qry1="SELECT balance FROM financial_transactions where patti_id='$row[patti_id]' ORDER BY id DESC LIMIT 1 ";
// // $balance_sql1=$connect->prepare("$balance_qry1");
// // $balance_sql1->execute();
// // $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// // if($bal_row1["balance"]!=""){ 
// // $balance1 = abs($bal_row1["balance"] + $amt);
// // }
// // else{
// // $balance1 = $ba1;
// // }
// // // print_r($balance1."n");die();
 
// // $fin_trans_qry = "INSERT INTO financial_transactions SET 
// // date = '$pay_date',
// // debit= '$amt',
// // balance='$balance1',
// // description = 'Revoked for Patti Id $row[patti_id]',
// // patti_id = '$row[patti_id]',
// // payment_id = '6',
// // ids='$row[supplier_id]'
// // ";
// // $res2=mysqli_query($con,$fin_trans_qry);


// header("location:pays.php?invoice=Patti&supplier=$supplier");
//         }
//         else if($remain > $totpati){
//             $ba2 = $am-abs($amt)+abs($totpati);

//             $upbal="update sar_patti set remain='$ba2',is_active=1 where patti_id='$row[patti_id]'";
// $balexe=mysqli_query($con,$upbal);

// // $balance_qry1="SELECT balance FROM financial_transactions where patti_id='$row[patti_id]' ORDER BY id DESC LIMIT 1 ";
// // $balance_sql1=$connect->prepare("$balance_qry1");
// // $balance_sql1->execute();
// // $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// // if($bal_row1["balance"]!=""){ 
// // $balance1 = abs($bal_row1["balance"] + $amt);
// // }
// // else{
// // $balance1 = $ba2;
// // }
// // // print_r($balance1."n");die();
 
// // $fin_trans_qry = "INSERT INTO financial_transactions SET 
// // date = '$pay_date',
// // debit= '$amt',
// // balance='$balance1',
// // description = 'Revoked for Patti Id $row[patti_id]',
// // patti_id = '$row[patti_id]',
// // payment_id = '6',
// // ids='$row[supplier_id]'
// // ";
// // $res2=mysqli_query($con,$fin_trans_qry);

// header("location:pays.php?invoice=Patti&supplier=$supplier");
  
//         }
//         else{
//             // echo 1;
//             $upbal="update sar_patti set remain=0,is_active=1 where patti_id='$row[patti_id]'";
//             $balexe=mysqli_query($con,$upbal);
//             header("location:pays.php?invoice=Patti&supplier=$supplier");
      
//         }
//         }
//     }
// break;
// }
// else{
//     $rem=$totpati-$amt;
//     $remain-=$rem;
//     $am+=$totpati;
//     if($amt==$remain){
//     //  echo $rem;
//     // echo 1;

//     $upbal="update sar_patti set is_active=1,remain=0 where patti_id='$row[patti_id]'";
//     $balexe=mysqli_query($con,$upbal);
//     header("location:pays.php?invoice=Patti&supplier=$supplier");
      
//         break;
//     // echo $am-$amt;    
// }
// else if(($totpati < $amt)){
//     $val = $am-$amt;
//     if($val<0) {
//         // echo 1;
//         $upbal="update sar_patti set is_active=1,remain=0 where patti_id='$row[patti_id]'";
// $balexe=mysqli_query($con,$upbal);


// $v=$row['remain'];
// if($v!=0){
//   $rems+=$v;
// }
// else{
//   $rems=($totpati-$amt);

// //   $j=$i+1;
//   $sqli="select * from sar_patti where supplier_id='$sid' and remain!='' order by id asc limit 1";
//   $exei=mysqli_query($con,$sqli);
//   $fet=mysqli_fetch_assoc($exei);
// //   print_r($fet);die();
//   $n=($fet['remain']-$fet['total_bill_amount'])+$remain;
// print_r($n."-".$am."-".$rems."-".$remain."-".$fet['total_bill_amount']."-".$fet['remain']);die();
// if($n==0){
// $ba7="update sar_patti set is_active=1,remain='$n' where patti_id='$fet[patti_id]'";
// print_r($ba7);die();
// $exe8=mysqli_query($con,$ba7);
// // header("location:pays.php?invoice=Patti&supplier=$supplier");

// // break;          
// }
// }
// header("location:pays.php?invoice=Patti&supplier=$supplier");
      
//     }
//     else {
//         $remains=$am-$val;
//         if($am==$remains)
//         {
//               $upbal="update sar_patti set is_active=1,remain=0 where patti_id='$row[patti_id]'";
// $balexe=mysqli_query($con,$upbal);
//         // echo 1;   
//         header("location:pays.php?invoice=Patti&supplier=$supplier");
      
//         }
//         else{
//             $ba4 = $am-$remains;
//             $upbal="update sar_patti set remain='$ba4',is_active=1 where patti_id='$row[patti_id]'";
//             $balexe=mysqli_query($con,$upbal);

// //             $balance_qry1="SELECT balance FROM financial_transactions where patti_id='$row[patti_id]' ORDER BY id DESC LIMIT 1 ";
// // $balance_sql1=$connect->prepare("$balance_qry1");
// // $balance_sql1->execute();
// // $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// // if($bal_row1["balance"]!=""){ 
// // $balance1 = abs($bal_row1["balance"] + $amt);
// // }
// // else{
// // $balance1 = $ba4;
// // }
// // // print_r($balance1."n");die();
 
// // $fin_trans_qry = "INSERT INTO financial_transactions SET 
// // date = '$pay_date',
// // debit= '$amt',
// // balance='$balance1',
// // description = 'Revoked for Patti Id $row[patti_id]',
// // patti_id = '$row[patti_id]',
// // payment_id = '6',
// // ids='$row[supplier_id]'
// // ";
// // $res2=mysqli_query($con,$fin_trans_qry);

//           header("location:pays.php?invoice=Patti&supplier=$supplier");
       
//         }
//         break;
// }
// }
//     //  else if($row['total_bill_amount']<$amt && $row['total_bill_amount']>abs($rem)){
//     //     // echo $row['total_bill_amount']-$amt;
//     //     echo $am-$amt;
//     //  }
//     else if($remain > $totpati){
//         // echo $am;
//        if($amt==$totpati)
//        { 
//         $am-=$totpati;
//         $ba5 = abs($am-$amt);
//         $upbal="update sar_patti set remain='$ba5',is_active=1 where patti_id='$row[patti_id]'";
//         $balexe=mysqli_query($con,$upbal);

// //         $balance_qry1="SELECT balance FROM financial_transactions where patti_id='$row[patti_id]' ORDER BY id DESC LIMIT 1 ";
// // $balance_sql1=$connect->prepare("$balance_qry1");
// // $balance_sql1->execute();
// // $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// // if($bal_row1["balance"]!=""){ 
// // $balance1 = abs($bal_row1["balance"] + $amt);
// // }
// // else{
// // $balance1 = $ba5;
// // }
// // // print_r($balance1."n");die();
 
// // $fin_trans_qry = "INSERT INTO financial_transactions SET 
// // date = '$pay_date',
// // debit= '$amt',
// // balance='$balance1',
// // description = 'Revoked for Patti Id $row[patti_id]',
// // patti_id = '$row[patti_id]',
// // payment_id = '6',
// // ids='$row[supplier_id]'
// // ";
// // $res2=mysqli_query($con,$fin_trans_qry);

//         header("location:pays.php?invoice=Patti&supplier=$supplier");
         
//     }
// else{
//     // $am-=$row['total_bill_amount'];
//     // echo $row['total_bill_amount'];
// }
// }
//     else{
//         if($amt==$totpati){
//             // echo 1;

//             $upbal="update sar_patti set remain=0,is_active=1 where patti_id='$row[patti_id]'";
//             $balexe=mysqli_query($con,$upbal);          
      
//             header("location:pays.php?invoice=Patti&supplier=$supplier");
      
//             break;
//         } else{
//         // echo $row['total_bill_amount'];
//     }
// }
// //    echo $am;
// }
// }
}

if(isset($_POST['revokes'])){
  $revid=$_POST['revsid'];
  $revamt=$_POST['revsamt'];
  $revpati=$_POST['revsale'];
  $revsup=$_POST['revcus'];
  $revdate=$_POST['revsdate'];
  $cup="update sar_sales_payment set is_revoked=3 where payment_id='$revid'";
  // print_r($cup);die();
  $exec=mysqli_query($con,$cup);
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] + $revamt);
  }
  else{
  $balance1 = $ba;
  }
  // print_r($balance1."n");die();
   
  $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$revdate',
  credit= '$revamt',
  balance='$balance1',
  description = 'Revoked Cancel for Patti Id $revpati',
  patti_id = '$revpati',
  payment_id = '6',
  ids='$revsup'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);
  if($res2){
  header("location:pays.php?invoice=Sales&customer=$customer");
  }
}

if(isset($_POST['revokec'])){
 
  $revid=$_POST['revid'];
  $revamt=$_POST['revamt'];
  $revpati=$_POST['revpati'];
  $revsup=$_POST['revsup'];
  $revdate=$_POST['revdate'];
  $cup="update sar_patti_payment set is_revoked=3 where payment_id='$revid'";
  // print_r($cup);die();
  $exec=mysqli_query($con,$cup);
  
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 = abs($bal_row1["balance"] - $revamt);
  }
  else{
  $balance1 = $ba;
  }
  // print_r($balance1."n");die();
   
  $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$revdate',
  credit= '$revamt',
  balance='$balance1',
  description = 'Revoked Amount for Patti Id $revpati',
  patti_id = '$revpati',
  payment_id = '6',
  ids='$revsup'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);
  if($res2){
  header("location:pays.php?invoice=Patti&supplier=$supplier");
  }
}

if(isset($_POST['revoke_cus'])){
    $revoke=$_POST['revokecus_id'];
    $sid=$_POST['salesid'];
    $amt=$_POST['cusamount'];
    $patid=$_POST['pattiid'];
    $cuid=$_POST['cid'];
    $pay_date=$_POST['revoke_date'];

    
 $upbal="update sar_sales_payment set is_revoked=1,description='Revoked' where payment_id='$revoke'";
 $balexe=mysqli_query($con,$upbal);

$balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
$balance_sql1=$connect->prepare("$balance_qry1");
$balance_sql1->execute();
$bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
if($bal_row1["balance"]!=""){ 
$balance1 = abs($bal_row1["balance"] + $amt);
}

$fin_trans_qry = "INSERT INTO financial_transactions SET 
date = '$pay_date',
debit= '$amt',
balance='$balance1',
description = 'Revoked Cancel for Patti Id $patid',
patti_id = '$patid',
payment_id = '6',
ids='$sid'
";
$res2=mysqli_query($con,$fin_trans_qry);

header("location:pays.php?invoice=Sales&customer=$customer");

//     $sql="select * from sar_sales_invoice where customer_id='$cuid' order by total_bill_amount asc";
//     $exe=mysqli_query($con,$sql);
//     $no=mysqli_num_rows($exe);
//     if($no==0){
//         $sql="select * from sar_sales_invoice where customer_id='$cuid' and is_active=0 and remain!='' order by total_bill_amount asc";
//         $exe=mysqli_query($con,$sql);
//         $no=mysqli_num_rows($exe);
//     }
//     // print_r($sql);die();
//      $remain=0; $am=0; $f=0; $rems=0; $i=0;
    
//      $upbal="update sar_sales_payment set is_revoked=1 where payment_id='$revoke'";
//      $balexe=mysqli_query($con,$upbal);
    
//     while($row=mysqli_fetch_assoc($exe)){
//         $i=$i+1;
//         $f+=1; 
//         if($row['remain']!=0){
//             $totpati=$row['remain'];
//         }
//         else{
//             $totpati=$row['total_bill_amount'];
//         }
    
//     if($amt<$totpati) {
//         if($remain==0){
//                 $ba = $totpati-$amt;
// $upbal="update sar_sales_invoice set remain='$ba',is_active=1 where sales_no='$row[sales_no]'";
// // print_r($upbal."t");die();
     
// $balexe=mysqli_query($con,$upbal);

//    $remain=0;
         
// //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
// //   $balance_sql1=$connect->prepare("$balance_qry1");
// //   $balance_sql1->execute();
// //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// //   if($bal_row1["balance"]!=""){ 
// //   $balance1 = abs($bal_row1["balance"] - $amt);
// //   }
// //   else{
// //    $balance1 = $ba;
// //   }
// //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
// //   date = '$pay_date',
// //   debit= '$amt',
// //   balance='$balance1',
// //   description = 'Payment for Sales Id $row[sales_no]',
// //   patti_id = '$row[sales_no]',
// //   payment_id = '6',
// //   ids='$row[customer_id]'
// //   ";
// //   $res2=mysqli_query($con,$fin_trans_qry);

//   header("location:pays.php?invoice=Sales&customer=$customer");
   
//     }
//        else{

//         if($amt==$remain){
//             // echo $row['total_bill_amount'];
//             }
//             else if($remain==0){
//                 // echo $row['total_bill_amount'];
//             }
//             else{
//                 if($remain < $totpati){
//                  $ba1= $am-abs($amt)+abs($totpati);
                 
//                  $upbal="update sar_sales_invoice set remain='$ba1',is_active=1 where sales_no='$row[sales_no]'";
//                 //  print_r($upbal."p");die();
//                  $balexe=mysqli_query($con,$upbal);
    
           
// //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
// //   $balance_sql1=$connect->prepare("$balance_qry1");
// //   $balance_sql1->execute();
// //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// //   if($bal_row1["balance"]!=""){ 
// //   $balance1 = abs($bal_row1["balance"] - $amt);
// //   }
// //   else{
// //    $balance1 = $ba1;
// //   }
// //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
// //   date = '$pay_date',
// //   debit= '$amt',
// //   balance='$balance1',
// //   description = 'Payment for Sales Id $row[sales_no]',
// //   patti_id = '$row[sales_no]',
// //   payment_id = '6',
// //   ids='$row[customer_id]'
// //   ";
// //   $res2=mysqli_query($con,$fin_trans_qry);99

  
//   header("location:pays.php?invoice=Sales&customer=$customer");
     
//                             }
//             else if($remain > $totpati){
//                 $ba2 = $am-abs($amt)+abs($totpati);
    
//                      $upbal="update sar_sales_invoice set remain='$ba2',is_active=1 where sales_no='$row[sales_no]'";
//                     //  print_r($upbal."a");die();
//                      $balexe=mysqli_query($con,$upbal);
              
    
// //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
// //   $balance_sql1=$connect->prepare("$balance_qry1");
// //   $balance_sql1->execute();
// //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// //   if($bal_row1["balance"]!=""){ 
// //   $balance1 = abs($bal_row1["balance"] - $amt);
// //   }
// //   else{
// //    $balance1 = $ba2;
// //   }
// //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
// //   date = '$pay_date',
// //   debit= '$amt',
// //   balance='$balance1',
// //   description = 'Payment for Sales Id $row[sales_no]',
// //   patti_id = '$row[sales_no]',
// //   payment_id = '6',
// //   ids='$row[customer_id]'
// //   ";
// //   $res2=mysqli_query($con,$fin_trans_qry);

  
//   header("location:pays.php?invoice=Sales&customer=$customer");
     
//             }
//             else{
//                 // echo 1;
//                 $upbal="update sar_sales_invoice set is_active=1,remain=0 where sales_no='$row[sales_no]'";
//                 // print_r($upbal);die();
//                 $balexe=mysqli_query($con,$upbal);
   
//                 header("location:pays.php?invoice=Sales&customer=$customer");
     
//             }
//             }
//         }
//     break;
//     }
//     else{
  
//         $rem=$totpati-$amt;
//         $remain-=$rem;
//         $am+=$totpati;
//         if($amt==$remain){
//         //  echo $rem;
//         // echo 1;
    
//         $upbal="update sar_sales_invoice set is_active=1,remain=0 where sales_no='$row[sales_no]'";
//         $balexe=mysqli_query($con,$upbal);
       
//         header("location:pays.php?invoice=Sales&customer=$customer");
        
//             break;
//         // echo $am-$amt;    
//     }
//     else if(($totpati < $amt)){
//         $val = $am-$amt;
//         if($val<0) {
//             // echo 1;
//             $upbal="update sar_sales_invoice set is_active=1,remain=0 where sales_no='$row[sales_no]'";
         
// //    print_r($remain);die();
//             $balexe=mysqli_query($con,$upbal);
//             $v=$row['remain'];
//             if($v!=0){
//               $rems+=$v;
//             }
//             else{
//               $rems=($totpati-$amt);
//        $j=$i+1;
//               $sqli="select * from sar_sales_invoice where customer_id='$cuid' order by id asc limit $j";
//               $exei=mysqli_query($con,$sqli);
//               $fet=mysqli_fetch_assoc($exei);
//             //   print_r($fet);die();
//               $n=$fet['remain']-($fet['total_bill_amount']-$remain);
// // print_r($n.$remain.$totpati);die();
// if($n==0){
// $ba7="update sar_sales_invoice set is_active=1,remain='$n' where sales_no='$fet[sales_no]'";
// $exe8=mysqli_query($con,$ba7);
// header("location:pays.php?invoice=Sales&customer=$customer");
       
// break;          
// }
//     header("location:pays.php?invoice=Sales&customer=$customer");
       
// }
           
//         }
//         else {
//             $remains=$am-$val;
//             if($am==$remains)
//             {
//                 $upbal="update sar_sales_invoice set is_active=1,remain=0 where sales_no='$row[sales_no]'";
//                 $balexe=mysqli_query($con,$upbal);
//             // echo 1;   
//             header("location:pays.php?invoice=Sales&customer=$customer");
       
//             }
//             else{
//                 $ba4 = $am-$remains;
//                 $upbal="update sar_sales_invoice set remain='$ba4',is_active=1 where sales_no='$row[sales_no]'";
//                 // print_r($upbal."z");die();
//                 $balexe=mysqli_query($con,$upbal);
    
// //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
// //   $balance_sql1=$connect->prepare("$balance_qry1");
// //   $balance_sql1->execute();
// //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// //   if($bal_row1["balance"]!=""){ 
// //   $balance1 = abs($bal_row1["balance"] - $amt);
// //   }
// //   else{
// //    $balance1 = $ba4;
// //   }
// //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
// //   date = '$pay_date',
// //   debit= '$amt',
// //   balance='$balance1',
// //   description = 'Payment for Sales Id $row[sales_no]',
// //   patti_id = '$row[sales_no]',
// //   payment_id = '6',
// //   ids='$row[customer_id]'
// //   ";
// //   $res2=mysqli_query($con,$fin_trans_qry);

  
//   header("location:pays.php?invoice=Sales&customer=$customer");
     
//             }
//             break;
//     }
//     }
//         //  else if($row['total_bill_amount']<$amt && $row['total_bill_amount']>abs($rem)){
//         //     // echo $row['total_bill_amount']-$amt;
//         //     echo $am-$amt;
//         //  }
//         else if($remain > $totpati){
//             // echo $am;
//            if($amt==$totpati)
//            { 
//             $am-=$totpati;
//             $ba5 = abs($am-$amt);
//             $upbal="update sar_sales_invoice set remain='$ba5',is_active=1 where sales_no='$row[sales_no]'";
//     //   print_r($upbal."q");die();
//             $balexe=mysqli_query($con,$upbal);
    
           
// //   $balance_qry1="SELECT balance FROM financial_transactions where supplier_id='$row[sales_no]' ORDER BY id DESC LIMIT 1 ";
// //   $balance_sql1=$connect->prepare("$balance_qry1");
// //   $balance_sql1->execute();
// //   $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
// //   if($bal_row1["balance"]!=""){ 
// //   $balance1 = abs($bal_row1["balance"] - $amt);
// //   }
// //   else{
// //    $balance1 = $ba5;
// //   }
// //    $fin_trans_qry = "INSERT INTO financial_transactions SET 
// //   date = '$pay_date',
// //   debit= '$amt',
// //   balance='$balance1',
// //   description = 'Payment for Sales Id $row[sales_no]',
// //   patti_id = '$row[sales_no]',
// //   payment_id = '6',
// //   ids='$row[customer_id]'
// //   ";
// //   $res2=mysqli_query($con,$fin_trans_qry);

  
//               header("location:pays.php?invoice=Sales&customer=$customer");
           
//         }
//     else{
//         // $am-=$row['total_bill_amount'];
//         // echo $row['total_bill_amount'];
//     }
//     }
//         else{
//             if($amt==$totpati){
//                 // echo 1;
//                 $upbal="update sar_sales_invoice set is_active=1,remain=0 where sales_no='$row[sales_no]'";
//                 $balexe=mysqli_query($con,$upbal);
          
//                 header("location:pays.php?invoice=Sales&customer=$customer");
          
//                 break;
//             } else{
//             // echo $row['total_bill_amount'];
//         }
//     }
//     //    echo $am;
//     }
//     }
    
}  
?>
<script>
   $(document).ready(function(){

        $("#supp").hide();
    $("#custom").hide();

    var invoice=$(this).find(":selected").val();
   if(invoice=="Patti"){
    $("#supp").show();
    $("#custom").hide();

 }
   else if(invoice=="Sales"){
    $("#custom").show();
    $("#supp").hide();
   }
   
    $("#invoice").change(function(){

        var invoice=$(this).find(":selected").val();
   if(invoice=="Patti"){
    var url = window.location.href;
    if(url == "https://udhaarsudhaar.net/ab_live1/pays.php?invoice=<?=$_GET['invoice']?>&supplier=<?=$_GET['supplier']?>&customer=<?=$_GET['customer']?>&submit=submit"){
window.location.replace("https://udhaarsudhaar.net/ab_live1/pays.php?invoice=Patti&supplier=<?=$_GET['supplier']?>&submit=submit");
    }
      $("#supp").show();
    $("#custom").hide();

 }
   else if(invoice=="Sales"){
    var url = window.location.href;
    if(url == "https://udhaarsudhaar.net/ab_live1/pays.php?invoice=<?=$_GET['invoice']?>&supplier=<?=$_GET['supplier']?>&customer=<?=$_GET['customer']?>&submit=submit"){
window.location.replace("https://udhaarsudhaar.net/ab_live1/pays.php?invoice=Sales&customer=<?=$_GET['customer']?>&submit=submit");
    }
    $("#custom").show();
    $("#supp").hide();
   }
    });
});
    </script>
