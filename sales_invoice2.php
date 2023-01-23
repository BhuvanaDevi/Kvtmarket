<?php require "header.php";
$date = date("Y-m-d");

$saleid=isset($_GET['sales_no'])?$_GET['sales_no']:"";

$val="select * from sar_sales_invoice where sale_id='$saleid'";
$exeval=mysqli_query($con,$val);
$valg=mysqli_fetch_assoc($exeval);
$patname=$valg['patti_name'];

$submit_action = 'add_sales_invoice';
$sales_no = "";
$quality_box = "";
$mobile_number = "";
$customer_name = "";
$customer_address = "";
$boxes_arrived = "";
$total_bill_amount = "";
$customer_id = "";
$cnt = 0;

$quality_name_list = array();
$quality_name_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
$quality_name_qry_stmt = $connect->prepare($quality_name_qry);
$quality_name_qry_stmt->execute();
while ($sel_row = $quality_name_qry_stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($quality_name_list, $sel_row["quality_name"]);
}


// print_r($valg);die();

if(isset($_REQUEST['req'])!="" && $_REQUEST["req"] == 'edit'){
    $submit_action = 'edit_sales_invoice';
    $sales_no = $_REQUEST["sales_no"];
    $sales_qry = "SELECT * FROM sar_sales_invoice WHERE sale_id = '".$sales_no."'";
    $sales_sql = $connect->prepare("$sales_qry");
    $sales_sql->execute();
	while ($data_row = $sales_sql->fetch(PDO::FETCH_ASSOC)) {
	   
	   if($cnt == 0){
	       $mobile_number = $data_row['mobile_number'];
	       $customer_name = $data_row['customer_name'];
	       $customer_address = $data_row['customer_address'];
	       $customerid = $data_row['customer_id'];
	       $boxes_arrived = $data_row['boxes_arrived'];
	       $total_bill_amount = $data_row['total_bill_amount'];
	       
	   }
	   if ($data_row['quality_name'] == "") {
            continue;
       }
	//    $quality_box .= '
	//     <div class="iq-card col-md-89 qualityBox" id="inputFormRow" style="margin:10px;">                      
	//         <div class="iq-card-header d-flex justify-content-between">
	//             <div class="iq-header-title">
	//                 <h4 class="card-title">Payment</h4>
	//             </div>
	//         </div>
	//         <div class="iq-card-body"> 
	//         <div class="form-group">
	//             <label for="exampleFormControlSelect1">Select Quality</label>
	//             <select class="form-control" id="exampleFormControlSelect1" name="quality_name[]">
	//                 <option value="">--Choose Quality--</option>';
    //         	   foreach ($quality_name_list as $value){
    //         	       $selected = '';
    //         	       if($value == $data_row['quality_name']){
    //         	           $selected = 'selected';
    //         	       }
    //         	       $quality_box .= '<option '. $selected.'>'.$value.'</option>';
    //         	   }
    //         	   $quality_box .= '</select>
	//         </div>
	//         <div>
	//             <label>Quantity</label>
	//             <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" onchange="calculate_bill_amount('.$cnt.')" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'" min="0">
	//         </div> 
	//         <div class="form-group">
	//             <label for="exampleInputNumber1">Rate</label>
	//             <input type="number" onchange="calculate_bill_amount('.$cnt.')" class="form-control rate_arr sum_tol" onkeyup=total_amt("'. $cnt .'"); id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'" min="0">
	//         </div>
	//         <div>
	//             <label>Total</label>
	//             <input type="text" readonly name="bill_amount[]" class="sum_tol_ov form-control" id="bill_amount' . $cnt . '" value="'.$data_row['bill_amount'].'" >
	//             <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'" min="0">
	//         </div><br>
	//         <button id="removeRow" data-rec-id="' . $data_row['id'] . '" type="button" class="btn btn-danger">Remove</button>
	//         </div>
	//     </div>';

    $quality_box .= '
        <div class="row col-md-12 qualityBox" id="inputFormRow" style="margin:10px;">                      
    <div class="form-group col-md-2">
            <label for="exampleFormControlSelect1">Select Quality</label>
            <select class="form-control" id="exampleFormControlSelect1" name="quality_name[]">
                <option value="">--Choose Quality--</option>';
               foreach ($quality_name_list as $value){
                   $selected = '';
                   if($value == $data_row['quality_name']){
                       $selected = 'selected';
                   }
                   $quality_box .= '<option '. $selected.'>'.$value.'</option>';
               }
               $quality_box .= '</select>
        </div>
        <div class="form-group col-md-2">
            <label>Quantity</label>
            <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" onchange="calculate_bill_amount('.$cnt.')" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'" min="0">
        </div> 
        <div class="form-group  col-md-2">
            <label for="exampleInputNumber1">Rate</label>
            <input type="number" onchange="calculate_bill_amount('.$cnt.')" class="form-control rate_arr sum_tol" onkeyup=total_amt("'. $cnt .'"); id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'" min="0">
        </div>
        <div class="form-group col-md-2">
            <label>Total</label>
        <input type="text" readonly name="bill_amount[]" class="sum_tol_ov form-control boxTotal" id="bill_amount' . $cnt . '" value="'.$data_row['bill_amount'].'" >
            <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'" min="0">
            </div>
        <div class="form-group col-md-2">
        <button id="removeRow" style="position:relative;top:28px !important" data-rec-id="' . $data_row['id'] . '" type="button" class="btn btn-danger">Remove</button>
        </div>
    </div>';
	   $cnt = $cnt + 1;
	}
//     <div class="form-group col-md-2">
//     <label>Quantity</label>
//     <input type="text" class="form-control" name="type[]" readonly myattr="' . $cnt . '" id="type' . $cnt . '" value="'.$data_row['type'].'">
// </div> 
   
} else {
    $req="";
    $sales_qry = "SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
    $sales_sql = $connect->prepare("$sales_qry");
    $sales_sql->execute();
    $sales_row = $sales_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id = $sales_row["id"] + 1;
    $sales_no = "CR_" . date("Ym") . "0" . $Last_id;
}


if(isset($_REQUEST['id'])!=""){
    $id=$_REQUEST["id"];
} else {
    $id="";
}

$check_patti="select * from sar_patti where patti_date='$date' and remain_box!=-2 and is_active!=0 and nullify!=1 group by supplier_name";
// $execheck=mysqli_query($con,$check_patti);
// $check_sql=mysqli_num_rows($execheck);
$execheck= $connect->prepare($check_patti);
$execheck->execute();
$numrow=$execheck->rowCount();


