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
if(isset($_REQUEST['revenue_no'])!=""){
    $revenue_no=$_REQUEST["revenue_no"];
} else {
    $revenue_no="";
}
if($req=="delete")
{
    $delete="DELETE FROM sar_stock WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    
    
    // $delete_fin_qry="DELETE FROM financial_transactions WHERE misc_id='$revenue_no'";
    // $delete_fin_sql= $connect->prepare($delete_fin_qry);
    // $delete_fin_sql->execute();
    header("location:view_stock.php");
    
}

if(isset($_POST["add_ob_return"])){
    $delivery_challan_qry="SELECT id FROM sar_ob_return ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $stock_return_id = "SR_".date("Ym")."0".$Last_id;  

    $payment_return_date = $_POST['return_date'];
    $stock_return = $_POST["return_box"];
    $popup_return_id = $_POST["popup_return_id"];
    
    $opening_balance_qry = "SELECT * FROM sar_stock WHERE purchase_id='$popup_return_id' GROUP BY purchase_id";

    $opening_balance_sql = $connect->prepare($opening_balance_qry);
    $opening_balance_sql->execute();
    $opening_balance_row = $opening_balance_sql->fetch(PDO::FETCH_ASSOC);

    $balance_qry = "SELECT no_of_boxes FROM sar_ob_return WHERE purchase_id='$popup_return_id' GROUP BY purchase_id";
    $balance_sql = $connect->prepare($balance_qry);
    $balance_sql->execute();
    $balance_row = $balance_sql->fetch(PDO::FETCH_ASSOC);
    
    $select_qry2="SELECT sum(return_discount) as discount FROM sar_waiver_return WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
    
    $balance = $opening_balance_row["quantity"] - $stock_return - $total_discount_on_sales - $balance_row['no_of_boxes'];
   // echo $balance;
    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_ob_return`

              SET no_of_boxes='$stock_return',
              return_id='$stock_return_id',
              return_date='$payment_return_date',
              purchase_id='$popup_return_id',
              balance='$balance'
              ";
        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
      $lastInsertId = $connect->lastInsertId();
    }
    //echo $insert;
    $total_qry = "SELECT *, sum(no_of_boxes) as totalquantity FROM sar_ob_return WHERE purchase_id='$popup_return_id' GROUP BY purchase_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);


    $quantity_qry = "SELECT *, sum(quantity) as total_bill_quantity FROM sar_stock WHERE purchase_id='$popup_return_id' GROUP BY purchase_id ORDER BY id DESC ";
    $quantity_sql = $connect->prepare($quantity_qry);
    $quantity_sql->execute();
    $quantity_row = $quantity_sql->fetch(PDO::FETCH_ASSOC);


    if ($quantity_row['total_bill_quantity'] <= $total_row['totalquantity']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        // $date = date("Y/m/d");

        $delete = "UPDATE `sar_stock` SET return_status=1  where purchase_id ='$popup_return_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_stock WHERE purchase_id='$popup_return_id'";
        $sel_sql3 = $connect->prepare($select_qry3);
        $sel_sql3->execute();
        $sel_row3 = $sel_sql3->fetchAll();
        // echo var_dump($sel_row3);
        // exit;
        // foreach ($sel_row3 as $sel) {
        //     $add_balance_query = "INSERT INTO `sar_cash_carry` SET
        //   cash_no = '" . $sel['balance_id'] . "',
        //   date = '" . $sel['date'] . "',
        //   customer_name = '" . $sel['name'] . "',
        //   total_bill_amount = '" . $sel['amount'] . "',
        //   updated_by = '". $sel['updated_by'] ."',
        //   is_active=1
        //   ";
        //     $res_balance = mysqli_query($con, $add_balance_query);
        //     /// echo $add_sales_query;
        // }
    }
}
if(isset($_POST["add_stock_payment"])){
    $delivery_challan_qry="SELECT id FROM sar_stock_payment ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $stock_payment_id = "SPAY_".date("Ym")."0".$Last_id;  

    $payment_stock_date = $_POST['payment_stock_date'];
    $stock_amount = $_POST["amount"];
    $payment_stock_mode = $_POST["payment_stock_mode"];
    
   // $id = $_POST["popup_customer_id"];
    $popup_purchase_id = $_POST["popup_purchase_id"];
   // $customer_name=$_POST['customer_name'];
    $opening_balance_qry = "SELECT * FROM sar_stock WHERE purchase_id='$popup_purchase_id' GROUP BY purchase_id";

    $opening_balance_sql = $connect->prepare($opening_balance_qry);
    $opening_balance_sql->execute();
    $opening_balance_row = $opening_balance_sql->fetch(PDO::FETCH_ASSOC);
    
    $balance_payment_qry = "SELECT amount FROM sar_stock_payment WHERE purchase_id='$popup_purchase_id' GROUP BY purchase_id";
    $balance_payment_sql = $connect->prepare($balance_payment_qry);
    $balance_payment_sql->execute();
    $balance_payment_row = $balance_payment_sql->fetch(PDO::FETCH_ASSOC);
    
    $select_qry2="SELECT sum(discount) as discount FROM sar_waiver_pay WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
    $balance = $opening_balance_row["stock_amount"] - $stock_amount - $total_discount_on_sales - $balance_payment_row['amount'];
   // echo $balance;
    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_stock_payment`

              SET amount='$stock_amount',
              payment_id='$stock_payment_id',
              payment_date='$payment_stock_date',
              payment_mode='$payment_stock_mode',
              purchase_id='$popup_purchase_id',
              balance='$balance'
              ";
        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
      $lastInsertId = $connect->lastInsertId();
    }
    $total_qry = "SELECT *, sum(amount) as totalamount FROM sar_stock_payment WHERE purchase_id='$popup_purchase_id' GROUP BY purchase_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);

    $open_amount_qry = "SELECT *, sum(stock_amount) as totalbillamount FROM sar_stock WHERE purchase_id='$popup_purchase_id' GROUP BY purchase_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);


    if ($open_amount_row['totalbillamount'] <= $total_row['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        // $date = date("Y/m/d");

        $delete = "UPDATE `sar_stock` SET payment_status=1  where purchase_id ='$popup_purchase_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_stock WHERE purchase_id='$popup_purchase_id'";
        $sel_sql3 = $connect->prepare($select_qry3);
        $sel_sql3->execute();
        $sel_row3 = $sel_sql3->fetchAll();
        // echo var_dump($sel_row3);
        // exit;
        // foreach ($sel_row3 as $sel) {
        //     $add_balance_query = "INSERT INTO `sar_cash_carry` SET
        //   cash_no = '" . $sel['balance_id'] . "',
        //   date = '" . $sel['date'] . "',
        //   customer_name = '" . $sel['name'] . "',
        //   total_bill_amount = '" . $sel['amount'] . "',
        //   updated_by = '". $sel['updated_by'] ."',
        //   is_active=1
        //   ";
        //     $res_balance = mysqli_query($con, $add_balance_query);
        //     /// echo $add_sales_query;
        // }
    }
}
 ?>

 <style>

    /*.iq-card-body*/
    /*{*/
    /*    display:flex;*/
    /*    flex-direction:row;*/
    /*}*/

 </style>

