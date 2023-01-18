<?php
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
    $delete="DELETE FROM sar_farmer WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    
    
   
    
}
 ?>
 
 <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-lg-6">
                      <?php
                      if($message !='')
                      {
                          echo $message;
                      }
                      ?>
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add Farmer</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="POST" action="">
                               
                                 <?php
                                 $exp_qry="SELECT id FROM sar_farmer ORDER BY id DESC LIMIT 1 ";
                                 $exp_sql=$connect->prepare("$exp_qry");
                                 $exp_sql->execute();
                                 $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
                                 $Last_id=$exp_row["id"]+1;
                                 $exp_no = "FAR_".date("Ym")."0".$Last_id;
                                   ?>
                                 <input type="text" class="form-control" id="farmer_no" name="farmer_no" value="<?=$exp_no?>" readonly>
                              
                                <div class="form-group">
                                 <label for="exampleInputdate">Name </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="farmer_name" name="farmer_name" value="" required>
                                 
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Mobile Number </label>
                                 <input type="text" class="form-control" id="farmer_contact_number" name="farmer_contact_number" maxlength="10" pattern="^[6-9]\d{9}$" value="">
                                  
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Address </label><span style="color:red">*</span>
                                 <input type="text" class="form-control" id="address" name="Address" value="" required>
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
                              <h4 class="card-title">View Farmer</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                    &nbsp;&nbsp;
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Farmer No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Address</th>
                        
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
   $message='';
if(isset($_POST["submit"])){
 
  $exp_qry="SELECT id FROM sar_farmer ORDER BY id DESC LIMIT 1 ";
  $exp_sql=$connect->prepare("$exp_qry");
  $exp_sql->execute();
  $exp_row=$exp_sql->fetch(PDO::FETCH_ASSOC);
  $Last_id=$exp_row["id"]+1;
  $exp_no = "FAR_".date("Ym")."0".$Last_id;
   $farmer_no = $_POST["farmer_no"];
  $farmer_name = ucwords($_POST["farmer_name"]);
  $farmer_contact_number= $_POST["farmer_contact_number"];
  $address = $_POST["Address"];
  
  $select_name="select farmer_name from sar_farmer where farmer_name='".$farmer_name."'";
    $select_sql=mysqli_query($con,$select_name);
    $select_row=mysqli_fetch_assoc($select_sql);
    $select_mobile="select farmer_name from sar_farmer where farmer_contact_number='".$farmer_contact_number."'";
    $select_sql_mobile=mysqli_query($con,$select_mobile);
    $select_row_mobile=mysqli_fetch_assoc($select_sql_mobile);
    if(mysqli_num_rows($select_sql)>=1  )
    {
        echo'<script>';
        echo'alert("Farmer Already Exists")';
        echo '</script>';
    }
     else if( mysqli_num_rows($select_sql_mobile)>=1)
    {
        echo'<script>';
        echo'alert("Farmer Mobile number Already Exists")';
        echo '</script>';
    }
   
    else
    {
         if($id==""){
  $query_1 = "INSERT INTO `sar_farmer`(farmer_no,farmer_name,farmer_contact_number,Address)values('$farmer_no','$farmer_name',
                '$farmer_contact_number','$address')";
                
                
        
        $sql_1= mysqli_query($con,$query_1);
       

  } 
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
                "url": "forms/ajax_request.php?action=view_farmer",
                "type": "POST"
            },
            "columns": [
                { "data": "farmer_no" },
                { "data": "farmer_name" },
                { "data": "farmer_contact_number" },
                { "data": "Address" },
               
            ],
            columnDefs: [
               
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.farmer_no;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.farmer_name;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.farmer_contact_number;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.Address;
                    }
                }
                
             ]
        });
    
    });
       
   
</script>
<script>
function checkDelete(){
    return confirm('Are you sure you want to delete?');
}
</script>