?>
 <div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-lg-12">
          <?php if($numrow<=0) { ?>
          <div class="iq-card">
                <div class="iq-card-body p-0" style="text-align: center;">
                   <div class="iq-edit-list">
                    <h5>If you generate sales invoice, you will must create patti.</h5><br/>
                    <a href="GeneratPatti.php" class="btn btn-success">Click Here</a>
                   </div>
                </div>
          </div>  
          <?php } else { ?>
          
          <div class="iq-card">
                <div class="iq-card-body p-0">
                   <div class="iq-edit-list">
                      <ul class="iq-edit-profile d-flex nav nav-pills">
                         <li class="col-md-3 p-0">
                            <a class="nav-link active" data-toggle="pill" href="#personal-information">
                               Credit 
                            </a>
                         </li>
                         <li class="col-md-3 p-0">
                            <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                               Cash & Carry
                            </a>
                         </li>
                       
                      </ul>
                   </div><br/><br/>
                   <div class="row col-md-12">
                    <div class="col-md-12" style="text-align: right;">
                   <a href="view_sales_invoice.php" class="btn btn-warning" style="color: #fff;">View Sale Invoice</a>
                   </div>
                   </div>
                     <div class="iq-edit-list-data">
                    <div class="tab-content">
                   <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                       <br/><br/><form id="form1" method="post" action="#" class="searchbox">
                               <div id="delete_rec_id_list">
                             
                               </div>
                                <input type="hidden" class="form-control" id="customer_id" name="customer_id" value="<?= $customerid ?>" >
                                <div class="row col-md-12">
                                                                    <div class="form-group col-md-6">
                                       <div class="row col-md-12">
                                       <div class="form-group col-md-6">
                                          <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                         <select class="form-control" name="grpname" id="grpname" required>
                                            <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct * from `sar_group_customer` order by grp_cust_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	              ?>  
                            	                <option value="<?= $sel_row["grp_cust_name"] ?>" <?=($valg['groupname']==$sel_row["grp_cust_name"])?"selected":""?>><?= $sel_row["grp_cust_name"] ?> </option>
                            	           <?php }
                            	           ?>
                            	          
                            	           </select>
                                       </div>

                                       <div class="form-group col-md-6">
                                          <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                                    <?php if($valg['customer_name']=="") { ?>
                                          <input list="searchval" id="search_val" class="form-control" name="search_val">
                                <datalist class="searchval" id="searchval" name="searchval" required>
                                  <option>Select Customer Name</option> 
                                 </datalist>
                                 <button type="button"style="position:relative;top:5px;float:right;" id="add_customer" name="add_customer" class="badge badge-success p-2 mymodal">Add Customer</button>
                                <?php } else { ?>
                                    <select class="form-control" name="searchval" id="searchval" required>
                                            <option value="">--Choose Customer Name--</option>
                                    <?php
                                               $sel_qry = "SELECT customer_name from `sar_customer` where grp_cust_name='$valg[groupname]'";
                                               $sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	              ?>  
                            	                <option value="<?= $sel_row["customer_name"] ?>" <?=($valg['customer_name']==$sel_row["customer_name"])?"selected":""?>><?= $sel_row["customer_name"] ?> </option>
                            	           <?php }
                            	           ?>
                            	          
                            	           </select>
                                  
                                           <?php } ?> 
                                </div>
                                     </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputdate">Sales Invoice ID </label>
                                        <input type="text" class="form-control" id="sales_no" name="sales_no" value="<?=$sales_no?>" readonly>
                                    </div>
                                    </div> 
                                    <div class="row col-md-12">
                                <div class="form-group col-md-6">
                                         <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="date" required>
                                      </div>
                                      
                                       <div class="form-group col-md-6">
                                        <label for="exampleInputdate">Group Name </label>
                                        <input type="text" class="form-control" id="grp_cust_name" value="<?=$valg['groupname']?>" name="grp_cust_name" readonly>
                                    </div>
                                    </div>
                                    <div class="row col-md-12">
                                      <div class="form-group col-md-6">
                                         <label for="exampleInputText1">Customer Name </label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?=$valg['customer_name']?>" required>
                                      </div>
                                       <div class="form-group col-md-6">
                                         <label for="exampleInputNumber1">Mobile number</label><span style="color:red">*</span>
                                         <input type="text" class="form-control" id="mobile_number" name="mobile_number" maxlength="10" pattern="^[6-9]\d{9}$" value="<?=$valg['mobile_number']?>" required>
                                      </div>
                                    </div>
                                    <div class="row col-md-12">
                                <div class="form-group col-md-6">
                                         <label for="exampleInputText1">Address </label>
                                         <input type="text" class="form-control" id="customer_address" name="customer_address" value="<?=$valg['customer_address']?>">
                                      </div>
                                      <div class="form-group col-md-6">
                                         <label for="exampleInputNumber1">Boxes Arrived</label><span style="color:red">*</span>
                                         <input type="number" class="form-control" id="exampleInputNumber1" name="boxes_arrived" value="<?=$valg['boxes_arrived']?>" readonly required>
                                      </div>
                                    </div>
                                      
                                     <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field">
                                          <?php echo $quality_box; ?>
                                      </div>
                                      </div>
                                   </div>
                                   <div class="row col-md-12">           
                          <div class="form-group col-md-6">
                          <input type="hidden" value="" class="form-control" id="coun" name="coun" />
                                      <input type="hidden" value="<?= $date ?>" class="form-control" id="pat_date" name="pat_date" />
                                         <label for="exampleInputNumber1">Select Patti</label><span style="color:red">*</span>
                                         <!-- <input list="pattiid" class="form-control" id="patti" name="patti">
                                         <datalist id="pattiid" name="patti" id="patti"> -->
                                         <select name="patti" id="patti" class="form-control"> 
                                         <option>-- Select Patti --</option>
                                         <?php
                                        while ($row = $execheck->fetch(PDO::FETCH_ASSOC)){
                                   if($row['remain_box']==-1 || $row['remain_box']>0){
                                    //   print_r($row["pat_id"]==$patname);die();
                                   ?>
 <option value="<?=$row["pat_id"]?>" <?=($row["pat_id"]==$patname)?"selected":''?>><?=$row["supplier_name"]." - ".$row["pat_id"]?></option>
                           <?php        }
                                   else{
?>
<!-- <option value="<?=$row["supplier_id"]?>"><?=$row["supplier_name"]." - ".$row["patti_id"]?></option> -->
 <?php                                   }
                                        }
                                           ?>
                                         </select>
                                         
                                         <?php  //while ($row = $execheck->fetch(PDO::FETCH_ASSOC)){
                                            //    echo '<option value="'.$row["supplier_id"].'">'.$row["supplier_name"]." - ".$row["farmer_name"]." (".$row["boxes_arrived"].") ".'</option>';
                            	        ?>
                                        <!-- <option value="<?=$row["supplier_id"]?>"><?=$row["supplier_name"]." - ".$row["farmer_name"]." (".$row["boxes_arrived"].")"?></option> -->
                                        <?php
                                       // }
                                           ?>
                                             </datalist>
                                      </div>
                                    
                                      <!-- <div class="form-group col-md-6">
                                      <label for="exampleInputText1">Tray Type</label>
                          <input list="types" name="tray_type" id="type" class="form-control">
                          
<datalist id="types">
<option value="" disabled>--Choose Tray Type--</option>
                                            <option value="Small Tray">Small Tray</option>
                                            <option value="Big Tray">Big Tray</option>
                                          
                                        
</datalist> 
                          </div> -->
