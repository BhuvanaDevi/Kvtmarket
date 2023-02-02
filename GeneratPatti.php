<?php

use function PHPSTORM_META\type;

$date = date("Y-m-d");
require "header.php";

$patti_qry = "SELECT * FROM sar_patti ORDER BY id DESC LIMIT 1";
$patti_sql = $connect->prepare("$patti_qry");
$patti_sql->execute(); 
$patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);


if(isset($_REQUEST['patti_id'])!=""){
    $patti_id=$_REQUEST["patti_id"];
} 
else {
$Last_id = $patti_row["id"] + 1;
$patti_id = "P_" . date("Ym") . "0" . $Last_id;
}

$submit_action = 'add_patti';
$patti_id = "";
$quality_box = "";
$mobile_number = "";
$supplier_name = "";
$supplier_address = "";
$boxes_arrived = "";
$lorry_no = "";
$commision = "";
$lorry_hire = "";
$box_charge = "";
$cooli = "";
$total_deduction = "";
$total_bill_amount = "";
$net_bill_amount = "";
$supplier_id="";
$cnt = 0;

$quality_name_list = array();
$quality_name_qry = "SELECT distinct quality_name from `quality` order by quality_name ASC ";
$quality_name_qry_stmt = $connect->prepare($quality_name_qry);
$quality_name_qry_stmt->execute();
while ($sel_row = $quality_name_qry_stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($quality_name_list, $sel_row["quality_name"]);
}

