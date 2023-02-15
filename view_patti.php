<?php require "header.php";
$date = date("Y-m-d");


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
if (isset($_REQUEST['patti_id']) != "") {
$patti_id = $_REQUEST["patti_id"];
} else {
$patti_id = "";
}   

if (isset($_REQUEST['pat_id']) != "") {
    $pat_id = $_REQUEST["pat_id"];
    } else {
    $pat_id = "";
    }   

if($req=="enabled")
{
    $update="UPDATE `sar_patti` SET is_active=0,nullify=1 WHERE pat_id=:pat_id";
    $update_sql= $connect->prepare($update);
    $update_sql->execute(array(':pat_id' => $pat_id));

$sqlen="select *,SUM(total_bill_amount) as tot,SUM(boxes_arrived) as box from sar_patti where pat_id='$pat_id'";
$exeen=mysqli_query($con,$sqlen);
$valen=mysqli_fetch_assoc($exeen);
$total=$valen['tot'];
$supname=$valen['supplier_name'];
$supid=$valen['supplier_id'];
$box=$valen['box'];
$type=$valen['type'];
$grpname=$valen['groupname'];

// $tray="SELECT * FROM trays where name='$supid' and type='$type' ORDER BY id DESC LIMIT 1 ";
// $tray1=$connect->prepare("$tray");
// $tray1->execute();
// $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);


$sqlbal="select * from payment where supplierid='$supid' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

// $exebal = $connect->prepare("$sqlbal");
// $exebal->execute(); 
// $valbal = $exebal->fetch(PDO::FETCH_ASSOC);
// $no=$valbal->rowCount();
// print_r($no);die();
  $paybal = $valbal["id"] + 1;
   $pay_id = "PAY" . date("Ym") . $paybal;   

   if($valbal['total']!=0){
    $op=$valbal['total']-$total;
   }
   else{
       $op=$total;
   }

//     if($type=="Small Tray"){
//    $small=$tray['smalltray']+$box;
//    $big=$tray['bigtray'];
//    $absmall=$tray['absmall']-$box;
//    $abbig=$tray['abbig'];
//    $inhand=$tray['inhand']+$box;
//    $ab_tray=$tray['ab_tray']-$box;
//     }
//     else if($type=="Big Tray"){
//         $small=$tray['smalltray'];
//         $big=$tray['bigtray']+$box;
//         $absmall=$tray['absmall'];
//         $abbig=$tray['abbig']-$box;
//         $inhand=$tray['inhand']+$box;
//         $ab_tray=$tray['ab_tray']-$box;
//     }
   
//    $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supid',$box,'$type','Patti Nullify','$box','$inhand','Admin','Supplier',$ab_tray,$small,$big,$absmall,$abbig)";
        //    print_r($supplier_insert_query);die();   
            // $supplier_sql=mysqli_query($con,$supplier_insert_query);
  

   $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid) values('$grpname','$pay_id','$date','$supname',0,$total,0,0,0,$op,'$supid','')";
    //  print_r($insbal."k");die(); ,smalltray,bigtray,inhand ,$small,$big,$inhand
     $exe=mysqli_query($con,$insbal);
   
           header("location:view_patti.php");
  
}

if($req=="disabled")
{
    $delete="UPDATE `sar_patti` SET is_active=1 WHERE patti_id=:patti_id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':patti_id' => $patti_id));
    header("location:view_patti.php");
}
?>

