<?php
$date = date("Y-m-d");

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

if($req=="enabled")
{
    $delete="UPDATE `sar_sales_invoice` SET is_active=0 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:Generate-Sales-Invoice.php");
}

if($req=="disabled")
{
    $delete="UPDATE `sar_sales_invoice` SET is_active=1 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location::Generate-Sales-Invoice.php");
}
 ?>
 <style>
     .iq-card-body
     {
         display:flex;
         flex-direction:row;
     }
 </style>
<div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                  
    <div class="col">
      <input type="date" value="<?= $date ?>" name="from" id="from" class="form-control">
    </div>
    <div class="col">
       <input type="date" value="<?= $date ?>" name="to" id="to" class="form-control">
    </div>
    <div class="col">
       <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Display">
    </div>
     <div class="col">
       <input type="submit" class="btn btn-success" id="download" name="download" value="Download">
    </div>
<div class="col-lg-12">
                        <div class="iq-edit-list-data">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Debits</th>
                        <th>Credits</th>
                       <th>Balance</th>
                       <th>Difference</th>
                    </tr>
                </thead>
               
            </table>
                                </div>
                                <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                                    <div></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
    </div>
     
<?php require "footer.php";
?>
<script>
//$.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       
    var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_revenue_report",
                "type": "POST"
            },
            "columns": [
              
                { "data": "total_deduction" },
                { "data": "amount" },
              
            ],
            columnDefs: [
               
                {
                    targets: 0,
                    render: function(data, type, row) {
                        return row.total_deduction;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.amount;
                    }
                }
             ]
        });    
       $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_delivery_chellan&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_delivery_chellan").load();
                table.ajax.reload();
            }
        });
         $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_challan.php?from="+from+'&to='+to;
        });
         $( ".close" ).click(function() {
            $( "#close" ).modal( "hide" );
        });
    
    });
</script>
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Sales Invoice</h4>
        <button type="button" class="btn-btn danger close" id="close" data-bs-dismiss="modal"></button>
       
      </div>
      
     

      <!-- Modal body -->
      <div class="modal-body">
          <table class="table table-bordered">
           <tr>
            <td align="left" colspan="3">
                <a class="previous_call_details" style="width: 100px;height: auto;cursor:pointer;">
                    << Prev
                </a>
            </td>
            <td align="right" colspan="2">
                <a class="next_call_details" style="width: 100px;height: auto;cursor:pointer;">
                  >>  Next
                </a>
            </td>
        </tr>
        <tr>
            <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs1')" data-toggle="tab" href="#tabs1">Sales Details</a></td>
            <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs2')" data-toggle="tab" href="#tabs2">Payment</a></td>
            <!--<td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" onclick="openCity(event, 'tabs3')" data-toggle="tab" href="#tabs3">Report</a></td>-->
              <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>
               <td style="background-color:#ddd;color:#000;font-weight:bold;"><a class="tabs_click tablinks" data-toggle="tab" href="#tabs5"></a></td>
           
        </tr>
      </table>
      <div id="tabs1" class="tabcontent">
          <form method="POST">
        <table class="table table-bordered">
            <tr>
              <th>Sales Invoice NO.</th>
                <td id="sales_no" name="sales_no"></td>
            </tr>
           <tr>
              <th>Date</th>
                <td id="date" name="date"></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td id="customer_name"></td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td id="mobile_number"></td>
            </tr>
            
            <tr>
                <th>Customer Address</th>
                <td id="customer_address"></td>
            </tr>
            <tr>
                <th>Boxes Arrived</th>
                <td id="boxes_arrived"></td>
            </tr>
             <tr>
                <th>Quality</th>
                <td id="quality_name"></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td id="quantity"></td>
            </tr>
             <tr>
                <th>Price</th>
                <td id="rate"></td>
            </tr>
             <tr>
                <th>Bill Amount</th>
                <td id="bill_amount"></td>
            </tr>
             <tr>
                <th>Total</th>
                <td id="total_bill_amount"></td>
            </tr>
             
            </table>
            </form>
    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger close" id="close" data-bs-dismiss="modal">Close</button>
      </div>
  </div>
</div>
</div>
</div>