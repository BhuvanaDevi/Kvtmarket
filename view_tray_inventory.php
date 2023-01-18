<?php
$date = date("Y-m-d");

require "header.php";

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
        <div><h2>View Tray Inventory</h2></div>
        <div class="row">
        <div class="col-lg-12" >
             <div class="iq-card12"style="padding:0">
                <div class="iq-card-body p-0">
                   <div class="iq-edit-list">
                      <ul class="iq-edit-profile d-flex nav nav-pills">
                         <li class="col-md-2 p-0">
                            <a class="nav-link active" data-toggle="pill" href="#personal-information">
                               Customer
                            </a>
                         </li>
                         <li class="col-md-2 p-0">
                            <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                               Supplier
                            </a>
                         </li>
                         <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#add_delete">
                               Add & Delete Trays
                            </a>
                         </li>
                         <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#all">
                               Summary
                            </a>
                         </li>
                      </ul>
                   </div>
                </div>
             </div>
            </div>
        <div class="col-lg-12" style="margin-top:20px">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                 <center>
                                <div class="row">
                                 <div class="col" style="margin-bottom:20px;">
                                      
                                    <input type="date" value="<?= $date ?>" id="from" name="from" class="form-control">
                                 </div>
                                 <div class="col">
                                      
                                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                                 </div>
                                
                                 <div class="col">
                                 <select id="tray" class="form-control" name="tray">
     <option value="">Please Select Tray</option>
     <option value="Small Tray">Small Tray</option>
        <option value="Big Tray">Big Tray</option>
     </select>
                                 </div>
                           <div class="col">
                           <select class="form-control" id="group" name="group" style="width:210px;">
                                                        <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
        </div>
                        
                                 <div class="col" id="custom">
                                 <select class="form-control" id="customer" name="customer" >
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
                                 <div class="col">
             <select class="form-control" id="payment" name="payment" >
                      <option value="">Choose Balance Type</option>
                    <!-- <option value="Payment Details">Payment Details</option> -->
                    <option value="Whole Details">Whole Details</option>
                    </select>
                                 </div>
     <div class="col">

                                    <button type="button" id="submit" name="submit" class="btn btn-primary">Display</button>
                                    <!-- <button type="button" id="download" name="download" class="btn btn-danger">Download</button> -->
                                    <button type="button" name="download" id="download" class="btn btn-success">Download</button>
                                </div> 
                                <br>
                            </div>
                           
                            </center>
                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>SI#</th>
                                <th>Date & Time</th>
                             <!-- <th>User Name</th> -->
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <!--<th>Category</th>-->
                                <th>Inward</th>
                                <th>Outward</th>
                                <!-- <th>Inhand</th> -->
                                <!-- <th>Tray Payment</th> -->
                                <th>Big Tray</th>
                                <th>Small Tray</th>
                                <th>Ab Small Tray</th>
                                <th>Ab Big Tray</th>
                                <th>Inhand</th>
                                <!-- <th>Total Tray</th> -->
                            </tr>
                        </thead>
                    </table>
    </div>
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                           <center>
                                
                            <div class="row">
                                 <div class="col">
                                      
                                    <input type="date" value="<?= $date ?>" id="from_supplier" name="from_supplier" class="form-control">
                                 </div>
                                   
                                
                                 <div class="col">
                                      
                                    <input type="date" value="<?= $date ?>" id="to_supplier" name="to_supplier" class="form-control">
                                 </div>
                             
                            <div class="col">
                                 <select id="trays" class="form-control" name="trays">
     <option value="">Please Select Tray</option>
     <option value="Small Tray">Small Tray</option>
        <option value="Big Tray">Big Tray</option>
     </select>
                                 </div>
                                 <div class="col">
                              <select class="form-control" id="grpname" name="grpname" required>
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
                                 <div class="col" id="custom">
                                <select class="form-control" id="supplier" name="supplier">
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
                                 <div class="col">
             <select class="form-control" id="payment1" name="payment1" >
                      <option value="">Choose Balance Type</option>
                    <!-- <option value="Payment Details">Payment Details</option> -->
                    <option value="Whole Details">Whole Details</option>
                   
                    </select>
                                 </div>
                                 <div class="col">
                                    <button type="button" id="submit_supplier" name="submit_supplier" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_supplier" name="download_supplier" class="btn btn-danger">Download</button>
                                    </div>                        
                            </div>
                        </center>
           <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%;">

                <thead>

                    <tr>

                        <th>SI#</th>
                        <th>Date & Time</th>
                        <!-- <th>User Name</th> -->
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <!--<th>Category</th>-->
                        <th>Inward</th>
                        <th>Outward</th>
                        <!-- <th>Inhand</th> -->
                        <!-- <th>Tray Payment</th> -->
                        <th>Small Tray</th>
                        <th>Big Tray</th>
                        <!-- <th>Total Tray</th> -->
                        <th>Ab Small Tray</th>
                        <th>Ab Big Tray</th>
                        <th>Inhand</th>
                    </tr>

                </thead>

               

            </table>                     

    </div>
                        <div class="tab-pane fade" id="add_delete" role="tabpanel">
                                               <center>
                                                    
                                                <div class="row">
                                                     <div class="col">
                                                          
                                                        <input type="date" value="<?= $date ?>" id="from_trays" name="from_trays" class="form-control">
                                                     </div>
                                                       
                                                    
                                                     <div class="col">
                                                          
                                                        <input type="date" value="<?= $date ?>" id="to_trays" name="to_trays" class="form-control">
                                                     </div>
                                                    <div class="col">
                                                        <button type="button" id="submit_trays" name="submit_trays" class="btn btn-primary">Display</button>
                                                        <button type="button" id="download_trays" name="download_trays" class="btn btn-danger">Download</button>
                                                        </div>                        
                                                </div>
                                                
                                                </center>
                               <table id="example3" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    
                                    <thead>
                    
                                        <tr>
                    
                                            <th>SI#</th>
                                            <th>Date & Time</th>
                                            <th>User Name</th>
                                            <!--<th>Category</th>-->
                                            <th>Description</th>
                                            <th>Tray - Add</th>
                                            <th>Tray - Delete</th>
                                            <th>Tray Type</th>
                                            <th>AB Small</th>
                                            <th>AB Big</th>
                                            <th>AB Tray</th>
                                            
                                        </tr>
                    
                                    </thead>
                    
                                   
                    
                                </table>                     
                    
    </div>
                        <div class="tab-pane fade" id="all" role="tabpanel">
                           <div class="row">
                             
                            <!-- <div class="col">
                               <select class="form-control" id="dropdown" name="dropdown" style="width:200px;" >
                                   <option>--Choose Category--</option>
                                   <option value="Customer">Customer</option>
                                   <option value="Supplier">Supplier</option>
                      </select>
                    </div> -->
                  <div class="col">
                    
