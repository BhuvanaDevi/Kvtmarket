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

if($req=="enabled")
{
    $delete="UPDATE `sar_customer` SET is_active=0 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_customer.php");
}

if($req=="disabled")
{
    $delete="UPDATE `sar_customer` SET is_active=1 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_customer.php");
}
 ?>
 
<div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                <div class="col-lg-6">
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add Customer</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="POST" action="">
                               <div class="form-group ">
                                   <input type="hidden" class="form-control" id="grp_cust_no" name="grp_cust_id">
                                     <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                     <select class="form-control grp_cust_name" id="grp_cust_name" name="grp_cust_name" required>
                                        <option value="">--Choose Group Name--</option>
                                    <?php
                                        $sel_qry = "SELECT distinct grp_cust_name from `sar_group_customer` order by grp_cust_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                        	           }
                        	           ?>
                        	          
                        	           </select>
                                </div>
                                <div class="form-group">
                                 <label for="exampleInputdate">Customer ID </label>
                                 <?php
                                  $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
                                $customer_sql=$connect->prepare("$customer_qry");
                                $customer_sql->execute();
                                $customer_row=$customer_sql->fetch(PDO::FETCH_ASSOC);
                                $Last_id=$customer_row["id"]+1;
                              $customer_no = "CUS_".date("Ym")."0".$Last_id;
                                  ?>
                                 <input type="text" class="form-control" id="customer_no" name="customer_no" value="<?=$customer_no?>" readonly>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Customer Name </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="customer_name" name="customer_name" value=""  required>
                                 <span style="color:red;font-weight:bold;" id="customer_name_disp"></span>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Contact Number </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="contact_number1" name="contact_number1" value="" maxlength="10" pattern="^[6-9]\d{9}$">
                                 <span style="color:red;font-weight:bold;" id="customer_mobile_disp"></span>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Address </label>
                                 <input type="text" class="form-control" id="address" name="address" value="" placeholder="Enter Address" required>
                              </div>
                              <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                         </form>
                    
                        </div>
                      </div>
                      </div>
                      <div class="col-lg-6">
                 <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add Group</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <form method="POST" action="">
                              <div class="form-group">
                                 <label for="exampleInputText1">Group Name</label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="group_name" name="group_name" required>
                              </div>
                              <button type="submit" name="group_submit" id="group_submit" class="btn btn-primary">Submit</button>
                           </form>
                           
                        </div>
                
                      </div>
                  </div>
                  <div class="col-lg-6">
                 <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Update Group</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <form method="POST" action="">
                              <div class="form-group ">
                                   <!--<input type="hidden" class="form-control" id="grp_cust_no" name="grp_cust_id">-->
                                     <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                     <select class="form-control grp_upt_name" id="grp_upt_name" name="grp_upt_name" required>
                                        <option value="">--Choose Group Name--</option>
                                    <?php
                                        $sel_qry = "SELECT distinct grp_cust_name from `sar_group_customer` order by grp_cust_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                        	           }
                        	           ?>
                        	          
                        	           </select>
                                </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="form-check">
                                <div class="form-group"> 
                                <?php
                                  $records = "SELECT customer_name,customer_no From sar_customer";  
                                  // Use select query here 
                                    $records_sql=$connect->prepare($records);
                                    $records_sql->execute();
                                    $i=0;
                                while($records_row = $records_sql->fetch(PDO::FETCH_ASSOC))
                                {
                                    
                                  ?>
                    
                            
                            <input type='text' readonly name='customer_name[]' value='<?=$records_row['customer_name']?>' class='customer_name'>
                                       
                                        <input type='hidden' id='customer_no' name='customer_no[]' value='<?=$records_row['customer_no']?>'>
                                        
                                        <input class='form-check-input' type='checkbox' id='invalidCheck2' name='<?=$records_row['customer_name']?>' value='checked'>
                                        
                                   
                                                   
                                    <?php
                                $i++;
                            }	
                            ?>
                                </div> 
                        </div>
                    </div>
                </div> 
                              <button type="submit" name="grp_upt_submit" id="grp_upt_submit" class="btn btn-primary">Submit</button>
                           </form>
                           
                        </div>
                
                      </div>
                  </div>
