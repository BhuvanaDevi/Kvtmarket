
<?php
require "header.php";
$purid=isset($_REQUEST['exp_id'])?$_REQUEST['exp_id']:"";

$purchaseqry="SELECT * FROM sar_expenditure where id='$purid'";
$purchasesql=$connect->prepare("$purchaseqry");
$purchasesql->execute();
$purchaserow=$purchasesql->fetch(PDO::FETCH_ASSOC);
// print_r($purchaserow['group_name']);die();
 ?>
 <div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class=" col-lg-12">
               <!-- <a href="view_stock.php"><button style="float: right;color:#fff" class="btn btn-warning">Edit Purchase</button></a>  -->
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                          <h4 class="card-title">Edit Expenditure</h4>
                        
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="post" action="#" class="searchbox">
                        <div class="row col-md-12">
                                <input type="hidden" class="form-control" id="exp_no" name="exp_no" value="<?=$purid?>" readonly>
                              
                                <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                 <input type="date" value="<?= $purchaserow['date'] ?>" class="form-control datepicker" id="date" name="date" value="" required>
                              </div>

                              <div class="form-group col-md-6">
                              <label for="exampleInputNumber1">Expenditure Type</label><span style="color:red">*</span>
                            <select name="particulars" class="form-control">
                                <option disabled selected>Select Expenditure Type</option>

                                <option value="Comission" <?= ($purchaserow['particulars']=="Comission")?"selected=selected":"" ?>>Comission</option>

<option value="Tea" <?= ($purchaserow['particulars']=="Tea")?"selected=selected":"" ?>>Tea</option>

<option value="Salary" <?= ($purchaserow['particulars']=="Salary")?"selected=selected":"" ?>>Salary</option>

<option value="Breakfast" <?= ($purchaserow['particulars']=="Breakfast")?"selected=selected":"" ?>>Breakfast</option>

<option value="Fuel" <?= ($purchaserow['particulars']=="Fuel")?"selected=selected":"" ?>>Fuel</option>

<option value="Stationary" <?= ($purchaserow['particulars']=="Stationary")?"selected=selected":"" ?>>Stationary</option>

<option value="Repair" <?= ($purchaserow['particulars']=="Repair")?"selected=selected":"" ?>>Repair</option>
<option value="Furniture" <?= ($purchaserow['particulars']=="Furniture")?"selected=selected":"" ?>>Furniture</option>
<option value="Hand Loan" <?= ($purchaserow['particulars']=="Hand Loan")?"selected=selected":"" ?>>Hand Loan</option>
<option value="Other Expense" <?= ($purchaserow['particulars']=="Other Expense")?"selected=selected":"" ?>>Other Expense</option>  

                            </select>
                           </div>

                              <div class="form-group col-md-4">
                                 <label for="exampleInputText1">People</label>
                                 <select name="type" id="type" class="form-control">
                                <option disabled selected>Select Customer / Supplier</option>
                                <option value="Customer" <?= ($purchaserow['type']=="Customer")?"selected=selected":"" ?>>Customer</option>
                                <option value="Supplier" <?= ($purchaserow['type']=="Supplier")?"selected=selected":"" ?>>Supplier</option>
                                 </select> 
                            </div>
                            
                              <div class="form-group col-md-4" id="grp">
                                 <label for="exampleInputText1">Group Name </label>
                                 <!-- <input type="text" class="form-control" id="grp" name="grp" value=""> -->
                          <input list="grp_name" class="form-control" name="grpname" id="grpname" value="<?=$purchaserow['grp']?>"> 
                                <datalist class="grp_name" id="grp_name" name="group_name" required >
                                    <option value="">--Choose Group Name--</option>
                                    <?php
                                        $sel_qry = "SELECT distinct group_name from `sar_group` order by group_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["group_name"].'">'.$sel_row["group_name"].'</option>';
                                            }
                                                 ?>
                                </datalist>

                              </div>

                              <div class="form-group col-md-4" id="grp1">
                                 <label for="exampleInputText1">Group Name </label>
                                 <input list="grp_name1" class="form-control" name="grpname1" id="grpname1" value="<?=$purchaserow['grp']?>"> 
                                <datalist class="grp_name1" id="grp_name1" name="group_name1" required >
                                    <option value="">--Choose Group Name--</option>
                             <?php
                                        $sel_qry1 = "SELECT * from `sar_group_customer` order by grp_cust_name ASC ";
                                    	$sel_sql1= $connect->prepare($sel_qry1);
                        	            $sel_sql1->execute();
                        	           while ($sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC)){
                        	              
                                        echo '<option value="'.$sel_row1["grp_cust_name"].'">'.$sel_row1["grp_cust_name"].'</option>';
                                    
                                    }
                                                 ?>
                                </datalist>
                               </div>

                               <div class="form-group col-md-4" id="cus">
                               <label for="exampleInputText1">Expenditure To </label>
                          <input list="searchval1" id="search_val1" class="form-control" name="search_val1" value="<?=$purchaserow['purchased_from']?>">
                                <datalist class="searchval1" id="searchval1" name="customer_name" required>
                                    <option value="">--Choose Customer Name--</option>
                                  
                                 <?php
                                //     $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                // 	$sel_sql= $connect->prepare($sel_qry);
                    	        //     $sel_sql->execute();
                    	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                    	                
                    	        //         echo '<option>'.$sel_row["contact_person"].'</option>';
                    	        //    }
                    	           ?>
                                </datalist> 

                                </div>
  
                              <div class="form-group col-md-4" id="sup">
                                 <label for="exampleInputText1">Expenditure To </label>
                           <input list="searchval" id="search_val" class="form-control" name="search_val" value="<?=$purchaserow['purchased_from']?>">
                                <datalist class="searchval" id="searchval" name="supplier_name" required>
                                    <option value="">--Choose Supplier Name--</option>
                                  
                                 <?php
                                //     $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                // 	$sel_sql= $connect->prepare($sel_qry);
                    	        //     $sel_sql->execute();
                    	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                    	                
                    	        //         echo '<option>'.$sel_row["contact_person"].'</option>';
                    	        //    }
                    	           ?>
                                </datalist> 
                             </div>
                              
                              <div class="form-group col-md-12">
                                 <label for="exampleInputText1">Remarks </label><span style="color:red">*</span>
                                 <!-- <input type="text" class="form-control" id="particulars" name="particulars" value="" required> -->
                                 <textarea id="particulars" name="remark" class="form-control" required><?=$purchaserow['remarks']?></textarea>
                              </div>
                               <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Amount</label><span style="color:red">*</span>
                                 <input type="number" id="amount" min="0" name="amount" class="form-control" id="exampleInputNumber1" value="<?=$purchaserow['amount']?>" required>
                              </div>
                          
                              <!-- <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Revenue</label><span style="color:red">*</span>
                            <select name="revenue" class="form-control">
                                <option disabled selected>Select Revenue</option>
                                <option value="Expenditure">Expenditure</option>
                                <option value="Miscellaneous Revenue">Miscellaneous Revenue</option>
                            </select>
                                </div> -->
                          
                              <div class="form-group col-md-6">
                              <label for="exampleInputNumber1">Payment Mode</label><span style="color:red">*</span>
                            <select name="payment_mode" class="form-control">
                                <option disabled selected>Select Payment Mode</option>
                                
                                <option value="NEFT" <?= ($purchaserow['payment_mode']=="NEFT")?"selected=selected":"" ?>>NEFT</option>

