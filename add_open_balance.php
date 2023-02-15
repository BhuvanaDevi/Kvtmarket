<?php
require "header.php";
    ?>
       
 <div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class=" col-lg-6">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                          <h4 class="card-title">Supplier Opening Balance</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                           <p></p>
                        <form method="post" action="#" class="searchbox">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="grp_no" name="group_id" value="<?=$ob_supplier_id?>" readonly>
                                </div>
                            <div class="form-group ">
                                <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                <!-- <select class="form-control grp_name" id="grp_name" name="group_name" required>
                                    <option value="">--Choose Group Name--</option> -->
                                    <?php
                                    //     $sel_qry = "SELECT distinct group_name from `sar_group` order by group_name ASC ";
                                    // 	$sel_sql= $connect->prepare($sel_qry);
                        	        //     $sel_sql->execute();
                        	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	        //         echo '<option>'.$sel_row["group_name"].'</option>';
                        	        //    }
                        	         ?>
                    	       <!-- </select> -->
                               <select class="form-control" id="grp_name" name="group_name" required>
                                            <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct group_name from `sar_supplier` order by group_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["group_name"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
                            </div>
                            <div class="form-group ">
                                <label for="exampleFormControlSelect1">Supplier Name</label><span style="color:red">*</span>
                                <!-- <select class="form-control searchval" id="searchval" name="name" required>
                                    <option value="">--Choose Supplier Name--</option>
                                   -->
                                 <?php
                                //     $sel_qry = "SELECT * from `sar_supplier` order by contact_person ASC ";
                                // 	$sel_sql= $connect->prepare($sel_qry);
                    	        //     $sel_sql->execute();
                    	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                    	                
                    	        //         echo '<option value='.$sel_row["supplier_no"].'>'.$sel_row["contact_person"].'</option>';
                    	        //    }
                    	           ?>
                    	       <!-- </select> -->
                               
                               <select class="form-control" id="supplier" name="supplier" required>
                      <option value="">Choose Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_id"].'">'.$sel_row["supplier_name"].'</option>';
            	           }
            	           ?>
     </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputNumber1">Amount</label>
                                <input type="number" class="form-control" id="amount" required name="amount" min="0">
                            </div>
                        <button type="submit" class="btn btn-primary" name="add_supplier_balance" value="Submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div><br><br>
            <div class=" col-lg-6">
                <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Customer Opening Balance</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form  method="post" action="#" class="searchbox" enctype="multipart/form-data">
                               <div class="form-group ">
                                   
                                     <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                     <!-- <select class="form-control grp_name" id="grp_name" name="group_name" required>
                                        <option value="">--Choose Group Name--</option> -->
                                    <?php
                                    //     $sel_qry = "SELECT distinct grp_cust_name from `sar_group_customer` order by grp_cust_name ASC ";
                                    // 	$sel_sql= $connect->prepare($sel_qry);
                        	        //     $sel_sql->execute();
                        	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	        //         echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                        	        //    }
                        	           ?>
                        	          
                        	           <!-- </select> -->
                                       <select class="form-control" id="group" name="group" required>
                                                        <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option value='.$sel_row["grp_cust_name"].'>'.$sel_row["grp_cust_name"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
                                </div>
                                <div class="form-group ">
                                    <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                                    <!-- <select class="form-control searchval1" id="searchval1" name="customer_name" required>
                                        <option value="">--Choose Customer Name--</option> -->
                                    <?php
                                    //     $sel_qry = "SELECT * from `sar_customer` order by customer_name ASC ";
                                    // 	$sel_sql= $connect->prepare($sel_qry);
                        	        //     $sel_sql->execute();
                        	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	        //         echo '<option value='.$sel_row["customer_no"].'>'.$sel_row["customer_name"].'</option>';
                        	        //    }
                        	       ?>
                        	       <!-- </select> -->
                                   <select class="form-control" id="customer" name="customer" required>
                      <option value="">Choose Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_id"].'">'.$sel_row["customer_name"].'</option>';
                        	           }
                        	           ?>
                    </select>
                                </div>
                              
                              <div class="form-group">
                                 <label for="exampleInputNumber1">Amount</label>
                                 <span style="color:red">*</span>
                                 <input type="number" class="form-control" id="exampleInputNumber1" required name="amount" min="0" required>
                              </div>
                           
                              <input type="submit" class="btn btn-primary"name="add_customer_balance" value="Submit">
                              <!--<button type="submit" class="btn iq-bg-danger">cancel</button>-->
                           </form>
                        </div>
                     </div>
            </div>
        </div>
    </div>
