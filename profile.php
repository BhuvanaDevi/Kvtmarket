<?php
require "header.php";

if(isset($_POST['submit']))
{
    //  $web_register_qry = "SELECT * from sar_user where user_name='".$_SESSION["user_name"]."' ";

    //                       $web_register_sql=$connect->prepare($web_register_qry);
    //                       $web_register_sql->execute();
    //                       $web_register_row=$web_register_sql->fetch(PDO::FETCH_ASSOC);
    $user_name=$_SESSION['user_name'];
    $password=base64_encode($_POST['password']);
   
  $web_register_qry = "UPDATE sar_user SET user_name=:user_name,password=:password WHERE user_name = :user_name";
$web_register_sql=$connect->prepare($web_register_qry);
    $web_register_sql->execute(array(':user_name'=>$user_name,
':password'=>$password));

}
?>
 
 
<div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                <div class="col-lg-6">
                    
                     
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">My Profile</h4>
                           </div>
                           <div>
                               <button type="button" class="profile_modal"data-toggle="modal" data-target="#profileModal?user_name='.$_SESSION["user_name"].'" style="background:none;border:none"><i class='bx bxs-edit'></i></button>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <?php
                           $web_register_qry = "SELECT * from sar_user where user_name='".$_SESSION["user_name"]."' ";

                           $web_register_sql=$connect->prepare($web_register_qry);
                           $web_register_sql->execute();
                           $web_register_row=$web_register_sql->fetch(PDO::FETCH_ASSOC);
                           $password=base64_decode($web_register_row["password"]);
                          echo'<table>';
                          echo'<tr>';
                          echo'<th>User Name : </th><td>'.$web_register_row["user_name"].'</td>';
                         
                          echo'</tr>';
                           echo'<tr>';
                          echo'<th>Password : </th><td>'.$password.'</td>';
                         
                          echo'</tr>';
                          echo'</table>';
                           ?>
                        </div>
                      </div>
                      </div>
                
        </div>
    </div>
</div>
 

         

<?php require "footer.php" ?> 
<div class="modal" id="profileModal">
   <div class="modal-dialog" id="profile_modal" style="width:450px">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Your Profile</h4>
           <button type="button" class="close" data-dismiss="modal" style="border:none;background:none;">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <p class="msg_txt"></p>
         <form id="account_form" method="POST">
                   <?php echo $result?>
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <td><input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $web_register_row['user_name'];?>"></td>
                        </tr>
                        
                         <tr>
                            <th>Password</th>
                            <td><input type="text" name="password" id="state" class="form-control" value="<?php echo $password;?>"></td>
                        </tr>
                        <tr>
                          <th></th>  
                           <td >
                                 <input type="submit" name="submit" class="btn btn-primary" value="Submit"  />
                            </td>
                        </tr>
                    </table>
        </form>
        </div>
        <!-- Modal footer -->
      </div>
    </div>
  </div>    
  
<script>
    $(document).ready(function(){
        $(".profile_modal").click(function(){
           $("#profileModal").modal('show');
        });
             $(".close").click(function(){
                $("#profileModal").modal('hide');
            });
    });
</script>