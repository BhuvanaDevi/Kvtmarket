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
        <div><h2>View Payment Wise Report</h2></div>
        <div class="row">
        <div class="col-lg-12" >
             <div class="iq-card12"style="padding:0">
                <div class="iq-card-body p-0">
                   <div class="iq-edit-list">
                      <ul class="iq-edit-profile d-flex nav nav-pills">
                         <li class="col-md-6 p-0">
                            <a class="nav-link active" data-toggle="pill" href="#personal-information">
                               Customer
                            </a>
                         </li>
                         <li class="col-md-6 p-0">
                            <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                               Supplier
                            </a>
                         </li>
                         <!-- <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#add_delete">
                               Add & Delete Trays
                            </a>
                         </li>
                         <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#all">
                               Summary
                            </a>
                         </li> -->
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
                                
                                 <!-- <div class="col">
                                 <select id="tray" class="form-control" name="tray">
     <option value="">Please Select Tray</option>
     <option value="Small Tray">Small Tray</option>
        <option value="Big Tray">Big Tray</option>
     </select>
                                 </div> -->
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
                                    //     $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    // 	$sel_sql= $connect->prepare($sel_qry);
                        	        //     $sel_sql->execute();
                        	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	        //         echo '<option value="'.$sel_row["customer_id"].'">'.$sel_row["customer_name"].'</option>';
                        	        //    }
                        	           ?>
                    </select>
          
                                 </div>
                                 <div class="col">
                                 <select class="form-control" id="payment" name="payment" >
                      <option value="">Choose Balance Type</option>
                    <option value="Payment Details">Payment Details</option>
                    <!-- <option value="Paymenttray">Payment - Tray</option> -->
                    <option value="Balance Only">Balance Only</option>
                    <!-- <option value="Balancetray">Balance - Tray</option> -->
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
                        <th>SI No</th>
                            <th>Customer</th>
                            <!-- <th>Payment Id</th> -->
                            <th>Payment Date</th>
                            <!--<th>Category</th>-->
                            <!-- <th>Payment Mode</th> -->
                            <th>Pay</th>
                            <!-- <th>Total Bill Amount</th> -->
                            <th>Balance</th>
                            <th>Big Tray</th>
                            <th>Small Tray</th>
                            <!-- <th>Tray</th> -->
                            <!--<th>Balance</th>-->
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
                            <!-- <div class="col">
                                 <select id="trays" class="form-control" name="trays">
     <option value="">Please Select Tray</option>
     <option value="Small Tray">Small Tray</option>
        <option value="Big Tray">Big Tray</option>
     </select>
                                 </div> -->
                              
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
                      <!-- <option value="">Choose Balance Type</option>-->
                    <option value="Payment Details">Payment Details</option>
                    <!-- <option value="Paymenttrays">Payment - Tray</option> -->
                    <option value="Balance Only">Balance Only</option>
                    <!-- <option value="Balancetrays">Balance - Tray</option> -->
                    <!-- <option value="Whole Details">Whole Details</option> -->
                   
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
                            <th>SI No</th>
                            <th>Supplier Name</th>
                            <!-- <th>Payment Id</th> -->
                            <th>Payment Date</th>
                            <!--<th>Category</th>-->
                            <!-- <th>Payment Mode</th> -->
                            <!-- <th>Total</th> -->
                               <th>Pay</th>
                         <th>Balance</th>
                            <th>Big Tray</th>
                           <th>Small Tray</th>
                            <!-- <th>Tray</th> -->
                            <!--<th>Balance</th>-->
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