<?php
if(isset($_POST["grp_upt_submit"])){
    $customer_name=$_POST["customer_name"];
    $customer_no=$_POST['customer_no'];
for($i=0;$i<count($_POST["customer_name"]);$i++)
    {
         $customer_name=$_POST["customer_name"][$i];
         $customer_no=$_POST['customer_no'][$i];
         
        if($_POST[$customer_name] == 'checked'){
            $attendance="Present";
        }
        // echo $_POST["attendance"][$i];
        // echo $_POST["employee_name"][$i];
        // for($j=0;$j<count($_POST["attendance"]);$j++)
        // {
        //      $attend=$_POST["attendance"][$j];
        //     if(trim($employee_name)==trim($attend))
        //     {
        //          $attendance="Present";
        //     }
        // }
       
       // echo trim($employee_name)."  ".$attendance."<br>";
        //echo $attend."--".$employee_name."<br/>";
        
        $employee_attendance_insert_query="INSERT INTO `thai_employee_attendance` SET
        attendance_id = '$employee_id',
        employee_name = '$employee_name', 
        attendance = '$attendance', 
        today_date = '$today_date', 
        today_time = '$today_time', 
        attendance_taken_date = '$attendance_taken_date'";
       //echo $employee_attendance_insert_query;
        $sql_1= $connect->prepare($employee_attendance_insert_query);
        $sql_1->execute();
    }
}
if(isset($_POST["group_submit"])){
    $group_qry="SELECT id FROM sar_group_customer ORDER BY id DESC LIMIT 1 ";
    $group_sql=$connect->prepare("$group_qry");
    $group_sql->execute();
    $group_row=$group_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $grp_cust_id = "GRPC_".date("Ym")."0".$Last_id;
   
  $grp_cust_name = $_POST["group_name"];
//   $contact_number1 = $_POST["contact_number1"];
//   $address = $_POST["address"];
//   $svar="SELECT * FROM sar_group WHERE contact_number1='".$contact_number1."' ";
//     $query = $connect->prepare($svar);
//     $user_array = $query ->execute();
//     $count=$query->rowCount();
    
//      $cust_var="SELECT * FROM sar_supplier WHERE contact_person='".$contact_person."' ";
//     $cust_query = $connect->prepare($cust_var);
//     $customer_array = $cust_query ->execute();
//     $customer_count=$cust_query->rowCount();
    
  
  if($id==""){
     
  
  $grp_qry = "INSERT INTO `sar_group_customer` SET 
                grp_cust_id='$grp_cust_id',
                grp_cust_name='$grp_cust_name'
                ";
                
                
        
        $grp_sql= $connect->prepare($grp_qry);
        $grp_sql->execute();
        
}
else if($id==""){
    
    
    $grp_qry = "UPDATE `sar_group_customer` SET 
                grp_cust_id='$grp_cust_id',
                grp_cust_name='$grp_cust_name'
                ";
}
else if($grp_qry->errno === 1062) {
    echo "Exist";
}
else {
        
            $grp_qry = "UPDATE `sar_group_customer` SET 
                        grp_cust_id='$grp_cust_id',
                        grp_cust_name='$grp_cust_name'
                        WHERE id=$id";
            $grp_sql= $connect->prepare($grp_qry);
            $grp_sql->execute();
                       
    }    
    
}
if(isset($_POST["submit"])){
    $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
    $customer_sql=$connect->prepare($customer_qry);
    $customer_sql->execute();
    $customer_row=$customer_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$customer_row["id"]+1;
    
    $customer_no = "CUS_".date("Ym")."0".$Last_id;
  $customer_name = ucwords($_POST["customer_name"]);
  $contact_number1 = $_POST["contact_number1"];
  $address = $_POST["address"];
  
   
    // $svar="SELECT * FROM sar_customer WHERE contact_number1='".$contact_number1."' ";
    // $query = $connect->prepare($svar);
    // $user_array = $query ->execute();
    // $count=$query->rowCount();
    
     $cust_var="SELECT * FROM sar_customer WHERE customer_name='".$customer_name."' ";
    $cust_query = $connect->prepare($cust_var);
    $customer_array = $cust_query ->execute();
    $customer_count=$cust_query->rowCount();
    
    if( $customer_count==0){
       
        $query_1 = "INSERT INTO `sar_customer` SET 
                customer_no='$customer_no',
                customer_name='$customer_name',
                contact_number1='$contact_number1',
                address='$address',
                is_active=1
                ";
                
         // echo  $query_1; 
        
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
      
    }
     else if($count !=0 && $customer_count!=0) {
       echo "<script type='text/javascript'>alert('Lead added successfully');location='add_customer.php';</script>";
    }
    
    else {
        
            $query_1 = "UPDATE `sar_customer` SET 
                        customer_no='$customer_no',
                        customer_name='$customer_name',
                        contact_number1='$contact_number1',
                        address='$address',
                        is_active=1
                         WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    } 
    header("location:add_customer.php");


}

         ?>

                <div class="col-lg-6">
                 <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">View Customer Details</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                    <center>
                        <tr>
                    <td align="left">
                        <b><label><input type="radio" name="view_customer_sts" class="view_customer_sts" value="1" checked />Active</label></b>
                    </td>
                    <td align="right">
                        <b><label><input type="radio" name="view_customer_sts" class="view_customer_sts" value="0" />InActive</label></b>
                    </td>
                    <button type="button" id="download" name="download" class="btn btn-danger">Download</button>
                </tr>
                </center>
                    &nbsp;&nbsp;
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Customer NO</th>
                        <th>Customer Name</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th></th>
                    </tr>
                </thead>
               
            </table>
        </div>
        </div>
        </div>
        </div>
    </div>
