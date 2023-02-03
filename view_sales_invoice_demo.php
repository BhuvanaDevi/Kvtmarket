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

// if($req=="enabled")
// {
//     $delete="UPDATE `sar_sales_invoice` SET reg_type=settled WHERE id=:id";
//     $delete_sql= $connect->prepare($delete);
//     $delete_sql->execute(array(':id' => $id));
//     header("location:Generate-Sales-Invoice.php");
// }

// if($req=="disabled")
// {
//     $delete="UPDATE `sar_sales_invoice` SET is_active=1 WHERE id=:id";
//     $delete_sql= $connect->prepare($delete);
//     $delete_sql->execute(array(':id' => $id));
//     header("location::Generate-Sales-Invoice.php");
// }





if (isset($_POST["add_payment"])) {

    $payment_date = $_POST['payment_date'];
    $amount = $_POST["amount"];
    $payment_mode = $_POST["payment_mode"];
    //$payment_id = "pay_".uniqid();
    // payment_id='$payment_id',
    $id = $_POST["popup_customer_id"];
    $popup_sales_id = $_POST["popup_sales_id"];
    $select_qry = "SELECT * FROM sar_sales_payment ORDER BY id DESC ";
    $select_sql = $connect->prepare($select_qry);
    $select_sql->execute();
    //$select_fetch=$select_sql->rowCount();
    $select_fetch = $select_sql->fetch(PDO::FETCH_ASSOC);

    $select_qry4 = "SELECT *, sum(bill_amount) as totalbillamount FROM sar_sales_invoice WHERE sales_no='$popup_sales_id' GROUP BY sales_no";
    $sel_sql4 = $connect->prepare($select_qry4);
    $sel_sql4->execute();
    $sel_row4 = $sel_sql4->fetch(PDO::FETCH_ASSOC);

    $select_qry5 = "SELECT *, sum(amount) as totalamount FROM sar_sales_payment WHERE customer_id='$popup_sales_id' GROUP BY customer_id ORDER BY id DESC ";
    $sel_sql5 = $connect->prepare($select_qry5);
    $sel_sql5->execute();
    $sel_row5 = $sel_sql5->fetch(PDO::FETCH_ASSOC);

    //$total_amt=$select_fetch["net_bill_amount"]-$amount;
    $balance = $sel_row4["totalbillamount"] - $sel_row5['totalamount'] - $amount;
    if ($balance >= 0) {
        $insert = "insert `sar_sales_payment`
              SET amount='$amount',
              payment_date='$payment_date',
              payment_mode='$payment_mode',
              customer_id='$popup_sales_id',
              balance='$balance'";

        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
    }

    $select_qry1 = "SELECT *, sum(amount) as totalamount FROM sar_sales_payment WHERE customer_id='$popup_sales_id' GROUP BY customer_id ORDER BY id DESC ";
    $sel_sql1 = $connect->prepare($select_qry1);
    $sel_sql1->execute();
    $sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);


    $select_qry2 = "SELECT *, sum(bill_amount) as totalbillamount FROM sar_sales_invoice WHERE sales_no='$popup_sales_id' GROUP BY sales_no ORDER BY id DESC ";
    $sel_sql2 = $connect->prepare($select_qry2);
    $sel_sql2->execute();
    $sel_row2 = $sel_sql2->fetch(PDO::FETCH_ASSOC);


    if ($sel_row2['totalbillamount'] > $sel_row1['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";
        // $delete_sql= $connect->prepare($delete);
        // $delete_sql->execute();

    } else if ($sel_row2['totalbillamount'] <= $sel_row1['totalamount']) {
        // $delete="UPDATE `sar_sales_payment` SET balance=0";
        // $delete_sql= $connect->prepare($delete);
        // $delete_sql->execute();

        $delete = "UPDATE `sar_sales_invoice` SET payment_status=1 where sales_no ='$popup_sales_id' ";
        $delete_sql = $connect->prepare($delete);
        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_sales_invoice WHERE sales_no='$popup_sales_id'";
        $sel_sql3 = $connect->prepare($select_qry3);
        $sel_sql3->execute();
        $sel_row3 = $sel_sql3->fetchAll();
        // echo var_dump($sel_row3);
        // exit;
        foreach ($sel_row3 as $sel) {
            $add_sales_query = "INSERT INTO `sar_cash_carry` SET
   cash_no = '" . $sel['sales_no'] . "',
   date = '" . $sel['date'] . "',
   customer_name = '" . $sel['customer_name'] . "',
   mobile_number = '" . $sel['mobile_number'] . "',
   quality_name = '" . $sel['quality_name'] . "',
   quantity = '" . $sel['quantity'] . "',
   rate = '" . $sel['rate'] . "',
   bill_amount = '" . $sel['bill_amount'] . "',
   customer_id = '" . $sel['id'] . "',
   total_bill_amount = '" . $sel['total_bill_amount'] . "',
   payment = '" . $sel['payment_id'] . "' ";
            $res = mysqli_query($con, $add_sales_query);
            /// echo $add_sales_query;

        }
    }

    //header("Location: /view_sales_invoice_demo.php"); /* Redirect browser */
    ///exit();
}


