<?php
require "header.php";
$date1 = date('Y-m-d'); 
   
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

if($req=="delete")
{
    $delete="DELETE FROM quality WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header('Location: view_tray_inventory.php');
   
}
 ?>
<div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-lg-12">
                     <div class=""style="padding:0">
                        <div class="iq-card-body p-0">
                           <div class="iq-edit-list">
                              <ul class="iq-edit-profile d-flex nav nav-pills">
                              <li class="col-md-4 p-0">
                              </li>
                                 <li class="col-md-4 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                       Add Chit
                                    </a>
                                 </li>
                                 <!-- <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                       Add & Delete Trays
                                    </a>
                                 </li> -->
                                 
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="iq-edit-list-data">
                        <div class="tab-content">
                           <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                               <div class="iq-card">
                                 
                                 <div class="add-item-flex">
                                     <div class="add-item-row">
                                     <div class="row">
                                         <div class="container-fluid">
                                 <!-- <div class="iq-card-body iq-card1">
                                     <h4 class="card-title">Add Quality</h4>
                                  
                                 </div>
                  -->
                     <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">View Chit List</h4>
                           </div>
                        </div>
                        <div class="iq-card-body card1">
                           <div class="row col-md-12">
                           <div class="col-md-6">
                              <button type="button" style="float: right;" id="download" name="download" class="btn btn-danger">Download</button>
                              </div>
                              <div class="col-md-6">
                              <button type="button" id="add" name="add" style="color: #fff;" class="btn btn-success mymodalQuality">Add chit</button>
                              </div>
                            </div>
                           <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead> 
                            </table>
                        </div>
                    </div>
                 </div>
             </div>
        </div>
    </div>
                              
</div>
                           
            <!-- <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                     <div class="add-item-flex">
                         <div class="add-item-row">
                                 <div class="row col-md-12">
                                         <div class="col-md-6">
                                 <div class="iq-card-body iq-card">
                                     <h4 class="card-title">Add trays</h4><br/>
                                    <form method="post" name="tray_form">
                                       <div class="form-group">
                                          <label for="cpass">Add New Trays</label>
                                         
                                             <input type="text" required class="form-control" id="cpass" name="total_trays">
                                          </div>
                                          <div class="form-group">
                                          <label for="cpass">Select Tray Types</label>
                                        <select name="type" class="form-control">
                                        <option value="">Select Tray Type</option>
                                      <option value="Big Tray">Big Tray</option>
                                        <option value="Small Tray">Small Tray</option>
                                        </select>
                                        </div>
                                          <input type="submit" name="add_trays" class="btn btn-primary mr-2" value="Submit">
                                      </form>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                 <div class="iq-card-body iq-card">
                                     <h4 class="card-title">Delete trays</h4>
                                     <form method="post" name="tray_delete">
                                       <div class="form-group"><br>
                                          <label for="npass">Delete Trays</label>
                                          <input required type="text" class="form-control" id="npass" name="delete_trays">
                                       </div>
                                       <div class="form-group"><br>
                                          <label for="npass">Select Tray Types</label>
                                          <select name="tray_type" class="form-control">
                                          <option value="">Select Tray Type</option>
                                            <option value="Big Tray">Big Tray</option>
                                            <option value="Small Tray">Small Tray</option>
                                          </select>
                                       </div>
                                        <input type="submit" name="delete_tray" class="btn btn-primary mr-2" value="submit">
                                      
                                     </form>
                                
                                 </div>
                                 </div>
                                 </div>
                                
                  
                  
                                 </div>
                                 </div>
                                 </div>
                              </div>
                           </div>
                     </div>
                  </div>
               </div>
            </div> -->
         </div>
     
      <!-- Wrapper END -->
      <!-- Footer -->
     
 <?php
require "footer.php";

