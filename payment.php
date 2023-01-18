<?php require "header.php";
//print_r($row['balance']);die();

$date = date("Y-m-d");

if (isset($_REQUEST['req']) != "") {
    $req = $_REQUEST["req"];
} else {
    $req = "";
}
if (isset($_REQUEST['id']) != "") {
    $id = $_REQUEST["id"];
} else {
    $id = "";
}
if (isset($_REQUEST['patti_id']) != "") {
$patti_id = $_REQUEST["patti_id"];
} else {
$patti_id = "";
}   

if($req=="enabled")
{
    $update="UPDATE `sar_patti` SET is_active=0 WHERE patti_id=:patti_id";
    $update_sql= $connect->prepare($update);
    $update_sql->execute(array(':patti_id' => $patti_id));
    $insert="INSERT INTO sar_patti_nullify_records(patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,net_payable,payment_status,is_active,updated_by,supplier_id )
SELECT patti_id,patti_date,mobile_number,supplier_name,supplier_address,boxes_arrived,lorry_no,quality_name,quantity,rate,bill_amount,total_bill_amount,
commision,lorry_hire,box_charge,cooli,total_deduction,net_bill_amount,payment_status,is_active,updated_by,supplier_id 
FROM 
   sar_patti
WHERE
   patti_id='".$patti_id."'";
    $insert_sql= $connect->prepare($insert);
        $insert_sql->execute();
    header("location:view_patti.php");
    // if($insert)
    // {
    // $delete="DELETE FROM `sar_patti` WHERE patti_id=:patti_id";
    // $delete_sql= $connect->prepare($delete);
    // $delete_sql->execute(array(':patti_id' => $patti_id));
    // }
}

if($req=="disabled")
{
    $delete="UPDATE `sar_patti` SET is_active=1 WHERE patti_id=:patti_id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':patti_id' => $patti_id));
    header("location:view_patti.php");
}
?>
<!-- 
<div id="content-page" class="content-page">
    <br>
    <div class="container-fluid">
     <select id="invoice" name="invoice">
     <option value="">Please Select Invoice</option>
     <option value="Patti Invoice">Patti Invoice</option>
        <option value="Sales Invoice">Sales Invoice</option>
     </select>
     <select class="form-control" id="supplier" name="supplier" style="width:200px;" >
                      <option value="">Search Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	           }
            	           ?>
                      
    
                    </select>
                    <select class="form-control" id="customer" name="customer" style="width:200px;" >
                      <option value="">Search Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'"."'.$sel_row["mobile_number"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
                        	           }
                        	           ?>
                    </select>
    <p id="nodata"></p>
     
     <table id="list" class="table table-bordered">
     
<thead>
<tr><td>Supplier Name :</td><td id="sup_name"></td></tr>
<tr><td>Mobile :</td><td id="sup_mobile"></td></tr>
<tr><td>Address :</td><td id="sup_address"></td></tr>
     <tr>
    <th>Id</th>
        <th>Date</th>
        <th>Bill Amount</th>
        <th>Remaining</th>
    </tr>
</thead>
    <tbody id="list_body"> 
   </tbody>

     </table>

     <form method="POST" action="" id="pay">
     <input type="text" name="supname" id="supname" value="">
     <input type="text" name="net_bill_amount" id="net" value="">
     <input type="text" name="amount" value="">
        <input type="date" name="payment_date" value="<?= $date ?>">
       <select id="payment_mode" name="payment_mode">
        <option disabled>Select Payment Mode</option>
        <option value="NEFT">NEFT</option>
        <option value="Cash">Cash</option>
        <option value="Online">Online</option>
        <option value="DD">DD</option>
       </select> 
       <select id="discount_type" name="discount_type">
        <option disabled selected>Select Discount Type

        </option>
        <option value="Percentage">Percentage</option>
        <option value="Cash">Cash</option>
       </select> 
       <input type="text" name="discount" value="">
       <input type="submit" name="add_payment" value="Payment">
     </form>