</div>
                                        <div class="row col-md-12">           
                          <div class="form-group col-md-6">
                                         <label for="exampleInputNumber1">Total Amount</label><span style="color:red">*</span>
                                         <input type="text" readonly class="form-control" id="totalamt" name="totalamt"  value="<?=$total_bill_amount?>">
                                      </div>
                                      <div class="col-md-6">
                                      <a href="#dynamic_field"><button style="position: relative;top:38px" type="button" name="add" id="add" class="btn btn-success">Add More</button></a>
                                      </div>   </div>  
                                    
                                      <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
                     </form>
                        
                   </div>
                   <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                       <br/><br/>
                            <form id="form2" method="post" action="#" class="searchbox">
                            <div class="row col-md-12">
                                   
                            <div class="form-group col-md-6">
                                         <label for="exampleInputdate">Cash&Carry ID </label>
                                         <?php
                                         $cash_qry="SELECT id FROM sar_cash_carry ORDER BY id DESC LIMIT 1 ";
                                            $cash_sql=$connect->prepare($cash_qry);
                                            $cash_sql->execute();
                                            $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
                                            $Last_id_cash=$cash_row["id"]+1;
                                            $cash_no = "CC_".date("Ym")."0".$Last_id_cash;
                                            
                                        ?>
                                  <input type="text" class="form-control" id="cash_no" name="cash_no" value="<?=$cash_no?>" readonly>
                                      </div>
                                      <div class="form-group col-md-6">
                                      <label for="exampleInputText1">Tray Type</label>
                          <input list="types" name="type1" id="type1" class="form-control">
                          
<datalist id="types">
<option value="" disabled>--Choose Tray Type--</option>
                                            <option value="Small Tray">Small Tray</option>
                                            <option value="Big Tray">Big Tray</option>
                                          
                                        
</datalist> 
                          </div>
                          <div class="row col-md-12">
                                  <div class="form-group col-md-4">
                                         <label for="exampleInputNumber1">Date</label><span style="color:red">*</span>
                                         <input type="date" value="<?= $date ?>" class="form-control date_cash datepicker" id="date_cash" name="date_cash" required>
                                      </div>
                                      <div class="form-group col-md-4">
                                       <label for="exampleInputNumber1">Group Name</label><span style="color:red">*</span>
                                       <select class="form-control" name="grpname1" id="grpname1" required>
                                            <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct * from `sar_group_customer` order by grp_cust_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	              ?>  
                            	                <option value="<?= $sel_row["grp_cust_name"] ?>"><?= $sel_row["grp_cust_name"] ?> </option>
                            	           <?php }
                            	           ?>
                            	          
                            	           </select>
                                       </div>
                                       <div class="form-group col-md-4">
                                      <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                                         <input list="searchvals" id="search_vals" class="form-control" name="search_vals">
                                <datalist class="searchval" id="searchvals" name="searchvals" required>
                                  <option>Select Customer Name</option> 
                                 </datalist>
                                       </div>
                                      
                                    </div>
                                    </div>
                                      <!-- <div class="row col-md-12" style="margin-left:25px !important">
                                      <div class="form-group col-md-2">
                                         <label for="exampleFormControlSelect1">Select Quality</label><span style="color:red">*</span>
                                         <input list="qualitynamecash" class="form-control" name="qualitynamecashs" id="qualitynamecashs">
                                         <datalist class="" id="qualitynamecash" name="quality_name_cash[]" required>
                                            <?php
                                        //     $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
                                        // 	$sel_sql= $connect->prepare($sel_qry);
                            	        //     $sel_sql->execute();
                            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	        //         echo '<option value="'.$sel_row["quality_name"].'">'.$sel_row["quality_name"].'</option>';
                            	        //    }
                            	           ?>
                                         </datalist>
                                      </div>
                                      <div class="form-group col-md-2">
                                         <label for="exampleInputNumber1">Quantity</label><span style="color:red">*</span>
                                         <input type="number" class="form-control quantity_cash" id="quantity_cash" name="quantity_cash[]" min="0" required>
                                      </div>
                                      <div class="form-group col-md-2">
                                         <label for="exampleInputNumber1">Rate</label><span style="color:red">*</span>
                                         <input type="number" id="rate_cash"
                                         name="rate_cash[]" class="form-control brick_rate_cash" min="0" required>
                                      </div>
                                      <div class="form-group col-md-2">
                                         <label for="exampleInputNumber1">Total</label><span style="color:red">*</span>
                                         <input type="number" id="bill_amount_cash"
                                         name="bill_amount_cash[]" class="form-control bill_amount_cash" min="0" required>
                                      </div>
                                      </div> -->
                                 
                                        <div class="container-fluid">
                                       <div class="col-lg-12">
                                      <div class="row" id="dynamic_field2">
                                      </div>
                                      </div>
                            </div>
                                    <div class="col-md-12 row" style="margin-left:25px">
                                        <div class="form-group col-md-6">
                                         <label for="exampleInputNumber1">Total Bill Amount</label><span style="color:red">*</span>
                                         <input type="number" readonly class="form-control" name="total_amount_cash[]" id="total_amount_cash" value="0" required>
                                      </div>
                                      <div class="form-group col-md-6">
                                        <a href="#dynamic_field2"><button style="position: relative;top:35px" type="button" name="add_more" id="add_more" class="btn btn-success">Add More</button>
                                 </a>
                                      </div>
                                      </div>
                                      <button type="submit" style="position:relative;left:55px !important" name="add_cash_carry" value="submit" class="btn btn-primary">Submit</button>
                           </form>
                         
                   </div>
                   </div>
                   </div>
                </div>
             </div>
             <?php } ?>
          </div>
        </div>
    </div>
</div>

