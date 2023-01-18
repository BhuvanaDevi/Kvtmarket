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
    
    if (isset($_REQUEST['patti_id']) != "") {

    $patti_id = $_REQUEST["patti_id"];

} else {

    $patti_id = "";

}
if($req=="enabled")
{
    $update="UPDATE `sar_patti` SET is_active=0 WHERE patti_id=:patti_id";
    $update_sql= $connect->prepare($update);
    $update_sql->execute(array(':patti_id' => $patti_id));
    $insert="INSERT INTO sar_patti_nullify_records(patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,payment_status,is_active,updated_by,supplier_id )
SELECT patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,payment_status,is_active,updated_by,supplier_id 
FROM 
   sar_patti
WHERE
   patti_id='".$patti_id."'";
    $insert_sql= $connect->prepare($insert);
        $insert_sql->execute();
    header("location:view_patti.php");
    // if($insert)
    // {
    // $delete="DELETE FROM `sar_patti` WHERE patti_id=:patti_id";
    // $delete_sql= $connect->prepare($delete);
    // $delete_sql->execute(array(':patti_id' => $patti_id));
    // }
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
             <div class="iq-card"style="padding:0">
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
            
            if($balance== 0){
                $insert = "UPDATE `sar_patti` SET payment_status= 0 WHERE patti_id='".$popup_patti_id."'";
                $sql_1 = $connect->prepare($insert);
                $sql_1->execute();
            }
            
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
            <button type="button" id="submit" name="submit" class="btn btn-primary">Display</button>
        </div>
        <div class="col">
            <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
        </div>
        <div class="col">
            <input type="radio" name="view_patti_sts" class="view_patti_sts " value="1" checked/>&nbsp;Active</label>
        </div>
        <div class="col">
            <input type="radio" name="view_patti_sts" class="view_patti_sts" value="0" />InActive</label>
        </div>
    </div>
    <select class="form-control" id="dropdown" name="dropdown" style="width:200px;" >
                      <option value="">Search Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	           }
            	           ?>
                      
    
                    </select>
                    <br>
                    &nbsp;
        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patti ID</th>
                    <th>Supplier Name</th>
                    <th>Boxes Arrived</th>
                    <th>Total Amount</th>
                    <th>Total Deduction</th>
                    <th>Net Amount</th>
                    <th>Inhand Trays</th>
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
                <input type="date" value="<?= $date ?>" id="from_settled" name="from_settled" class="form-control">
            </div>
            <div class="col">
                <input type="date" value="<?= $date ?>" id="to_settled" name="to_settled" class="form-control">
            </div>
            <div class="col">
                 <button type="button" id="submit_settled" name="submit_settled" class="btn btn-primary">Display</button>
            </div>
            <div class="col">
                  <button type="button" id="download_settled" name="download_settled" class="btn btn-danger">Download</button>
            </div>
        </div>
       
                    <select class="form-control" id="dropdown_settled" name="dropdown_settled" style="width:200px;">
                      <option value="">Search Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=0 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	           }
            	           ?>
                      
    
                    </select>
                    <br>
                    &nbsp;
                <table id="settled" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patti ID</th>
                            <th>Supplier Name</th>
                            <th>Boxes Arrived</th>
                            <th>Total Amount</th>
                            <th>Total Deduction</th>
                            <th>Net Amount</th>
                            <th>Inhand Trays</th>
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
    function update_model_data(patti_id, data_src){
        if(data_src == 'settled'){
            $('#payment_form').hide();
        }else{
            $('#payment_form').show();
        }
        $.ajax({
            type:"POST",
            url:"forms/ajax_request_view.php",
            data:{"action":"view_patti_modal","patti_id":patti_id, "data_src": data_src},
            dataType:"json",
            success:function(result){
                $("#produ_details").html("");
                var i=0;
                $('#sar_patti_payment_table').html("");
                $('#revoke_table').html("");
                var sum_totalamount = 0;
                for(i=0;i<result.length;i++)
                {
                    if(result[i].hasOwnProperty("patti_id")){
                        sum_totalamount += parseFloat(result[i].bill_amount);
                        $('#produ_details').append('<tr>');
                        $('#produ_details').append('<td>'+result[i].quality_name+'</td>');
                        $('#produ_details').append('<td>'+result[i].quantity+'</td>');
                        $('#produ_details').append('<td>'+result[i].rate+'</td>');
                        $('#produ_details').append('<td>'+result[i].bill_amount+'</td>');
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
                    }else{
                        if(result[i].is_revoked){
                            $('#revoke_table').append('<tr id=revoke_row_id_'+result[i].id+'>');
                            $('#revoke_table').append('<td>'+result[i].id+'</td>');
                            $('#revoke_table').append('<td>'+result[i].payment_date+'</td>');
                            $('#revoke_table').append('<td>'+result[i].payment_mode+'</td>');
                            $('#revoke_table').append('<td>'+result[i].amount+'</td>');
                            $('#revoke_table').append('</tr>');
                        }else{
                            $('#sar_patti_payment_table').append('<tr id=revoke_row_id_'+result[i].id+'>');
                            $('#sar_patti_payment_table').append('<td>'+result[i].id+'</td>');
                            $('#sar_patti_payment_table').append('<td>'+result[i].payment_date+'</td>');
                            $('#sar_patti_payment_table').append('<td>'+result[i].payment_mode+'</td>');
                            $('#sar_patti_payment_table').append('<td>'+result[i].amount+'</td>');
                            $('#sar_patti_payment_table').append('<td>'+result[i].balance+'</td>');
                            $('#sar_patti_payment_table').append('<td><a class="tabs_click tablinks" onclick=revoke_payment(this,'+result[i].id+',"'+data_src+'") data-toggle="tab" href="#tabs1">Revoke</a></td>');
                            $('#sar_patti_payment_table').append('</tr>');
                        }
                    }
                }
                $('#produ_details').append('<tr>');
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td></td>');
                $('#produ_details').append('<td><b>Total Amount</b></td>');
                $('#produ_details').append('<td>' + sum_totalamount + '</td>');
                $('#produ_details').append('</tr>');
            }
        });
    }
    
    function revoke_payment(obj, sar_patti_payment_id, data_src){
        var myKeyVals = { "id": sar_patti_payment_id,"action": "revoke_patti_payment", "data_src": data_src};
        $.ajax({
          type: 'POST',
          url: "forms/ajax_request.php?action=revoke_patti_payment",
          data: myKeyVals,
          dataType: "json",
          success: function(resultData) { update_model_data(resultData[0]['supplier_id'], data_src) }
        });
    }
    $(document).ready(function(){
        var user_role='<?=$user_role?>';
        var username='<?=$username?>';
        var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_patti&req=enabled&username="+username+'&user_role='+user_role,
                "type": "POST"
            },
            "columns": [
                { "data": "patti_date" },
                { "data": "patti_id" },
                { "data": "supplier_name" },
                { "data": "boxes_arrived" },
                { "data": "totalbillamount" },
                { "data": "total_deduction" },
                { "data": "net_bill_amount" },
                { "data": "inhand_sum" },
                { "data": "id" },
                { "data": "is_active" },
                { "data": "updated_by" }
                
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
                        return row.patti_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" supplier_name="' + row.patti_id + '" >' + row.supplier_name + '</a>';
                    }
                },
                 {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.boxes_arrived;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.totalbillamount;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.total_deduction;
                    }
                },
                 {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.net_bill_amount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return row.inhand_sum;
                    }
                },
                 {
                    targets: 8,
                    render: function(data, type, row) {
                        return '<a href="download_patti.php?patti_id='+row.patti_id+'" target="_blank">PDF</a>';
                        // return'<a href="download_patti.php" target="_blank">PDF</a>';
                
                    }
                },
                {
                    targets: 9,
                    render: function(data, type, row) {
                        var htm ='';
                        if(row.is_active==1 && <?=$amount == null?>){
                             htm = '<a class="label label-success" href="GeneratPatti.php?req=edit&patti_id='+row.patti_id+'"><span class="bx bxs-edit" >&nbsp Edit</span></a>&nbsp';
                        
                             htm = htm + '<?php if($user_role=="admin") { ?><a href="view_patti.php?req=enabled&patti_id='+row.patti_id+'"><button type="button" class="btn btn-danger">Nullify</button></a> <?php }?>';
                        } else if(row.is_active==0){
                            
                           htm = '-';
                        }
                        return htm;
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
        
        $("#dropdown").on("change",function(){
            var dropdown=$("#dropdown").val();
            if(dropdown!=""){
                table.ajax.url("forms/ajax_request.php?action=view_patti&req=enabled&dropdown="+dropdown).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_patti&req=enabled").load();
                table.ajax.reload();
            }
        });
        $(".view_patti_sts").on("click",function(){
            var is_active=$(this).val();
            if(is_active==1){
                table.ajax.url("forms/ajax_request.php?action=view_patti&req=enabled&is_active=1&username="+username+'&user_role='+user_role).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_patti_nullify_records").load();
                table.ajax.reload();
            }
        });
        $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_patti&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_patti").load();
                table.ajax.reload();
            }
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_patti_report.php?from="+from+'&to='+to;
        })
        var table1 = $('#settled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_patti_settled&username="+username+'&user_role='+user_role,
                "type": "POST"
            },
            "columns": [
                { "data": "patti_date" },
                { "data": "patti_id" },
                { "data": "supplier_name" },
                { "data": "boxes_arrived" },
                { "data": "bill_amount" },
                { "data": "total_deduction" },
                { "data": "net_bill_amount" },
                { "data": "inhand_sum" },
                { "data": "id" },
                { "data": "updated_by" }
                
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
                        return row.patti_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<a class="mymodal" supplier_name="' + row.patti_id + '" >' + row.supplier_name + '</a>';
                    }
                },
                 {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.boxes_arrived;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.totalbillamount;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return row.total_deduction;
                    }
                },
                 {
                    targets: 6,
                    render: function(data, type, row) {
                        return row.net_bill_amount;
                    }
                },
                {
                    targets: 7,
                    render: function(data, type, row) {
                        return row.inhand_sum;
                    }
                },
                 {
                    targets: 8,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="download_patti.php?patti_id='+row.patti_id+'" >PDF</a>';
            
                    }
                },
                {
                    targets: 9,
                    render: function(data, type, row) {
                        return row.updated_by;
                    }
                }
                
             ]
        });
        
        $("#dropdown_settled").on("change",function(){
            var dropdown_settled=$("#dropdown_settled").val();
            if(dropdown_settled!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_patti_settled&dropdown_settled="+dropdown_settled).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_patti_settled").load();
                table1.ajax.reload();
            }
        });
        $("#submit_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to_settled").val();
            if(from_settled!="" && to_settled!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_patti_settled&from_settled="+from_settled+'&to_settled='+to_settled).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_patti_settled").load();
                table1.ajax.reload();
            }
        });
        $("#download_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to_settled").val();
            window.location="download_patti_settled_report.php?from_settled="+from_settled+'&to_settled='+to_settled;
        });
        
        
        $('#example tbody').on('click', '.mymodal', function (){
            var patti_id=$(this).attr("supplier_name");
            $( "#myModal" ).modal( "show" );
            $("#supplier_name").val(patti_id);
            $("#supplier_id").val(patti_id);
            update_model_data(patti_id, 'unsettled')
         });
         
         $('#settled tbody').on('click', '.mymodal', function (){
            var patti_id=$(this).attr("supplier_name");
            $( "#myModal" ).modal( "show" );
            $("#supplier_name").val(patti_id);
            $("#supplier_id").val(patti_id);
            update_model_data(patti_id, 'settled')
         });
         $( ".close" ).click(function() {
            $( "#myModal" ).modal( "hide" );
        });
        $( ".close" ).click(function() {
            $( "#myModal1" ).modal( "hide" );
        });
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
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
                                    <td id="mobile_number" ></td>
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
                                 <tr>
                                    <th><b>Net Bill Amount</b></th>
                                    <td id="net_bill_amount" style="font-size:25px;color: #f55989;text-align:center;"></td>
                                </tr>
    
                        </table>
                        <form id="form1" action="" method="POST">
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
                        </form>
                    </div>
                
                    <div id="tabs2" class="tabcontent">
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
                                            <!--<th>Total Amount</th>-->

                                        </tr>

                                    </thead>

                                    <tbody id="sar_patti_payment_table">

                                        
                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    </table>

                    </div>
                    
                    <div id="tabs2" class="tabcontent">
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

                                            
                                            <!--<th>Total Amount</th>-->

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