if(isset($_POST["add_chit"]))
{
    $select_qry = "SELECT * FROM chit ORDER BY id DESC limit 1";
    $select_sql = $connect->prepare($select_qry);
    $select_sql->execute();
    $group_row=$select_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$group_row["id"]+1;
    $chit_id = "chit_".date("Ym")."0".$Last_id;
    $customer_name = $_POST['customer_name'];
    $customer_date = $_POST['add_date'];
    $add_customer_sql = "INSERT INTO `chit` SET chitname='$customer_name', chitdate='$customer_date', chitid='$chit_id'";
    $add_customer = $connect->prepare($add_customer_sql);
    if($add_customer->execute())
    {
    //    $result='<div class="alert alert-success">Success</div>';
       echo "<script>alert('Success')</script>";
    }
    
}
// if(isset($_POST["add_trays"]))
// {
// //  print_r($_POST);   
//     $total_trays=$_POST["total_trays"];
//     $traytype=$_POST["type"];
//     $select_qry="SELECT * FROM trays where category='Admin' and type='$traytype' ORDER BY id DESC limit 1";
//     $select_sql=$connect->prepare($select_qry);
//     $select_sql->execute();
//     $select_fetch=$select_sql->rowCount();
   
//     //print_r($select_fetch);
//     if($select_fetch==0){
//         $ab="SELECT * FROM trays ORDER BY id DESC LIMIT 1 ";
//         $balance_sql=$connect->prepare("$ab");
//         $balance_sql->execute();
//         $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);
//               $abtray=$bal_row["ab_tray"]+$total_trays;
//               $small=$bal_row["smalltray"];
//               $big=$bal_row["bigtray"];
//               $absmall=$bal_row["absmall"];
//               $abbig=$bal_row["abbig"];

//               if($traytype=="Small Tray"){
//               if($small!=0)
// {
//                   $small=$bal_row["smalltray"]+$total_trays;
// }
// else{
//     $small=$total_trays;
// }

// if($big!=0){
//     $big=$big;
// }
// else{
//     $big=0;
// }

// if($absmall!=0)
// {
//                   $absmall=$bal_row["absmall"]+$total_trays;
// }
// else{
//     $absmall=$total_trays;
// }

// if($abbig!=0){
//     $abbig=$abbig;
// }
// else{
//     $abbig=0;
// }
 
// }
//               else if($traytype=="Big Tray"){
//                 if($big!=0)
//   {  
//                     $big=$bal_row["bigtray"]+$total_trays;
//   }
//   else{
//       $big=$total_trays;
//   }
  
//   if($small!=0){
//       $small=$small;
//   }
//   else{
//       $small=0;
//   }
  
// if($abbig!=0)
// {
//                   $abbig=$bal_row["abbig"]+$total_trays;
// }
// else{
//     $abbig=$total_trays;
// }

// if($absmall!=0){
//     $absmall=$absmall;
// }
// else{
//     $absmall=0;
// }
//                 }
// $small=isset($small)?$small:0;
// $big=isset($big)?$big:0;
// $absmall=isset($absmall)?$absmall:0;
// $abbig=isset($abbig)?$abbig:0;

//               $add_trays_query="insert into `trays` SET date='$date1',name='Admin',no_of_trays='$total_trays',type='$traytype',description='Tray Added',inward='$total_trays',outward=0,inhand='$total_trays',updated_by='Admin',category='Admin',ab_tray='$abtray',smalltray='$small',bigtray='$big',absmall='$absmall',abbig='$abbig'";
//         // print_r($add_trays_query);die();
//               $trays_sql=$connect->prepare($add_trays_query);
//         $trays_sql->execute();
//     } else {
//         $fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//        // print_r($fetch);
//         // $id=$fetch["id"];
//         $total_amt=$fetch["inhand"]+$total_trays;
  
//         $ab="SELECT * FROM trays ORDER BY id DESC LIMIT 1 ";
//         $balance_sql=$connect->prepare("$ab");
//         $balance_sql->execute();
//         $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);
//               $abtray=$bal_row["ab_tray"]+$total_trays;
//               $small=$bal_row["smalltray"];
//               $big=$bal_row["bigtray"];
//               $absmall=$bal_row["absmall"];
//               $abbig=$bal_row["abbig"];