<?php require "footer.php";
?>
<?php
if(isset($_POST["add_sales_invoice"]))
{
    //print_r($_POST);
    // $count=count($_POST["quality_name"]);
    $count=$_POST["coun"];
    $date=$_POST["date"];
    $mobile_number=$_POST["mobile_number"];
    $customer_name=$_POST["customer_name"];
    $customer_address=$_POST["customer_address"];
    $boxes_arrived=$_POST["boxes_arrived"];
    $total_bill_amount = $_POST["totalamt"];
    $customer_id = $_POST["customer_id"];
    $patti_name = $_POST["patti"];
    // $reg_type="unsettled";
  
    $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
        $sales_sql=$connect->prepare("$sales_qry");
        $sales_sql->execute();
        $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
        $Last_id=$sales_row["id"]+1;
        $saleid = "C_".date("Ym")."0".$Last_id;

    // print_r($_POST);die();  
       $bill_amount=0; $tpend=0; $s=0; $tot=0;
    for($i=0;$i<=$s;$i++)
    {
    if($_POST["quantity"][$i]!=0 && $_POST["rate"][$i]!=0 && $_POST["rate"][$i]!=0){
        //&& $_POST["tray_type"][$i]!=""

        $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
        $sales_sql=$connect->prepare("$sales_qry");
        $sales_sql->execute();
        $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
        $Last_id=$sales_row["id"]+1;
        $sales_no = "CR_".date("Ym")."0".$Last_id;
        $grpname=$_POST['grpname'];
  
        $quality_name=$_POST["quality_name"][$i];
        $quantity=$_POST["quantity"][$i];
        // $type=$_POST['tray_type'][$i];
        $patid=$_POST['patid'][$i];
        $rate=$_POST["rate"][$i];
       $bill_amount=$quantity*$rate;
        $tamt=$quantity*100;
        $tpend+=$tamt;
        $s+=1;
  
        $tot+=$total_bill_amount;
        //$total_bill_amount+=$bill_amount;
    // print_r($s);die();
      
        //print_r($_POST);
        if($id==""){
      
            $add_sales_query="insert into sar_sales_invoice(groupname,date,sale_id,sales_no,mobile_number,customer_name,customer_address,boxes_arrived,quality_name,quantity,rate,bill_amount,total_bill_amount,customer_id,updated_by,credit_type,is_active,patti_name,pattiid) values('$grpname','$date','$saleid','$sales_no','$mobile_number','$customer_name','$customer_address','$boxes_arrived','$quality_name','$quantity','$rate','$bill_amount','$total_bill_amount','$customer_id','$username','Unsettled',1,'$patti_name','$patti')";
            // print_r($add_sales_query);die(); type '$type',
            $res=mysqli_query($con,$add_sales_query);
     
                // print_r("hello");die();
        //    echo $add_sales_query="INSERT INTO `sar_sales_invoice` SET
        //     groupname='$grpname',
        //     date='$date',
        //     sale_id='$saleid',
        //     sales_no='$sales_no',
        //     mobile_number='$mobile_number',
        //     customer_name='$customer_name',
        //     customer_address='$customer_address',
        //     boxes_arrived='$boxes_arrived',
        //     quality_name='$quality_name',
        //     quantity='$quantity',
        //     rate='$rate',
        //     bill_amount='$bill_amount',
        //     total_bill_amount='$total_bill_amount',
        //     customer_id='$customer_id',
        //     updated_by='$username',
        //     credit_type='Unsettled',
        //     is_active=1,
        //     patti_name='$patti_name',
        //     type='$type'";
        //     exit;

        // $res=mysqli_query($con,$add_sales_query);
     
// $check_patti1="select * from sar_patti where patti_date='$date' and is_active!=0 and remain_box!=-2 and quality_name='$quality_name' and supplier_id='$patti_name' and quality_name='$quality_name' order by id desc limit 1";
// $execheck1= $connect->prepare($check_patti1);
// $execheck1->execute();
// $exechec=$execheck1->fetch(PDO::FETCH_ASSOC);
//   $rembox=$exechec['remain_box'];

//     if($exechec['remain_box']==-1){
//     $bal_box=$exechec['quantity']-$quantity;
//     // print_r($bal_box);die();
   
//     $uppatti="update sar_patti set remain_box='$bal_box' where remain_box!=-2 and is_active!=0 and  quality_name='$quality_name' and supplier_id='$patti_name' and quality_name='$quality_name' order by id desc limit 1";
//     // print_r($uppatti."u");die();
//      $upexe=mysqli_query($con,$uppatti);
//     if($bal_box==0){
//         $uppatti1="update sar_patti set remain_box=-2 where is_active!=0 and  quality_name='$quality_name' and supplier_id='$patti_name' and quality_name='$quality_name' order by id desc limit 1";
//         //  print_r($exechec['remain_box'].$quantity.$pat_id."d");die();
//               $upexe=mysqli_query($con,$uppatti1);
      
//       }       }
//      else if($exechec['remain_box']>0){
//         $balbox=$exechec['remain_box']-$quantity;
   
//         $uppatti="update sar_patti set remain_box='$balbox' where remain_box!=-2 and is_active!=0 and  quality_name='$quality_name' and supplier_id='$patti_name' and quality_name='$quality_name' order by id desc limit 1";
//         // print_r($exechec['remain_box'].$quantity.$pat_id."s");die();
//          $upexe=mysqli_query($con,$uppatti);
//         if($balbox==0){
//             $uppatti1="update sar_patti set remain_box=-2 where is_active!=0 and  quality_name='$quality_name' and supplier_id='$patti_name' and quality_name='$quality_name' order by id desc limit 1";
//             //  print_r($exechec['remain_box'].$quantity.$pat_id."d");die();
//                   $upexe=mysqli_query($con,$uppatti1);
          
//           }
//  }

$check_patti1="select * from sar_patti where patti_id='$patid' and is_active!=0 and remain_box!=-2 order by id desc limit 1";
$execheck1= $connect->prepare($check_patti1);
$execheck1->execute();
$exechec=$execheck1->fetch(PDO::FETCH_ASSOC);
  $rembox=$exechec['remain_box'];

    if($exechec['remain_box']==-1){
    $bal_box=$exechec['quantity']-$quantity;
    // print_r($bal_box);die();
   
    $uppatti="update sar_patti set remain_box='$bal_box' where remain_box!=-2 and is_active!=0 and  patti_id='$patid' order by id desc limit 1";
    // print_r($uppatti."u");die();
     $upexe=mysqli_query($con,$uppatti);
    if($bal_box==0){
        $uppatti1="update sar_patti set remain_box=-2 where is_active!=0 and  patti_id='$patid' order by id desc limit 1";
        //  print_r($exechec['remain_box'].$quantity.$pat_id."d");die();
              $upexe=mysqli_query($con,$uppatti1);
      
      }       }
     else if($exechec['remain_box']>0){
        $balbox=$exechec['remain_box']-$quantity;
   
        $uppatti="update sar_patti set remain_box='$balbox' where remain_box!=-2 and is_active!=0 and  patti_id='$patid' order by id desc limit 1";
        // print_r($exechec['remain_box'].$quantity.$pat_id."s");die();
         $upexe=mysqli_query($con,$uppatti);
        if($balbox==0){
            $uppatti1="update sar_patti set remain_box=-2 where is_active!=0 and  patti_id='$patid' order by id desc limit 1";
            //  print_r($exechec['remain_box'].$quantity.$pat_id."d");die();
                  $upexe=mysqli_query($con,$uppatti1);
          
   }
 }
 
$sqltray="select * from trays where name='$customer_id' and type='$type' order by id desc limit 1";
 $exetray=mysqli_query($con,$sqltray);
 $restray=mysqli_fetch_assoc($exetray);
 
 $sqllast="select * from trays order by id desc limit 1";
 $exelast=mysqli_query($con,$sqllast);
 $reslast=mysqli_fetch_assoc($exelast);
 $box=$quantity;
 if($reslast['ab_tray']<0){
 $abtray=isset($reslast['ab_tray'])?$reslast['ab_tray']+$quantity:$box;
 }
 else{
    $abtray=isset($reslast['ab_tray'])?$reslast['ab_tray']-$quantity:$box;
 }
 $tray=($restray['inhand']!=0)?$restray['inhand']+$quantity:$box;
 
 if($type=="Small Tray")
 {
 $small=$restray['smalltray']+$quantity;
 if($reslast['absmall']<$quantity){
 $absmall=$reslast['absmall']+$quantity;
 }
 else{
 $absmall=$reslast['absmall']-$quantity;
 }
 $big=isset($reslast['bigtray'])?$reslast['bigtray']:0;
 $abbig=isset($reslast['abbig'])?$reslast['abbig']:0;
 }
 else if($type=="Big Tray")
 {
 $big=$restray['bigtray']+$quantity;
 if($reslast['abbig']<$quantity){
 $abbig=$reslast['abbig']+$quantity;
 }
 else{
 $abbig=$reslast['abbig']-$quantity;
 }
 $small=isset($reslast['smalltray'])?$reslast['smalltray']:0;
 $absmall=isset($reslast['absmall'])?$reslast['absmall']:0;
 }
 // print_r($sqltray);die();


 //    print_r($abtray);die();
 // $boxes=-$boxes_arrived;
//  $instray="insert into trays(date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,ids,smalltray,bigtray,absmall,abbig) values('$date','$customer_id','$quantity','$type','Outward from sales $customer_name',0,'$box','$tray','Admin','Customer','$abtray','$saleid',$small,$big,$absmall,$abbig)";
// //  print_r($instray."d");die();
//    $trayexe=mysqli_query($con,$instray);
 
}
}

}

$sqlbal="select * from payment_sale where customerid='$customer_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

$tray="SELECT * FROM trays where name='$customer_id' and type='$type' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=isset($tray['smalltray'])?$tray['smalltray']:0;
$big=isset($tray['bigtray'])?$tray['bigtray']:0;
$inhand=isset($tray['inhand'])?$tray['inhand']:0;

// $exebal = $connect->prepare("$sqlbal");
// $exebal->execute(); 
// $valbal = $exebal->fetch(PDO::FETCH_ASSOC);
// $no=$valbal->rowCount();
// print_r($no);die();
if($valbal['total']==0){
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   

        $total=round($tot);
      $am=$valbal["total"];
    
      $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$date','$customer_name',$am,$tot,0,0,0,$total,'$customer_id','$saleid',$small,$big,$inhand)";
    // print_r($insbal."ok1");die(); 
      $exe=mysqli_query($con,$insbal);
    
}
else{
if($no==0) {
    if($valbal==""){
   $pay_id = "PAY".date("Ym")."1";
    }
    else{
        $paybal = $valbal["id"] + 1;
        $pay_id = "PAY" . date("Ym") . $paybal;   
   }
//    print_r($sqlbal);die();

$ob="select * from sar_opening_balance where name='$supplier_id' order by id desc limit 1";
$op = $connect->prepare("$ob");
$op->execute(); 
$opb = $op->fetch(PDO::FETCH_ASSOC);
$opne=$opb['amount'];
$ob_supplier_id=$opb['balance_id'];
if($opne==0){
    $opne=0;
}
else{
    $opne=$opne;
}

// $tr="select * from trays where name='$supplier_id' and type='$type' order by id desc limit 1";
// $tra = $connect->prepare("$tr");
// $tra->execute(); 
// $trayp = $tra->fetch(PDO::FETCH_ASSOC);
// if($trayp==""){
//     $traypay=$boxes_arrived*100;    
// }
// else{
// $traypay=$trayp['inhand']*100;
// }
$pay=0;



if($valbal['total']==""){
    $total1=$opne+$tot-($traypay);
    $total = $valbal["total"]+$total1;
 }
 else{
    $total=$opne+$tot-($traypay);  
 }
 
 $total=round($total);

  $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$date','$customer_name',$opne,$tot,0,0,0,$total,'$customer_id','$saleid',$small,$big,$inhand)";
//   print_r($insbal."okh");die(); 
  $exe=mysqli_query($con,$insbal);
}
else{
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   
  
    if($valbal['total']!=0){
        $ob="select * from payment_sale where customerid='$customer_id' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['total'];
        // print_r($opne);die();
        $ob_supplier_id="";
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        }
    }
    else{
        $ob="select * from sar_opening_balance where name='$customer_id' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['amount'];
        // print_r($opne);die();
        $ob_supplier_id=$opb['balance_id'];
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        } 
    }

if($valbal['total']==""){
    $total1=$opne+$tot-($traypay);
    $total = $valbal["total"]+$total1;
 }
 else{
    $total=$opne+$tot-($traypay);  
 }
 
      $total=round($total);
   

 $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$date','$customer_name',$opne,$tot,0,0,0,$total,'$customer_id','$saleid',$small,$big,$inhand)";
//   print_r($insbal."ko");die(); 
  $exe=mysqli_query($con,$insbal);

}
}

