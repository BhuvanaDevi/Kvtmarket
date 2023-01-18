<?php
require "header.php";
$supplier_qry="SELECT * FROM `sar_user` WHERE id=:id ";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute(array(':id' => $id));
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);
?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                 <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Add User</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="POST" action="">
                               <div class="form-group">
                                 <label for="exampleInputText1">User Name </label><span style="color:red">*</span>
                                 
                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?=$supplier_row["user_name"]?>" required>

                              </div>
                              <div class="form-group">
                                 <label for="exampleInputText1">Password </label><span style="color:red">*</span>
                                 <input type="password" class="form-control" id="password" name="password" required>
                              </div>
                              
                              <div class="form-group">
                                 <label for="exampleInputText1">Role </label><span style="color:red">*</span>
                                 <select class="form-control" name="role">
                                     <option value="">--Select Role --</option>
                                      <option value="admin">Admin</option>
                                     <option value="user">User</option>
                                 </select>
                                  </div>
                             
                              
                               
                              <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                           </form>
                        </div>
                    
                    
                      </div>
                  </div>
              
               </div>
            </div>
         </div>
<?php
require "footer.php";
if(isset($_POST["submit"]))
{
    $user_name=$_POST["user_name"];
    $password=base64_encode($_POST["password"]);
    $role=$_POST["role"];
    if($id==""){
    $user_insert_query="INSERT INTO `sar_user` SET 
                user_name='$user_name',
                password='$password',
                role='$role',
                is_active=1";
                  //echo $employee_advance_insert_query;             
        $sql_1= $connect->prepare($user_insert_query);
     $sql_1->execute();
    }
    else if($user_name != "" && $password != "" ) {
        
            $query_1 = "UPDATE `sar_user` SET 
                        user_name='$user_name',
                        password='$password',
                        role='$role',
                        is_active=1
                         WHERE id='$id'";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    }    
    header("location:add_user.php");
}
?>