?>



<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3>View Generate Sales Invoice</h3>
                <div class="iq-card7" style="padding:0">
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-3 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        UnSettled
                                    </a>
                                </li>
                                <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                        Settled
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

                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sales Invoice ID</th>
                                        <th>UserName</th>
                                        <th>Category</th>
                                        <th>Customer Name</th>
                                        <th>Mobile Number</th>
                                        <th>Bill Amount</th>
                                        <th>Payment</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                            <table id="example1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Cash&Carry ID</th>
                                        <th>UserName</th>
                                        <th>Category</th>
                                        <th>Customer Name</th>
                                        <th>Mobile Number</th>
                                        <th>Bill Amount</th>
                                        <th>Payment</th>
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
    //$.fn.dataTableExt.sErrMode = 'throw';
    function update_sale_modal(customer_name, customer_id, sales_id) {
        $("#myModal").modal("show");
        $("#customer_name").val(customer_name);
        var payload = {
            "action": "view_sales_modal",
            "customer_name": customer_name,
            "customer_id": customer_id,
            "sales_id": sales_id
        }
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: payload,
            dataType: "json",
            success: function(data) {
                console.log(data);

                $('#tabs1 #popup_customer_id').val(customer_id);
                $('#tabs1 #popup_sales_id').val(sales_id);
                var result = data.data;
                var result1 = data.cash;


                $("#produ_details").html("");
                $("#tabs2 #produ_details").html("");
                if (result1) {
                    var k = 0;
                    for (k = 0; k < result1.length; k++) {
                        $('#tabs2 #produ_details').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td><td>' + result1[k].balance + '</td></tr>');
                    }
                }


                var i = 0;
                for (i = 0; i < result.length; i++) {
                    $('#produ_details').append('<tr>');
                    $('#produ_details').append('<td>' + result[i].quality_name + '</td>');
                    $('#produ_details').append('<td>' + result[i].quantity + '</td>');
                    $('#produ_details').append('<td>' + result[i].rate + '</td>');
                    $('#produ_details').append('<td>' + result[i].bill_amount + '</td>');
                    $('#produ_details').append('</tr>');
                }
                $("#boxes_arrived").html(result[i - 1].boxes_arrived);
                $("#sales_no").html(result[i - 1].sales_no);
                $("#date").html(result[i - 1].date);
                $("#customer_address").html(result[i - 1].customer_address);
                $("#mobile_number").html(result[i - 1].mobile_number);
                $('#produ_details').append('<tr>');
                $("#customer_name").html(result[i - 1].customer_name);
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td><b>Total Amount</b></td>');

                var sum_totalamount = 0;
                for (j = 0; j < result.length; j++) {
                    sum_totalamount += parseFloat(result[j].bill_amount);
                }

                $('#produ_details').append('<td>' + sum_totalamount + '</td>');
                $('#produ_details').append('</tr>');
            }

        });
    }

    function update_cash_modal(cash_no) {
        $("#myModal1").modal("show");
        $("#cash_no").val(cash_no);
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {
                "action": "view_cash_modal",
                "cash_no": cash_no
            },
            dataType: "json",
            success: function(result) {
                $("#produ_details").html("");
                var i = 0;
                for (i = 0; i < result.length; i++) {

                    $('#produ_details1').append('<tr>');
                    $('#produ_details1').append('<td>' + result[i].quality_name + '</td>');
                    $('#produ_details1').append('<td>' + result[i].quantity + '</td>');
                    $('#produ_details1').append('<td>' + result[i].rate + '</td>');
                    $('#produ_details1').append('<td>' + result[i].bill_amount + '</td>');

                    $('#produ_details1').append('</tr>');
                }

                $("#date_cash").html(result[i - 1].date);

                $('#produ_details1').append('<tr>');
                $("#cash_no").html(result[i - 1].cash_no);
                $('#produ_details1').append('<td></td>');
                $('#produ_details1').append('<td></td>');
                $('#produ_details1').append('<td><b>Total Amount</b></td>');

                $('#produ_details1').append('<td>' + result[i - 1].total_bill_amount + '</td>');
                $('#produ_details1').append('</tr>');

            }

        });
    }
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_sales_invoice",
                "type": "POST"
            },
            "columns": [{
                    "data": "date"
                },
                {
                    "data": "sales_no"
                },
                {
                    "data": "updated_by"
                },
                {
                    "data": "category"
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "mobile_number"
                },
                {
                    "data": "bill_amount"
                },
                {
                    "data": "amount"
                },
                {
                    "data": "balance"
                }
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
                        return row.sales_no;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.category;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return '<a class="mymodal" customer_name="' + row.customer_name + '" id="' + row.id + '" crid="' + row.sales_no + '" >' + row.customer_name + '</a>';
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.mobile_number;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.totalbillamount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return row.amount;
                    }
                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                        return row.balance;
                    }
                }

            ]
        });
        var table1 = $('#example1').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_cash_carry",
                "type": "POST"
            },
            "columns": [{
                    "data": "date"
                },
                {
                    "data": "cash_no"
                },
                {
                    "data": "updated_by"
                },
                {
                    "data": "category"
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "mobile_number"
                },
                {
                    "data": "bill_amount"
                },
                {
                    "data": "payment"
                }
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
                        return '<a class="mymodal" cash_no="' + row.cash_no + '" id="' + row.id + '"crid="' + row.cash_no + '">' + row.cash_no + '</a>';
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.category;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return '<a class="mymodal" customer_name="' + row.customer_name + '" id="' + row.customer_id + '" crid="' + row.cash_no + '" >' + row.customer_name + '</a>';
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        row.mobile_number;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        row.bill_amount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        row.payment;
                    }
                }

            ]
        });
        // $(".reg_type").on("click",function(){
        //     var reg_type=$(this).val();
        //     //alert(reg_type);
        //     table.ajax.url('forms/ajax_request?action=view_sales_invoice&reg_type='+reg_type).load();
        //     table.ajax.reload();

        // });
        $('#example tbody').on('click', '.mymodal', function() {
            var customer_name = $(this).attr("customer_name");
            var customer_id = $(this).attr("id");
            var sales_id = $(this).attr("crid");
            $("#myModal").modal("show");
            $("#customer_name").val(customer_name);
            $.ajax({
                type: "POST",
                url: "forms/ajax_request_view.php",
                data: {
                    "action": "view_sales_modal",
                    "customer_name": customer_name,
                    "customer_id": customer_id,
                    "sales_id": sales_id
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#tabs1 #popup_customer_id').val(customer_id);
                    $('#tabs1 #popup_sales_id').val(sales_id);
                    var result = data.data;
                    var result1 = data.cash;


                    $("#produ_details").html("");
                    $("#tabs2 #produ_details").html("");
                    if (result1) {
                        var k = 0;
                        // var sum_totalamount1 = 0; <td><input type="hidden" name="id"></td>
                        for (k = 0; k < result1.length; k++) {
                            $('#tabs2 #produ_details').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td><td>' + result1[k].balance + '</td></tr>');



                            // sum_totalamount1 += parseFloat(result1[k].balance);


                        }

                        ///$('#tabs2 #produ_details').append('<tr><td></td><td></td><td></td><td><b>Taltal balance : </b></td><td>'+sum_totalamount1+'</td></tr>');

                    }


                    var i = 0;
                    for (i = 0; i < result.length; i++) {

                        $('#produ_details').append('<tr>');
                        $('#produ_details').append('<td>' + result[i].quality_name + '</td>');
                        $('#produ_details').append('<td>' + result[i].quantity + '</td>');
                        $('#produ_details').append('<td>' + result[i].rate + '</td>');
                        $('#produ_details').append('<td>' + result[i].bill_amount + '</td>');

                        //  $("#product_type").html("").append(result.data.finished_product_type);

                        //   $('#product_type').html("").append("<td>").append(result.data.finished_product_type);
                        $('#produ_details').append('</tr>');
                    }
                    $("#boxes_arrived").html(result[i - 1].boxes_arrived);
                    $("#sales_no").html(result[i - 1].sales_no);
                    $("#date").html(result[i - 1].date);
                    $("#customer_address").html(result[i - 1].customer_address);
                    $("#mobile_number").html(result[i - 1].mobile_number);
                    $('#produ_details').append('<tr>');
                    $("#customer_name").html(result[i - 1].customer_name);
                    $('#produ_details').append('<td></td>');
                    $('#produ_details').append('<td></td>');
                    $('#produ_details').append('<td><b>Total Amount</b></td>');

                    var sum_totalamount = 0;
                    for (j = 0; j < result.length; j++) {
                        sum_totalamount += parseFloat(result[j].bill_amount);
                    }


                    $('#produ_details').append('<td>' + sum_totalamount + '</td>');
                    // $('#produ_details').append('<td>'+result[i-1].total_bill_amount+'</td>');
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






        $('#example1 tbody').on('click', '.mymodal', function() {

            var sales_id = $(this).attr("crid");
            console.log(sales_id);

            if (sales_id.startsWith("CC")) {
                update_cash_modal(sales_id);
            } else {
                var customer_name = $(this).attr("customer_name");
                var customer_id = $(this).attr("id")
                update_sale_modal(customer_name, customer_id, sales_id);
            }



        });
        $(".close").click(function() {
            $("#close").modal("hide");
        });

    });
</script>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sales Invoice</h4>
                <button type="button" class="btn-btn close" id="close" data-bs-dismiss="modal"></button>

            </div>



            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td align="left" colspan="3">
                            <a class="previous_call_details" style="width: 100px;height: auto;cursor:pointer;">
                                << Prev </a>
                        </td>
                        <td align="right" colspan="2">
                            <a class="next_call_details" style="width: 100px;height: auto;cursor:pointer;">
                                >> Next
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs1')" data-toggle="tab" href="#tabs1">Sales Details</a></td>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs2')" data-toggle="tab" href="#tabs2">Payment</a></td>
                        <!--<td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs3')" data-toggle="tab" href="#tabs3">Report</a></td>-->
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>

                    </tr>
                </table>
                <div id="tabs1" class="tabcontent">
                    <form method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Sales Invoice NO.</th>
                                <td id="sales_no" name="sales_no"></td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td id="date" name="date"></td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td id="customer_name"></td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td id="mobile_number"></td>
                            </tr>

                            <tr>
                                <th>Customer Address</th>
                                <td id="customer_address"></td>
                            </tr>
                            <tr>
                                <th>Boxes Arrived</th>
                                <td id="boxes_arrived"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Quality Name</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <th>Bill Amount</th>
                                                <!--<th>Total Amount</th>-->
                                            </tr>
                                        </thead>
                                        <tbody id="produ_details">

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered">

                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_date" required>
                                        <input type="hidden" id="popup_customer_id" name="popup_customer_id" value="" /><input type="hidden" id="popup_sales_id" name="popup_sales_id" value="" />
                                    </td>
                                    <td><input type="text" class="form-control" name="amount" required></td>
                                    <td><select name="payment_mode" class="form-control" required>
                                            <option value="">--Select Payment Mode--</option>
                                            <!-- <option value="neft">NEFT</option>
                                            <option value="online">Online</option>
                                            <option value="cash">Cash</option>
                                            <option value="dd">DD</option> -->
                                                 
                                <option value="NEFT">NEFT</option>

<option value="Gpay">Gpay(UPI)</option>

<option value="Cash">Cash</option>

<option value="Cheque">Cheque</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" name="add_payment" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
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
                                            <!--<th>Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="produ_details">
                                        <td><input type="hidden" name="id"></td>
                                        <?php
                                        $id = $_POST["id"];
                                        $select_qry = "SELECT * FROM sar_sales_payment ORDER BY id DESC ";
                                        $select_sql = $connect->prepare($select_qry);
                                        $select_sql->execute();
                                        $select_fetch = $select_sql->rowCount();
                                        $select_fetch = $select_sql->fetch(PDO::FETCH_ASSOC);
                                        echo "<tr>";
                                        echo "<td>" . $select_fetch['payment_id'] . "</td>";
                                        echo "<td>" . $select_fetch['payment_date'] . "</td>";
                                        echo "<td>" . $select_fetch['payment_mode'] . "</td>";
                                        echo "<td>" . $select_fetch['amount'] . "</td>";
                                        echo "<td>" . $select_fetch['balance'] . "</td>";
                                        echo "</tr>";
                                        ?>
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
                <h4 class="modal-title">Sales Invoice</h4>
                <button type="button" class="btn-btn close" id="close" data-bs-dismiss="modal"></button>

            </div>



            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td align="left" colspan="3">
                            <a class="previous_call_details" style="width: 100px;height: auto;cursor:pointer;">
                                << Prev </a>
                        </td>
                        <td align="right" colspan="2">
                            <a class="next_call_details" style="width: 100px;height: auto;cursor:pointer;">
                                >> Next
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs1')" data-toggle="tab" href="#tabs1">Sales Details</a></td>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs2')" data-toggle="tab" href="#tabs2">Payment</a></td>
                        <!--<td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs3')" data-toggle="tab" href="#tabs3">Report</a></td>-->
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>
                        <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>

                    </tr>
                </table>
                <div id="tabs1" class="tabcontent">
                    <form action="/view_sales_invoice_demo.php" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="date_cash"></td>
                            </tr>
                            <tr>
                                <th>Sales Invoice NO.</th>
                                <td id="cash_no"></td>
                            </tr>


                            <tr>
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Quality Name</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <th>Bill Amount</th>
                                                <!--<th>Total Amount</th>-->
                                            </tr>
                                        </thead>
                                        <tbody id="produ_details1">

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered">

                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_date" required> <input type="hidden" id="popup_customer_id" name="popup_customer_id" value="" /><input type="hidden" id="popup_sales_id" name="popup_sales_id" value="" /> </td>
                                    <td><input type="text" class="form-control" name="amount" required></td>
                                    <td><select name="payment_mode" class="form-control" required>
                                            <option value="">--Select Payment Mode--</option>
                                            <option value="neft">NEFT</option>
                                            <option value="online">Online</option>
                                            <option value="cash">Cash</option>
                                            <option value="dd">DD</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" name="add_payment" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
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

                                            <!--<th>Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="produ_details1">
                                        <td><input type="hidden" name="id"></td>
                                        <?php
                                        // $id=$_POST["id"];
                                        // $select_qry="SELECT * FROM sar_cash ORDER BY id DESC ";
                                        // $select_sql=$connect->prepare($select_qry);
                                        // $select_sql->execute();
                                        // $select_fetch=$select_sql->rowCount();
                                        // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
                                        // echo"<tr>";
                                        // echo"<td>".$select_fetch['payment_id']."</td>";
                                        // echo"<td>".$select_fetch['payment_date']."</td>";
                                        // echo"<td>".$select_fetch['payment_mode']."</td>";
                                        // echo"<td>".$select_fetch['amount']."</td>";
                                        // echo"<td>".$select_fetch['balance']."</td>";
                                        // echo"</tr>";

                                        echo "<tr>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "</tr>";
                                        ?>
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