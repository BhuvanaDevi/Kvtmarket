<?php require "header.php";
$date = date("Y-m-d");

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
  
}

if($req=="disabled")
{
    $delete="UPDATE `sar_patti` SET is_active=1 WHERE patti_id=:patti_id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':patti_id' => $patti_id));
    header("location:view_patti.php");
}
?>
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
              $groupnames=$rowa['group_name'];

         $sql1="select * from sar_patti where supplier_name='$sup' and is_active!=0 and nullify=0 order by total_bill_amount desc";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
    $sup_id=$row1['supplier_id'];
    $pattiid=$row1['patti_id'];

    $sqlid="select * from sar_supplier where contact_person='$sup'";
    $exeid=mysqli_query($con,$sqlid);
     $rowid=mysqli_fetch_assoc($exeid);

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
 $group_names=$rowc['grp_cust_name'];
//  print_r($cus_id);die();
 
$sql1="select * from sar_sales_invoice where customer_id='$cus_id' and is_active!=0 and nullify=0 order by total_bill_amount desc";
$exe1=mysqli_query($con,$sql1);
 $row1=mysqli_fetch_assoc($exe1);
 $sal_id=$row1['sale_id'];
 $sal_no=$row1['sales_no'];

//  print_r($sal_no);die();
 
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
     $sql1="select * from payment where supplierid='$rowid[supplier_no]' order by id desc limit 1";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
   $no=mysqli_num_rows($exe1);
 
   $sqld="select *,sum(sale) as sale,sum(pay) as pay from payment where supplierid='$rowid[supplier_no]' and date='$date'";
   $exed=mysqli_query($con,$sqld);
    $rowd=mysqli_fetch_assoc($exed);

    $sqlda="select * from payment where supplierid='$rowid[supplier_no]' and date<'$date' order by id desc limit 1";
    $exeda=mysqli_query($con,$sqlda);
     $rowda=mysqli_fetch_assoc($exeda);
   
  // print_r($rowda['date']);die();
  $bal=0;
    if($no>0)
    {
    echo "<div class='row col-md-12'><div class='col-md-6' style='margin-top:50px'>Supplier Name : $supplier</div><div class='col-md-6' style='margin-top:50px;left:100px'>Supplier Id : $rowid[supplier_no]</div></div>
    <table class='table table-bordered mt-4'>
  ";  
  if($rowda["total"]!=0){
   echo"<tr><td align='right'>Old Balance :</td>
   <td>$rowda[total]</td><td></td><td></td></tr>";
  }
  else{
    echo"<tr><td align='right'>Old Balance :</td>
    <td>0</td><td></td><td></td></tr>";
  }
   if($date==$date){
    if($rowd['sale']==0){
      $tots=$rowda["total"]+$row1["sale"];
      echo"<tr><td align='right'>Today Patti</td>
    <td>$row1[sale]</td><td></td><td></td></tr>";
    echo"<tr><td align='right'>Total</td>
    <td>$tots</td><td></td><td></td></tr>";
      }
      else{
        $tots=$rowda["total"]+$rowd["sale"];
        echo"<tr><td align='right'>Today Patti</td>
        <td>$rowd[sale]</td><td></td><td></td></tr>";
        echo"<tr><td align='right'>Total</td>
        <td>$tots</td><td></td><td></td></tr>";
          }  
    if($rowd['pay']==0){
      echo"<tr><td align='right'>Payment</td>
      <td>0</td><td></td><td></td></tr>";
    } else{
      echo"<tr><td align='right'>Payment</td>
      <td>$rowd[pay]</td><td></td><td></td></tr>";
    } 
    echo"<tr><td align='right'>Balance</td>
    <td>$row1[total]</td><td></td><td></td></tr>";
    $bal+=$row1["total"];
   }
      echo "</table>";
    }