var table=$('#example').DataTable({

    "processing": true,

"serverSide": true,

"responsive": true,

"ajax": {

    "url": "forms/ajax_request.php?action=view_payment_report_customer",

    "type": "POST",

},

"columns": [
        { "data": "rowIndex", "orderable" : false },
        { "data": "name", "orderable" : false },
        { "data": "date", "orderable" : false },
        { "data": "given", "orderable" : false },
        { "data": "total", "orderable" : false },
        // { "data": "bal", "orderable" : false },
        { "data": "bigtray", "orderable" : false },
        { "data": "smalltray", "orderable" : false },
    
],

columnDefs: [
    {
        targets: 0,
        render: function(data, type, row) {
            return row.rowIndex;
        }
    },
    {
        targets: 1,
        render: function(data, type, row) {
            return row.name;
        }
    },  
    // {
    //     targets: 2,
    //     render: function(data, type, row) {
    //         return row.payment_id;
    //     }
    // },
    {
        targets: 2,
        render: function(data, type, row) {
            return row.date;
        }
    },

    {
        targets: 3,
        render: function(data, type, row) {
            return row.given;
        }
    },
    {
        targets: 4,
        render: function(data, type, row) {
            return row.total;
        }
    },    // {
    //     targets: 5,
    //     render: function(data, type, row) {
    //         return row.bal;    
    //     }
    // },
    {
        targets: 5,
        render: function(data, type, row) {
            return row.bigtray;
        }
    },
    {
        targets: 6,
        render: function(data, type, row) {
            return row.smalltray;    
        }
    },
    // {
    //     targets: 8,
    //     render: function(data, type, row) {
    //         return row.discount;    
    //     }
    // },
    // {
    //     targets: 9,
    //     render: function(data, type, row) {
    //        return row.discount_type;    
    //     }
    // },
    // {
    //     targets: 10,
    //     render: function(data, type, row) {
    //        return row.tray;    
    //     }
    // }

    // {
    //     targets: 5
    //     render: function(data, type, row) {
    //         return row.balance;
    //     }
    // }
    
 ],
 "order": [[ 1, 'asc' ]]
});


  $("#submit").on("click",function(){
     var from=$("#from").val();
     var to=$("#to").val();
     var customer=$("#customer").val();
     var payment=$("#payment").val();
     if(from!="" && to!=""){
         table.ajax.url("forms/ajax_request.php?action=view_payment_report_customer&from="+from+'&to='+to+'&customer='+customer+'&payment='+payment).load();
         table.ajax.reload();
     } else {
         table.ajax.url("forms/ajax_request.php?action=view_payment_report_customer").load();
         table.ajax.reload();
     }
 });
 $("#download").on("click",function(){
     var from=$("#from").val();
     var to=$("#to").val();
     var customer=$("#customer").val();
     var payment=$("#payment").val();
    
     if(from!="" && to!="" && payment=="" && customer!=""){
     window.location="Paymentwise_Report.php?from="+from+'&to='+to+'&customer='+customer+'&payment='+payment;
     }
     else if(from!="" && payment=="Payment Details"){
     window.location="datewisecustomer.php?from="+from+'&to='+to+'&payment='+payment+'&customer='+customer;
 }
     else if(from!="" && to!="" && payment=="Balance Only"){
     window.location="Paymentwisereport_customer.php?from="+from+'&to='+to+'&payment='+payment+'&customer='+customer;
 }
        else if(from!="" && to!="" && payment=="Whole Details"){
        window.location="wholepayment_customer.php?from="+from+'&to='+to+'&payment='+payment+'&customer='+customer;
     }
     else if(from!="" && to!="" && payment=="Paymenttray"){
        window.location="Paymenttray.php?from="+from+'&to='+to+'&payment='+payment+'&customer='+customer;
     }
     else if(from!="" && to!="" && payment=="Balancetray"){
        window.location="Balancetray.php?from="+from+'&to='+to+'&payment='+payment+'&customer='+customer;
     }
     
 });



});