<div class="modal fade" id="myModal1" role="dialog">

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
    
                                    <td id="mobile_number" ></td>
    
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
                                 <tr>
    
                                    <th><b>Net Bill Amount</b></th>
    
                                    <td id="net_bill_amount" style="font-size:25px;color: #f55989;text-align:center;"></td>
    
                                </tr>
    
                        </table>
                        <!--<form id="form1" action="" method="POST">-->
                        <!--    <input type="hidden" name="supplier_id" id="supplier_id" />-->
                        <!--    <table class="table table-bordered">-->
    
                        <!--        <thead>-->
    
                        <!--            <h4>Payment</h4>-->
    
                        <!--        </thead>-->
    
                        <!--        <tbody>-->
    
                        <!--            <tr>-->
    
                        <!--                <td><input type="date" class="form-control datepicker" name="payment_date" required>-->
    
                        <!--                    <input type="hidden" id="popup_supplier_id" name="popup_supplier_id" value="" /><input type="hidden" id="popup_patti_id" name="popup_patti_id" value="" />-->
    
                        <!--                </td>-->
    
                        <!--                <td><input type="text" class="form-control" name="amount" required>-->
                        <!--                </td>-->
    
                        <!--                <td><select name="payment_mode" class="form-control" required>-->
    
                        <!--                        <option value="">--Select Payment Mode--</option>-->
    
                        <!--                        <option value="neft">NEFT</option>-->
    
                        <!--                        <option value="online">Online</option>-->
    
                        <!--                        <option value="cash">Cash</option>-->
    
                        <!--                        <option value="dd">DD</option>-->
    
                        <!--                    </select>-->
                        <!--                    </td>-->
    
                        <!--            </tr>-->
    
                        <!--            <tr>-->
    
                        <!--                <td></td>-->
    
                        <!--                <td><input type="submit" name="add_payment" class="btn btn-primary" value="Submit">-->
                        <!--                </td>-->
    
                        <!--                <td></td>-->
    
                        <!--            </tr>-->
    
                        <!--        </tbody>-->
    
                        <!--</table>-->
                        <!--</form>-->
                    </div>
                
                    <div id="tabs2" class="tabcontent">
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
                                            <!--<th>Total Amount</th>-->

                                        </tr>

                                    </thead>

                                    <tbody id="sar_patti_payment_table">

                                        
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