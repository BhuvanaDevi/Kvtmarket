<?php
require "header.php";
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


if($req=="delete")
{
    $delete="delete from sar_group where id='$id'";
  $exe=mysqli_query($con,$delete);

    header("location:add_customer.php");
}

if($req=="deletes")
{
    $delete="delete from sar_group_customer where id='$id'";
  $exe=mysqli_query($con,$delete);

    header("location:add_customer.php");
}

$supplier_no = $_REQUEST["supplier_no"];
$supplier_qry="SELECT * FROM `sar_supplier` WHERE id='".$id."'";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute();
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);
$supplier_no=$supplier_row["supplier_no"];
$supplier_name=$supplier_row["contact_person"];
$supplier_contact=$supplier_row["contact_number1"];
$Address=$supplier_row["Address"];

if($req=="deletesup")
{
    $delete="DELETE FROM sar_supplier WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_customer.php");
}

$customer_no = $_REQUEST["customer_no"];
$supplier_qry="SELECT * FROM `sar_customer` WHERE id='".$id."'";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute();
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);
$customer_no=$supplier_row["customer_no"];
$customer_name=$supplier_row["customer_name"];
$customer_contact=$supplier_row["contact_number1"];
$address=$supplier_row["address"];

if($req=="deletecus")
{
    $delete="DELETE FROM sar_customer WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_customer.php");
}
 ?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-card12" style="padding:0">
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-4 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#supplier">
                                        SUPPLIER
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#farmer">
                                        FARMER
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#customer">
                                        CUSTOMER
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
                        <div class="tab-pane fade active show" id="supplier" role="tabpanel">
                            <div class="row">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">View Supplier Details</h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body">-
                                        <center>
                                            <button type="button" id="download" name="download"
                                                class="btn btn-danger">Download</button>
                                            <button type="button" class="btn btn-success mymodalsupgrp">Add
                                                Group</button>
                                            <button type="button" class="btn btn-warning mymodalsupplier">Add
                                                Supplier</button>
                                        </center>
                                        &nbsp;&nbsp;
                                        <table id="example_sup"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Group Name</th>
                                                    <th>Supplier View</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="farmer" role="tabpanel">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">View Farmer Details</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <center>
                                        <button type="button" class="btn btn-warning mymodalfarmer">Add Farmer</button>
                                    </center>
                                    &nbsp;&nbsp;
                                    <table id="example_farmer"
                                        class="table table-striped table-bordered dt-responsive nowrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Farmer No</th>
                                                <th>Name</th>
                                                <th>Mobile Number</th>
                                                <th>Address</th>

                                            </tr>
                                        </thead>

                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="customer" role="tabpanel">

                            <div class="iq-card">
                                <div class="col-lg-12">
                                    <div class="iq-card">
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">View Customer Details</h4>
                                            </div>
                                        </div>
                                        <div class="iq-card-body">
                                            <center>
                                                <button type="button" id="download" name="download"
                                                    class="btn btn-danger">Download</button>
                                                <button type="button" class="btn btn-warning mymodalcustomergrp">Add
                                                    Group</button>
                                                <button type="button" class="btn btn-success mymodalcustomer">Add
                                                    Customer</button>
                                                </tr>
                                            </center>
                                            &nbsp;&nbsp;
                                            <table id="example"
                                                class="table table-striped table-bordered dt-responsive nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Customer View</th>
                                                        <th>Action</th>
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