</div>
</div> -->
<div id="content-page" class="content-page">
        <div class="container-fluid">
          <div class="row">
              <div class=" col-lg-6">
                  <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">Invoice</h4>
                          </div>
                        </div>
                        <div class="iq-card-body iq-search-bar iq-search-bar1  d-md-block">
                 <div class="row col-md-12 mt-3">   
                 <div class="col-md-6">Choose Invoice</div>   
                 <div class="col-md-6">
                 <select id="invoice" class="form-control" name="invoice">
     <option value="">Please Select Invoice</option>
     <option value="Patti Invoice">Patti Invoice</option>
        <option value="Sales Invoice">Sales Invoice</option>
     </select>
     </div>   
     <div class="col-md-4"></div>   
                 <div class="col-md-6">
                 <select class="form-control mt-4" id="supplier" name="supplier" style="width:200px;" >
                      <option value="">Choose Supplier Name </option>
                       <?php
                            $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        	$sel_sql= $connect->prepare($sel_qry);
            	            $sel_sql->execute();
            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	                echo '<option value="'.$sel_row["supplier_name"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	           }
            	           ?>
                      
    
                    </select>
                    
                    <select class="form-control mt-4" id="customer" name="customer" style="width:200px;" >
                      <option value="">Search Customer Name </option>
                      <?php
                                        $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    	$sel_sql= $connect->prepare($sel_qry);
                        	            $sel_sql->execute();
                        	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	                echo '<option value="'.$sel_row["customer_name"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
                        	           }
                        	           ?>
                    </select>
                </div>
                 
                    </div>
    <div class="row col-md-12">
    <div class="col-md-12"> 
    <p id="nodatas" style="text-align: center;" class="mt-3"></p>
    </div></div>
     <table id="list" class="table table-bordered">
     
<thead>
<tr><td></td><td>Supplier Name</td><td id="sup_name"></td><td></td></tr>
<tr><td></td><td>Mobile</td><td id="sup_mobile"></td><td></td></tr>
<tr><td></td><td>Address</td><td id="sup_address"></td><td></td></tr>
     <tr>
    <th>Id</th>
        <th>Date</th>
        <th>Bill Amount</th>
        <th>Remaining</th>
    </tr>
</thead>
    <tbody id="list_body"> 
   </tbody>

     </table>

     <table id="cuslist" class="table table-bordered">
     
     <thead>
     <tr><td></td><td>Customer Name</td><td id="cus_name"></td><td></td></tr>
     <tr><td></td><td>Mobile</td><td id="cus_mobile"></td><td></td></tr>
     <tr><td></td><td>Address</td><td id="cus_address"></td><td></td></tr>
          <tr>
         <th>Id</th>
             <th>Date</th>
             <th>Bill Amount</th>
             <th>Remaining</th>
         </tr>
     </thead>
         <tbody id="cuslist_body"> 
        </tbody>
     
          </table>
  
                        </div>
                  </div>
              </div>
              <div class=" col-sm-6">
                  <div class="iq-card">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Payment</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <div class="row col-md-12">
                                               <div class="col-md-1"></div>
                                                <div class="col-md-3" style="text-align: right;">
                                                    <p>Total Balance Amount :</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                    <p id="net_amount">-</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                <form method="POST" action="" id="sale_pay">
     <input type="hidden" name="cusname" id="cusname" value="">
     <input type="hidden" name="supname" id="supname" value="">
     <input type="text" name="remain" id="remain" value="">
     <input type="text" name="net_bill_amount" id="net" value="">
     <input type="text" name="pay_id" id="pay_id" value="">
  <div class="row col-md-12">
<div class="col-md-6">
         <input type="text" class="form-control" name="amount" value="">
         </div> 
         <div class="col-md-6">
   <input type="date" class="form-control" name="payment_date" value="<?= $date ?>">
         </div>        </div>
         <div class="row col-md-12">
         <div class="col-md-2"></div>
         <div class="col-md-6 mt-3">
           <select id="payment_mode" class="form-control" name="payment_mode">
        <option disabled>Select Payment Mode</option>
        <option value="NEFT">NEFT</option>
        <option value="Cash">Cash</option>
        <option value="Online">Online</option>
        <option value="DD">DD</option>
       </select> 
</div>
         </div>
         <div class="row col-md-12">
            <div class="col-md-1"></div>
      <div class="col-md-10"> 
         <p class="mt-3">
  If you need discount, click here&nbsp;&nbsp;<a class="btn btn-primary mt-3" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Discount
  </a>
  </p>
      </div>
  </div>
<div class="collapse" id="collapseExample">
  <div class="card card-body">
 <div class="row col-md-12">
     <div class="col-md-6">
        <select id="discount_type" class="form-control" name="discount_type">
        <option disabled selected>Select Discount Type

        </option>
        <option value="Percentage">Percentage</option>
        <option value="Cash">Cash</option>
       </select></div>
       <div class="col-md-6"> 
       <input type="text"  class="form-control" name="discount" value="">
       </div>
       </div>
 </div>
