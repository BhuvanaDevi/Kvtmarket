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
$customer_no = $_REQUEST["customer_no"];
$supplier_qry="SELECT * FROM `sar_customer` WHERE id='".$id."'";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute();
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);
$grp_cust_name=$supplier_row["grp_cust_name"];
$customer_no=$supplier_row["customer_no"];
$customer_name=$supplier_row["customer_name"];
$customer_contact=$supplier_row["contact_number1"];
$address=$supplier_row["address"];

if($req=="delete")
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
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Add Customer</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <p></p>
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
                        	          ?>      
                        	                <option value="<?=$sel_row["grp_cust_name"]?>" <?=($sel_row["grp_cust_name"]==$grp_cust_name)?'selected':""?>><?=$sel_row["grp_cust_name"]?></option>
                        	         <?php  }
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
                                    <label for="exampleInputText1">Customer Name </label><span
                                        style="color:red">*</span>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        value="<?=$customer_name?>" required>
                                    <span style="color:red;font-weight:bold;" id="customer_name_disp"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputText1">Contact Number </label><span
                                        style="color:red">*</span>
                                    <input type="text" class="form-control" id="contact_number1" name="contact_number1"
                                        value="<?=$customer_contact?>" maxlength="10" pattern="^[6-9]\d{9}$">
                                    <span style="color:red;font-weight:bold;" id="customer_mobile_disp"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputText1">Address </label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?=$address?>" placeholder="Enter Address" required>
                                </div>
                            </div> <button type="submit" name="submit" id="submit"
                                class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Add Group</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="exampleInputText1">Group Name</label><span style="color:red">*</span>
                                <input type="text" class="form-control" id="group_name" name="group_name" required>
                            </div>
                            <button type="submit" name="group_submit" id="group_submit"
                                class="btn btn-primary">Submit</button>
                        </form>

                    </div>

                </div>
            </div> -->

            <?php
if(isset($_POST["group_submit"])){
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
if(isset($_POST["submit"])){
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
            <!-- <div class="col-lg-6">
                 <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">View Customer Details</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                    <center>
                    <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
                </tr>
                </center>
                    &nbsp;&nbsp;
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th></th>
                        
                    </tr>
                </thead>
            </table>
        </div>
        </div>
        </div> -->
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
            }
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
                    return '<a href="#" class="mymodal" grp_cust_name="' + row.grp_cust_name +
                        '" ><i class="bx bx-comment-dots"></i>&nbsp;View</a>';
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
                $("#produ_details").html("");
                var i = 0;
                for (i = 0; i < result.length; i++) {
                    if (result[i].hasOwnProperty("grp_cust_name")) {
                        $('#produ_details').append('<tr>');
                        $("#produ_details").append('<td>' + result[i].grp_cust_name + '</td>');
                        $("#produ_details").append('<td>' + result[i].customer_no + '</td>');
                        $("#produ_details").append('<td>' + result[i].customer_name + '</td>')
                        $("#produ_details").append('<td>' + result[i].contact_number1 + '</td>');
                        $("#produ_details").append('<td>' + result[i].address + '</td>');
                        $("#produ_details").append(
                            '<td><a class="label label-success" href="add_customer.php?req=edit&id=' +
                            result[i].id + '"><span class="bx bxs-edit" ></span></a></td>');
                        $("#produ_details").append(
                            '<td><a class="label label-delete" href="add_customer.php?req=delete&id=' +
                            result[i].id + '"><span class="bx bxs-trash" ></span></a></td>');
                        $('#produ_details').append('</tr>');
                    }
                }
            }
        });
    }
});
</script>
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