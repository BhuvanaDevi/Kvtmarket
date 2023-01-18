<?php
$date = date("Y-m-d");
require "header.php";
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
    
    if (isset($_REQUEST['wastage_id']) != "") {

    $wastage_id = $_REQUEST["wastage_id"];

} else {

    $wastage_id = "";

}


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
        <div><h2>View Wastage</h2></div>
       <div class="row">
          <div class="col-lg-12">
             <div style="padding:0">
                <div class="iq-card-body p-0">
                   
               
            <div class="col-lg-12">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">

     
                            
                        <div class="row">
  <div class="col" style="margin-bottom:20px;"><input type="date" value="<?= $date ?>" name="from" id="from" class="form-control"></div>
  <div class="col"><input type="date" value="<?= $date ?>" name="to" id="to" class="form-control"></div>
  <div class="col"><button type="button" id="submit" name="submit" class="btn btn-primary">Display</button>
                               </div>
  <div class="col">
    <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
    <button type="button" style="color:#fff;position:relative;left:15px" name="" class="btn btn-warning mymodalwastage">Add Wastage</button>
</div>
  
  </div>
</div>
      <!--<div class="table-responsive">-->
        <table id="example" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0" style="width:100%">
            <thead>
                <tr>
                    <th>SI No</th>
                    <th>Wastage ID</th>
                    <th>Date</th>
                    <th>Quality Name</th>
                    <th>Quantity</th>
                    <th>Total Quantity</th>
                  <th>User Name</th>  </tr>
            </thead>
        
        </table>
        <!--</div>-->
    </div>
    
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php
    require "footer.php";
?>
<script>
    
    
    $(document).ready(function(){
        var user_role='<?=$user_role?>';
        var username='<?=$username?>';
        var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_wastage",
                "type": "POST"
            },
            "columns": [
                { "data" : "rowIndex","orderable" :false },
                { "data": "wastage_id" },
                { "data": "quality_name" },
                { "data": "quantity" },
                { "data": "total_quantity" },
                { "data": "created_at" },
                { "data": "updated_by" }
                
                
            ],
            columnDefs: [
                {
                    targets:0,
                    render: function(data,type,row){
                        return row.rowIndex;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return '<a class="mymodal" wastage_id="' + row.wastage_id + '" >' + row.wastage_id + '</a>';
                    }
                },
                {
                    targets:2,
                    render: function(data,type,row){
                        return row.quality_name;
                    }
                },
                {
                    targets:3,
                    render: function(data,type,row){
                        return row.quantity;
                    }
                },
                {
                    targets:4,
                    render: function(data,type,row){
                        return row.total_quantity;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.created_at;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                }
               
             ],
              "order": [[ 1, 'asc' ]]

        });
          $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_wastage&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_wastage").load();
                table.ajax.reload();
            }
        });
        
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_wastage.php?from="+from+'&to='+to;
        });
        
    
        
        $('#example tbody').on('click', '.mymodal', function (){
            var wastage_id=$(this).attr("wastage_id");
            $( "#myModal" ).modal( "show" );
            $("#wastage_id").val(wastage_id);
            $.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_wastage_modal","wastage_id":wastage_id},
                dataType:"json",
                success:function(result){
                    
                        //alert(result.data)
                    
                        $("#produ_details").html("");
                        var i=0;
                        for(i=0;i<result.length;i++)
                        {
                            
                            $('#produ_details').append('<tr>');
                           $('#produ_details').append('<td>'+result[i].quality_name+'</td>');
                          $('#produ_details').append('<td>'+result[i].quantity+'</td>');
                        
                            //  $("#product_type").html("").append(result.data.finished_product_type);
                             
                            //   $('#product_type').html("").append("<td>").append(result.data.finished_product_type);
                          $('#produ_details').append('</tr>');
                        }
                         $("#wastage_id").html(result[i-1].wastage_id);
                        $("#created_at").html(result[i-1].created_at);
                        $("#updated_by").html(result[i-1].updated_by);
                          $('#produ_details').append('<tr>');
                          
                            $('#produ_details').append('<td><b>Total Quantity</b></td>');
                            
                            $('#produ_details').append('<td>'+result[i-1].total_quantity+'</td>');
                            //  $("#product_type").html("").append(result.data.finished_product_type);
                             
                            //   $('#product_type').html("").append("<td>").append(result.data.finished_product_type);
                          $('#produ_details').append('</tr>');
                        // var amount_req=result.data.quantity*result.data.raw_material_rate;

                        
                        // $("#bill_amount").val(amount_req);
                        // $("#total_amount").val(total_amount);
                        // $("#gst").html("").append(result.data.gst_no);
                        // $("#vehicle_number").html("").append(result.data.vehicle_no)
                    }
                
            });
         });
         
        

         $( ".close" ).click(function() {
            $( "#myModal" ).modal( "hide" );
        });
      
      
        $('.mymodalwastage').on('click', function (){
    $( "#mymodal_wastage" ).modal( "show" );
});

 $('.close').on('click', function (){
    $( "#mymodal_wastage" ).modal( "hide" );
});

 
    });
        