//               if($traytype=="Small Tray"){
//                 if($small!=0)
//   {
//                     $small=$bal_row["smalltray"]+$total_trays;
//   }
//   else{
//       $small=$total_trays;
//   }
  
//   if($big!=0){
//       $big=$big;
//   }
//   else{
//       $big=0;
//   }
   
//   if($absmall!=0)
//   {
//                     $absmall=$bal_row["absmall"]+$total_trays;
//   }
//   else{
//       $absmall=$total_trays;
//   }
  
//   if($abbig!=0){
//       $abbig=$abbig;
//   }
//   else{
//       $abbig=0;
//   }

// }
//                 else if($traytype=="Big Tray"){
//                   if($big!=0)
//     {
//                       $big=$bal_row["bigtray"]+$total_trays;
//     }
//     else{
//         $big=$total_trays;
//     }
    
//     if($small!=0){
//         $small=$small;
//     }
//     else{
//         $small=0;
//     }
//     if($abbig!=0)
//     {
//                       $abbig=$bal_row["abbig"]+$total_trays;
//     }
//     else{
//         $abbig=$total_trays;
//     }
    
//     if($absmall!=0){
//         $absmall=$absmall;
//     }
//     else{
//         $absmall=0;
//     }
//                   }

// $small=isset($small)?$small:0;
// $big=isset($big)?$big:0;
// $absmall=isset($absmall)?$absmall:0;
// $abbig=isset($abbig)?$abbig:0;
      
//         $add_trays_query="insert into `trays` SET date='$date1',name='Admin',no_of_trays='$total_trays',type='$traytype',description='Tray Added',inward='$total_trays',outward=0,inhand='$total_amt',updated_by='Admin',category='Admin',ab_tray='$abtray',absmall='$absmall',abbig='$abbig'";
//         $trays_sql=$connect->prepare($add_trays_query);
//         $trays_sql->execute();

//     }

//     // $trays = $_POST['total_trays'];
//     // $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//     // $balance_sql=$connect->prepare("$balance_qry");
//     // $balance_sql->execute();
//     // $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//     // $balance = $bal_row["inhand"] + $trays;
//     // $date1 = date('Y-m-d'); 
//     // $tray_trans_qry = "INSERT INTO tray_transactions SET 
//     //                 date = '$date1',
//     //                 name = 'Unknown',
//     //                 category = 'Trays',
//     //                 inward = '$trays',
//     //                 inhand = '$balance',
//     //                 updated_by = '$username',
//     //                 description = 'Item Add'";
//     // $res2=mysqli_query($con,$tray_trans_qry);
//     header('Location: view_tray_inventory.php');
// }

// if(isset($_POST["delete_tray"]))
// {
    
    
//     $total_trays=$_POST["delete_trays"];
//     $traytype=$_POST["tray_type"];
//     $select_qry="SELECT * FROM trays where category='Admin' and type='$traytype' ORDER BY id DESC limit 1";
//     $select_sql=$connect->prepare($select_qry);
//     $select_sql->execute();
//     $select_fetch=$select_sql->rowCount();
   
//     //print_r($select_fetch);
//     if($select_fetch==0){
//         $ab="SELECT * FROM trays ORDER BY id DESC LIMIT 1 ";
//         $balance_sql=$connect->prepare("$ab");
//         $balance_sql->execute();
//         $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);
//               $abtray=$bal_row["ab_tray"]-$total_trays;
//               $small=$bal_row["smalltray"];
//               $big=$bal_row["bigtray"];
//               $absmall=$bal_row["absmall"];
//               $abbig=$bal_row["abbig"];
//    if($traytype=="Small Tray"){
//                 if($small!=0)
//   {
//                     $small=$bal_row["smalltray"]-$total_trays;
//   }
//   else{
//       $small=$total_trays;
//   }
  
//   if($big!=0){
//       $big=$big;
//   }
//   else{
//       $big=0;
//   }

//   if($absmall!=0)
//   {
//                     $absmall=$bal_row["absmall"]-$total_trays;
//   }
//   else{
//       $absmall=$total_trays;
//   }
  