</div>
<div class="row col-md-12" style="text-align: center;">
<div class="col-md-2"></div>
<div class="col-md-3 mt-4">
     <input type="submit" class="btn btn-success" style="position:relative;left:50px"  name="add_payment_sale" value="Payment">
</div>
</div>
     </form>
                                                </div>

                    </div>
                   </div>
                   <div class="col-md-12">
                   <div class="iq-card"  id="re">
                      <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Revoke</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                     <form method="POST" action="" id="">
                                                <div class="row col-md-12">
                                                    <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="Enter your amount here" name="bal">
                            <input type="hidden" class="form-control" placeholder="" value="" name="supplier_n" id="supplier_n">
                        </div>
                            <div class="col-md-4">
                                  <input type="submit" class="btn btn-danger" name="revoke" value="Revoke">
                            </div>
                    </div>
                    </form>
                           
                             </div>
                  </div>
              </div>
              <div class="container-fluid">
                   </div>
          </div>
        </div>
    </div></div>
    
<?php
    require "footer.php";
?>
<script>
   $(document).ready(function(){
    
    $("#invoice").change(function(){
        $("#net_amount").text("");

        var invoice=$(this).find(":selected").val();
   if(invoice=="Patti Invoice"){
    $("#supplier").show();
    $("#supplier_name").show();
    $("#customer").hide();

 }
   else if(invoice=="Sales Invoice"){
    $("#customer").show();
    $("#customer_name").show();
    $("#supplier").hide();
   }
    });
    $("#re").hide();
    
    $("#supplier").hide();
   // $("#supplier_name").text("Choose Supplier");
    $("#customer").hide();
       $("#list").hide();
       $("#cuslist").hide();
    $("#pay").hide();
    $("#sale_pay").hide();

    $("#supplier").change(function(){
        $("#net_amount").text("");
        $("#cuslist").hide();
          
    
    var supplier=$(this).find(":selected").val();
    var act="pay_patti";
   // console.log(supplier);
    var datastring="sup_name="+supplier;
    $("#re").show();
      $.ajax({
        url:'forms/ajax_request.php?action=patti_report',
dataType:'json',
data:datastring,
success:function(empdata){
if(empdata){
             $("#list").show();
             $("#pay").show();
             $("#sale_pay").show();
         $('#list_body').html("");
        var patti_id=empdata[0].patti_id;
        var supname=empdata[0].supplier_name;
         if(empdata[0].supplier_name){
            $('#nodata').text("");
  
             $("#sup_name").text(empdata[0].supplier_name);
             $("#supplier_n").val(empdata[0].supplier_name);
             $("#supname").val(empdata[0].supplier_name);
             $("#sup_mobile").text(empdata[0].mobile_number);
             $("#sup_address").text(empdata[0].supplier_address);
             $("#supplierid").val(empdata[0].supplier_id);
             $("#pay_id").val(empdata[0].patti_id);
            // $('#list > tbody').append('<tr><td>'+empdata[i].patti_id+'</td></tr>');
            var i=0;
            var sum=0;
            s=0;f=0;
            if(empdata.length>0){
    for(i=0;i<empdata.length;i++){
        sum += parseFloat(empdata[i].bill_amount);
       // console.log(empdata[i].bill_amount <= empdata[0]['pay']['amount']);
                     
        $('#list_body').append('<tr><td>'+empdata[i].patti_id+'</td><td>'+empdata[i].patti_date+'</td><td>'+empdata[i].bill_amount+'</td>');
       if(i==0 && empdata[0]['pay']!=null){
    //    var val=empdata[i].bill_amount-(empdata[0]['pay']['net_bill']-empdata[0]['pay']['balance']);
  
     if(empdata[i].bill_amount == empdata[0]['pay']['given']){
        $.ajax({
                    url:'forms/ajax_request.php?action=update_reports',
dataType:'json',
type:'POST',
data:{
    supname:supname,
},
success: function (data) {

}
});
    }
      if(empdata[i].bill_amount >= empdata[0]['pay']['given']){
        var bal=empdata[i].bill_amount - empdata[0]['pay']['given'];
            if(bal < 0){
          //      console.log(bal);
             //   var am=Math.abs(bal);
                $('#list_body td:last').after('<td>'+empdata[i].bill_amount +'</td>');
   
//                 $.ajax({
//     url:'forms/ajax_request.php?action=update_report',
// dataType:'json',
// type:'POST',
// data:{
// patti_id:patti_id,
// },
// success: function (data) {
//    console.log("success");
// }
// });
        }
        else if(bal > 0){
            console.log(bal);
            $('#list_body td:last').after('<td>'+bal+'</td>');
            
        }
    if(bal==0){
            $.ajax({
    url:'forms/ajax_request.php?action=update_report',
dataType:'json',
type:'POST',
data:{
patti_id:patti_id,
},
success: function (data) {
   console.log("success");
}
});

        }
        
        else{
          //  $('#list_body td:last').after('<td>'+empdata[i].bill_amount+'</td>');
            $.ajax({
    url:'forms/ajax_request.php?action=update_reports',
dataType:'json',
type:'POST',
data:{
patti_id:patti_id,
},
success: function (data) {
   console.log("success");
}
});
        }
//      else{
//  //console.log(bal);
// $.ajax({
//     url:'forms/ajax_request.php?action=update_report',
// dataType:'json',
// type:'POST',
// data:{
// patti_id:patti_id,
// },
// success: function (data) {
//    console.log("success");
// }
// });
//        } 
    }

        
        else if(empdata[i].bill_amount <= empdata[0]['pay']['given']){
            var bal1=empdata[i].bill_amount - empdata[0]['pay']['given'];
        //  console.log(bal1+'hello');
            if(bal1 < 0){
                $("#remain").val(bal1);
               var remain= $("#remain").val();
              // alert(remain);
                $.ajax({
                    url:'forms/ajax_request.php?action=update_remain',
dataType:'json',
type:'POST',
data:{
    supname:supname,
    remain:remain
},
success: function (data) {

    if(remain<0)
{
    $.ajax({
    url:'forms/ajax_request.php?action=update_report',
dataType:'json',
type:'POST',
data:{
patti_id:patti_id,
},
success: function (data) {
   console.log("success");
}
});
}
}
});

var amt=empdata[i].bill_amount-Math.abs(bal1);
     $('#list_body td:last').after('<td>'+amt+'</td>');
      }
        else if(bal1 > 0){
         //   console.log(bal1);
            $('#list_body td:last').after('<td>'+Math.abs(bal1)+'</td>');
        }
        
        else{
            $('#list_body td:last').after('<td>'+empdata[i].bill_amount+'</td>');
        }
    }
    
//         else{
//        //     $('#list_body td:last').after('<td>'+0+'</td>');
//             $.ajax({
//     url:'forms/ajax_request.php?action=update_report',
// dataType:'json',
// type:'POST',
// data:{
// patti_id:patti_id,
// },
// success: function (data) {
//     console.log("Suceess");
// }
// });
//       }
  //   console.log(f);
    }
   else if(empdata[0]['pay']==null){
        $('#list_body td:last').after('<td>'+empdata[i].bill_amount+'</td>');
      }
        else if(i>0 && empdata[0]['pay']!=null){
            var remain =  empdata[0]['pay']['given'] - empdata[i-1].bill_amount;
          //  if(remain > 0){
        //     var bal=empdata[i].bill_amount - remain;
        //         $('#list_body td:last').after('<td>'+Math.abs(bal)+'</td>');
        //     }
            if(remain > 0){
                var remaining = empdata[i].bill_amount - remain;
           //     console.log(remaining);
        //   console.log(remaining);
             if(remaining < 0) {
                   $('#list_body td:last').after('<td>'+empdata[i].bill_amount+'</td>'); 
                }
             else{ 
                $('#list_body td:last').after('<td>'+remaining+'</td>');
             }
            }
            else if(remain < 0){
                $('#list_body td:last').after('<td>'+Math.abs(empdata[i].bill_amount)+'</td>');
            }
            else{
           if(remain==0){
                $('#list_body td:last').after('<td>'+0+'</td>');
           }else{
            $('#list_body td:last').after('<td>'+empdata[i].bill_amount+'</td>'); 
           }
            
        }
        //    else{
        //       var remain=Math.abs(bal);
        //     var remaining= remain - empdata[i].bill_amount;
        //         $('#list_body td:last').after('<td>'+Math.abs(remaining)+'</td>');
        //     }
         }
        else{
            $('#list_body td:last').after('<td>'+0+'</td>');
            
        }
          $('#list_body td:last').after('</tr>');
    }
}
$('#list_body').append('<tr><td></td><td>Total Amount</td><td>'+sum+'</td><td></td></tr>');
if(empdata[0]['pay']==null){
    $("#net").val(sum);
    var net=sum;
    $("#net_amount").text(net);
  }
else{
    $("#net").val(empdata[0]['pay']['balance']);
    net=empdata[0]['pay']['balance'];
    $("#net_amount").text(net);
 }
    //       $('#list_body').append('<tr><td></td><td></td><td></td><td></td><td></td><td>Net Patti</td><td>'+net+'</td></tr>');
}
else{
    $('#nodata').text("No data Found");
    $('#container-fluid').html();
    $('table').hide();
    $('form').hide();
}}

}
    })
});

$("#customer").change(function(){
    var customer=$(this).find(":selected").val();
    var act="pay_sale";
   // console.log(supplier);
   $("#list").hide();
   $("#re").show();
    
    var datastring="cus_name="+customer;
    $.ajax({
        url:'forms/ajax_request.php?action=customer_report',
dataType:'json',
data:datastring,
success:function(cusdata){
if(cusdata){
             $("#cuslist").show();
             $("#sale_pay").show();
             $("#pay").hide();
         $('#cuslist_body').html("");
         if(cusdata[0].customer_name){
            $('#nodatas').text("");
  
             $("#cus_name").text(cusdata[0].customer_name);
             $("#cusname").val(cusdata[0].customer_name);
             $("#cus_mobile").text(cusdata[0].mobile_number);
             $("#cus_address").text(cusdata[0].customer_address);
             $("#customerid").val(cusdata[0].customer_id);
            var i=0;
            var sum=0;
            if(cusdata.length>0){
    for(i=0;i<cusdata.length;i++){
        sum += parseFloat(cusdata[i].bill_amount);
                    
        $('#cuslist_body').append('<tr><td>'+cusdata[i].customer_id+'</td><td>'+cusdata[i].date+'</td><td>'+cusdata[i].bill_amount+'</td>');
        if(i==0 && cusdata[0]['pay']!=null){
        var val=cusdata[i].bill_amount-(cusdata[0]['pay']['net_bill']-cusdata[0]['pay']['balance']);
        if(val<0 || val==0){
        $('#cuslist_body td:last').after('<td>'+0+'</td>');
        }
        else{
            $('#cuslist_body td:last').after('<td>'+val+'</td>');
            
        }
    }
   else if(cusdata[0]['pay']==null){
        $('#cuslist_body td:last').after('<td>'+cusdata[i].bill_amount+'</td>');
      }
        else if(i>0 && cusdata[0]['pay']!=null){
            var dif=cusdata[0]['pay']['net_bill']-cusdata[0]['pay']['balance'];
         //console.log(cusdata[i-1].bill_amount>=dif);
             
            if(cusdata[i-1].bill_amount>=dif)
            {
                $('#cuslist_body td:last').after('<td>'+cusdata[i].bill_amount+'</td>');
         }
    else if(cusdata[i-1].bill_amount<=dif)
          {
            $('#cuslist_body td:last').after('<td>'+cusdata[0]['pay']['balance']+'</td>');
       }
     else{
            $('#cuslist_body td:last').after('<td>'+0+'</td>');
        }
}  
        else{
            $('#cuslist_body td:last').after('<td>'+0+'</td>');
            
        }
         $('#cuslist_body td:last').after('</tr>');
    }
}
$('#cuslist_body').append('<tr><td></td><td>Total Amount</td><td>'+sum+'</td><td></td></tr>');
if(cusdata[0]['pay']==null){
    $("#net").val(sum);
    var net=sum;
    $("#net_amount").text(net);
  }
else{
    $("#net").val(cusdata[0]['pay']['balance']);
    net=cusdata[0]['pay']['balance'];
    $("#net_amount").text(net);
 }
    //       $('#list_body').append('<tr><td></td><td></td><td></td><td></td><td></td><td>Net Patti</td><td>'+net+'</td></tr>');
}
else{
    $('#nodatas').text("No data Found");
    $('#container-fluid').html();
    $('table').hide();
    $('form').hide();
}}

}
    })
})

    });


    