</div>
      <!-- Wrapper END -->
      <!-- Footer -->
      
<?php
require "footer.php";
if(isset($_POST["add_supplier_balance"]))
{
    $supplier_qry="SELECT id FROM sar_ob_supplier ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $ob_supplier_id = "SOB".date("Ym")."0".$Last_id;
    
    $date = date("Y-m-d");
    $name=$_POST["supplier"];
    $group_name=$_POST["group_name"];
    $amount=$_POST["amount"];
    
    $supplier_insert_query="insert into `sar_ob_supplier`(date,ob_supplier_id,supplier_name,group_name,amount,updated_by)values('$date','$ob_supplier_id','$name','$group_name',$amount,'$username')";
    $supplier_sql=mysqli_query($con,$supplier_insert_query);
   
    $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$name'";
 $query = $connect -> prepare($sql);
    $query->execute();
    $results=$query->fetch(PDO::FETCH_OBJ);
    $names=$results->contact_person;
    $groupname=$results->group_name;
    // print_r($names);die();

    
$tray="SELECT * FROM trays where name='$name' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
   
$sqlbal="select * from payment where supplierid='$name' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

// $exebal = $connect->prepare("$sqlbal");
// $exebal->execute(); 
// $valbal = $exebal->fetch(PDO::FETCH_ASSOC);
// $no=$valbal->rowCount();
// print_r($no);die();
if($valbal['total']==0){
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   

        $op=$amount;
    
      $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid) values('$groupname','$pay_id','$date','$names',$op,0,0,0,0,$op,'$name','OB')";
    //   print_r($insbal."k");die(); 
      $exe=mysqli_query($con,$insbal);
    
}
else{
if($no==0) {
    if($valbal==""){
   $pay_id = "PAY".date("Ym")."1";
    }
    else{
        $paybal = $valbal["id"] + 1;
        $pay_id = "PAY" . date("Ym") . $paybal;   
   }
//    print_r($sqlbal);die();

$ob="select * from sar_ob_supplier where supplier_name='$name' order by id desc limit 1";
$op = $connect->prepare("$ob");
$op->execute(); 
$opb = $op->fetch(PDO::FETCH_ASSOC);
$opne=$opb['amount'];
// $ob_supplier_id=$opb['ob_supplier_id'];
if($opne==""){
    $opne=0;
}
else{
    $opne=$amount;
}

// $tr="select * from trays where name='$supplier_id' and type='$type' order by id desc limit 1";
// $tra = $connect->prepare("$tr");
// $tra->execute(); 
// $trayp = $tra->fetch(PDO::FETCH_ASSOC);
// if($trayp==""){
//     $traypay=$boxes_arrived*100;    
// }
// else{
// $traypay=$trayp['inhand']*100;
// }
$pay=0;
    $total=$opne;


  $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid) values('$groupname','$pay_id','$date','$names',$opne,0,$amount,0,0,$total,'$name','OB')";
//   print_r($insbal."ok");die(); 
  $exe=mysqli_query($con,$insbal);
}
else{
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   
  
    if($valbal['total']!=0){
        $ob="select * from payment where supplierid='$name' order by id desc limit 1";
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
        $ob="select * from sar_ob_supplier where supplier_name='$name' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['amount'];
        // print_r($opne);die();
        // $ob_supplier_id=$opb['ob_supplier_id'];
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        } 
    }

if($valbal['total']==""){
    $total1=$opne+$amount;
    $total = $valbal["total"]+$total1;
 }
 else{
    $total=$opne+$amount;  
 }

 $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid) values('$groupname','$pay_id','$date','$names',$opne,0,$amount,0,0,$total,'$name','OB')";