<select class="form-control" name="description" id="description">
<option value="--Choose Details--" selected disabled>--Choose --</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Customer">Customer</option>
                                        
</select>
                  </div>
                  <!-- <div class="col" id="supgrp">
                  <select class="form-control" id="sumsupgrp" name="sumsupgrp">
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
                  <div class="col" id="supgrpname">
                    <select class="form-control" id="dropdown" name="dropdown">
                      <option value="">Search Supplier Name </option>
                         </select>
                  </div> -->
     <!-- <div class="col" id="custgrp">
     <select class="form-control" id="cusgrp" name="cusgrp">
                                                        <option value="">--Choose Group Name--</option>
                                    <?php
                                        //     $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                        // 	$sel_sql= $connect->prepare($sel_qry);
                            	        //     $sel_sql->execute();
                            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	        //         echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                            	        //    }
                            	           ?>
                            	          
                            	           </select>
     </div> -->
                <!-- <div class="col" id="custgpname">
                                           <select class="form-control" id="dropdown_all" name="dropdown_all" >
                      <option value="">Choose Customer Name </option>
                        </select>
                                           </div> -->
                  <div class="col" style="text-align:center">
                               <button type="button" id="download_inv_all" name="download_inv_all" class="btn btn-primary">Display</button>
                                   <button type="button" id="download_all" name="download_all" class="btn btn-danger">Download</button>
                                </div>
                                
                           </div>
                    <br>
               <table id="example4" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                <thead>

                    <tr>
                        <th>SI#</th>
                        <!--<th>Date & Time</th>-->
                        <!--<th>User Name</th>-->
                        <th>Name</th>
                        <th>Category</th>
                        <!--<th>Inward</th>-->
                        <!--<th>Outward</th>-->
                        <th>Inhand</th>
                        <th>Big Tray</th>
                        <th>Small Tray</th>
                        </tr>
                </thead>
            </table>                     
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

