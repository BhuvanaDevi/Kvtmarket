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

$supplier_no = $_REQUEST["supplier_no"];
$supplier_qry="SELECT * FROM `sar_supplier` WHERE id='".$id."'";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute();
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);
$group_name=$supplier_row["group_name"];
$supplier_no=$supplier_row["supplier_no"];
$supplier_name=$supplier_row["contact_person"];
$supplier_contact=$supplier_row["contact_number1"];
$Address=$supplier_row["Address"];

if($req=="delete")
{
    $delete="DELETE FROM sar_supplier WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:add_supplier.php");
}
?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <!--  <div class="row col-md-12">
        <div class="col-md-6">  
             <div class="row col-md-12">
     <div class="col-md-12">     
         <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add Group</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <form method="POST" action="">
                            <div class="row col-md-12">   
                              <div class="form-group col-md-6">
                                 <label for="exampleInputText1">Group Name</label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="group_name" name="group_name" required>
                              </div>
                              <div class="form-group col-md-4">
                            <br/>  <button style="position: relative;top:10px" type="submit" name="group_submit" id="group_submit" class="btn btn-primary">Submit</button>
                              </div>
                                 </div>
                              </form>
                           
                        </div>
                
                      </div> 
                      </div>
                      <div class="col-lg-12">
                 <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                           <div class="row col-md-12">  
                           <div class="col-md-8">  
                           <h4 class="card-title">View Group</h4>
                           </div>
                           <div class="col-md-4">  
                             <button style="position: relative;left:200px" type="button" id="download" name="download" class="btn btn-danger">Download</button>
                           </div>
                              </div>
                                 </div>
                        </div>
                        <div class="iq-card-body">
                    <center>
                        <tr>
                    <td align="left">-->
                    <!--    <b><label><input type="radio" name="view_supplier_sts" class="view_supplier_sts" value="1" checked />&nbsp;Active</label></b>-->
                    <!--</td>-->
                    <!--<td align="right">-->
                    <!--    <b><label><input type="radio" name="view_supplier_sts" class="view_supplier_sts" value="0" />&nbsp;Inactive</label></b>-->
                    <!--</td>
                </tr>
                </center>
                    &nbsp;&nbsp;
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>  
            </th>
                    </tr>
                </thead>
               
            </table>
        </div>
        </div> 
                  </div>
        </div>
        </div>
    -->        <div class="col-lg-12">
                 <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add Supplier</h4>
                           </div>
                        </div>
                        <form method="POST" action="">
                        <div class="iq-card-body">
                        <div class="row col-md-12 ">
                           <div class="form-group col-md-6">
                               <input type="hidden" class="form-control" id="grp_no" name="group_id">
                                 <label for="exampleFormControlSelect1">Group Name</label><span style="color:red">*</span>
                                 <select class="form-control grp_name" id="grp_name" name="grp_name" required>
                                    <option value="">--Choose Group Name--</option>
                                    <?php
                                        $sel_qry = "SELECT distinct group_name from `sar_group` order by group_name ASC ";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                ?>
                        	                <option value="<?= $sel_row["group_name"] ?>" <?=($group_name==$sel_row["group_name"])?'selected':""?>><?= $sel_row["group_name"] ?></option>
                        	          <?php }
                        	           ?>
                    	        </select>
                            </div>
                        
                            <div class="form-group col-md-6">
                                                      
                                                      <label for="exampleInputText1">Supplier ID </label><span style="color:red">*</span>
                                                          <?php
                                                             $supplier_qry="SELECT id FROM sar_supplier ORDER BY id DESC LIMIT 1 ";
                                                             $supplier_sql=$connect->prepare("$supplier_qry");
                                                             $supplier_sql->execute();
                                                             $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
                                                             $Last_id=$supplier_row1["id"]+1;
                                                             $supplier_id = "SUP_".date("Ym")."0".$Last_id;
                                                          if($supplier_no!=""){
                                                      echo '<input type="text" class="form-control" id="supplier_no" name="supplier_no" value="'.$supplier_no.'" readonly>';
                                                          }
                                                          else
                                                          {
                                                             echo '<input type="text" class="form-control" id="supplier_no" name="supplier_no" value="'.$supplier_id.'" readonly>'; 
                                                          }
                                                          ?>
                                                   </div>
                                                    </div>
                       <div class="col-md-12 row">
                                                    <div class="form-group col-md-6">
                             <label for="exampleInputText1">Supplier Name </label><span style="color:red">*</span>
                             <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?=$supplier_name?>" required>
                             <span style="color:red;font-weight:bold;" id="supplier_name_disp"></span>
                            </div>
                           <div class="form-group col-md-6">
                             <label for="exampleInputText1">Contact Number </label><span style="color:red">*</span>
                             <input type="text" class="form-control" id="contact_number1" name="contact_number1" maxlength="10" pattern="^[6-9]\d{9}$" placeholder="Enter Mobile Number" value="<?=$supplier_contact?>" >
                               <span style="color:red;font-weight:bold;" id="employee_mobile_disp"></span>
                            </div>
                       </div>
                       <div class="col-md-12 row">
                                                    <div class="form-group col-md-12">
                             <label for="exampleInputText1">Address </label>
                             <input type="text" class="form-control" id="address" name="address" value="<?=$Address?>" placeholder="Enter Address" >
                            </div>
                            </div>
                          <button style="position: relative;left:15px" type="submit" name="add_supplier" id="add_supplier" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                 </div>
            </div>
        </div>
    </div>