else{
  echo "<div class='row col-md-12'><div class='col-md-6' style='margin-top:50px'>Supplier Name : $supplier</div><div class='col-md-6' style='margin-top:50px;left:100px'>Supplier Id : $rowid[supplier_no]</div></div>
  <table class='table table-bordered mt-4'>
";  
if($rowda["total"]!=0){
  echo"<tr><td align='right'>Old Balance :</td>
   <td>$rowda[total]</td><td></td><td></td></tr>";
 }
 else{
  echo"<tr><td align='right'>Old Balance :</td>
   <td>0</td><td></td><td></td></tr>";
 }
  if($date==$date){
    if($rowd['sale']==0){
      $tots=$rowda["total"]+$row1["sale"];
      echo"<tr><td align='right'>Today Patti</td>
    <td>$row1[sale]</td><td></td><td></td></tr>";
    echo"<tr><td align='right'>Total</td>
    <td>$tots</td><td></td><td></td></tr>";
      }
      else{
        $tots=$rowda["total"]+$rowd["sale"];
        echo"<tr><td align='right'>Today Patti</td>
        <td>$rowd[sale]</td><td></td><td></td></tr>";
        echo"<tr><td align='right'>Total</td>
        <td>$tots</td><td></td><td></td></tr>";
          }  
    if($rowd['pay']==0){
      echo"<tr><td align='right'>Payment</td>
      <td>$row1[pay]</td><td></td><td></td></tr>";
    } else{
      echo"<tr><td align='right'>Payment</td>
      <td>$rowd[pay]</td><td></td><td></td></tr>";
    } 
    echo"<tr><td align='right'>Balance</td>
    <td>$row1[total]</td><td></td><td></td></tr>";
    $bal+=$row1["total"];
   }
      echo "</table>";
    }
}
  else if(!empty($invoice) && !empty($customer) && $invoice=="Sales"){
  
    $sql1="select * from payment_sale where customerid='$cus_id' order by id desc limit 1";
    $exe1=mysqli_query($con,$sql1);
     $row1=mysqli_fetch_assoc($exe1);
     $no=mysqli_num_rows($exe1);
    // print_r($no);die();
    
    $sqld="select *,sum(sale) as sale,sum(pay) as pay from payment_sale where customerid='$cus_id' and date='$date'";
    $exed=mysqli_query($con,$sqld);
     $rowd=mysqli_fetch_assoc($exed);
 
     $sqlda="select * from payment_sale where customerid='$cus_id' and date<'$date' order by id desc limit 1";
     $exeda=mysqli_query($con,$sqlda);
      $rowda=mysqli_fetch_assoc($exeda);
  
      $bals=0;
      if($no>0)
      {
      echo "<div class='row col-md-12'><div class='col-md-6' style='margin-top:50px'>Customer Name : $customer</div><div class='col-md-6' style='margin-top:50px;left:100px'>Customer Id : $cus_id</div></div>
      <table class='table table-bordered mt-4'>";  
     if($rowda["total"]!=0){
       echo"<tr><td align='right'>Old Balance :</td>
      <td>$rowda[total]</td><td></td><td></td></tr>";
     }
     else{
      echo"<tr><td align='right'>Old Balance :</td>
      <td>0</td><td></td><td></td></tr>";
     }
     if($date==$date){
       if($rowd['sale']==0){
         $tots=$rowda["total"]+$row1["sale"];
         echo"<tr><td align='right'>Today Sales</td>
       <td>$row1[sale]</td><td></td><td></td></tr>";
       echo"<tr><td align='right'>Total</td>
       <td>$tots</td><td></td><td></td></tr>";
         }
         else{
           $tots=$rowda["total"]+$rowd["sale"];
           echo"<tr><td align='right'>Today Sales</td>
           <td>$rowd[sale]</td><td></td><td></td></tr>";
           echo"<tr><td align='right'>Total</td>
           <td>$tots</td><td></td><td></td></tr>";
             }  
       if($rowd['pay']==0){
         echo"<tr><td align='right'>Payment</td>
         <td>0</td><td></td><td></td></tr>";
       } else{
         echo"<tr><td align='right'>Payment</td>
         <td>$rowd[pay]</td><td></td><td></td></tr>";
       } 
       echo"<tr><td align='right'>Balance</td>
       <td>$row1[total]</td><td></td><td></td></tr>";
       $bals+=$row1["total"];
      }  echo "</table>";
      }
  else{
    echo "<div class='row col-md-12'><div class='col-md-6' style='margin-top:50px'>Customer Name : $customer</div><div class='col-md-6' style='margin-top:50px;left:100px'>Customer Id : $cus_id</div></div>
    <table class='table table-bordered mt-4'>";  
   echo"<tr><td align='right'>Balance Amount :</td>
   <td>0</td><td></td><td></td></tr>";
      echo "</table>";
  }
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
             <input type="hidden" class="form-control" name="supplierid" id="supplierid" value="<?=$sup_id?>">
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
         
 <?php       
 if($sid!=""){
 ?>
  <div class="col-md-12">
          <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Revoke Payment - Supplier</h4>
                          </div>
                        </div>
        <table class='table table-bordered mt-4'>
        <thead>
        <th width="10">Sno</th>
        <th width="10">Date</th>
          <th width="30">Pay</th>
          <th width="30">Payment Mode</th>
          <th width="30">Action</th>
          <th width="30"></th>
         </thead>
         <tbody> 
     <?php 
     $sqlrevoke="select * from payment where pattid like 'PAT_%' and active=0 order by id desc";
     $exerevoke=mysqli_query($con,$sqlrevoke);
     $norevoke=mysqli_num_rows($exerevoke);
    $s=0;
    if($norevoke>0){
     while( $fetchrevoke=mysqli_fetch_assoc($exerevoke)) {
      $s+=1;?>
         <tr>
      <td><?=$s?></td>
      <td><?=$fetchrevoke['date']?></td>
      <td><?=$fetchrevoke['pay']?></td>
      <td><?=($fetchrevoke['paymentmode']=="cash" || $fetchrevoke['paymentmode']=="percentage")?"Discount":"-"?></td>
<td>
        <form method="POST" action="">
        <div class="row col-md-12">  
        <input type="submit" name="revokec" class="btn btn-danger" value="Revoke"/>
        <div class="col-md-6"> 
    <input type="hidden" name="revpati" value="<?=$pattiid?>" />
        <input type="hidden" value="<?=$sup_id?>" name="suppid" />
        <input type="hidden" name="revamt" class="form-control" value="<?=$fetchrevoke['pay']?>" placeholder="Enter Revoke Amount Here"/> 
        <!--<input type="date" name="revdate" class="form-control"  value="<?=$date?>"  style="display:none"/>-->
        </div>
    </div>   
    </form>
        </td>
        </tr>
        <?php }
        } else { ?><td></td><td>
         <div class="alert alert-danger" role="alert">
  No Data Found!
</div>
</td><td></td>
        <?php } ?>
        </tbody>
         </table>
          </div>
  </div>
       <?php
         }  
         else if($cus_id!="")
{
  ?>
   <div class="col-md-12">
          <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Revoke Payment - Customer</h4>
                          </div>
                        </div>
                   
    <table class='table table-bordered mt-4'>
    <thead>
        <th width="10">Sno</th>
        <th width="30">Date</th>
          <th width="30">Pay</th>
          <th width="30">Payment Mode</th>
             <th width="30">Action</th>
          <th width="30"></th>
         </thead>
    <tbody> 
      <?php
$sqlcpay="select * from payment_sale where saleid like 'CR_%' and active=0 order by id desc";
$execpay=mysqli_query($con,$sqlcpay);
$nocpay=mysqli_num_rows($execpay);
$c=0;
if($nocpay>0) {
while($fetchcpay=mysqli_fetch_assoc($execpay)) {?>
<tr>
  <td><?=$c+=1;?></td>
<td><?=$fetchcpay['date']?></td>
<td><?=$fetchcpay['pay']?></td>
<td><?=($fetchcpay['paymentmode']=="cash" || $fetchcpay['paymentmode']=="percentage")?"Discount":"-"?></td>
<td><form method="POST" action="">
    <div class="row col-md-12">  
      <input type="submit" name="revokes" class="btn btn-danger" value="Revoke"/>
      <div class="col-md-6"> 
    <input type="hidden" name="revpati" value="<?=$sal_no?>" />
        <input type="hidden" value="<?=$cid?>" name="suppid" />
  <input class="form-control" type="hidden" value="<?=$fetchcpay['pay']?>" name="revsamt" placeholder="Enter Your Revoke Amount"/> 
    <!--<input type="date" class="form-control" name="revsdate" value="<?=$date?>" style="display:none"/>-->
      </div>   
     </div>
    </form>
    </td> 
    </tr>

    <?php } 
    }
    else { ?>
<td></td><td>
         <div class="alert alert-danger" role="alert">
  No Data Found!
</div>
</td><td></td>
    <?php } ?>
      </tbody>
       </table>
       </div>
          </div>
          <?php }    ?>
      
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
date = '$date',
debit= '$amt',
balance='$balance1',
description = 'Revoked for Patti Id $patid',
patti_id = '$patid',
payment_id = '6',
ids='$sid'
";
$res2=mysqli_query($con,$fin_trans_qry);

header("location:pays.php?invoice=Patti&supplier=$supplier");


}