</div>
 
         

    <?php require "footer.php" ?> 
    
    <!--
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    -->
<script>
//$.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_customer&req=enabled",
                "type": "POST"
            },
            "columns": [
                { "data": "customer_no" },
                { "data": "customer_name" },
                { "data": "contact_number1" },
                { "data": "address" },
                { "data": "id" }
            ],
            columnDefs: [
               {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.customer_no;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.customer_name;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.contact_number1;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.address;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        if(row.is_active==1){
                            var htm = '<a class="label label-success" href="add_customer.php?req=edit&id='+row.id+'"><span class="bx bxs-edit" >&nbsp Edit</span></a>&nbsp';
                        
                           var htm1 = '<a href="add_customer.php?req=enabled&id='+row.id+'"><button type="button" class="btn btn-danger">DeActivate</button></a>';
                        } else if(row.is_active==0){
                            var htm ='';
                            var htm1 = '<a href="add_customer.php?req=disabled&id='+row.id+'" ><button type="button" class="btn btn-success">Active</button></a>';
                        }
                        return htm+htm1;
                    }
                }
             ]
        });
       
         $(".view_customer_sts").on("click",function(){
            var is_active=$(this).val();
            if(is_active==1){
                table.ajax.url("forms/ajax_request.php?action=view_customer&req=enabled&is_active=1").load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_customer&req=disabled&is_active=0").load();
                table.ajax.reload();
            }
        });
         $("#contact_number1").on("change",function(){
        var contact_number1=$(this).val();
        //alert(employee_mobile)
        $.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_contact_customer","contact_number1":contact_number1},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        if(result.msg=="alreadyexist") {
                            $("#customer_mobile_disp").html("Mobile No Already Exists");
                    } else {
                        $("#customer_mobile_disp").html("");
                    }
                }
            }
        });
    });
         $("#customer_name").on("change",function(){
        var customer_name=$(this).val();
        //alert(employee_mobile)
        $.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_name_customer","customer_name":customer_name},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        if(result.msg=="alreadyexist") {
                            $("#customer_name_disp").html("Customer Already Exists");
                    } else {
                        $("#customer_name_disp").html("");
                    }
                }
            }
        });
    });
            $("#download").on("click",function(){
            
            window.location="download_customer_report.php";
            });
    });
</script>
<script>
(function($) {
  var CheckboxDropdown = function(el) {
    var _this = this;
    this.isOpen = false;
    this.areAllChecked = false;
    this.$el = $(el);
    this.$label = this.$el.find('.dropdown-label');
    this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
    this.$inputs = this.$el.find('[type="checkbox"]');
    
    this.onCheckBox();
    
    this.$label.on('click', function(e) {
      e.preventDefault();
      _this.toggleOpen();
    });
    
    this.$checkAll.on('click', function(e) {
      e.preventDefault();
      _this.onCheckAll();
    });
    
    this.$inputs.on('change', function(e) {
      _this.onCheckBox();
    });
  };
  
  CheckboxDropdown.prototype.onCheckBox = function() {
    this.updateStatus();
  };
  
  CheckboxDropdown.prototype.updateStatus = function() {
    var checked = this.$el.find(':checked');
    
    this.areAllChecked = false;
    this.$checkAll.html('Check All');
    
    if(checked.length <= 0) {
      this.$label.html('Select Options');
    }
    else if(checked.length === 1) {
      this.$label.html(checked.parent('label').text());
    }
    else if(checked.length === this.$inputs.length) {
      this.$label.html('All Selected');
      this.areAllChecked = true;
      this.$checkAll.html('Uncheck All');
    }
    else {
      this.$label.html(checked.length + ' Selected');
    }
  };
  
  CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
    if(!this.areAllChecked || checkAll) {
      this.areAllChecked = true;
      this.$checkAll.html('Uncheck All');
      this.$inputs.prop('checked', true);
    }
    else {
      this.areAllChecked = false;
      this.$checkAll.html('Check All');
      this.$inputs.prop('checked', false);
    }
    
    this.updateStatus();
  };
  
  CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
    var _this = this;
    
    if(!this.isOpen || forceOpen) {
       this.isOpen = true;
       this.$el.addClass('on');
      $(document).on('click', function(e) {
        if(!$(e.target).closest('[data-control]').length) {
         _this.toggleOpen();
        }
      });
    }
    else {
      this.isOpen = false;
      this.$el.removeClass('on');
      $(document).off('click');
    }
  };
  
  var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
  for(var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
    new CheckboxDropdown(checkboxesDropdowns[i]);
  }
});
</script>