if(isset($_REQUEST['req'])!="" && $_REQUEST["req"] == 'edit'){
    $submit_action = 'edit_patti';
    $patti_id = $_REQUEST["patti_id"];
    $patti_qry = "SELECT * FROM sar_patti WHERE pat_id = '".$patti_id."'";
    $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute();
	while ($data_row = $patti_sql->fetch(PDO::FETCH_ASSOC)) {
	   
	   if($cnt == 0){
        $group_name = $data_row['groupname'];
        $mobile_number = $data_row['mobile_number'];
	       $supplier_name = $data_row['supplier_name'];
	       $supplier_address = $data_row['supplier_address'];
	       $boxes_arrived = $data_row['boxes_arrived'];
	       $lorry_no = $data_row['lorry_no'];
	       $commision = $data_row['commision'];
	       $lorry_hire = $data_row['lorry_hire'];
	       $box_charge = $data_row['box_charge'];
	       $cooli = $data_row['cooli'];
	       $total_deduction = $data_row['total_deduction'];
	       $total_bill_amount = $data_row['total_bill_amount'];
	       $net_bill_amount = $data_row['net_bill_amount'];
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
	//             <div class="form-group">
	//             <label for="exampleFormControlSelect1">Select Quality</label>
	//             <select class="form-control" id="exampleFormControlSelect1" name="quality_name[]">
    // 	                <option value="">--Choose Quality--</option>';
    //         	   foreach ($quality_name_list as $value){
    //         	       $selected = '';
    //         	       if($value == $data_row['quality_name']){
    //         	           $selected = 'selected';
    //         	       }
    //         	       $quality_box .= '<option '. $selected.'>'.$value.'</option>';
    //         	   }
    // 	        $quality_box .= '</select>
	//         </div>
	//         <div>
	//             <label>Quantity</label>
	//             <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" onchange="calculate_bill_amount('.$cnt.')" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'" min="0">
	//         </div> 
	       
	//         <div class="form-group">
	//             <label for="exampleInputNumber1">Rate</label>
	//             <input type="number" onchange="calculate_bill_amount('.$cnt.')" class="form-control rate_arr" id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'" min="0">
	//         </div>
	//         <div>
	//             <label>Total</label>
	//             <input type="text" name="bill_amount[]" class="form-control boxTotal" id="total_amount' . $cnt . '" value="'.$data_row['bill_amount'].'">
	//             <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'" min="0" readonly>
	//         </div><br>
	//         <button id="removeRow" data-rec-id="' . $data_row['id'] . '" type="button" class="btn btn-danger">Remove</button>
	//         </div>
	//     </div>';

    //iq-card col-md-89 qualityBox
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
	        <div class="col-md-2">
	            <label>Quantity</label>
	            <input type="text" class="form-control qty sum_qty" onkeyup="total_qty();" onchange="calculate_bill_amount('.$cnt.')" name="quantity[]" myattr="' . $cnt . '" id="qty' . $cnt . '" value="'.$data_row['quantity'].'" min="0">
	        </div> 
	       
	        <div class="form-group col-md-2">
	            <label for="exampleInputNumber1">Rate</label>
	            <input type="number" onchange="calculate_bill_amount('.$cnt.')" class="form-control rate_arr" id="rate_arr' . $cnt . '" myattr="' . $cnt . '" name="rate[]" value="'.$data_row['rate'].'" min="0">
	        </div>
            <div class="form-group col-md-2">
            <label for="exampleInputNumber1">Type</label>
            <input type="text" class="form-control rate_arr" id="type' . $cnt . '" myattr="' . $cnt . '" name="type[]" value="'.$data_row['type'].'" readonly>
        </div>
	        <div class="col-md-2">
	            <label>Total</label>
	            <input type="text" name="bill_amount[]" class="form-control boxTotal" id="total_amount' . $cnt . '" value="'.$data_row['bill_amount'].'">
	            <input type="hidden" name="rec_id[]" class="form-control boxTotal" id="rec_id' . $cnt . '" value="'.$data_row['id'].'" min="0" readonly>
	        </div>
            <div class="col-md-2">
	        <button id="removeRow" data-rec-id="' . $data_row['id'] . '" type="button" class="btn btn-danger" style="padding:8px !important;position:relative;top:30px !important">Remove</button>
	        </div>
	    </div>';
	   $cnt = $cnt + 1;
	}
} else {
    $req="";
    $patti_qry = "SELECT id FROM sar_patti ORDER BY id DESC LIMIT 1 ";
    $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute();
    $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id = $patti_row["id"] + 1;
    $patti_id = "PAT_" . date("Ym") . "0" . $Last_id;
}


if(isset($_REQUEST['id'])!=""){
    $id=$_REQUEST["id"];
} else {
    $id="";
}






$val="select *,SUM(bill_amount) as tb from sar_patti where pat_id='$patti_id'";
$exe=mysqli_query($con,$val);
$valg=mysqli_fetch_assoc($exe);
$no=mysqli_num_rows($exe);

// print_r($valg['supplier_name']);die();
?>

<form method="post" action="#" class="searchbox">
    <div id="content-page" class="content-page">
        <div class="container-fluid">
          <div class="row">
              <div class=" col-lg-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Generate Patti </h4>
                          </div>
                        </div>
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
                            <input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="<?= $valg['supplier_id'] ?>" >
                            <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                         <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                  <input type="hidden" name="gn" id="gn" value="<?=$group_name?>" />
                                         <select class="form-control" id="grpname" name="grpname" required>
                                            <option value="">--Choose Group Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct group_name from `sar_supplier` order by group_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                                            // echo '<option value="'.$sel_row["group_name"].'"'.($sel_row["group_name"]==$group_name)?'selected':"".'>'.$sel_row["group_name"].'</option>';
                                            ?>
                            	                <option value="<?=$sel_row["group_name"]?>" <?=($sel_row["group_name"]==$valg['groupname'])?'selected':""?>><?=$sel_row["group_name"]?></option>
                            	          <?php }
                            	           ?>
                            	          
                            	           </select>
                            </div>
                                           <div class="form-group col-md-6">
                                         <label for="exampleFormControlSelect1">Supplier Name</label><span style="color:red">*</span>
                                  <?php if($valg['supplier_name']=="") { ?>
                                         <input list="searchval" id="search_val" class="form-control" name="search_val">
                                <datalist class="searchval" id="searchval" name="searchval" required>
                                    <option value="">--Choose Supplier Name--</option>
                                    <?php } else { 
                                        ?>
                                        <select name="search_val" class="form-control">
                                            <?php
                                        $sel_qry = "SELECT contact_person from `sar_supplier` where group_name='$valg[groupname]'";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                                            // echo '<option value="'.$sel_row["group_name"].'"'.($sel_row["group_name"]==$group_name)?'selected':"".'>'.$sel_row["group_name"].'</option>';
                                            ?>
                            	                <option value="<?=$sel_row['contact_person']?>" <?=($sel_row['contact_person']==$valg['supplier_name'])?'selected':""?>><?=$sel_row["contact_person"]?></option>
                            	          <?php }
                            	           ?>
                            	           </select>
                                           <?php } ?>
                                 <?php
                                //     $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                // 	$sel_sql= $connect->prepare($sel_qry);
                    	        //     $sel_sql->execute();
                    	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                    	                
                    	        //         echo '<option>'.$sel_row["contact_person"].'</option>';
                    	        //    }
                    	           ?>
                                </datalist>
                                <!-- <select class="form-control searchval" id="searchval" name="searchval" required>
                                            <option value="">--Choose Supplier Name--</option> -->
                                    <?php
                                        //     $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                        // 	$sel_sql= $connect->prepare($sel_qry);
                            	        //     $sel_sql->execute();
                            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	        //         echo '<option>'.$sel_row["contact_person"].'</option>';
                            	        //    }
                            	           ?>
                            	          
                            	           <!-- </select> -->
                         <!--<a class="search-link" href="#"><i class="ri-search-line sales"></i></a>-->
                         <!--&nbsp;-->
                         <!--<button type="button" id="clear" name="clear" class="btn btn-danger ">Clear-->
                         <!--</button>-->
                         <button style="position:relative;left:170px !important;top:5px !important" type="button" id="add_supplier" name="add_supplier" class="btn btn-sm btn-success mymodal">Add Supplier</button>
                         
                         <div id="delete_rec_id_list">
                         
                         </div>
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputdate">Patti ID </label>

                            <input type="text" class="form-control" id="patti_id" name="patti_id" value="<?= $patti_id ?>" readonly>
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                            <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="patti_date" required>
                         </div>
                          <div class="form-group col-md-6">
                            <label for="exampleInputNumber1">Mobile number</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="contact_number1" maxlength="10" pattern="^[6-9]\d{9}$" name="mobile_number" value="<?= $valg['mobile_number'] ?>" required>
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputText1">Supplier Name </label><span style="color:red">*</span>
                            <input type="text" class="form-control" name="supplier_name" id="contact_person" value="<?= $valg['supplier_name'] ?>" required>
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputText1">Address </label>
                            <input type="text" class="form-control" id="Address" name="supplier_address" value="<?= $valg['supplier_address'] ?>">
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputNumber1">Boxes Arrived</label><span style="color:red">*</span>
                            <input type="text" class="form-control" id="exampleInputNumber1" readonly name="boxes_arrived" value="<?=$valg['boxes_arrived']?>" required>
                         </div>
                         <div class="form-group col-md-6">
                            <label for="exampleInputText1">Lorry No</label>
                            <input type="text" class="form-control" id="exampleInputText1" name="lorry_no" style="text-transform:uppercase;" value="<?=$valg['lorry_no']?>">
                         </div>
                         <!-- <div class="form-group col-md-6">
                         <label for="exampleInputText1">Tray Type</label>
                          <input list="types" name="type" id="type" class="form-control" required>