</script>

<?php

if(isset($_POST["revoke"])) {
    $supname = $_POST["supplier_n"];
    $bal = $_POST["bal"];
    
$sqlsup="select * from sar_patti_payment where supplier_id='$supname'";
$exesqlsup=mysqli_query($con,$sqlsup);
$row=mysqli_fetch_assoc($exesqlsup);
$remain=$row['balance'];

$inc=abs($remain+$bal);
if($remain==0){
    $sql="update sar_patti_payment set balance=$inc,amount=$bal where supplier_id='$supname'";
    $exesql=mysqli_query($con,$sql);

    $sql="update sar_patti set is_active=1 supplier_name='$supname'";
    $exesql=mysqli_query($con,$sql);
}
else{
    $sql="update sar_patti_payment set balance=$inc,amount=$bal where supplier_id='$supname'";
    $exesql=mysqli_query($con,$sql);

}
}
//   else if(isset($_POST["add_payment"])) {
//     $payment_date = $_POST['payment_date'];
//     $amount = $_POST["amount"];
//     $payment_mode = $_POST["payment_mode"];
//     $supname = $_POST["supname"];
//     $discount_type = $_POST["discount_type"];
//     $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;
//     $net_bill_amount = isset($_POST["net_bill_amount"]) ? $_POST["net_bill_amount"] : 0;
//     if($discount_type=="Percentage"){
//         $discount=$discount/100;
//         $net_bill_amount=($net_bill_amount)*($discount);
//     }
//     else if($discount_type=="Cash"){
//         $net_bill_amount=$net_bill_amount-$discount;
//     }
//     // $select_qry5 = "SELECT net_bill_amount FROM sar_patti WHERE patti_id='$popup_patti_id' GROUP BY patti_id";

