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
if (isset($_REQUEST['sales_no']) != "") {
    $sales_no = $_REQUEST["sales_no"];
} else{
    $sales_no = "";
}
if (isset($_REQUEST['cash_no']) != "") {

    $cash_no = $_REQUEST["cash_no"];
} else{
    $cash_no = "";
}
if (isset($_REQUEST['name']) != "") {
    $name = $_REQUEST["name"];
} else {
    $name = "";
}
if($req=="enabled")
{
    $delete="UPDATE `sar_sales_invoice` SET is_active=0 WHERE sales_no=:sales_no";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':sales_no' => $sales_no));
    header("location:view_sales_invoice.php");
}

if($req=="disabled")
{
    $delete="UPDATE `sar_sales_invoice` SET is_active=1 WHERE sales_no=:sales_no";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':sales_no' => $sales_no));
    header("location:view_sales_invoice.php");
}
if($req=="active")
{
    $active_cash_qry="UPDATE `sar_cash_carry` SET is_active=0 WHERE cash_no=:cash_no";
    $active_cash_sql= $connect->prepare($active_cash_qry);
    $active_cash_sql->execute(array(':cash_no' => $cash_no));
    header("location:view_sales_invoice.php");
}
if($req=="inactive")
{
    $active_cash_qry="UPDATE `sar_cash_carry` SET is_active=1 WHERE cash_no=:cash_no";
    $active_cash_sql= $connect->prepare($active_cash_qry);
    $active_cash_sql->execute(array(':cash_no' => $cash_no));
    header("location:view_sales_invoice.php");
}