//   print_r($insbal."ko");die(); 
  $exe=mysqli_query($con,$insbal);

}
}

  header('Location: view_ob_balance.php');
   } 
if(isset($_POST["add_customer_balance"]))
{
    
    $name=$_POST["customer"];
    $group_name=$_POST["group"];
    $amount=$_POST["amount"];
     $category='Customer';
    //  print_r($_POST);die();

    
    $cusname="select * from sar_customer where customer_no='$name'";
    $execus=mysqli_query($con,$cusname);
    $customername=mysqli_fetch_assoc($execus);
    $cusname=$customername['customer_name'];

    $supplier_qry="SELECT id FROM sar_opening_balance ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $balance_id = "COB".date("Ym")."0".$Last_id;
    $date=date("Y-m-d");
    
    $supplier_insert_query="insert into `sar_opening_balance`(date,balance_id,name,group_name,amount,customerid,category,updated_by) values('$date','$balance_id','$cusname','$group_name',$amount,'$name','$category','$username')";
   $supplier_sql=mysqli_query($con,$supplier_insert_query);
    
     
   $sql = "SELECT * FROM  sar_customer WHERE customer_no='$name'";
   $query = $connect -> prepare($sql);
      $query->execute();
      $results=$query->fetch(PDO::FETCH_OBJ);
      $names=$results->customer_name;
      $groupname=$results->grp_cust_name;
      // print_r($names);die();

      
$tray="SELECT * FROM trays where name='$name' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
     
  $sqlbal="select * from payment_sale where customerid='$name' order by id desc limit 1";
  $exebal=mysqli_query($con,$sqlbal);
  $valbal=mysqli_fetch_assoc($exebal);
  $no=mysqli_num_rows($exebal);
  
  // $exebal = $connect->prepare("$sqlbal");
  // $exebal->execute(); 
  // $valbal = $exebal->fetch(PDO::FETCH_ASSOC);
  // $no=$valbal->rowCount();
  // print_r($no);die();
  if($valbal['total']==0){
      $paybal = $valbal["id"] + 1;
      $pay_id = "PAY" . date("Ym") . $paybal;   
  
          $op=$amount;
      
        $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid) values('$groupname','$pay_id','$date','$names',$op,0,$amount,0,0,$op,'$name','OB')";
      //   print_r($insbal."k");die(); 
        $exe=mysqli_query($con,$insbal);
      
  }
  else{
  if($no==0) {
      if($valbal==""){
     $pay_id = "PAY".date("Ym")."1";
      }
      else{
          $paybal = $valbal["id"] + 1;
          $pay_id = "PAY" . date("Ym") . $paybal;   
     }
  //    print_r($sqlbal);die();
  
  $ob="select * from sar_opening_balance where name='$name' order by id desc limit 1";
  $op = $connect->prepare("$ob");
  $op->execute(); 
  $opb = $op->fetch(PDO::FETCH_ASSOC);
  $opne=$opb['amount'];
  // $ob_supplier_id=$opb['ob_supplier_id'];
  if($opne==""){
      $opne=0;
  }
  else{
      $opne=$amount;
  }
  
  // $tr="select * from trays where name='$supplier_id' and type='$type' order by id desc limit 1";
  // $tra = $connect->prepare("$tr");
  // $tra->execute(); 
  // $trayp = $tra->fetch(PDO::FETCH_ASSOC);
  // if($trayp==""){
  //     $traypay=$boxes_arrived*100;    
  // }
  // else{
  // $traypay=$trayp['inhand']*100;
  // }
  $pay=0;
      $total=$opne;
  
  
    $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid) values('$groupname','$pay_id','$date','$names',$opne,0,0,0,0,$total,'$name','OB')";
  //   print_r($insbal."ok");die(); 
    $exe=mysqli_query($con,$insbal);
  }
  else{
      $paybal = $valbal["id"] + 1;
      $pay_id = "PAY" . date("Ym") . $paybal;   
    

      if($valbal['total']!=0){
          $ob="select * from payment_sale where customerid='$name' order by id desc limit 1";
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
          $ob="select * from sar_opening_balance where name='$name' order by id desc limit 1";
          //   print_r($ob);die();
            $op = $connect->prepare("$ob");
          $op->execute(); 
          $opb = $op->fetch(PDO::FETCH_ASSOC);
          $opne=$opb['amount'];
          // print_r($opne);die();
          // $ob_supplier_id=$opb['ob_supplier_id'];
          if($opne==0){
              $opne=0;
          }
          else{
              $opne=$opne;
          } 
      }
  
  if($valbal['total']==""){
      $total1=$opne+$amount;
      $total = $valbal["total"]+$total1;
   }
   else{
      $total=$opne+$amount;  
   }
  
   $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid) values('$groupname','$pay_id','$date','$names',$opne,0,$amount,0,0,$total,'$name','OB')";
  //   print_r($insbal."ko");die(); 
    $exe=mysqli_query($con,$insbal);
  
  }
  }
    
   header('Location: view_ob_balance.php');
 }
