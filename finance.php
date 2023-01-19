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

if(isset($_REQUEST['exp_no'])!=""){
    $exp_no=$_REQUEST["exp_no"];
} else {
    $exp_no="";
}
if($req=="delete")
{
    $delete="DELETE FROM sar_finance WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    
    
    // $delete_fin_qry="DELETE FROM financial_transactions WHERE exp_id='$exp_no'";
    // $delete_fin_sql= $connect->prepare($delete_fin_qry);
    // $delete_fin_sql->execute();
    header("location:finance.php");
    
    
}
if(isset($_POST['add_payment'])) {
    // print_r($_POST);die();
    
    $exp_qry="SELECT id FROM sar_finance_payment ORDER BY id DESC LIMIT 1 ";
    $exp_sql=$connect->prepare($exp_qry);
    $exp_sql->execute();
    $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$exp_row["id"]+1;
    $payment_id = "PAY_".date("Ym")."0".$Last_id;

    

    $payment_date = $_POST['payment_date'];
    
    $amount = $_POST["amount"];
    
    $payment_mode = $_POST["payment_mode"];
    // echo $popup_finance_id; die();
    $popup_finance_id = $_POST["finance_id"];
    
    $select_qry5 = "SELECT amount FROM sar_finance WHERE finance_id='$popup_finance_id' GROUP BY finance_id";

    $sel_sql5 = $connect->prepare($select_qry5);
    $sel_sql5->execute();
    $sel_row5 = $sel_sql5->fetch(PDO::FETCH_ASSOC);
    $select_qry6 = "SELECT sum(amount) as paid FROM sar_finance_payment WHERE finance_id='$popup_finance_id' AND is_revoked is NULL GROUP BY finance_id";
    $select_sql6 = $connect->prepare($select_qry6);
    $select_sql6->execute();
    $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);

    $balance = $sel_row5["amount"] - $amount - $sel_row6["paid"];

    if ($balance >= 0) {
         $insert = "INSERT INTO `sar_finance_payment` 
                    SET 
                        payment_id='$payment_id',
                        amount='$amount',
                        payment_date='$payment_date',
                        payment_mode='$payment_mode',
                        finance_id='$popup_finance_id',
                        balance='$balance'
                        ";
                    $sql_1 = $connect->prepare($insert);
                    $sql_1->execute();
                    // echo $insert; die();
        // echo $insert;exit;
        $lastInsertId = $connect->lastInsertId();
        
        if($balance== 0){
            $insert = "UPDATE `sar_finance` SET payment_status= 1 WHERE finance_id='".$popup_finance_id."'";
            $sql_1 = $connect->prepare($insert);
            $sql_1->execute();
        }
                        // $sel_qry = "SELECT * FROM `sar_finance`";
                        // $sel_sql= $connect->prepare($sel_qry);
                        // $sel_sql->execute();
                        // $sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
                        // // $farmer_name=$sel_row["farmer_name"];
                        // $balance = $bal_row["balance"] - $amount;
        
    }

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
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        Unsettled
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                        Settled
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            &nbsp;&nbsp;
            <div class="col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                        <div class="row">
                            <div class="col" style="margin-bottom:20px">                               
                                <input type="date" id="from" name="from" class="form-control">
                            </div>
                            <div class="col">   
                                <input type="date" id="to" name="to" class="form-control">
                            </div>       
                            <div class="col">
                                <select class="form-control" id="group" name="group" style="width:210px;">
                                    <option value="">GroupName</option>
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
                                <select class="form-control" id="customer" name="customer">
                                    <option value="">CustomerName </option>

                                </select>
                            </div>             
                            <div class="col">
                                <button type="button" id="display" name="display" class="btn btn-primary">Display</button>
                                <button type="button" id="download" name="download" class="btn btn-success">Download</button>
                            </div> 
                            <br>                       
                            <div class="col-md-12">
                                <button type="button" style="position: relative;left:500px" id="add" name="add" style="color:#fff" class="btn btn-warning mymodal_finance">Add Finance</button>
                            </div>
                        </div>
                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Finance ID</th>
                                    <th>Customer Name</th>
                                    <th>Group Name</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">                               
                                    <input type="date" value="<?= $date ?>" id="from_settled" name="from" class="form-control">
                                </div>
                                <div class="col">
                                    
                                <input type="date" value="<?= $date ?>" id="to_settled" name="to" class="form-control">
                                </div>       
                                <div class="col">
                                        <select class="form-control" id="group_settled" name="group" style="width:210px;">
                                            <option value="">GroupName</option>
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
                                        <select class="form-control" id="customer_settled" name="customer">
                                            <option value="">CustomerName </option>

                                        </select>

                                    </div>             
                                <div class="col">
                                    <button type="button" id="display_settled" name="display_settled" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_settled" name="download_settled" class="btn btn-success">Download</button>
                                </div> 
                                <br>
                            </div>               
                            <div class="col-md-12">
                                <button type="button" style="position: relative;left:500px" id="add" name="add" style="color:#fff" class="btn btn-warning mymodal_finance">Add Finance</button>
                            </div>
                            <table id="example_settled" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Finance ID</th>
                                        <th>Customer Name</th>
                                        <th>Group Name</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
      <!-- Wrapper END -->
      <!-- Footer -->