//     // $sel_sql5 = $connect->prepare($select_qry5);
//     // $sel_sql5->execute();
//     // $sel_row5 = $sel_sql5->fetch(PDO::FETCH_ASSOC);
//    // $select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='$supname' AND is_revoked is NULL GROUP BY supplier_id";
    
   
//     $balance = $net_bill_amount - $amount;
// //print_r($balance.$amount.$payment_mode);die();
// $select_qry6 ="select * from sar_patti_payment where supplier_id='$supname'";
// $exesel=mysqli_query($con,$select_qry6);
// $no=mysqli_num_rows($exesel);
// $exes=mysqli_fetch_assoc($exesel);
// //print_r($exes['balance']);die();
// //print_r($exe);die();
//     if($no<=0){
    
//     if ($balance >= 0) {
//         $insert = "INSERT INTO `sar_patti_payment`
//               SET amount='$amount',
//               payment_date='$payment_date',
//               payment_mode='$payment_mode',
//               supplier_id='$supname',
//               balance='$balance'";

//         $sql_1 = $connect->prepare($insert);
//         $sql_1->execute();

//   } 
// }
//   else{
//    // print_r($exes['balance']);die();
//    if($balance == 0){
//     $usql="update sar_patti set is_active=0 where supplier_name='$supname'";
//     $esql=mysqli_query($con,$usql);
//     } 
//    if(isset($discount_type)){
//     $sql="update sar_patti_payment set balance='$balance',payment_mode='$payment_mode',discount_type='$discount_type',discount='$discount' where supplier_id='$supname'";
//     $exe=mysqli_query($con,$sql);
//     }
//   else{
//     $sql="update sar_patti_payment set balance='$balance',payment_mode='$payment_mode',amount='$amount' where supplier_id='$supname'";
//     $exe=mysqli_query($con,$sql);
//  }