<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div><h2>View Stock</h2></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-card12" style="padding:0">
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-4 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        UnSettled
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                       Payment Settled
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#return">
                                        Return Settled
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from" name="from">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit" name="submit" class="btn btn-primary">Display</button>
                                    <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <table id="unsettled" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Quality Name</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_settled" name="from_settled">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_settled" name="to_settled" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_settled" name="submit_settled" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_settled" name="download_settled" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <br>
                            <table id="settled" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Quality Name</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                        <div class="tab-pane fade" id="return" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_return" name="from_return">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_return" name="to_return" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_return" name="submit_return" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_return" name="download_return" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <table id="return_settled" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Boxes</th>
                                        <th>Username</th>
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

<?php require "footer.php";

?>
<script>
function add_waiver_cash(){
    
        var myKeyVals = { "waiver_date": $('#waiver_date').val(),"action": "add_waiver_stock", "waiver_amount": $('#waiver_amount').val(), "purchase_id":$('#purchase_id').val() };
        
        $.ajax({
          type: 'POST',
          url: "forms/ajax_request.php?action=add_waiver_stock",
          data: myKeyVals,
          dataType: "json",
          success: function(resultData) {
              window.location.reload();
              //update_sale_modal(customer_name, resultData[0]['customer_id'], resultData[0]['customer_id'], data_src) 
              }
          
        });
    }
