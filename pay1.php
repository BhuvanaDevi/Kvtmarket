<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script><?php require "header.php";
$date = date("Y-m-d");

// $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
// $CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  
// //echo $CurPageURL;exit;
// //echo "http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&supplier=$_GET[supplier]&customer=$_GET[customer]&submit=submit";exit;
// if($CurPageURL=="http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&supplier=$_GET[supplier]&customer=$_GET[customer]&submit=submit")
// {
// header("Location:http://localhost:8080/ab_live/pay.php?invoice=$_GET[invoice]&customer=$_GET[customer]&submit=submit");
// }


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
            	                
            	                echo '<option value="'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
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
    if(url == "http://localhost:8080/ab_live/pay.php?invoice=<?=$_GET['invoice']?>&supplier=<?=$_GET['supplier']?>&customer=<?=$_GET['customer']?>&submit=submit"){
window.location.replace("http://localhost:8080/ab_live/pay.php?invoice=Patti&supplier=<?=$_GET['supplier']?>&submit=submit");
    }
      $("#supp").show();
    $("#custom").hide();

 }
   else if(invoice=="Sales"){
    var url = window.location.href;
    if(url == "http://localhost:8080/ab_live/pay.php?invoice=<?=$_GET['invoice']?>&supplier=<?=$_GET['supplier']?>&customer=<?=$_GET['customer']?>&submit=submit"){
window.location.replace("http://localhost:8080/ab_live/pay.php?invoice=Sales&customer=<?=$_GET['customer']?>&submit=submit");
    }
    $("#custom").show();
    $("#supp").hide();
   }
    });
});
    </script>
<div id="content-page" class="content-page">
        <div class="container-fluid">
          <div class="row">
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
if($cus=="") { 
?>
                 <select class="form-control" id="customer" name="customer" >
                      <option value="">Choose Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
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
    $supplier = $_GET['supplier'];
   } else{
    $customer = $_GET['customer'];
   }

    if(!empty($invoice) && !empty($supplier) && $invoice=="Patti"){
    $sql="select * from sar_patti where supplier_name='$supplier' and is_active!=0 order by total_bill_amount asc";
	$exe=mysqli_query($con,$sql);

    $sql1="select * from sar_patti where supplier_name='$supplier' and is_active!=0 order by total_bill_amount asc";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
    $patti_id=$row1['patti_id'];

    $amot=$row1['total_bill_amount'];
    
    $remain=0;
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
</tr>
";
   }
echo "<tr><td colspan='2' align='center'>Total Amount</td>
<td>$s</td><td>$remain</td></tr></table>";
  }
  else if(!empty($invoice) && !empty($customer) && $invoice=="Sales"){
    $sql="select * from sar_sales_invoice where customer_name='$customer' and is_active!=0 order by total_bill_amount asc";
	$exe=mysqli_query($con,$sql);

    $sql1="select * from sar_sales_invoice where customer_name='$customer' and is_active!=0 order by total_bill_amount asc";
	$exe1=mysqli_query($con,$sql1);
   $row1=mysqli_fetch_assoc($exe1);
    $customer_id=$row1['sales_no'];
    $remain=$row1['remain'];

    $amot=$row1['total_bill_amount'];
    
    $remain=0;
    echo "<hr class='mt-4'><div class='row col-md-12'><div class='col-md-6'>Cs Name : $customer</div></div>
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
<td>$tot</td></tr>
</tr>
";
   }
echo "<tr><td colspan='2' align='center'>Total Amount</td>
<td>$s</td><td>$remain</td></tr></table>";
  }
    ?>
     </div>   </div>
                        </form>
                  </div>

          <div class=" col-lg-6">
            <div class="row col-md-12">
                <div class="col-md-12">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Invoice</h4>
                          </div>
                        </div>
                        <form method="POST">
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
                        <input type="hidden" class="form-control" name="patti_id" id="patti_id" value="<?=$patti_id?>">
             <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="<?=$customer_id?>">
             <input type="hidden"  class="form-control"  name="amot" value="<?=$amot?>">
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
        <select id="discount_type" class="form-control" name="discount_type">
        <option disabled selected>Select Discount Type   </option>
        <option value="Percentage">Percentage</option>
        <option value="Cash">Cash</option>
       </select></div>
       <div class="col-md-6"> 
       <input type="text" name="discount" placeholder="Enter Amount Here" class="form-control" value="">
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
            