<div id="content-page" class="content-page">
    <h2>View Patti Details</h2>
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-card" style="padding:0">
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-3 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        Unsettled
                                    </a>
                                </li>
                                <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                        Settled
                                    </a>
                                </li>
                                <!--  <li class="col-md-4 p-0">-->
                                <!--   <a class="nav-link" data-toggle="pill" href="#supplier-wise-unsettled">-->
                                <!--      Supplier Wise Unsettled-->
                                <!--   </a>-->
                                <!--</li>-->
                                <!--<li class="col-md-3 p-0">-->
                                <!--   <a class="nav-link" data-toggle="pill" href="#chang-pwd">-->
                                <!--      Settled-->
                                <!--   </a>-->
                                <!--</li>-->

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                            <?php
    if (isset($_POST["add_payment"])) {
        $payment_date = $_POST['payment_date'];
        $amount = $_POST["amount"];
        $payment_mode = $_POST["payment_mode"];
        $popup_patti_id = $_POST["supplier_id"];
        $select_qry5 = "SELECT net_bill_amount FROM sar_patti WHERE patti_id='$popup_patti_id' GROUP BY patti_id";
    
        $sel_sql5 = $connect->prepare($select_qry5);
        $sel_sql5->execute();
        $sel_row5 = $sel_sql5->fetch(PDO::FETCH_ASSOC);
        $select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='$popup_patti_id' AND is_revoked is NULL GROUP BY supplier_id";
        
        $select_sql6 = $connect->prepare($select_qry6);
        $select_sql6->execute();
        $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    
        $balance = $sel_row5["net_bill_amount"] - $amount - $sel_row6["paid"];
    
        if ($balance >= 0) {
            $insert = "INSERT INTO `sar_patti_payment`
                  SET amount='$amount',
                  payment_date='$payment_date',
                  payment_mode='$payment_mode',
                  supplier_id='$popup_patti_id',
                  balance='$balance'";
    
            $sql_1 = $connect->prepare($insert);
            $sql_1->execute();

            $lastInsertId = $connect->lastInsertId();
            
            if($balance== 0){
                $insert = "UPDATE `sar_patti` SET payment_status= 0 WHERE patti_id='".$popup_patti_id."'";
                $sql_1 = $connect->prepare($insert);
                $sql_1->execute();
            }
                            $sel_qry = "SELECT * FROM `sar_patti`";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	            $sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
            	            $farmer_name=$sel_row["farmer_name"];
                            $balance = $bal_row["balance"] - $amount;
                            
                            $fin_trans_qry = "INSERT INTO financial_transactions SET 
                             date = '$payment_date',
                             debit= '$amount',
                             balance= '$balance',
                             description = 'Payment for Patti. Farmer Name: $farmer_name',
                             patti_id = '$popup_patti_id',
                             payment_id = '$lastInsertId',
                             ids='$popup_patti_id'
                             ";
           $res2=mysqli_query($con,$fin_trans_qry);
            
        }
    
    }
?>

                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" id="from" name="from" class="form-control">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit" name="submit"
                                        class="btn btn-primary">Display</button>
                                </div>
                                <div class="col">
                                    <button type="button" id="download" name="download"
                                        class="btn btn-danger">Download</button>
                                </div>
                                <div class="col">
                                    <input type="radio" name="view_patti_sts" class="view_patti_sts " value="1"
                                        checked />&nbsp;Active</label>
                                </div>
                                <div class="col">
                                    <input type="radio" name="view_patti_sts" class="view_patti_sts"
                                        value="0" />InActive</label>
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-md-2">
                                    <select class="form-control" id="group" name="group" style="width:210px;">
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
                                <div class="col-md-3">
                                    <select class="form-control" id="dropdown" name="dropdown" style="width:210px;">
                                        <option value="">Search Supplier Name </option>
                                        <?php
                        //     $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=0 GROUP BY supplier_name";
                        // 	$sel_sql= $connect->prepare($sel_qry);
            	        //     $sel_sql->execute();
            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	        //         echo '<option value="'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	        //    }
            	           ?>


                                    </select>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3"></div>
                            </div><br>

                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patti ID</th>
                                        <th>Supplier ID</th>
                                        <th>Farmer Name</th>
                                        <th>Supplier Name</th>
                                        <!-- <th>Quality Name</th> -->
                                        <th>Boxes Arrived</th>
                                        <th>Total Amount</th>
                                        <th>Total Deduction</th>
                                        <th>Net Patti</th>
                                        <!--<th>Net Payable</th>-->
                                        <!-- <th>Small Trays</th>
                                        <th>Big Trays</th> -->
                                        <th>Download</th>
                                        <th>Action</th>
                                        <th>Username</th>
                                        <!--<th>Updated Date</th>-->
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" id="from_settled" name="from_settled"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_settled" name="to_settled"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_settled" name="submit_settled"
                                        class="btn btn-primary">Display</button>
                                </div>
                                <div class="col">
                                    <button type="button" id="download_settled" name="download_settled"
                                        class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-md-2">
                                    <select class="form-control" id="grpname" name="grpname" style="width:210px;">
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
                                <div class="col-md-3">
                                    <select class="form-control" id="dropdown_settled" name="dropdown_settled"
                                        style="width:210px;">
                                        <option value="">Search Supplier Name </option>
                                        <?php
                        //     $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=0 GROUP BY supplier_name";
                        // 	$sel_sql= $connect->prepare($sel_qry);
            	        //     $sel_sql->execute();
            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	        //         echo '<option value="'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	        //    }
            	           ?>


                                    </select>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3"></div>
                            </div><br>
                            &nbsp;
                            <table id="settled" class="table table-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patti ID</th>
                                        <th>Farmer Name</th>
                                        <th>Supplier Id</th>
                                        <th>Supplier Name</th>
                                        <!-- <th>Quality Name</th> -->
                                        <th>Boxes Arrived</th>
                                        <th>Total Amount</th>
                                        <th>Total Deduction</th>
                                        <th>Net Patti</th>
                                        <!--<th>Net Payable</th>-->
                                        <!-- <th>Small Trays</th>
                                        <th>Big Trays</th>
                                        <th>Inhand Trays</th> -->
                                        <th>Download</th>
                                        <th>Username</th>
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
<?php
    require "footer.php";
