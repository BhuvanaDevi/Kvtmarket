<?php require "header.php";
$date = date("Y-m-d");

if(isset($_REQUEST['req'])!=""){
    $req=$_REQUEST["req"];
} else {
    $req="";
}


if(isset($_REQUEST['id'])!=""){
    $id=$_REQUEST["id"];
} else {
    $id="";
}

?>
 <div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-lg-12">
             <div class="iq-card">
                <div class="iq-card-body p-0">
                   <div class="iq-edit-list">
                      <ul class="iq-edit-profile d-flex nav nav-pills">
                         <li class="col-md-3 p-0">
                            <a class="nav-link active" data-toggle="pill" href="#personal-information">
                               Credit
                            </a>
                         </li>
                         <li class="col-md-3 p-0">
                            <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                               Cash & Carry
                            </a>
                         </li>
                       
                      </ul>
                   </div>
                   
                   <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                       <form id="form1" method="post" action="#" class="searchbox">
                           <div class="form-group">
                                         <label for="exampleInputdate">Sales Invoice ID </label>
                                         <?php
                                         $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
                                        $sales_sql=$connect->prepare("$sales_qry");
                                        $sales_sql->execute();
                                        $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
                                        $Last_id=$sales_row["id"]+1;
                                        $sales_no = "CR_".date("Ym")."0".$Last_id;
                                        ?>
                                         <input type="text" class="form-control" id="sales_no" name="sales_no" value="<?=$sales_no?>" readonly>
                                      </div>
                                  <div class="form-group">
                                         <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="date" required>
                                      </div>
                                      
                                      <div class="form-group">
                                         <label for="exampleInputText1">Customer Name </label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="customer_name" name="customer_name">
                                      </div>
                                       <div class="form-group">
                                         <label for="exampleInputNumber1">Mobile number</label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="mobile_number" name="mobile_number" maxlength="10" pattern="^[6-9]\d{9}$" required>
                                      </div>
                                       <div class="form-group">
                                         <label for="exampleInputText1">Address </label>
                                         <input type="text" class="form-control" id="customer_address" name="customer_address">
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Boxes Arrived</label><span style="color:red">*</span>
                                         <input type="number" class="form-control" id="exampleInputNumber1" name="boxes_arrived" required>
                                      </div>
                                      <div class="iq-card4">
                            
                                <div class="iq-card-header d-flex justify-content-between">
                                   <div class="iq-header-title">
                                      <h4 class="card-title">Payment</h4>
                                   </div>
                                </div>
                                <div class="iq-card-body">
                                   
                                         <div class="form-group">
                                         <label for="exampleFormControlSelect1">Select Quality</label><span style="color:red">*</span>
                                         <select class="form-control" id="exampleFormControlSelect1" name="quality_name[]" required>
                                            <option value="">--Choose Quality--</option>
                                            <?php
                                            $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["quality_name"].'</option>';
                            	           }
                            	           ?>
                                         </select>
                                      </div>
                                       <div class="form-group">
                                         <label for="exampleInputNumber1">Quantity</label><span style="color:red">*</span>
                                         <input type="number" class="form-control quantity0" id="quantity" name="quantity[]" required>
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Rate</label><span style="color:red">*</span>
                                         <input type="number" class="form-control brick_rate0" id="rate" name="rate[]" required>
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Total</label><span style="color:red">*</span>
                                         <input type="number" readonly class="form-control tamt0" id="total_amount" name="bill_amount[]" required>
                                      </div>
                                   
                                 <a href="#dynamic_field"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></a>
                               <button type="submit" name="add_sales_invoice" value="submit" class="btn btn-primary">Submit</button>
                                </div>
                                
                             </div>
                                     <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field">
                                      </div>
                                      </div>
                                   </div>
                        </form>
                        
                   </div>
                   <div class="tab-pane fade active show" id="chang-pwd" role="tabpanel">
                       
                            <form id="form2" method="post" action="#" class="searchbox">
                                  <div class="form-group">
                                         <label for="exampleInputdate">Cash&Carry ID </label>
                                         <?php
                                         $cash_qry="SELECT id FROM sar_cash_carry ORDER BY id DESC LIMIT 1 ";
                                            $cash_sql=$connect->prepare($cash_qry);
                                            $cash_sql->execute();
                                            $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
                                            $Last_id_cash=$cash_row["id"]+1;
                                            $cash_no = "CC_".date("Ym")."0".$Last_id_cash;
                                            
                                        ?>
                                  <input type="text" class="form-control" id="cash_no" name="cash_no" value="<?=$cash_no?>" readonly>
                                      </div>
                                  <div class="form-group">
                                         <label for="exampleInputNumber1">Date</label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control date_cash" id="date_cash" name="date_cash" required>
                                      </div>
                                      <div class="form-group ">
                                         <label for="exampleFormControlSelect1">Select Quality</label><span style="color:red">*</span>
                                         <select class="form-control quality_name_cash" id="quality_name_cash" name="quality_name_cash[]" required>
                                            <option value="">--Choose Quality Name--</option>
                                            <?php
                                            $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["quality_name"].'</option>';
                            	           }
                            	           ?>
                                         </select>
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Quantity</label><span style="color:red">*</span>
                                         <input type="number" class="form-control quantity_cash1" id="quantity_cash" name="quantity_cash[]" required>
                                      </div>
                                      <div class="form-group ">
                                         <label for="exampleInputNumber1">Rate</label><span style="color:red">*</span>
                                         <input type="number" id="rate_cash"
                                         name="rate_cash[]" class="form-control brick_rate_cash1"  required>
                                      </div>
                                      <div class="form-group ">
                                         <label for="exampleInputNumber1">Bill Amount</label><span style="color:red">*</span>
                                         <input type="number" id="bill_amount_cash"
                                         name="bill_amount_cash[]" class="form-control bill_amount_cash"  required>
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Total</label><span style="color:red">*</span>
                                         <input type="number" readonly class="form-control tamt_cash1" name="total_amount_cash[]" required>
                                      </div>
                                        <a href="#dynamic_field2"><button type="button" name="add_more" id="add_more" class="btn btn-success">Add More</button>
                                 </a>
                                        <button type="submit" name="add_cash_carry" value="submit" class="btn btn-primary">Submit</button>
                                 
                               <div class="container-fluid">
                               <div class="col-lg-12">
                              <div class="row" id="dynamic_field2">
                              </div>
                              </div>
                           </div>
                           </form>
                         
                   </div>
                </div>
             </div>
          </div>
        </div>
    </div>