<datalist id="types">
<option value="" disabled>--Choose Tray Type--</option>
                                            <option value="Small Tray">Small Tray</option>
                                            <option value="Big Tray">Big Tray</option>
                                          
                                        
</datalist> </div> -->
<div class="form-group col-md-6">
                             <label for="exampleFormControlSelect1">Farmer Name</label><span style="color:red">*</span>
                             <!-- <select class="form-control farmer_name" id="farmer_name" name="farmer_name" required>
                                <option value="">--Choose Farmer Name--</option> -->
                            <?php
                            //     $sel_qry = "SELECT distinct farmer_name from `sar_farmer` order by farmer_name ASC ";
                            // 	$sel_sql= $connect->prepare($sel_qry);
                	        //     $sel_sql->execute();
                	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                	                
                	        //         echo '<option value="'.$sel_row["farmer_name"].'">'.$sel_row["farmer_name"].'</option>';
                	        //    /}
                	           ?>
                	           <!-- </select> -->
                             <?php if($valg['farmer_name']==""){ ?>
                               <input list="farmername" name="farmer_name" id="farmer_name" class="form-control" required>

<datalist id="farmername">
<option value="" disabled>--Choose Farmer Name--</option> <?php
    $sel_qry = "SELECT distinct farmer_name from `sar_farmer` order by farmer_name ASC ";
                            	$sel_sql= $connect->prepare($sel_qry);
                	            $sel_sql->execute();
                	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                	                ?>
                	            <option value="<?=$sel_row['farmer_name']?>" <?= ($sel_row["farmer_name"]==$valg['farmer_name'])?"selected":''?>><?=$sel_row["farmer_name"]?></option>
                                <?php       // echo '<option value="'.$sel_row["farmer_name"].'">'.$sel_row["farmer_name"].'</option>';
                	          }
                            }else {  ?>        
                               <select name="farmer_name" class="form-control"> 
                           <?php    
                           $sel_qry = "SELECT distinct farmer_name from `sar_farmer` order by farmer_name ASC ";
                           $sel_sql= $connect->prepare($sel_qry);
                	            $sel_sql->execute();
                	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                	                ?>
                	            <option value="<?=$sel_row['farmer_name']?>" <?= ($sel_row["farmer_name"]==$valg['farmer_name'])?"selected":''?>><?=$sel_row["farmer_name"]?></option>
                                <?php       // echo '<option value="'.$sel_row["farmer_name"].'">'.$sel_row["farmer_name"].'</option>';
                	          }  ?>
                               </select>
                           <?php }?>
</datalist> 
                                      </div>
                        
                       </div>
                        </div>
                  </div>
              </div>
              <div class=" col-sm-6">
                  <div class="iq-card">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Commission, Cooli & Lorry</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <div class="row col-md-12">
                         <div class="form-group col-md-6">
                           <label for="exampleInputNumber1">Commission
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control commision" id="commision" name="commision" value="<?=$valg['commision']?>" min="0" required>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="exampleInputNumber1">Lorry Hire
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control lorry_hire" id="lorry_hire" name="lorry_hire" value="<?=$valg['lorry_hire']?>" min="0" required>
                        </div></div>
                        <div class="row col-md-12">
                         <div class="form-group col-md-6">
                           <label for="exampleInputNumber1">Box Charges
            
            
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control box_charge" id="box_charge" name="box_charge" value="<?=$valg['box_charge']?>" min="0" required>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="exampleInputNumber1">Cooli (Hamali)
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control cooli" id="cooli" name="cooli" value="<?=$valg['cooli']?>" min="0" required>
                        </div>
                            </div>
                            <div class="row col-md-12">
                         <div class="form-group col-md-12">
                          <label for="exampleInputNumber1"style="font-weight:700;">Total
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control total_deduction" id="total_deduction" name="total_deduction" value="<?=$valg['total_deduction']?>" onInput="edValueKeyPress()" min="0" readonly required>
                        </div>
                            </div><br>
                        <span id="lblValue">The text box contains: </span>
                     </div>
                  </div>
              </div>
              <div class="container-fluid">
                  <div class="col-lg-12" style="background: var(--iq-card-bg);border-radius: 8px;box-shadow: 0px 2px 8px var(--iq-card-shadow-1), 0px 8px 16px var(--iq-card-shadow-2);">
                      <div class="row form-group" id="dynamic_field">
                          <?php echo $quality_box; ?>
                      </div>
                      <div class="iq-card-body">
                      <div class="row col-md-12">   
                          <div class="form-group col-md-4">
                           <label for="exampleInputNumber1" style="font-weight:700">Total Box Amount
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control commision" id="totalBoxAmount" name="totalBoxAmount" value="<?=$valg['tb']?>" readonly required>
                         </div>
                         <div class="form-group col-md-4">
                           <label for="exampleInputNumber1"style="font-weight:700;">Total Bill Amount
                           </label><span style="color:red">*</span>
                           <input type="text" class="form-control grandTotal" id="grandTotal" name="grandTotal" onInput="edValueKeyPress()" value="<?=$valg['net_bill_amount']?>" readonly required>
                        </div>
                        <div class="form-group col-md-4">    
                      <a href="#dynamic_field"><button type="button" name="add" id="add" class="btn btn-success">Add Quality Box</button>
                    
                      <!-- style="position:relative;top:50px !important" -->
                    </a>
                            </div>
                            </div>
                        <button type="submit" name="<?= $submit_action ?>" value="submit" class="btn btn-primary">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