<?php
if(isset($_POST["customer_group_submit"])){
    $group_qry="SELECT id FROM sar_group_customer ORDER BY id DESC LIMIT 1 ";
    $group_sql=$connect->prepare("$group_qry");
    $group_sql->execute();
    $group_row=$group_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $grp_cust_id = "GRPC_".date("Ym")."0".$Last_id;
   
    $grp_cust_name = $_POST["group_name"];
    
  $grp_qry = "INSERT INTO `sar_group_customer` SET 
                grp_cust_id='$grp_cust_id',
                grp_cust_name='$grp_cust_name'
                ";
                
        $grp_sql= $connect->prepare($grp_qry);
        $grp_sql->execute();

    header("location:add_customer.php");
}
if(isset($_POST["add_customer"])){
    $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
    $customer_sql=$connect->prepare($customer_qry);
    $customer_sql->execute();
    $customer_row=$customer_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$customer_row["id"]+1;
    $customer_no = "CUS_".date("Ym")."0".$Last_id;
    
    $grp_cust_id = $_POST["grp_cust_no"];
    $grp_cust_name = $_POST["grp_cust_name"];
    $customer_name = $_POST["customer_name"];
    $contact_number1 = $_POST["contact_number1"];
    $address = $_POST["address"];
    
    $cust_var="SELECT * FROM sar_customer WHERE customer_name='".$customer_name."'";
    $cust_query = $connect->prepare($cust_var);
    $customer_array = $cust_query ->execute();
    $customer_count=$cust_query->rowCount();
    
    if($customer_count==0){
       
        $query_1 = "INSERT INTO `sar_customer` SET 
                    grp_cust_id='$grp_cust_id',
                    grp_cust_name='$grp_cust_name',
                    customer_no='$customer_no',
                    customer_name='$customer_name',
                    contact_number1='$contact_number1',
                    address='$address',
                    created_by='$date'
                    ";
                
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
      
    }
     else if($count !=0 && $customer_count!=0) {
       echo "<script type='text/javascript'>alert('Lead added successfully');location='add_customer.php';</script>";
    }
    
    else {
        
            $query_1 = "UPDATE `sar_customer` SET 
                        customer_name='$customer_name',
                        contact_number1='$contact_number1',
                        address='$address'
                         WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
    } 
    header("location:add_customer.php");
}

         ?>
<div class="col-lg-6">

</div>
</div>
</div>
</div>

<?php require "footer.php" ?>
<script>
$(document).ready(function() {
    var table = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "forms/ajax_request.php?action=view_customer",
            "type": "POST"
        },
        "columns": [{
                "data": "grp_cust_name"
            },
            {
                "data": "id"
            },
            // { "data": "id" },
            {
                "data": "no"
            }
            //    { "data": "countcus" },
        ],
        columnDefs: [{
                targets: 0,
                render: function(data, type, row) {
                    return row.grp_cust_name;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    if (row.no == 0) {
                        return "-";
                        }
                else{
                    return '<a href="#" class="mymodal" grp_cust_name="' + row.grp_cust_name +
                        '" ><i class="bx bx-comment-dots"></i>&nbsp;View</a>';
                  
                }
            }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    if (row.no == 0) {
                        return '<a href="add_customer.php?req=delete&id=' + row.id +
                            '" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>';
                    } else {
                        return "-";
                    }
                }
            }
        ]
    });
    $("#contact_number1").on("change", function() {
        var contact_number1 = $(this).val();
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_contact_customer",
                "contact_number1": contact_number1
            },
            dataType: "json",
            success: function(result) {
                if (result.status == 1) {
                    if (result.msg == "alreadyexist") {
                        $("#customer_mobile_disp").html("Mobile No Already Exists");
                    } else {
                        $("#customer_mobile_disp").html("");
                    }
                }
            }
        });
    });
    $("#customer_name").on("change", function() {
        var customer_name = $(this).val();
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_name_customer",
                "customer_name": customer_name
            },
            dataType: "json",
            success: function(result) {
                if (result.status == 1) {
                    if (result.msg == "alreadyexist") {
                        $("#customer_name_disp").html("Customer Already Exists");
                    } else {
                        $("#customer_name_disp").html("");
                    }
                }
            }
        });
    });
    $("#download").on("click", function() {

        window.location = "download_customer_report.php";
    });
    $('#example tbody').on('click', '.mymodal', function() {
        var grp_cust_name = $(this).attr("grp_cust_name");
        $("#myModal").modal("show");
        $("#grp_cust_name").val(grp_cust_name);
        update_model_data(grp_cust_name)
    });

    function update_model_data(grp_cust_name) {

        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_grp_customer_modal",
                "grp_cust_name": grp_cust_name
            },
            dataType: "json",
            success: function(result) {
                $("#product_details").html("");
                var i = 0;
                for (i = 0; i < result.length; i++) {
                    if (result[i].hasOwnProperty("grp_cust_name")) {
                        $('#product_details').append('<tr>');
                        $("#product_details").append('<td>' + result[i].grp_cust_name + '</td>');
                        $("#product_details").append('<td>' + result[i].customer_no + '</td>');
                        $("#product_details").append('<td>' + result[i].customer_name + '</td>')
                        $("#product_details").append('<td>' + result[i].contact_number1 + '</td>');
                        $("#product_details").append('<td>' + result[i].address + '</td>');
                        $("#product_details").append(
                            '<td><a class="label label-success" href="edit_customer.php?req=edit&id=' +
                            result[i].id + '"><span class="bx bxs-edit" ></span></a></td>');
                        $("#product_details").append(
                            '<td><a class="label label-delete" href="edit_customer.php?req=delete&id=' +
                            result[i].id + '"><span class="bx bxs-trash" ></span></a></td>');
                        $('#product_details').append('</tr>');
                    }
                }
            }
        });
    }
});
$(document).ready(function() {

    $('.mymodalsupplier').on('click', function() {
        $("#mymodal_supplier").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_supplier").modal("hide");
    });

    $('.mymodalfarmer').on('click', function() {
        $("#mymodal_farmer").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_farmer").modal("hide");
    });

    $('.mymodalsupgrp').on('click', function() {
        $("#mymodal_sup_grp").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_sup_grp").modal("hide");
    });
    $('.mymodalsupgrpview').on('click', function() {
        $("#mymodal_sup_grp_view").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_sup_grp_view").modal("hide");
    });
    $('.mymodalcustomer').on('click', function() {
        $("#mymodal_customer").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_customer").modal("hide");
    });
    $('.mymodalcustomergrp').on('click', function() {
        $("#mymodal_customer_grp").modal("show");
    });

    $('.close').on('click', function() {
        $("#mymodal_customer_grp").modal("hide");
    });
});

