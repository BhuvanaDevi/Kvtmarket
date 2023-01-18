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

<div id="content-page" class="content-page">
    <div>
        <h2>View Date Wise Report</h2>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="iq-card12" style="padding:0">
                <div class="iq-card-body p-0">
                    <div class="iq-edit-list">
                        <ul class="iq-edit-profile d-flex nav nav-pills">
                            <li class="col-md-4 p-0">
                                <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                    Customer
                                </a>
                            </li>
                            <li class="col-md-4 p-0">
                                <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                    Supplier
                                </a>
                            </li>
                            <li class="col-md-4 p-0">
                                <a class="nav-link" data-toggle="pill" href="#all">
                                    All
                                </a>
                            </li>
                            <!-- <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#add_delete">
                               Add & Delete Trays
                            </a>
                         </li>
                         <li class="col-md-4 p-0">
                            <a class="nav-link" data-toggle="pill" href="#all">
                               Summary
                            </a>
                         </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="margin-top:20px">
            <div class="iq-edit-list-data">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                        <center>
                            <div class="row">
                                <div class="col" style="margin-bottom:20px;">

                                    <input type="date" value="<?= $date ?>" id="from" name="from" class="form-control">
                                </div>
                                <div class="col">

                                    <input type="date" value="<?= $date ?>" id="to" name="to" class="form-control">
                                </div>
                                <div class="col">
                                    <select class="form-control" id="group" name="group" style="width:210px;">
                                        <option value="">--Choose Group Name--</option>
                                        <?php
                                            $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                            	           }
                            	           ?>

                                    </select>
                                </div>
                                <div class="col" id="custom">
                                    <select class="form-control" id="customer" name="customer">
                                        <option value="">Choose Customer Name </option>

                                    </select>

                                </div>
                                <div class="col">

                                    <button type="button" id="submit" name="submit"
                                        class="btn btn-primary">Display</button>
                                    <button type="button" name="download" id="download"
                                        class="btn btn-success">Download</button>
                                </div>
                                <br>
                            </div>

                        </center>
                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>SI No</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                        <center>

                            <div class="row">
                                <div class="col">

                                    <input type="date" value="<?= $date ?>" id="from_supplier" name="from_supplier"
                                        class="form-control">
                                </div>


                                <div class="col">

                                    <input type="date" value="<?= $date ?>" id="to_supplier" name="to_supplier"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <select class="form-control" id="grpname" name="grpname" style="width:210px;">
                                        <option value="">--Choose Group Name--</option>
                                        <?php
                                            $sel_qry = "SELECT distinct group_name from `sar_supplier` order by group_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["group_name"].'</option>';
                            	           }
                            	           ?>

                                    </select>
                                </div>
                                <div class="col" id="custom">
                                    <select class="form-control" id="supplier" name="supplier">
                                        <option value="">Choose Supplier Name </option>
                                    </select>

                                </div>

                                <div class="col">
                                    <button type="button" id="submit_supplier" name="submit_supplier"
                                        class="btn btn-primary">Display</button>
                                    <button type="button" id="download_supplier" name="download_supplier"
                                        class="btn btn-danger">Download</button>
                                </div>
                            </div>
                        </center>
                        <table id="example2" class="table table-striped table-bordered dt-responsive nowrap"
                            style="width:100%;">

                            <thead>
                                <tr>
                                    <th>SI No</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                        </table>

                    </div>

                    <div class="tab-pane fade active" id="all" role="tabpanel">
                        <center>
                            <div class="row">
                                <div class="col" style="margin-bottom:20px;">

                                    <input type="date" value="<?= $date ?>" id="from_all" name="from_all" class="form-control">
                                </div>
                                <div class="col">

                                    <input type="date" value="<?= $date ?>" id="to_all" name="from_all" class="form-control">
                                </div>
                                <!-- <div class="col">
                                    <select class="form-control" id="group" name="group" style="width:210px;">
                                        <option value="">--Choose Group Name--</option> -->
                                        <?php
                                        //     $sel_qry = "SELECT distinct grp_cust_name from `sar_customer` order by grp_cust_name ASC ";
                                        // 	$sel_sql= $connect->prepare($sel_qry);
                            	        //     $sel_sql->execute();
                            	        //    while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	        //         echo '<option>'.$sel_row["grp_cust_name"].'</option>';
                            	        //    }
                            	           ?>

                                    <!-- </select>
                                </div> -->
                                <!-- <div class="col" id="custom">
                                    <select class="form-control" id="customer" name="customer">
                                        <option value="">Choose Customer Name </option>

                                    </select>

                                </div> -->
                                <div class="col">

                                    <button type="button" id="submit1" name="submit"
                                        class="btn btn-primary">Display</button>
                                    <button type="button" name="download" id="download1"
                                        class="btn btn-success">Download</button>
                                </div>
                                <br>
                            </div>

                        </center>
                        <table id="example3" class="table table-striped table-bordered dt-responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>SI No</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                        </table>
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

