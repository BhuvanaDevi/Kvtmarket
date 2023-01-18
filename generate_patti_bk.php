<?php
$date = date("Y-m-d");

require "header.php";
$submit_action = 'add_patti';
$patti_id = "";
$quality_box = "";
$mobile_number = "";
$quality_name_list = array();
$quality_name_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
$quality_name_qry_stmt = $connect->prepare($quality_name_qry);
$quality_name_qry_stmt->execute();
while ($sel_row = $quality_name_qry_stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($quality_name_list, $sel_row["quality_name"]);
}

if(isset($_REQUEST['req'])!="" && $_REQUEST["req"] == 'edit'){
    $submit_action = 'edit_patti';
    $patti_id = $_REQUEST["patti_id"];
    $patti_qry = "SELECT * FROM sar_patti WHERE patti_id = '".$patti_id."'";
    $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute();
    $cnt = 0;
	while ($data_row = $patti_sql->fetch(PDO::FETCH_ASSOC)) {
	   $selected = '';
	   if($cnt == 0){
	       $mobile_number = $data_row['mobile_number'];
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
	       if($value == $data_row['quality_name']){
	           $selected = 'selected';
	       }
	       $quality_box .= '<option '. $selected.'>'.$value.'</option>';
	   }
	   $quality_box .= '</select>
	        </div>
	        <div>
	            <label>Quantity</label>
	            <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'">
	        </div> 
	        <div class="form-group">
	            <label for="exampleInputNumber1">Rate</label>
	            <input type="number" class="form-control rate_arr" id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'">
	        </div>
	        <div>
	            <label>Total</label>
	            <input type="text" name="bill_amount[]" class="form-control boxTotal" id="total_amount' . $cnt . '" value="'.$data_row['bill_amount'].'">
	            <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'">
	        </div><br>
	        <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
	        </div>
	   </div>';
	   $cnt = $cnt + 1;
	}
} else {
    $req="";
    $patti_qry = "SELECT id FROM sar_patti ORDER BY id DESC LIMIT 1 ";
    $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute();
    $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id = $patti_row["id"] + 1;
    $patti_id = "PAT_" . date("Ym") . "0" . $Last_id;
}


if(isset($_REQUEST['id'])!=""){
    $id=$_REQUEST["id"];
} else {
    $id="";
}

?>

<form method="post" action="#" class="searchbox">
    <div id="content-page" class="content-page">
        <div class="container-fluid">
          <div class="row">
              <div class=" col-lg-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Generate Patti </h4>
                          </div>
                        </div>
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
                            <input type="text" class="text search-input generate_patti" maxlength="10" pattern="^[6-9]\d{9}$" id="searchval" name="searchval" placeholder="Type here to search...">
                         <a class="search-link" href="#"><i class="ri-search-line sales"></i></a>
                         &nbsp;
                         <button type="button" id="clear" name="clear" class="btn btn-danger ">Clear
                         </button>
                         <span style="color:red;font-weight:bold;" id="searchval_disp"></span>
                         <span style="color:green;font-weight:bold;" id="searchval_disp2"></span>
                         &nbsp;&nbsp;
                         
                         <div class="form-group">
                            <label for="exampleInputdate">Patti ID </label>

                            <input type="text" class="form-control" id="patti_id" name="patti_id" value="<?= $patti_id ?>" readonly>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                            <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="patti_date" required>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputNumber1">Mobile number</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="mobile_number" maxlength="10" pattern="^[6-9]\d{9}$" name="mobile_number" value="<?= $mobile_number ?>"required>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputText1">Supplier Name </label><span style="color:red">*</span>
                            <input type="text" class="form-control" name="supplier_name" id="supplier_name" required>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputText1">Address </label>
                            <input type="text" class="form-control" id="supplier_address" name="supplier_address">
                         </div>
                         <div class="form-group">
                            <label for="exampleInputNumber1">Boxes Arrived</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="exampleInputNumber1" readonly name="boxes_arrived" required>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputText1">Lorry No</label>
                            <input type="text" class="form-control" id="exampleInputText1" name="lorry_no" style="text-transform:uppercase;">
                         </div>
                        </div>
                  </div>
              </div>
              <div class=" col-sm-6">
                  <div class="iq-card">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Commission, Cooli & Lorry</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                         <div class="form-group">
                           <label for="exampleInputNumber1">Commission
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control commision" id="commision" name="commision" required>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputNumber1">Lorry Hire
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control lorry_hire" id="lorry_hire" name="lorry_hire" required>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputNumber1">Box Charges
            
            
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control box_charge" id="box_charge" name="box_charge" required>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputNumber1">Cooli (Hamali)
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control cooli" id="cooli" name="cooli" required>
                        </div>
                        <div class="form-group">
                           <label for="exampleInputNumber1"style="font-weight:700;">Total
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control total_deduction" id="total_deduction" name="total_deduction" onInput="edValueKeyPress()" required>
                        </div><br>
                        <span id="lblValue">The text box contains: </span>
                     </div>
                  </div>
              </div>
              <div class="container-fluid">
                  <div class="col-lg-12" style="background: var(--iq-card-bg);border-radius: 8px;box-shadow: 0px 2px 8px var(--iq-card-shadow-1), 0px 8px 16px var(--iq-card-shadow-2);">
                      <div class="row form-group" id="dynamic_field">
                          <?php echo $quality_box; ?>
                      
                      </div>
                      <div class="iq-card-body">
                          <a href="#dynamic_field"><button type="button" name="add" id="add" class="btn btn-success">Add Quality Box</button>
                          </a>
                          <div class="form-group" style="margin-top:10px;">
                           <label for="exampleInputNumber1" style="font-weight:700">Total Box Amount
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control commision" id="totalBoxAmount" name="totalBoxAmount">
                        </div>
                      </div>
                  </div>
              </div>
              <div class=" col-sm-6"style="margin-top:20px">
                  <div class="iq-card">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           
                        </div>
                     </div>
                     <div class="iq-card-body">
                         <div class="form-group">
                           <label for="exampleInputNumber1"style="font-weight:700;">Total bill amount
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control grandTotal" id="grandTotal" name="grandTotal" onInput="edValueKeyPress()" required>
                        </div><br>
                        <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
                     </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
