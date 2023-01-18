<?php require "header.php";

$date = date("Y-m-d");

$submit_action = 'add_sales_invoice';
$sales_no = "";
$quality_box = "";
$mobile_number = "";
$customer_name = "";
$customer_address = "";
$boxes_arrived = "";
$cnt = 0;

$quality_name_list = array();
$quality_name_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
$quality_name_qry_stmt = $connect->prepare($quality_name_qry);
$quality_name_qry_stmt->execute();
while ($sel_row = $quality_name_qry_stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($quality_name_list, $sel_row["quality_name"]);
}

if(isset($_REQUEST['req'])!="" && $_REQUEST["req"] == 'edit'){
    $submit_action = 'edit_sales_invoice';
    $sales_no = $_REQUEST["sales_no"];
    $sales_qry = "SELECT * FROM sar_sales_invoice WHERE sales_no = '".$sales_no."'";
    $sales_sql = $connect->prepare("$sales_qry");
    $sales_sql->execute();
	while ($data_row = $sales_sql->fetch(PDO::FETCH_ASSOC)) {
	   
	   if($cnt == 0){
	       $mobile_number = $data_row['mobile_number'];
	       $customer_name = $data_row['customer_name'];
	       $customer_address = $data_row['customer_address'];
	       $boxes_arrived = $data_row['boxes_arrived'];
	       
	   }
	   if ($data_row['quality_name'] == "") {
            continue;
       }
	   $quality_box .= '
	    <div class="iq-card col-md-89 qualityBox" id="inputFormRow" style="margin:10px;">                      
	        <div class="iq-card-header d-flex justify-content-between">
	            <div class="iq-header-title">
	                <h4 class="card-title">Payment</h4>
	            </div>
	        </div>
	        <div class="iq-card-body"> 
	        <div class="form-group">
	            <label for="exampleFormControlSelect1">Select Quality</label>
	            <select class="form-control" id="exampleFormControlSelect1" name="quality_name[]">
	                <option value="">--Choose Quality--</option>';
	   foreach ($quality_name_list as $value){
	       $selected = '';
	       if($value == $data_row['quality_name']){
	           $selected = 'selected';
	       }
	       $quality_box .= '<option '. $selected.'>'.$value.'</option>';
	   }
	   $quality_box .= '</select>
	        </div>
	        <div>
	            <label>Quantity</label>
	            <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" onchange="calculate_bill_amount('.$cnt.')" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'">
	        </div> 
	        <div class="form-group">
	            <label for="exampleInputNumber1">Rate</label>
	            <input type="number" onchange="calculate_bill_amount('.$cnt.')" class="form-control rate_arr sum_tol" onkeyup=total_amt("'. $cnt .'"); id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'">
	        </div>
	        <div>
	            <label>Total</label>
	            <input type="text" readonly name="bill_amount[]" class="sum_tol_ov form-control" id="bill_amount' . $cnt . '" value="'.$data_row['bill_amount'].'" >
	            <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'">
	        </div><br>
	        <button id="removeRow" data-rec-id="' . $data_row['id'] . '" type="button" class="btn btn-danger">Remove</button>
	        </div>
	    </div>';
	   $cnt = $cnt + 1;
	}
} else {
    $req="";
    $sales_qry = "SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
    $sales_sql = $connect->prepare("$sales_qry");
    $sales_sql->execute();
    $sales_row = $sales_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id = $sales_row["id"] + 1;
    $sales_no = "CR_" . date("Ym") . "0" . $Last_id;
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
                   <div class="iq-edit-list-data">
                    <div class="tab-content">
                   <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                       <form id="form1" method="post" action="#" class="searchbox">
                           <div id="delete_rec_id_list">
                         
                           </div>
                           <div class="form-group">
                                         <label for="exampleInputdate">Sales Invoice ID </label>
                                         <input type="text" class="form-control" id="sales_no" name="sales_no" value="<?=$sales_no?>" readonly>
                                      </div>
                                  <div class="form-group">
                                         <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="date" required>
                                      </div>
                                      
                                      <div class="form-group">
                                         <label for="exampleInputText1">Customer Name </label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?=$customer_name?>" required>
                                      </div>
                                       <div class="form-group">
                                         <label for="exampleInputNumber1">Mobile number</label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="mobile_number" name="mobile_number" maxlength="10" pattern="^[6-9]\d{9}$" value="<?=$mobile_number?>" required>
                                      </div>
                                       <div class="form-group">
                                         <label for="exampleInputText1">Address </label>
                                         <input type="text" class="form-control" id="customer_address" name="customer_address" value="<?=$customer_address?>">
                                      </div>
                                      <div class="form-group">
                                         <label for="exampleInputNumber1">Boxes Arrived</label><span style="color:red">*</span>
                                         <input type="number" class="form-control" id="exampleInputNumber1" name="boxes_arrived" value="<?=$boxes_arrived?>" readonly required>
                                      </div>
                                     
                                      
                                      <div class="iq-card4">
                            
                                <div class="iq-card-header d-flex justify-content-between">
                                   
                                   
                         
                                </div>
                                <a href="#dynamic_field"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></a>
                              
                             </div>
                                     <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field">
                                          <?php echo $quality_box; ?>
                                      </div>
                                      </div>
                                   </div>
                                   <div class="form-group">
                                         <label for="exampleInputNumber1">Total Amount</label><span style="color:red">*</span>
                                         <input type="text" readonly class="form-control" id="totalamt" name="totalamt" >
                                      </div>
                                       <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
                        </form>
                        
                   </div>
                   <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                       
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
                                         <input type="number" class="form-control quantity_cash" id="quantity_cash" name="quantity_cash[]" required>
                                      </div>
                                      <div class="form-group ">
                                         <label for="exampleInputNumber1">Rate</label><span style="color:red">*</span>
                                         <input type="number" id="rate_cash"
                                         name="rate_cash[]" class="form-control brick_rate_cash"  required>
                                      </div>
                                      <div class="form-group ">
                                         <label for="exampleInputNumber1">Total</label><span style="color:red">*</span>
                                         <input type="number" id="bill_amount_cash"
                                         name="bill_amount_cash[]" class="form-control bill_amount_cash"  required>
                                      </div>
                                        <a href="#dynamic_field2"><button type="button" name="add_more" id="add_more" class="btn btn-success">Add More</button>
                                 </a>
                                 
                                        <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field2">
                                      </div>
                                      </div>
                            </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                         <label for="exampleInputNumber1">Total Bill Amount</label><span style="color:red">*</span>
                                         <input type="number" readonly class="form-control" name="total_amount_cash[]" id="total_amount_cash" value="0" required>
                                      </div>
                                      </div>
                                      <button type="submit" name="add_cash_carry" value="submit" class="btn btn-primary">Submit</button>
                           </form>
                         
                   </div>
                   </div>
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
    print_r($_POST);
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
    $total_bill_amount = $_POST["totalamt"];
    // $reg_type="unsettled";
  
      
       $bill_amount=0;
    for($i=0;$i<$count;$i++)
    {
        $quality_name=$_POST["quality_name"][$i];
        $quantity=$_POST["quantity"][$i];
        $rate=$_POST["rate"][$i];
        $bill_amount=$quantity*$rate;
        //$total_bill_amount+=$bill_amount;
    
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
            total_bill_amount='$total_bill_amount',
            updated_by='$username',
            is_active=1
            ";
            $res=mysqli_query($con,$add_sales_query);
            echo $add_sales_query;
      }
    }
    $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
   $balance_sql=$connect->prepare("$balance_qry");
   $balance_sql->execute();
   $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
   $balance = $bal_row["inhand"] - $boxes_arrived;
   $tray_trans_qry = "INSERT INTO tray_transactions SET 
                  date = '$date',
                  name = '$customer_name',
                  category = 'Customer',
                  outward = $boxes_arrived,
                  inhand = $balance,
                  description = 'outward from sales $customer_name'";
   $res2=mysqli_query($con,$tray_trans_qry);
   header('Location: ./sales_invoice2.php');
}else if(isset($_POST["edit_sales_invoice"])){
    $count=count($_POST["quality_name"]);
    $date=$_POST["date"];
    $mobile_number=$_POST["mobile_number"];
    $customer_name=$_POST["customer_name"];
    $customer_address=$_POST["customer_address"];
    $boxes_arrived=$_POST["boxes_arrived"];
    $total_bill_amount = $_POST["totalamt"];
    $bill_amount=0;
    $del_count = count($_POST["delete_rec_id"]);
    for($i=0;$i<$del_count;$i++)
   {
       $del_patti_query = "DELETE FROM `sar_patti` WHERE id=".$_POST["delete_rec_id"][$i];
       $del_patti_sql = mysqli_query($con, $del_patti_query);
   }
    for($i=0;$i<$count;$i++)
    {
        $quality_name=$_POST["quality_name"][$i];
        $quantity=$_POST["quantity"][$i];
        $rate=$_POST["rate"][$i];
        $bill_amount=$quantity*$rate;
    }
  for($i=0;$i<$count;$i++)
  {
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
      $rec_id = $_POST["rec_id"][$i];
      $bill_amount=$quantity*$rate;
      
     if($rec_id == ""){
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
            total_bill_amount='$total_bill_amount',
            updated_by='$username',
            is_active=1
            ";
            $res=mysqli_query($con,$add_sales_query);
            echo $add_sales_query;
    }else if($rec_id != ""){
        $add_sales_query = "UPDATE `sar_sales_invoice` SET
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
            total_bill_amount='$total_bill_amount',
            updated_by='$username',
            is_active=1
            WHERE id ='".$rec_id."'
            ";
            
        $add_sales_sql = mysqli_query($con, $add_sales_query);
    }
    
   }
  $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
   $balance_sql=$connect->prepare("$balance_qry");
   $balance_sql->execute();
 
  header('Location: ./view_sales_invoice.php');
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
    $cash_id=strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6));
    $date=$_POST["date_cash"];

    
    
    // $reg_type="unsettled";
  
      
       $bill_amount=0;
       $total_quantity = 0;
    for($i=0;$i<$count;$i++)
    {
        
        $quality_name_cash=$_POST["quality_name_cash"][$i];
        $qty_cash=$_POST["quantity_cash"][$i];
        $rate_cash=$_POST["rate_cash"][$i];
        $bill_amount_cash=$qty_cash*$rate_cash;
        $total_bill_amount_cash+=$bill_amount_cash;
        $total_quantity += $qty_cash;
        //print_r($_POST);
         if($id==""){
            $add_sales_query1="INSERT INTO `sar_cash_carry` SET
            date='$date',
            cash_id='$cash_id',
            cash_no='$cash_no',
            quality_name='$quality_name_cash',
            quantity='$qty_cash',
            rate='$rate_cash',
            bill_amount='$bill_amount_cash',
            total_bill_amount='$total_bill_amount_cash',
            updated_by='$username',
            is_active=1
            ";
            $res1=mysqli_query($con,$add_sales_query1);
            echo $add_sales_query1;

         }
    }

    $balance_qry="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
    $balance = $bal_row["balance"] + $total_bill_amount_cash;
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
                     date = '$date',
                     credit = $total_bill_amount_cash,
                     balance = $balance,
                     description = 'Cash and Carry. ID : $cash_no'
                     ";
   $res2=mysqli_query($con,$fin_trans_qry);

   $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
   $balance_sql=$connect->prepare("$balance_qry");
   $balance_sql->execute();
   $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
   $balance = $bal_row["inhand"] - $total_quantity;
   $tray_trans_qry = "INSERT INTO tray_transactions SET 
                  date = '$date',
                  name = '$cash_no',
                  category = 'Customer',
                  outward = $total_quantity,
                  inhand = $balance,
                  description = 'outward from cash & carry sales $cash_no'";
   $res2=mysqli_query($con,$tray_trans_qry);

   header('Location: ./sales_invoice2.php');
}
?>
<script>
function total_qty(){
var sum_qty = 0;
   $('.sum_qty').each(function() {
      sum_qty += Number($(this).val());
    }); 
 $('#exampleInputNumber1').val(sum_qty);
}
function total_amt(col){
var sum_tol = 0;
   $('.sum_tol').each(function() {
      sum_tol += Number($(this).val());
    }); 
 var rate_arr = parseInt($('#rate_arr'+col).val());
 var qty = parseInt($('#qty'+col).val());
 var total_amount = (qty * rate_arr);
 $('#bill_amount'+col).val(total_amount);
 
 total_amt_ov();
}