?>
<script>
    $(document).ready(function(){
        $("#sid_disp").hide();
        $(".chk").click(function(){
                var val=$(this).val();
                //alert(val);
                if(val=="s"){
                    $("#stock_disp").show();
                    $("#quality_disp").show();
                    $("#id_disp").show();
                    $("#sid_disp").hide();
                    
                }else if(val=="c"){
                    $("#stock_disp").show();
                    $("#quality_disp").hide();
                    $("#id_disp").hide();
                    $("#sid_disp").show();
                }
                
            });
        
    });
</script>
<script>
    $(document).ready(function() {
        // $("#chkNo").click(function(){
        //     var purchase_id=$("#purchase_id").val();
        //     $('#purchase_id').val(purchase_id).attr('readonly',false);    
        //     });
      $('#rate').on("change",function(){
        var quantity = $('#quantity').val();
        var rate = $('#rate').val();
        var amount = quantity * rate;
        $('#amount').val(amount);

    });
    
   var dtToday = new Date();
   var month = dtToday.getMonth() + 1;
   var day = dtToday.getDate();
   var year = dtToday.getFullYear();

   if (month < 10)
      month = '0' + month.toString();
   if (day < 10)
      day = '0' + day.toString();

   var maxDate = year + '-' + month + '-' + day;
   $('.datepicker').attr('max', maxDate);
    });
    
    //  $("#searchval").on("change", function() {
    //   var contact_person=$(this).val();
    //   //alert(contact_person);
    //   $.ajax({
    //      type: "POST",
    //      url: "forms/ajax_request_view.php",
    //      data: {
    //         "action": "view_patti_search",
    //         "contact_person":contact_person
    //      },
    //      dataType: "json",
    //      success: function(result) {
    //         if (result.status == 1) {
    //           // $("#searchval_disp").html("");
    //           $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
    //            $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
    //            $("#contact_person").val(result.data.contact_person).attr('readonly', true);
    //            $("#Address").val(result.data.Address).attr('readonly', true);
    //         } 
    //      }

    //   });
    // });
    $("#grp_name").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchgrp","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#supplier").empty();
                    $("#supplier").append('<option>Choose Supplier Name</option>');
                    for(var i=0;i<len;i++){
                    $("#supplier").append('<option value='+result[i].supplier_no+'>'+result[i].contact_person+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});

$("#group").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchsup","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#customer").empty();
                    $("#customer").append('<option>Choose Customer Name</option>');
                    for(var i=0;i<len;i++){
                    $("#customer").append('<option value='+result[i].customer_no+'>'+result[i].customer_name+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});
      $("#searchval2").on("change", function() {
      var customer_name=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_customer_search",
            "customer_name":customer_name
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
    });
      $("#searchval3").on("change", function() {
      var customer_name=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_customer_search",
            "customer_name":customer_name
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
    });
      $("#searchval1").on("change", function() {
      var customer_name=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_customer_search",
            "customer_name":customer_name
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }
      });
    });
</script>
<script>
    // $("#searchval").chosen();
    // $("#searchval1").chosen();
    //  $("#searchval2").chosen();
    //   $("#searchval3").chosen();
</script>