<script>

//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){
      
        var user_role='<?=$user_role?>';
        var username='<?=$username?>';
       var table=$('#example').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_tray_inventory&username="+username+'&user_role='+user_role,

                "type": "POST",
            },

            "columns": [
                { "data": "rowIndex", "orderable" : false },
                { "data": "date", "orderable" : false },
                // { "data": "updated_by", "orderable" : false },
                { "data": "name", "orderable" : false },
                { "data": "description", "orderable" : false },
                { "data": "type", "orderable" : false },
               // { "data": "category", "orderable" : false },
                { "data": "inward", "orderable" : false },
                { "data": "outward", "orderable" : false },
                // { "data": "inhand", "orderable" : false },
                // { "data": "tray_pay", "orderable" e: false },
                { "data": "bigtray", "orderable" : false },
                { "data": "smalltray", "orderable" : false },
                // { "data": "ab_tray", "orderable" : false },
                { "data": "abstray", "orderable" : false },
                { "data": "abbtray", "orderable" : false },
                // { "data": "ab_tray", "orderable" : false },
                { "data": "inhand", "orderable" : false },
            ],

             "order": [[ 1, 'asc' ]]

        });

        $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            var tray=$("#tray").val();
            var customer=$("#customer").val();
             
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_tray_inventory&from="+from+'&to='+to+'&customer='+customer+'&tray='+tray).load();

                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_tray_inventory").load();
                table.ajax.reload();
            }

        });
        

    $("#download").on("click",function(){
        var from=$("#from").val();
            var to=$("#to").val();
            var tray=$("#tray").val();
            var customer=$("#customer").val();
            var tray=$("#tray").val();
            var payment=$("#payment").val();
        if(from!="" && to!="" && tray!="" && customer!=""){
            window.location.href="GetTrayInventory.php?from="+from+"&to="+to+"&customer="+customer+"&tray="+tray;
       }
    else if(from!="" && to!="" && payment=="Whole Details" && tray==""){
        window.location.href="wholepayment_tray.php?from="+from+"&to="+to+"&payment="+payment;
          }

          else if(from!="" && to!="" && payment=="Whole Details" && tray!=""){
        window.location.href="wholepayment_tray.php?from="+from+"&to="+to+"&payment="+payment+"&tray="+tray;
          }
    });

    });

</script>
<script>

