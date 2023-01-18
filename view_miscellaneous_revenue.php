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
if(isset($_REQUEST['revenue_no'])!=""){
    $revenue_no=$_REQUEST["revenue_no"];
} else {
    $revenue_no="";
}
if($req=="delete")
{
    $delete="DELETE FROM sar_miscellaneous_revenue WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    
    
    $delete_fin_qry="DELETE FROM financial_transactions WHERE misc_id='$revenue_no'";
    $delete_fin_sql= $connect->prepare($delete_fin_qry);
    $delete_fin_sql->execute();
    header("location:view_miscellaneous_revenue.php");
    
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
<div><h2>Miscellaneous Revenue</h2></div>
               <div class="row">

                  

    <div class="col">

      <input type="date" value="<?= $date ?>" name="from" id="from" class="form-control">

    </div>

    <div class="col" style="margin-bottom:20px">

       <input type="date" value="<?= $date ?>" name="to" id="to" class="form-control">

    </div>

    <div class="col">

       <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Display">

    </div>

     <div class="col">

       <button type="button" id="download" name="download" class="btn btn-danger">Download</button>

    </div>

<div class="col-lg-12">

                        <div class="iq-edit-list-data">

                            <div class="tab-content">

                                <div class="tab-pane fade active show" id="personal-information" role="tabpanel">

                                   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                <thead>

                    <tr>

                        <th>SI No</th>
                        <th>Revenue No</th>
                        <th>Date</th>
                        <!--<th>Category</th>-->
                        <th>Particulars</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Remarks</th>
                        <th>Updated by</th>
                        <th>Action</th>

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

                "url": "forms/ajax_request.php?action=view_miscellaneous_revenue",

                "type": "POST",

            },

            "columns": [
                    { "data": "rowIndex", "orderable" : false },
                    { "data": "date", "orderable" : false },

                    { "data": "revenue_no", "orderable" : false },
                    // { "data": "category", "orderable" : false },
                    { "data": "particulars", "orderable" : false },

                    { "data": "amount", "orderable" : false },

                    { "data": "mode", "orderable" : false },
                    { "data": "remarks", "orderable" : false },
                    { "data": "updated_by", "orderable" : false },
                    { "data": "id" }

            ],

            columnDefs: [

               
                {

                    targets: 0,

                    render: function(data, type, row) {
                        return row.rowIndex;

                    }

                },
                {

                    targets: 1,

                    render: function(data, type, row) {

                        return row.revenue_no;

                    }

                },

                {

                    targets: 2,

                    render: function(data, type, row) {

                        return row.date;

                    }

                },
                {

                    targets: 3,

                    render: function(data, type, row) {
                        return row.particulars;
                    }

                },

                {

                    targets: 4,

                    render: function(data, type, row) {
                        return row.amount;    
                    }

                },
                {

                    targets: 5,

                    render: function(data, type, row) {
                        return row.mode;
                    }

                },
                {

                    targets: 6,

                    render: function(data, type, row) {
                        return row.remarks;
                    }

                },
                {

                    targets: 7,

                    render: function(data, type, row) {
                        return row.updated_by;
                    }

                },
                {
                    targets: 8,
                    render: function(data, type, row) {
                        return '<a href="view_miscellaneous_revenue.php?req=delete&id='+row.id+'&revenue_no='+row.revenue_no+'" onclick="return checkDelete()">Delete</a>';
                    }
                }
                
             ],

             "order": [[ 1, 'asc' ]]

             

        });

       

         $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_miscellaneous_revenue&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_miscellaneous_revenue").load();
                table.ajax.reload();
            }
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_miscellaneous_revenue.php?from="+from+'&to='+to;
        });



    });

</script>
<script>
function checkDelete(){
    return confirm('Are you sure you want to delete?');
}
</script>