?>
<script>
$("#grpname").on("change", function() {
    var grp = $(this).val();
    // alert(grp);
    $.ajax({
        type: "POST",
        url: "forms/ajax_request.php",
        data: {
            "action": "fetchgrp",
            "grp": grp
        },
        dataType: "json",
        success: function(result) {
            var len = result.length;
            // alert(result.length);
            $("#dropdown_settled").empty();
            $("#dropdown_settled").append('<option>Search Supplier Name</option>');
            for (var i = 0; i < len; i++) {
                $("#dropdown_settled").append('<option value=' + result[i].contact_person + '>' +
                    result[i].contact_person + '</option>');
            }
            // alert(result.contact_person);
        }
    })
});

$("#group").on("change", function() {
    var grp = $(this).val();
    // alert(grp);
    $.ajax({
        type: "POST",
        url: "forms/ajax_request.php",
        data: {
            "action": "fetchgrp",
            "grp": grp
        },
        dataType: "json",
        success: function(result) {
            var len = result.length;
            // alert(result.length);
            $("#dropdown").empty();
            $("#dropdown").append('<option>Search Supplier Name</option>');
            for (var i = 0; i < len; i++) {
                $("#dropdown").append('<option value=' + result[i].contact_person + '>' + result[i]
                    .contact_person + '</option>');
            }
            // alert(result.contact_person);
        }
    })
});
</script>
<script>
function update_model_data(patti_id, supid, farmer, patdate, patid, data_src) {
    if (data_src == 'settled') {
        $('#payment_form').hide();
    } else {
        $('#payment_form').show();
    }
    $.ajax({
        type: "POST",
        url: "forms/ajax_request_view.php",
        data: {
            "action": "view_patti_modal",
            "patti_id": patti_id,
            "supplier_id": supid,
            "farmer": farmer,
            "patdate": patdate,
            "patid": patid,
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
                if (result[i].hasOwnProperty("patti_id")) {
                    sum_totalamount += parseFloat(result[i].net_payable);
                    $('#produ_details').append('<tr>');
                    $('#produ_details').append('<td>' + result[i].quality_name + '</td>');
                    $('#produ_details').append('<td>' + result[i].quantity + '</td>');
                    $('#produ_details').append('<td>' + result[i].rate + '</td>');
                    $('#produ_details').append('<td>' + result[i].commision + '</td>');
                    $('#produ_details').append('<td>' + result[i].lorry_hire + '</td>');
                    $('#produ_details').append('<td>' + result[i].cooli + '</td>');
                    $('#produ_details').append('<td>' + result[i].total_deduction + '</td>');
                    $('#produ_details').append('<td>' + result[i].net_payable + '</td>');
                    $('#produ_details').append('</tr>');
                    $("#boxes_arrived").html(result[i].boxes_arrived);
                    $("#patti_id").html(result[i].patti_id);
                    $("#date").html(result[i].patti_date);
                    $("#supplier_address").html(result[i].supplier_address);
                    $("#mobile_number").html(result[i].mobile_number);
                    $("#lorry_no").html(result[i].lorry_no);
                    $("#commision").html(result[i].commision);
                    $("#lorry_hire").html(result[i].lorry_hire);
                    $("#box_charge").html(result[i].box_charge);
                    $("#cooli").html(result[i].cooli);
                    $("#total_deduction").html(result[i].total_deduction);
                    $("#supplier_name").html(result[i].supplier_name);
                    $("#net_bill_amount").html(result[i].net_bill_amount);
                    // $("#net_payable").html(result[i].net_payable);
                } else {
                    if (result[i].is_revoked) {
                        $('#revoke_table').append('<tr id=revoke_row_id_' + result[i].id + '>');
                        $('#revoke_table').append('<td>' + result[i].id + '</td>');
                        $('#revoke_table').append('<td>' + result[i].payment_date + '</td>');
                        $('#revoke_table').append('<td>' + result[i].payment_mode + '</td>');
                        $('#revoke_table').append('<td>' + result[i].amount + '</td>');
                        $('#revoke_table').append('</tr>');
                    } else {
                        $('#sar_patti_payment_table').append('<tr id=revoke_row_id_' + result[i].id + '>');
                        $('#sar_patti_payment_table').append('<td>' + result[i].id + '</td>');
                        $('#sar_patti_payment_table').append('<td>' + result[i].payment_date + '</td>');
                        $('#sar_patti_payment_table').append('<td>' + result[i].payment_mode + '</td>');
                        $('#sar_patti_payment_table').append('<td>' + result[i].amount + '</td>');
                        $('#sar_patti_payment_table').append('<td>' + result[i].balance + '</td>');
                        $('#sar_patti_payment_table').append(
                            '<td><a class="tabs_click tablinks" onclick=revoke_payment(this,' + result[
                                i].id + ',"' + data_src +
                            '") data-toggle="tab" href="#tabs1">Revoke</a></td>');
                        $('#sar_patti_payment_table').append('</tr>');
                    }
                }
            }
            $('#produ_details').append('<tr>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td></td>');
            $('#produ_details').append('<td><b>Total Amount</b></td>');
            $('#produ_details').append('<td>' + sum_totalamount + '</td>');
            $('#produ_details').append('</tr>');
        }
    });
}