function total_amt_ov(){
var sum_tol_ov = 0;
   $('.sum_tol_ov').each(function() {
      sum_tol_ov += Number($(this).val());
    }); 
  $('#totalamt').val(sum_tol_ov);
}


</script>
<script>
    $(document).ready(function(){
         $("#rate").keyup(function(){
           var quantity=$("#quantity").val();
           var rate=$(this).val();
           var bill_amount = quantity * rate;
           $("#bill_amount").val(bill_amount.toFixed(2));
        });
    
        var i=<?=$cnt?>;  
      $('#add').click(function(){  
          // i++;  
          
           $('#dynamic_field').append('<div class="iq-card col-md-89" id="inputFormRow" style="margin:10px;"> <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body">        <div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="exampleFormControlSelect1" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty sum_qty" onkeyup="total_qty();"  name="quantity[]" id="qty'+i+'"></div> <div class="form-group">                             <label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr sum_tol" onkeyup=total_amt("'+i+'"); id="rate_arr'+i+'" myattr="'+i+'"name="rate[]"></div><div><label>Total</label><input type="text" readonly name="bill_amount[]" id="bill_amount'+i+'" class=" sum_tol_ov form-control"></div><br><button id="removeRow" type="button" class=" btn btn-danger">Remove</button></div></div></div></div><br>');  
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
            	           ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty_cash" name="quantity_cash[]" id="qty_cash'+i+'"></div> <div class="form-group">                             <label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr_cash" id="rate_arr_cash'+i+'" myattr1="'+i+'"name="rate_cash[]"></div><div class="addition"><div><label>Total</label><input type="text" name="bill_amount_cash[]" id="bill_amount_cash'+i+'" class="form-control bill_amount_cash"></div></div><br><button id="removeRow2" type="button" class="btn btn-danger">Remove</button></div></div></div></div><br>');  
            	           //Cash & Carry
                $(".rate_arr_cash").keyup(function(){
                    var id=$(this).attr("myattr1");
                    //console.log(id);
                    var qty_cash=$("#qty_cash"+id).val();
                    var rate_arr_cash=$(this).val();
                    var total=qty_cash*rate_arr_cash;
                $("#bill_amount_cash"+id).val(total.toFixed(2));
               // console.log(total);
                update_total_amount_cash()
            });
            i++;
   
      });
      
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
          var id = $(this).attr("data-rec-id");
          if(id){
              $('#delete_rec_id_list').append("<input type='hidden' class='form-control' id='delete_rec_id' name='delete_rec_id[]' value= "+id+" readonly>"
              );
          }
          
        });
        $(document).on('click', '#removeRow2', function() {
                  $(this).closest('#inputFormRow2').remove();
               });
        
        $(".rate").keyup(function(){
           var quantity=$(".quantity").val();
           var rate=$(this).val();
           var bill_amount = quantity * rate;
           $(".bill_amount").val(bill_amount.toFixed(2));
           //console.log("403",bill_amount);
           update_total_amount()
        });
        // Cash & Carry
        $(".brick_rate_cash").keyup(function(){
           var quantity_cash=$(".quantity_cash").val();
           var brick_rate_cash=$(this).val();
           var bill_amount_cash = quantity_cash * brick_rate_cash;
           $(".bill_amount_cash").val(bill_amount_cash.toFixed(2));
           update_total_amount_cash()
        });
        // Cash & Carry
        function update_total_amount_cash(){
            var sum=0;
            $('.bill_amount_cash').each(function() {
                sum = sum  + parseInt($(this).val());
                //console.log("430",sum);
            });
            $("#total_amount_cash").val(sum.toFixed(2));
        }
        //Credit
        function update_total_amount(){
            var sum=0;
            $('.bill_amount').each(function() {
                sum = sum  + parseInt($(this).val());
                //console.log("439",sum);
            });
            $("#total_amount").val(sum.toFixed(2));
        }
   
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