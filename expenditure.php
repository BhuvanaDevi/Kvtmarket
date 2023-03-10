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

if(isset($_REQUEST['exp_no'])!=""){
    $exp_no=$_REQUEST["exp_no"];
} else {
    $exp_no="";
}
if($req=="delete")
{
    $delete="DELETE FROM sar_expenditure WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    
    
    $delete_fin_qry="DELETE FROM financial_transactions WHERE exp_id='$exp_no'";
    $delete_fin_sql= $connect->prepare($delete_fin_qry);
    $delete_fin_sql->execute();
    header("location:expenditure.php");
    
}
 ?>

 
 
 <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                     <div class="col-lg-12">
                 <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">View Expenditure</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                    &nbsp;&nbsp;
            <!-- <div class="row col-md-12">
                <div class="col-md-2" style="margin-bottom:20px">
                    <input type="date" value="<?= $date ?>" id="from" name="from" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                </div>
                <div class="col-md-2">
            <select id="revenue" name="revenue" class="form-control">
                <option value="Expenditure">Expenditure</option>
                <option value="Miscellaneous Revenue">Miscellaneous Revenue</option>
            </select>   
            </div>
                <div class="col-md-2">
                    <button type="button" style="position: relative;left:500px" id="display" name="display" class="btn btn-primary">Display</button>
                    <button type="button" id="add" name="add" style="color:#fff" class="btn btn-warning mymodalexpenditure">Add Expenditure</button>
                </div>
                <div class="col-md-2">
                    <button type="button" id="add" name="add" style="color:#fff" class="btn btn-warning mymodalexpenditure">Add Expenditure</button>
                </div>
            </div> -->
            <div class="row">
                                 <div class="col" style="margin-bottom:20px">
                                      
                                    <input type="date" value="<?= $date ?>" id="from" name="from" class="form-control">
                                 </div>
                                 <div class="col">
                                      
                                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                                 </div>
                                
                                 <div class="col">
                                 <select id="category" class="form-control" name="category">
     <option value="">Please Select Revenue</option>
     <option value="Expenditure">Expenditure</option>
        <option value="Miscellaneous Revenue">Miscellaneous Revenue</option>
     </select>
                                 </div>
                                 <!-- <div class="col">
                                 <select id="purchased" class="form-control" name="purchased">
     <option value="">Please Select Name</option>
    <?php
        //  $sel_qry = "SELECT distinct purchased_from from `sar_expenditure` order by purchased_from ASC ";
        //  $sel_sql= $connect->prepare($sel_qry);
        //  $sel_sql->execute();
        // while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
             
        //      echo '<option value='.$sel_row["purchased_from"].'>'.$sel_row["purchased_from"].'</option>';
        // }
      
    ?></select>
                                 </div> -->
                              
                                <div class="col">
                                <button type="button" id="display" name="display" class="btn btn-primary">Display</button>
                                <button type="button" id="download" name="download" class="btn btn-success">Download</button>
                                            </div> 
                                <br>
                            </div>
                            
                            <div class="col-md-12">
                            <button type="button" style="position: relative;left:500px" id="add" name="add" style="color:#fff" class="btn btn-warning mymodalexpenditure">Add Expenditure</button>
                            </div>
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Purchased From</th>
                        <th>Particulars</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <!-- <th>Payment</th> -->
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
     
      <!-- Wrapper END -->
      <!-- Footer -->