<option value="Gpay" <?= ($purchaserow['payment_mode']=="Gpay")?"selected=selected":"" ?>>Gpay(UPI)</option>

<option value="Cash" <?= ($purchaserow['payment_mode']=="Cash")?"selected=selected":"" ?>>Cash</option>

<option value="Cheque" <?= ($purchaserow['payment_mode']=="Cheque")?"selected=selected":"" ?>>Cheque</option>

                                <!-- <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="DD">DD</option>
                                <option value="UPI">UPI</option>
                                <option value="NEFT">NEFT</option> -->
                            </select>
                           </div>
                        <button type="submit" class="btn btn-primary" name="add_stock_purchase" value="Submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div><br><br>
        </div>
    </div>
</div>
      <!-- Wrapper END -->
      <!-- Footer -->
<?php
require "footer.php";
 //print_r($_POST);
if(isset($_POST["add_stock_purchase"]))
{
//     $exp_qry="SELECT id FROM sar_expenditure ORDER BY id DESC LIMIT 1 ";
//   $exp_sql=$connect->prepare("$exp_qry");
//   $exp_sql->execute();
//   $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
//   $Last_id=$exp_row["id"]+1;
//   $exp_no = "EXP_".date("Ym")."0".$Last_id;
  $exp_no=$_POST['exp_no'];
  $date = ucwords($_POST["date"]);
  $type=$_POST['type'];
  $grp=$_POST['grpname1'];
  $purchased_from = ucwords($_POST["search_val1"]);
  $particulars = ucwords($_POST["particulars"]);
  $amount = $_POST["amount"];
  $payment_mode = $_POST["payment_mode"];
  $remarks = $_POST["remark"];
  
  if($type=="Supplier" || $type=="Customer"){
    $balance_qry="SELECT * FROM sar_expenditure where grp='$grp' and purchased_from='$purchased_from' and id!='$purid' ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
    $balance1 = abs($bal_row["balance"] - $amount);
 //   print_r($balance1);die();
    if($balance1!=0){
        $balance=$balance1;
    }else{
        $balance=$amount;
    }
  }
  $bal=$balance;
  
 // print_r($balance);die();    exp_no='$exp_no',
                    
            $query_1 = "UPDATE `sar_expenditure` SET 
                        date='$date',
                        type='$type',
                         grp='$grp',
                        purchased_from='$purchased_from',
                        particulars='$particulars',
                        amount='$amount',
                        remarks='$remarks',
                        payment_mode='$payment_mode',
                        balance='$bal'
                        WHERE id='$purid'";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
   header('Location: expenditure.php');
   } 
?>


<script>
//    $("#grp1").hide();
//     $("#cus").hide();
//     $("#grp").hide();
//     $("#sup").hide();
 
// $("#type").on("focus", function() {
    var type = $("#type").val();
    // alert(type);
  if(type=="Customer"){
    $("#grp1").show();
    $("#cus").show();
    $("#grp").hide();
    $("#sup").hide();
    }
  else{
    $("#grp1").hide();
    $("#cus").hide();
    $("#grp").show();
    $("#sup").show();
   }
// });
    
$("#grpname1").on("change", function() {
    var grp = $(this).val();
    // alert(grp);
    $.ajax({
        type: "POST",
        url: "forms/ajax_request.php",
        data: {
            "action": "fetchsup",
            "grp": grp
        },
        dataType: "json",
        success: function(result) {
            var len = result.length;
            // alert(result.length);
            $("#searchval1").empty();
            $("#searchval1").append('<option>Choose Customer Name</option>');
            for (var i = 0; i < len; i++) {
                $("#searchval1").append('<option>' + result[i].customer_name + '</option>');
            }
            // alert(result.contact_person);
        }
    })
});

      $("#grpname").on("change",function(){
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
                    $("#searchval").empty();
                    for(var i=0;i<len;i++){
                    $("#searchval").append('<option>'+result[i].contact_person+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});
 //   $("#searchval").chosen();
</script>