</div>


<?php require "footer.php";
?>
<?php
if(isset($_POST["add_sales_invoice"]))
{
    //print_r($_POST);
    $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
    $sales_sql=$connect->prepare("$sales_qry");
    $sales_sql->execute();
    $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$sales_row["id"]+1;
    $sales_no = "CR_".date("Ym")."0".$Last_id;
    
    $count=count($_POST["quality_name"]);
    
    $date=$_POST["date"];
    $mobile_number=$_POST["mobile_number"];
    $customer_name=$_POST["customer_name"];
    $customer_address=$_POST["customer_address"];
    $boxes_arrived=$_POST["boxes_arrived"];
    // $reg_type="unsettled";
  
      
       $bill_amount=0;
    for($i=0;$i<$count;$i++)
    {
        $quality_name=$_POST["quality_name"][$i];
        $quantity=$_POST["quantity"][$i];
        $rate=$_POST["rate"][$i];
        $bill_amount=$quantity*$rate;
        $total_bill_amount+=$bill_amount;
    
        //print_r($_POST);
if($id==""){
    $add_sales_query="INSERT INTO `sar_sales_invoice` SET
    date='$date',
    sales_id='$sales_id',
    sales_no='$sales_no',
    mobile_number='$mobile_number',
    customer_name='$customer_name',
    customer_address='$customer_address',
    boxes_arrived='$boxes_arrived',
    quality_name='$quality_name',
    quantity='$quantity',
    rate='$rate',
    bill_amount='$bill_amount',
    total_bill_amount='$total_bill_amount'
    ";
    $res=mysqli_query($con,$add_sales_query);
    echo $add_sales_query;
    }
    }
}
if(isset($_POST["add_cash_carry"]))
{
    //print_r($_POST);
    $cash_qry="SELECT id FROM sar_cash_carry ORDER BY id DESC LIMIT 1 ";
    $cash_sql=$connect->prepare("$cash_qry");
    $cash_sql->execute();
    $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id_cash=$cash_row["id"]+1;
    $cash_no = "CC_".date("Ym")."0".$Last_id_cash;
    
    $count=count($_POST["quality_name_cash"]);
    
    $date=$_POST["date_cash"];
    
    // $reg_type="unsettled";
  
      
       $bill_amount=0;
    for($i=0;$i<$count;$i++)
    {
        
        $quality_name_cash=$_POST["quality_name_cash"][$i];
        $qty_cash=$_POST["quantity_cash"][$i];
        $rate_cash=$_POST["rate_cash"][$i];
        $bill_amount_cash=$qty_cash*$rate_cash;
        $total_bill_amount_cash+=$bill_amount_cash;
    
        //print_r($_POST);
if($id==""){
    $add_sales_query1="INSERT INTO `sar_cash_carry` SET
    date='$date_cash',
    cash_id='$cash_id',
    cash_no='$cash_no',
    quality_name='$quality_name_cash',
    quantity='$qty_cash',
    rate='$rate_cash',
    bill_amount='$bill_amount_cash',
    total_bill_amount='$total_bill_amount_cash'
    ";
    $res1=mysqli_query($con,$add_sales_query1);
    echo $add_sales_query1;
    }
    }
}
?>