<?php
if(isset($_POST["add_finance"])){
  $exp_qry="SELECT id FROM sar_finance ORDER BY id DESC LIMIT 1 ";
  $exp_sql=$connect->prepare("$exp_qry");
  $exp_sql->execute();
  $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
  $Last_id=$exp_row["id"]+1;
  $finance_id = "FIN_".date("Ym")."0".$Last_id;
  
  $date = date("Y-m-d");
  $customer_name = ucwords($_POST["customer_name"]);
  $group_name = ucwords($_POST["group_name"]);
  $amount = $_POST["amount"];

//   if($id==""){
  $query_1 = "INSERT INTO `sar_finance` SET 
                finance_id='$finance_id',
                date='$date',
                customer_name='$customer_name',
                group_name='$group_name',
                amount='$amount',
                updated_by='$updated_by'
                ";
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
        header('Location: finance.php');
    
// }else {
        
//             $query_1 = "UPDATE `sar_finance` SET 
//                             finance_id='$finance_id',
//                             date='$date',
//                             customer_name='$customer_name',
//                             group_name='$group_name',
//                             amount='$amount'
//                             updated_by='$updated_by'
//                         WHERE id='$id'";
//             $sql_1= $connect->prepare($query_1);
//             $sql_1->execute();
                       
//     }    
}

 ?>


    <?php require "footer.php" ?>
<script>
function update_model_data(finance_id, data_src) {
    if (data_src == 'settled') {
        $('#payment_form').hide();
    } else {
        $('#payment_form').show();
    }
    $.ajax({
        type: "POST",
        url: "forms/ajax_request_view.php",
        data: {
            "action": "view_finance_modal",
            "finance_id": finance_id,
            "data_src": data_src
        },
        dataType: "json",
        success: function(result) {
            var i = 0;
            $('#sar_payment_table').html("");
            $('#revoke_table').html("");
            for (i = 0; i < result.length; i++) {
                if (result[i].is_revoked) {
                    $('#revoke_table').append('<tr id=revoke_row_id_' + result[i].id + '>');
                    $('#revoke_table').append('<td>' + result[i].id + '</td>');
                    $('#revoke_table').append('<td>' + result[i].payment_date + '</td>');
                    $('#revoke_table').append('<td>' + result[i].payment_mode + '</td>');
                    $('#revoke_table').append('<td>' + result[i].amount + '</td>');
                    $('#revoke_table').append('</tr>');
                } else {
                    $('#sar_payment_table').append('<tr id=revoke_row_id_' + result[i].id + '>');
                    $('#sar_payment_table').append('<td>' + result[i].id + '</td>');
                    $('#sar_payment_table').append('<td>' + result[i].payment_date + '</td>');
                    $('#sar_payment_table').append('<td>' + result[i].payment_mode + '</td>');
                    $('#sar_payment_table').append('<td>' + result[i].amount + '</td>');
                    $('#sar_payment_table').append('<td>' + result[i].balance + '</td>');
                    $('#sar_payment_table').append(
                        '<td><a class="tabs_click tablinks" onclick=revoke_payment(this,' + result[
                            i].id + ',"' + data_src +
                        '") data-toggle="tab" href="#tabs1">Revoke</a></td>');
                    $('#sar_payment_table').append('</tr>');
                }
            }          
        }
    });
}