function add_return_waiver(){
    var myKeyVals = { "waiver_return_date": $('#waiver_return_date').val(),"action": "add_waiver_return", "discount_return": $('#discount_return').val(), "return_id":$('#purchase_id').val() };
    $.ajax({
      type: 'POST',
      url: "forms/ajax_request.php?action=add_waiver_return",
      data: myKeyVals,
      dataType: "json",
      success: function(resultData) {
          window.location.reload();
          //update_sale_modal(customer_name, resultData[0]['customer_id'], resultData[0]['customer_id'], data_src) 
        }
      
    });
}
function update_payment_modal(purchase_id,data_src) {
         
        $("#myModal").modal("show");
        $("#purchase_id").val(purchase_id);
        if(data_src == 'settled'){
            $('#payment_form').hide();
        }else{
            $('#payment_form').show();
        }
        // $('#waiver_form').hide();
        // $("#waiver_click").on("click",function(){
        //     if(){
        //     $('#waiver_form').show();
        //     }else{
        //     $('#waiver_form').hide();
        //     }
        // });
        
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {"action": "view_stock_payment_modal","purchase_id": purchase_id,"data_src": data_src},
            dataType: "json",
            success: function(data) {
                //console.log(data);
                $('#tabs1 #popup_purchase_id').val(purchase_id);
                var result = data.data;
                var result1 = data['cash'];
                
                $("#date").html(result[0].date);
                $("#purchase_id").html(result[0].purchase_id);
                $("#group_name").html(result[0].group_name);
                $("#supplier_name").html(result[0].supplier_name);
                $("#quality_name").html(result[0].quality_name);
                $("#quantity").html(result[0].quantity);
                $("#rate").html(result[0].rate);
                $("#amount").html(result[0].stock_amount);
                $("#total_discount").html(result[0].total_discount);
                $("#total_amount").html(result[0].total_amount);
                $("#payment").html("");
                var i = 0;
                for (i = 0; i < result1.length; i++) {
                    $('#payment').append('<tr>');
                    $('#payment').append('<td>' + result1[i].payment_id + '</td>');
                    $('#payment').append('<td>' + result1[i].payment_date + '</td>');
                    $('#payment').append('<td>' + result1[i].payment_mode + '</td>');
                    $('#payment').append('<td>' + result1[i].amount + '</td>');
                    $('#payment').append('<td>' + result1[i].balance + '</td>');
                    $('#payment').append('</tr>');
                }
                
            }
        });
    }
function update_return_modal(purchase_id,data_src) {
         
        $("#myModal1").modal("show");
        $("#purchase_id").val(purchase_id);
        if(data_src == 'settled'){
            $('#return_form').hide();
        }else{
            $('#return_form').show();
        }
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {"action": "view_ob_return_modal","purchase_id": purchase_id,"data_src": data_src},
            dataType: "json",
            success: function(data) {
                //console.log(data);
                $('#tabs1 #popup_return_id').val(purchase_id);
                var result = data.data;
                var result1 = data['return'];
                
                $("#ret_date").html(result[0].date);
                $("#pur_id").html(result[0].id);
                $("#grp_name").html(result[0].group_name);
                $("#sup_name").html(result[0].supplier_name);
                $("#no_of_boxes").html(result[0].quantity);
                $("#total_discount_return").html(result[0].total_discount);
                $("#total_quantity_return").html(result[0].total_quantity);
                $("#stock_return").html("");
                var i = 0;
                for (i = 0; i < result1.length; i++) {
                    $('#stock_return').append('<tr>');
                    $('#stock_return').append('<td>' + result1[i].return_id + '</td>');
                    $('#stock_return').append('<td>' + result1[i].return_date + '</td>');
                    $('#stock_return').append('<td>' + result1[i].no_of_boxes + '</td>');
                    $('#stock_return').append('<td>' + result1[i].balance + '</td>');
                    $('#stock_return').append('</tr>');
                }
                
            }
        });
    }
</script>
<script>