</div class="col-md-6">
<div class="iq-card"  id="re">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Revoke</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                     <form method="POST" action="" id="">
                        <?php
                        if($remain!=0){
$remains=$remain;
                        } else{
                            $remains=$row1['total_bill_amount'];           
                        } ?>
                     <input type="text" class="form-control" name="customerrevid" id="customerrevid" value="<?=$customer_id?>">
             <input type="text"  class="form-control"  name="revamount"  id="revamount" value="<?=$remains?>">
                                                <div class="row col-md-12">
                                                    <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="Enter your amount here" name="revokeamt" id="revokeamt">
                            <input type="hidden" class="form-control" placeholder="" value="" name="supplierrevid" id="supplierrevid">
                        </div>
                            <div class="col-md-4">
                                  <input type="submit" class="btn btn-danger" name="revoke" value="Revoke">
                            </div>
                    </div>
                    </form>
                           
                             </div>
                  </div>
                </div>
          </div>
          </div>
         
              <?php
if(isset($_POST['remainings'])){
    $patid=isset($_POST['patti_id'])?$_POST['patti_id']:"";
    $cusid=isset($_POST['customer_id'])?$_POST['customer_id']:"";

    $amount=$_POST['amount']; 
    $amot=$_POST['amot'];
    if($patid!=""){    
        $sql2="select * from sar_patti where patti_id='$patid' and is_active!=0";
        $exe2=mysqli_query($con,$sql2);
        $row=mysqli_fetch_assoc($exe2);
        $check=$amot-$row['remain'];
   
        if($amot==$amount || $row['remain']==$amount){
            $sql="update sar_patti set remain=0,is_active=0 where patti_id='$patid'";
            $exe=mysqli_query($con,$sql);     
    //        print_r($balance."a");die();
        }
        else if($row['remain']==0){
        $balance=$amot-$amount; 
      //  print_r($balance."b");die();
    }
        else if($row['remain']!=0){
            if($row['remain']<0){
                $rem=abs($row['remain']);  
                $balance=$amount+($rem); 
         //       print_r($balance."c");die();
                 if($amot > $balance){
                    $rem=abs($row['remain']);  
                    $balance=$amount-($rem); 
                        $balance=abs($balance);
           //       print_r($balance."e");die();
                    }
                    else if($amot < $balance){
                        $balance=$balance;
             //           print_r($balance."f");die();
                    }
            else if($check==$row['remain']){
                $balance=$row['remain']-$amount; 
               // print_r($balance."d");die();
            }
            else{
                $balance=$balance;
              //  print_r($balance."g");die();
            }
            //  print_r($balance."h");die();
        }
        // else if($amot < $amount){
        //     $rem=$row['remain'];  
        //     $balance=$amot-abs($rem)-$amount; 
        //     print_r($balance."o");die();
             
        //     }
    else{
            $rem=$row['remain'];  
            $balance=$rem-$amount; 
        //    print_r($balance."w");die();
        }
    }
        $bal=$balance;
       // print_r($bal."hely".$amot.$patid);die(); 
           
      if($bal==$amot){
        $sql="update sar_patti set is_active=0,remain=0 where patti_id='$patid'";
        $exe=mysqli_query($con,$sql);
    
      }
    if($bal<0){
       // print_r($bal."hel".$amot.$patid);die(); 
  
        $discount_type = $_POST["discount_type"];
        $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;
    
        if($discount_type=="Percentage"){
            $discount=$discount/100;
            $bal=($bal)*($discount);
        }
        else if($discount_type=="Cash"){
            $bal=$bal-$discount;
        }    

        //$bal < $amot


        $sql="update sar_patti set is_active=0,remain=0 where patti_id='$patid'";
        $exe=mysqli_query($con,$sql);
    
        $sql1="SELECT * FROM sar_patti WHERE supplier_name='$supplier' and is_active!=0 order by total_bill_amount asc limit 1";
            $exe1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($exe1);
            $pattiid=$row1['patti_id'];
            $amt=$row1['total_bill_amount'];
  //  print_r($pattiid);die();
   if($bal<0){
    $bal=$amt-abs($bal);
$sqlbal="update sar_patti set remain='$bal' where patti_id='$pattiid'";
            $exebal=mysqli_query($con,$sqlbal);
           }
           else{
            $sqlbal="update sar_patti set remain='$bal' where patti_id='$pattiid'";
            $exebal=mysqli_query($con,$sqlbal);
           }
                   // /    print_r($sqlbal);die();

        if($exebal){
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                         title: "Patti Invoice",
                        text: "Payment Success",
                        type: "danger"}).then(okay => {
                          if (okay) {
                           window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                         }
                       });
                     });</script>';
        }else{
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                    title: "Patti Invoice",
                    text: "Payment Failure",
                    type: "danger"}).then(okay => {
                            if (okay) {
                                window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                              }
                       
                       });
                     });</script>';
        }
  
      
    }
    else if($bal>0){
        //$bal > $amot
   //  print_r($bal."helo".$amot.$patid);die(); 
  
        $discount_type = $_POST["discount_type"];
        $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;
    
        if($discount_type=="Percentage"){
            $discount=$discount/100;
            $bal=($bal)*($discount);
        }
        else if($discount_type=="Cash"){
            $bal=$bal-$discount;
        }   
       
        $rem=$row['remain'];
         $remain=$row['remain']-$amount;
 //   print_r($remain);die();
if($remain<0 && $rem!=0){
    //print_r($rem);die();
 // print_r(abs($remain) > $amot." ");die();   
  if(abs($remain) > $amot){
     $sql="update sar_patti set is_active=0,remain=0 where patti_id='$patid'";
    $exe=mysqli_query($con,$sql);
    }
    
    $sql1="SELECT * FROM sar_patti WHERE supplier_name='$supplier' and is_active!=0 order by total_bill_amount asc limit 1";
    $exe1=mysqli_query($con,$sql1);
    $row1=mysqli_fetch_assoc($exe1);
    $pattiid=$row1['patti_id'];
    $amt=$row1['total_bill_amount'];
    
    $bal=abs($remain)-$amt-$amot;
   $bal=abs($bal);
   // print_r($bal);die();
    $sqlbal="update sar_patti set remain='$bal' where patti_id='$pattiid'";
    $exebal=mysqli_query($con,$sqlbal);
    }
    else{ 
    //  print_r($remain."r");die();    
// $sql1="SELECT * FROM sar_patti WHERE supplier_name='$supplier' and is_active!=0 order by total_bill_amount asc limit 1";
// $exe1=mysqli_query($con,$sql1);
// $row1=mysqli_fetch_assoc($exe1);
// $pattiid=$row1['patti_id'];
// $amt=$row1['total_bill_amount'];

$sqlbal="update sar_patti set remain='$bal' where patti_id='$pattiid'";
    $exebal=mysqli_query($con,$sqlbal);
    }

//  print_r($pattiid);die();



$sqldis="update sar_patti set remain='$remain' where patti_id='$patid'";
      //  print_r($sqldis);die();
        $exe2=mysqli_query($con,$sqldis);
        if($exe2){
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                         title: "Patti Invoice",
                        text: "Payment Success",
                        type: "danger"}).then(okay => {
                          if (okay) {
                           window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                         }
                       });
                     });</script>';
        }else{
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                    title: "Patti Invoice",
                    text: "Payment Failure",
                    type: "danger"}).then(okay => {
                            if (okay) {
                                window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                              }
                       
                       });
                     });</script>';
        }
    }
    
    else{
        $sql="update sar_patti set remain='0' where patti_id='$patid'";
        $exe=mysqli_query($con,$sql);
        if($exe){
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                         title: "Patti Invoice",
                        text: "Payment Success",
                        type: "danger"}).then(okay => {
                          if (okay) {
                           window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                         }
                       });
                     });</script>';
        }else{
            echo '<script>
            $(document).ready(function(){
                Swal.fire({ 
                    title: "Patti Invoice",
                    text: "Payment Failure",
                    type: "danger"}).then(okay => {
                            if (okay) {
                                window.location.href = "pay1.php?invoice=Patti&supplier='.$supplier.'";
                              }
                       
                       });
                     });</script>';
        }
    }
    }

