<?php
require "header.php";
$date1 = date('Y-m-d'); 
   
if(isset($_REQUEST['req'])!=""){
    $req=$_REQUEST["req"];
} else {
    $req="";
}
$customer_name=$_REQUEST['customer_name'];

if(isset($_REQUEST['id'])!=""){
    $id=$_REQUEST["id"];
} else {
    $id="";
}

if($req=="delete")
{
    $delete="DELETE FROM quality WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header('Location: view_tray_inventory.php');
   
}
 ?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="iq-card-body iq-card1">
                <h4 class="card-title">Add Chit</h4>                    
            </div>               
            <div class="col-lg-12">
                <div class="iq-edit-list-data">                    
                    <div class="iq-card">                               
                        <div class="add-item-flex">
                            <div class="add-item-row">
                                <div class="row">
                                    <div class="container-fluid">                               
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">View Chit List</h4>
                                            </div>
                                        </div>
                                        <div class="iq-card-body card1">
                                            <div class="row col-md-12">
                                                <div class="col-md-6">
                                                    <button type="button" style="float: right;" id="download" name="download" class="btn btn-danger">Download</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="add" name="add" style="color: #fff;" class="btn btn-success mymodalQuality">Add Customer</button>
                                                </div>
                                            </div>
                                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Customer ID</th>
                                                        <th>Date</th>
                                                        <th>Customer Name</th>
                                                        <th>Total Amount</th>
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
        </div>
    </div>
</div>
<!-- Wrapper END -->
<!-- Footer -->
 <?php
require "footer.php";

if(isset($_POST["add_chit"]))
{
    $select_qry = "SELECT * FROM customer_table ORDER BY id DESC limit 1";
    $select_sql = $connect->prepare($select_qry);
    $select_sql->execute();
    $group_row=$select_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $customer_id = "CHIT_".date("Ym")."0".$Last_id;
    $customer_name = $_POST['customer_name'];
    $customer_date = $_POST['add_date'];
    $add_customer_sql = "INSERT INTO `customer_table` SET customer_name='$customer_name', date='$customer_date', customer_id='$customer_id'";
    $add_customer = $connect->prepare($add_customer_sql);
    if($add_customer->execute())
    {
    //    $result='<div class="alert alert-success">Success</div>';
       echo "<script>alert('Success')</script>";
    }
    
}

?>
<script>
// $.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
        var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "ajax": {
                "url": "forms/ajax_request.php?action=view_customer_table",
                "type": "POST"
            },
            "columns": [

                { "data": "customer_id" },
                { "data": "date" },
                { "data": "customer_name" },
                { "data": "total_amount" }
                
            ],
            columnDefs: [   
                {
                    targets: 0,
                    render: function(data, type, row) {
                        // return row.chitname;
                        return row.customer_id;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.date;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        // return row.chitamt;
                        return '<a name_val='+ row.customer_name + ' idd='+ row.customer_id+' class="mymodal">'+ row.customer_name + '</a>';
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.total_amount;
                    }
                }
                
             ],
             "order": [[ 1, 'asc' ]]
        });
            $("#download").on("click",function(){
            window.location="download_quality_list_report.php";
            });

            $('.mymodalQuality').on('click', function (){
                $( "#mymodal_quality" ).modal( "show" );
            });
            $('.close').on('click', function (){
                $( "#mymodal_quality" ).modal( "hide" );
            });
    $('#example tbody').on('click', '.mymodal', function() {
        var idd=$(this).attr('idd');
        var cus_name=$(this).attr('name_val'); 
        $('#title').html(cus_name);
        // alert(idd)
        $('#hidden_id').val(idd);
        $("#myModal").modal("show");
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_chit_modal",
                     "chit_id": idd
            },
            dataType: "json",
            success: function(result) {
                var i = 0;
                $('#datas').html("");
                    for (i = 0; i < result.length; i++) {
                        $('#datas').append('<tr>');
                        $('#datas').append('<td>' + result[i].id + '</td>');
                        $('#datas').append('<td>' + result[i].chitdate + '</td>');
                        $('#datas').append('<td>' + result[i].chitamt + '</td>');
                        $('#datas').append('</tr>');
                    }
                        
            }
        });
    });
    $(".close").click(function() {
        $("#myModal").modal("hide");
    });

   
    
    });
   
</script>

<div class="modal fade" id="mymodal_quality" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Customer</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form method="post">
                        <div class="form-group">
                            <label for="cpass">Customer Name</label>
                            
                                <input type="text" required class="form-control" name="customer_name">
                                <label for="cpass">Date</label>                           
                            <input type="date" required class="form-control" value="<?php echo $date1?>" name="add_date">
                        </div>
                        <input type="submit" name="add_chit" class="btn btn-primary mr-2" value="Submit">
                        <!--<button type="reset" class="btn iq-bg-danger">Cancel</button>-->
                        <div class="form-group">                                
                            <div class="col-sm-10 col-sm-offset-2">
                                <?php echo $result; ?>    
                            </div>
                        </div>             
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
                <h4 class="modal-title" style="color:#f55989;" id="title"></h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form method="post">
                        <input type="hidden" id='hidden_id' name='hidden_id'>
                        <input type="hidden" id='hidden_id' name='hidden_id'>
                        <div class="form-group">
                            <label for="cpass">Enter Amount</label>                                
                            <input type="text" required class="form-control" name="amt">
                            <label for="cpass">Date</label>                        
                            <input type="date" required class="form-control" value="<?php echo $date1 ?>" name="amt_date">
                        </div>
                        <input type="submit" name="add_amt" class="btn btn-primary mr-2" value="Submit">
                        <!--<button type="reset" class="btn iq-bg-danger">Cancel</button>-->
                        <div class="form-group">                                
                            <div class="col-sm-10 col-sm-offset-2">
                            <?php echo $result; ?>    
                        </div>
                        </div>
                    </form>
                    <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead> 
                        <tbody id="datas">
                        </tbody>    
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
if(isset($_POST['add_amt'])) {
    $hidden_id = $_POST['hidden_id']; 
    $add_amt=$_POST['amt'];
    $amt_date=$_POST['amt_date'];

    // $hidden_sql = "SELECT * FROM `chit` WHERE id = '$hidden_id'";
    // $hidden_sql_run = $connect->prepare($hidden_sql);
    // $hidden_sql_run->execute();
    // $hidden_group = $hidden_sql_run->fetch(PDO::FETCH_ASSOC);
    // $last_amt = $hidden_group["chitamt"]+$add_amt;

   
   
    // $update_query = "UPDATE `chit` SET chitamt = '$add_amt', updatedate = '$amt_date' WHERE id='$hidden_id'";
    $update_query = "INSERT INTO `chit` SET chitamt='$add_amt', chitdate='$amt_date', chitid='$hidden_id'";
    $update_query_run =$connect->prepare($update_query);
    $update_query_run->execute();
    header("location:add_chit.php");
}
?>