<?php
$date = date("Y-m-d");

require "header.php";
$req1=$_GET['req1'];
$id1=$_GET['id1'];
// echo $req1.$id1;die();
if($req1!="" && $id1!=""){

    $cusid=$_REQUEST["cusid"];
    $amount1=$_REQUEST["amount1"];

    $delete="DELETE FROM sar_opening_balance WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id1));
    

    $sqlbal="select * from payment_sale where customerid='$cusid' order by id desc limit 1";
    $exebal=mysqli_query($con,$sqlbal);
    $valbal=mysqli_fetch_assoc($exebal);
    $no=mysqli_num_rows($exebal);
//   print_r($no);die();
    $sqla="select * from sar_customer where customer_no='$cusid'";
    $exea=mysqli_query($con,$sqla);
    $rowa=mysqli_fetch_assoc($exea);
     $name=$rowa['customer_name'];
     $groupname=$rowa['grp_cust_name'];

    if($no>0) {
        if($valbal==""){
          $paybal = $valbal["id"] + 1;
          $pay_id = "PAY" . date("Ym") . $paybal;   
        }
           else{
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal;   
          }
          
          if($valbal['total']!=0){
              $ob="select * from payment_sale where customerid='$cusid' order by id desc limit 1";
              //   print_r($ob);die();
                $op = $connect->prepare("$ob");
              $op->execute(); 
              $opb = $op->fetch(PDO::FETCH_ASSOC);
              $opne=$opb['total'];
              // print_r($opne);die();
              $ob_supplier_id="";
              if($opne==0){
                  $opne=$amount1;
              }
              else{
                  $opne=$opne-$amount1;
              }
          }
          else{
              $ob="select * from sar_opening_balance where name='$cusid' order by id desc limit 1";
              //   print_r($ob);die();
                $op = $connect->prepare("$ob");
              $op->execute(); 
              $opb = $op->fetch(PDO::FETCH_ASSOC);
              $opne=$opb['amount'];
              // print_r($opne);die();
              $ob_supplier_id=$opb['ob_supplier_id'];
              if($opne==0){
                  $opne=$amount1;
              }
              else{
                  $opne=$opne-$amount1;
              } 
          }
      
        $total = $valbal["total"]-$amount1;
      
        
    $tray="SELECT * FROM trays where name='$cusid' ORDER BY id DESC LIMIT 1 ";
    $tray1=$connect->prepare("$tray");
    $tray1->execute();
    $tray=$tray1->fetch(PDO::FETCH_ASSOC);   

    $type=$tray['type'];
    $description="Open Balance Deleted";
    $inward=$tray['inward'];
    $outward=$tray['outward'];
    $inhand=$tray['inhand'];
    $no_of_trays=$tray['no_of_trays'];
    $small=$tray['smalltray'];
    $big=$tray['bigtray'];
    $absmall=$tray['absmall'];
    $abbig=$tray['abbig'];
    $ab_tray=$tray['ab_tray'];
    
    // $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) value('$date','$supid',$no_of_trays,'$type','$description',$inward,'$outward','$inhand','Admin','Supplier',$ab_tray,$small,$big,$absmall,$abbig)";
    //     //    print_r($supplier_insert_query);die();   
    //         $supplier_sql=mysqli_query($con,$supplier_insert_query);
  

       $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,paymentmode) values('$groupname','$pay_id','$date','$name',$amount1,0,0,0,0,$total,'$cusid','OB Return','')";
        // print_r($insbal."ko");die(); 
        $exe=mysqli_query($con,$insbal);
      }
  

    //  print_r($delete1);die();
   
    // $delete_fin_qry="DELETE FROM financial_transactions WHERE misc_id='$revenue_no'";
    // $delete_fin_sql= $connect->prepare($delete_fin_qry);
    // $delete_fin_sql->execute();
    header("location:view_ob_balance.php");
}
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

    $supid=$_REQUEST["supid"];
    $amount=$_REQUEST["amount"];

    $delete="DELETE FROM sar_ob_supplier WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
  
    $sqlbal="select * from payment where supplierid='$supid' order by id desc limit 1";
    $exebal=mysqli_query($con,$sqlbal);
    $valbal=mysqli_fetch_assoc($exebal);
    $no=mysqli_num_rows($exebal);
  
    $sqla="select * from sar_supplier where supplier_no='$supid'";
    $exea=mysqli_query($con,$sqla);
    $rowa=mysqli_fetch_assoc($exea);
     $name=$rowa['contact_person'];
     $grpname=$rowa['group_name'];

    if($no>0) {
        if($valbal==""){
          $paybal = $valbal["id"] + 1;
          $pay_id = "PAY" . date("Ym") . $paybal;   
        }
           else{
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal;   
          }
          
          if($valbal['total']!=0){
              $ob="select * from payment where supplierid='$supid' order by id desc limit 1";
              //   print_r($ob);die();
                $op = $connect->prepare("$ob");
              $op->execute(); 
              $opb = $op->fetch(PDO::FETCH_ASSOC);
              $opne=$opb['total'];
              // print_r($opne);die();
              $ob_supplier_id="";
              if($opne==0){
                  $opne=$amount;
              }
              else{
                  $opne=$opne-$amount;
              }
          }
          else{
              $ob="select * from sar_ob_supplier where supplier_name='$supid' order by id desc limit 1";
              //   print_r($ob);die();
                $op = $connect->prepare("$ob");
              $op->execute(); 
              $opb = $op->fetch(PDO::FETCH_ASSOC);
              $opne=$opb['amount'];
              // print_r($opne);die();
              $ob_supplier_id=$opb['ob_supplier_id'];
              if($opne==0){
                  $opne=$amount;
              }
              else{
                  $opne=$opne-$amount;
              } 
          }
      
        $total = $valbal["total"]-$amount;
      
        
    $tray="SELECT * FROM trays where name='$supid' ORDER BY id DESC LIMIT 1 ";
    $tray1=$connect->prepare("$tray");
    $tray1->execute();
    $tray=$tray1->fetch(PDO::FETCH_ASSOC);   

    $type=$tray['type'];
    $description="Open Balance Deleted";
    $inward=$tray['inward'];
    $outward=$tray['outward'];
    $inhand=$tray['inhand'];
    $no_of_trays=$tray['no_of_trays'];
    $small=$tray['smalltray'];
    $big=$tray['bigtray'];
    $absmall=$tray['absmall'];
    $abbig=$tray['abbig'];
    $ab_tray=$tray['ab_tray'];
    
    // $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) value('$date','$supid',$no_of_trays,'$type','$description',$inward,'$outward','$inhand','Admin','Supplier',$ab_tray,$small,$big,$absmall,$abbig)";
    //     //    print_r($supplier_insert_query);die();   
    //         $supplier_sql=mysqli_query($con,$supplier_insert_query);
  

       $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,paymentmode) values('$grpname','$pay_id','$date','$name',$amount,0,0,0,0,$total,'$supid','OB Return','')";
        // print_r($insbal."ko");die(); 
        $exe=mysqli_query($con,$insbal);
      }
    
    //  print_r($delete1);die();
   
    // $delete_fin_qry="DELETE FROM financial_transactions WHERE misc_id='$revenue_no'";
    // $delete_fin_sql= $connect->prepare($delete_fin_qry);
    // $delete_fin_sql->execute();
    header("location:view_ob_balance.php");
    
}


