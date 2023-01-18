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



// if($req=="enabled")
// {
//     $delete="UPDATE `tray_transactions` SET is_active=0 WHERE id='$id'";
//     $delete_sql= $connect->prepare($delete);
//     $delete_sql->execute();
//     header("location:view_tray_report.php");
// }

// if($req=="disabled")
// {
//     $delete="UPDATE `tray_transactions` SET is_active=1 WHERE id='$id'";
//     $delete_sql= $connect->prepare($delete);
//     $delete_sql->execute();
//     header("location:view_tray_report.php");
// }

 ?>

<div id="content-page" class="content-page">
   
    <div class="container-fluid">
       <div class="row">
          <div class="col-lg-12">
              <h2>View Tray Stock</h2>
             <div class="iq-card"style="padding:0">
                <div class="iq-card-body p-0">
                   <div class="iq-edit-list">
                      <ul class="iq-edit-profile d-flex nav nav-pills">
                         <li class="col-md-3 p-0">
                            <a class="nav-link active" data-toggle="pill" href="#personal-information">
                               Customer
                            </a>
                         </li>
                         <li class="col-md-3 p-0">
                            <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                               Supplier
                            </a>
                         </li>
                         
                      </ul>
                   </div>
                </div>
             </div>
            </div>
           
            <div class="col-lg-12">
                 
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                <center>
                                
                                 <tr>
                                    <td>From Date:</td>
                                    <td><input type="date" value="<?= $date ?>" id="from" name="from"></td>
                                 </tr>
                                <tr>
                                    <td>To Date:</td>
                                    <td><input type="date" value="<?= $date ?>" id="to" name="to"></td>
                                </tr>
                            <tr>
                                <td>
                                    <button type="button" id="submit" name="submit" class="btn btn-primary">Display</button>
                                </td>
                                <td>
                                    <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
                                </td>
                            </tr>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!--<tr>-->
                            <!--        <td align="left">-->
                            <!--            <label><input type="radio" name="view_customer_sts" class="view_customer_sts active" value="1" checked/>&nbsp;Active</label>-->
                            <!--        </td>-->
                            <!--        &nbsp;&nbsp;&nbsp;&nbsp;-->
                            <!--        <td align="right">-->
                            <!--            <label><input type="radio" name="view_customer_sts" class="view_customer_sts" value="0" />&nbsp;InActive</label>-->
                            <!--        </td>-->
                                    
                            <!--    </tr>-->
                            </center>
                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    
                                    <thead>
                    
                                        <tr>
                    
                                            <th>SI No</th>
                                            <th>Date</th>
                    
                                            <th>Held By</th>
                    
                                            <th>Category</th>
                    
                                            <th>No Of Trays</th>
                    
                                        </tr>
                    
                                    </thead>
                    
                                </table>
                        </div>
                <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                        <center>
                                
                                 <tr>
                                    <td>From Date:</td>
                                    <td><input type="date" value="<?= $date ?>" id="from_supplier" name="from_supplier"></td>
                                 </tr>
                                <tr>
                                    <td>To Date:</td>
                                    <td><input type="date" value="<?= $date ?>" id="to_supplier" name="to_supplier"></td>
                                </tr>
                            <tr>
                                <td>
                                    <button type="button" id="submit_supplier" name="submit_supplier" class="btn btn-primary">Display</button>
                                </td>
                                <td>
                                    <button type="button" id="download_supplier" name="download_supplier" class="btn btn-danger">Download</button>
                                </td>
                            </tr>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!--<tr>-->
                            <!--        <td align="left">-->
                            <!--            <label><input type="radio" name="view_sales_invoice_sts" class="view_sales_invoice_sts active" value="1" checked/>&nbsp;Active</label>-->
                            <!--        </td>-->
                            <!--        &nbsp;&nbsp;&nbsp;&nbsp;-->
                            <!--        <td align="right">-->
                            <!--            <label><input type="radio" name="view_sales_invoice_sts" class="view_sales_invoice_sts" value="0" />&nbsp;InActive</label>-->
                            <!--        </td>-->
                                    
                            <!--    </tr>-->
                            </center>
                       <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                            <thead>
            
                                <tr>
            
                                    <th>SI No</th>
                                    <th>Date</th>
                                    <th>Held By</th>
            
                                    <th>Category</th>
            
                                    <th>No Of Trays</th>
            
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
<div id="content-page" class="content-page">

            <div class="container-fluid">

               <div class="row">
                 <div class="col-lg-12">

                      <h3>Tray Summary</h3>
                    </div>

                    <div class="col-lg-12">

                        <div class="iq-edit-list-data">

                            <div class="tab-content">

                                <div class="tab-pane fade active show" id="personal-information" role="tabpanel">

                                   <table id="example1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                        <thead>
        
                            <tr>
        
                                <th>Total Trays</th>
        
                                <th>Available with Suppliers</th>
        
                                <th>Available with Customers</th>
        
                                <th>Inhand Trays</th>
        
                            </tr>
        
                        </thead>

                    </table>

                                </div>

                                <div class="tab-pane fade" id="chang-pwd" role="tabpanel">


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

                "url": "forms/ajax_request.php?action=view_tray_stocks&username="+username+'&user_role='+user_role,

                "type": "POST"

            },

            "columns": [

                { "data": "rowIndex", "orderable" : false },
                { "data": "date" },

                { "data": "name" },

                { "data": "category" },

                { "data": "balance" }
            ],
            "order": [[ 1, 'asc' ]]
        });
        
        // $(".view_customer_sts").on("click",function(){
        //     var is_active=$(this).val();
        //     if(is_active==1){
        //         table.ajax.url("forms/ajax_request.php?action=view_tray_stocks&req=enabled&is_active=1&username="+username+'&user_role='+user_role).load();
        //         table.ajax.reload();
        //     } else {
        //         table.ajax.url("forms/ajax_request.php?action=view_tray_stocks&req=disabled&is_active=0&username="+username+'&user_role='+user_role).load();
        //         table.ajax.reload();
        //     }
        // });
        $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_tray_stocks&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_tray_stocks").load();
                table.ajax.reload();
            }
        });
        
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_tray_stock_customer_report.php?from="+from+'&to='+to;
        });
    });
     
        