if(isset($_POST['revokes'])){
  $revamt=$_POST['revsamt'];
  $revdate=$_POST['revsdate'];
  $revpati=$_POST['revpati'];
  
  
  $sqlbal="select * from payment_sale where customerid='$cus_id' order by id desc limit 1";
  $exebal=mysqli_query($con,$sqlbal);
  $valbal=mysqli_fetch_assoc($exebal);
  $no=mysqli_num_rows($exebal);

  $sqlcrev="select * from payment_sale where customerid='$cus_id' and saleid='$revpati'";
  $sqlcrevs=mysqli_query($con,$sqlcrev);
  $valcrev=mysqli_fetch_assoc($sqlcrevs);
  $nocrev=mysqli_num_rows($sqlcrevs);

if($nocrev>0){
  $upcrev="update payment_sale set active=1 where saleid='$revpati' order by id desc limit 1";
  // print_r($upcrev);die();
  $execrev=mysqli_query($con,$upcrev);
}
  
  
$tray="SELECT * FROM trays where name='$cus_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
  
  // print_r($no);die();
  if($no>0) {
    if($valbal==""){
      $paybal = $valbal["id"] + 1;
      $pay_id = "PAY" . date("Ym") . $paybal;   
    }
       else{
           $paybal = $valbal["id"] + 1;
           $pay_id = "PAY" . date("Ym") . $paybal;   
      }
      
      if($valbal['total']!=0){
          $ob="select * from payment_sale where customerid='$cus_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['total'];
          // print_r($opne);die();
          $ob_supplier_id="";
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          }
      }
      else{
          $ob="select * from sar_opening_balance where name='$cus_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['amount'];
          // print_r($opne);die();
          $ob_supplier_id=$opb['balance_id'];
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          } 
      }
    $total = $valbal["total"]+$revamt;
  
   $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,paymentmode,smalltray,bigtray,inhand,description,active) values('$group_names','$pay_id','$date','$customer',0,0,$revamt,0,0,$total,'$cus_id','$revpati','',$small,$big,$inhand,'Revoked Amount',2)";
    // print_r($insbal."ko");die(); 
    $exe=mysqli_query($con,$insbal);
  }

    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 =$bal_row1["balance"] - $revamt;
  }
  else{
  $balance1 = $revamt;
  }
  // print_r($balance1."n");die();
  
  $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$date',
  debit= '$revamt',
  balance='$balance1',
  description = 'Revoke Payment : $customer',
  patti_id = '$sal_id',
  payment_id = '6',
  ids='$cus_id'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  header("location:pay.php?invoice=Sales&customer=$customer");
  }