//   if($abbig!=0){
//       $abbig=$abbig;
//   }
//   else{
//       $abbig=0;
//   }
//                 }
//                 else if($traytype=="Big Tray"){
//                   if($big!=0)
//     {
//                       $big=$bal_row["bigtray"]-$total_trays;
//     }
//     else{
//         $big=$total_trays;
//     }
    
//     if($small!=0){
//         $small=$small;
//     }
//     else{
//         $small=0;
//     }
    
//     if($abbig!=0)
//     {
//                       $abbig=$bal_row["abbig"]-$total_trays;
//     }
//     else{
//         $abbig=$total_trays;
//     }
    
//     if($absmall!=0){
//         $absmall=$absmall;
//     }
//     else{
//         $absmall=0;
//     }
//                   }

// $small=isset($small)?$small:0;
// $big=isset($big)?$big:0;

// $absmall=isset($absmall)?$absmall:0;
// $abbig=isset($abbig)?$abbig:0;

//       $add_trays_query="insert into `trays` SET date='$date1',name='Admin',no_of_trays='$total_trays',type='$traytype',description='Tray Deleted',inward='0',outward='$total_trays',inhand='$total_trays',updated_by='Admin',category='Admin',ab_tray='$total_trays',smalltray='$small',bigtray='$big',absmall='$absmall',abbig='$abbig'";
//     //   print_r($add_trays_query);die();  
//         $trays_sql=$connect->prepare($add_trays_query);
//         $trays_sql->execute();
//     } else {
//         $fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//        // print_r($fetch);
//         // $id=$fetch["id"];
//         $total_amt=$fetch["inhand"]-$total_trays;

//         $ab="SELECT * FROM trays ORDER BY id DESC LIMIT 1 ";
//         $balance_sql=$connect->prepare("$ab");
//         $balance_sql->execute();
//         $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);
//         $abtray=$bal_row["ab_tray"]-$total_trays;
//         $small=$bal_row["smalltray"];
//         $big=$bal_row["bigtray"];
//         $absmall=$bal_row["absmall"];
//         $abbig=$bal_row["abbig"];

//         if($traytype=="Small Tray"){
//           if($small!=0)
// {
//               $small=$bal_row["smalltray"]-$total_trays;
// }
// else{
// $small=$total_trays;
// }

// if($big!=0){
// $big=$big;
// }
// else{
// $big=0;
// }

// if($absmall!=0)
// {
//               $absmall=$bal_row["absmall"]-$total_trays;
// }
// else{
// $absmall=$total_trays;
// }

// if($abbig!=0){
// $abbig=$abbig;
// }
// else{
// $abbig=0;
// }
//           }
//           else if($traytype=="Big Tray"){
//             if($big!=0)
// {
//                 $big=$bal_row["bigtray"]-$total_trays;
// }
// else{
//   $big=$total_trays;
// }

// if($small!=0){
//   $small=$small;
// }
// else{
//   $small=0;
// }

// if($abbig!=0)
// {
//                 $abbig=$bal_row["abbig"]-$total_trays;
// }
// else{
//   $abbig=$total_trays;
// }

// if($absmall!=0){
//   $absmall=$absmall;
// }
// else{
//   $absmall=0;
// }
//             }

// $small=isset($small)?$small:0;
// $big=isset($big)?$big:0;
// $absmall=isset($absmall)?$absmall:0;
// $abbig=isset($abbig)?$abbig:0;
//         // $add_trays_query="update `sar_trays`
//         // SET total_trays='$total_amt' where id=".$id;
//         //         $trays_sql=$connect->prepare($add_trays_query);
//         // $trays_sql->execute();

//         $add_trays_query="insert into `trays` SET date='$date1',name='Admin',no_of_trays='$total_trays',type='$traytype',description='Tray Deleted',inward='0',outward='$total_trays',inhand='$total_amt',updated_by='Admin',category='Admin',ab_tray='$abtray',smalltray='$small',bigtray='$big',absmall='$absmall',abbig='$abbig'";
//         $trays_sql=$connect->prepare($add_trays_query);
//         $trays_sql->execute();