$(document).ready(function() {

    var table = $('#example').DataTable({

        "processing": true,

        "serverSide": true,

        "responsive": true,

        "ajax": {

            "url": "forms/ajax_request.php?action=view_datewise_report",

            "type": "POST",

        },

        "columns": [{
                "data": "rowIndex",
                "orderable": false
            },
            {
                "data": "date",
                "orderable": false
            },

            {
                "data": "description",
                "orderable": false
            },
            // { "data": "category", "orderable" : false },
            {
                "data": "debit",
                "orderable": false
            },

            {
                "data": "credit",
                "orderable": false
            },
            {
                "data": "balance",
                "orderable": false
            }

            // { "data": "balance", "orderable" : false }

        ],

        columnDefs: [{
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
            },
            {
                targets: 5,
                render: function(data, type, row) {
                    return row.balance;
                }
            }
            // {
            //     targets: 5
            //     render: function(data, type, row) {
            //         return row.balance;
            //     }
            // }

        ],
        "order": [
            [1, 'asc']
        ]
    });


    $("#submit").on("click", function() {
        var from = $("#from").val();
        var to = $("#to").val();
        var group = $("#group").val();
        var customer = $("#customer").val();
        if (from != "" && to != "") {
            table.ajax.url("forms/ajax_request.php?action=view_datewise_report&from=" + from + '&to=' +
                to + '&customer=' + customer + '&grp=' + group).load();
            table.ajax.reload();
        } else {
            table.ajax.url("forms/ajax_request.php?action=view_datewise_report").load();
            table.ajax.reload();
        }
    });
    $("#download").on("click", function() {
        var from = $("#from").val();
        var to = $("#to").val();
        var customer = $("#customer").val();

        window.location = "DateWiseReport.php?from=" + from + '&to=' + to + '&customer=' + customer;
    });



});

</script>

<script>
//$.fn.dataTableExt.sErrMode = 'throw';

$(document).ready(function() {

    
var table = $('#example3').DataTable({

"processing": true,

"serverSide": true,

"responsive": true,

"ajax": {

    "url": "forms/ajax_request.php?action=view_datewise_report_all",

    "type": "POST",

},

"columns": [{
        "data": "rowIndex",
        "orderable": false
    },
    {
        "data": "date",
        "orderable": false
    },

    {
        "data": "description",
        "orderable": false
    },
    // { "data": "category", "orderable" : false },
    {
        "data": "debit",
        "orderable": false
    },

    {
        "data": "credit",
        "orderable": false
    },
    {
        "data": "balance",
        "orderable": false
    }

    // { "data": "balance", "orderable" : false }

],

columnDefs: [{
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
    },
    {
        targets: 5,
        render: function(data, type, row) {
            return row.balance;
        }
    }
    // {
    //     targets: 5
    //     render: function(data, type, row) {
    //         return row.balance;
    //     }
    // }

],
"order": [
    [1, 'asc']
]
});


$("#submit1").on("click", function() {
var from = $("#from_all").val();
var to = $("#to_all").val();
// var group = $("#group").val();
// var customer = $("#customer").val();
if (from != "" && to != "") {
    table.ajax.url("forms/ajax_request.php?action=view_datewise_report_all&from=" + from + '&to=' +
        to).load();
// + '&customer=' + customer + '&grp=' + group
        table.ajax.reload();
} else {
    table.ajax.url("forms/ajax_request.php?action=view_datewise_report_all").load();
    table.ajax.reload();
}
});
$("#download1").on("click", function() {
    var from = $("#from_all").val();
var to = $("#to_all").val();
// var customer = $("#customer").val();
window.location = "DateWiseReport_all.php?from=" + from + '&to=' + to;
});



});

