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
<div><h2>Datewise Report</h2></div>
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
                            <th>Date</th>
                            <th>Particulars</th>
                            <!--<th>Category</th>-->
                            <th>Debit</th>
                            <th>Credit</th>
                            <!--<th>Balance</th>-->
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

                "url": "forms/ajax_request.php?action=view_datewise_report",

                "type": "POST",

            },

            "columns": [
                    { "data": "rowIndex", "orderable" : false },
                    { "data": "date", "orderable" : false },

                    { "data": "description", "orderable" : false },
                    // { "data": "category", "orderable" : false },
                    { "data": "debit", "orderable" : false },

                    { "data": "credit", "orderable" : false }

                    // { "data": "balance", "orderable" : false }

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
                        return row.date;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.description;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.debit;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return row.credit;    
                    }
                }
                // {
                //     targets: 5
                //     render: function(data, type, row) {
                //         return row.balance;
                //     }
                // }
                
             ],
             "order": [[ 1, 'asc' ]]
        });

       
         $("#submit").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            if(from!="" && to!=""){
                table.ajax.url("forms/ajax_request.php?action=view_datewise_report&from="+from+'&to='+to).load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_datewise_report").load();
                table.ajax.reload();
            }
        });
        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_date_wise.php?from="+from+'&to='+to;
        });



    });

</script>