//$.fn.dataTableExt.sErrMode = 'throw';

    $(document).ready(function(){
       var table=$('#unsettled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_stock",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "purchase_id"},
                    { "data": "supplier_name"},
                    { "data": "quality_name"},
                    { "data": "quantity"},
                    { "data": "rate"},
                    { "data": "stock_amount" },
                    { "data": "updated_by"},
                    { "data": "id"}
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
                        return row.purchase_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.supplier_name;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.quality_name;    
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.quantity;
                    }
                },
                {
                    targets: 5,

                    render: function(data, type, row) {
                        return row.rate;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.stock_amount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                        if(row.paid_amount == null && row.no_of_boxes == null){
                            return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_id" href="#" purchase_id="'+row.purchase_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a><a class="dropdown-item" href="view_stock.php?req=delete&id='+row.id+'" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a><a class="dropdown-item" href="#" id="return_modal" purchase_id="'+row.purchase_id+'"><i class="ri-file-download-fill mr-2"></i>Return</a><a class="dropdown-item" target="_blank" onclick="var w = window.open(\'download_stock_purchase.php?purchase_id='+row.purchase_id+'\',\'mywin\'); w.print();" ><i class="fa fa-print"></i>&nbsp;&nbsp; Print</a></div></div>';
                        
                    }else if(row.paid_amount != null){
                            return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_id" href="#" purchase_id="'+row.purchase_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
                        }else if(row.no_of_boxes != null){
                            return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" href="#" id="return_modal" purchase_id="'+row.purchase_id+'"><i class="ri-file-download-fill mr-2"></i>Return</a></div></div>';
                    }
                  }
                }
             ],
             "order": [[ 1, 'asc' ]]
        });
       
        $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_stock&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_stock").load();
                table.ajax.reload();
            }
        });
        
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="stockunsettled.php?from="+from+'&to='+to;
        });
        
        var table1=$('#settled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_stock_settled",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "purchase_id"},
                    { "data": "supplier_name"},
                    { "data": "quality_name"},
                    { "data": "quantity"},
                    { "data": "rate"},
                    { "data": "stock_amount" },
                    { "data": "updated_by"},
                    { "data": "id"}
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
                        return row.purchase_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.supplier_name;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.quality_name;    
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.quantity;
                    }
                },
                {
                    targets: 5,

                    render: function(data, type, row) {
                        return row.rate;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.stock_amount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                       return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_settled" href="#" purchase_id="'+row.purchase_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
                    }
                }
             ],
             "order": [[ 1, 'asc' ]]
             
        });
        $("#submit_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to_settled").val();
            if(from_settled!="" && to_settled!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_stock_settled&from_settled="+from_settled+'&to_settled='+to_settled).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_stock_settled").load();
                table1.ajax.reload();
            }
        });
        $("#download_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to_settled").val();
            window.location="stocksettled.php?from="+from_settled+'&to='+to_settled;
        });
        
        var table2=$('#return_settled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_return_settled",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "purchase_id"},
                    { "data": "supplier_name"},
                    { "data": "quantity"},
                    { "data": "updated_by"},
                    { "data": "id"}
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
                        return row.purchase_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.supplier_name;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.quantity;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                      return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" href="#" id="return_modal_settled" purchase_id="'+row.purchase_id+'"><i class="ri-file-download-fill mr-2"></i>Return</a></div></div>';
                    }
                }
             ],
             "order": [[ 1, 'asc' ]]
        });
       
       $("#submit_return").on("click",function(){
            var from_return=$("#from_return").val();
            var to_return=$("#to_return").val();
            if(from_return!="" && to_return!=""){
                table2.ajax.url("forms/ajax_request.php?action=view_return_settled&from_return="+from_return+'&to_return='+to_return).load();
                table2.ajax.reload();
            } else {
                table2.ajax.url("forms/ajax_request.php?action=view_return_settled").load();
                table2.ajax.reload();
            }
        }); 
        $("#download_return").on("click",function(){
            var from_return=$("#from_return").val();
            var to_return=$("#to_return").val();
            window.location="stockreturn.php?from="+from_return+'&to='+to_return;
        });
        $('#unsettled tbody').on('click', '#mymodal_id', function() {
            var purchase_id = $(this).attr("purchase_id");
            console.log(purchase_id);
            $("#myModal").modal("show");
            $("#purchase_id").val(purchase_id);
            update_payment_modal(purchase_id, 'unsettled');
        });
        
        $('#settled tbody').on('click', '#mymodal_settled', function (){
            var purchase_id = $(this).attr("purchase_id");
            $( "#myModal" ).modal( "show" );
            $("#purchase_id").val(purchase_id);
            update_payment_modal(purchase_id, 'settled')
        });
        
        $('#unsettled tbody').on('click', '#return_modal', function() {
            var purchase_id = $(this).attr("purchase_id");
            console.log(purchase_id);
            $("#myModal1").modal("show");
            $("#purchase_id").val(purchase_id);
            update_return_modal(purchase_id, 'unsettled');
        });
        
        $('#return_settled tbody').on('click', '#return_modal_settled', function (){
            var purchase_id = $(this).attr("purchase_id");
            $( "#myModal1" ).modal( "show" );
            $("#purchase_id").val(purchase_id);
            update_return_modal(purchase_id, 'settled')
        });
        $(".close").click(function() {
            $("#myModal").modal("hide");
            
        });
        $(".close").click(function() {
            $("#myModal1").modal("hide");
            
        });
    });