</script>
<script>
$(document).ready(function() {

    var table = $('#example2').DataTable({

        "processing": true,

        "serverSide": true,

        "responsive": true,

        "ajax": {

            "url": "forms/ajax_request.php?action=view_datewise_report_supplier",

            "type": "POST",

        },

        "columns": [{
                "data": "rowIndex",
                "orderable": false
            },
            {
                "data": "date",
                "orderable": false
            },

            {
                "data": "description",
                "orderable": false
            },
            // { "data": "category", "orderable" : false },
            {
                "data": "debit",
                "orderable": false
            },

            {
                "data": "credit",
                "orderable": false
            },
            {
                "data": "balance",
                "orderable": false
            }

            // { "data": "balance", "orderable" : false }

        ],

        columnDefs: [{
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
            },
            {
                targets: 5,
                render: function(data, type, row) {
                    return row.balance;
                }
            }
            // {
            //     targets: 5
            //     render: function(data, type, row) {
            //         return row.balance;
            //     }
            // }

        ],
        "order": [
            [1, 'asc']
        ]
    });


    $("#submit_supplier").on("click", function() {
        var from = $("#from_supplier").val();
        var to = $("#to_supplier").val();
        var supplier = $("#supplier").val();
        //  alert(supplier);
        if (from != "" && to != "") {
            table.ajax.url("forms/ajax_request.php?action=view_datewise_report_supplier&from=" + from +
                '&to=' + to + '&supplier=' + supplier).load();
            table.ajax.reload();
        } else {
            table.ajax.url("forms/ajax_request.php?action=view_datewise_report_supplier").load();
            table.ajax.reload();
        }
    });
    $("#download_supplier").on("click", function() {
        var from = $("#from_supplier").val();
        var to = $("#to_supplier").val();
        var supplier = $("#supplier").val();

        window.location = "DatewiseReport_Supplier.php?from=" + from + '&to=' + to + '&supplier=' +
            supplier;
    });



});
</script>
<script>
$("#group").on("change", function() {
    var grp = $(this).val();
    // alert(grp);
    $.ajax({
        type: "POST",
        url: "forms/ajax_request.php",
        data: {
            "action": "fetchsup",
            "grp": grp
        },
        dataType: "json",
        success: function(result) {
            var len = result.length;
            // alert(result.length);
            $("#customer").empty();
            $("#customer").append('<option>Choose Customer Name</option>');
            for (var i = 0; i < len; i++) {
                $("#customer").append('<option value=' + result[i].customer_no + '>' + result[i]
                    .customer_name + '</option>');
            }
            // alert(result.contact_person);
        }
    })
});
$("#grpname").on("change", function() {
    var grp = $(this).val();
    // alert(grp);
    $.ajax({
        type: "POST",
        url: "forms/ajax_request.php",
        data: {
            "action": "fetchgrp",
            "grp": grp
        },
        dataType: "json",
        success: function(result) {
            var len = result.length;
            // alert(result.length);
            $("#supplier").empty();
            $("#supplier").append('<option>Search Supplier Name</option>');
            for (var i = 0; i < len; i++) {
                $("#supplier").append('<option value=' + result[i].supplier_no + '>' + result[i]
                    .contact_person + '</option>');
            }
            // alert(result.contact_person);
        }
    })
});
</script>