</div>

<?php
//print_r($_POST);
if(isset($_POST["group_submit"])){
    $group_qry="SELECT id FROM sar_group ORDER BY id DESC LIMIT 1 ";
    $group_sql=$connect->prepare("$group_qry");
    $group_sql->execute();
    $group_row=$group_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $group_no = "GRP_".date("Ym")."0".$Last_id;
   
  $group_name = $_POST["group_name"];
  $contact_number1 = $_POST["contact_number1"];
  $address = $_POST["address"];
 
      $grp_qry = "INSERT INTO `sar_group` SET 
                    group_no='$group_no',
                    group_name='$group_name'
                    ";
                    
            $grp_sql= $connect->prepare($grp_qry);
            $grp_sql->execute();
        header("location:add_customer.php");
}
//print_r($_POST);
if(isset($_POST["add_supplier"])){
    $supplier_qry="SELECT id FROM sar_supplier ORDER BY id DESC LIMIT 1 ";
    $supplier_sql=$connect->prepare("$supplier_qry");
    $supplier_sql->execute();
    $supplier_row1=$supplier_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$supplier_row1["id"]+1;
    $supplier_no = "SUP_".date("Ym")."0".$Last_id;
    
    $grp_no = $_POST["grp_no"];
    $grp_name = $_POST["grp_name"];
    $contact_number1 = $_POST["contact_number1"];
    $contact_person = ucwords($_POST["contact_person"]);
    $contact_number1 = $_POST["contact_number1"];
    $address = $_POST["address"];
    $svar="SELECT * FROM sar_supplier WHERE contact_number1='".$contact_number1."' ";
    $query = $connect->prepare($svar);
    $user_array = $query ->execute();
    $count=$query->rowCount();
    
     $cust_var="SELECT * FROM sar_supplier WHERE contact_person='".$contact_person."' ";
    $cust_query = $connect->prepare($cust_var);
    $customer_array = $cust_query ->execute();
    $customer_count=$cust_query->rowCount();
    
  
  if($id==""){
     
  
  $query_1 = "INSERT INTO `sar_supplier` SET 
                group_id='$group_no',
                group_name='$grp_name',
                supplier_no='$supplier_no',
                contact_person='$contact_person',
                contact_number1='$contact_number1',
                address='$address',
                created_by='$date'
                ";
                
                
        
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
        
}
else if($id==""){
    
    
    $query_1 = "UPDATE `sar_supplier` SET 
                contact_person='$supplier_name',
                contact_number1='$mobile_number',
                address='$supplier_address'
                ";
}
else if($query_1->errno === 1062) {
    echo "Exist";
}
else {
        
            $query_1 = "UPDATE `sar_supplier` SET 
                        contact_person='$contact_person',
                        contact_number1='$contact_number1',
                        address='$address'
                        WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    }    
    header("location:add_customer.php");
}


?>

    <?php require "footer.php" ?> 
   