</script>
<script>
$(document).ready(function(){

var table=$('#example2').DataTable({

     "processing": true,

     "serverSide": true,

     "responsive": true,

     "ajax": {

         "url": "forms/ajax_request.php?action=view_payment_report",

         "type": "POST",

     },

     "columns": [
             { "data": "rowIndex", "orderable" : false },
             { "data": "supplier_name", "orderable" : false },
          //   { "data": "supplier_id", "orderable" : false },
       //      { "data": "payment_id", "orderable" : false },
             { "data": "payment_date", "orderable" : false },

           //   { "data": "category", "orderable" : false },
           //  { "data": "payment_mode", "orderable" : false },
           { "data": "total", "orderable" : false },
            // { "data": "bal", "orderable" : false },
            { "data": "given", "orderable" : false },
             { "data": "bigtray", "orderable" : false },
        { "data": "smalltray", "orderable" : false },
       //     { "data": "discount", "orderable" : false },
          //   { "data": "discount_type", "orderable" : false },
        
            // { "data": "credit", "orderable" : false }

             // { "data": "balance", "orderable" : false }

     ],

     columnDefs: [
         {
             targets: 0,
             render: function(data, type, row) {
                 return row.rowIndex;
             }
         },
         {
             targets: 1,
             render: function(data, type, row) {
                 return row.supplier_name;
             }
         },  
        //  {
        //      targets: 2,
        //      render: function(data, type, row) {
        //          return row.payment_id;
        //      }
        //  },
         {
             targets: 2,
             render: function(data, type, row) {
                 return row.payment_date;
             }
         },
        //  {
        //      targets: 3,
        //      render: function(data, type, row) {
        //          return row.payment_mode;
        //      }
        //  },
        {
             targets: 3,
             render: function(data, type, row) {
                 return row.given;    
             }
         }, 
         {
             targets: 4,
             render: function(data, type, row) {
                 return row.total;    
             }
         }, 
        //  {
        //      targets: 5,
        //      render: function(data, type, row) {
        //          return row.bal;    
        //      }
        //  },
       
         {
             targets: 5,
             render: function(data, type, row) {
                 return row.bigtray;
             }
         },
         {
             targets: 6,
             render: function(data, type, row) {
                 return row.smalltray;    
             }
         },
        //  {
        //      targets: 8,
        //      render: function(data, type, row) {
        //          return row.discount;    
        //      }
        //  },
        //  {
        //      targets: 9,
        //      render: function(data, type, row) {
        //         return row.discount_type;    
        //      }
        //  },
        //  {
        //      targets: 10,
        //      render: function(data, type, row) {
        //         return row.tray;    
        //      }
        //  }

         // {
         //     targets: 5
         //     render: function(data, type, row) {
         //         return row.balance;
         //     }
         // }
         
      ],
      "order": [[ 1, 'asc' ]]
 });


  $("#submit_supplier").on("click",function(){
     var from=$("#from_supplier").val();
     var to=$("#to_supplier").val();
     var supplier=$("#supplier").val();
     var payment1=$("#payment1").val();
     if(from!="" && to!=""){
         table.ajax.url("forms/ajax_request.php?action=view_payment_report&from="+from+'&to='+to+'&supplier='+supplier).load();
         table.ajax.reload();
     }
    //  else  if(from!="" && to!="" && supplier!="" && payment1!=""){
    //      table.ajax.url("forms/ajax_request.php?action=view_payment_report&from="+from+'&to='+to+'&supplier='+supplier+'&payment'+payment1).load();
    //      table.ajax.reload();
    //  }
      else {
         table.ajax.url("forms/ajax_request.php?action=view_payment_report").load();
         table.ajax.reload();
     }
 });
 $("#download_supplier").on("click",function(){
    var from=$("#from_supplier").val();
     var to=$("#to_supplier").val();
     var supplier=$("#supplier").val();
     var payment1=$("#payment1").val();
     if(from!="" && to!="" && payment1=="" && supplier!=""){
        window.location="Paymentwisereport_Supplier.php?from="+from+'&to='+to+'&supplier='+supplier;
     }
     else if(from!="" && payment1=="Payment Details"){
         window.location="datewisesupplier.php?from="+from+'&to='+to+'&payment='+payment1+'&supplier='+supplier;
 }
     else if(from!="" && to!="" && payment1=="Balance Only"){
        window.location="Paymentwisereport_balance.php?from="+from+'&to='+to+'&payment='+payment1+'&supplier='+supplier;
     }
     else if(from!="" && to!="" && payment1=="Paymenttrays"){
        window.location="Paymenttray_supplier.php?from="+from+'&to='+to+'&payment='+payment1+'&supplier='+supplier;
     }
     else if(from!="" && to!="" && payment1=="Balancetrays"){
        window.location="Balancetray_supplier.php?from="+from+'&to='+to+'&payment='+payment1+'&supplier='+supplier;
     }


    //  else if(from!="" && to!="" && payment1!="Whole Details"){
    //     window.location="wholepayment_supplier.php?from="+from+'&to='+to+'&payment='+payment1+'&supplier='+supplier;
    //  }
 });



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
</script>