if(isset($_POST["add_balance_payment"])){
    $delivery_challan_qry="SELECT id FROM sar_balance_payment ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $balance_payment_id = "BPAY_".date("Ym")."0".$Last_id;  

    $payment_balance_date = $_POST['payment_balance_date'];
    $open_balance_amount = $_POST["open_balance_amount"];
    $payment_balance_mode = $_POST["payment_balance_mode"];
    
   // $id = $_POST["popup_customer_id"];
    $popup_balance_id = $_POST["popup_balance_id"];
    $popup_name = $_POST["popup_name"];
   // $customer_name=$_POST['customer_name'];
    $opening_balance_qry = "SELECT * FROM sar_opening_balance WHERE balance_id='$popup_balance_id' AND category='Customer' GROUP BY balance_id";

    $opening_balance_sql = $connect->prepare($opening_balance_qry);
    $opening_balance_sql->execute();
    $opening_balance_row = $opening_balance_sql->fetch(PDO::FETCH_ASSOC);
    
    $balance_payment_qry = "SELECT amount FROM sar_balance_payment WHERE balance_id='$popup_balance_id' GROUP BY balance_id";
    $balance_payment_sql = $connect->prepare($balance_payment_qry);
    $balance_payment_sql->execute();
    $balance_payment_row = $balance_payment_sql->fetch(PDO::FETCH_ASSOC);
    
//      $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$popup_sales_id' GROUP BY sales_no";
// 	$select_sql2=$connect->prepare($select_qry2);
// 	$select_sql2->execute();
// 	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
// 	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];

    $balance = $opening_balance_row["amount"] - $open_balance_amount - $balance_payment_row['amount'];

    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_balance_payment`

              SET amount='$open_balance_amount',
              payment_id='$balance_payment_id',
              payment_date='$payment_balance_date',
              payment_mode='$payment_balance_mode',
              balance_id='$popup_balance_id',
              balance='$balance',
              name='$popup_name'
              
              ";
        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
      // $lastInsertId = $connect->lastInsertId();
    }
    $total_qry = "SELECT *, sum(amount) as totalamount FROM sar_balance_payment WHERE balance_id='$popup_balance_id' GROUP BY balance_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);


    $open_amount_qry = "SELECT *, sum(amount) as totalbillamount FROM sar_opening_balance WHERE balance_id='$popup_balance_id' GROUP BY balance_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);


    if ($open_amount_row['totalbillamount'] <= $total_row['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        // $date = date("Y/m/d");

        $delete = "UPDATE `sar_opening_balance` SET payment_status=1  where balance_id ='$popup_balance_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_opening_balance WHERE balance_id='$popup_balance_id'";
        $sel_sql3 = $connect->prepare($select_qry3);
        $sel_sql3->execute();
        $sel_row3 = $sel_sql3->fetchAll();
        // echo var_dump($sel_row3);
        // exit;
        foreach ($sel_row3 as $sel) {
            $add_balance_query = "INSERT INTO `sar_cash_carry` SET
           cash_no = '" . $sel['balance_id'] . "',
           date = '" . $sel['date'] . "',
           customer_name = '" . $sel['name'] . "',
           total_bill_amount = '" . $sel['amount'] . "',
           updated_by = '". $sel['updated_by'] ."',
           is_active=1
           ";
            $res_balance = mysqli_query($con, $add_balance_query);
            /// echo $add_sales_query;
        }
    }
}
if (isset($_POST["add_payment"])) {

//print ($_POST);
    $delivery_challan_qry="SELECT id FROM sar_sales_payment ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $payment_id = "PAY_".date("Ym")."0".$Last_id;  

    $payment_date = $_POST['payment_date'];
    //$payment_id=$_POST['payment_id'];
    $amount = $_POST["amount"];
    $payment_mode = $_POST["payment_mode"];
    //$payment_id = "pay_".uniqid();

    // payment_id='$payment_id',

    $id = $_POST["popup_customer_id"];

    $popup_sales_id = $_POST["popup_sales_id"];
    $customer_name=$_POST['customer_name'];


    $select_qry4 = "SELECT *, sum(bill_amount) as totalbillamount FROM sar_sales_invoice WHERE sales_no='$popup_sales_id' GROUP BY sales_no";
    $sel_sql4 = $connect->prepare($select_qry4);
    $sel_sql4->execute();
    $sel_row4 = $sel_sql4->fetch(PDO::FETCH_ASSOC);

    $select_qry6 = "SELECT sum(amount) as paid FROM sar_sales_payment WHERE customer_id='$popup_sales_id' AND is_revoked is NULL GROUP BY customer_id";
    $select_sql6 = $connect->prepare($select_qry6);
    $select_sql6->execute();
    $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    
     $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$popup_sales_id' GROUP BY sales_no";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];

    $balance = $sel_row4["totalbillamount"] - $amount - $total_discount_on_sales - $sel_row6['paid'];

    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_sales_payment`

              SET amount='$amount',
              payment_id='$payment_id',
              payment_date='$payment_date',
              payment_mode='$payment_mode',
              customer_id='$popup_sales_id',
              customer_name='$customer_name',
              balance='$balance'
              
              ";



        $sql_1 = $connect->prepare($insert);

        $sql_1->execute();

        $lastInsertId = $connect->lastInsertId();

    }

    $balance_qry="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
    $balance = $bal_row["balance"] + $amount;
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
                     date = '$payment_date',
                     credit = $amount,
                     balance = $balance,
                     description = 'Credit sales invoice payment.Customer Name : $customer_name',
                     invoice_id = '$popup_sales_id',
                     payment_id = '$lastInsertId'
                     ";
   $res2=mysqli_query($con,$fin_trans_qry);


    $select_qry1 = "SELECT *, sum(amount) as totalamount FROM sar_sales_payment WHERE customer_id='$popup_sales_id' AND is_revoked is NULL GROUP BY customer_id ";

    $sel_sql1 = $connect->prepare($select_qry1);

    $sel_sql1->execute();

    $sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);





    $select_qry2 = "SELECT *, sum(bill_amount) as totalbillamount FROM sar_sales_invoice WHERE sales_no='$popup_sales_id' GROUP BY sales_no ORDER BY id DESC ";

    $sel_sql2 = $connect->prepare($select_qry2);

    $sel_sql2->execute();

    $sel_row2 = $sel_sql2->fetch(PDO::FETCH_ASSOC);


    if (($sel_row2['totalbillamount'] - $total_discount_on_sales) <= $sel_row1['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        $date = date("Y/m/d");

        $delete = "UPDATE `sar_sales_invoice` SET payment_status=1,updated_date='$date',credit_type='Settled'  where sales_no ='$popup_sales_id'";

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
           customer_address = '" . $sel['customer_address'] . "',
           boxes_arrived = '" . $sel['boxes_arrived'] . "',
           quality_name = '" . $sel['quality_name'] . "',
           quantity = '" . $sel['quantity'] . "',
           rate = '" . $sel['rate'] . "',
           bill_amount = '" . $sel['bill_amount'] . "',
           customer_id = '" . $sel['id'] . "',
           total_bill_amount = '" . $sel['total_bill_amount'] . "',
           payment = '" . $sel['payment_id'] . "',
           is_active=1
           ";
            $res = mysqli_query($con, $add_sales_query);
            /// echo $add_sales_query;
        }
    }
        $select_qry3= "SELECT sum(inhand) as inhand_sum,name FROM `tray_transactions` WHERE category='Customer' AND name='$name'";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	  
    header("Location: ./view_sales_invoice.php"); /* Redirect browser */

}

?>