</script>

<div class="modal fade" id="mymodal_wastage" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Wastage</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                    <form id="form1" method="post" action="#" class="searchbox">
                           <div>
                               <h2>Wastage</h2>
                           </div>
                          
                                  <div class="form-group">
                                         <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="created_at" required>
                                      </div>
                                      
                                     
                                      
                              
                                     <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field">
                                          <?php echo $quality_box; ?>
                                      </div>
                                      </div>
                                   </div>
                                 <div class="row col-md-12"> 
                                 <div class="form-group col-md-8">
                                         <label for="exampleInputNumber1">Total Wastage</label><span style="color:red">*</span>
                                         <input type="text" readonly class="form-control" id="total_bill_amount" name="total_quantity"  value="<?=$total_bill_amount?>">
                                      </div>                                   <div class="form-group col-md-4">
                                   <a href="#dynamic_field"><button type="button" name="add" id="add" style="position:relative;top:38px" class="btn btn-success">Add More</button></a>
</div>
     
                                      </div>  
                                      <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
                 
</form>
                                </div>
                
                    <div class="modal-footer">

                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Wastage Details</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Wastage ID</th>
                                    <td id="wastage_id" name="wastage_id"></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td id="created_at" name="created_at"></td>
                                </tr>
                                <tr>
                                    <th>User Name</th>
                                    <td id="updated_by" name="updated_by"></td>
                                </tr>
                               
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Quality Name</th>
                                                    <th>Quantity</th>
                                                    <!--<th>Total Quantity</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="produ_details">
    
    
    
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
    
                        </table>
                     
                    </div>
                
                
                    
                
                
                <div class="modal-footer">

                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>

                </div>

            </div>

        </div>

    </div>

</div>

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
<?php
$sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
$sel_sql= $connect->prepare($sel_qry);
$sel_sql->execute();?>

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
          
           $('#dynamic_field').append('<div class="iq-card col-md-12 row" id="inputFormRow" style="margin:10px;"><div class="form-group col-md-4"><input list="qualities" class="form-control" name="quality_name[]" id="quality_name[]"><datalist id="qualities" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></datalist></div><div class="form-group col-md-4"><input type="text" class="form-control qty sum_qty" onkeyup="total_qty();"  name="quantity[]" id="qty'+i+'"></div><div class="form-group col-md-4"><button id="removeRow" type="button" class=" btn btn-danger">Remove</button></div></div>');  
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
           $('#dynamic_field2').append('<div class="iq-card col-md-89" id="inputFormRow2" style="margin:10px;"> <div class="iq-card-header d-flex justify-content-between"><div class="iq-header-title"><h4 class="card-title">Payment</h4></div></div><div class="iq-card-body">        <div class="form-group"><label for="exampleFormControlSelect1">Select Quality</label><select class="form-control" id="quality_name_cash" name="quality_name_cash[]"><option value="">--Choose Quality--</option><?php while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){ echo '<option>'.$sel_row["quality_name"].'</option>'; } ?></select></div><div><label>Quantity</label><input type="text" class="form-control qty_cash" name="quantity_cash[]" id="qty_cash'+i+'"></div> <div class="form-group">                             <label for="exampleInputNumber1">Rate</label><input type="number" class="form-control rate_arr_cash" id="rate_arr_cash'+i+'" myattr1="'+i+'"name="rate_cash[]"></div><div class="addition"><div><label>Total</label><input type="text" name="bill_amount_cash[]" id="bill_amount_cash'+i+'" class="form-control bill_amount_cash"></div></div><br><button id="removeRow2" type="button" class="btn btn-danger">Remove</button></div></div></div></div><br>');  
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