</form>


<?php
require "footer.php";
if (isset($_POST["add_patti"])) {
 //echo $name;exit;

   $count = count($_POST["quality_name"]);
   $patti_date = $_POST["patti_date"];
   $mobile_number = $_POST["mobile_number"];
   $supplier_name = $_POST["supplier_name"];
   $farmer_name = $_POST["farmer_name"];
   $supplier_address = $_POST["supplier_address"];
   $boxes_arrived = $_POST["boxes_arrived"];
   $lorry_no = $_POST["lorry_no"];
   $commision = $_POST["commision"];
   $lorry_hire = $_POST["lorry_hire"];
   $box_charge = $_POST["box_charge"];
   $cooli = $_POST["cooli"];
//    $type=$_POST['type'];
   $total_deduction = $_POST["total_deduction"];
   $supplier_id = $_POST["supplier_id"];
   $grpname = $_POST["grpname"];
   
   
   $total_bill_amount = 0;
   $net_bill_amount = 0;
   $tot=0;

   $patti_qry = "SELECT * FROM sar_patti ORDER BY id DESC LIMIT 1";
   $patti_sql = $connect->prepare("$patti_qry");
   $patti_sql->execute(); 
   $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
  
   $Last_id = $patti_row["id"] + 1;
   $pat_id = "P_" . date("Ym") . "0" . $Last_id;
    
   
   for($i=0;$i<$count;$i++)
  { 
      $quantity = $_POST["quantity"][$i];
    //   $type = $_POST["type"][$i];
      $rate = $_POST["rate"][$i];
      $bill_amount=$quantity*$rate;
      $total_bill_amount += $bill_amount;
    

   $net_bill_amount = $total_bill_amount - $total_deduction;
   $net_payable = $net_bill_amount + $lorry_hire + $box_charge + $cooli;

   $sample=$total_bill_amount;
   $tot+=$sample;
    $patti_qry = "SELECT * FROM sar_patti ORDER BY id DESC LIMIT 1";
    $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute(); 
    $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
   
    $Last_id = $patti_row["id"] + 1;
    $patti_id = "PAT_" . date("Ym") . "0" . $Last_id;
    $name=$patti_row['supplier_name']; 
    
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
    //   $type = $_POST["type"][$i];
      $rate = $_POST["rate"][$i];
      $bill_amount=$quantity*$rate;
 

            
//       if($type=="S"){
//         $type="Small Tray";
//       }
//       else if($type=="B")
// {
//     $type="Big Tray";
// }     

     if($id == ""){
 
      //   print_r($instray);die();
                                // $extray=mysqli_query($con,$instray);
  
  $add_patti_query = "INSERT INTO `sar_patti` SET
            groupname='$grpname',
            patti_id='$patti_id',
            pat_id='$pat_id',
            patti_date='$patti_date',
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            farmer_name='$farmer_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            updated_by='$username',
            bill_amount='$bill_amount',
            total_bill_amount='$total_bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            net_payable='$net_payable',
            supplier_id='$supplier_id',
            payment_status=1,
            is_active=1,
        remain_box=-1,
        type='$type'";
         
         $exe_sql= $connect->prepare($add_patti_query);
         $exe_sql->execute();
        // print_r($add_patti_query);die();
   $grandTotal = $_POST["grandTotal"];
 // header('Location: view_patti.php');

 $sqltray="select * from trays where name='$supplier_id' and type='$type' order by id desc limit 1";
 $exetray=mysqli_query($con,$sqltray);
 $restray=mysqli_fetch_assoc($exetray);
 
 $sqllast="select * from trays order by id desc limit 1";
 $exelast=mysqli_query($con,$sqllast);
 $reslast=mysqli_fetch_assoc($exelast);
 $box=-$quantity;
 
 $abtray=($reslast['ab_tray']!=0)?$reslast['ab_tray']+$quantity:$box;
 $tray=($restray['inhand']!=0)?$restray['inhand']-$quantity:$box;
 
 if($type=="Small Tray")
 {
 $small=$restray['smalltray']-$quantity;
 $absmall=$reslast['absmall']+$quantity;
 $big=isset($reslast['bigtray'])?$reslast['bigtray']:0;
 $abbig=isset($reslast['abbig'])?$reslast['abbig']:0;
 }
 else if($type=="Big Tray")
 {
 $big=$restray['bigtray']-$quantity;
 $abbig=$reslast['abbig']+$quantity;
 $small=isset($reslast['smalltray'])?$reslast['smalltray']:0;
 $absmall=isset($reslast['absmall'])?$reslast['absmall']:0;
 }
 // print_r($sqltray);die();


 //    print_r($abtray);die();
 // $boxes=-$boxes_arrived;
 $instray="insert into trays(date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,ids,smalltray,bigtray,absmall,abbig) values('$patti_date','$supplier_id','$quantity','$type','Inward from Patti $supplier_name','$quantity',0,'$tray','Admin','Supplier','$abtray','$patti_id',$small,$big,$absmall,$abbig)";
//  print_r($instray."d");die();
   $trayexe=mysqli_query($con,$instray);
 
}

  }

  
// print_r($tot);die();

   
//  $balance_qry1="SELECT balance FROM financial_transactions ORDER BY id DESC LIMIT 1 ";
//  $balance_sql1=$connect->prepare("$balance_qry1");
//  $balance_sql1->execute();
//  $bal_row1=$balance_sql1->fetch(PDO::FETCH_ASSOC);   
//  if($bal_row1["balance"]!=""){ 
//  $balance1 = $bal_row1["balance"] - $total_bill_amount;
//  }
//  else{
//   $balance1 = $total_bill_amount;
// }
// //print_r($balance1);die();
//  $fin_trans_qry = "INSERT INTO financial_transactions SET 
//                    date = '$patti_date',
//                    debit = $total_bill_amount,
//                    balance = $balance1,
//                    patti_id='$patti_id',
//                    description = 'Payment for Patti, ID: $supplier_name',
//                    ids='$supplier_id'";
//       //             print_r($fin_trans_qry);die();
//  $res1=mysqli_query($con,$fin_trans_qry);


$sqlbal="select * from payment where supplierid='$supplier_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

$tray="SELECT * FROM trays where name='$supplier_id' ORDER BY id DESC LIMIT 1 ";
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
if($valbal['total']==0 || $valbal['total']==""){
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   

    if($valbal['total']==""){
        $total=$tot;
      }
     else{
        $total=$opne+$tot-($traypay);  
     }
    
    $total=round($tot);
      $am=$valbal['total'];
  
    
      $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$patti_date','$supplier_name',$total_bill_amount,$tot,0,0,0,$total,'$supplier_id','$pat_id',$small,$big,$inhand)";
    //   print_r($insbal."oks");die(); 
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

$ob="select * from sar_ob_supplier where supplier_name='$supplier_id' order by id desc limit 1";
$op = $connect->prepare("$ob");
$op->execute(); 
$opb = $op->fetch(PDO::FETCH_ASSOC);
$opne=$opb['amount'];
$ob_supplier_id=$opb['ob_supplier_id'];
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
  
  $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$patti_date','$supplier_name',$opne,$tot,0,0,0,$total,'$supplier_id','$pat_id',$small,$big,$inhand)";
//   print_r($insbal."ok1");die(); 
  $exe=mysqli_query($con,$insbal);
}
else{
    $paybal = $valbal["id"] + 1;
    $pay_id = "PAY" . date("Ym") . $paybal;   
  
    if($valbal['total']!=0){
        $ob="select * from payment where supplierid='$supplier_id' order by id desc limit 1";
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
        $ob="select * from sar_ob_supplier where supplier_name='$supplier_id' order by id desc limit 1";
        //   print_r($ob);die();
          $op = $connect->prepare("$ob");
        $op->execute(); 
        $opb = $op->fetch(PDO::FETCH_ASSOC);
        $opne=$opb['amount'];
        // print_r($opne);die();
        $ob_supplier_id=$opb['ob_supplier_id'];
        if($opne==0){
            $opne=0;
        }
        else{
            $opne=$opne;
        } 
    }
// print_r($opne);die();

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

if($valbal['total']==""){
    $total1=$opne+$tot-($traypay);
    $total = $valbal["total"]+$total1;
 }
 else{
    $total=$opne+$tot-($traypay);  
 }


    $total=round($total);
    
 $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$patti_date','$supplier_name',$opne,$tot,0,0,0,$total,'$supplier_id','$pat_id',$small,$big,$inhand)";
//   print_r($insbal."ko");die(); 
  $exe=mysqli_query($con,$insbal);

}
}

