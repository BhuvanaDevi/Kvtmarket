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
<div><h2>Daywise Report</h2></div>
               <div class="row">

                  

    <div class="col" style="margin-bottom:20px">

      <input type="date" value="<?= $date ?>" name="from" id="from" class="form-control">

    </div>

    

    <div class="col">

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

                        <th>Date</th>

                        <th>Spent</th>

                        <th>Revenue</th>

                        <th>P/L</th>

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

                "url": "forms/ajax_request.php?action=view_daywise_report",

                "type": "POST",

                "data": {
                    from: $("#from").val(),
                    to: $("#to").val()
                }

            },

            "columns": [

                { "data": "dateOnly" },

                { "data": "sum_debit" },

                { "data": "sum_credit" },

                { "data": "profit" }

              

            ],

             "order": [[ 1, 'asc' ]]

             

        });

        $("#submit").on("click",function(){

                var from=$("#from").val();

                var to=$("#to").val();

                if(from!="" && to!=""){

                    table.ajax.url("forms/ajax_request.php?action=view_daywise_report&from="+from+'&to='+to).load();

                    table.ajax.reload();

                } else {

                    table.ajax.url("forms/ajax_request.php?action=view_daywise_report").load();

                    table.ajax.reload();

                }

        });

        $("#download").on("click",function(){
            var from=$("#from").val();
            var to=$("#to").val();
            window.location="download_day_wise_report.php?from="+from+'&to='+to;
        });
    });

</script>