<script>
// $.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_supplier&req=enabled",
                "type": "POST"
            },
            "columns": [
                { "data": "group_name" },
                { "data": "id" }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.group_name;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return '<a href="#" class="mymodal" group_name="' + row.group_name + '" ><i class="bx bx-comment-dots"></i>&nbsp;View</a>';
                    }
                }
             ]
        });
            $('#example tbody').on('click', '.mymodal', function (){
            var group_name=$(this).attr("group_name");
            $( "#myModal" ).modal( "show" );
            $("#group_name").val(group_name);
            //$("#supplier_id").val(patti_id);
            update_model_data(group_name, 'unsettled')
         });
        //  $(".view_supplier_sts").on("click",function(){
        //     var is_active=$(this).val();
        //     if(is_active==1){
        //         table.ajax.url("forms/ajax_request.php?action=view_supplier&req=enabled&is_active=1").load();
        //         table.ajax.reload();
        //     } else {
        //         table.ajax.url("forms/ajax_request.php?action=view_supplier&req=disabled&is_active=0").load();
        //         table.ajax.reload();
        //     }
        // });
        function update_model_data(group_name, data_src){
        if(data_src == 'settled'){
            $('#payment_form').hide();
        }else{
            $('#payment_form').show();
        }
        $.ajax({
            type:"POST",
            url:"forms/ajax_request_view.php",
            data:{"action":"view_supplier_modal","group_name":group_name, "data_src": data_src},
            dataType:"json",
            success:function(result){
                $("#produ_details").html("");
                var i=0;
                $('#sar_patti_payment_table').html("");
                $('#revoke_table').html("");
                var sum_totalamount = 0;
                for(i=0;i<result.length;i++)
                {
                    if(result[i].hasOwnProperty("group_name")){
                        sum_totalamount += parseFloat(result[i].bill_amount);
                        $('#produ_details').append('<tr>');
                       
                        // $("#produ_details").append('<td>'+result[i].group_no+'</td>');
                        $("#produ_details").append('<td>'+result[i].group_name+'</td>');
                        $("#produ_details").append('<td>'+result[i].supplier_no+'</td>');
                        $("#produ_details").append('<td>'+result[i].contact_person+'</td>')
                        $("#produ_details").append('<td>'+result[i].contact_number1+'</td>');
                        $("#produ_details").append('<td>'+result[i].Address+'</td>');
                        $("#produ_details").append('<td><a class="label label-success" href="add_supplier.php?req=edit&id='+result[i].id+'"><span class="bx bxs-edit" ></span></a></td>');
                         $("#produ_details").append('<td><a class="label label-delete" href="add_supplier.php?req=delete&id='+result[i].id+'"><span class="bx bxs-trash" ></span></a></td>');
                       $('#produ_details').append('</tr>');
                    }
                    
                }
                
            }
        });
    }
        $("#contact_number1").on("change",function(){
        var contact_number1=$(this).val();
        //alert(employee_mobile)
        $.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_contact_supplier","contact_number1":contact_number1},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        if(result.msg=="alreadyexist") {
                            $("#employee_mobile_disp").html("Mobile Number Already Exists");
                    } else {
                        $("#employee_mobile_disp").html("");
                    }
                }
            }
        });
    });
        $("#contact_person").on("change",function(){
        var contact_person=$(this).val();
        //alert(employee_mobile)
        $.ajax({
                type:"POST",
                url:"forms/ajax_request_view.php",
                data:{"action":"view_name_supplier","contact_person":contact_person},
                dataType:"json",
                success:function(result){
                    if(result.status==1){
                        if(result.msg=="alreadyexist") {
                            $("#supplier_name_disp").html("Supplier Already Exists");
                    } else {
                        $("#supplier_name_disp").html("");
                    }
                }
            }
        });
    });
        $("#download").on("click",function(){
            
            window.location="download_supplier_report.php";
            });
    
    });
</script>
<script>
$("#grp_name").chosen();
</script>
<script>
    $(document).ready(function(){
        
        $(".chk").click(function(){
                var val=$(this).val();
                //alert(val);
                if(val=="s"){
                    $("#stock_disp").show();
                    $("#quality_disp").show();
                    
                }else if(val=="c"){
                    $("#stock_disp").show();
                    $("#quality_disp").hide();
                }
                // if(val=="s"){
                //     $("#stock_disp").show();
                // } else 
                // if(val=="c"){
                //     $("#stock_disp").hide();
                //     $("#quality_disp").show();
                // }
            });
        
    });
</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                        <h4 class="modal-title">Suppliers</h4>
                      <button type="button" class="close" data-dismiss="modal">&times</button>
                      
                    </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!--<td>Group No</td>-->
                                <td><b>Group Name</b></td>
                                <td><b>Supplier Id</b></td>
                                <td><b>Supplier Name</b></td>
                                <td><b>Mobile Number</b></td>
                                <td><b>Address</b></td>
                                <td><b>Edit</b></td>
                                <td><b>Delete</b></td>
                            </tr>                
                        </thead>
                         <tbody id="produ_details">
                             
                         </tbody>
                    </table>
                </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  