if($cusid!=""){   
    $sql2="select * from sar_sales_invoice where sales_no='$cusid' and is_active!=0";
	$exe2=mysqli_query($con,$sql2);
    $row=mysqli_fetch_assoc($exe2);
    //print_r($cusid);die();
      $check=$amot-$row['remain'];
    //  print_r($check==$row['remain']."hell");die();
      if($amot==$amount || $row['remain']==$amount){
        $sql="update sar_sales_invoice set remain=0,is_active=0 where sales_no='$cusid'";
        $exe=mysqli_query($con,$sql);     
    //        print_r($balance."a");die();
}
    else if($row['remain']==0){
    $balance=$amot-$amount; 
       //  print_r($balance."b");die();
    }
    else if($row['remain']!=0){
        if($row['remain']<0){
            $rem=$row['remain'];  
            $balance=$amount+($rem); 
             //        print_r($balance."c");die();
              if($amot > $balance){
                $rem=abs($row['remain']);  
                $balance=$amount-($rem); 
                    $balance=abs($balance);
          //  print_r($balance."e");die();
                }
                else if($amot < $balance){
                    $balance=$balance;
            //       print_r($balance."f");die();
                }
        else if($check==$row['remain']){
            $balance=abs($row['remain'])-$amount; 
              //   print_r($balance."c");die();
    }
else{
    $balance=$balance;
//    print_r($balance."g");die();
}
}

else{
        $rem=$row['remain'];  
        $balance=$rem-$amount; 
    //    print_r($balance."w");die();
} 
    }
$bal=$balance;
//print_r($row['remain'].$amount."c");die();
 //print_r($bal."hely".$amot.$patid);die(); 

//print_r($bal);die();
if($bal==$amot){
//   /  print_r($bal."s");die();
    
    $sql="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$cusid'";
    $exe=mysqli_query($con,$sql);
 
    $sql1="SELECT * FROM sar_sales_invoice WHERE customer_name='$customer' and is_active!=0 order by total_bill_amount asc limit 1";
        $exe1=mysqli_query($con,$sql1);
        $row1=mysqli_fetch_assoc($exe1);
        $pattiid=$row1['sales_no'];
        $amt=$row1['total_bill_amount'];
        //  print_r($amt);die();
            $bal=$amt-abs($bal);
            $sqlbal="update sar_sales_invoice set remain='$bal' where sales_no='$pattiid'";
            $exebal=mysqli_query($con,$sqlbal);
  }

    if($bal<0){
//    /  print_r($bal."hel".$amot.$patid);die(); 
  
  
   $discount_type = $_POST["discount_type"];
    $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;

    if($discount_type=="Percentage"){
        $discount=$discount/100;
        $bal=($bal)*($discount);
    }
    else if($discount_type=="Cash"){
        $bal=$bal-$discount;
    }    
    
    $sql="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$cusid'";
    $exe=mysqli_query($con,$sql);
   
    $sql1="SELECT * FROM sar_sales_invoice WHERE customer_name='$customer' and is_active!=0 order by total_bill_amount asc limit 1";
        $exe1=mysqli_query($con,$sql1);
        $row1=mysqli_fetch_assoc($exe1);
        $pattiid=$row1['sales_no'];
        $amt=$row1['total_bill_amount'];
        //  print_r($amt);die();
    if($bal<0){
            $bal=$amt-abs($bal);
            // print_r($bal);die();
            $sqlbal="update sar_sales_invoice set remain='$bal' where sales_no='$pattiid'";
            $exebal=mysqli_query($con,$sqlbal);
        }
        else{
            $sqlbal="update sar_sales_invoice set remain='$bal' where sales_no='$pattiid'";
                        $exebal=mysqli_query($con,$sqlbal);
        }
//print_r($pattiid);die();
     // /    print_r($sqlbal);die();

    if($exebal){
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                     title: "Sales Invoice",
                    text: "Payment Success",
                    type: "danger"}).then(okay => {
                      if (okay) {
                       window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                     }
                 });</script>';
    }else{
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                title: "Sales Invoice",
                text: "Payment Failure",
                type: "danger"}).then(okay => {
                        if (okay) {
                            window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                          }
                   
                   });
                 });</script>';
    }
  
}
else if($bal>0){
   //$bal > $amot
   //  print_r($bal."helo".$amot.$patid);die(); 
  
   $discount_type = $_POST["discount_type"];
    $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;

    if($discount_type=="Percentage"){
        $discount=$discount/100;
        $bal=($bal)*($discount);
    }
    else if($discount_type=="Cash"){
        $bal=$bal-$discount;
    }   
    
    // $rem=$row['remain'];
    // $remain=$row['remain']-$amount;
// print_r($remain);die();
if($remain<0 && $rem!=0){ 

 //print_r($rem);die();
 // print_r(abs($remain) > $amot." ");die();   
  if(abs($remain) > $amot){
       $sql="update sar_sales_invoice set is_active=0,remain=0 where sales_no='$cusid'";
    $exe=mysqli_query($con,$sql);
  }
    $sql1="SELECT * FROM sar_sales_invoice WHERE customer_name='$customer' and is_active!=0 order by total_bill_amount asc limit 1";
        $exe1=mysqli_query($con,$sql1);
        $row1=mysqli_fetch_assoc($exe1);
        $pattiid=$row1['sales_no'];
        $amt=$row1['total_bill_amount'];

        $bal=abs($remain)-$amt-$amot;
        $bal=abs($bal);
        // print_r($bal);die();
        
  $sqldis="update sar_sales_invoice set remain='$remain' where sales_no='$pattiid'";
    $exe2=mysqli_query($con,$sqldis);
   }else{

   // print_r($bal);die();
    $sqldis="update sar_sales_invoice set remain='$remain' where sales_no='$pattiid'";
    $exe2=mysqli_query($con,$sqldis);
 
}
 
//    $sqldis="update sar_sales_invoice set remain='$remain' where sales_no='$cusid'";
//    $exe2=mysqli_query($con,$sqldis);
 
   if($exe2){
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                     title: "Sales Invoice",
                    text: "Payment Success",
                    type: "danger"}).then(okay => {
                      if (okay) {
                       window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                     }
                   });
                 });</script>';
    }else{
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                title: "Sales Invoice",
                text: "Payment Failure",
                type: "danger"}).then(okay => {
                        if (okay) {
                            window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                          }
                   
                   });
                 });</script>';
    }
}

else{
    $sql="update sar_sales_invoice set remain='0' where sales_no='$cusid'";
    $exe=mysqli_query($con,$sql);

    if($exe){
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                     title: "Sales Invoice",
                    text: "Payment Success",
                    type: "danger"}).then(okay => {
                      if (okay) {
                       window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                     }
                   });
                 });</script>';
    }else{
        echo '<script>
        $(document).ready(function(){
            Swal.fire({ 
                title: "Sales Invoice",
                text: "Payment Failure",
                type: "danger"}).then(okay => {
                        if (okay) {
                            window.location.href = "pay1.php?invoice=Sales&customer='.$customer.'";
                          }
                   
                   });
                 });</script>';
    }
}
}
}
              ?>
        </div>
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
if(isset($_REQUEST['revoke'])){
$revamount=$_POST['revamount'];
$revokeamt=$_POST['revokeamt'];
$customerrevid=$_POST['customerrevid'];
$supplierrevid=$_POST['supplierrevid'];

// if($customerrevid!=""){
// if($revamount > $revokeamt){

// }
}
?>