$(document).ready(function() {
    var table = $('#example_sup').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "forms/ajax_request.php?action=view_supplier&req=enabled",
            "type": "POST"
        },
        "columns": [{
                "data": "group_name"
            },
            {
                "data": "id"
            },
            {
                "data": "no"
            }
        ],
        columnDefs: [{
                targets: 0,
                render: function(data, type, row) {
                    return row.group_name;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    if (row.no == 0) {
                        return "-";                     }
                    else{

return '<a href="#" class="mymodalsupgrpview" group_name="' + row
                        .group_name + '" ><i class="bx bx-comment-dots"></i>&nbsp;View</a>';
                
                    } 
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    if (row.no == 0) {
                        return '<a href="add_customer.php?req=deletes&id=' + row.id +
                            '" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>';
                    } else {
                        return "-";
                    }
                }
            }
        ]
    });
    $('#example_sup tbody').on('click', '.mymodalsupgrpview', function() {
        var group_name = $(this).attr("group_name");
        $("#mymodal_sup_grp_view").modal("show");
        $("#group_name").val(group_name);
        //$("#supplier_id").val(patti_id);
        update_model_data(group_name, 'unsettled')
    });
    //  $(".view_supplier_sts").on("click",function(){
    //     var is_active=$(this).val();
    //     if(is_active==1){
    //         table.ajax.url("forms/ajax_request.php?action=view_supplier&req=enabled&is_active=1").load();
    //         table.ajax.reload();
    //     } else {
    //         table.ajax.url("forms/ajax_request.php?action=view_supplier&req=disabled&is_active=0").load();
    //         table.ajax.reload();
    //     }
    // });
    function update_model_data(group_name, data_src) {
        if (data_src == 'settled') {
            $('#payment_form').hide();
        } else {
            $('#payment_form').show();
        }
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_supplier_modal",
                "group_name": group_name,
                "data_src": data_src
            },
            dataType: "json",
            success: function(result) {
                $("#produ_details").html("");
                var i = 0;
                $('#sar_patti_payment_table').html("");
                $('#revoke_table').html("");
                var sum_totalamount = 0;
                for (i = 0; i < result.length; i++) {
                    if (result[i].hasOwnProperty("group_name")) {
                        sum_totalamount += parseFloat(result[i].bill_amount);
                        $('#produ_details').append('<tr>');

                        // $("#produ_details").append('<td>'+result[i].group_no+'</td>');
                        $("#produ_details").append('<td>' + result[i].group_name + '</td>');
                        $("#produ_details").append('<td>' + result[i].supplier_no + '</td>');
                        $("#produ_details").append('<td>' + result[i].contact_person + '</td>')
                        $("#produ_details").append('<td>' + result[i].contact_number1 + '</td>');
                        $("#produ_details").append('<td>' + result[i].Address + '</td>');
                        $("#produ_details").append(
                            '<td><a class="label label-success" href="add_supplier.php?req=edit&id=' +
                            result[i].id + '"><span class="bx bxs-edit" ></span></a></td>');
                        $("#produ_details").append(
                            '<td><a class="label label-delete" href="add_supplier.php?req=delete&id=' +
                            result[i].id + '"><span class="bx bxs-trash" ></span></a></td>');
                        $('#produ_details').append('</tr>');
                    }

                }

            }
        });
    }
    $("#contact_number1").on("change", function() {
        var contact_number1 = $(this).val();
        //alert(employee_mobile)
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_contact_supplier",
                "contact_number1": contact_number1
            },
            dataType: "json",
            success: function(result) {
                if (result.status == 1) {
                    if (result.msg == "alreadyexist") {
                        $("#employee_mobile_disp").html("Mobile Number Already Exists");
                    } else {
                        $("#employee_mobile_disp").html("");
                    }
                }
            }
        });
    });
    $("#contact_person").on("change", function() {
        var contact_person = $(this).val();
        //alert(employee_mobile)
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_name_supplier",
                "contact_person": contact_person
            },
            dataType: "json",
            success: function(result) {
                if (result.status == 1) {
                    if (result.msg == "alreadyexist") {
                        $("#supplier_name_disp").html("Supplier Already Exists");
                    } else {
                        $("#supplier_name_disp").html("");
                    }
                }
            }
        });
    });
    $("#download").on("click", function() {

        window.location = "download_supplier_report.php";
    });

});