header('Location: view_sales_invoice.php');

}
else if(isset($_POST["edit_sales_invoice"])){
     $count=count($_POST["quality_name"]);
    $date=$_POST["date"];
    $mobile_number=$_POST["mobile_number"];
    $customer_name=$_POST["customer_name"];
    $customer_address=$_POST["customer_address"];
    $boxes_arrived=$_POST["boxes_arrived"];
    $total_bill_amount = $_POST["totalamt"];
    $customer_id = $_POST["customer_id"];
    $patti_name = $_POST["patti"];
    $grpname=$_POST['grpname'];
 
    $bill_amount=0;
    if(isset($_POST["delete_rec_id"])){
    $del_count = count($_POST["delete_rec_id"]);
    for($i=0;$i<$del_count;$i++)
   {
$id=$_POST["delete_rec_id"][$i];

$sqlfetch="select * from sar_sales_invoice WHERE id='$id'";
$sqlexe=mysqli_query($con,$sqlfetch);
$idfetch=mysqli_fetch_assoc($sqlexe);
$sid=$idfetch['sale_id'];
$cid=$idfetch['customer_id'];

    $sqltray="select * from trays where ids='$sid' and type='$type' order by id desc limit 1";
 $exetray=mysqli_query($con,$sqltray);
 $restray=mysqli_fetch_assoc($exetray);
 
 $sqllast="select * from trays order by id desc limit 1";
 $exelast=mysqli_query($con,$sqllast);
 $reslast=mysqli_fetch_assoc($exelast);
 $box=$quantity;
 
 $abtray=($reslast['ab_tray']!=0)?$reslast['ab_tray']-$quantity:$box;
 $tray=($restray['inhand']!=0)?$restray['inhand']+$quantity:$box;
 
 if($type=="Small Tray")
 {
 $small=$restray['smalltray']+$quantity;
 $absmall=($reslast['absmall']!=0)?$reslast['absmall']-$quantity:$box;
 $big=$restray['bigtray'];
 $abbig=$restray['abbig'];
 }
 else if($type=="Big Tray")
 {
 $big=$restray['bigtray']+$quantity;
 $abbig=($restray['abbig']!=0)?$restray['abbig']-$quantity:$box;
 $small=$reslast['smalltray'];
 $absmall=$reslast['absmall'];
 }

//  $instray="insert into trays(date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,ids,smalltray,bigtray,absmall,abbig) values('$date','$customer_id','$quantity','$type','Outward from sales $customer_name',0,'$box','$tray','Admin','Customer','$abtray','$saleid',$small,$big,$absmall,$abbig)";
//    $trayexe=mysqli_query($con,$instray);
 
   $sqlbal="select * from payment_sale where saleid='$saleid' and customerid='$cid' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);


$tray="SELECT * FROM trays where name='$cid' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   

$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];

    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   
    
 if($valbal['total']==0){
        $total=$tot;
      }
     else{
        $total=$valbal['total']-$idfetch['total_bill_amount']+$tot;  
        // $total=$valbal['total']-+$tot;  
     }
    
    $am=$valbal['total'];
    $sal=$idfetch['total_bill_amount'];
    $total=round($total);
    
      $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$date','$customer_name',$am,$sal,0,0,0,$total,'$cid','$saleid',$small,$big,$inhand)";
    // print_r($insbal."e".$am."-".$total.$tot);die();
      $exe=mysqli_query($con,$insbal);

       $del_sales_query = "DELETE FROM `sar_sales_invoice` WHERE id=".$_POST["delete_rec_id"][$i];
       $del_sales_sql = mysqli_query($con, $del_sales_query);

   }
}
    for($i=0;$i<$count;$i++)
    {
        $quality_name=$_POST["quality_name"][$i];
        // $type=$_POST['tray_type'][$i];
        $quantity=$_POST["quantity"][$i];
        // $type=$_POST['tray_type'][$i];
        $rate=$_POST["rate"][$i];
        $bill_amount=$quantity*$rate;
    }
  for($i=0;$i<$count;$i++)
  {
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
    //   $type=$_POST['tray_type'][$i];
      $rate = $_POST["rate"][$i];
      $rec_id = $_POST["rec_id"][$i];
      $bill_amount=$quantity*$rate;
      
     if($rec_id == ""){
        $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
        $sales_sql=$connect->prepare("$sales_qry");
        $sales_sql->execute();
        $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
        $Last_id=$sales_row["id"]+1;
        $sales_no = "CR_".date("Ym")."0".$Last_id;
       
         $sales_qry="SELECT id FROM sar_sales_invoice ORDER BY id DESC LIMIT 1 ";
        $sales_sql=$connect->prepare("$sales_qry");
        $sales_sql->execute();
        $sales_row=$sales_sql->fetch(PDO::FETCH_ASSOC);
        $Last_id=$sales_row["id"]+1;
        $saleid = "C_".date("Ym")."0".$Last_id;
        
        // $add_sales_query="INSERT INTO `sar_sales_invoice` SET
        //     date='$date',
        //     groupname='$grpname',
        //     sale_id='$saleid',
        //     sales_no='$sales_no',
        //     mobile_number='$mobile_number',
        //     customer_name='$customer_name',
        //     customer_address='$customer_address',
        //     boxes_arrived='$boxes_arrived',
        //     quality_name='$quality_name',
        //     quantity='$quantity',
        //     rate='$rate',
        //     bill_amount='$bill_amount',
        //     total_bill_amount='$total_bill_amount',
        //     customer_id='$customer_id',
        //     updated_by='$username',
        //     credit_type='Unsettled',
        //     is_active=1,
        //     patti_name='$patti_name',
        //     ";
         //   print_r($add_sales_query);die();
         $add_sales_query="insert into sar_sales_invoice(groupname,date,sale_id,sales_no,mobile_number,customer_name,customer_address,boxes_arrived,quality_name,quantity,rate,bill_amount,total_bill_amount,customer_id,updated_by,credit_type,is_active,patti_name,pattiid) values('$grpname','$date','$saleid','$sales_no','$mobile_number','$customer_name','$customer_address','$boxes_arrived','$quality_name','$quantity','$rate','$bill_amount','$total_bill_amount','$customer_id','$username','Unsettled',1,'$patti_name','$patti')";
         // print_r($add_sales_query);die(); type ,'$type'
  $res=mysqli_query($con,$add_sales_query);

            
$sqlbal="select * from payment_sale where saleid='$saleid' and customerid='$customer_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

$tray="SELECT * FROM trays where name='$customer_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];


// $exebal = $connect->prepare("$sqlbal");
// $exebal->execute(); 
// $valbal = $exebal->fetch(PDO::FETCH_ASSOC);
// $no=$valbal->rowCount();
// print_r($no);die();
  $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   


if($valbal['total']==0){
   $total=$total_bill_amount;
}
    else{
        // $total=$valbal['total']+($total_bill_amount-$valbal['sale']);  
        $total=($valbal['total']+$total_bill_amount)-$valbal['sale']; 
        }
        
         
    $sale=$total_bill_amount-$valbal['sale'];
    $total=round($total);
    $am=$valbal['total'];
    
      $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$date','$customer_name',$am,$sale,0,0,0,$total,'$customer_id','$saleid',$small,$big,$inhand)";
    // print_r($insbal."ok");die(); 
      $exe=mysqli_query($con,$insbal);
    
          // echo $add_sales_query;
}
 
else if($rec_id != ""){
        $add_sales_query = "UPDATE `sar_sales_invoice` SET
            date='$date',
            sale_id='$saleid',
            sales_no='$sales_no',
            mobile_number='$mobile_number',
            customer_name='$customer_name',
            customer_address='$customer_address',
            boxes_arrived='$boxes_arrived',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            bill_amount='$bill_amount',
            total_bill_amount='$total_bill_amount',
            customer_id='$customer_id',
            updated_by='$username',
            credit_type='Unsettled',
            is_active=1
            WHERE id ='".$rec_id."'
            ";
            
        $add_sales_sql = mysqli_query($con, $add_sales_query);
    }
    
   }
  $balance_qry="SELECT inhand FROM trays ORDER BY id DESC LIMIT 1 ";
   $balance_sql=$connect->prepare("$balance_qry");
   $balance_sql->execute();
 
  header('Location: view_sales_invoice.php');
}
if(isset($_POST["add_cash_carry"]))
{
    $type1=$_POST['type1'];
    $cash_qry="SELECT id FROM sar_cash_carry ORDER BY id DESC LIMIT 1 ";
    $cash_sql=$connect->prepare("$cash_qry");
    $cash_sql->execute();
    $cash_row=$cash_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id_cash=$cash_row["id"]+1;
    $cash_no = "CC_".date("Ym")."0".$Last_id_cash;
    $customer_id1 = $_POST["search_vals"];
 
    $cuname="select * from sar_customer where customer_no='$customer_id1'";
    $cuexe=mysqli_query($con,$cuname);
    $cuval=mysqli_fetch_assoc($cuexe);
    $cusname=$cuval['customer_name'];
    $grpname1=$_POST['grpname1'];
        // print_r($cusname);die();
//    print_r($_POST);die();
    $count=count($_POST["quality_name_cash"]);
    $cash_id=strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6));
    $date=$_POST["date_cash"];
    
    
    // $reg_type="unsettled";
       
       $bill_amount=0;
       $total_quantity = 0;
    for($i=0;$i<$count;$i++)
    {
        $quality_name_cash=$_POST["quality_name_cash"][$i];
        $qty_cash=$_POST["quantity_cash"][$i];
        $rate_cash=$_POST["rate_cash"][$i];
        $bill_amount_cash=$qty_cash*$rate_cash;
        $total_bill_amount_cash+=$bill_amount_cash;
        $total_quantity += $qty_cash;
        //print_r($_POST);
         if($id==""){
            $add_sales_query1="INSERT INTO `sar_cash_carry` SET
            groupname='$grpname1',
            customer_name='$cusname',
            date='$date',
            cash_id='$cash_id',
            cash_no='$cash_no',
            quality_name='$quality_name_cash',
            quantity='$qty_cash',
            rate='$rate_cash',
            bill_amount='$bill_amount_cash',
            total_bill_amount='$total_bill_amount_cash',
            updated_by='$username',
            is_active=1,
            customer_id='$customer_id1',
            type='$type1'
            ";
            $res1=mysqli_query($con,$add_sales_query1);
        //   echo $add_sales_query1;exit;
    //    echo $add_sales_query1;exit;
         
     if($res1){
    $balance_qry="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
    $balance_sql=$connect->prepare("$balance_qry");
    $balance_sql->execute();
    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC); 
    if($bal_row["balance"]!=""){   
    $balance = $bal_row["balance"] + $total_bill_amount_cash;
    }
    else{
        $balance = $total_bill_amount_cash;
    }
    //print_r($balance);die();
    $fin_trans_qry = "INSERT INTO financial_transactions SET 
                     date = '$date',
                     credit = $total_bill_amount_cash,
                     balance = $balance,
                     description = 'Cash and Carry. ID : $cash_no',
                     cash_carry_id = '$cash_no',
                     ids='$customer_id1'
                     ";
   $res2=mysqli_query($con,$fin_trans_qry);
if($res2) {
//    $balance_qry="SELECT inhand FROM trays where name='$customer_id1' ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    if($bal_row["inhand"]!=""){
//    $balance = $bal_row["inhand"] - $total_quantity;
//    }
//    else{
//     $balance = $total_quantity;
//    }
//    $tray_trans_qry = "INSERT INTO trays SET 
//                   date = '$date',
//                   name = '$customer_id1',
//                   category = 'Customer',
//                   outward = $total_quantity,
//                   inhand = $balance,
//                   updated_by = 'Admin',
//                   type = '$type1',
//                   no_of_trays = $balance,
//                   description = 'outward from cash & carry sales $customer_id1'";
//    $res2=mysqli_query($con,$tray_trans_qry);

   header('Location: view_sales_invoice.php');
}
         }
        }
}
}

