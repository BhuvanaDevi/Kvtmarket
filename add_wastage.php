<?php require "header.php";

$date = date("Y-m-d");

$submit_action = 'add_wastage';
$sales_no = "";
$quality_box = "";
$mobile_number = "";
$customer_name = "";
$customer_address = "";
$boxes_arrived = "";
$total_bill_amount = "";
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
	       $total_bill_amount = $data_row['total_bill_amount'];
	       
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
                   
                   <div class="iq-edit-list-data">
                    <div class="tab-content">
                   <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                       <form id="form1" method="post" action="#" class="searchbox">
                           <div>
                               <h2>Wastage</h2>
                           </div>
                          
                                  <div class="form-group">
                                         <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="created_at" required>
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
                                         <label for="exampleInputNumber1">Total Wastage</label><span style="color:red">*</span>
                                         <input type="text" readonly class="form-control" id="total_bill_amount" name="total_quantity"  value="<?=$total_bill_amount?>">
                                      </div>
                                       <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
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
if(isset($_POST["add_wastage"]))
{
    print_r($_POST);
    $sales_qry="SELECT id FROM sar_wastage ORDER BY id DESC LIMIT 1 ";
    $sales_sql=$connect->prepare("$sales_qry");
    $sales_sql->execute();
    $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$sales_row["id"]+1;
    $wastage_id = "WAS_".date("Ym")."0".$Last_id;
    
    $count=count($_POST["quality_name"]);
    $created_at=$_POST["created_at"];
    $updated_at=Date('Y-m-d'); 
    $total_bill_amount = $_POST["total_quantity"];
    
  
      
     
    for($i=0;$i<$count;$i++)
    {
        $quality_name=$_POST["quality_name"][$i];
        $quantity=$_POST["quantity"][$i];
        
        
      if($id==""){
            $add_sales_query="INSERT INTO `sar_wastage` SET
            created_at='$created_at',
            wastage_id='$wastage_id',
           updated_at=$updated_at,
            quality_name='$quality_name',
            quantity='$quantity',
            total_quantity='$total_bill_amount',
            updated_by='$username'
           
            ";
            $res=mysqli_query($con,$add_sales_query);
            echo $add_sales_query;
      }
    }
    

}

?>
<script>
function total_qty(){
var sum_qty = 0;
   $('.sum_qty').each(function() {
      sum_qty += Number($(this).val());
    }); 
 $('#total_bill_amount').val(sum_qty);
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

<?php $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC "; 	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
                            ?>
                            <script>
    $(document).ready(function(){
        //  $("#rate").keyup(function(){
        //   var quantity=$("#quantity").val();
        //   var rate=$(this).val();
        //   var bill_amount = quantity * rate;
        //   $("#bill_amount").val(bill_amount.toFixed(2));
        // });
    
        var i=<?=$cnt?>;  
      $('#add').click(function(){  
          // i++;  
          
           $('#dynamic_field').append('<div class="iq-card col-md-89" id="inputFormRow" style="margin:10px;"><div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body"><div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="exampleFormControlSelect1" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty sum_qty" onkeyup="total_qty();"  name="quantity[]" id="qty'+i+'"></div><br><button id="removeRow" type="button" class=" btn btn-danger">Remove</button></div></div></div></div><br>');  
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

                            $('#dynamic_field2').append('<div class="iq-card col-md-89" id="inputFormRow2" style="margin:10px;"> <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body"><div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="quality_name_cash" name="quality_name_cash[]"><option value="">--Choose Quality--</option><?php while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){echo '<option>'.$sel_row["quality_name"].'</option>'; } ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty_cash" name="quantity_cash[]" id="qty_cash'+i+'"></div> <div class="form-group"><label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr_cash" id="rate_arr_cash'+i+'" myattr1="'+i+'"name="rate_cash[]"></div><div class="addition"><div><label>Total</label><input type="text" name="bill_amount_cash[]" id="bill_amount_cash'+i+'" class="form-control bill_amount_cash"></div></div><br><button id="removeRow2" type="button" class="btn btn-danger">Remove</button></div></div></div></div><br>');  
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
        
        // $(".rate").keyup(function(){
        //   var quantity=$(".quantity").val();
        //   var rate=$(this).val();
        //   var bill_amount = quantity * rate;
        //   $(".bill_amount").val(bill_amount.toFixed(2));
        //   //console.log("403",bill_amount);
        //   update_total_amount()
        // });
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
        // function update_total_amount(){
        //     var sum=0;
        //     $('.bill_amount').each(function() {
        //         sum = sum  + parseInt($(this).val());
        //         //console.log("439",sum);
        //     });
        //     $("#totalamt").val(sum.toFixed(2));
        // }
   
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