</form>


<?php
require "footer.php";
if (isset($_POST["add_patti"])) {
   $patti_qry = "SELECT id FROM sar_patti ORDER BY id DESC LIMIT 1";
   $patti_sql = $connect->prepare("$patti_qry");
   $patti_sql->execute();
   $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
   $Last_id = $patti_row["id"] + 1;
   $patti_id = "PAT_" . date("Ym") . "0" . $Last_id;
    
   $count = count($_POST["quality_name"]);
   $patti_date = $_POST["patti_date"];
   $mobile_number = $_POST["mobile_number"];
   $supplier_name = $_POST["supplier_name"];
   $supplier_address = $_POST["supplier_address"];
   $boxes_arrived = $_POST["boxes_arrived"];
   $lorry_no = $_POST["lorry_no"];
   $commision = $_POST["commision"];
   $lorry_hire = $_POST["lorry_hire"];
   $box_charge = $_POST["box_charge"];
   $cooli = $_POST["cooli"];
   $total_deduction = $_POST["total_deduction"];
   
   $total_bill_amount = 0;
   $net_bill_amount = 0;
   
   
   
   for($i=0;$i<$count;$i++)
  { 
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
      $bill_amount=$quantity*$rate;
      $total_bill_amount += $bill_amount;
  }
   
   $net_bill_amount = $total_bill_amount - $total_deduction;
  
  for($i=0;$i<$count;$i++)
  {
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
      $bill_amount=$quantity*$rate;
      
     if($id == ""){
        $add_patti_query = "INSERT INTO `sar_patti` SET
            patti_id='$patti_id',
            patti_date='$patti_date',
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            total_bill_amount='$total_bill_amount',
            bill_amount='$bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            payment_status=1,
            is_active=1
            ";
            
        $add_patti_sql = mysqli_query($con, $add_patti_query);
    }
   }
  $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql=$connect->prepare("$balance_qry");
  $balance_sql->execute();
  $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
  $balance = $bal_row["inhand"] + $boxes_arrived;
  $tray_trans_qry = "INSERT INTO tray_transactions SET 
                  date = '$patti_date',
                  name = '$supplier_name',
                  category = 'Supplier',
                  inward = $boxes_arrived,
                  inhand = $balance,
                  description = 'Inward from Patti $patti_id'";
  $res2=mysqli_query($con, $tray_trans_qry);
  header('Location: ./GeneratPatti.php');
   
} else if(isset($_POST["edit_patti"])){
   $count = count($_POST["quality_name"]);
   $patti_date = $_POST["patti_date"];
   $mobile_number = $_POST["mobile_number"];
   $supplier_name = $_POST["supplier_name"];
   $supplier_address = $_POST["supplier_address"];
   $boxes_arrived = $_POST["boxes_arrived"];
   $lorry_no = $_POST["lorry_no"];
   $commision = $_POST["commision"];
   $lorry_hire = $_POST["lorry_hire"];
   $box_charge = $_POST["box_charge"];
   $cooli = $_POST["cooli"];
   $total_deduction = $_POST["total_deduction"];
   
   $total_bill_amount = 0;
   $net_bill_amount = 0;
   
   for($i=0;$i<$count;$i++)
   { 
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
      $bill_amount=$quantity*$rate;
      $total_bill_amount += $bill_amount;
   }
   
   $net_bill_amount = $total_bill_amount - $total_deduction;
  
  for($i=0;$i<$count;$i++)
  {
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
      $rec_id = $_POST["rec_id"][$i];
      $bill_amount=$quantity*$rate;
      
     if($rec_id == ""){
        $add_patti_query = "INSERT INTO `sar_patti` SET
            patti_id='$patti_id',
            patti_date='$patti_date',
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            total_bill_amount='$total_bill_amount',
            bill_amount='$bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            payment_status=1,
            is_active=1
            ";
            
        $add_patti_sql = mysqli_query($con, $add_patti_query);
    }else if($rec_id != ""){
        $add_patti_query = "UPDATE `sar_patti` SET
            patti_id='$patti_id',
            patti_date='$patti_date',
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            total_bill_amount='$total_bill_amount',
            bill_amount='$bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            payment_status=1,
            is_active=1 WHERE id =".$rec_id."
            ";
            
        $add_patti_sql = mysqli_query($con, $add_patti_query);
    }
    
   }
  $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
  $balance_sql=$connect->prepare("$balance_qry");
  $balance_sql->execute();
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
</script>
<script>
   $(document).ready(function() {
         function calculate_grantotal() {

            var totalBoxAmount = ( $("#totalBoxAmount").val() ) ? parseFloat($("#totalBoxAmount").val()) : 0;
            var total_deduction = ( $("#total_deduction").val() ) ? parseFloat($("#total_deduction").val()) : 0;
            $("#grandTotal").val(totalBoxAmount - total_deduction);
         }

            $("#rate").on("change", function() {
               var quantity = $("#quantity").val();
               var rate = $(this).val();
               var bill_amount = quantity * rate;
               //var total_bill_amount +=bill_amount;
               $("#bill_amount").val(bill_amount.toFixed(2));


            });

            $("#cooli,#commision,#lorry_hire,#box_charge").on("change", function() {
               var commision = ( $("#commision").val() ) ? parseFloat($("#commision").val()) : 0;
               var lorry_hire = ( $("#lorry_hire").val() ) ? parseFloat($("#lorry_hire").val()) : 0;
               var box_charge = ( $("#box_charge").val() ) ? parseFloat($("#box_charge").val()) : 0;
               var cooli = ( $("#cooli").val() ) ? parseFloat($("#cooli").val()) : 0;
               var total_deduction = commision + lorry_hire + box_charge + cooli;
               //document.getElementById("total_deduction").value=total_deduction;
               $("#total_deduction").val(total_deduction.toFixed(2));
               calculate_grantotal();
            });

            $(document).on("keypress", "#total_deduction", function (e) {
               var total_deduction = document.getElementById("total_deduction");
               var s = total_deduction.value;

               var lblValue = document.getElementById("lblValue");
               lblValue.innerText = "The text box contains: " + s;
            });
            
               var i = 1;
               $('#add').click(function() {
                  $('#dynamic_field').append('<div class="iq-card col-md-89 qualityBox" id="inputFormRow" style="margin:10px;">     <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body"> <div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="exampleFormControlSelect1" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group"><label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr" id="rate_arr' + i + '" myattr="' + i + '" name="rate[]"></div><div><label>Total</label><input type="text" name="bill_amount[]" class="form-control boxTotal" id="total_amount' + i + '"></div><br><button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div>');

                  $(".rate_arr, .qty").on('change', function() {
                     var id = $(this).attr("myattr");
                     var qty = $("#qty" + id).val();
                     var rate_arr = $("#rate_arr" + id).val();
                     var total = qty * rate_arr;
                     // alert*(total);
                     $("#total_amount" + id).val(total.toFixed(2));
                     var quaBoxTotal = 0;
                     $(".qualityBox").each(function(index){
                        var value = $(this).find(".boxTotal").val();
                        if (value) {
                           quaBoxTotal += parseFloat(value);
                        }
                     });
                     $("#totalBoxAmount").val(quaBoxTotal);
                     calculate_grantotal();
                  });
                  i++;
               });
               $(document).on('click', '#removeRow', function() {
                  $(this).closest('#inputFormRow').remove();
               });

               $(".sales").on("click", function() {
                  var searchval = $("#searchval").val();
                  $.ajax({
                     type: "POST",
                     url: "forms/ajax_request_view.php",
                     data: {
                        "action": "view_patti_search",
                        "searchval": searchval
                     },
                     dataType: "json",
                     success: function(result) {
                        if (result.status == 1) {
                           $("#searchval_disp2").html("Contact Number Found").fadeOut(5000);
                           $("#mobile_number").val(result.data.mobile_number).attr('readonly', true);
                           $("#supplier_name").val(result.data.supplier_name).attr('readonly', true);
                           $("#supplier_address").val(result.data.supplier_address).attr('readonly', true);
                        } else {
                           $("#searchval_disp").html("Contact Number Not Found").fadeOut(5000);
                        }
                     }

                  });



               });
               $("#clear").on("click", function() {
                  $("#searchval").val('');
                  $("#mobile_number").val('').attr('readonly', false);
                  $("#supplier_name").val('').attr('readonly', false);
                  $("#supplier_address").val('').attr('readonly', false);
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