?>
<script>
function total_qty(){
var sum_qty = 0;
   $('.sum_qty').each(function() {
      sum_qty += Number($(this).val());
    }); 
 $('#exampleInputNumber1').val(sum_qty);
}
function total_amt(col){
var sum_tol = 0;
   $('.sum_tol').each(function() {
      sum_tol += Number($(this).val());
    }); 
 var rate_arr = parseInt($('#rate_arr'+col).val());
 var qty = parseInt($('#qty'+col).val());
 var total_amount = (qty * rate_arr);
 $('#bill_amount'+col).val(total_amount);
 
 total_amt_ov();
}

function total_amt_ov(){
var sum_tol_ov = 0;
   $('.sum_tol_ov').each(function() {
      sum_tol_ov += Number($(this).val());
    }); 
  $('#totalamt').val(sum_tol_ov);
}
</script>
<script>
    $(document).ready(function(){
        //  $("#rate").keyup(function(){
        //   var quantity=$("#quantity").val();
        //   var rate=$(this).val();
        //   var bill_amount = quantity * rate;
        //   $("#bill_amount").val(bill_amount.toFixed(2));
        // });
    
        var i=<?=$cnt?>;  
      $('#add').click(function(){  
          // i++;  
          var sam = $('#patti :selected').val();
            var da = $('#pat_date').val();
     
            $.ajax({
         type: "POST",
         url: "forms/ajax_request.php",
         data: {
            "action": "pat_list",
            "supplier_id":sam,
            "supplier_date":da
         },
         dataType: "json",
         success: function(result) {
            if (result) {
            //    alert(result[0].quantity)
              // $("#searchval_disp").html("");
              var len=result.length;
              $("#coun").val(len);
             // alert(len)
           //  $('#dynamic_field').html('');
//                 var qualityname=[];
// for(i=0;i<len;i++){
//     qualityname.push(result[i].quality_name);
// }
//alert(qualityname);
//var text = "";
var remain;
$('#dynamic_field').html('');
           for(j=0;j<len;j++)
           {

            if(result[j].remain_box==-1){
remain=result[j].quantity;
            }
            else{
                remain=result[j].remain_box;
            }
            $('#dynamic_field').append('<div class="col-md-12 row qualityBox" id="inputFormRow" style="margin:10px;"><div class="form-group col-md-2"><select class="form-control" name="quality_name[]" id="quality"><option value="">--Choose Quality--</option><option value="'+result[j].quality_name+'" selected>'+result[j].quality_name+'</option></select></div><div col-md-2"><input type="text" class="form-control name="size" read-only value="'+remain+'"/></div><div class="form-group col-md-2"><input type="hidden" name="patid[]" value="'+result[j].patti_id+'"><input type="text" class="form-control qty sum_qty" placeholder="Enter Quantity" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr sum_tol" placeholder="Rate" onkeyup=total_amt("'+i+'"); id="rate_arr'+i+'" myattr="'+i+'"name="rate[]"></div><div class="col-md-2"><input type="text" readonly name="bill_amount[]" id="bill_amount'+i+'" class=" sum_tol_ov form-control"></div><br><div class="form-group col-md-2"><button id="removeRow" type="button" class=" btn btn-danger">Remove</button></div></div></div>');
            $(".rate_arr").on('change',function(){
                //<div class="form-group col-md-2"><select name="tray_type[]" style="pointer_events:none" class="form-control"><option value="'+result[j].type+'" selected>'+result[j].type+'</option></select></div>
                var id=$(this).attr("myattr");
                var qty=$("#qty"+id).val();
                var rate_arr=$(this).val();
                var total=qty*rate_arr;
              // alert*(total);
                $("#bill_amount"+id).val(total.toFixed(2));
            });
            i++;       }
              //      var text = "<option>"+qualityname[j]+"</option>";
        //      $('#quality').append('<option>'+qualityname[j]+'</option>');
      //   }
    //    $('#dynamic_field').append('</datalist></div><div class="form-group col-md-2"><input type="text" class="form-control qty sum_qty" placeholder="Enter Quantity" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr sum_tol" placeholder="Rate" onkeyup=total_amt("'+i+'"); id="rate_arr'+i+'" myattr="'+i+'"name="rate[]"></div><div class="col-md-2"><input type="text" readonly name="bill_amount[]" id="bill_amount'+i+'" class=" sum_tol_ov form-control"></div><br><div class="form-group col-md-2"><button id="removeRow" type="button" class=" btn btn-danger">Remove</button></div></div></div>');


        }
    }
});
      });
      
      <?php
        $sel_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
        $sel_sql= $connect->prepare($sel_qry);
        $sel_sql->execute();
     
       ?>
      $('#add_more').click(function(){  
          // i++;  
           $('#dynamic_field2').append('<div class="col-md-12 row" id="inputFormRow2" style="margin:10px;"><div class="form-group col-md-2"><input class="form-control" list="qualitynamecash" name="quality_name_cash[]" id="qualitynamecashs"><datalist class="" id="qualitynamecash" name="quality_name_cash[]"><option value="">--Choose Quality--</option><?php   while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){ echo '<option>'.$sel_row["quality_name"].'</option>';} ?></datalist></div><div class="form-group col-md-2"><input type="text" class="form-control qty_cash" name="quantity_cash[]" id="qty_cash'+i+'"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr_cash" id="rate_arr_cash'+i+'" myattr1="'+i+'"name="rate_cash[]"></div><div class="form-group col-md-2"><input type="text" name="bill_amount_cash[]" id="bill_amount_cash'+i+'" class="form-control bill_amount_cash"></div><div class="form-group col-md-2"><button id="removeRow2" type="button" class="btn btn-danger">Remove</button></div></div></div>');  
            	           //Cash & Carry
                $(".rate_arr_cash").keyup(function(){
                    var id=$(this).attr("myattr1");
                    //console.log(id);
                    var qty_cash=$("#qty_cash"+id).val();
                    var rate_arr_cash=$(this).val();
                    var total=qty_cash*rate_arr_cash;
                $("#bill_amount_cash"+id).val(total.toFixed(2));
               // console.log(total);
                update_total_amount_cash()
            });
            i++;
   
      });
      
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
              var id = $(this).attr("data-rec-id");
          if(id){
              $('#delete_rec_id_list').append("<input type='hidden' class='form-control' id='delete_rec_id' name='delete_rec_id[]' value= "+id+" readonly>"
              );
          }
          var quaBoxTotal = 0;
      $(".qualityBox").each(function(index){
        var value = $(this).find(".boxTotal").val();
        if (value) {
           quaBoxTotal += parseFloat(value);
        }
      });
      $("#totalamt").val(quaBoxTotal);
    //   alert(quaBoxTotal);
      calculate_grantotal();
   });
   
     function calculate_grantotal() {
    var totalBoxAmount = ( $("#totalamt").val() ) ? parseFloat($("#totalamt").val()) : 0;
    var total_deduction = ( $("#total_deduction").val() ) ? parseFloat($("#total_deduction").val()) : 0;
    $("#grandTotal").val(totalBoxAmount - total_deduction);
}

        $(document).on('click', '#removeRow2', function() {
                  $(this).closest('#inputFormRow2').remove();
               });
        
        // $(".rate").keyup(function(){
        //   var quantity=$(".quantity").val();
        //   var rate=$(this).val();
        //   var bill_amount = quantity * rate;
        //   $(".bill_amount").val(bill_amount.toFixed(2));
        //   //console.log("403",bill_amount);
        //   update_total_amount()
        // });
        // Cash & Carry
        $(".brick_rate_cash").keyup(function(){
           var quantity_cash=$(".quantity_cash").val();
           var brick_rate_cash=$(this).val();
           var bill_amount_cash = quantity_cash * brick_rate_cash;
           $(".bill_amount_cash").val(bill_amount_cash.toFixed(2));
           update_total_amount_cash()
        });
        // Cash & Carry
        function update_total_amount_cash(){
            var sum=0;
            $('.bill_amount_cash').each(function() {
                sum = sum  + parseInt($(this).val());
                //console.log("430",sum);
            });
            $("#total_amount_cash").val(sum.toFixed(2));
        }
        //Credit
        // function update_total_amount(){
        //     var sum=0;
        //     $('.bill_amount').each(function() {
        //         sum = sum  + parseInt($(this).val());
        //         //console.log("439",sum);
        //     });
        //     $("#totalamt").val(sum.toFixed(2));
        // }
   
    // $("#searchval").on("change",function(){
        $("#search_val").on('input', function () {
   var customer_name=$(this).val();
	$.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_sales_search","customer_name":customer_name},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        
                        $("#grp_cust_name").val(result.data.grp_cust_name).attr('readonly', true);
                        $("#customer_id").val(result.data.customer_id).attr('readonly', true);
                        $("#customer_name").val(result.data.customer_name).attr('readonly', true);
                        $("#mobile_number").val(result.data.mobile_number).attr('readonly', true);
                        $("#customer_address").val(result.data.customer_address).attr('readonly', true);
                } 
            }
                
        })
    });

    // $("#searchval1").on("change",function(){
	// var customer_name=$(this).val();
	// $.ajax({
    //             type:"POST",
    //             url:"forms/ajax_request_view.php",
    //             data:{"action":"view_sales_search","customer_name":customer_name},
    //             dataType:"json",
    //             success:function(result){
    //                 if(result.status==1){
                        
    //                     $("#grp_cust_name").val(result.data.grp_cust_name).attr('readonly', true);
    //                     $("#customer_id").val(result.data.customer_id).attr('readonly', true);
    //                     $("#customer_name").val(.customer_name).attr('readonly', true);
    //                     $("#mobile_number").val(result.data.mobile_number).attr('readonly', true);
    //                     $("#customer_address").val(result.data.customer_address).attr('readonly', true);
    //             } 
    //         }
                
    //     })
    // });
    
    $('.mymodal').on('click', function (){
        $( "#myModal" ).modal( "show" );
    });
    
     $('.close').on('click', function (){
        $( "#myModal" ).modal( "hide" );
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
                <h4 class="modal-title" style="color:#f55989;">Add Customer</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                            
                        <form id="form1" action="" method="POST">
                        
                          <div id="customer_form">
                            <h4>Customer Form</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            
                                            <label>Customer Name</label><span style="color:red">*</span>
                                            <input type="text" class="form-control " name="name_modal" id="name_modal" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Mobile Number</label><span style="color:red">*</span>
                                            <input type="text" class="form-control" name="mobile_modal" id="mobile_modal" maxlength="10" pattern="^[6-9]\d{9}$" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Address</label><span style="color:red">*</span>
                                            <input type="text" class="form-control" name="address_modal" id="address_modal" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="customer_add" class="btn btn-primary" value="Submit">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
//print_r($_POST);
if (isset($_POST["customer_add"])) {
    $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
    $customer_sql=$connect->prepare("$customer_qry");
    $customer_sql->execute();
    $customer_row1=$customer_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$customer_row1["id"]+1;
    $customer_no = "CUS_".date("Ym")."0".$Last_id;
   $name_modal = $_POST["name_modal"];
   $mobile_modal = $_POST["mobile_modal"];
   $address_modal = $_POST["address_modal"];
   
   $customer_query = "INSERT INTO `sar_customer` SET
   
            customer_no='$customer_no',
            customer_name='$name_modal',
            contact_number1='$mobile_modal',
            address='$address_modal',
            is_active=1
            ";
            
        $customer_sql = mysqli_query($con, $customer_query);
   
}
    ?>
<script>
// $("#searchval").chosen();
//$("#searchval1").chosen();
</script>
<script>
      $("#grpname").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchsup","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#searchval").empty();
                    for(var i=0;i<len;i++){
                    $("#searchval").append('<option>'+result[i].customer_name+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});
$("#grpname1").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchsup","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#searchvals").empty();
                    for(var i=0;i<len;i++){
                    $("#searchvals").append('<option value='+result[i].customer_no+'>'+result[i].customer_name+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});//   $("#searchval").chosen();
</script>
