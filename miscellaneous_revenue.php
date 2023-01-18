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

$supplier_name = $_REQUEST["supplier_name"];
$mobile_number = $_REQUEST["mobile_number"];
$supplier_address = $_REQUEST["supplier_address"];
$supplier_qry="SELECT * FROM `sar_supplier` WHERE id=:id ";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute(array(':id' => $id));
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);

if($req=="enabled")
{
    $delete="UPDATE `sar_supplier` SET is_active=0 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_supplier.php");
}

if($req=="disabled")
{
    $delete="UPDATE `sar_supplier` SET is_active=1 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_supplier.php");
}


?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                 <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Miscellaneous Revenue</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                          <form method="POST" action="">
                             <div class="row col-md-12">  
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Particulars </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="particulars" name="particulars"  required>
                              </div>
                              
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Date </label><span style="color:red">*</span>
                                 <input type="date" value="<?= $date ?>" class="form-control" id="date" name="date" required>
                              </div>
                             
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Amount </label>
                                 <span style="color:red">*</span>
                                 <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                              </div>
                                <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Payment Mode </label><span style="color:red">*</span>
                                 <select name="mode" class="form-control" required>
                                     <option value="Cash">Cash</option>
                                     <option value="Cheque">Cheque</option>
                                     <option value="DD">DD</option>
                                     <option value="UPI">UPI</option>
                                     <option value="NEFT">NEFT</option>
                                 </select>
                              </div>
                               <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Remarks </label>
                                 
                                 <input type="text" class="form-control" id="remarks" name="remarks">
                              </div>
                             </div>
                              <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                           </form>
                        </div>
                    
                    
                      </div>
                  </div>
               <div class="col-lg-6">
                
        </div>   
               </div>
            </div>
         </div>
     
<?php
if(isset($_POST["submit"])){
    $supplier_qry="SELECT id FROM sar_miscellaneous_revenue ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $revenue_no = "MISC_".date("Ym")."0".$Last_id;
   
 $particulars=$_POST['particulars'];
   $date=$_POST['date'];
  $amount=$_POST['amount'];
  $mode=$_POST['mode'];
  $remarks=$_POST['remarks'];
  
  if($id==""){
     
  
  $query_1 = "INSERT INTO `sar_miscellaneous_revenue` SET 
                revenue_no='$revenue_no',
                particulars='$particulars',
                date='$date',
                amount='$amount',
                mode='$mode',
                remarks='$remarks',
                updated_by='$user_name'
                ";
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
        
        $balance_qry="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
        $balance_sql=$connect->prepare("$balance_qry");
        $balance_sql->execute();
        $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);  
        if($bal_row["balance"]!=""){  
        $balance = $bal_row["balance"] + $amount;
        }
        else{
            $balance = $amount;
        }
        $fin_trans_qry = "INSERT INTO financial_transactions SET 
                        misc_id = '$revenue_no',
                        date = '$date',
                        credit = $amount,
                        balance = $balance,
                        description = 'Miscellaneous Revenue : $particulars'";
        $res2=mysqli_query($con,$fin_trans_qry);
        
        
}else if($id==""){
    
    
    $query_1 = "UPDATE `sar_miscellaneous_revenue` SET 
                revenue_no='$revenue_no',
                particulars='$particulars',
                date='$date',
                amount='$amount',
                mode='$mode',
                remarks='$remarks'
                ";
}

else {
        
            $query_1 = "UPDATE `sar_miscellaneous_revenue` SET 
                revenue_no='$revenue_no',
                particulars='$particulars',
                date='$date',
                amount='$amount',
                mode='$mode',
                remarks='$remarks'
                WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    }    
    header("location:miscellaneous_revenue.php");
}

?>

    <?php require "footer.php" ?> 
    
    