if(isset($_POST["add_ob_balance"])){
    $delivery_challan_qry="SELECT id FROM sar_ob_payment ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $stock_payment_id = "SPAY_".date("Ym")."0".$Last_id;  

    $payment_stock_date = $_POST['payment_balance_date'];
    $stock_amount = $_POST["amount"];
    $payment_stock_mode = $_POST["payment_balance_mode"];
    
   // $id = $_POST["popup_customer_id"];
    $popup_ob_id = $_POST["popup_supplier_id"];
   // $customer_name=$_POST['customer_name'];
    $opening_balance_qry = "SELECT * FROM sar_ob_supplier WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id";

    $opening_balance_sql = $connect->prepare($opening_balance_qry);
    $opening_balance_sql->execute();
    $opening_balance_row = $opening_balance_sql->fetch(PDO::FETCH_ASSOC);
    
    $balance_payment_qry = "SELECT amount FROM sar_ob_payment WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id";
    $balance_payment_sql = $connect->prepare($balance_payment_qry);
    $balance_payment_sql->execute();
    $balance_payment_row = $balance_payment_sql->fetch(PDO::FETCH_ASSOC);
    

    $balance = $opening_balance_row["amount"] - $stock_amount - $balance_payment_row['amount'];
   // echo $balance;
    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_ob_payment`

              SET amount='$stock_amount',
              payment_id='$stock_payment_id',
              payment_date='$payment_stock_date',
              payment_mode='$payment_stock_mode',
              ob_supplier_id='$popup_ob_id',
              balance='$balance'
              ";
        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
      $lastInsertId = $connect->lastInsertId();

      $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
      $balance_sql1=$connect->prepare("$balance_qry1");
      $balance_sql1->execute();
      $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
      if($bal_row1["balance"]!=""){ 
      $balance1 = abs($bal_row1["balance"] - $stock_amount);
      }
      else{
      $balance1 = $stock_amount;
      }
      // print_r($balance1."n");die();
       
      $fin_trans_qry = "INSERT INTO financial_transactions SET 
      date = '$payment_stock_date',
      credit= '$stock_amount',
      balance='$balance1',
      description = 'Payment for Opening Balance Id $popup_ob_id',
      customer_data = '$popup_ob_id',
      payment_id = '6',
      ids='$popup_ob_id'
      ";
      $res2=mysqli_query($con,$fin_trans_qry);
  
    }
    $total_qry = "SELECT *, sum(amount) as totalamount FROM sar_ob_payment WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);





    $open_amount_qry = "SELECT *, sum(amount) as totalbillamount FROM sar_ob_supplier WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);


    if ($open_amount_row['totalbillamount'] <= $total_row['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        // $date = date("Y/m/d");

        $delete = "UPDATE `sar_ob_supplier` SET payment_status=1  where ob_supplier_id ='$popup_ob_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_ob_supplier WHERE ob_supplier_id='$popup_ob_id'";
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

if(isset($_POST["add_ob_balance1"])){
    $delivery_challan_qry="SELECT id FROM sar_ob_payment ORDER BY id DESC LIMIT 1 ";
    $delivery_challan_sql=$connect->prepare($delivery_challan_qry);
    $delivery_challan_sql->execute();
    $delivery_challan_row=$delivery_challan_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$delivery_challan_row["id"]+1;
    $stock_payment_id = "SPAY_".date("Ym")."0".$Last_id;  

    $payment_stock_date = $_POST['payment_balance_date1'];
    $stock_amount = $_POST["amount1"];
    $payment_stock_mode = $_POST["payment_balance_mode1"];
    // print_r($_POST);die();
   // $id = $_POST["popup_customer_id"];
    $popup_ob_id = $_POST["popup_customer_id"];
   // $customer_name=$_POST['customer_name'];
    $opening_balance_qry = "SELECT * FROM sar_opening_balance WHERE balance_id='$popup_ob_id' GROUP BY balance_id";

    $opening_balance_sql = $connect->prepare($opening_balance_qry);
    $opening_balance_sql->execute();
    $opening_balance_row = $opening_balance_sql->fetch(PDO::FETCH_ASSOC);
    
    $balance_payment_qry = "SELECT amount FROM sar_ob_payment WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id";
    $balance_payment_sql = $connect->prepare($balance_payment_qry);
    $balance_payment_sql->execute();
    $balance_payment_row = $balance_payment_sql->fetch(PDO::FETCH_ASSOC);
    

    $balance = $opening_balance_row["amount"] - $stock_amount - $balance_payment_row['amount'];
   // echo $balance;
    if ($balance >= 0) {

        $insert = "INSERT INTO  `sar_ob_payment`

              SET amount='$stock_amount',
              payment_id='$stock_payment_id',
              payment_date='$payment_stock_date',
              payment_mode='$payment_stock_mode',
              ob_supplier_id='$popup_ob_id',
              balance='$balance'
              ";
        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();
      $lastInsertId = $connect->lastInsertId();

      
      $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
      $balance_sql1=$connect->prepare("$balance_qry1");
      $balance_sql1->execute();
      $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
      if($bal_row1["balance"]!=""){ 
      $balance1 = abs($bal_row1["balance"] - $stock_amount);
      }
      else{
      $balance1 = $stock_amount;
      }
      // print_r($balance1."n");die();
       
      $fin_trans_qry = "INSERT INTO financial_transactions SET 
      date = '$payment_stock_date',
      credit= '$stock_amount',
      balance='$balance1',
      description = 'Payment for Opening Balance Id $popup_ob_id',
      customer_data = '$popup_ob_id',
      payment_id = '6',
      ids='$popup_ob_id'
      ";
      $res2=mysqli_query($con,$fin_trans_qry);
    }
    $total_qry = "SELECT *, sum(amount) as totalamount FROM sar_ob_payment WHERE ob_supplier_id='$popup_ob_id' GROUP BY ob_supplier_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);

    $open_amount_qry = "SELECT *, sum(amount) as totalbillamount FROM sar_opening_balance WHERE balance_id='$popup_ob_id' GROUP BY balance_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);


    if ($open_amount_row['totalbillamount'] <= $total_row['totalamount']) {

        // $delete="UPDATE `sar_sales_payment` SET balance=0";

        // $delete_sql= $connect->prepare($delete);

        // $delete_sql->execute();

        // $date = date("Y/m/d");

        $delete = "UPDATE `sar_opening_balance` SET payment_status=1  where balance_id ='$popup_ob_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        $select_qry3 = "SELECT * FROM sar_opening_balance WHERE balance_id='$popup_ob_id'";
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
        <div><h2>View Open Balance</h2></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <div class="iq-card-body">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-6">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                    Supplier
                                    </a>
                                </li>
                                <!-- <li class="col-md-2">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                    Supplier Settled
                                    </a>
                                </li> -->
                                <li class="col-md-6">
                                    <a class="nav-link" data-toggle="pill" href="#supplier-unsettled">
                                        Customer
                                    </a>
                                </li>
                                <!-- <li class="col-md-2">
                                    <a class="nav-link" data-toggle="pill" href="#supplier-settled">
                                    Customer Settled
                                    </a>
                                </li> -->
                                
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
                                    <input type="date" id="to" value="<?= $date ?>" name="to" class="form-control">
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
                                        <!-- <th>Supplier Id</th> -->
                                       <th>Supplier Name</th>
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
                                        <th>Amount</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                        <div class="tab-pane fade" id="supplier-unsettled" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="fromsup" name="fromsup">
                                </div>
                                <div class="col">
                                    <input type="date" id="tosup" value="<?= $date ?>" name="tosup" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submitsup" name="submitsup" class="btn btn-primary">Display</button>
                                    <button type="button" id="downloadsup" name="downloadsup" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <table id="supplierunsettled" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                         <th>Customer Id</th> 
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="supplier-settled" role="tabpanel">
                            <div class="row">
                                <div class="col" style="margin-bottom:20px">
                                    <input type="date" value="<?= $date ?>" class="form-control" id="from_settled1" name="from_settled1">
                                </div>
                                <div class="col">
                                    <input type="date" value="<?= $date ?>" id="to_settled1" name="to_settled1" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" id="submit_settled" name="submit_settled1" class="btn btn-primary">Display</button>
                                    <button type="button" id="download_settled" name="download_settled1" class="btn btn-danger">Download</button>
                                </div>
                            </div>
                            <br>
                            <table id="suppliersettled" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Amount</th>
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
function update_payment_modal(ob_supplier_id,data_src) {
         
        $("#myModal").modal("show");
        // alert(ob_supplier_id)
        // $("#ob_supplier_id").val(ob_supplier_id);
        if(data_src == 'settled'){
            $('#payment_form').hide();
        }else{
            $('#payment_form').show();
        }
        $.ajax({
            type: "POST",
            url: "forms/ajax_request_view.php",
            data: {"action": "view_ob_payment_modal","ob_supplier_id": ob_supplier_id,"data_src": data_src},
            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#tabs1 #popup_supplier_id').val(ob_supplier_id);
                var result = data.data;
                var result1 = data['cash'];
                // alert(result[0].date)
            //  console.log(result1)
                // alert(result[0].date)
                $("#payment").html("");
                var i = 0;
                $("#date").html(result[0].date);
                $("#ob_supplier_id").html(result[0].ob_supplier_id);
                $("#group_name").html(result[0].group_name);
                $("#supplier_name").html(result[0].supplier_name);
                $("#amount").html(result[0].amount);
                
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

    function update_payment_modal1(balance_id,data_src) {
         
         $("#myModal1").modal("show");
         // alert(ob_supplier_id)
         // $("#ob_supplier_id").val(ob_supplier_id);
         if(data_src == 'settled'){
             $('#payment_form1').hide();
         }else{
             $('#payment_form1').show();
         }
         $.ajax({
             type: "POST",
             url: "forms/ajax_request_view.php",
             data: {"action": "view_ob_payment_modal1","balance_id": balance_id,"data_src": data_src},
             dataType: "json",
             success: function(data) {
                 console.log(data);
                 $('#tabs3 #popup_customer_id').val(balance_id);
                 var result = data.data;
                 var result1 = data['cash'];
                 // alert(result[0].date)
             //  console.log(result1)
                 // alert(result[0].date)
                 $("#payment").html("");
                 var i = 0;
                 $("#date1").html(result[0].date);
                 $("#balance_id").html(result[0].balance_id);
                 $("#groupname").html(result[0].group_name);
                 $("#name").html(result[0].name);
                 $("#amount1").html(result[0].amount);
                 
                 for (i = 0; i < result1.length; i++) {
                     $('#payment1').append('<tr>');
                     $('#payment1').append('<td>' + result1[i].payment_id + '</td>');
                     $('#payment1').append('<td>' + result1[i].payment_date + '</td>');
                     $('#payment1').append('<td>' + result1[i].payment_mode + '</td>');
                     $('#payment1').append('<td>' + result1[i].amount + '</td>');
                     $('#payment1').append('<td>' + result1[i].balance + '</td>');
                     $('#payment1').append('</tr>');
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
                "url": "forms/ajax_request.php?action=view_ob",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "ob_supplier_id"},
                    // { "data": "supplier_id"},
                    { "data": "supplier_name"},
                    { "data": "amount" },
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
                        return row.ob_supplier_id;
                    }
                },
                // {
                //     targets: 2,
                //     render: function(data, type, row) {
                //         return row.supplier_id;
                //     }
                // },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.supplier_name;
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
                           return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" href="view_ob_balance.php?req=delete&id='+row.id+'&supid='+row.supplier_id+'&amount='+row.amount+'" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a></div></div>';
                        }else{
                            // <a class="dropdown-item" id="mymodal_id" href="#" ob_supplier_id="'+row.ob_supplier_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a>
                            // return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_id" href="#" ob_supplier_id="'+row.ob_supplier_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
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
                table.ajax.url("forms/ajax_request.php?action=view_ob&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_ob").load();
                table.ajax.reload();
            }
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="obreport.php?from="+from+'&to='+to;
        });
        
        var table1=$('#settled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_ob_settled",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "ob_supplier_id"},
                    { "data": "supplier_name"},
                    { "data": "amount" },
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
                        return row.ob_supplier_id;
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
                       return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ></div></div>';
                    //    <a class="dropdown-item" id="mymodal_settled" href="#" ob_supplier_id="'+row.ob_supplier_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a>
                    }
                }
             ],
             "order": [[ 1, 'asc' ]]
             
        });
        $("#submit_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to_settled").val();
            if(from_settled!="" && to_settled!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_ob_settled&from_settled="+from_settled+'&to_settled='+to_settled).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_ob_settled").load();
                table1.ajax.reload();
            }
        });
        $("#download_settled").on("click",function(){
            var from_settled=$("#from_settled").val();
            var to_settled=$("#to").val();
            window.location="download_ob_settled.php?from_settled="+from_settled+'&to_settled='+to_settled;
        });
        
        $('#unsettled tbody').on('click', '#mymodal_id', function() {
            var ob_supplier_id = $("#mymodal_id").attr("ob_supplier_id");
            // alert($("#mymodal_id").attr("ob_supplier_id"));
            //console.log(purchase_id);
            // alert(ob_supplier_id)
            $("#myModal").modal("show");
            $("#ob_supplier_id").val(ob_supplier_id);
            update_payment_modal(ob_supplier_id, 'unsettled');
        });
        
        $('#settled tbody').on('click', '#mymodal_settled', function (){
            // var ob_supplier_id = $(this).attr("ob_supplier_id");
            var ob_supplier_id = $("#mymodal_settled").attr("ob_supplier_id");
        // alert(ob_supplier_id)
            $( "#myModal" ).modal( "show" );
            $("#ob_supplier_id").val(ob_supplier_id);
            update_payment_modal(ob_supplier_id, 'settled')
        });
        
        $(".close").click(function() {
            $("#myModal").modal("hide");
        });
    });
    //supplierunsettled

</script>
<script>
function checkDelete(){
    return confirm('Are you sure you want to delete?');
}
</script>
<script>
        $(document).ready(function(){
       var table=$('#supplierunsettled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_ob_customer",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "balance_id"},
                    { "data": "customerid"},
                    { "data": "name"},
                    { "data": "amount" },
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
                        return row.balance_id;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.customerid;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.name;
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
                        return row.updated_by;
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        if(row.paid_amount == null){
                           return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" href="view_ob_balance.php?req1=deleted&id1='+row.id+'&cusid='+row.customerid+'&amount1='+row.amount+'" onclick="return checkDelete()"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a></div></div>';
                        }else{
                            // <a class="dropdown-item" id="mymodal_id1" href="#" ob_supplier_id="'+row.balance_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a>
                            // return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" ><a class="dropdown-item" id="mymodal_id1" href="#" ob_supplier_id="'+row.balance_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
                        }
                    }
                }
             ],
             "order": [[ 1, 'asc' ]]
             
        });
       
        $("#submitsup").on("click",function(){
            var from=$("#fromsup").val();
            var to=$("#tosup").val();
            // alert(from);
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_ob_customer&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_ob_customer").load();
                table.ajax.reload();
            }
        });
        $("#downloadsup").on("click",function(){
            var from=$("#fromsup").val();
            var to=$("#tosup").val();
            // alert(to);
            // window.location="download_ob.php?from="+from+'&to='+to;
            window.location="obreport_customer.php?from="+from+'&to='+to;
        });
        
        var table1=$('#suppliersettled').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_ob_settled_cus",
                "type": "POST",
            },
            "columns": [
                    { "data": "date"},
                    { "data": "balance_id"},
                    { "data": "name"},
                    { "data": "amount" },
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
                        return row.balance_id;
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
                       return '<div class="iq-card-header-toolbar d-flex align-items-center"><span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown"><i class="ri-more-fill"></i></span><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" ><a class="dropdown-item" id="mymodal_settled1" href="#" balance_id="'+row.balance_id+'"><i class="ri-eye-fill mr-2"></i>Payment</a></div></div>';
                    }
                }
             ],
             "order": [[ 1, 'asc' ]]
             
        });
        $("#submit_settled1").on("click",function(){
            var from_settled=$("#from_settled1").val();
            var to_settled=$("#to_settled1").val();
            if(from_settled!="" && to_settled!=""){
                table1.ajax.url("forms/ajax_request.php?action=view_ob_settled&from_settled="+from_settled+'&to_settled='+to_settled).load();
                table1.ajax.reload();
            } else {
                table1.ajax.url("forms/ajax_request.php?action=view_ob_settled").load();
                table1.ajax.reload();
            }
        });
        $("#download_settled1").on("click",function(){
            var from_settled=$("#from_settled1").val();
            var to_settled=$("#to1").val();
            window.location="download_ob_settled.php?from_settled="+from_settled+'&to_settled='+to_settled;
        });
        
        $('#supplierunsettled tbody').on('click', '#mymodal_id1', function() {
            var balance_id = $("#mymodal_id1").attr("ob_supplier_id");
            // alert($("#mymodal_id").attr("ob_supplier_id"));
            //console.log(purchase_id);
            // alert(balance_id)
            $("#myModal1").modal("show");
            $("#balance_id").val(balance_id);
            update_payment_modal1(balance_id, 'unsettled');
        });
        
        $('#suppliersettled tbody').on('click', '#mymodal_settled1', function (){
            // var ob_supplier_id = $(this).attr("ob_supplier_id");
            var balance_id = $("#mymodal_settled1").attr("balance_id");
        // alert(balance_id)
            $( "#myModal1" ).modal( "show" );
            $("#balance_id").val(balance_id);
            update_payment_modal1(balance_id, 'settled')
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
                <h4 class="modal-title">Open Balance Payment</h4>
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
                                <!-- <p id="date"></p> -->
                            </tr>
                            <tr>
                                <th>OB Supplier ID</th>
                                <td id="ob_supplier_id"></td>
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
                                <th>Amount</th>
                                <td id="amount"></td>
                            </tr>
                        </table>
                        <div id="payment_form">
                            <table class="table table-bordered">
                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_balance_date" required> 
                                    <input type="hidden" id="popup_supplier_id" name="popup_supplier_id" value="" />
                                    </td>
                                    <td><input type="text" class="form-control" name="amount" required>
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
                                    <td><input type="submit" name="add_ob_balance" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
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
                <h4 class="modal-title">Open Balance Payment</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="tabs3" class="tabcontent">
                    <form action="" method="POST">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td id="date1"></td>
                                <!-- <p id="date"></p> -->
                            </tr>
                            <tr>
                                <th>OB Supplier ID</th>
                                <td id="balance_id"></td>
                            </tr>
                            <tr>
                                <th>Group Name</th>
                                <td id="groupname"></td>
                            </tr>
                            <tr>
                                <th>Supplier Name</th>
                                <td id="name"></td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td id="amount1"></td>
                            </tr>
                        </table>
                        <div id="payment_form1">
                            <table class="table table-bordered">
                            <thead>
                                <h4>Payment</h4>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" value="<?= $date ?>" class="form-control datepicker" name="payment_balance_date1" required> 
                                    <input type="text" id="popup_customer_id" name="popup_customer_id" value="" />
                                    </td>
                                    <td><input type="text" class="form-control" name="amount1" required>
                                    </td>
                                    <td>
                                        <select name="payment_balance_mode1" class="form-control" required>
                                            <option value="">--Select Payment Mode--</option>
                                            <!-- <option value="neft">NEFT</option>
                                            <option value="online">Online</option>
                                            <option value="cash">Cash</option>
                                            <option value="dd">DD</option> -->

                                                 
                                <option value="NEFT">NEFT</option>

<option value="Gpay">Gpay(UPI)</option>

<option value="Cash">Cash</option>

<option value="Cheque">Cheque</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" name="add_ob_balance1" class="btn btn-primary" value="Submit"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </form>
                </div>
                <div id="tabs4" class="tabcontent">
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
                                    <tbody id="payment1">
                                        
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