//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){
        var user_role='<?=$user_role?>';
        var username='<?=$username?>';
       var table1=$('#example2').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_tray_inventory_supplier&username="+username+'&user_role='+user_role,

                "type": "POST",

            },

            "columns": [
                { "data": "rowIndex", "orderable" : false },
                { "data": "date", "orderable" : false },
       
                // { "data": "updated_by", "orderable" : false },

                { "data": "supplier_name", "orderable" : false },
                { "data": "description", "orderable" : false },
                { "data": "type", "orderable" : false },
         
                // { "data": "category", "orderable" : false },
                { "data": "inward", "orderable" : false },

                { "data": "outward", "orderable" : false },

                // { "data": "inhand", "orderable" : false },
                // { "data": "tray_pay", "orderable" : false },
                { "data": "smalltray", "orderable" : false },
                { "data": "bigtray", "orderable" : false },
             
                // { "data": "ab_tray", "orderable" : false },
                { "data": "abstray", "orderable" : false },
                { "data": "abbtray", "orderable" : false },
                { "data": "inhand", "orderable" : false },
      
             ],

             "order": [[ 1, 'asc' ]]

        });

        $("#submit_supplier").on("click",function(){

            var from_supplier=$("#from_supplier").val();

            var to_supplier=$("#to_supplier").val();

            var trays=$("#trays").val();
            var supplier=$("#supplier").val();

           
           
            if(from_supplier!="" && to_supplier!=""){

                table1.ajax.url("forms/ajax_request.php?action=view_tray_inventory_supplier&from="+from_supplier+'&to='+to_supplier+'&supplier='+supplier+'&trays='+trays).load();

                table1.ajax.reload();

            } else {

                table1.ajax.url("forms/ajax_request.php?action=view_tray_inventory_supplier").load();

                table1.ajax.reload();

            }

        });

    $("#download_supplier").on("click",function(){
            var from_supplier=$("#from_supplier").val();
            var to_supplier=$("#to_supplier").val();
            var tray_supplier=$("#trays").val();
            var supplier=$("#supplier").val();
            var payment1=$("#payment1").val();
          if(from_supplier!="" && to_supplier!="" && tray_supplier!="" && supplier!=""){
             window.location.href="GetTrayInventorySupplier.php?from="+from_supplier+"&to="+to_supplier+"&supplier="+supplier+"&trays="+tray_supplier;
          }
          else if(from_supplier!="" && to_supplier!="" && payment1=="Whole Details"){
            window.location.href="wholepayment_Supplier.php?from="+from_supplier+"&to="+to_supplier+"&payment="+payment1;
          }
            });

    });

</script>
<script>

//$.fn.dataTableExt.sErrMode = 'throw';
var user_role='<?=$user_role?>';
        var username='<?=$username?>';
    $(document).ready(function(){

       var table2=$('#example3').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_tray_inventory_trays&username="+username+'&user_role='+user_role,

                "type": "POST",
            },

            "columns": [
                { "data": "rowIndex", "orderable" : false },
                { "data": "date", "orderable" : false },
                { "data": "name", "orderable" : false },
                { "data": "description", "orderable" : false },
                
               // { "data": "category", "orderable" : false },
               { "data": "inward", "orderable" : false },
               { "data": "outward", "orderable" : false },
                { "data": "type", "orderable" : false },
                { "data": "absmall", "orderable" : false },
                { "data": "abbig", "orderable" : false },
                { "data": "ab_tray", "orderable" : false }
                
             ],

             "order": [[ 1, 'asc' ]]

        });

        $("#submit_trays").on("click",function(){
            var from=$("#from_trays").val();
            var to=$("#to_trays").val();
            if(from!="" && to!=""){
                table2.ajax.url("forms/ajax_request.php?action=view_tray_inventory_trays&from_trays="+from+'&to_trays='+to).load();

                table2.ajax.reload();
            } else {
                table2.ajax.url("forms/ajax_request.php?action=view_tray_inventory_trays").load();
                table2.ajax.reload();
            }

        });

    $("#download_trays").on("click",function(){
            var from=$("#from_trays").val();
            var to=$("#to_trays").val();
            window.location="download_tray_inventory_trays.php?from_trays="+from+'&to_trays='+to;
        });

    });

</script>
<script>
var user_role='<?=$user_role?>';
        var username='<?=$username?>';