header('Location: view_patti.php');

} else if(isset($_POST["edit_patti"])){
    
    $patti_qry = "SELECT * FROM sar_patti ORDER BY id DESC LIMIT 1";
   $patti_sql = $connect->prepare("$patti_qry");
    $patti_sql->execute(); 
    $patti_row = $patti_sql->fetch(PDO::FETCH_ASSOC);
   
    $Last_id = $patti_row["id"] + 1;
    $pattiid = "PAT_" . date("Ym") . "0" . $Last_id;
    
   $count = count($_POST["quality_name"]);
   $patti_date = $_POST["patti_date"];
   $mobile_number = $_POST["mobile_number"];
   $grpname = $_POST["grpname"];
   $supplier_name = $_POST["supplier_name"];
   $farmer_name = $_POST["farmer_name"];
      $supplier_address = $_POST["supplier_address"];
   $boxes_arrived = $_POST["boxes_arrived"];
   $lorry_no = $_POST["lorry_no"];
   $commision = $_POST["commision"];
   $lorry_hire = $_POST["lorry_hire"];
   $box_charge = $_POST["box_charge"];
   $cooli = $_POST["cooli"];
   $total_deduction = $_POST["total_deduction"];
   $supplier_id = $_POST["supplier_id"];
   
   $total_bill_amount = 0;
   $net_bill_amount = 0;
   $tot=0;
   
          
   for($i=0;$i<$count;$i++)
   { 
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
    //   $type = $_POST["type"][$i];
      $bill_amount=$quantity*$rate;
      $total_bill_amount += $bill_amount;
        $to=$total_bill_amount;
        $tot+=$to;
    }
   
   if(isset($_POST["delete_rec_id"])){
    $del_count = count($_POST["delete_rec_id"]);
   for($i=0;$i<$del_count;$i++)
   {
$id=$_POST["delete_rec_id"][$i];
    $sqlfetch="select * from sar_patti WHERE id='$id'";
    $sqlexe=mysqli_query($con,$sqlfetch);
    $idfetch=mysqli_fetch_assoc($sqlexe);
    $pid=$idfetch['patti_id'];

    $sqltray="select * from trays where ids='$pid' and type='$type' order by id desc limit 1";
    $exetray=mysqli_query($con,$sqltray);
    $restray=mysqli_fetch_assoc($exetray);
    
    $sqllast="select * from trays order by id desc limit 1";
    $exelast=mysqli_query($con,$sqllast);
    $reslast=mysqli_fetch_assoc($exelast);
    $box=-$quantity;
    
    $abtray=($reslast['ab_tray']!=0)?$reslast['ab_tray']-$quantity:$box;
    $tray=($restray['inhand']!=0)?$restray['inhand']+$quantity:$box;
    
    if($type=="Small Tray")
    {
    $small=$restray['smalltray']+$quantity;
    $absmall=$reslast['absmall']-$quantity;
    $big=$reslast['bigtray'];
    $abbig=$reslast['abbig'];
    }
    else if($type=="Big Tray")
    {
    $big=$restray['bigtray']+$quantity;
    $abbig=$reslast['abbig']-$quantity;
    $small=$reslast['smalltray'];
    $absmall=$reslast['absmall'];
    }
    // print_r($sqltray);die();
   
   
    //    print_r($abtray);die();
    // $boxes=-$boxes_arrived;
    $instray="insert into trays(date,name,no_of_trays,type,description,inward,outward,inhand,updated_by,category,ab_tray,ids,smalltray,bigtray,absmall,abbig) values('$date','$supplier_id','$quantity','$idfetch[type]','Inward from Patti $supplier_name','$box',0,'$tray','Admin','Supplier','$abtray','$patti_id',$small,$big,$absmall,$abbig)";
   //  print_r($instray."d");die();
      $trayexe=mysqli_query($con,$instray);

// if($patti_id!=""){
      $sqlbal="select * from payment where pattid='$patti_id' and supplierid='$supplier_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);
// }
// else{
     $sqlbal="select * from payment where supplierid='$supplier_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);
// }


$tray="SELECT * FROM trays where name='$supplier_id' ORDER BY id DESC LIMIT 1 ";
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
    $total=round($total);
    
      $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$patti_date','$supplier_name',$am,$tot,0,0,0,$total,'$supplier_id','$patti_id',$small,$big,$inhand)";
    //   print_r($insbal."ok1".$tot."-".$total."-".$idfetch['total_bill_amount']);die(); 
      $exe=mysqli_query($con,$insbal);

      $del_patti_query = "DELETE FROM `sar_patti` WHERE id=".$_POST["delete_rec_id"][$i];
       $del_patti_sql = mysqli_query($con, $del_patti_query);
        }     
   
 }
   $net_bill_amount = $total_bill_amount - $total_deduction;
   $net_payable = $net_bill_amount + $lorry_hire + $box_charge + $cooli;
  
  for($i=0;$i<$count;$i++)
  {
      $quality_name = $_POST["quality_name"][$i];
      $quantity = $_POST["quantity"][$i];
      $rate = $_POST["rate"][$i];
    //   $type = $_POST["type"][$i];
      $rec_id = $_POST["rec_id"][$i];
      $bill_amount=$quantity*$rate;


if($rec_id == ""){
        
        $add_patti_query = "INSERT INTO `sar_patti` SET
            groupname='$grpname',           
            patti_id='$pattiid',
            pat_id='$patti_id',
            patti_date='$patti_date',
            updated_by='admin',
            remain_box=-1,
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            farmer_name='$farmer_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            bill_amount='$bill_amount',
            total_bill_amount='$total_bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            net_payable='$net_payable',
            supplier_id='$supplier_id',
            payment_status=1,
            is_active=1,
            type='$type'
            ";
            
        $add_patti_sql = mysqli_query($con, $add_patti_query);
        
        // if($patti_id!=""){
$sqlbal="select * from payment where pattid='$patti_id' and supplierid='$supplier_id' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);
// }
// else{
//     $sqlbal="select * from payment where supplierid='$supplier_id' order by id desc limit 1";
// $exebal=mysqli_query($con,$sqlbal);
// $valbal=mysqli_fetch_assoc($exebal);
// $no=mysqli_num_rows($exebal);
// }

$tray="SELECT * FROM trays where name='$supplier_id' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];

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
    
    $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,total,supplierid,pattid,smalltray,bigtray,inhand) values('$grpname','$pay_id','$patti_date','$supplier_name',$am,$sale,0,0,$total,'$supplier_id','$patti_id',$small,$big,$inhand)";
    //   print_r($insbal."ok");die()    ; 
      $exe=mysqli_query($con,$insbal);
     
    }else if($rec_id != ""){
        // patti_id='$patti_id',
        // patti_date='$patti_date',
       
       $add_patti_query = "UPDATE `sar_patti` SET
            mobile_number='$mobile_number',
            supplier_name='$supplier_name',
            farmer_name='$farmer_name',
            supplier_address='$supplier_address',
            boxes_arrived='$boxes_arrived',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            rate='$rate',
            bill_amount='$bill_amount',
            total_bill_amount='$total_bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            box_charge='$box_charge',
            cooli='$cooli',
            total_deduction='$total_deduction',
            net_bill_amount='$net_bill_amount',
            net_payable='$net_payable',
            payment_status=1,
            supplier_id='$supplier_id',
            is_active=1 WHERE id ='".$rec_id."'
            ";
            
        $add_patti_sql = mysqli_query($con, $add_patti_query);
    }
    
   }
  $balance_qry="SELECT inhand FROM trays ORDER BY id DESC LIMIT 1 ";
  $balance_sql=$connect->prepare("$balance_qry");
  $balance_sql->execute();
  header('Location: view_patti.php');
}
?>
<script>
    //  $(document).ready(function(){
    //         var grp=$(this).val();
    //   alert(grp)
    //  });
        $("#grpname").on("change",function(){
        var grp=$(this).val();
        
        // alert(grp);
             $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchgrp","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#searchval").empty();
                    for(var i=0;i<len;i++){
                   $("#searchval").append('<option value="'+result[i].contact_person+'">'+result[i].contact_person+'</option>');
                }
                                                    // alert(result.contact_person);
	   }
    })

});
 //   $("#searchval").chosen();