if(isset($_POST['revokec'])){
 
  $revamt=$_POST['revamt'];
  $revpati=$_POST['revpati'];
  $revsup=$_POST['revsup'];
  $revdate=$_POST['revdate'];
  $description="Revoke";
  
  $sqlbal="select * from payment where supplierid='$sup_id' order by id desc limit 1";
  $exebal=mysqli_query($con,$sqlbal);
  $valbal=mysqli_fetch_assoc($exebal);
  $no=mysqli_num_rows($exebal);

  $sqlrev="select * from payment where supplierid='$sup_id' and pattid='$revpati'";
  $sqlrev=mysqli_query($con,$sqlrev);
  $valrev=mysqli_fetch_assoc($sqlrev);
  $norev=mysqli_num_rows($sqlrev);

if($norev>0){
  $uprev="update payment set active=1 where pattid='$revpati' order by id desc limit 1";
  $exerev=mysqli_query($con,$uprev);
}
  
$tray="SELECT * FROM trays where name='$sup_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];

  // print_r($no);die();
  if($no>0) {
    if($valbal==""){
      $paybal = $valbal["id"] + 1;
           $pay_id = "PAY" . date("Ym") . $paybal;   
    }
       else{
           $paybal = $valbal["id"] + 1;
           $pay_id = "PAY" . date("Ym") . $paybal;   
      }
      
      if($valbal['total']!=0){
          $ob="select * from payment where supplierid='$sup_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['total'];
          // print_r($opne);die();
          $ob_supplier_id="";
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          }
      }
      else{
          $ob="select * from sar_ob_supplier where supplier_name='$sup_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['amount'];
          // print_r($opne);die();
          $ob_supplier_id=$opb['ob_supplier_id'];
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          } 
      }

    $total = $valbal["total"]+$revamt;
  
   $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,paymentmode,smalltray,bigtray,inhand,description,active) values('$groupnames','$pay_id','$date','$supplier',$opne,0,$revamt,0,0,$total,'$sup_id','$pattiid','',$small,$big,$inhand,'Revoked Supplier',2)";
    // print_r($insbal."ko");die(); 
    $exe=mysqli_query($con,$insbal);
  }

    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 =$bal_row1["balance"] + $revamt;
  }
  else{
  $balance1 = $revamt;
  }
  // print_r($balance1."n");die();
  
  $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$date',
  credit= '$revamt',
  balance='$balance1',
  description = 'Payment Revoke for Patti $supplier',
  patti_id = '$pattiid',
  payment_id = '6',
  ids='$sup_id'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);

  header("location:pay.php?invoice=Patti&supplier=$supplier");
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
date = '$date',
debit= '$amt',
balance='$balance1',
description = 'Payment Revoke for Patti Id $patid',
patti_id = '$patid',
payment_id = '6',
ids='$sid'
";
$res2=mysqli_query($con,$fin_trans_qry);