$(document).ready(function() {
    var table = $('#example_farmer').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "forms/ajax_request.php?action=view_farmer",
            "type": "POST"
        },
        "columns": [{
                "data": "farmer_no"
            },
            {
                "data": "farmer_name"
            },
            {
                "data": "farmer_contact_number"
            },
            {
                "data": "Address"
            },

        ],
        columnDefs: [

            {
                targets: 0,
                render: function(data, type, row) {
                    return row.farmer_no;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    return row.farmer_name;
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    return row.farmer_contact_number;
                }
            },
            {
                targets: 3,
                render: function(data, type, row) {
                    return row.Address;
                }
            }

        ]
    });

});

function checkDelete() {
    return confirm('Are you sure you want to delete?');
}
</script>
<div class="modal fade" id="mymodal_supplier" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Supplier</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form method="POST" action="">
                        <div class="iq-card-body">
                            <div class="row col-md-12 ">
                                <div class="form-group col-md-6">
                                    <input type="hidden" class="form-control" id="grp_no" name="group_id">
                                    <label for="exampleFormControlSelect1">Group Name</label><span
                                        style="color:red">*</span>
                                    <select class="form-control grp_name" id="grp_name" name="grp_name" required>
                                        <option value="">--Choose Group Name--</option>
                                        <?php
                                        $sel_qry = "SELECT distinct group_name from `sar_group` order by group_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option>'.$sel_row["group_name"].'</option>';
                        	           }
                        	           ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">

                                    <label for="exampleInputText1">Supplier ID </label><span style="color:red">*</span>
                                    <?php
                                                             $supplier_qry="SELECT id FROM sar_supplier ORDER BY id DESC LIMIT 1 ";
                                                             $supplier_sql=$connect->prepare("$supplier_qry");
                                                             $supplier_sql->execute();
                                                             $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
                                                             $Last_id=$supplier_row1["id"]+1;
                                                             $supplier_id = "SUP_".date("Ym")."0".$Last_id;
                                                          if($supplier_no!=""){
                                                      echo '<input type="text" class="form-control" id="supplier_no" name="supplier_no" value="'.$supplier_no.'" readonly>';
                                                          }
                                                          else
                                                          {
                                                             echo '<input type="text" class="form-control" id="supplier_no" name="supplier_no" value="'.$supplier_id.'" readonly>'; 
                                                          }
                                                          ?>
                                </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputText1">Supplier Name </label><span
                                        style="color:red">*</span>
                                    <input type="text" class="form-control" id="contact_person" required
                                        name="contact_person" value="<?=$supplier_name?>" required>
                                    <span style="color:red;font-weight:bold;" id="supplier_name_disp"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputText1">Contact Number </label><span
                                        style="color:red">*</span>
                                    <input type="text" class="form-control" required id="contact_number1"
                                        name="contact_number1" maxlength="10" pattern="^[6-9]\d{9}$"
                                        placeholder="Enter Mobile Number" value="<?=$supplier_contact?>">
                                    <span style="color:red;font-weight:bold;" id="employee_mobile_disp"></span>
                                </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputText1">Address </label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?=$Address?>" placeholder="Enter Address">
                                </div>
                            </div>
                            <button style="position: relative;left:15px" type="submit" name="add_supplier"
                                id="add_supplier" class="btn btn-primary">Submit</button>
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
<div class="modal fade" id="mymodal_sup_grp_view" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Suppliers</h4>
                <button type="button" class="close" data-dismiss="modal">&times</button>

            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <!--<td>Group No</td>-->
                            <td><b>Group Name</b></td>
                            <td><b>Supplier Id</b></td>
                            <td><b>Supplier Name</b></td>
                            <td><b>Mobile Number</b></td>
                            <td><b>Address</b></td>
                            <td><b>Edit</b></td>
                            <td><b>Delete</b></td>
                        </tr>
                    </thead>
                    <tbody id="produ_details">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



