<?php require "header.php";
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

$supplier_qry="SELECT * FROM `sar_user` WHERE id=:id ";
$supplier_sql= $connect->prepare($supplier_qry);
$supplier_sql->execute(array(':id' => $id));
$supplier_row = $supplier_sql->fetch(PDO::FETCH_ASSOC);

if($req=="enabled")
{
    $delete="UPDATE `sar_user` SET is_active=0 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:view_user.php");
}

if($req=="disabled")
{
    $delete="UPDATE `sar_user` SET is_active=1 WHERE id=:id";
    $delete_sql= $connect->prepare($delete);
    $delete_sql->execute(array(':id' => $id));
    header("location:view_user.php");
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

         <div class="container">
             <h2>View User</h2>
  <div class="row justify-content-md-center">
    
  </div>
  <div class="row">
    <div class="col">
               <b><label><input type="radio" name="view_user_sts" class="view_user_sts" value="1" />Active</label></b>
    </div>
    <div class="col-md-auto">
      
    </div>
    <div class="col col-lg-2">
    <b><label><input type="radio" name="view_user_sts" class="view_user_sts" value="0" />InActive</label></b>
    </div>
  </div>

<div class="col-lg-12">

                        <div class="iq-edit-list-data">

                            <div class="tab-content">

                                <div class="tab-pane fade active show" id="personal-information" role="tabpanel">

                                       <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                <thead>

                    <tr>
                        <th>SI No</th>
                        <th>User Name</th>
                        <th>Password</th>
                        <th>Role</th>
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

    </div>

     

<?php require "footer.php";

?>
   
<script>
// $.fn.dataTableExt.sErrMode = 'throw';
    $(document).ready(function(){
       var table=$('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "forms/ajax_request.php?action=view_user&req=enabled",
                "type": "POST"
            },
            "columns": [
                { "data": "rowIndex", "orderable" : false },
                { "data": "user_name" },
                { "data": "password" },
                { "data": "role" },
                
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
                        return row.user_name;
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return row.password;
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return row.role;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        if(row.is_active==1){
                            var htm ='';
                        
                           var htm1 = '<a href="view_user.php?req=enabled&id='+row.id+'"><button type="button" class="btn btn-danger">Deactivate</button></a>';
                        } else if(row.is_active==0){
                            var htm ='';
                            var htm1 = '<a href="view_user.php?req=disabled&id='+row.id+'" ><button type="button" class="btn btn-success">Active</button></a>';
                        }
                        return htm+htm1;
                    }
                }
             ],
              "order": [[ 1, 'asc' ]]
        });
         $(".view_user_sts").on("click",function(){
            var is_active=$(this).val();
            if(is_active==1){
                table.ajax.url("forms/ajax_request.php?action=view_user&req=enabled&is_active=1").load();
                table.ajax.reload();
            } else {
                table.ajax.url("forms/ajax_request.php?action=view_user&req=disabled&is_active=0").load();
                table.ajax.reload();
            }
        });
    
    });
</script>