header("location:pays.php?invoice=Sales&customer=$customer");
    
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

<?php
if(isset($_POST['remainings'])){
  
  $patid=isset($_POST['patti_id'])?$_POST['patti_id']:"";
    $amount=$_POST['amount'];
    $dis=($_POST['dis']!="")?$_POST['dis']:0;
    $amttype=($_POST['amttype']!="")?$_POST['amttype']:"-";

  //  $amount=($amount=="")?$dis:$amount;
    if($amttype=="cash"){
      if($amount!=""){
        $amt=$amount+$dis;
      }
      else{
        $amount=($amount=="")?$dis:$amount;
      $amt=$amount;
    }
    }
     if($amttype=="percentage"){
      if($amount!=""){
      $amt=$amount-(($dis*$amount)/100);
      }
    else{
      $amount=($amount=="")?$dis:$amount;
      if($bal!=""){
      $amt=($bal*$amount)/100;
      }
      else{
        $amt=($bals*$amount)/100;
      }
    }
  }
    if($amt!=0){
        $amt=round($amt);
    }
    else{
    $amt=round($amount);
    }
    // print_r($amt);die();
if($sup_id!=""){    
 
    $sqlbal="select * from payment where supplierid='$sup_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

$sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$sup_id'";
$query = $connect -> prepare($sql);
   $query->execute();
   $results=$query->fetch(PDO::FETCH_OBJ);
  // $exegrp=mysqli_query($con,$sql);
  // $results=mysqli_fetch_assoc($exegrp);
   $names=$results->contact_person;
   $groupname=$results->group_name;

$tray="SELECT * FROM trays where name='$sup_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];


if($no>0) {
  if($valbal==""){
    $pay_id = "PAY".date("Ym")."1";
     }
     else{
         $paybal = $valbal["id"] + 1;
         $pay_id = "PAY" . date("Ym") . $paybal;   
    }
    
    if($valbal['total']!=0){
        $ob="select * from payment where supplierid='$sup_id' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['total'];
        // print_r($opne);die();
        $ob_supplier_id="";
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        }
    }
    else{
        $ob="select * from sar_ob_supplier where supplier_name='$sup_id' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['amount'];
        // print_r($opne);die();
        $ob_supplier_id=$opb['ob_supplier_id'];
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        } 
    }
    
if($valbal['total']==""){
    $total1=$opne+$tot-$traypay-$amt;
    $total = $valbal["total"]+$total1;
 }
 else{
    $total=$opne+$tot-$traypay-$amt;  
 }

//  print_r($opne." ".$tot." ".$total);die();
 if($total==0){
 $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,paymentmode,smalltray,bigtray,inhand) values('$groupnames','$pay_id','$date','$supplier',$opne,0,$amount,0,$dis,$total,'$sup_id','$pattiid','$amttype',$small,$big,$inhand)";
  // print_r($insbal."ko");die(); 
  $exe=mysqli_query($con,$insbal);

  $upsup="update sar_patti set is_active=0,paid=1 where supplier_id='$sup_id'";
  $exeup=mysqli_query($con,$upsup);

}
else{
 $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,paymentmode,smalltray,bigtray,inhand) values('$groupnames','$pay_id','$date','$supplier',$opne,0,$amount,0,$dis,$total,'$sup_id','$pattiid','$amttype',$small,$big,$inhand)";
  // print_r($insbal."ko");die(); 
  $exe=mysqli_query($con,$insbal);
}
  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
