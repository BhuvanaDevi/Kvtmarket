<?php
include("include/config.php");
session_start();
$message="";

if(isset($_POST["login"]))
{
    if(empty($_POST["user_name"]) || empty($_POST["password"]))
    {
        $message="<label>All Fields are required</label>";
    }
    else
    {
       $query="select * from sar_user where user_name=:user_name AND password= :password AND is_active=1";
        
        $statement= $connect->prepare($query);
       $statement->execute(array(
            'user_name' => $_POST["user_name"],
            'password' => base64_encode($_POST["password"])
        ));
            
            
        $count = $statement ->rowCount();
        if($count > 0)
        {
            $login_fetch=$statement->fetch(PDO::FETCH_ASSOC);
            print_r($login_fetch);
            if($login_fetch!=0)
            {
                $_SESSION["user_id_sar_id"] = $login_fetch["id"];
                $_SESSION["user_name"] = $login_fetch["user_name"];
                $_SESSION["role"]=$login_fetch["role"];
        		header("location:dashboard.php");
            }
        }
        else
        {
            $message="<label>Username or Password is incorrect</label>";
        }
    }
}

?>


<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>AB</title>
      
      <link rel="shortcut icon" href="images/tomato-SAR-5.png" />
    <!--
      <link rel="stylesheet" href="css/bootstrap.min.css">
      
      <link rel="stylesheet" href="./js/chartist/chartist.min.css">
     
      <link rel="stylesheet" href="css/typography.css">
      <link rel="stylesheet" href="css/responsive.css">-->

      <link rel="stylesheet" href="css/style.css">
      <!-- <link rel="stylesheet" href="css/style1.css"> -->
      <!-- Responsive CSS -->
      
   </head>
     
      <body class="sidebar-main-active right-column-fixed header-top-bgcolor">
      
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
        <!-- Sign in Start -->
        <section class="sign-in-page">
            <div class="container mt-5 p-0 bg-white">
                <div class="row no-gutters">
                    <div class="col-sm-6 align-self-center">
                        <div class="sign-in-from">
                            <img src="images/ab-tomato.png" alt="user-image" class="rounded-circle"style="width:150px;">
                                  
                                    <p>Enter your password to access the admin.</p>
                            <form method="post" name="login_form" class="mt-4" >
                        <div class="form-group">
                                    <label for="exampleInputEmail1">User</label>
                                    <input type="text" class="form-control mb-0" id="exampleInputEmail1" name="user_name" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Password</label>
                                    <input type="Password" class="form-control mb-0" placeholder="Password" name="password">
                                </div>

                                <div class="d-inline-block w-100">
                                    <input type="submit" class="btn btn-primary float-right" name="login" value="Log In">
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center">
                        <div class="sign-in-detail text-white">
                            <a class="sign-in-logo mb-5" href="#"><img src="images/ab-tomato.png" class="img-fluid" alt="logo"></a>
                            <div class="slick-slider11">
                                <div class="item">
                                    <img src="images/tomato-SAR-5.png" class="img-fluid1 mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                   
                                </div>
                                <div class="item">
                                    <img src="images/tomato-SAR-5.png" class="img-fluid1 mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                    
                                </div>
                                <div class="item">
                                    <img src="images/tomato-SAR-5.png" class="img-fluid1 mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</body>
</html>
 <script src="js/jquery.min.js"></script>
        <!-- Rtl and Darkmode -->
        <script src="js/rtl.js"></script>
        <script src="js/customizer.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- Appear JavaScript -->
      <script src="js/jquery.appear.js"></script>
      <!-- Countdown JavaScript -->
      <script src="js/countdown.min.js"></script>
      <!-- Counterup JavaScript -->
      <script src="js/waypoints.min.js"></script>
      <script src="js/jquery.counterup.min.js"></script>
      <!-- Wow JavaScript -->
      <script src="js/wow.min.js"></script>
      <!-- Apexcharts JavaScript -->
      <script src="js/apexcharts.js"></script>
      <!-- Slick JavaScript -->
      <script src="js/slick.min.js"></script>
      <!-- Select2 JavaScript -->
      <script src="js/select2.min.js"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="js/owl.carousel.min.js"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="js/jquery.magnific-popup.min.js"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="js/smooth-scrollbar.js"></script>
      <!-- lottie JavaScript -->
      <script src="js/lottie.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>