<div class="modal fade" id="mymodal_sup_grp" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Supplier</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                </div>
                <form method="POST" action="">
                    <div class="row col-md-12">
                        <div class="form-group col-md-12">
                            <label for="exampleInputText1">Group Name</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="group_name" name="group_name" required>
                        </div>
                        <br />
                        <center> <button type="submit" name="group_submit" id="group_submit"
                                class="btn btn-primary">Submit</button>
                        </center>
                        <br /><br /><br />
                    </div>
                </form>

                <div class="modal-footer">

                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mymodal_farmer" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Farmer</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form method="POST" action="">

                        <?php
                               $exp_qry="SELECT id FROM sar_farmer ORDER BY id DESC LIMIT 1 ";
                               $exp_sql=$connect->prepare("$exp_qry");
                               $exp_sql->execute();
                               $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
                               $Last_id=$exp_row["id"]+1;
                               $exp_no = "FAR_".date("Ym")."0".$Last_id;
                                 ?>
                        <input type="text" class="form-control" id="farmer_no" name="farmer_no" value="<?=$exp_no?>"
                            readonly>

                        <div class="form-group">
                            <label for="exampleInputdate">Name </label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="farmer_name" name="farmer_name" value=""
                                required>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputText1">Mobile Number </label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="farmer_contact_number"
                                name="farmer_contact_number" required maxlength="10" pattern="^[6-9]\d{9}$" value="">

                        </div>
                        <div class="form-group">
                            <label for="exampleInputText1">Address </label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="address" name="Address" value="" required>
                        </div>

                        <button type="submit" name="add_farmer" id="add_farmer" class="btn btn-primary">Submit</button>
                    </form>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mymodal_customer_grp" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Group</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="exampleInputText1">Group Name</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="group_name" name="group_name" required>
                        </div>
                        <button type="submit" name="customer_group_submit" id="customer_group_submit"
                            class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mymodal_customer" role="dialog">
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

                    <form method="POST" action="">
                        <input type="hidden" class="form-control" id="grp_cust_no" name="grp_cust_no">
                        <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlSelect1">Group Name</label><span
                                    style="color:red">*</span>
                                <select class="form-control grp_cust_name" id="grp_cust_name" name="grp_cust_name"
                                    required>
                                    <option value="">--Choose Group Name--</option>
                                    <?php
                                        $sel_qry = "SELECT distinct grp_cust_name from `sar_group_customer` order by grp_cust_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                        	           }
                        	           ?>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputdate">Customer ID </label>
                                <?php
                                  $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
                                $customer_sql=$connect->prepare("$customer_qry");
                                $customer_sql->execute();
                                $customer_row=$customer_sql->fetch(PDO::FETCH_ASSOC);
                                $Last_id=$customer_row["id"]+1;
                                $customer_id = "CUS_".date("Ym")."0".$Last_id;
                                  
                                 if($customer_no!=""){
                             echo '<input type="text" class="form-control" id="customer_no" name="customer_no" value="'.$customer_no.'" readonly>';
                                 }
                                 else
                                 {
                                    echo '<input type="text" class="form-control" id="customer_no" name="customer_no" value="'.$customer_id.'" readonly>'; 
                                 }
                                 ?>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputText1">Customer Name </label><span style="color:red">*</span>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    value="<?=$customer_name?>" required>
                                <span style="color:red;font-weight:bold;" id="customer_name_disp"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputText1">Contact Number </label><span style="color:red">*</span>
                                <input type="text" class="form-control" id="contact_number1" name="contact_number1"
                                    required value="<?=$customer_contact?>" maxlength="10" pattern="^[6-9]\d{9}$">
                                <span style="color:red;font-weight:bold;" id="customer_mobile_disp"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputText1">Address </label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?=$address?>" placeholder="Enter Address" required>
                            </div>
                        </div>
                        <button style="position: relative;left:20px;" type="submit" name="add_customer"
                            id="add_customer" class="btn btn-primary">Submit</button>
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
            <div class="modal-header">
                <h4 class="modal-title">Customer</h4>
                <button type="button" class="close" data-dismiss="modal">&times</button>

            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><b>Group Name</b></td>
                            <td><b>Customer ID</b></td>
                            <td><b>Customer Name</b></td>
                            <td><b>Mobile Number</b></td>
                            <td><b>Address</b></td>
                            <td><b>Edit</b></td>
                            <td><b>Delete</b></td>
                        </tr>
                    </thead>
                    <tbody id="product_details">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<?php
  if(isset($_POST["add_supplier"])){
    $supplier_qry="SELECT id FROM sar_supplier ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $supplier_no = "SUP_".date("Ym")."0".$Last_id;
    
    $grp_no = $_POST["grp_no"];
    $grp_name = $_POST["grp_name"];
    $contact_number1 = $_POST["contact_number1"];
    $contact_person = ucwords($_POST["contact_person"]);
    $contact_number1 = $_POST["contact_number1"];
    $address = $_POST["address"];
    $svar="SELECT * FROM sar_supplier WHERE contact_number1='".$contact_number1."' ";
    $query = $connect->prepare($svar);
    $user_array = $query ->execute();
    $count=$query->rowCount();
    
     $cust_var="SELECT * FROM sar_supplier WHERE contact_person='".$contact_person."' ";
    $cust_query = $connect->prepare($cust_var);
    $customer_array = $cust_query ->execute();
    $customer_count=$cust_query->rowCount();
    
  
  if($id==""){
     
  
  $query_1 = "INSERT INTO `sar_supplier` SET 
                group_id='$group_no',
                group_name='$grp_name',
                supplier_no='$supplier_no',
                contact_person='$contact_person',
                contact_number1='$contact_number1',
                address='$address',
                created_by='$date'
                ";
                
                
        
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
        
}
else if($id==""){
    
    
    $query_1 = "UPDATE `sar_supplier` SET 
                contact_person='$supplier_name',
                contact_number1='$mobile_number',
                address='$supplier_address'
                ";
}
else if($query_1->errno === 1062) {
    echo "Exist";
}
else {
        
            $query_1 = "UPDATE `sar_supplier` SET 
                        contact_person='$contact_person',
                        contact_number1='$contact_number1',
                        address='$address'
                        WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    }    
    header("location:add_customer.php");
}
if(isset($_POST["group_submit"])){
    $group_qry="SELECT id FROM sar_group ORDER BY id DESC LIMIT 1 ";
    $group_sql=$connect->prepare("$group_qry");
    $group_sql->execute();
    $group_row=$group_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $group_no = "GRP_".date("Ym")."0".$Last_id;
   
  $group_name = $_POST["group_name"];
  $contact_number1 = $_POST["contact_number1"];
  $address = $_POST["address"];
 
      $grp_qry = "INSERT INTO `sar_group` SET 
                    group_no='$group_no',
                    group_name='$group_name'
                    ";
                    
            $grp_sql= $connect->prepare($grp_qry);
            $grp_sql->execute();
        header("location:add_customer.php");
}