<?php
if(isset($_POST["submit"])){
  $exp_qry="SELECT id FROM sar_expenditure ORDER BY id DESC LIMIT 1 ";
  $exp_sql=$connect->prepare("$exp_qry");
  $exp_sql->execute();
  $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
  $Last_id=$exp_row["id"]+1;
  $exp_no = "EXP_".date("Ym")."0".$Last_id;
  
  $date = ucwords($_POST["date"]);
  $purchased_from = ucwords($_POST["purchased_from"]);
  $particulars = ucwords($_POST["particulars"]);
  $amount = $_POST["amount"];
  $payment_mode = $_POST["payment_mode"];
  $revenue = $_POST["revenue"];
  
  if($revenue=="Expenditure"){
    $balance_qry="SELECT * FROM sar_expenditure where particulars='$particulars' ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
    $balance1 = abs($bal_row["balance"] - $amount);
 //   print_r($balance1);die();
    if($balance1!=0){
        $balance=$balance1;
    }else{
        $balance=$amount;
    }
  }
  else{
    $balance_qry="SELECT * FROM sar_expenditure where particulars='$particulars' ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
    $balance1 = abs($bal_row["balance"] + $amount);
   //print_r($balance1);die();
    if($balance1!=0){
        $balance=$balance1;
    }else{
        $balance=$amount;
    }
//$amount="-".$amount;
  }
  $bal=$balance;
 // print_r($balance);die();
  if($id==""){
  $query_1 = "INSERT INTO `sar_expenditure` SET 
                exp_no='$exp_no',
                date='$date',
                purchased_from='$purchased_from',
                particulars='$particulars',
                amount='$amount',
                revenue='$revenue',
                payment_mode='$payment_mode',
                balance='$bal'
                ";
                //print_r($query_1);die();
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();

        $balance_qry="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
        $balance_sql=$connect->prepare("$balance_qry");
        $balance_sql->execute();
        $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
        if($revenue=="Expenditure"){
            if($bal_row["balance"]!=""){
        $balance = $bal_row["balance"] - $amount;
       }
       if($bal_row["balance"]==""){
        $balance = -$amount;
         }
           else{
        $balance = $amount;
       }
        $fin_trans_qry = "INSERT INTO financial_transactions SET 
                        exp_id = '$exp_no',
                        date = '$date',
                        debit = '$amount',
                        balance = '$balance',
                        description = 'Expenditure : $particulars'";
        $res2=mysqli_query($con,$fin_trans_qry);
        header('Location: expenditure.php');
    }
    else{
        if($bal_row["balance"]!=""){
            $balance = $bal_row["balance"] + $amount;
           }
           else{
            $balance = $amount;
           }
            $fin_trans_qry = "INSERT INTO financial_transactions SET 
                            exp_id = '$exp_no',
                            date = '$date',
                            credit = '$amount',
                            balance = '$balance',
                            description = 'Expenditure : $particulars'";
            $res2=mysqli_query($con,$fin_trans_qry);
            header('Location: expenditure.php');
    }      
}else {
        
            $query_1 = "UPDATE `sar_expenditure` SET 
                        exp_no='$exp_no',
                        date='$date',
                        purchased_from='$purchased_from',
                        particulars='$particulars',
                        amount='$amount'
                         WHERE id='$id'";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    }    
}

 ?>


    <?php require "footer.php" ?>
<script>
//$.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_expenditure",
                "type": "POST"
            },
            "columns": [
                { "data": "date" },
                { "data": "purchased_from" },
                { "data": "particulars" },
                { "data": "amount" },
                { "data": "revenue" },
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
                        return row.purchased_from;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.particulars;
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
                        return row.revenue;
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return '<a href="expenditure.php?req=delete&id='+row.id+'&exp_no='+row.exp_no+'" onclick="return checkDelete()">Delete</a>';
                    }
                }
                
             ]
        });
    $("#display").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            var category=$("#category").val();
          //  var purchased=$("#purchased").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_expenditure&from="+from+'&to='+to+'&category='+category).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_expenditure").load();
                table.ajax.reload();
            }


        });
             

        $("#download").on("click",function(){
        var from=$("#from").val();
            var to=$("#to").val();
            var category=$("#category").val();
            // alert(category);
           // var purchased=$("#purchased").val();
             window.location.href="GetExpenditure.php?from="+from+"&to="+to+"&category="+category;
        });
        
        
        $('.mymodalexpenditure').on('click', function (){
    $( "#mymodal_expenditure" ).modal( "show" );
});

 $('.close').on('click', function (){
    $( "#mymodal_expenditure" ).modal( "hide" );
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
<div class="modal fade" id="mymodal_expenditure" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Expenditure</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                    <form method="POST" action="">
                            <div class="row col-md-12">
                                 <?php
                                 $exp_qry="SELECT id FROM sar_expenditure ORDER BY id DESC LIMIT 1 ";
                                 $exp_sql=$connect->prepare("$exp_qry");
                                 $exp_sql->execute();
                                 $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
                                 $Last_id=$exp_row["id"]+1;
                                 $exp_no = "EXP_".date("Ym")."0".$Last_id;
                                   ?>
                                 <input type="hidden" class="form-control" id="exp_no" name="exp_no" value="<?=$exp_no?>" readonly>
                              
                                <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                 <input type="date" value="<?= $date ?>" class="form-control datepicker" id="date" name="date" value="" required>
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Purchased From </label>
                                 <input type="text" class="form-control" id="purchased_from" name="purchased_from" value="">
                              </div>
                              
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Particulars </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="particulars" name="particulars" value="" required>
                              </div>
                               <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Amount</label><span style="color:red">*</span>
                                 <input type="number" id="amount" min="0" name="amount" class="form-control" id="exampleInputNumber1" value="" required>
                              </div>
                          
                              <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Revenue</label><span style="color:red">*</span>
                            <select name="revenue" class="form-control">
                                <option disabled selected>Select Revenue</option>
                                <option value="Expenditure">Expenditure</option>
                                <option value="Miscellaneous Revenue">Miscellaneous Revenue</option>
                            </select>
                                </div>
                          
                              <div class="form-group col-md-6">
                              <label for="exampleInputNumber1">Payment Mode</label><span style="color:red">*</span>
                            <select name="payment_mode" class="form-control">
                                <option disabled selected>Select Payment Mode</option>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="DD">DD</option>
                                <option value="UPI">UPI</option>
                                <option value="NEFT">NEFT</option>
                            </select>
                           </div>
                            </div>
                             <div class="" style="text-align:center"> <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                             </div> </form>
                    </div>
            </div>
        </div>
    </div>
</div>