//     }

//     // $trays = $_POST['total_trays'];
//     // $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//     // $balance_sql=$connect->prepare("$balance_qry");
//     // $balance_sql->execute();
//     // $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//     // $balance = $bal_row["inhand"] - $trays;
//     // $tray_trans_qry = "INSERT INTO tray_transactions SET 
//     //                 date = '$date1',
//     //                 name = 'Unknown',
//     //                 category = 'Trays',
//     //                 outward = '$trays',
//     //                 inhand = '$balance',
//     //                 updated_by = '$username',
//     //                 description = 'Item Delete'";
//     // $res2=mysqli_query($con,$tray_trans_qry);
//     header('Location: view_tray_inventory.php');

// }

// if(isset($_POST["DELETE_QUERY"]))

// {
    
//     /*$select_qry="SELECT * FROM quality ORDER BY id DESC LIMIT 1 ";
//     $select_sql=$connect->prepare($select_qry);
//     $select_sql->execute();
//     $fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//         $id=$fetch["id"];*/
        
//     $delete_query="DELETE FROM quality WHERE id=$id";
    
//     $query=$connect->prepare($delete_query);
//     $query->execute();
//     $update_query=$connect->prepare($update_query);
//     $update_query->execute();
// }

?>
<script>
// $.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            
            "ajax": {
                "url": "forms/ajax_request.php?action=view_chit",
                "type": "POST"
            },
            "columns": [

                { "data": "chitname" },
                { "data": "chitdate" },
                { "data": "chitamt" },
                { "data": "updatedate" }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, row) {
                        // return row.chitname;
                        return '<a href="#" class="mymodal">' + row.chitname + '</a>';
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.chitdate;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.chitamt;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.updatedate;
                    }
                }
                
             ],
             "order": [[ 1, 'asc' ]]
        });
         $("#download").on("click",function(){
            window.location="download_quality_list_report.php";
        });

        $('.mymodalQuality').on('click', function (){
    $( "#mymodal_quality" ).modal( "show" );
});
 $('.close').on('click', function (){
    $( "#mymodal_quality" ).modal( "hide" );
});
    $('#example tbody').on('click', '.mymodal', function() {
        $("#myModal").modal("show");
    });
    $(".close").click(function() {
        $("#myModal").modal("hide");
    });
    });
   
</script>

<div class="modal fade" id="mymodal_quality" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Wastage</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                    <form method="post">
                                       <div class="form-group">
                                          <label for="cpass">Customer Name</label>
                                         
                                             <input type="text" required class="form-control" name="customer_name">
                                             <label for="cpass">Date</label>
                                         
                                             <input type="date" required class="form-control" name="add_date">
                                          </div>
                                          <input type="submit" name="add_chit" class="btn btn-primary mr-2" value="Submit">
                                       <!--<button type="reset" class="btn iq-bg-danger">Cancel</button>-->
                               <div class="form-group">                                
                                  <div class="col-sm-10 col-sm-offset-2">
                                    <?php echo $result; ?>    
                                </div>
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
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="color:#f55989;">Add Wastage</h4>
                <button type="button" class="btn-btn close" data-bs-dismiss="modal">X</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <div id="tabs1" class="tabcontent">
                    <form method="post">
                                       <div class="form-group">
                                          <label for="cpass">Customer Name</label>
                                         
                                             <input type="text" required class="form-control" name="customer_name">
                                             <label for="cpass">Date</label>
                                         
                                             <input type="date" required class="form-control" name="add_date">
                                          </div>
                                          <input type="submit" name="add_chit" class="btn btn-primary mr-2" value="Submit">
                                       <!--<button type="reset" class="btn iq-bg-danger">Cancel</button>-->
                               <div class="form-group">                                
                                  <div class="col-sm-10 col-sm-offset-2">
                                    <?php echo $result; ?>    
                                </div>
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