//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){

       var table3=$('#example4').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_tray_inventory_all&username="+username+'&user_role='+user_role,
                "type": "POST",

            },

            "columns": [
                { "data": "rowIndex", "orderable" : false },
                { "data": "name", "orderable" : false },
                { "data": "category", "orderable" : false },
                { "data": "inhand", "orderable" : false },
                { "data": "bigtray", "orderable" : false },
                { "data": "smalltray", "orderable" : false }
             ],

             "order": [[ 1, 'asc' ]]

        });
        $("#download_inv_all").on("click",function(){
            // alert("hello");
            var dropdown=$("#description").val();
            // var dropdown_all=$("#dropdown_all").val();
            // alert(dropdown)
            console.log(dropdown)
        //    alert(dropdown); // var grp=$("#sumsupgrp").val();
            // var supplier=$("#supgrpname").val();
        
            if(dropdown!=""){
                table3.ajax.url("forms/ajax_request.php?action=view_tray_inventory_all&dropdown="+dropdown).load();
                table3.ajax.reload();
            }
            // else if(dropdown_all!="" && dropdown==""){
            //     table3.ajax.url("forms/ajax_request.php?action=view_tray_inventory_all&dropdown_all="+dropdown_all).load();
            //     table3.ajax.reload();
            // }
             else {
                table3.ajax.url("forms/ajax_request.php?action=view_tray_inventory_all").load();
                table3.ajax.reload();
            }
        });

    $("#download_all").on("click",function(){
        var dropdown=$("#description").val();
               // window.location="download_tray_inventory_summary.php?dropdown="+dropdown;
            window.location.href="download_tray_inventory_summary.php?dropdown="+dropdown;
        });

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
                    $("#dropdown_all").empty();
                    $("#dropdown_all").append('<option>Choose Customer Name</option>');
                    for(var i=0;i<len;i++){
                    $("#dropdown_all").append('<option value='+result[i].customer_name+'>'+result[i].customer_name+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});
</script>
<script>
//    $(document).ready(function(){
//     $("#supgrp").hide();
//     $("#dropdown").hide();
//     $("#cusgrp").hide();
//     $("#dropdown_all").hide();
    
//     $("#description").change(function(){
//         var invoice=$(this).find(":selected").val();
//     //    alert(invoice);
//    if(invoice=="Supplier"){
//     $("#supgrp").show();
//     $("#dropdown").show();
//     $("#cusgrp").hide();
//     $("#dropdown_all").hide();
// //    $("#custom").hide();
// //    $("#supplier").prop('required',true);

//  }
//    else if(invoice=="Customer"){
//     $("#cusgrp").show();
//     $("#dropdown_all").show();
//     $("#supgrp").hide();
//     $("#dropdown").hide();
//     // $("#custom").prop('required',true);
//  }
//     });
//     //$("#re").hide();
    
//     $("#supplier").hide();
//    // $("#supplier_name").text("Choose Supplier");
//     $("#custom").hide();
//    });
   
//    $("#sumsupgrp").on("change",function(){
//                        var grp=$(this).val();
//         // alert(grp);
//         $.ajax({
//                 type:"POST",
//                 url:"forms/ajax_request.php",
//                 data:{"action":"fetchgrp","grp":grp},
//                 dataType:"json",
//                 success:function(result){
//                     var len=result.length;
//                     // alert(result.length);
//                     $("#dropdown").empty();
//                     $("#dropdown").append('<option>Search Supplier Name</option>');
//                        for(var i=0;i<len;i++){
//                     $("#dropdown").append('<option value='+result[i].supplier_no+'>'+result[i].contact_person+'</option>');
//                                     }
//                                                     // alert(result.contact_person);
// 	   }
//     })
// });

// $("#cusgrp").on("change",function(){
//         var grp=$(this).val();
//         // alert(grp);
//         $.ajax({
//                 type:"POST",
//                 url:"forms/ajax_request.php",
//                 data:{"action":"fetchsup","grp":grp},
//                 dataType:"json",
//                 success:function(result){
//                     var len=result.length;
//                     // alert(result.length);
//                     $("#dropdown_all").empty();
//                     $("#dropdown_all").append('<option>Choose Customer Name</option>');
//                     for(var i=0;i<len;i++){
//                     $("#dropdown_all").append('<option value='+result[i].customer_no+'>'+result[i].customer_name+'</option>');
//                                     }
//                                                     // alert(result.contact_person);
// 	   }
//     })
// });

</script>