</script>
<script>
function checkDelete(){
    return confirm('Are you sure you want to delete?');
}
</script>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Stock Payment</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form action="" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="date"></td>
                            </tr>
                            <tr>
                                <th>Purchase ID</th>
                                <td id="purchase_id"></td>
                            </tr>
                            <tr>
                                <th>Group Name</th>
                                <td id="group_name"></td>
                            </tr>
                            <tr>
                                <th>Supplier Name</th>
                                <td id="supplier_name"></td>
                            </tr>
                            <tr>
                                <th>Quality Name</th>
                                <td id="quality_name"></td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td id="quantity"></td>
                            </tr>
                            <tr>
                                <th>rate</th>
                                <td id="rate"></td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td id="amount"></td>
                            </tr>
                            <tr>
                                <th>Total Discount</th>
                                <td id="total_discount"></td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td id="total_amount"></td>
                            </tr>
                        </table>
                        <div id="payment_form">
                            <table class="table table-bordered">
                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_stock_date" required> 
                                    <input type="hidden" id="popup_purchase_id" name="popup_purchase_id" value="" />
                                    </td>
                                    <td><input type="text" class="form-control" name="amount" placeholder="Enter Amount" required>
                                    </td>
                                    <td>
                                        <select name="payment_stock_mode" class="form-control" required>
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
                                    <td><input type="submit" name="add_stock_payment" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </form>
                    <!--<a href="#" id="waiver_click">Waiver</a>-->
                        <form method="POST">
                            
                                <div id="waiver_form"><a href="#"><h4>Waiver<h4></a></div>
                                <table class="table table-bordered" >
                                    <tbody>
                                        <tr>
                                            <td>
                                            <input type="date" value="<?= $date ?>" class="form-control datepicker" name="waiver_date"  id="waiver_date" style="display:none" required>
                                                <input type="hidden" id="purchase_id" name="purchase_id" value="" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="waiver_amount" id="waiver_amount" placeholder="Enter Discount Amount" style="display:none" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                            <input type="button" id="add_waiver" name="add_waiver" onclick=add_waiver_cash() class="btn btn-primary" style="display:none" value="Submit">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                        
                        </form>
                </div>
                <div id="tabs2" class="tabcontent">
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
                                            <!--<th>Action</th>-->
                                            <!--<th>Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="payment">
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn close" id="close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Return</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form action="" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="ret_date"></td>
                            </tr>
                            <tr>
                                <th>Purchase ID</th>
                                <td id="pur_id"></td>
                            </tr>
                            <tr>
                                <th>Group Name</th>
                                <td id="grp_name"></td>
                            </tr>
                            <tr>
                                <th>Supplier Name</th>
                                <td id="sup_name"></td>
                            </tr>
                            <tr>
                                <th>No.of.boxes</th>
                                <td id="no_of_boxes"></td>
                            </tr>
                            <tr>
                                <th>Total Discount</th>
                                <td id="total_discount_return"></td>
                            </tr>
                            <tr>
                                <th>Total Quantity</th>
                                <td id="total_quantity_return"></td>
                            </tr>
                            
                        </table>
                        <div id="return_form">
                            <table class="table table-bordered">
                            <thead>
                                <h4>Return</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="return_date" required> 
                                    <input type="hidden" id="popup_return_id" name="popup_return_id" value="" />
                                    </td>
                                    <td><input type="text" class="form-control" name="return_box" placeholder="Enter Quantity" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" name="add_ob_return" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </form>
                    
                        <form method="POST">
                            
                            <div id="waiver_return_form"><a href="#"><h4>Waiver<h4></a></div>
                                <table class="table table-bordered" >
                                    <!--<thead>-->
                                    <!--    <h4>Box Waiver</h4>-->
                                    <!--</thead>-->
                                    <tbody>
                                        <tr>
                                            <td>
                                            <input type="date" value="<?= $date ?>" class="form-control datepicker" name="waiver_return_date"  id="waiver_return_date" style="display:none" required>
        
                                                <input type="hidden" id="purchase_id" name="return_id" value="" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="discount_return" id="discount_return" placeholder="Enter Box Discount" style="display:none" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="button" id="add_return" name="add_return" onclick=add_return_waiver() class="btn btn-primary" style="display:none" value="Submit">
                                            </td>
                                        </tr>
    
                                    </tbody>

                                </table>
                            
                            
                        </form>
                </div>
                <div id="tabs2" class="tabcontent">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Return ID</th>
                                            <th>Return Date</th>
                                            <th>No.Of.Boxes</th>
                                            <th>Balance</th>
                                            <!--<th>Action</th>-->
                                            <!--<th>Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="stock_return">
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn close" id="close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$("#waiver_form").click(function () { 
    $("#waiver_date").toggle();
    $("#waiver_amount").toggle();
    $("#add_waiver").toggle();      
});
$("#waiver_return_form").click(function () { 
    $("#waiver_return_date").toggle();
    $("#discount_return").toggle();
    $("#add_return").toggle();      
});
</script>
  