</script>
<script>
function total_qty(){
var sum_qty = 0;
   $('.sum_qty').each(function() {
      sum_qty += Number($(this).val());
    }); 
 $('#exampleInputNumber1').val(sum_qty);
}
function calculate_grantotal() {
    var totalBoxAmount = ( $("#totalBoxAmount").val() ) ? parseFloat($("#totalBoxAmount").val()) : 0;
    var total_deduction = ( $("#total_deduction").val() ) ? parseFloat($("#total_deduction").val()) : 0;
    $("#grandTotal").val(totalBoxAmount - total_deduction);
}
function calculate_bill_amount(id){
    var qty = $("#qty" + id).val();
    console.log(qty);
    var rate_arr = $("#rate_arr" + id).val();
    console.log(rate_arr);
    var total = qty * rate_arr;
    console.log(total);
    $("#total_amount" + id).val(total.toFixed(2));
    // alert(total)
    var quaBoxTotal = 0;
    $(".qualityBox").each(function(index){
        var value = $(this).find(".boxTotal").val();
        if (value) {
           quaBoxTotal += parseFloat(value);
        }
    });
    // console.log(total);
   
    $("#totalBoxAmount").val(quaBoxTotal);
    calculate_grantotal(quaBoxTotal);
}
$(document).ready(function() {
    // $("#rate").on("change", function() {
    //   var rate = $(this).val();
    //   var bill_amount = quantity * rate;
    //   //var total_bill_amount +=bill_amount;
    //   $("#bill_amount").val(bill_amount.toFixed(2));
    // });

    $("#cooli,#commision,#lorry_hire,#box_charge").on("change", function() {
       var commision = ( $("#commision").val() ) ? parseFloat($("#commision").val()) : 0;
       var lorry_hire = ( $("#lorry_hire").val() ) ? parseFloat($("#lorry_hire").val()) : 0;
       var box_charge = ( $("#box_charge").val() ) ? parseFloat($("#box_charge").val()) : 0;
       var cooli = ( $("#cooli").val() ) ? parseFloat($("#cooli").val()) : 0;
       var total_deduction = commision + lorry_hire + box_charge + cooli;
       //document.getElementById("total_deduction").value=total_deduction;
       $("#total_deduction").val(total_deduction.toFixed(2));
       calculate_grantotal();
    });

    $(document).on("keypress", "#total_deduction", function (e) {
       var total_deduction = document.getElementById("total_deduction");
       var s = total_deduction.value;

       var lblValue = document.getElementById("lblValue");
       lblValue.innerText = "The text box contains: " + s;
    });
            
    var i = <?=$cnt?>;
    $('#add').click(function() {
    //   $('#dynamic_field').append('<div class="col-md-12 row qualityBox" id="inputFormRow" style="margin:10px;"><div class="form-group col-md-2"><input list="qualities" class="form-control" name="quality_name[]" id="quality_name"> <datalist id="qualities" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></datalist></div><div class="form-group col-md-2"><input type="text" class="form-control qty sum_qty" placeholder="Enter Quantity" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr" placeholder="Rate" id="rate_arr' + i + '" myattr="' + i + '" name="rate[]"></div><div class="form-group col-md-2"><input type="text" name="bill_amount[]" class="form-control boxTotal" placeholder="Total" id="total_amount' + i + '" readonly></div><br><div class="form-group col-md-2"><button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div></div>');
      $('#dynamic_field').append('<div class="col-md-12 row qualityBox" id="inputFormRow" style="margin:10px;"><div class="form-group col-md-2"><input list="qualities" class="form-control" name="quality_name[]" id="quality_name"> <datalist id="qualities" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></datalist></div><div class="form-group col-md-2"><input type="text" class="form-control qty sum_qty" placeholder="Enter Quantity" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr" placeholder="Rate" id="rate_arr' + i + '" myattr="' + i + '" name="rate[]"></div><div class="form-group col-md-2"><input type="text" name="bill_amount[]" class="form-control boxTotal" placeholder="Total" id="total_amount' + i + '" readonly></div><br><div class="form-group col-md-2"><button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div></div>');
    // <div class="form-group col-md-2"><select name="type[]" id="type" class="form-control" required><option>Choose Tray Type</option><option value="Small Tray">Small Tray</option><option value="Big Tray">Big Tray</option></select></div>
    //  $('#dynamic_field').append('<div class="col-md-12 row qualityBox" id="inputFormRow" style="margin:10px;"><div class="form-group col-md-2"><input list="qualities" class="form-control" name="quality_name[]" id="quality_name"> <datalist id="qualities" name="quality_name[]"><option value="">--Choose Quality--</option><?php foreach ($quality_name_list as $value){echo '<option>'.$value.'</option>';} ?></datalist></div><div class="form-group col-md-2"><input type="text" class="form-control qty sum_qty" placeholder="Enter Quantity" onkeyup="total_qty();" name="quantity[]" myattr="' + i + '" id="qty' + i + '"></div> <div class="form-group col-md-2"><input type="number" class="form-control rate_arr" placeholder="Rate" id="rate_arr' + i + '" myattr="' + i + '" name="rate[]"></div><div class="form-group col-md-2"><select name="type[]" id="type" class="form-control" required><option>Choose Tray Type"</option><option value="Small Tray">Small tray</option><option value="Big Tray">Big Tray</option></select></div><div class="form-group col-md-2"><input type="text" name="bill_amount[]" class="form-control boxTotal" placeholder="Total" id="total_amount' + i + '" readonly></div><br><div class="form-group col-md-2"><button id="removeRow" type="button" class="btn btn-danger">Remove</button></div></div></div>');

    $(".rate_arr, .qty").on('change', function() {
        var id = $(this).attr("myattr");
        calculate_bill_amount(id);
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
      $("#totalBoxAmount").val(quaBoxTotal);
      calculate_grantotal();
   });

//    $("#searchval").on("change", function() {
    $("#search_val").on('input', function () {
        var contact_person = this.value;
        //  var contact_person=$(this).val();
    //   alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_patti_search",
            "contact_person":contact_person
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });



   });
//   $("#clear").on("click", function() {
//      $("#searchval").val('');
//      $("#supplier_id").val(result.data.supplier_id).attr('readonly', false);
//       $("#contact_number1").val('').attr('readonly', false);
//       $("#contact_person").val('').attr('readonly', false);
//       $("#Address").val('').attr('readonly', false);
//   });
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
$(document).ready(function() {

    $('.mymodal').on('click', function (){
        $( "#myModal" ).modal( "show" );
    });
    
     $('.close').on('click', function (){
        $( "#myModal" ).modal( "hide" );
    });
});
</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Supplier</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                            
                        <form id="form1" action="" method="POST">
                        
                          <div id="supplier_form">
                            <h4>Supplier Form</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            
                                             <label>Supplier Name</label><span style="color:red">*</span>
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
                                            <label>Supplier Address</label><span style="color:red">*</span>
                                            <input type="text" class="form-control" name="address_modal" id="address_modal" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="supplier_add" class="btn btn-primary" value="Submit">
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
if (isset($_POST["supplier_add"])) {
    $supplier_qry="SELECT id FROM sar_supplier ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $supplier_no = "SUP_".date("Ym")."0".$Last_id;
   $name_modal = $_POST["name_modal"];
   $mobile_modal = $_POST["mobile_modal"];
   $address_modal = $_POST["address_modal"];
   
   $supplier_query = "INSERT INTO `sar_supplier` SET
   
            supplier_no='$supplier_no',
            contact_person='$name_modal',
            contact_number1='$mobile_modal',
            Address='$address_modal',
            is_active=1
            ";
            
        $supplier_sql = mysqli_query($con, $supplier_query);
   
}
    ?>
<script>
// $("#searchval").chosen();
// $("#farmer_name").chosen();
</script>