//  }
// }

else if(isset($_POST["add_payment_sale"])) {
    $cusname = $_POST["cusname"];
    $supname = $_POST["supname"];
   if($cusname!=""){
    $payment_date = $_POST['payment_date'];
    $amount = $_POST["amount"];
    $payment_mode = $_POST["payment_mode"];
    $discount_type = $_POST["discount_type"];
    $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;
    $net_bill_amount = isset($_POST["net_bill_amount"]) ? $_POST["net_bill_amount"] : 0;
    if($discount_type=="Percentage"){
        $discount=$discount/100;
        $net_bill_amount=($net_bill_amount)*($discount);
    }
    else if($discount_type=="Cash"){
        $net_bill_amount=$net_bill_amount-$discount;
    }    
   
    $balance = $net_bill_amount - $amount;
//print_r($balance.$amount.$payment_mode);die();
$select_qry6 ="select * from sar_sales_payment where customer_name='$cusname'";
$exesel=mysqli_query($con,$select_qry6);
$no=mysqli_num_rows($exesel);
$exes=mysqli_fetch_assoc($exesel);
$Last_id = $exes["id"] + 1;
$patti_id = "PAY_" . date("Ym") . "0" . $Last_id;
// print_r($exes['balance']);die();
// print_r($exe);die();
    if($no<=0){
    
    if ($balance >= 0) {
        $insert = "INSERT INTO `sar_sales_payment`
              SET amount='$amount',
              payment_id='$patti_id',
              payment_date='$payment_date',
              payment_mode='$payment_mode',
              customer_name='$cusname',
              balance='$balance',
              net_bill='$net_bill_amount'";

        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();

  } 
}
  else{
 //print_r($exes['balance']);die();
   if($balance == 0){
    $usql="update sar_sales_invoice set is_active=0 where customer_name='$cusname'";
    $esql=mysqli_query($con,$usql);
    } 
   if(isset($discount_type)){
    $sql="update sar_sales_payment set balance='$balance',payment_mode='$payment_mode',discount_type='$discount_type',discount='$discount' where customer_name='$cusname'";
    $exe=mysqli_query($con,$sql);
    }
  else{
    $sql="update sar_sales_payment set balance='$balance',payment_mode='$payment_mode',amount='$amount' where customer_name='$cusname'";
  // print_r($sql);die();
    $exe=mysqli_query($con,$sql);
 }

 }
}
else if($supname!=""){
    $payment_date = $_POST['payment_date'];
    $amount = $_POST["amount"];
    $payment_mode = $_POST["payment_mode"];
    $supname = $_POST["supname"];
    $discount_type = $_POST["discount_type"];
    $discount = isset($_POST["discount"]) ? $_POST["discount"] : 0;
    $net_bill_amount = isset($_POST["net_bill_amount"]) ? $_POST["net_bill_amount"] : 0;
    if($discount_type=="Percentage"){
        $discount=$discount/100;
        $net_bill_amount=($net_bill_amount)*($discount);
    }
    else if($discount_type=="Cash"){
        $net_bill_amount=$net_bill_amount-$discount;
    }
    // $select_qry5 = "SELECT net_bill_amount FROM sar_patti WHERE patti_id='$popup_patti_id' GROUP BY patti_id";

    // $sel_sql5 = $connect->prepare($select_qry5);
    // $sel_sql5->execute();
    // $sel_row5 = $sel_sql5->fetch(PDO::FETCH_ASSOC);
   // $select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='$supname' AND is_revoked is NULL GROUP BY supplier_id";
    
   
    $balance = $net_bill_amount - $amount;
   // print_r($balance);die();
    //print_r($balance.$amount.$payment_mode);die();
$select_qry6 ="select * from sar_patti_payment where supplier_id='$supname'";
$exesel=mysqli_query($con,$select_qry6);
$no=mysqli_num_rows($exesel);
$exes=mysqli_fetch_assoc($exesel);
$price=$exes['net_bill'];
$Last_id = $exes["id"] + 1;
$patti_id = "PAY_" . date("Ym") . "0" . $Last_id;
$given=$exes['given']+$amount;	

//print_r($exes['balance']);die();
//print_r($exe);die();
    if($no<=0){
    
    if ($balance >= 0) {
        $insert = "INSERT INTO `sar_patti_payment`
              SET amount='$amount',
              payment_id='$patti_id',
              payment_date='$payment_date',
              payment_mode='$payment_mode',
              supplier_id='$supname',
              balance='$balance',
              given='$given',
              net_bill='$net_bill_amount'";

        $sql_1 = $connect->prepare($insert);
        $sql_1->execute();

  } 
}
  else{
   // print_r($exes['balance']);die();
   if($balance == 0){
    $usql="update sar_patti set is_active=0 where supplier_name='$supname'";
    $esql=mysqli_query($con,$usql);
    } 
   if(isset($discount_type)){
    $given=$exes['given']+$amount;
    $sql="update sar_patti_payment set balance='$balance',payment_mode='$payment_mode',discount_type='$discount_type',discount='$discount',given='$given' where supplier_id='$supname'";
    $exe=mysqli_query($con,$sql);
    }
  else{
    $given=$exes['given']+$amount;
    $sql="update sar_patti_payment set balance='$balance',payment_mode='$payment_mode',amount='$amount',given='$given' where supplier_id='$supname'";
    $exe=mysqli_query($con,$sql);
 }

 }
}
}
  
?>