function revoke_payment(obj, sar_patti_payment_id, data_src) {
    var myKeyVals = {
        "id": sar_patti_payment_id,
        "action": "revoke_patti_payment",
        "data_src": data_src
    };
    $.ajax({
        type: 'POST',
        url: "forms/ajax_request.php?action=revoke_patti_payment",
        data: myKeyVals,
        dataType: "json",
        success: function(resultData) {
            update_model_data(resultData[0]['supplier_id'], data_src)
        }
    });
}
$(document).ready(function() {
    var user_role = '<?=$user_role?>';
    var username = '<?=$username?>';
    var table = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "forms/ajax_request.php?action=view_patti&req=enabled&username=" + username +
                '&user_role=' + user_role,
            "type": "POST"
        },
        "columns": [{
                "data": "patti_date"
            },
            {
                "data": "patti_id"
            },
            // { "data": "pat_id" },
            {
                "data": "supplier_id"
            },
            {
                "data": "farmer_name"
            },
            {
                "data": "supplier_name"
            },
            // { "data": "quality_name" },
            {
                "data": "boxes_arrived"
            },
            {
                "data": "totalbillamount"
            },
            {
                "data": "total_deduction"
            },
            {
                "data": "net_bill_amount"
            },
            // { "data": "net_payable" },
            // {
            //     "data": "small"
            // },
            // {
            //     "data": "big"
            // },
            {
                "data": "id"
            },
            {
                "data": "is_active"
            },
            {
                "data": "updated_by"
            }

        ],
        columnDefs: [{
                targets: 0,
                render: function(data, type, row) {
                    return row.patti_date;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    return row.patti_id;
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    return row.supplier_id;
                    // "-"+row.pat_id
                }
            },
            {
                targets: 3,
                render: function(data, type, row) {
                    return row.farmer_name;
                }
            },
            {
                targets: 4,
                render: function(data, type, row) {
                    return '<a class="mymodal" style="color:#f55989" pat_id="' + row.pat_id +
                        '" supplier_name="' + row.patti_id + '" supid="' + row.supplier_id +
                        '" farmer="' + row.farmer_name + '" patdate="' + row.patti_date + '">' +
                        row.supplier_name + '</a>';
                }
            },
            // {
            //     targets: 5,
            //     render: function(data, type, row) {
            //         return row.quality_name;
            //     }
            // },
            {
                targets: 5,
                render: function(data, type, row) {
                    return row.boxes_arrived;
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
                    return row.total_deduction;
                }
            },
            {
                targets: 8,
                render: function(data, type, row) {
                    return row.net_bill_amount;
                }
            },
            // {
            //     targets: 7,
            //     render: function(data, type, row) {
            //         return row.net_payable;
            //     }
            // },
            // {
            //     targets: 9,
            //     render: function(data, type, row) {
            //         return row.small;
            //     }
            // },
            // {
            //     targets: 10,
            //     render: function(data, type, row) {
            //         return row.big;
            //     }
            // },
            {
                targets: 9,
                render: function(data, type, row) {
                    return '<a href="download_patti.php?supplier_id=' + row.supplier_id +
                        '&date=' + row.patti_date + '&farmer=' + row.farmer_name +
                        '&patti_id=' + row.pat_id + '" target="_blank">PDF</a>';
                    // return'<a href="download_patti.php" target="_blank">PDF</a>';

                }
            },
            {
                targets: 10,
                render: function(data, type, row) {
                    var htm = '';
                    if (row.is_active == 1 && row.paid == null) {
                        htm =
                            '<a class="label label-success" href="GeneratPatti.php?req=edit&patti_id=' +
                            row.pat_id +
                            '"><span class="bx bxs-edit" >&nbsp Edit</span></a>&nbsp';

                        htm = htm +
                            '<?php if($user_role=="admin") { ?><a href="view_patti.php?req=enabled&pat_id=' +
                            row.pat_id +
                            '"><button type="button" class="btn btn-danger">Nullify</button></a> <?php }?>';
                    } else if (row.is_active == 0) {

                        htm = '-';
                    }
                    return htm;
                }
            },
            {
                targets: 11,
                render: function(data, type, row) {
                    return row.updated_by;
                }
            }
        ]
    });

    $("#dropdown").on("change", function() {
        var dropdown = $("#dropdown").val();
        var group = $("#group").val();
        // alert(dropdown+group);
        if (dropdown != "") {
            table.ajax.url("forms/ajax_request.php?action=view_patti&req=enabled&dropdown=" + dropdown +
                "&grp=" + group).load();
            table.ajax.reload();
        } else {
            table.ajax.url("forms/ajax_request.php?action=view_patti&req=enabled").load();
            table.ajax.reload();
        }
    });
    $(".view_patti_sts").on("click", function() {
        var is_active = $(this).val();
        if (is_active == 1) {
            table.ajax.url(
                "forms/ajax_request.php?action=view_patti&req=enabled&is_active=1&username=" +
                username + '&user_role=' + user_role).load();
            table.ajax.reload();
        } else {
            //   table.ajax.url("forms/ajax_request.php?action=view_patti_nullify_records").load();
            table.ajax.url(
                "forms/ajax_request.php?action=view_patti&req=disabled&is_active=0&username=" +
                username + '&user_role=' + user_role).load();
            table.ajax.reload();
        }
    });
    $("#submit").on("click", function() {
        var from = $("#from").val();
        var to = $("#to").val();
        if (from != "" && to != "") {
            table.ajax.url("forms/ajax_request.php?action=view_patti&from=" + from + '&to=' + to)
        .load();
            table.ajax.reload();
        } else {
            table.ajax.url("forms/ajax_request.php?action=view_patti").load();
            table.ajax.reload();
        }
    });
    $("#download").on("click", function() {
        var from = $("#from").val();
        var to = $("#to").val();
        window.location = "pattireport.php?from=" + from + '&to=' + to;
    })
    var table1 = $('#settled').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax": {
            "url": "forms/ajax_request.php?action=view_patti_settled&username=" + username +
                '&user_role=' + user_role,
            "type": "POST"
        },
        "columns": [{
                "data": "patti_date"
            },
            {
                "data": "pat_id"
            },
            {
                "data": "supplier_id"
            },
            {
                "data": "farmer_name"
            },
            {
                "data": "supplier_name"
            },
            // {
            //     "data": "quality_name"
            // },
            {
                "data": "boxes_arrived"
            },
            {
                "data": "totalbillamount"
            },
            {
                "data": "total_deduction"
            },
            {
                "data": "net_bill_amount"
            },
            // {
            //     "data": "small"
            // },
            // {
            //     "data": "big"
            // },
            // {
            //     "data": "inhand"
            // },
            {
                "data": "id"
            },
            {
                "data": "updated_by"
            }

        ],
        columnDefs: [

            {
                targets: 0,
                render: function(data, type, row) {
                    return row.patti_date;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    return row.pat_id;
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    return row.farmer_name;
                }
            },
            {
                targets: 3,
                render: function(data, type, row) {
                    return row.supplier_id;
                }
            },
            // {
            //     targets: 4,
            //     render: function(data, type, row) {
            //         // return row.farmer_name;
            //     }
            // },
            {
                targets: 4,
                render: function(data, type, row) {
                    // return '<a class="mymodal"  supplier_name="' + row.patti_id + '" >' + row
                    //     .supplier_name + '</a>';
                    return '<a class="mymodal" style="color:#f55989" pat_id="' + row.pat_id +
                        '" supplier_name="' + row.patti_id + '" supid="' + row.supplier_id +
                        '" farmer="' + row.farmer_name + '" patdate="' + row.patti_date + '">' +
                        row.supplier_name + '</a>';
                }
            },
            // {
            //     targets: 4,
            //     render: function(data, type, row) {
            //         return row.quality_name;
            //     }
            // },
            {
                targets: 5,
                render: function(data, type, row) {
                    return row.boxes_arrived;
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
                    return row.total_deduction;
                }
            },
            {
                targets: 8,
                render: function(data, type, row) {
                    return row.net_bill_amount;
                }
            },
            // {
            //     targets: 7,
            //     render: function(data, type, row) {
            //         return row.net_payable;
            //     }
            // },
            // {
            //     targets: 9,
            //     render: function(data, type, row) {
            //         return row.small;
            //     }
            // }, {
            //     targets: 10,
            //     render: function(data, type, row) {
            //         return row.big;
            //     }
            // }, {
            //     targets: 11,
            //     render: function(data, type, row) {
            //         return row.inhand;
            //     }
            // },
            {
                targets: 9,
                render: function(data, type, row) {
                    return '<a href="download_patti.php?supplier_id=' + row.supplier_id +
                        '&date=' + row.patti_date + '&farmer=' + row.farmer_name +
                        '&patti_id=' + row.pat_id + '" target="_blank">PDF</a>';
                    //  return '<a target="_blank" href="download_patti.php?patti_id='+row.patti_id+'" >PDF</a>';

                }
            },
            {
                targets: 10,
                render: function(data, type, row) {
                    return row.updated_by;
                }
            }

        ]
    });

    $("#dropdown_settled").on("change", function() {
        var dropdown_settled = $("#dropdown_settled").val();
        var grp = $("#grpname").val();
        if (dropdown_settled != "") {
            table1.ajax.url("forms/ajax_request.php?action=view_patti_settled&dropdown_settled=" +
                dropdown_settled + "&grp=" + grp).load();
            table1.ajax.reload();
        } else {
            table1.ajax.url("forms/ajax_request.php?action=view_patti_settled").load();
            table1.ajax.reload();
        }
    });
    $("#submit_settled").on("click", function() {
        var from_settled = $("#from_settled").val();
        var to_settled = $("#to_settled").val();
        if (from_settled != "" && to_settled != "") {
            table1.ajax.url("forms/ajax_request.php?action=view_patti_settled&from_settled=" +
                from_settled + '&to_settled=' + to_settled).load();
            table1.ajax.reload();
        } else {
            table1.ajax.url("forms/ajax_request.php?action=view_patti_settled").load();
            table1.ajax.reload();
        }
    });
    $("#download_settled").on("click", function() {
        var from_settled = $("#from_settled").val();
        var to_settled = $("#to_settled").val();
        window.location = "pattisettled_report.php?from=" + from_settled +
            '&to=' + to_settled;
    });


    $('#example tbody').on('click', '.mymodal', function() {
        var patti_id = $(this).attr("supplier_name");
        var supid = $(this).attr("supid");
        var farmer = $(this).attr("farmer");
        var patdate = $(this).attr("patdate");
        var patid = $(this).attr("pat_id");
        $("#myModal").modal("show");
        $("#supplier_name").val(patti_id);
        $("#supplier_id").val(patti_id);
        // alert(patid);
        update_model_data(patti_id, supid, farmer, patdate, patid, 'unsettled')
    });

    $('#settled tbody').on('click', '.mymodal', function() {
        var patti_id = $(this).attr("supplier_name");
        $("#myModal").modal("show");
        var patti_id = $(this).attr("supplier_name");
        var supid = $(this).attr("supid");
        var farmer = $(this).attr("farmer");
        var patdate = $(this).attr("patdate");
        var patid = $(this).attr("pat_id");
    var patid = $(this).attr("pat_id");
        // alert(patid)
        update_model_data(patti_id, supid, farmer, patdate, patid, 'unsettled')
        // update_model_data(patti_id, 'settled')
    });
    $(".close").click(function() {
        $("#myModal").modal("hide");
    });
    $(".close").click(function() {
        $("#myModal1").modal("hide");
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
});
</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Patti Details</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs1" class="tabcontent">
                    <table class="table table-bordered">
                        <tr>
                            <th>Patti ID</th>
                            <td id="patti_id" name="patti_id"></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td id="date" name="date"></td>
                        </tr>
                        <tr>
                            <th>Supplier Name</th>
                            <td id="supplier_name"></td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td id="mobile_number"></td>
                        </tr>
                        <tr>
                            <th>Supplier Address</th>
                            <td id="supplier_address"></td>
                        </tr>
                        <tr>
                            <th>Boxes Arrived</th>
                            <td id="boxes_arrived"></td>
                        </tr>
                        <tr>
                            <th>Lorry NO.</th>
                            <td id="lorry_no"></td>
                        </tr>
                        <tr>
                            <th>Commision</th>
                            <td id="commision"></td>
                        </tr>
                        <tr>
                            <th>Lorry Hire</th>
                            <td id="lorry_hire"></td>
                        </tr>
                        <tr>
                            <th>Box Charge</th>
                            <td id="box_charge"></td>
                        </tr>
                        <tr>
                            <th>Cooli</th>
                            <td id="cooli"></td>
                        </tr>
                        <tr>
                            <th>Total Deduction</th>
                            <td id="total_deduction"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Quality</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Comission</th>
                                            <th>Lorry Hire</th>
                                            <th>Cooli</th>
                                            <th>Deduction</th>
                                            <th>Amount</th>
                                            <!--<th>Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="produ_details">



                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- <tr>
                                    <th><b>Net Patti</b></th>
                                    <td id="net_bill_amount" style="font-size:25px;color: #f55989;text-align:center;"></td>
                                 </tr>
                                  -->

                    </table>
                    <!-- <form id="form1" action="" method="POST">
                        <input type="hidden" name="supplier_id" id="supplier_id" />
                        <div id="payment_form">
                            <h4>Payment</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_date" required>
                                            <input type="hidden" id="popup_supplier_id" name="popup_supplier_id" value="" /><input type="hidden" id="popup_patti_id" name="popup_patti_id" value="" />
    
                                        </td>
    
                                        <td><input type="text" class="form-control" name="amount" required>
                                        </td>
    
                                        <td><select name="payment_mode" class="form-control" required>
    
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
                        </div>
                        </form> -->
                </div>

                <div id="tabs2" class="tabcontent">
                    <!-- <p style="font-size:25px;">Payment History</p>
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

                                    <tbody id="sar_patti_payment_table">

                                        
                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    </table> -->

                </div>

                <div id="tabs2" class="tabcontent">
                    <!-- <p style="font-size:25px;">Revoke History</p>
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

                                </table> -->

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