<script>
    $(document).ready(function(){
         $("#rate").on("change",function(){
           var quantity=$("#quantity").val();
           var rate=$(this).val();
           var bill_amount = quantity * rate;
           $("#total_amount").val(bill_amount.toFixed(2));
        });
    
        var i=1;  
      $('#add').click(function(){  
          // i++;  
          
           $('#dynamic_field').append('<div class="iq-card col-md-89" id="inputFormRow" style="margin:10px;"> <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body">        <div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="exampleFormControlSelect1" name="quality_name[]"><option value="">--Choose Quality--</option><?php
                            $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option>'.$sel_row["quality_name"].'</option>';
            	           }
            	           ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty" name="quantity[]" id="qty'+i+'"></div> <div class="form-group">                             <label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr" id="rate_arr'+i+'" myattr="'+i+'"name="rate[]"></div><div><label>Total</label><input type="text" name="bill_amount[]" id="bill_amount'+i+'" class="form-control"></div><div><label>Total Bill Amount</label><input type="text" id="total_bill_amount'+i+'" name="total_bill_amount" class="form-control tot_bill_amt"><br><button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div></div></div><br>');  
                        $(".rate_arr").on('change',function(){
                var id=$(this).attr("myattr");
                var qty=$("#qty"+id).val();
                var rate_arr=$(this).val();
                var total=qty*rate_arr;
              // alert*(total);
                $("#bill_amount"+id).val(total.toFixed(2));
            });
            i++;
   
      });
      $('#add_more').click(function(){  
          // i++;  
          
           $('#dynamic_field2').append('<div class="iq-card col-md-89" id="inputFormRow2" style="margin:10px;"> <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body">        <div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="quality_name_cash" name="quality_name_cash[]"><option value="">--Choose Quality--</option><?php
                            $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option>'.$sel_row["quality_name"].'</option>';
            	           }
            	           ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty_cash" name="quantity_cash[]" id="qty_cash'+i+'"></div> <div class="form-group">                             <label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr_cash" id="rate_arr_cash'+i+'" myattr="'+i+'"name="rate_cash[]"></div><div><label>Total</label><input type="text" name="bill_amount_cash[]" id="bill_amount_cash'+i+'" class="form-control"></div><div><label>Total Bill Amount</label><input type="text" id="total_bill_amount'+i+'" name="total_bill_amount_cash" class="form-control tot_bill_amt"><br><button id="removeRow2" type="button" class="btn btn-danger">Remove</button></div></div></div></div><br>');  
                        $(".rate_arr_cash").on('change',function(){
                var id=$(this).attr("myattr");
                var qty_cash=$("#qty_cash"+id).val();
                var rate_arr_cash=$(this).val();
                var total=qty_cash*rate_arr_cash;
              // alert*(total);
                $("#bill_amount_cash"+id).val(total.toFixed(2));
            });
            i++;
   
      });
      $(document).on('click', '#removeRow', function() {
                  $(this).closest('#inputFormRow').remove();
               });
      $(document).on('click', '#removeRow2', function() {
                  $(this).closest('#inputFormRow2').remove();
               });
    $(".brick_rate0").on("change",function(){
           var quantity0=$(".quantity0").val();
           var brick_rate0=$(this).val();
           var tamt0 = quantity0 * brick_rate0;
           $(".tamt0").val(tamt0.toFixed(2));
        });
        $(".brick_rate_cash1").on("change",function(){
           var quantity_cash1=$(".quantity_cash1").val();
           var brick_rate_cash1=$(this).val();
           var tamt_cash1 = quantity_cash1 * brick_rate_cash1;
           $(".tamt_cash1").val(tamt1.toFixed(2));
        });
    $(".sales").on("click",function(){
	var searchval=$("#searchval").val();
	$.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_sales_search","searchval":searchval},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        $("#searchval_disp2").html("Mobile Number Found").fadeOut(5000);
                        $("#customer_name").val(result.data.customer_name).attr('readonly', true);
                        $("#mobile_number").val(result.data.mobile_number).attr('readonly', true);
                        $("#customer_address").val(result.data.customer_address).attr('readonly', true);
                } else {
                    $("#searchval_disp").html("Mobile Number Not Found").fadeOut(5000);
                }
            }
                
        })
    });
    $("#clear").on("click",function(){
    	$("#searchval").val('');
    	$("#customer_name").val('').attr('readonly', false);
        $("#mobile_number").val('').attr('readonly', false);
        $("#customer_address").val('').attr('readonly', false);
    });
  
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;    
    $('.datepicker').attr('max', maxDate);
 
});

</script>