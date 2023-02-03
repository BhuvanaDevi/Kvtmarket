<?php
require "header.php";
$purid=isset($_REQUEST['purchase_id'])?$_REQUEST['purchase_id']:"";

$purchaseqry="SELECT * FROM sar_stock where purchase_id='$purid'";
$purchasesql=$connect->prepare("$purchaseqry");
$purchasesql->execute();
$purchaserow=$purchasesql->fetch(PDO::FETCH_ASSOC);
// print_r($purchaserow['group_name']);die();
 ?>
 <div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class=" col-lg-12">
               <a href="view_stock.php"><button style="float: right;color:#fff" class="btn btn-warning">View Stock Purchase</button></a> 
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                          <h4 class="card-title">Stock Purchase</h4>
                        
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="post" action="#" class="searchbox">
                            <div class="row col-md-12">
                                <div class="form-group col-md-6">
                                <label for="exampleInputdate">Purchase ID </label>
                                <?php
                                $purchase_qry="SELECT id FROM   sar_ob_supplier ORDER BY id DESC LIMIT 1 ";
                                $purchase_sql=$connect->prepare("$purchase_qry");
                                $purchase_sql->execute();
                                $purchase_row=$purchase_sql->fetch(PDO::FETCH_ASSOC);
                                $last_id=$purchase_row["id"]+1;
                                $purchase_id = "PUR".date("Ym")."0".$last_id;
                                ?>
                                <input type="text" class="form-control" id="purchase_id" name="purchase_id" value="<?=isset($purchaserow['purchase_id'])?$purchaserow['purchase_id']:""?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                          <input list="grp_name" class="form-control" name="grpname" id="grpname" value="<?=isset($purchaserow['group_name'])?$purchaserow['group_name']:""?>"> 
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
                            </div>
                            <div class="row col-md-12">
                                                            <div class="form-group col-md-6">
                                <label for="exampleFormControlSelect1">Supplier Name</label><span style="color:red">*</span>
                           <input list="searchval" id="search_val" class="form-control" name="search_val" value="<?=isset($purchaserow['supplier_name'])?$purchaserow['supplier_name']:""?>">
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
                            <div class="form-group col-md-6">
                                
                                <label for="exampleFormControlSelect1">Quality Name</label><span style="color:red">*</span>
                                <input list="quality_name" class="form-control" name="qualityname" required id="qualityname" value="<?=isset($purchaserow['quality_name'])?$purchaserow['quality_name']:""?>">
                        <datalist class="quality_name" id="quality_name" name="quality_name">
                                    <option value="">--Choose Quality Name--</option>
                                   <?php
                                    $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                                	$sel_sql= $connect->prepare($sel_qry);
                    	            $sel_sql->execute();
                    	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                    	                echo '<option>'.$sel_row["quality_name"].'</option>';
                    	           }
                    	           ?>
                	         
                        </datalist>
                            </div>
                            </div>
                            <div class="row col-md-12">
                            <div class="form-group col-md-6">
                             <label for="exampleInputNumber1">Quantity</label><span style="color:red">*</span>
                             <input type="number" class="form-control" name="quantity" required id="quantity" min="0" value="<?=isset($purchaserow['quantity'])?$purchaserow['quantity']:""?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputNumber1">Rate</label><span style="color:red">*</span>
                                <input type="number" class="form-control" name="rate" required id="rate" min="0" value="<?=isset($purchaserow['rate'])?$purchaserow['rate']:""?>">
                            </div>
                            </div>
                            <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                <label for="exampleInputNumber1">Amount</label><span style="color:red">*</span>
                                <input type="number" class="form-control" id="amount" required name="amount" min="0" value="<?=isset($purchaserow['stock_amount'])?$purchaserow['stock_amount']:""?>">
                            </div>
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
   
    $purchase_qry="SELECT id FROM sar_stock ORDER BY id DESC LIMIT 1 ";
    $purchase_sql=$connect->prepare("$purchase_qry");
    $purchase_sql->execute();
    $purchase_row=$purchase_sql->fetch(PDO::FETCH_ASSOC);
    $last_id=$purchase_row["id"]+1;
    $purchase_id = "PUR".date("Ym")."0".$last_id;
    
    $date = date("Y-m-d");
    $supplier_name=$_POST["search_val"];
    $group_name=$_POST["grpname"];
    $quality_name=$_POST["qualityname"];
    $quantity=$_POST["quantity"];
    $rate=$_POST["rate"];
    $amount=$_POST["amount"];

    if($purid=="")
    {
    $supplier_insert_query="insert into `sar_stock`(date,purchase_id,supplier_name,group_name,quality_name,quantity,rate,stock_amount,updated_by)values('$date','$purchase_id','$supplier_name','$group_name','$quality_name','$quantity','$rate',$amount,'$username')";
  // echo $supplier_insert_query;exit;
    $supplier_sql=mysqli_query($con,$supplier_insert_query);
    }
    else{
        $upsql="update sar_stock set supplier_name='$supplier_name',group_name='$group_name',quality_name='$quality_name',quantity=$quantity,rate=$rate,stock_amount=$amount where purchase_id='$purid'";
        $exesql=mysqli_query($con,$upsql);

        $purchasqry="SELECT * FROM sar_stock_payment where purchase_id='$purid' order by id desc limit 1";
        $purchassql=$connect->prepare("$purchasqry");
        $purchassql->execute();
        $purchasrow=$purchassql->fetch(PDO::FETCH_ASSOC);
        $balan=$purchasrow['balance'];
        $amt= abs($balan-$amount);
        
        $upssql="update sar_stock_payment set balance=$amt where purchase_id='$purid'";
$exesql=mysqli_query($con,$upssql);


    }
   header('Location: view_stock.php');
   } 
?>

<script>
    $(document).ready(function() {
        // $("#chkNo").click(function(){
        //     var purchase_id=$("#purchase_id").val();
        //     $('#purchase_id').val(purchase_id).attr('readonly',false);    
        //     });
      $('#rate').on("focus",function(){
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

     

</script>
<script>
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