<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div><h2>View Sales Invoice</h2></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-card7" style="padding:0">
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-2 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        UnSettled
                                    </a>
                                </li>
                                <li class="col-md-2 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                        Settled
                                    </a>
                                </li>
                                <li class="col-md-2 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#open_balance_tab">
                                        OB
                                    </a>
                                </li>
                                <li class="col-md-1 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#all">
                                        All 
                                    </a>
                                </li>
                                <li class="col-md-5 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#customer_wise_report">
                                        Customer Wise Unsettled 
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
                                
                                <div class="col">
                                    <label><input type="radio" name="view_sales_invoice_sts" class="view_sales_invoice_sts" value="1" checked/>&nbsp;Active</label>
                                </div>
                               <div class="col">
                                    <label><input type="radio" name="view_sales_invoice_sts" class="view_sales_invoice_sts" value="0" />&nbsp;Inactive</label>
                                </div>
                            </div>
                                 
                               <select class="form-control" id="dropdown" name="dropdown" style="width:200px;">
                                <option value="">Search Customer Name </option>
                                   <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
                        	           }
                        	           ?>
                        </select>
                        &nbsp;
                            <table id="example" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Credit ID</th>
                                        <th>Cus Name</th>
                                        <th>Mobile Number</th>
                                        <th>Bill Amount</th>
                                        <th>Payment</th>
                                        <th>Balance</th>
                                        <th>Discount</th>
                                        <th>Inhand Trays</th>
                                        <th>UserName</th>
                                        <th>Action</th>
                                        <th>Download</th>
                                    </tr>

                                </thead>

                            </table>

                        </div>
                        
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                              <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_cash" name="from_cash">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_cash" name="to_cash" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_cash" name="submit_cash" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_cash" name="download_cash" class="btn btn-danger">Download</button>
                                </div>
                                <div class="col">
                                    <label><input type="radio" name="view_cash_sts" class="view_cash_sts" value="1" checked/>&nbsp;Active</label>
                                </div>
                               <div class="col">
                                    <label><input type="radio" name="view_cash_sts" class="view_cash_sts" value="0" />&nbsp;Inactive</label>
                                </div>
                            </div>
                            
                            
                            <select class="form-control" id="dropdown_cash" name="dropdown_cash" style="width:200px;">
                                <option value="">Search Customer Name </option>
                                   <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=1 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
                        	           }
                        	           ?>
                              
                        </select>
                        <br>
                       
                            <table id="example1" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Cus Name</th>
                                        <th>Bill Amount</th>
                                        <th>Username</th>
                                        <th>Category</th>
                                        <th>Inhand Trays</th>
                                        <th>Action</th>
                                        <th>Download</th>
                                        
                                    </tr>
                                </thead>
                            </table>
                        </div>
                         <div class="tab-pane fade" id="open_balance_tab" role="tabpanel">
                             <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_ob" name="from_ob">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_ob" name="to_ob" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_ob" name="submit_ob" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_ob" name="download_ob" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                                
                            <table id="customer_open_balance" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Username</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                         <div class="tab-pane fade" id="all" role="tabpanel">
                                <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_all" name="from_all">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_all" name="to_all" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_all" name="submit_all" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_all" name="download_all" class="btn btn-danger">Download</button>
                                </div>
                                <div class="col">
                                     
                                </div>
                               <!-- <div class="col">-->
                               <!--     <label><input type="radio" name="view_cash_sts" class="view_cash_sts active" value="1" checked/>&nbsp;Active</label>-->
                               <!-- </div>-->
                               <!--<div class="col">-->
                               <!--     <label><input type="radio" name="view_cash_sts" class="view_cash_sts active" value="0" checked/>InActive</label>-->
                               <!-- </div>-->
                            </div>
                            <select class="form-control" id="dropdown_all" name="dropdown_all" style="width:200px;">
                                <option value="">Search Customer Name </option>
                                   <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_payment` GROUP BY customer_id";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"].'">'.$sel_row["customer_name"].'</option>';
                        	           }
                        	           ?>
                              
                        </select>
                        &nbsp;
                            <table id="example2" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Cus Name</th>
                                        <th>Category</th>
                                        <th>Bill Amount</th>
                                        <th>Payment</th>
                                        <th>Discount</th>
                                        <th>Balance</th>
                                        <th>Credit Type</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                         <div class="tab-pane fade" id="customer_wise_report" role="tabpanel">
                             &nbsp;
                                <div class="col">
                                     <button type="button" id="download_customer" name="download_customer" class="btn btn-danger">Download</button>
                                </div>
                                <br>
                                
                            <table id="example3" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Net Invoice Balance</th>
                                        <th>Net OB Balance</th>
                                        <th>Net LTD Balance</th>
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
        var myKeyVals = { "waiver_date": $('#waiver_date').val(),"action": "add_waiver", "waiver_amount": $('#waiver_amount').val(), "sales_no_id":$('#sales_no_id').val() };
        $.ajax({
          type: 'POST',
          url: "forms/ajax_request.php?action=add_waiver",
          data: myKeyVals,
          dataType: "json",
          success: function(resultData) {
              window.location.reload();
              //update_sale_modal(customer_name, resultData[0]['customer_id'], resultData[0]['customer_id'], data_src) 
              }
          
        });
    }
    //$.fn.dataTableExt.sErrMode = 'throw';

    function update_sale_modal(customer_name, customer_id, sales_id, data_src) {
        if(data_src == 'settled'){
            $('#payment_form').hide();
            $('#waiver_form').hide();
        }else{
            $('#payment_form').show();
            $('#waiver_form').show();
        }
        $('#waiver_form').hide();
        $("#waiver_click").on("click",function(){
            
            $('#waiver_form').show();
    
    });
        $("#myModal").modal("show");
        $("#customer_name").val(customer_name);
        var payload = {
            "action": "view_sales_modal",
            "customer_name": customer_name,
            "customer_id": customer_id,
            "sales_id": sales_id,
            "data_src": data_src
        }
        console.log(customer_name, customer_id, sales_id, data_src)
        $('#sales_no_id').val(sales_id);
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: payload,
            dataType: "json",
            success: function(data) {
                //console.log(data);
                $('#tabs1 #popup_customer_id').val(customer_id);
                $('#tabs1 #popup_customer_name').val(customer_name);

                $('#tabs1 #popup_sales_id').val(sales_id);
                var result = data.data;
                var result1 = data['cash'];
                
                $("#produ_details").html("");
                $("#revoke_table").html("");
                $("#tabs2 #produ_details").html("");

                if (result1) {
                    var k = 0;
                    for (k = 0; k < result1.length; k++) {
                        //console.log(result1)
                        if(result1[k].is_revoked){
                           $('#revoke_table').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td></tr>');
                        }else{
                            $('#tabs2 #produ_details').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td><td>' + result1[k].balance + '</td><td><a class="tabs_click tablinks" onclick=revoke_payment(this,'+result1[k].id+',"'+data_src+'") data-toggle="tab" data-customer_name='+customer_name+' href="#tabs1">Revoke</a></td></tr>');
                        }
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
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td><b>Discount</b></td>');
                $('#produ_details').append('<td>' + data['total_discount_on_sales'] + '</td>');
                $('#produ_details').append('</tr>');
                
                $('#produ_details').append('<tr>');
                $("#customer_name").html(result[i - 1].customer_name);
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td><b>Total Amount</b></td>');

                var sum_totalamount = 0;
                for (j = 0; j < result.length; j++) {
                    sum_totalamount += parseFloat(result[j].bill_amount);
                }
                
                sum_totalamount = sum_totalamount - parseInt(data['total_discount_on_sales'])

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
                $("#produ_details1").html("");
                var k = 0;
                for (k = 0; k < result.length; k++) {
                    if(result[k].hasOwnProperty("cash_no")){
                    $('#produ_details1').append('<tr>');
                    $('#produ_details1').append('<td>' + result[k].quality_name + '</td>');
                    $('#produ_details1').append('<td>' + result[k].quantity + '</td>');
                    $('#produ_details1').append('<td>' + result[k].rate + '</td>');
                    $('#produ_details1').append('<td>' + result[k].bill_amount + '</td>');

                    $('#produ_details1').append('</tr>');
                }
                }
                //$('#produ_details1').html('');
                $("#date_cash").html(result[k - 1].date);

                $('#produ_details1').append('<tr>');
                $("#cash_no").html(result[k - 1].cash_no);
                $('#produ_details1').append('<td></td>');
                $('#produ_details1').append('<td></td>');
                $('#produ_details1').append('<td><b>Total Amount</b></td>');
                var sum_totalamount = 0;

                for (j = 0; j < result.length; j++) {

                    sum_totalamount += parseFloat(result[j].bill_amount);

                }

                $('#produ_details').append('<td>' + sum_totalamount + '</td>');
                $('#produ_details1').append('<td>' + result[k - 1].total_bill_amount + '</td>');
                $('#produ_details1').append('</tr>');

            }

        });
    }
    
    function revoke_payment(obj, sar_sales_payment_id, data_src){
        var myKeyVals = { "id": sar_sales_payment_id,"action": "revoke_sales_payment", "data_src": data_src};
        var customer_name = $(obj).attr("customer_name");
        $.ajax({
          type: 'POST',
          url: "forms/ajax_request.php?action=revoke_sales_payment",
          data: myKeyVals,
          dataType: "json",
          success: function(resultData) {
              window.location.reload();
              //update_sale_modal(customer_name, resultData[0]['customer_id'], resultData[0]['customer_id'], data_src) 
              }
          
        });
    }
    
    function update_open_balance_modal(balance_id) {
    
        $("#myModal_balance").modal("show");
        $("#balance_id").val(balance_id);
       
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {"action": "view_open_balance_modal", "balance_id": balance_id,"name":name},
            dataType: "json",
            success: function(data) {
                //console.log(data);
                // $('#tabs1 #popup_customer_id').val(customer_id);
                // $('#tabs1 #popup_customer_name').val(customer_name);

                $('#tabs1 #popup_balance_id').val(balance_id);
                $('#tabs1 #popup_name').val(name);
                var result = data.data;
                var result1 = data['cash'];
                
                // console.log(result);
                // console.log(result1);
                $("#open_balance").html("");
                
                // if (result1) {
                //     var k = 0;
                //     for (k = 0; k < result1.length; k++) {
                //         console.log(result1)
                //         if(result1[k].is_revoked){
                //           $('#revoke_table').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td></tr>');
                //         }else{
                //              $('#tabs2 #produ_details').append('<tr><td>' + result1[k].payment_id + '</td><td>' + result1[k].payment_date + '</td><td>' + result1[k].payment_mode + '</td><td>' + result1[k].amount + '</td><td>' + result1[k].balance + '</td><td><a class="tabs_click tablinks" onclick=revoke_payment(this,'+result1[k].id+',"'+data_src+'") data-toggle="tab" data-customer_name='+customer_name+' href="#tabs1">Revoke</a></td></tr>');
                //         }
                //     }
                // }
                if(result1){
                    var i = 0;
                    for (i = 0; i < result1.length; i++) {
                        $('#open_balance').append('<tr>');
                        $('#open_balance').append('<td>' + result1[i].payment_id + '</td>');
                        $('#open_balance').append('<td>' + result1[i].payment_date + '</td>');
                        $('#open_balance').append('<td>' + result1[i].payment_mode + '</td>');
                        $('#open_balance').append('<td>' + result1[i].open_balance_amount + '</td>');
                        $('#open_balance').append('<td>' + result1[i].balance + '</td>');
                        $('#open_balance').append('</tr>');
                        // console.log(result1[i]);
                    }
                }
                $("#ob_date").html(result[0].date);
                $("#balance_id").html(result[0].balance_id);
                $("#group_name").html(result[0].group_name);
                $("#name").html(result[0].name);
                $("#category").html(result[0].category);
                $("#amount").html(result[0].amount);
                
                // $('#open_balance').append('<tr>');
                // $("#balance_id").html(result.balance_id);
                // $('#open_balance').append('</tr>');
                // $('#produ_details').append('<td></td>');
                // $('#produ_details').append('<td></td>');
                // $('#produ_details').append('<td><b>Discount</b></td>');
                // $'#produ_details').append('<td>' + data['total_discount_on_sales'] + '</td>');
                // $('#produ_details').append('</tr>');
                
                // $('#produ_details').append('<tr>');
                // $("#customer_name").html(result[i - 1].customer_name);
                // $('#produ_details').append('<td></td>');
                // $('#produ_details').append('<td></td>');
                // $('#produ_details').append('<td><b>Total Amount</b></td>');
                // var sum_totalamount = 0;
                // for (j = 0; j < result.length; j++) {

                //     sum_totalamount += parseFloat(result[j].bill_amount);

                // }
                
                // sum_totalamount = sum_totalamount - parseInt(data['total_discount_on_sales'])



                // $('#produ_details').append('<td>' + sum_totalamount + '</td>');

                // $('#produ_details').append('</tr>');

            }



        });

    }
    $(document).ready(function() {
        var user_role='<?=$user_role?>';
        var username='<?=$username?>';
        var table = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "ajax": {
                "url": "forms/ajax_request.php?action=view_sales_invoice&req=enabled&username="+username+'&user_role='+user_role,

                "type": "POST"
            },
            "columns": [
                { "data": "date"},
                {"data": "sales_no" },
                {"data": "customer_name" },
                {"data": "mobile_number" },
                {"data": "total_bill_amount" },
                {"data": "paid_amount"},
                {"data": "balance"},
                {"data": "waiver_discount"},
                {"data": "inhand_sum"},
                {"data": "updated_by"},
                {"data": "is_active"},
                {"data": "id"}
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
                        return '<a class="mymodal" customer_name="' + row.customer_name + '" id="' + row.id + '" crid="' + row.sales_no + '" >' + row.customer_name + '</a>';

                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.mobile_number;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.total_bill_amount;
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
                        return row.waiver_discount;
                    }
                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                        return row.inhand_sum;
                    }
                },
                {
                    targets: 9,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                },
                {
                    targets: 10,
                    render: function(data, type, row) {
                        var htm ='';
                        if(row.is_active == 1 && row.paid_amount == null){
                             htm = '<a class="label label-success" href="sales_invoice2.php?req=edit&sales_no='+row.sales_no+'"><span class="bx bxs-edit" >&nbsp Edit</span></a>&nbsp';
                        
                             htm = htm + '<?php if($user_role=="admin") { ?>
                                <a href="view_sales_invoice.php?req=enabled&sales_no='+row.sales_no+'"><button type="button" class="btn btn-danger">Nullify</button></a> <?php }?>';
                        } else if(row.is_active == 0){
                             
                             htm = '<a href="view_sales_invoice.php?req=disabled&sales_no='+row.sales_no+'" ><button type="button" class="btn btn-success">Active</button></a>';
                        }
                        return htm;
                    }
                },
                {
                    targets: 11,
                    render: function(data, type, row) {
                        return '<button class="btn btn-success"><a target="_blank" onclick="var w = window.open(\'download_sales_invoice.php?sales_no='+row.sales_no+'\',\'mywin\'); w.print();" >Print</a></button>';
                    }
                }
            ]
        });
        
        $("#dropdown").on("change",function(){
            var dropdown=$("#dropdown").val();
            if(dropdown!=""){
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice&req=enabled&dropdown="+dropdown).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice&req=enabled").load();
                table.ajax.reload();
            }
        });
        $(".view_sales_invoice_sts").on("click",function(){
            var is_active=$(this).val();
            if(is_active==1){
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice&req=enabled&is_active=1&username="+username+'&user_role='+user_role).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice&req=disabled&is_active=0&username="+username+'&user_role='+user_role).load();
                table.ajax.reload();
            }
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_credit_report.php?from="+from+'&to='+to;
        });
        $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_sales_invoice").load();
                table.ajax.reload();
            }
        });
         $.fn.dataTable.ext.errMode = 'none';
        var table1 = $('#example1').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "order": [[0, "desc"]],
            "ajax": {
                "url": "forms/ajax_request.php?action=view_cash_carry&req=active&username="+username+'&user_role='+user_role,

                "type": "POST"
            },
            
            "columns": [
                {"data": "date"},
                {"data": "cash_no"},
                {"data": "customer_name"},
                {"data": "total_bill_amount"},
                {"data": "updated_by"},
                {"data": "cash_no"},
                {"data": "inhand_sum"},
                {"data": "id"},
                {"data": "id"}
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
                        if(row.cash_no.startsWith("CC") || row.cash_no.startsWith("CR")){
                            return '<a href="#" class="mymodal1" cash_no="' + row.cash_no + '" id="' + row.id + '"crid="' + row.cash_no + '">' + row.cash_no + '</a>';
                        }else {
                            return row.cash_no;
                        }
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" customer_name="' + row.customer_name + '" id="' + row.customer_id + '" crid="' + row.cash_no + '" >' + row.customer_name + '</a>';
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.total_bill_amount;
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
                        return row.cash_no.startsWith("CC") ? "Cash":"Credit";
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.inhand_sum;
                    }
                },
                {
                    targets: 7,
                    //"targets": -1,
                    //"data": null,
                    render: function(data, type, row) {
                        if(row.is_active && row.is_active==1 && row.cash_no.startsWith("CC")){
                           var htm1 = '<?php if($user_role=="admin") { ?>
                                <a href="view_sales_invoice.php?req=active&cash_no='+row.cash_no+'"><button type="button" class="btn btn-danger">Nullify</button></a> <?php }?>';
                        } else if(row.is_active && row.is_active==0 && row.cash_no.startsWith("CC")){
                            var htm1 = '-';
                        }
                        return htm1;
                    }
                },
                {
                    targets: 8,
                    //"targets": -1,
                    //"data": null,
                    render: function(data, type, row) {
                    if(row.cash_no.startsWith("CC")){
                        return '<button class="btn btn-success"><a target="_blank" onclick="var w = window.open(\'download_cash_carry.php?cash_no='+row.cash_no+'\',\'mywin\'); w.print();" >Print</a></button>';
                    
                    }else if(row.cash_no.startsWith("CR")){
                      return '<button class="btn btn-success"><a target="_blank" onclick="var w = window.open(\'download_sales_invoice_settled.php?cash_no='+row.sa+'\',\'mywin\'); w.print();" >Print</a></button>';
                      }
                    }
                }
            ]
        });
        
        $("#dropdown_cash").on("change",function(){
            var dropdown_cash=$("#dropdown_cash").val();
            if(dropdown_cash!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry&req=active&dropdown_cash="+dropdown_cash).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry&req=active").load();
                table1.ajax.reload();
            }
        });
         $("#download_cash").on("click",function(){
            
            window.location="download_cash_carry_report.php?from_cash="+from_cash+'&to_cash='+to_cash;
        });
        $("#submit_cash").on("click",function(){
            var from_cash=$("#from_cash").val();
            var to_cash=$("#to_cash").val();
            if(from_cash!="" && to_cash!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry&from_cash="+from_cash+'&to_cash='+to_cash).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry").load();
                table1.ajax.reload();
            }
        });
        $(".view_cash_sts").on("click",function(){
            var is_active=$(this).val();
            if(is_active==1){
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry&req=active&is_active=1&username="+username+'&user_role='+user_role).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_cash_carry&req=inactive&is_active=0&username="+username+'&user_role='+user_role).load();
                table1.ajax.reload();
            }
        });
        
        var table2 = $('#customer_open_balance').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "ajax": {
                "url": "forms/ajax_request.php?action=view_cust_open_balance&username="+username+'&user_role='+user_role,

                "type": "POST"
            },
            "columns": [
                { "data": "date"},
                {"data": "balance_id" },
                {"data": "name" },
                {"data": "amount" },
                {"data": "updated_by" },
                {"data": "id"}
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
                        return row.balance_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" name="' + row.name + '" id="' + row.id + '" >' + row.name + '</a>';

                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.amount;
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
                        if(row.paid_amount == null){
                           return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_id" href="#" balance_id="'+row.balance_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a><a class="dropdown-item" href="view_sales_invoice.php?req=delete&id='+row.id+'" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a></div></div>';
                        }else{
                            return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item mymodal_balance" id="mymodal_id" href="#" balance_id="'+row.balance_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
                        }
                    }
                }
            ]
        });
        $("#download_ob").on("click",function(){
            var from_ob=$("#from_ob").val();
            var to_ob=$("#to_ob").val();
            window.location="download_ob.php?from_ob="+from_ob+'&to_ob='+to_ob;
        });
        $("#submit_ob").on("click",function(){
            var from_ob=$("#from_ob").val();
            var to_ob=$("#to_ob").val();
            if(from_ob!="" && to_ob!=""){
                table2.ajax.url("forms/ajax_request.php?action=view_cust_open_balance&from_ob="+from_ob+'&to_ob='+to_ob).load();
                table2.ajax.reload();
            } else {
                table2.ajax.url("forms/ajax_request.php?action=view_cust_open_balance").load();
                table2.ajax.reload();
            }
        });
        
        var table3 = $('#example2').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "order": [[0, "desc"]],
            "ajax": {
                "url": "forms/ajax_request.php?action=view_cash_carry_all&req=active&username="+username+'&user_role='+user_role,
                "type": "POST"
            },
            "columns": [
                {"data": "Date"},
                {"data": "id"},
                {"data": "name"},
                {"data": "id"},
                {"data": "Bill_Amount"},
                {"data": "payment"},
                {"data": "waiver_discount"},
                {"data": "id"},
                {"data": "credit_type"}
            ],
            columnDefs: [
                
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.Date;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return  row.customer_id ;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.name;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.customer_id.startsWith("CR") ? "Credit":"Cash";
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.Bill_Amount;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        if (row.id.startsWith("CC")) {
                            return '-';
                        }else{
                            return row.payment;
                        }
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        if (row.id.startsWith("CC")) {
                            return '-';
                        }else{
                            return row.waiver_discount;
                        }
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        if (row.id.startsWith("CC")) {
                            return '-';
                        }else{
                            return row.balance;
                        }
                    }
                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                        return row.credit_type;
                    }
                }
            ]
        });
        
        $("#download_all").on("click",function(){
            
            window.location="download_all.php?from_all="+from_all+'&to_all='+to_all;
        });
        $("#submit_all").on("click",function(){
            var from_all=$("#from_all").val();
            var to_all=$("#to_all").val();
            if(from_all!="" && to_all!=""){
                table3.ajax.url("forms/ajax_request.php?action=view_cash_carry_all&from_all="+from_all+'&to_all='+to_all).load();
                table3.ajax.reload();
            } else {
                table3.ajax.url("forms/ajax_request.php?action=view_cash_carry_all").load();
                table3.ajax.reload();
            }
        });
        $("#dropdown_all").on("change",function(){
            var dropdown_all=$("#dropdown_all").val();
            if(dropdown_all!=""){
                table3.ajax.url("forms/ajax_request.php?action=view_cash_carry_all&dropdown_all="+dropdown_all).load();
                table3.ajax.reload();
            } else {
                table3.ajax.url("forms/ajax_request.php?action=view_cash_carry_all").load();
                table3.ajax.reload();
            }
        });
        
        //$.fn.dataTableExt.sErrMode = 'none';
        var table4 = $('#example3').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[0, "desc"]],
            "ajax": {
            "url": "forms/ajax_request.php?action=view_customer_wise_report",
                "type": "POST"
            },
            "columns": [
                {"data": "customer_name"},
                {"data": "net_inv_balance"},
                {"data": "net_ob_balance"},
                {"data": "balance"}
            ],
            columnDefs: [
                // {
                //     targets: 0,
                //     render: function(data, type, row) {
                //         return  row.date;
                //     }
                // },
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return  row.customer_name ;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return  row.net_inv_balance ;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return  row.net_ob_balance ;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                            return row.balance;
                    }
                }
            ]
        });
        
        $("#download_customer").on("click",function(){
            
            window.location="download_customer_wise_unsettled_report.php";
        });
        
        $('#example tbody').on('click', '.mymodal', function() {
            var customer_name = $(this).attr("customer_name");
            var customer_id = $(this).attr("id");
            var sales_id = $(this).attr("crid");
            console.log(customer_name, customer_id, sales_id);

            $("#myModal").modal("show");
            $("#customer_name").val(customer_name);
            update_sale_modal(customer_name, customer_id, sales_id, 'unsettled');
        });
        
        $('#example1 tbody').on('click', '.mymodal1', function() {
            var sales_id = $(this).attr("crid");
            if (sales_id.startsWith("CC")) {
                update_cash_modal(sales_id);
            } else {
                var customer_name = $(this).attr("customer_name");
                var customer_id = $(this).attr("id")
                update_sale_modal(customer_name, customer_id, sales_id, 'settled');
            }
        });
        
        $('#customer_open_balance tbody').on('click', '#mymodal_id', function() {
            var balance_id = $(this).attr("balance_id");
            //console.log(balance_id);
            
            $("#myModal_balance").modal("show");
            $("#balance_id").val(balance_id);
            
            update_open_balance_modal(balance_id);
            
        });
        $(".close").click(function() {
            $("#myModal_balance").modal("hide");
        });
        $(".close").click(function() {
            $("#myModal").modal("hide");
        });

        $(".close").click(function() {
            $("#myModal1").modal("hide");
        });
        
    });
    
</script>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sales Invoice</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
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
                        <div id="payment_form">
                            <table class="table table-bordered" >
                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_date" required>
                                        <input type="hidden" id="popup_customer_id" name="popup_customer_id" value="" /><input type="hidden" id="popup_sales_id" name="popup_sales_id" value="" />
                                        <input type="hidden" id="popup_customer_name" name="customer_name" value="" />
                                    </td>
                                    <!--<td><input type="hidden" class="form-control" name="payment_id"></td>-->
                                    <td><input type="text" class="form-control" name="amount" placeholder="Enter Payment Amount" required></td>

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
                        </div>
                        </form>
                        <a href="#" id="waiver_click">Waiver</a>
                        <form method="POST">
                        <div id="waiver_form">
                            <table class="table table-bordered">
                                <thead>
                                    <h4>Waiver</h4>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                        <input type="date" value="<?= $date ?>" class="form-control datepicker" name="waiver_date"  id="waiver_date" required>
    
                                            <input type="hidden" id="sales_no_id" name="sales_no_id" value="" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="waiver_amount" id="waiver_amount" placeholder="Enter Discount Amount" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="button" name="add_waiver" onclick=add_waiver_cash() class="btn btn-primary" value="Submit">
                                        </td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>
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
                                            <th>Action</th>
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
            <div id="tabs2" class="tabcontent">
                       <table class="table table-bordered">
                        <tr>
                            <td colspan="2">
                                <h4>Revoke History</h4>
                                <table class="table table-bordered">
                                    <thead>
                                            <th>Revoke ID</th>
                                            <th>Payment Date</th>
                                            <th>Payment Mode</th>
                                            <th>Revoked Amount(-)</th>
                                            
                                            <!--<th>Total Amount</th>-->
                                        </th>
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

<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cash and Carry</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form action="" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="date_cash"></td>
                            </tr>
                            <tr>
                                <th>Cash&Carry NO.</th>
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
                        <!--<table class="table table-bordered">-->
                        <!--    <thead>-->
                        <!--        <h4>Payment</h4>-->
                        <!--    </thead>-->
                        <!--    <tbody>-->
                        <!--        <tr>-->
                        <!--            <td><input type="date" class="form-control datepicker" name="payment_date" required> <input type="hidden" id="popup_customer_id" name="popup_customer_id" value="" /><input type="hidden" id="popup_sales_id" name="popup_sales_id" value="" /> </td>-->
                        <!--            <td><input type="text" class="form-control" name="amount" required></td>-->
                        <!--            <td><select name="payment_mode" class="form-control" required>-->
                        <!--                    <option value="">--Select Payment Mode--</option>-->
                        <!--                    <option value="neft">NEFT</option>-->
                        <!--                    <option value="online">Online</option>-->
                        <!--                    <option value="cash">Cash</option>-->
                        <!--                    <option value="dd">DD</option>-->
                        <!--                </select></td>-->
                        <!--        </tr>-->
                        <!--        <tr>-->
                        <!--            <td></td>-->
                        <!--            <td><input type="submit" name="add_payment" class="btn btn-primary" value="Submit"></td>-->
                        <!--            <td></td>-->
                        <!--        </tr>-->
                        <!--    </tbody>-->
                        <!--</table>-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn close" id="close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal_balance" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Open Balance</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <form action="" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="ob_date"></td>
                            </tr>
                            <tr>
                                <th>Balance NO.</th>
                                <td id="balance_id"></td>
                            </tr>
                            <tr>
                                <th>Group Name</th>
                                <td id="group_name"></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td id="name"></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td id="category"></td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td id="amount"></td>
                            </tr>
                        </table>
                        
                        <table class="table table-bordered">
                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_balance_date" required> <input type="hidden" id="popup_name" name="popup_name" value="" /><input type="hidden" id="popup_balance_id" name="popup_balance_id" value="" /> 
                                    </td>
                                    <td><input type="text" class="form-control" name="open_balance_amount" required>
                                    </td>
                                    <td>
                                        <select name="payment_balance_mode" class="form-control" required>
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
                                    <td><input type="submit" name="add_balance_payment" class="btn btn-primary" value="Submit"></td>
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
                                            <!--<th>Action</th>-->
                                            <!--<th>Total Amount</th>-->

                                        </tr>
                                    </thead>
                                    <tbody id="open_balance">
                                        
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