$balance_sql1=$connect->prepare("$balance_qry1");
$balance_sql1->execute();
$bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
if($bal_row1["balance"]!=""){ 
$balance1 =$bal_row1["balance"] - $amt;
}
else{
$balance1 = 0 - $amt;
}
// print_r($balance1."n");die();

$fin_trans_qry = "INSERT INTO financial_transactions SET 
date = '$date',
debit= '$amt',
balance='$balance1',
description = 'Payment for Patti $supplier',
patti_id = '$pattiid',
payment_id = '6',
ids='$sup_id'
";
$res2=mysqli_query($con,$fin_trans_qry);
header("location:pay.php?invoice=Patti&supplier=$supplier");
}  
   
}

if($cus_id!=""){   
 
  $sqlbal="select * from payment_sale where customerid='$cus_id' order by id desc limit 1";
  $exebal=mysqli_query($con,$sqlbal);
  $valbal=mysqli_fetch_assoc($exebal);
  $no=mysqli_num_rows($exebal);
   
  $sql = "SELECT * FROM  sar_customer WHERE customer_no='$cus_id'";
  $query = $connect -> prepare($sql);
     $query->execute();
     $results=$query->fetch(PDO::FETCH_OBJ);
     $names=$results->customer_name;
     $groupname=$results->grp_cust_name;

  $tray="SELECT * FROM trays where name='$cus_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
  
  // print_r($no);die();
  if($no>0) {
    if($valbal==""){
      $pay_id = "PAY".date("Ym")."1";
       }
       else{
           $paybal = $valbal["id"] + 1;
           $pay_id = "PAY" . date("Ym") . $paybal;   
      }
      
      if($valbal['total']!=0){
          $ob="select * from payment_sale where customerid='$cus_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['total'];
          // print_r($opne);die();
          $ob_supplier_id="";
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          }
      }
      else{
          $ob="select * from sar_opening_balance where name='$cus_id' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['amount'];
          // print_r($opne);die();
          $ob_supplier_id=$opb['balance_id'];
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          } 
      }
  
  
  if($valbal['total']==""){
      $total1=$opne+$tot-$traypay-$amt;
      $total = $valbal["total"]+$total1;
   }
   else{
      $total=$opne+$tot-$traypay-$amt;  
   }
  
  $total=round($total);
   
   if($total==0){
    //cus_id
    $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,paymentmode,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$customer',$opne,0,$amount,0,$dis,$total,'$cus_id','$sal_no','$amttype',$small,$big,$inhand)";
    // print_r($insbal."ko");die(); 
    $exe=mysqli_query($con,$insbal);
  
    $upsup="update sar_sales_invoice set is_active=0,paid=1 where customer_id='$cus_id'";
    $exeup=mysqli_query($con,$upsup);
  
  }
   else{
    $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,paymentmode,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$customer',$opne,0,$amount,0,$dis,$total,'$cus_id','$sal_no','$amttype',$small,$big,$inhand)";
    // print_r($insbal."ko");die(); 
    $exe=mysqli_query($con,$insbal);
    }
   
    $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql1=$connect->prepare("$balance_qry1");
  $balance_sql1->execute();
  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
  if($bal_row1["balance"]!=""){ 
  $balance1 =$bal_row1["balance"] + $amt;
  }
  else{
  $balance1 =  $amt;
  }
  // print_r($balance1."n");die();
  
  $fin_trans_qry = "INSERT INTO financial_transactions SET 
  date = '$date',
  credit= '$amt',
  balance='$balance1',
  description = 'Payment for Sales Invoice $customer',
  patti_id = '$sal_id',
  payment_id = '6',
  ids='$cus_id'
  ";
  $res2=mysqli_query($con,$fin_trans_qry);
  header("location:pay.php?invoice=Sales&customer=$customer");           
  }
}
}
              ?>
      