function revoke_payment(obj, sar_patti_payment_id, data_src) {
    var myKeyVals = {
        "id": sar_patti_payment_id,
        "action": "revoke_finance_payment",
        "data_src": data_src
    };
    $.ajax({
        type: 'POST',
        url: "forms/ajax_request.php?action=revoke_finance_payment",
        data: myKeyVals,
        dataType: "json",
        success: function(resultData) {
            update_model_data(resultData[0]['finance_id'], data_src)
            window.location.reload();
        }
    });
}
//$.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
        var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_finance",
                "type": "POST"
            },
            "columns": [
                { "data": "date" },
                { "data": "finance_id" },
                { "data": "customer_name" },
                { "data": "group_name" },
                { "data": "amount" },
                { "data": "paid_amount" },
                { "data": "balance" },
                { "data": "id" }
            ],
            columnDefs: [
               
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.date;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.finance_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" style="color:#f55989" finance_id="' + row.finance_id+'">' +
                        row.customer_name + '</a>';
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.group_name;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.amount;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.paid_amount;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.balance;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return '<a href="finance.php?req=delete&id='+row.id+'&finance_id='+row.finance_id+'" onclick="return checkDelete()">Delete</a>';
                    }
                }
                
            ]
        });
        $("#display").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            var grp = $("#group").val();
            var customer = $("#customer").val();
            // alert(customer)
            if(from!="" && to!="" && grp!="" && customer!=""){
                table.ajax.url("forms/ajax_request.php?action=view_finance&from="+from+'&to='+to+'&grp='+grp+'&customer='+customer).load();
                table.ajax.reload();
            }else if(from!="" && to!="" && grp!=""){
                table.ajax.url("forms/ajax_request.php?action=view_finance&from="+from+'&to='+to+'&grp='+grp).load();
                table.ajax.reload();
            }
            else if(from!="" && to!="" && customer!=""){
                table.ajax.url("forms/ajax_request.php?action=view_finance&from="+from+'&to='+to+'&customer='+customer).load();
                table.ajax.reload();
            }
            else if(customer!=""){
                table.ajax.url("forms/ajax_request.php?action=view_finance&customer="+customer).load();
                table.ajax.reload();
            }
            else if(grp!=""){
                table.ajax.url("forms/ajax_request.php?action=view_finance&grp="+grp).load();
                table.ajax.reload();
            }
            else if(customer=="CustomerName" || customer=="" || grp=="GroupName" || grp==""){
                table.ajax.url("forms/ajax_request.php?action=view_finance").load();
                table.ajax.reload();
            }
        });
        $('#example tbody').on('click', '.mymodal', function() {
            var finance_id = $(this).attr("finance_id");
            $("#myModal").modal("show");
            $("#finance_id").val(finance_id);
            // alert(finance_id);
            update_model_data(finance_id, 'unsettled')
        });    
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location.href="Getfinance.php?from="+from+"&to="+to;
        });    
        var table1=$('#example_settled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_finance_settled",
                "type": "POST"
            },
            "columns": [
                { "data": "date" },
                { "data": "finance_id" },
                { "data": "customer_name" },
                { "data": "group_name" },
                { "data": "amount" },
                { "data": "paid_amount" },
                { "data": "balance" },
                { "data": "id" }
            ],
            columnDefs: [
               
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.date;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.finance_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" style="color:#f55989" finance_id="' + row.finance_id+'">' +
                        row.customer_name + '</a>';
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.group_name;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.amount;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.paid_amount;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.balance;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return '<a href="finance.php?req=delete&id='+row.id+'&finance_id='+row.finance_id+'" onclick="return checkDelete()">Delete</a>';
                    }
                }
                
            ]
        });
        // $("#display_settled").on("click",function(){
        //     var from=$("#from_settled").val();
        //     var to=$("#to_settled").val();
        //     var group = $("#group_settled").val();
        //     var customer = $("#customer_settled").val();
        //     // alert(customer)
        //     if(from!="" && to!=""){
        //         table1.ajax.url("forms/ajax_request.php?action=view_finance_settled&from="+from+'&to='+to+'&grp='+group+'&customer='+customer).load();
        //         table1.ajax.reload();
        //     } else {
        //         table1.ajax.url("forms/ajax_request.php?action=view_finance_settled").load();
        //         table1.ajax.reload();
        //     }

        // });
        $('#example tbody').on('click', '.mymodal', function() {
            var finance_id = $(this).attr("finance_id");
            $("#myModal").modal("show");
            $("#finance_id").val(finance_id);
            // alert(finance_id);
            update_model_data(finance_id, 'unsettled')
        });    
        $('#example_settled tbody').on('click', '.mymodal', function() {
            var finance_id = $(this).attr("finance_id");
            $("#myModal").modal("show");
            $("#finance_id").val(finance_id);
            // alert(finance_id);
            update_model_data(finance_id, 'settled')
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location.href="Getfinance.php?from="+from+"&to="+to;
        });
        $("#download_settled").on("click",function(){
            var from=$("#from_settled").val();
            var to=$("#to_settled").val();
            window.location.href="Getfinance_settled.php?from="+from+"&to="+to;
        });    
        $('.mymodal_finance').on('click', function (){
            $( "#mymodal_finance" ).modal( "show" );
        });

        $('.close').on('click', function (){
            $( "#mymodal_finance" ).modal( "hide" );
        });
        $('.close').on('click', function (){
            $( "#myModal" ).modal( "hide" );
        });
        $('.close').on('click', function (){
            $( "#myModal_settled" ).modal( "hide" );
        });
        $("#group").on("change", function() {
            var grp = $(this).val();
            // alert(grp);
            $.ajax({
                type: "POST",
                url: "forms/ajax_request.php",
                data: {
                    "action": "fetch_finance",
                    "grp": grp
                },
                dataType: "json",
                success: function(result) {
                    var len = result.length;
                    // alert(result.length);
                    $("#customer").empty();
                    $("#customer").append('<option>CustomerName</option>');
                    for (var i = 0; i < len; i++) {
                        $("#customer").append('<option value=' + result[i].customer_name+ '>' + result[i]
                            .customer_name + '</option>');
                    }
                    // alert(result.contact_person);
                }
            })
        });
        $("#group_settled").on("change", function() {
            var grp = $(this).val();
            // alert(grp);
            $.ajax({
                type: "POST",
                url: "forms/ajax_request.php",
                data: {
                    "action": "fetch_finance_settled",
                    "grp": grp
                },
                dataType: "json",
                success: function(result) {
                    var len = result.length;
                    // alert(result.length);
                    $("#customer_settled").empty();
                    $("#customer_settled").append('<option>CustomerName</option>');
                    for (var i = 0; i < len; i++) {
                        $("#customer_settled").append('<option value=' + result[i].customer_name+ '>' + result[i]
                            .customer_name + '</option>');
                    }
                    // alert(result.contact_person);
                }
            })
        });
 
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
</script>
<script>
function checkDelete(){
    return confirm('Are you sure you want to delete?');
}
</script>
<div class="modal fade" id="mymodal_finance" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Finance</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                
                <form  method="post" action="">
                    <div class="form-group ">          
                        <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>                                   
                    <select class="form-control" id="group" name="group_name" required>
                            <option value="">--Choose Group Name--</option>
                            <?php
                                $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                $sel_sql= $connect->prepare($sel_qry);
                                $sel_sql->execute();
                                while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                                        
                                        echo '<option value='.$sel_row["grp_cust_name"].'>'.$sel_row["grp_cust_name"].'</option>';
                                }
                            ?>
                        
                        </select>
                    </div>
                    <div class="form-group ">
                        <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                    <select class="form-control" id="customer" name="customer_name" required>
                            <option value="">Choose Customer Name </option>
                            <?php
                                    $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    $sel_sql= $connect->prepare($sel_qry);
                                    $sel_sql->execute();
                                while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                                        
                                        echo '<option value="'.$sel_row["customer_name"].'">'.$sel_row["customer_name"].'</option>';
                                }
                            ?>
                        </select>
                    </div>                             
                    <div class="form-group">
                        <label for="exampleInputNumber1">Amount</label>
                        <span style="color:red">*</span>
                        <input type="number" class="form-control" id="exampleInputNumber1" required name="amount" min="0" required>
                    </div>
                
                    <input type="submit" class="btn btn-primary" name="add_finance" value="Submit">
                    <!--<button type="submit" class="btn iq-bg-danger">cancel</button>-->
                </form>
                            
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Finance Payment</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="form1" action="" method="POST">
                    <input type="hidden" name="finance_id" id="finance_id" value=""/>                
                        <table class="table table-responsive">
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_date" required>
                                        <!-- <input type="hidden" id="popup_finance_id" name="popup_finance_id" value="" /> -->
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Enter Amount" name="amount" required>
                                    </td>
                                    <td>
                                        <select name="payment_mode" class="form-control" required>

                                            <option value="">--Select Payment Mode--</option>

                                            <option value="neft">NEFT</option>

                                            <option value="online">Online</option>

                                            <option value="cash">Cash</option>

                                            <option value="dd">DD</option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" name="add_payment" class="btn btn-primary" value="Submit">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                </form>
                <p style="font-size:25px;">Payment History</p>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Payment Date</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="sar_payment_table">
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <p style="font-size:25px;">Revoke History</p>
                <table class="table table-bordered">                           
                    <tr>
                        <td colspan="2">                               
                            <table class="table table-bordered">                                   
                                <thead>                                       
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Payment Date</th>
                                        <th>Payment Mode</th>
                                        <th>Revoked Amount(-)</th>
                                    </tr>
                                </thead>
                                <tbody id="revoke_table">                                       
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