$message='';
if(isset($_POST["add_farmer"])){
 
  $exp_qry="SELECT id FROM sar_farmer ORDER BY id DESC LIMIT 1 ";
  $exp_sql=$connect->prepare("$exp_qry");
  $exp_sql->execute();
  $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
  $Last_id=$exp_row["id"]+1;
  $exp_no = "FAR_".date("Ym")."0".$Last_id;
   $farmer_no = $_POST["farmer_no"];
  $farmer_name = ucwords($_POST["farmer_name"]);
  $farmer_contact_number= $_POST["farmer_contact_number"];
  $address = $_POST["Address"];
  
  $select_name="select farmer_name from sar_farmer where farmer_name='".$farmer_name."'";
    $select_sql=mysqli_query($con,$select_name);
    $select_row=mysqli_fetch_assoc($select_sql);
    $select_mobile="select farmer_name from sar_farmer where farmer_contact_number='".$farmer_contact_number."'";
    $select_sql_mobile=mysqli_query($con,$select_mobile);
    $select_row_mobile=mysqli_fetch_assoc($select_sql_mobile);
    if(mysqli_num_rows($select_sql)>=1  )
    {
        echo'<script>';
        echo'alert("Farmer Already Exists")';
        echo '</script>';
    }
     else if( mysqli_num_rows($select_sql_mobile)>=1)
    {
        echo'<script>';
        echo'alert("Farmer Mobile number Already Exists")';
        echo '</script>';
    }
   
    else
    {
         if($id==""){
  $query_1 = "INSERT INTO `sar_farmer`(farmer_no,farmer_name,farmer_contact_number,Address)values('$farmer_no','$farmer_name',
                '$farmer_contact_number','$address')";
                
                
        
        $sql_1= mysqli_query($con,$query_1);
       

  } 
    }
 
 
        
}

if(isset($_POST["group_submit"])){
    $group_qry="SELECT id FROM sar_group ORDER BY id DESC LIMIT 1 ";
    $group_sql=$connect->prepare("$group_qry");
    $group_sql->execute();
    $group_row=$group_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $group_no = "GRP_".date("Ym")."0".$Last_id;
   
  $group_name = $_POST["group_name"];
  $contact_number1 = $_POST["contact_number1"];
  $address = $_POST["address"];
 
      $grp_qry = "INSERT INTO `sar_group` SET 
                    group_no='$group_no',
                    group_name='$group_name'
                    ";
                    
            $grp_sql= $connect->prepare($grp_qry);
            $grp_sql->execute();
        header("location:add_customer.php");
}
?>