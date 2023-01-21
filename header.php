<?php require "include/config.php";

session_start();

 if(isset($_SESSION["user_id_sar_id"])!=0){
     $username=$_SESSION["user_name"];
     $user_role=$_SESSION["role"];
     $user_id=$_SESSION["user_id_sar_id"];
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

      

     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />

    <link rel="stylesheet" type="text/css" href="js/datatable/datatable.css" />

    <link rel="stylesheet" type="text/css" href="js/datatable/dataTable_responsive.css" />

    <link rel="stylesheet" type="text/css" href="js/datatable/jquery.dataTables.min.css" />

    

    <link rel="stylesheet" type="text/css" href="js/datatable/datatable.css" />

     <link href="boxicons/css/boxicons.min.css" rel="stylesheet"> 

      

      <!-- Responsive CSS -->

      

   </head>

   <body class="sidebar-main-active right-column-fixed header-top-bgcolor">

      <!-- loader Start -->

      <div id="loading">

         <div id="loading-center">

         </div>

      </div>

      <!-- loader END -->

      <!-- Wrapper Start -->

     

         <!-- Sidebar  -->

         <div class="iq-sidebar">

            <div class="iq-sidebar-logo d-flex justify-content-between">

               <a href="dashboard.php">

               <div class="iq-light-logo">

                  <div class="iq-light-logo">

                     <img src="images/tomato-SAR-5.png" class="img-fluid" alt="">

                   </div>

                     <div class="iq-dark-logo">

                        <img src="images/tomato-SAR-5.png" class="img-fluid" alt="">

                     </div>

               </div>

               <div class="iq-dark-logo">

                  <img src="images/tomato-SAR-5.png" class="img-fluid" alt="">

               </div>

               <span>AB</span>

               </a>

               <div class="iq-menu-bt-sidebar">

                  <div class="iq-menu-bt align-self-center">

                     <div class="wrapper-menu">

                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>

                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>

                     </div>

                  </div>

               </div>

            </div>

            <div id="sidebar-scrollbar">

               <nav class="iq-sidebar-menu">

                  <ul id="iq-sidebar-toggle" class="iq-menu">

                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Home</span></li>

                     <li class="active">

                        <a href="dashboard.php" class="iq-waves-effect"><i class="ri-home-4-line"></i><span>Dashboard</span></a>

                     </li>

                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Apps</span></li>

                     

                     <!-- <li><a href="Trays-Inward_Outward-Entry.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-package'></i><span>Trays Inward & Outward Entry</span></a></li> -->

                     <!-- <li><a href="add_supplier.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-pin'></i><span>Add Supplier</span></a></li>
                     
                     <li><a href="add_farmer.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-pin'></i><span>Add Farmer</span></a></li> -->

                     <li><a href="add_customer.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Add Customer</span></a></li>

                     <li>
                     <li><a href="add_chit.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Add Chit</span></a></li>

                     <li>
                     <li><a href="add_open_balance.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Opening Balance</span></a></li>

                     <li>
                    <li><a href="stock_purchase.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Stock Purchase</span></a></li>
                    <li><a href="add_user.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Add User</span></a></li>

                    <!-- <li>-->
                   
                    <li><a href="finance.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Finance</span></a></li>
                    <li><a href="ask_finance.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-user-check' ></i><span>Interest</span></a></li>
                          <li><a href="GeneratPatti.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-checkbox-checked'></i><span>Generate Patti</span></a></li>

                          </li>

                     <li>

                        <li><a href="expenditure.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-store-alt' ></i><span>Expenditure</span></a></li>

                          </li>   

                          <li>

                          <!--   <li><a href="Generate-sales-invoice.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-carousel' ></i><span>Generate Sales Invoice</span></a></li>-->

                          <!--</li> -->

                        <li><a href="sales_invoice2.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-carousel' ></i><span>Generate Sales Invoice</span></a></li>
                          </li> 
                             <!-- <li><a href="miscellaneous_revenue.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-carousel' ></i><span>Miscellaneous Revenue</span></a></li>
                          </li>  -->
                          <li><a href="view_wastage.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-book-add' ></i><span>Add Wastage</span></a></li>

                          <li><a href="pay.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-money' ></i><span>Payment</span></a></li>

                          <li><a href="Add-item_demo.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-book-add' ></i><span>Add item</span></a></li>
                     <!--      <li><a href="genarate_invoce_demo.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-carousel' ></i><span>Generate Sales Invoice demo</span></a></li>-->

                          <!--</li> -->

                    <!-- <li>

                        <a href="#userinfo" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-user-line"></i><span>User</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="userinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="profile.html"><i class="ri-profile-line"></i>User Profile</a></li>

                           <li><a href="profile-edit.html"><i class="ri-file-edit-line"></i>User Edit</a></li>

                           <li><a href="add-user.html"><i class="ri-user-add-line"></i>User Add</a></li>

                           <li><a href="user-list.html"><i class="ri-file-list-line"></i>User List</a></li>
                        </ul>

                     </li>

                      <li><a href="Add-item.php" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Add items</span></a></li>

                     <!--<li><a href="calendar.html" class="iq-waves-effect"><i class="ri-calendar-2-line"></i><span>Calendar</span></a></li>

                     <li><a href="chat.html" class="iq-waves-effect"><i class="ri-message-line"></i><span>Chat</span></a></li>

                     

                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Components</span></li>

                     <li>

                        <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-pencil-ruler-line"></i><span>UI Elements</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="ui-elements" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="ui-colors.html"><i class="ri-font-color"></i>colors</a></li>

                           <li><a href="ui-typography.html"><i class="ri-text"></i>Typography</a></li>

                           <li><a href="ui-alerts.html"><i class="ri-alert-line"></i>Alerts</a></li>

                           <li><a href="ui-badges.html"><i class="ri-building-3-line"></i>Badges</a></li>

                           <li><a href="ui-breadcrumb.html"><i class="ri-menu-2-line"></i>Breadcrumb</a></li>

                           <li><a href="ui-buttons.html"><i class="ri-checkbox-blank-line"></i>Buttons</a></li>

                           <li><a href="ui-cards.html"><i class="ri-bank-card-line"></i>Cards</a></li>

                           <li><a href="ui-carousel.html"><i class="ri-slideshow-line"></i>Carousel</a></li>

                           <li><a href="ui-embed-video.html"><i class="ri-slideshow-2-line"></i>Video</a></li>

                           <li><a href="ui-grid.html"><i class="ri-grid-line"></i>Grid</a></li>

                           <li><a href="ui-images.html"><i class="ri-image-line"></i>Images</a></li>

                           <li><a href="ui-list-group.html"><i class="ri-file-list-3-line"></i>list Group</a></li>

                           <li><a href="ui-media-object.html"><i class="ri-camera-line"></i>Media</a></li>

                           <li><a href="ui-modal.html"><i class="ri-stop-mini-line"></i>Modal</a></li>

                           <li><a href="ui-notifications.html"><i class="ri-notification-line"></i>Notifications</a></li>

                           <li><a href="ui-pagination.html"><i class="ri-pages-line"></i>Pagination</a></li>

                           <li><a href="ui-popovers.html"><i class="ri-folder-shield-2-line"></i>Popovers</a></li>

                           <li><a href="ui-progressbars.html"><i class="ri-battery-low-line"></i>Progressbars</a></li>

                           <li><a href="ui-tabs.html"><i class="ri-database-line"></i>Tabs</a></li>

                           <li><a href="ui-tooltips.html"><i class="ri-record-mail-line"></i>Tooltips</a></li>

                        </ul>

                     </li>

                     <li>-->

                        <!--<a href="#forms" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-profile-line"></i><span>Forms</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="forms" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="form-layout.html"><i class="ri-tablet-line"></i>Form Elements</a></li>

                           <li><a href="form-validation.html"><i class="ri-device-line"></i>Form Validation</a></li>

                           <li><a href="form-switch.html"><i class="ri-toggle-line"></i>Form Switch</a></li>

                           <li><a href="form-chechbox.html"><i class="ri-checkbox-line"></i>Form Checkbox</a></li>

                           <li><a href="form-radio.html"><i class="ri-radio-button-line"></i>Form Radio</a></li>

                        </ul>

                     </li>

                     

                     <li>

                        <a href="#tables" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-table-line"></i><span>Table</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="tables" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="tables-basic.html"><i class="ri-table-line"></i>Basic Tables</a></li>

                           <li><a href="data-table.html"><i class="ri-database-line"></i>Data Table</a></li>

                           <li><a href="table-editable.html"><i class="ri-refund-line"></i>Editable Table</a></li>

                        </ul>

                     </li>

                     <li>

                        <a href="#charts" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-pie-chart-box-line"></i><span>Charts</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="charts" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="chart-morris.html"><i class="ri-file-chart-line"></i>Morris Chart</a></li>

                           <li><a href="chart-high.html"><i class="ri-bar-chart-line"></i>High Charts</a></li>

                           <li><a href="chart-am.html"><i class="ri-folder-chart-line"></i>Am Charts</a></li>

                           <li><a href="chart-apex.html"><i class="ri-folder-chart-2-line"></i>Apex Chart</a></li>

                        </ul>

                     </li>

                     <li>

                        <a href="#icons" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-list-check"></i><span>Icons</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="icons" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="icon-dripicons.html"><i class="ri-stack-line"></i>Dripicons</a></li>

                           <li><a href="icon-fontawesome-5.html"><i class="ri-facebook-fill"></i>Font Awesome 5</a></li>

                           <li><a href="icon-lineawesome.html"><i class="ri-keynote-line"></i>line Awesome</a></li>

                           <li><a href="icon-remixicon.html"><i class="ri-remixicon-line"></i>Remixicon</a></li>

                           <li><a href="icon-unicons.html"><i class="ri-underline"></i>unicons</a></li>

                        </ul>

                     </li>-->

                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Reports</span></li>
                       <?php if($user_role=="admin") { ?>
                     <!-- <li><a href="view_user.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bxs-user-account' ></i><span>View User</span></a></li> -->
                     <?php }?>
                     <li><a href="view_patti.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bxs-report'></i><span>View Patti</span></a></li>

                     <li><a href="view_sales_invoice.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-barcode-reader' ></i><span>View Sales Invoice</span></a></li>

                     <!--<li><a href="view_sales_invoice_demo.php" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>View Sales Invoice demo</span></a></li>-->

                     <li><a href="view_date_wise.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-cabinet' ></i><span>View Date Wise Report</span></a></li>
                     <li><a href="view_paymentwise.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-cabinet' ></i><span>View Payment Wise Report</span></a></li>

                     <!-- <li><a href="view_day_wise_report.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-bar-chart-alt-2'></i><span>View Day Wise Report</span></a></li> -->

                     <!-- <li><a href="view_date_wise_report.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-notepad' ></i><span>Revenue Report</span></a></li> -->

                    <!-- <li><a href="view_tray_report.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-copy-alt' ></i><span>View Tray Stocks</span></a></li> -->

                     <!-- <li><a href="view_tray_inventory.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-book-content' ></i><span>View Tray Inventory</span></a></li> -->
                      <!-- <li><a href="view_miscellaneous_revenue.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-book-content' ></i><span>View Miscellaneous Revenue</span></a></li> -->
                      <li><a href="view_stock.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bxs-report'></i><span>View Stock</span></a></li>
                      <li><a href="view_ob_balance.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bxs-report'></i><span>View Open Balance</span></a></li>
                     <!-- <li><a href="view_wastage.php" class="iq-waves-effect" aria-expanded="false"><i class='bx bx-book-content' ></i><span>View Wastage</span></a></li> -->

                     <!-- <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Login</span></li> -->

                    

                  

                    <!-- <li>

                        <a href="#authentication" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-pages-line"></i><span>Authentication</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="authentication" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="sign-in.html"><i class="ri-login-box-line"></i>Login</a></li>

                           <li><a href="sign-up.html"><i class="ri-login-circle-line"></i>Register</a></li>

                           <li><a href="pages-recoverpw.html"><i class="ri-record-mail-line"></i>Recover Password</a></li>

                           <li><a href="pages-confirm-mail.html"><i class="ri-file-code-line"></i>Confirm Mail</a></li>

                           <li><a href="pages-lock-screen.html"><i class="ri-lock-line"></i>Lock Screen</a></li>

                        </ul>

                     </li>

                     <li>

                        <a href="#map-page" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-map-pin-user-line"></i><span>Maps</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="map-page" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="pages-map.html"><i class="ri-google-line"></i>Google Map</a></li>

                           

                        </ul>

                     </li>

                     <li>

                        <a href="#extra-pages" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-pantone-line"></i><span>Extra Pages</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="extra-pages" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="pages-timeline.html"><i class="ri-map-pin-time-line"></i>Timeline</a></li>

                           <li><a href="pages-invoice.html"><i class="ri-question-answer-line"></i>Invoice</a></li>

                           <li><a href="blank-page.html"><i class="ri-invision-line"></i>Blank Page</a></li>

                           <li><a href="pages-error.html"><i class="ri-error-warning-line"></i>Error 404</a></li>

                           <li><a href="pages-error-500.html"><i class="ri-error-warning-line"></i>Error 500</a></li>

                           <li><a href="pages-pricing.html"><i class="ri-price-tag-line"></i>Pricing</a></li>

                           <li><a href="pages-pricing-one.html"><i class="ri-price-tag-2-line"></i>Pricing 1</a></li>

                           <li><a href="pages-maintenance.html"><i class="ri-archive-line"></i>Maintenance</a></li>

                           <li><a href="pages-comingsoon.html"><i class="ri-mastercard-line"></i>Coming Soon</a></li>

                           <li><a href="pages-faq.html"><i class="ri-compasses-line"></i>Faq</a></li>

                        </ul>

                     </li>

                     <li>

                        <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-record-circle-line"></i><span>Menu Level</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>

                        <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                           <li><a href="#"><i class="ri-record-circle-line"></i>Menu 1</a></li>

                           <li><a href="#"><i class="ri-record-circle-line"></i>Menu 2</a></li>

                           <li><a href="#"><i class="ri-record-circle-line"></i>Menu 3</a></li>

                           <li><a href="#"><i class="ri-record-circle-line"></i>Menu 4</a></li>

                        </ul>

                     </li>-->

                  </ul>

               </nav>

               <div class="p-3"></div>

            </div>

         </div>

         <!-- TOP Nav Bar -->

         <div class="iq-top-navbar">

            <div class="iq-navbar-custom">

               <div class="iq-sidebar-logo">

                  <div class="top-logo">

                     <a href="index.html" class="logo">

                     <div class="iq-light-logo">

                  <img src="images/logo.gif" class="img-fluid" alt="">

               </div>

               <div class="iq-dark-logo">

                  <img src="images/logo-dark.gif" class="img-fluid" alt="">

               </div>

                     <span>AB</span>

                     </a>

                  </div>

               </div>

               <nav class="navbar navbar-expand-lg navbar-light p-0">

                  <div class="navbar-left">

                  <ul id="topbar-data-icon" class="d-flex p-0 topbar-menu-icon">

                     <li class="nav-item">

                        <a href="dashboard.php" class="nav-link font-weight-bold search-box-toggle"><i class="ri-home-4-line"></i></a>

                     </li>

                     <li><a href="view_sales_invoice.php" class="nav-link"><i class="ri-message-line"></i></a></li>

                     <li><a href="view_date_wise.php" class="nav-link"><i class="ri-question-answer-line"></i></a></li>

                     <!-- <li><a href="view_tray_inventory.php" class="nav-link router-link-exact-active router-link-active"><i class="ri-chat-check-line"></i></a></li> -->

                  </ul>

                  

               </div>

                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"  aria-label="Toggle navigation">

                  <i class="ri-menu-3-line"></i>

                  </button>

                  <div class="iq-menu-bt align-self-center">

                     <div class="wrapper-menu">

                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>

                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>

                     </div>

                  </div>

                  <div class="collapse navbar-collapse" id="navbarSupportedContent">

                     <!--<ul class="navbar-nav ml-auto navbar-list">-->
                     <!--   <li class="nav-item">-->

                     <!--      <a href="#" class="search-toggle iq-waves-effect">-->

                     <!--         <div id="lottie-beil"></div>-->

                     <!--         <span class="bg-danger dots"></span>-->

                     <!--      </a>-->

                     <!--      <div class="iq-sub-dropdown">-->

                     <!--         <div class="iq-card shadow-none m-0">-->

                     <!--            <div class="iq-card-body p-0 ">-->

                     <!--               <div class="bg-primary p-3">-->

                     <!--                  <h5 class="mb-0 text-white">All Notifications<small class="badge  badge-light float-right pt-1">4</small></h5>-->

                     <!--               </div>-->



                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/01.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Emma Watson Nik</h6>-->

                     <!--                        <small class="float-right font-size-12">Just Now</small>-->

                     <!--                        <p class="mb-0">95 MB</p>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/02.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">New customer is join</h6>-->

                     <!--                        <small class="float-right font-size-12">5 days ago</small>-->

                     <!--                        <p class="mb-0">Jond Nik</p>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/03.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Two customer is left</h6>-->

                     <!--                        <small class="float-right font-size-12">2 days ago</small>-->

                     <!--                        <p class="mb-0">Jond Nik</p>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/04.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">New Mail from Fenny</h6>-->

                     <!--                        <small class="float-right font-size-12">3 days ago</small>-->

                     <!--                        <p class="mb-0">Jond Nik</p>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--            </div>-->

                     <!--         </div>-->

                     <!--      </div>-->

                     <!--   </li>-->

                     <!--   <li class="nav-item dropdown">-->

                     <!--      <a href="#" class="search-toggle iq-waves-effect">-->

                     <!--        <div id="lottie-mail"></div>-->

                     <!--         <span class="bg-primary count-mail"></span>-->

                     <!--      </a>-->

                     <!--      <div class="iq-sub-dropdown">-->

                     <!--         <div class="iq-card shadow-none m-0">-->

                     <!--            <div class="iq-card-body p-0 ">-->

                     <!--               <div class="bg-primary p-3">-->

                     <!--                  <h5 class="mb-0 text-white">All Messages<small class="badge  badge-light float-right pt-1">5</small></h5>-->

                     <!--               </div>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/01.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Nik Emma Watson</h6>-->

                     <!--                        <small class="float-left font-size-12">13 Jun</small>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/02.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Lorem Ipsum Watson</h6>-->

                     <!--                        <small class="float-left font-size-12">20 Apr</small>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/03.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Why do we use it?</h6>-->

                     <!--                        <small class="float-left font-size-12">30 Jun</small>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/04.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Variations Passages</h6>-->

                     <!--                        <small class="float-left font-size-12">12 Sep</small>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--               <a href="#" class="iq-sub-card" >-->

                     <!--                  <div class="media align-items-center">-->

                     <!--                     <div class="">-->

                     <!--                        <img class="avatar-40 rounded" src="images/user/05.jpg" alt="">-->

                     <!--                     </div>-->

                     <!--                     <div class="media-body ml-3">-->

                     <!--                        <h6 class="mb-0 ">Lorem Ipsum generators</h6>-->

                     <!--                        <small class="float-left font-size-12">5 Dec</small>-->

                     <!--                     </div>-->

                     <!--                  </div>-->

                     <!--               </a>-->

                     <!--            </div>-->

                     <!--         </div>-->

                     <!--      </div>-->

                     <!--   </li>-->

                     <!--</ul>-->

                  </div>

                  <ul class="navbar-list">

                     <li>

                        <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">

                           <img src="images/tomato-SAR-5.png" class="img-fluid rounded mr-3" alt="user">

                           <div class="caption">

                              <h6 class="mb-0 line-height text-white">AB</h6>

                              <span class="font-size-12 text-white">Available</span>

                           </div>

                        </a>

                        <div class="iq-sub-dropdown iq-user-dropdown">

                           <div class="iq-card shadow-none m-0">

                              <div class="iq-card-body p-0 ">

                                 <div class="bg-primary p-3">

                                    <h5 class="mb-0 text-white line-height" style="text-transform: capitalize";><?=$username?></h5>

                                    <span class="text-white font-size-12" style="text-transform: capitalize"><?=$user_role?></span>

                                 </div>

                                   <a class="dropdown-item text-danger" data-toggle="modal" data-target="#profileModal" class="iq-sub-card iq-bg-primary-hover">

                                 <!--<a href="profile.php" class="iq-sub-card iq-bg-primary-hover">-->

                                    <div class="media align-items-center">

                                       <div class="rounded iq-card-icon iq-bg-primary">

                                          <i class="ri-file-user-line"></i>

                                       </div>

                                       <div class="media-body ml-3">

                                          <h6 class="mb-0 ">Account Settings</h6>

                                          <p class="mb-0 font-size-12">Change your account details.</p>

                                       </div>

                                    </div>

                                 </a>
                                  <?php if($user_role=="admin") { ?>   
                                 <a class="dropdown-item text-danger" href="add_user.php" class="iq-sub-card iq-bg-primary-hover">

                                 <!--<a href="profile.php" class="iq-sub-card iq-bg-primary-hover">-->

                                    <div class="media align-items-center">

                                       <div class="rounded iq-card-icon iq-bg-primary">

                                          <i class='bx bx-user-pin' ></i>

                                       </div>

                                       <div class="media-body ml-3">

                                          <h6 class="mb-0 ">Add User</h6>

                                          

                                       </div>

                                    </div>

                                 </a>
                                <?php }?>

                                 <!--<a href="profile-edit.html" class="iq-sub-card iq-bg-primary-hover">-->

                                 <!--   <div class="media align-items-center">-->

                                 <!--      <div class="rounded iq-card-icon iq-bg-primary">-->

                                 <!--         <i class="ri-lock-line"></i>-->

                                 <!--      </div>-->

                                 <!--      <div class="media-body ml-3">-->

                                 <!--         <h6 class="mb-0 ">Change Password</h6>-->

                                 <!--         <p class="mb-0 font-size-12">Update your password.</p>-->

                                 <!--      </div>-->

                                 <!--   </div>-->

                                 <!--</a>-->

                                 <!--<a href="account-setting.html" class="iq-sub-card iq-bg-primary-hover">-->

                                 <!--   <div class="media align-items-center">-->

                                 <!--      <div class="rounded iq-card-icon iq-bg-primary">-->

                                 <!--         <i class="ri-account-box-line"></i>-->

                                 <!--      </div>-->

                                 <!--      <div class="media-body ml-3">-->

                                 <!--         <h6 class="mb-0 ">Account settings</h6>-->

                                 <!--         <p class="mb-0 font-size-12">Manage your account parameters.</p>-->

                                 <!--      </div>-->

                                 <!--   </div>-->

                                 <!--</a>-->

                                 <!--<a href="privacy-setting.html" class="iq-sub-card iq-bg-primary-hover">-->

                                 <!--   <div class="media align-items-center">-->

                                 <!--      <div class="rounded iq-card-icon iq-bg-primary">-->

                                 <!--         <i class="ri-lock-line"></i>-->

                                 <!--      </div>-->

                                 <!--      <div class="media-body ml-3">-->

                                 <!--         <h6 class="mb-0 ">Privacy Settings</h6>-->

                                 <!--         <p class="mb-0 font-size-12">Control your privacy parameters.</p>-->

                                 <!--      </div>-->

                                 <!--   </div>-->

                                 <!--</a>-->

                                 <div class="d-inline-block w-100 text-center p-3">

                                    <a class="btn btn-primary dark-btn-primary" href="sign-in.php" role="button">Sign out<i class="ri-login-box-line ml-2"></i></a>

                                 </div>

                              </div>

                           </div>

                        </div>

                     </li>

                  </ul>

               </nav>

               



            </div>

         </div>

         

         </body>

         </html>

         <?php }

         else {

   header("location:sign-in.php");  

}?>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        ...

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <button type="button" class="btn btn-primary">Save changes</button>

      </div>

    </div>

  </div>

</div>

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

                   <?= $result1?>

                    <table class="table">

                        <tr>

                            <th>Name</th>

                            <td><input type="text" readonly name="user_name" id="user_name" class="form-control" value="<?php echo $_SESSION["user_name"];?>" ></td>

                        </tr>

                         <tr>

                            <th>Old Password</th>

                            <td><input type="text" name="old_password" class="form-control"></td>

                        </tr>

                         <tr>

                            <th>New Password</th>

                            <td><input type="text" name="new_password"  class="form-control"></td>

                        </tr>

                         <tr>

                            <th>Confirm Password</th>

                            <td><input type="text" name="password" id="state" class="form-control"></td>

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

 <?php
 if(isset($_POST['submit']))
 {
     $user_name=$_POST['user_name'];
     $password=base64_encode($_POST['password']);
      $sql="UPDATE sar_user SET user_name = '$user_name' , password = '$password'  WHERE user_name='$user_name'";
      $result1= $connect->prepare($sql);
        $result1->execute();
    //   $result=mysqli_query($con,$sql);
 }
 ?>