</script>
<script>

//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){

       var table1=$('#example2').DataTable({

            "processing": true,

            "serverSide": true,

            "responsive": true,

            "ajax": {

                "url": "forms/ajax_request.php?action=view_tray_stocks_supplier",

                "type": "POST"

            },

            "columns": [

                { "data": "rowIndex", "orderable" : false },
                
                { "data": "date" },
                { "data": "name" },

                { "data": "category" },

                { "data": "balance" }
            ],
            "order": [[ 1, 'asc' ]]
        });
        
        $("#submit_supplier").on("click",function(){
            var from_supplier=$("#from_supplier").val();
            var to_supplier=$("#to_supplier").val();
            if(from_supplier!="" && to_supplier!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_tray_stocks_supplier&from_supplier="+from_supplier+'&to_supplier='+to_supplier).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_tray_stocks_supplier").load();
                table1.ajax.reload();
            }
        });
        
        $("#download_supplier").on("click",function(){
            var from_supplier=$("#from_supplier").val();
            var to_supplier=$("#to_supplier").val();
            window.location="download_tray_stock_supplier_report.php?from_supplier="+from_supplier+'&to_supplier='+to_supplier;
        });
    });

</script>
<script>
//$.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       
    var table=$('#example1').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_tray_summary",
                "type": "POST"
            },
            "columns": [
              
                { "data": "total_tray" },
                { "data": "SUPPLIER" },
                { "data": "CUSTOMER" },
                { "data": "inhand_tray" }
              
            ],
            columnDefs: [
               
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.total_tray;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.SUPPLIER;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.CUSTOMER;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.inhand_tray;
                    }
                }
                
             ]
        });    
    });
</script>