<?php
include("../include/config.php");
$action = $_REQUEST["action"];

if($action=="view_customer_table")
{
    $req=$_REQUEST["req"];
	$from=$_REQUEST["from"];
	$to=$_REQUEST["to"];
    //$is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            customer_name like '%".$searchValue."%'
            OR customer_id like '%".$searchValue."%'
            OR date like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `customer_table`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
    if($from!="" && $to!="")
    {
     	$sel_qry .=" where (date >='$from' AND date<='$to')";   
	}
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `customer_table` ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    if($from!="" && $to!="")
    {
     	$data_sql .=" where (date >='$from' AND date<='$to')";   
	}
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		$sel_qry2 = "SELECT  *,sum(chitamt) as total_amount from chit where chitid = '".$data_row["customer_id"]."' group by chitid ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    // $tot+=$data_row["total_bill_amount"];
	    $data[]=array(
	        "total_amount"=>$data_row2["total_amount"],
	        "customer_name"=>$data_row["customer_name"],
	        "id"=>$data_row["id"],
	        "customer_id"=>$data_row['customer_id'],	        
	        "date"=>$data_row["date"]
	    );
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
if($action=="fetch_finance"){
	$grp=$_REQUEST['grp'];
	$sql="select * from sar_customer where grp_cust_name='$grp'";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}

//print_r($cusdata);die();
echo json_encode($cusdata);
}
if($action=="fetch_finance_settled"){
	$grp=$_REQUEST['grp'];
	$sql="select * from sar_customer where grp_cust_name='$grp'";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}

//print_r($cusdata);die();
echo json_encode($cusdata);
}
if($action=="view_supplier")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR Address like '%".$searchValue."%'
	    )";
	}
	// $sel_qry = "SELECT count(*) as allcount FROM `sar_supplier` ";
	$sel_qry = "SELECT count(*) as allcount FROM `sar_group` ";
	
    // if($req=="enabled")
    // {
    //     $sel_qry.=" WHERE is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $sel_qry.=" WHERE is_active=0 ";
    // }
    if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    $sel_qry .="GROUP BY group_name";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_group` ";
// 	if($req=="enabled")
//     {
//         $data_sql.=" WHERE is_active=1 ";
//     }
//     else if($req=="disabled")
//     {
//         $data_sql.=" WHERE is_active=0 ";
//     }
    if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
	$data_sql.="GROUP BY group_name ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	//    $data[] = $data_row;
	
	$cusnames=$data_row["group_name"];
	$count="select * from sar_supplier where group_name='$cusnames'";
	$execount=mysqli_query($con,$count);
	$cus_co=mysqli_fetch_assoc($execount);
	$no=mysqli_num_rows($execount);

if($no>0) { $n=$no; }
else { $n=0; }
	$data[]=array(
		"id"=>$data_row['id'],
		"group_name"=>$data_row["group_name"],
		"no"=>$n
	);
}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="revoke_patti_payment")
{
  if(isset($_REQUEST['id'])){
		$id=$_REQUEST["id"];
		$data_src=$_REQUEST["data_src"];
	//	$supplier_id=$_REQUEST["supplier_id"];
		
		$fetch_record = "SELECT * FROM `sar_patti_payment` WHERE id='".$id."' AND is_revoked is NULL";
		$sql_1 = $connect->prepare($fetch_record);
		$sql_1->execute();
		$data = array();
		while ($data_row = $sql_1->fetch(PDO::FETCH_ASSOC)) {
		   $data[] = $data_row;
		}
		$amount = $data[0]['amount'];
		$supplier_id = $data[0]['supplier_id'];
		if(count($data) != 0){
			$delete = "UPDATE `sar_patti_payment` SET is_revoked = 1 WHERE id='".$id."'";
			$sql_1 = $connect->prepare($delete);
			$sql_1->execute();
			
			$update = "UPDATE `sar_patti_payment` SET balance = balance + ".$amount." WHERE supplier_id='".$supplier_id."' and is_revoked is NULL and id > " . $id ;
			$sql_1 = $connect->prepare($update);
			$sql_1->execute();
			
			if($data_src=="settled"){
    			$update = "UPDATE `sar_patti` SET payment_status = 1 WHERE patti_id='".$supplier_id."' and payment_status = 0";
    			$sql_1 = $connect->prepare($update);
    			$sql_1->execute();
			}

			$fin_trans_qry = "INSERT INTO financial_transactions SET 
                             date = '".date('Y-m-d')."',
                             credit= $amount,
                             description = 'Revoke of patti payment, ID: $supplier_id',
                             patti_id = '$supplier_id',
                             payment_id = '$id'
                             ";
           $res2=mysqli_query($con,$fin_trans_qry);
			
		}
		echo json_encode($data);
	}  
}
else if($action=="revoke_sales_payment")
{
  if(isset($_REQUEST['id'])){
		$id=$_REQUEST["id"];
		$data_src=$_REQUEST["data_src"];
	//	$supplier_id=$_REQUEST["supplier_id"];
		
		$fetch_record = "SELECT * FROM `sar_sales_payment` WHERE id='".$id."' AND is_revoked is NULL";
		$sql_1 = $connect->prepare($fetch_record);
		$sql_1->execute();
		$data = array();
		while ($data_row = $sql_1->fetch(PDO::FETCH_ASSOC)) {
		   $data[] = $data_row;
		}
		$amount = $data[0]['amount'];
		
// 	     $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$data[0]['amount']' GROUP BY sales_no";
//     	$select_sql2=$connect->prepare($select_qry2);
//     	$select_sql2->execute();
//     	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
//     	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
		
// 		$amount = $amount + $total_discount_on_sales;
		
		$customer_id = $data[0]['customer_id'];
		$data[0]['data_src'] = $data_src;
		
		if(count($data) != 0){
			$delete = "UPDATE `sar_sales_payment` SET is_revoked=1 WHERE id='".$id."'";
			$sql_1 = $connect->prepare($delete);
			$sql_1->execute();
			
			$update = "UPDATE `sar_sales_payment` SET balance = balance + ".$amount." WHERE customer_id='".$customer_id."' and is_revoked is NULL and id > " . $id ;
			$sql_1 = $connect->prepare($update);
			$sql_1->execute();
			if($data_src=="settled"){
    			$update = "UPDATE `sar_sales_invoice` SET payment_status = 0,credit_type='Unsettled' WHERE sales_no='".$customer_id."' and payment_status = 1";
    			$sql_1 = $connect->prepare($update);
    			$sql_1->execute();
    			$update = "DELETE FROM `sar_cash_carry` WHERE cash_no='".$customer_id."'";
    			$sql_1 = $connect->prepare($update);
    			$sql_1->execute();
			}

			$fin_trans_qry = "INSERT INTO financial_transactions SET 
                             date = '".date('Y-m-d')."',
                             debit= $amount,
                             description = 'Revoke of sales payment, ID: $data[0][customer_name]',
                             invoice_id = '$customer_id',
                             payment_id = '$id'
                             ";
           $res2=mysqli_query($con,$fin_trans_qry);
		}
		echo json_encode($data);
	}  
}
else if($action=="view_add_item")
{
    $req=$_REQUEST["req"];
    //$is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `quality`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `quality` ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		
	   $data[] = $data_row; 
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_patti_nullify_records")
{
    $req=$_REQUEST["req"];
    //$is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti_nullify_records`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
    $sel_qry.=" GROUP BY patti_id";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_patti_nullify_records` ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    $data_sql.=" GROUP BY patti_id";
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		
	   $data[] = $data_row; 
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_patti")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	$grp=$_REQUEST["grp"];
    $dropdown=explode("_",$_REQUEST["dropdown"]);
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = "
	   (
            supplier_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR supplier_address like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = "";
	}
	
    $sel_qry = "SELECT count(*) as allcount FROM `sar_patti` ";
   
    if($req=="enabled")
    {
        $sel_qry.=" where is_active=1 AND nullify=0 ";
    }
    else if($req=="disabled")
    {
		$sel_qry.=" where is_active=0 AND nullify=1 ";
    }
   	 if($from!="" && $to!="")
    {
     $sel_qry .=" where (patti_date >='$from' AND patti_date<='$to')";   
	 if($searchQuery!=""){
		$sel_qry .= " AND ".$searchQuery;
		}
	  }
    if($dropdown[0]!="")
	{
	   $sel_qry .= "AND groupname='$grp' AND (supplier_name='$dropdown[0]' OR mobile_number='$dropdown[1]')";
	}
	$sel_qry.=" GROUP BY pat_id";
	// print_r($sel_qry);die();
  	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti`";
	    
	if($req=="enabled")
    {
        $sel_qry.="where is_active=1 AND nullify=0 ";
		}
    else if($req=="disabled")
    {
        $sel_qry.="where is_active=0 AND nullify=1 ";
	   }
	 if($from!="" && $to!="")
    {
     $sel_qry .=" where (patti_date >='$from' AND patti_date<='$to')";   
    if($searchQuery!=""){
			$sel_qry .= " AND ".$searchQuery;
			}
	}
    if($dropdown[0]!="")
	{
	   $sel_qry .= "AND groupname='$grp' AND (supplier_name='$dropdown[0]' OR mobile_number='$dropdown[1]')";
	}
	    $sel_qry.=" GROUP BY pat_id";
	//}
    // print_r($sel_qry);die();

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_patti`";
	
	if($req=="enabled")
    {
        $data_sql.=" where is_active=1 AND nullify=0 ";
		}
    else if($req=="disabled")
    {
        $data_sql.=" where is_active=0 AND nullify=1 ";
			}
	
		
		if($from!="" && $to!="")
    {
     $data_sql .=" where (patti_date >='$from' AND patti_date<='$to')";   
    	if($searchQuery!=""){
			$data_sql .= " AND ".$searchQuery;
			}   
	}
    if($dropdown[0]!="")
	{
	   $data_sql .= "AND groupname='$grp' AND (supplier_name='$dropdown[0]' OR mobile_number='$dropdown[1]')";
	}
	$data_sql.=" GROUP BY pat_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
$tot=0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   if($data_row['patti_id']!=null){
	    $select_qry3= "SELECT * FROM `trays` WHERE ids='".$data_row["patti_id"]."' and type='".$data_row["type"]."'";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	// $inward_sum=$select_row3["inhand"];
    	$small=isset($select_row3["smalltray"])?$select_row3["smalltray"]:0;
    	$big=isset($select_row3["bigtray"])?$select_row3["bigtray"]:0;
    	
		$tot+=$data_row['total_bill_amount'];
    	// $select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    // $select_sql4=$connect->prepare($select_qry4);
    	// $select_sql4->execute();
    	// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["outward_sum"];
    	$total_sum=$inward_sum;
    	
    	$select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='".$data_row["patti_id"]."' AND is_revoked is NULL GROUP BY supplier_id";
        
        $select_sql6 = $connect->prepare($select_qry6);
        $select_sql6->execute();
        $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    
	    $data[]=array(
	        "id"=>$data_row["id"],
	        "paid"=>$sel_row6["paid"],
	         "farmer_name"=>$data_row["farmer_name"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "supplier_id"=>$data_row["supplier_id"],
	        "quality_name"=>$data_row["quality_name"],
	        "patti_date"=>$data_row["patti_date"],
	        "patti_id"=>$data_row["patti_id"],
	        "pat_id"=>$data_row["pat_id"],
	        "updated_by"=>$data_row["updated_by"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row["net_bill_amount"],
	       // "net_payable"=>$data_row["net_payable"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "totalbillamount"=>$tot,
	         "is_active"=>$data_row["is_active"],
	         "small"=>$small,
			 "big"=>$big
	         
	    );
	}
}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
        // "qry1" => $sel_qry,
        // "qry2" => $sel_qry,
        // "qry3" => $data_sql
	);

	echo json_encode($response);
   
}
else if($action=="view_patti_settled")
{
    $req=$_REQUEST["req"];
    //$is_active=$_REQUEST["is_active"];
    $balance=$_REQUEST["balance"];
    $from=$_REQUEST["from_settled"];
    $to=$_REQUEST["from_settled"];
    $grp=$_REQUEST["grp"];
    $dropdown_settled=explode("_",$_REQUEST["dropdown_settled"]);
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " is_active = 0 AND 
	   (
            supplier_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR supplier_address like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = "nullify=0 and paid=1";
	}
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti` where is_active=0";
	$sel_qry .= " AND ".$searchQuery;
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]') AND groupname='$grp'";
	}
	$sel_qry.=" AND nullify=0 AND paid=1 AND is_active=0 GROUP BY pat_id";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti` where is_active=0  ";
	    $sel_qry .= " AND ".$searchQuery;
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
	 if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]')  AND groupname='$grp'";
	}   
    $sel_qry.=" AND nullify=0 AND paid=1 AND is_active=0 GROUP BY pat_id";

	// print_r($sel_qry);die();
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT *, SUM(bill_amount) AS totalbillamount, sum(net_bill_amount) as net FROM `sar_patti`  where is_active=0";
	    $data_sql .= " AND ".$searchQuery;  
	if($from!="" && $to!="")
    {
     $data_sql .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $data_sql .= " AND (supplier_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]')  AND groupname='$grp'";
	}
	$data_sql.=" AND nullify=0 AND is_active=0 AND paid=1 GROUP BY pat_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	// print_r($data_sql);die();

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	//    $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	//     $select_sql3=$connect->prepare($select_qry3);
    // 	$select_sql3->execute();
    // 	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    // 	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT * FROM `trays` WHERE ids='".$data_row["patti_id"]."' and type='".$data_row["type"]."'";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["outward_sum"];
    	// $total_sum=$outward_sum-$inward_sum;

	    $data[]=array(
	        "id"=>$data_row["id"],
	        "farmer_name"=>$data_row["farmer_name"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "supplier_id"=>$data_row["supplier_id"],
	        "quality_name"=>$data_row["quality_name"],
	        "patti_date"=>$data_row["patti_date"],
	        "patti_id"=>$data_row["patti_id"],
	        "pat_id"=>$data_row["pat_id"],
	        "updated_by"=>$data_row["updated_by"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row['net_bill_amount'],
	         "mobile_number"=>$data_row["mobile_number"],
	         "totalbillamount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "inhand"=>$select_row4["inhand"],
	         "small"=>$select_row4["smalltray"],
	         "big"=>$select_row4["bigtray"]
	         
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_revenue_report")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
	 if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    // if($req=="enabled")
    // {
    //     $sel_qry.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $sel_qry.=" is_active=0 ";
    // }
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT a_sum.material_name,opening_stock, used FROM (SELECT material_name, SUM(net_weight) AS opening_stock FROM raw_material_purchase_invoice GROUP BY material_name ) a_sum LEFT JOIN (SELECT material_name, SUM(used) AS used FROM raw_material_used GROUP BY material_name ) b_sum ON (a_sum.material_name = b_sum.material_name) ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    // if($req=="enabled")
    // {
    //     $data_sql.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $data_sql.=" is_active=0 ";
    // }
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_stocks")
{

    $req=$_REQUEST["req"];
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $is_active=$_REQUEST["is_active"];
    ////$category=$_REQUEST["category"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
    //$filter = array();
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
            OR balance like '%".$searchValue."%'
            OR date like '%".$searchValue."%'
	    )";
	   // array_push($filter, $searchQuery);
	}
	
	$sel_qry = "SELECT COUNT(*) as allcount FROM( SELECT name, category, date, (SUM(outward) - SUM(inward)) as balance FROM `trays` where category ='Customer' ";
	
	 if($from!="" && $to!="")
    {
       $sel_qry .=" AND (date >='$from' AND date<='$to')"; 
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
    if($searchValue!=''){
	    $sel_qry .= " HAVING ".$searchQuery;
	}
	$sel_qry .=" GROUP BY name ";
	$sel_qry .= " ) t";
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
    $sel_qry = "SELECT COUNT(*) as allcount FROM( SELECT name, category, date, (SUM(outward) - SUM(inward)) as balance FROM `trays` where category ='Customer' ";
	 if($from!="" && $to!="")
    {
        $sel_qry .=" AND (date >='$from' AND date<='$to')"; 
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
	if($searchValue!=''){
	    $sel_qry .= " HAVING ".$searchQuery;
	}
	$sel_qry .="  GROUP BY name ";
	$sel_qry .= " ) t";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];	

	$data_sql = "SELECT name, category, date ,(SUM(outward) - SUM(inward)) as balance FROM `trays` WHERE category='Customer'";
     if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
	
	if($searchValue!=''){
	    $data_sql .= " HAVING  ".$searchQuery;
	}
	$data_sql.="  GROUP BY name";
	$data_sql.="  ORDER BY ".$columnName." ". $columnSortOrder ." limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex = $row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row; 
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        "qry1" => $sel_qry,
        "qry2" => $sel_qry,
        "qry3" => $data_sql
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_stocks_supplier")
{

    $req=$_REQUEST["req"];
    $from=$_REQUEST["from_supplier"];
    $to=$_REQUEST["to_supplier"];
    $is_active=$_REQUEST["is_active"];
    //$category=$_REQUEST["category"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
            OR balance like '%".$searchValue."%'
	    )";
	}
	
$sel_qry = "SELECT COUNT(*) as allcount FROM( SELECT name, category, date, (SUM(outward) - SUM(inward)) as balance FROM `trays` where category ='Supplier' ";
	 if($from!="" && $to!="")
    {
       $sel_qry .=" AND (date >='$from' AND date<='$to')"; 
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
    if($searchValue!=''){
	    $sel_qry .= " HAVING ".$searchQuery;
	}
	$sel_qry .=" GROUP BY name ";
	$sel_qry .= " ) t";
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
    $sel_qry = "SELECT COUNT(*) as allcount FROM( SELECT name, category, date, (SUM(outward) - SUM(inward)) as balance FROM `trays` WHERE category ='Supplier' ";
	 if($from!="" && $to!="")
    {
        $sel_qry .=" AND (date >='$from' AND date<='$to')"; 
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
	if($searchValue!=''){
	    $sel_qry .= " HAVING ".$searchQuery;
	}
	$sel_qry .="  GROUP BY name ";
	$sel_qry .= " ) t";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];	

	$data_sql = "SELECT name, category, date ,(SUM(outward) - SUM(inward)) as balance FROM `trays` WHERE category='Supplier'";
     if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
     //array_push($filter, " (date >='$from' AND date<='$to')");  
    }
	
	if($searchValue!=''){
	    $data_sql .= " HAVING  ".$searchQuery;
	}
	$data_sql.="  GROUP BY name";
	$data_sql.="  ORDER BY ".$columnName." ". $columnSortOrder ." limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	$data = array();
	$rowIndex = $row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row; 
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
if($action=="view_user")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_user` WHERE ";
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    if($req=="enabled")
    {
        $sel_qry.=" is_active=1 ";
    }
    else if($req=="disabled")
    {
        $sel_qry.=" is_active=0 ";
    }
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_user` WHERE ";
	if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
    if($req=="enabled")
    {
        $data_sql.=" is_active=1 ";
    }
    else if($req=="disabled")
    {
        $data_sql.=" is_active=0 ";
    }
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		$balanceAmount = $balanceAmount + $data_row["credit"] - $data_row["debit"];
		$data_row["balance"] = $balanceAmount;
	   $data[] = $data_row; 
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_summary")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            contact_person like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
	 if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    // if($req=="enabled")
    // {
    //     $sel_qry.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $sel_qry.=" is_active=0 ";
    // }
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT (SELECT SUM(`total_trays`) FROM `trays` ) as `total_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ) as `supplier_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ) as `customer_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_patti`) as `patti_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_sales_invoice`) as `sales_tray`,(SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`) as `supplier_received`,(SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`)as `customer_received`,(SELECT customer_tray +
    sales_tray - customer_received)as `CUSTOMER`,(SELECT supplier_tray - patti_tray - supplier_received) as `SUPPLIER`,(SELECT (total_tray - SUPPLIER - CUSTOMER) )AS inhand_tray

";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    // if($req=="enabled")
    // {
    //     $data_sql.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $data_sql.=" is_active=0 ";
    // }
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_wastage")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND 
	   (
            created_at like '%".$searchValue."%'
            OR wastage_id like '%".$searchValue."%'
            OR quality_name like '%".$searchValue."%'
             OR updated_by like '%".$searchValue."%'
	    )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `sar_wastage`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (created_at >='$from' AND created_at<='$to')";   
    }
// print_r($sel_qry);die();
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_wastage` ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (created_at >='$from' AND created_at<='$to')";   
    }
	 
    $sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_wastage`  ";
	
	 
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (created_at >='$from' AND created_at<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
    $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    	$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_inventory")
{

    $req=$_REQUEST["req"];
	$from=$_GET["from"];
	$to=$_GET["to"];
    $customer=$_GET["customer"];
    $tray=$_GET["tray"];

	//print_r($tray);die();
    $is_active=$_REQUEST["is_active"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			date like '%".$searchValue."%'
            OR name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
			OR inward like '%".$searchValue."%'
			OR outward like '%".$searchValue."%'
			OR inhand like '%".$searchValue."%'
			OR description like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays` WHERE category='Customer' ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    if($customer!=''){
	    $sel_qry .= " AND name='$customer'";
	}
	if($tray!=''){
	    $sel_qry .= " AND type='$tray'";
	}

	//print_r($sel_qry);die();
	
	$sel_qry .="GROUP by name";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
    $sel_qry = " SELECT count(*) as allcount FROM `trays` WHERE category='Customer'";
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to') ";   
    }
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
	if($customer!=''){
	    $sel_qry .= " AND name='$customer'";
	}
	if($tray!=''){
	    $sel_qry .= " AND type='$tray'";
	}
	// $sel_qry .="order by id desc";
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];

    
    $data_sql = " SELECT * FROM `trays` WHERE category='Customer'";
  
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
	if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
	
	if($customer!=''){
	    $data_sql .= " AND name='$customer'";
	}
	if($tray!=''){
	    $data_sql .= " AND type='$tray'";
	}
	// $data_sql .=" order by id desc";
		
	//$data_sql .="group by name,date order by id desc limit 1";
	$data_sql.=" ORDER BY id DESC limit ".$row.",".$rowperpage;
	//print_r($data_sql);die();

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex = $row;
	$balanceTray = 0;
	$amt=0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
			$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
    	// $select_qry6= "SELECT * FROM `sar_sales_invoice` WHERE customer_id='".$data_row["name"]."' group by customer_id" ;
	    // $select_sql6=$connect->prepare($select_qry6);
    	// $select_sql6->execute();
    	// $select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	// $customer_name=$select_row6["customer_name"];
        
		$select_qry6= "SELECT * FROM `sar_customer` WHERE customer_no='".$data_row["name"]."'" ;
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$customer_name=$select_row6["customer_name"];

        $tray_pay=($data_row["inhand"])*100;
	
// $sqltray="select * from trays where name='$data_row[name]' and type='Big Tray'";
// $datatray= $connect->prepare($sqltray);
// $datatray->execute();
// $resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

// $sqltray1="select * from trays where name='$data_row[name]' and type='Small Tray'";
// $datatray1= $connect->prepare($sqltray1);
// $datatray1->execute();
// $resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

// 	$smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;
// 	$bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
	
	
// 	$small_ab_tray=isset($resulttray1['ab_tray'])?$resulttray1['ab_tray']:0;
// 	$big_ab_tray=isset($resulttray['ab_tray'])?$resulttray['ab_tray']:0;
	
// if($data_row['type']=="Small Tray"){
// 	$absmalltray=isset($data_row['ab_tray'])?$data_row['ab_tray']:0;
// 	if($data_row['inhand']!=0){
// 	$smalltray=$data_row['inhand'];
// 	}
// 	else{
// 		$smalltray=0;
// 	}
// }
// if($data_row['type']=="Big Tray"){
// 	$abbigtray=isset($data_row['ab_tray'])?$data_row['ab_tray']:0;
// 	if($data_row['inhand']!=0){
// 		$bigtray=$data_row['inhand'];
// 	}
// 	else{
// 		$bigtray=0;
// 	}
// }

// $small=isset($datarow['smalltray'])?$datarow['smalltray']:0;
// $big=isset($datarow['bigtray'])?$datarow['bigtray']:0;
// $absmall=isset($datarow['$absmalltray'])?$$datarow['$absmalltray']:0;
// $abbig=isset($datarow['abbigtray'])?$datarow['abbigtray']:0;	
// print_r($smalltray);die();
		// $smalltray=isset($smalltray)?$smalltray:0;
// $bigtray=isset($bigtray)?$bigtray:0;

// $absmalltray=isset($absmalltray)?$absmalltray:0;
// $abbigtray=isset($abbigtray)?$abbigtray:0;
	
// $previous_balance_qry="SELECT sum(outward) - sum(inward) as balance FROM trays WHERE name='".$data_row["name"]."' AND category='Customer' AND date<'".$data_row["date"]."' GROUP BY name ORDER BY id  DESC";
  	    // $previous_balance_sql=$connect->prepare($previous_balance_qry);
  	    // $previous_balance_sql->execute();
  	    // $previous_balance_row=$previous_balance_sql->fetch(PDO::FETCH_ASSOC);
  	    // $previous_days_balance_amount=$previous_balance_row["balance"];
  	    
  	    // $inhand_sum =$previous_days_balance_amount + $select_row6["outward"] - $select_row6["inward"];
	   //$data_row["inhand"] = $balanceTray;
	   $data[]=array(
	        "rowIndex"=>$data_row["rowIndex"],
	        "date"=>$data_row["date"],
	        "type"=>$data_row["type"],
	        "name"=>$customer_name,
	        "category"=>$data_row["category"],
	        "inward"=>abs($data_row["inward"]),
	        "outward"=>$data_row["outward"],
	        "ab_tray"=>$data_row["ab_tray"],
	        "description"=>$data_row["description"],
	        "updated_by"=>$data_row["updated_by"],
	        "inhand"=>$data_row["inhand"],
	        "tray_pay"=>$tray_pay,
	        "balance"=>$previous_days_balance_amount,
			"smalltray"=>$data_row['smalltray']." : (₹".($data_row['smalltray']*100).")",
			"abstray"=>$data_row['absmall'],
			"abbtray"=>$data_row['abbig'],
			"bigtray"=>$data_row['bigtray']." : (₹".($data_row['bigtray']*100).")"
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        "qry1" => $data_sql
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_inventory_supplier")
{

    $req=$_REQUEST["req"];
	$from=$_GET["from"];
	$to=$_GET["to"];
    $trays=$_GET["trays"];
    $supplier=$_GET["supplier"];
    $is_active=$_REQUEST["is_active"];

    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			date like '%".$searchValue."%'
            OR name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
			OR inward like '%".$searchValue."%'
			OR outward like '%".$searchValue."%'
			OR inhand like '%".$searchValue."%'
			OR description like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays` WHERE category='Supplier'";
		 $counter = 0;
		 if($from!="" && $to!="")
		 {
		  $sel_qry .=" AND (date >='$from' AND date<='$to')";   
		 }
		 if($searchValue!=''){
			 $sel_qry .= " AND ".$searchQuery;
		 }
		 if($supplier!=''){
			 $sel_qry .= " AND name='$supplier'";
		 }
		 if($trays!=''){
			 $sel_qry .= " AND type='$trays'";
		 }
	 
		 //print_r($sel_qry);die();
		 
		 $sel_qry .="GROUP by name";
		
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];

	$sel_qry = " SELECT count(*) as allcount FROM `trays` WHERE category='Supplier'";
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to') ";   
    }
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
	if($supplier!=''){
	    $sel_qry .= " AND name='$supplier'";
	}
	if($trays!=''){
	    $sel_qry .= " AND type='$trays'";
	}
	// $sel_qry .="order by id desc";
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


	$data_sql = " SELECT * FROM `trays` WHERE category='Supplier'";
  
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
	if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
	
	if($supplier!=''){
	    $data_sql .= " AND name='$supplier'";
	}
	if($trays!=''){
	    $data_sql .= " AND type='$trays'";
	}
	// $data_sql .=" order by id desc";
		
	//$data_sql .="group by name,date order by id desc limit 1";
	$data_sql.=" ORDER BY id DESC limit ".$row.",".$rowperpage;
	//print_r($data_sql);die();

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$select_qry6= "SELECT * FROM `sar_patti` WHERE supplier_id='".$data_row["name"]."'" ;
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$supplier_name=$select_row6["supplier_name"];

		$select_qry6= "SELECT * FROM `sar_supplier` WHERE supplier_no='".$data_row["name"]."'" ;
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$supplier_name=$select_row6["contact_person"];

// $sqltray="select * from trays where name='".$data_row["name"]."' and type='Big Tray' order by id desc limit 1";
// $datatray= $connect->prepare($sqltray);
// $datatray->execute();
// $resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

// $sqltray1="select * from trays where name='".$data_row["name"]."' and type='Small Tray' order by id desc limit 1";
// $datatray1= $connect->prepare($sqltray1);
// $datatray1->execute();
// $resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

	// $smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;
	// $bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
	
	// $small_ab_tray=isset($resulttray1['ab_tray'])?$resulttray1['ab_tray']:0;
	// $big_ab_tray=isset($resulttray['ab_tray'])?$resulttray['ab_tray']:0;
	
        
		// $select_qry6= "SELECT * FROM `sar_sales_invoice` WHERE customer_id='".$data_row["name"]."' group by customer_id" ;
	    // $select_sql6=$connect->prepare($select_qry6);
    	// $select_sql6->execute();
    	// $select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	// $customer_name=$select_row6["customer_name"];
        
        $tray_pay=($data_row["inhand"])*100;
//     if($data_row['type']=="Small Tray"){
// 		$absmalltray=isset($data_row['ab_tray'])?$data_row['ab_tray']:0;
// 		if($data_row['inhand']!=0){
// 		$smalltray=$data_row['inhand'];
// 		}
// 		// else{
// 		//     	$smalltray=isset($smalltray)?$smalltray:0;
// 		//     	}
// 	}
// 	if($data_row['type']=="Big Tray"){
// 		$abbigtray=isset($data_row['ab_tray'])?$data_row['ab_tray']:0;
// 		if($data_row['inhand']!=0){
// 			$bigtray=$data_row['inhand'];
// 		}
// else{
//     	$bigtray=isset($bigtray)?$bigtray:0;
// 	}
// }
 
// $small=isset($smalltray)?$smalltray:0;
// $big=isset($bigtray)?$bigtray:0;
// $absmall=isset($absmalltray)?$absmalltray:0;
// $abbig=isset($abbigtray)?$abbigtray:0;

$data_row["rowIndex"] = $rowIndex;
		$data_row["supplier_name"] = $supplier_name;
		$data_row["inward"] = abs($data_row["inward"]);
		$data_row["outward"] = $data_row["outward"];
		$data_row["description"] = $data_row["description"];
		$data_row["tray_pay"] = $tray_pay;
		$data_row["type"] = $data_row['type'];
		$data_row["smalltray"]=$data_row['smalltray']." : (₹".($data_row['smalltray']*100).")";
		$data_row["bigtray"]=$data_row['bigtray']." : (₹".($data_row['bigtray']*100).")";
		$data_row["abstray"]=$data_row['absmall'];
		$data_row["abbtray"]=$data_row['abbig'];
		$data_row["inhand"]=$data_row['inhand'];
		// $balanceTray = $balanceTray + $data_row["outward"]-$data_row["inward"] ;
		// $data_row["inhand"] = $balanceTray;
	   $data[] = $data_row; 
		//  $select_qry3= "SELECT sar_patti FROM `tray_transactions` WHERE category='Farmer'";
	    // $select_sql3=$connect->prepare($select_qry3);
    	// $select_sql3->execute();
    	// $select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	// $inward_sum=$select_row3["inward_sum"];
    	
//     	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Farmer'";
// 	    $select_sql4=$connect->prepare($select_qry4);
//     	$select_sql4->execute();
//     	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
//     	$outward_sum=$select_row4["outward_sum"];
//     	$total_sum=$outward_sum-$inward_sum;

	//    $data[]=array(
	//         "name"=>$data_row["name"],
	//         "date"=>$data_row["date"],
	//         "id"=>$data_row["id"],
	//         "date"=>$data_row["date"],
	//        "inward"=>$data_row["inward"],
	//         "outward"=>$data_row["outward"],
	//          "inhand"=>$total_sum
	         
	//     ); 
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_inventory_trays")
{

    $req=$_REQUEST["req"];
	$from=$_REQUEST["from_trays"];
	$to=$_REQUEST["to_trays"];
    $is_active=$_REQUEST["is_active"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			date like '%".$searchValue."%'
            OR name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
			OR inward like '%".$searchValue."%'
			OR outward like '%".$searchValue."%'
			OR inhand like '%".$searchValue."%'
			OR description like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays` where category='Admin'";
		 $counter = 0;
	// if ($searchValue!='' || ($from!="" && $to!="")) {
	// 	$sel_qry .= "  where ";
	// }
	if($searchValue!=''){
	    $sel_qry .= " AND".$searchQuery;
	}
	if($from!="" && $to!="")
    {
	// if($searchValue!=''){
	// 	$sel_qry .= " AND ";
	// }
     $sel_qry .=" AND date >='$from' AND date<='$to'";   
    }
	$sel_qry.=" ORDER BY date DESC";
	
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `trays` where category='Admin'";
   
    $counter = 0;
	// if ($searchValue!='' || ($from!="" && $to!="")) {
	// 	$data_sql .= "  where ";
	// }
	if($searchValue!=''){
	    $data_sql .= " AND".$searchQuery;
	}
	if($from!="" && $to!="")
    {
		// if($searchValue!=''){
		// 	$data_sql .= " AND ";
		// }
     $data_sql .=" AND date >='$from' AND date<='$to'";   
    }
	//$data_sql .="GROUP BY name";
    
	$data_sql.=" ORDER BY date DESC limit ".$row.",".$rowperpage;
	
	// echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		
		//$balanceTray = $balanceTray + $data_row["outward"]-$data_row["inward"] ;
		// $data_row["inhand"] = $balanceTray;
	   $data[] = $data_row; 
// 		 $select_qry3= "SELECT sum(inward) as inward_sum FROM `tray_transactions` WHERE category='Farmer'";
// 	    $select_sql3=$connect->prepare($select_qry3);
//     	$select_sql3->execute();
//     	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
//     	$inward_sum=$select_row3["inward_sum"];
    	
//     	$select_qry4= "SELECT sum(outward) as outward_sum FROM `tray_transactions` WHERE category='Farmer'";
// 	    $select_sql4=$connect->prepare($select_qry4);
//     	$select_sql4->execute();
//     	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
//     	$outward_sum=$select_row4["outward_sum"];
//     	$total_sum=$outward_sum-$inward_sum;

// 	   $data[]=array(
// 	        "name"=>$data_row["name"],
// 	        "date"=>$data_row["date"],
// 	        "id"=>$data_row["id"],
// 	        "date"=>$data_row["date"],
// 	       "inward"=>$data_row["inward"],
// 	        "outward"=>$data_row["outward"],
// 	         "inhand"=>$total_sum
	         
// 	    ); 
	}


	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_tray_inventory_all")
{

    $req=$_REQUEST["req"];
	//$from_all=$_GET["from_all"];
	$dropdown=$_REQUEST["dropdown"];
	$dropdown_all=$_REQUEST["dropdown_all"];
	// $sup=$_REQUEST["sup"];
	// $to_all=$_GET["to_all"];
	// print_r($dropdown_all);die();
    $is_active=$_REQUEST["is_active"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			date like '%".$searchValue."%'
            OR name like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
			OR inward like '%".$searchValue."%'
			OR outward like '%".$searchValue."%'
			OR inhand like '%".$searchValue."%'
			OR description like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays` ";
	if($searchValue!='' || $dropdown!='' || $dropdown_all!=''){
	    $sel_qry.= " where";
	}
	if($dropdown!="")
	{
	   $sel_qry.= " category='$dropdown'";
	}
	// if($dropdown_all!="")
	// {
	//    $sel_qry.= " name='$dropdown_all'";
	// }
	if($searchValue!=''){
	    $sel_qry.= " ".$searchQuery;
	}
  
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
  //     if($from_all!="" && $to_all!="")
    // {
    //  $sel_qry .=" WHERE (date >='$from_all' AND date<='$to_all')";   
    // }
	$sel_qry1 = "SELECT count(*) as allcount FROM `trays`";
	if($searchValue!='' || $dropdown!='' || $dropdown_all!=''){
	    $sel_qry1.= " where";
	}
	if($dropdown!="")
	{
	   $sel_qry1.= " category='$dropdown'";
	}
	// if($dropdown_all!="")
	// {
	//    $sel_qry1.= " name='$dropdown_all'";
	// }
	if($searchValue!=''){
	    $sel_qry1.= " ".$searchQuery;
	}
    // print_r($sel_qry1);die();
  
	// print_r($sel_qry);die();
	
	$sel_sql1= $connect->prepare($sel_qry1);
	$sel_sql1->execute();
	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row1["allcount"];

	$data_sql = " SELECT * FROM `trays`";
	if($searchValue!='' || $dropdown!='' || $dropdown_all!=''){
	    $data_sql.= " where";
	}
	if($dropdown!="")
	{
	   $data_sql.= " category='$dropdown'";
	}
	// if($dropdown_all!="")
	// {
	//    $data_sql.= " name='$dropdown_all'";
	// }
	if($searchValue!=''){
	    $data_sql.= " ".$searchQuery;
	}
    	
	$data_sql.=" GROUP by name";
	// $data_sql.=" ORDER BY date ASC limit ".$row.",".$rowperpage;
	
    // print_r($data_sql);die();
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	$rowIndex = $row;

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
    	if($data_row["category"] == 'Supplier'){
			$sup_id=$data_row['name'];
			
			$select_qry4= "SELECT * FROM `trays` WHERE category='Supplier' and name='$sup_id' order by id desc limit 1";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $farmer_sum=$select_row4["inhand"];
    	$total_sum=$select_row4["inhand"];
    
		$sqltray="select * from trays where category='Supplier' and name='$sup_id' and type='Big Tray' order by id desc limit 1";
$datatray= $connect->prepare($sqltray);
$datatray->execute();
$resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

$sqltray1="select * from trays where category='Supplier' and name='$sup_id' and type='Small Tray' order by id desc limit 1";
$datatray1= $connect->prepare($sqltray1);
$datatray1->execute();
$resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

$bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
$smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;

		$sup_name="select * from sar_supplier where supplier_no='$sup_id'"; 
		$sup_namee= $connect->prepare($sup_name);
		$sup_namee->execute();
		$supname = $sup_namee->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['contact_person'];	
		$name=$suppliername;
	}
    	else if($data_row["category"] == 'Customer'){
			$cus_id=$data_row['name'];
	
			$select_qry6= "SELECT * FROM `trays` WHERE category='Customer' and name='$cus_id' order by id desc limit 1";
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$customer_sum=$select_row6["inhand"];
    	$total_sum=$customer_sum;
    
	
		$sqltray="select * from trays where category='Customer' and name='$cus_id' and type='Big Tray' order by id desc limit 1";
$datatray= $connect->prepare($sqltray);
$datatray->execute();
$resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

$sqltray1="select * from trays where category='Customer' and name='$cus_id' and type='Small Tray' order by id desc limit 1";
$datatray1= $connect->prepare($sqltray1);
$datatray1->execute();
$resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

$bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
$smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;

		$sup_name1="select * from sar_customer where customer_no='$cus_id'"; 
		$sup_namee1= $connect->prepare($sup_name1);
		$sup_namee1->execute();
		$supname1 = $sup_namee1->fetch(PDO::FETCH_ASSOC);
		$suppliername1=$supname1['customer_name'];
		$name=$suppliername1;

	}
	else{
		$name=$data_row["name"];
		$select_qry6= "SELECT * FROM `trays` WHERE category='Admin' group by name order by id desc limit 1";
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$customer_sum=$select_row6["inhand"];
    	$total_sum=$customer_sum;
 
		$sqltray="select * from trays where category='Admin' and type='Big Tray' order by id desc limit 1";
		$datatray= $connect->prepare($sqltray);
		$datatray->execute();
		$resulttray = $datatray->fetch(PDO::FETCH_ASSOC);
		
		$sqltray1="select * from trays where category='Admin'and type='Small Tray' order by id desc limit 1";
		$datatray1= $connect->prepare($sqltray1);
		$datatray1->execute();
		$resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

		$sqltraya="select * from trays where type='Big Tray' order by id desc limit 1";
		$datatraya= $connect->prepare($sqltraya);
		$datatraya->execute();
		$resulttraya = $datatraya->fetch(PDO::FETCH_ASSOC);
		
		$sqltrayb="select * from trays where type='Small Tray' order by id desc limit 1";
		$datatrayb= $connect->prepare($sqltrayb);
		$datatrayb->execute();
		$resulttrayb = $datatrayb->fetch(PDO::FETCH_ASSOC);
		
		$bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
		$smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;
		
		$resulttraya=isset($resulttraya['inhand'])?$resulttraya['inhand']:0;
		$resulttrayb=isset($resulttrayb['inhand'])?$resulttrayb['inhand']:0;
	
		$inhan=$resulttraya+$resulttrayb;
		$inhanda=$bigtray+$smalltray;
	
	}
if($data_row["category"]=="Admin"){

	
	   $data[]=array(
	       "rowIndex"=>$data_row["rowIndex"],
	        "name"=>$name,
			"category"=>$data_row["category"],
	        "inhand"=>$inhanda,
	        "bigtray"=>$bigtray,
	        "smalltray"=>$smalltray
	    );
	}
	else{
		$data[]=array(
			"rowIndex"=>$data_row["rowIndex"],
			 "name"=>$name,
			 "category"=>$data_row["category"],
			 "inhand"=>$total_sum,
			 "bigtray"=>$bigtray,
			 "smalltray"=>$smalltray
		 );
	}
	}
// 	$data = array();
// 	$rowIndex = $row;
// 	$balanceTray = 0;
// 	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 		$rowIndex++;
// 		$data_row["rowIndex"] = $rowIndex;
		
	 

//     	$total_sum=$outward_sum-$inward_sum;
// 		$data_row["inhand"] = $total_sum;
// 	   $data[] = $data_row; 
// 	}


	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
        // "qry1" => $sel_qry,
        // "qry2" => $sel_qry,
        // "qry3" => $data_sql
	);

	echo json_encode($response);
   
}
else if($action=="view_customer")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            customer_name like '%".$searchValue."%'
            OR contact_number1 like '%".$searchValue."%'
            OR address like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_group_customer` ";
	
    // if($req=="enabled")
    // {
    //     $sel_qry.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $sel_qry.=" is_active=0 ";
    // }
    if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
	$sel_qry .="GROUP BY grp_cust_name";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_group_customer`  ";
    
    // if($req=="enabled")
    // {
    //     $data_sql.=" is_active=1 ";
    // }
    // else if($req=="disabled")
    // {
    //     $data_sql.=" is_active=0 ";
    // }
    if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
	$data_sql.="GROUP BY grp_cust_name ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$cusnames=$data_row["grp_cust_name"];
		$count="select * from sar_customer where grp_cust_name='$cusnames'";
		$execount=mysqli_query($con,$count);
		$cus_co=mysqli_fetch_assoc($execount);
		$no=mysqli_num_rows($execount);
	
	if($no>0) { $n=$no; }
	else { $n=0; }
		$data[]=array(
	        "id"=>$data_row['id'],
	        "grp_cust_name"=>$data_row["grp_cust_name"],
			"no"=>$n
		);
}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);
	// print_r($data);die();

	echo json_encode($response);
   
}
else if($action=="view_expenditure")
{
    $req=$_REQUEST["req"];
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
   $category=$_REQUEST["category"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            date like '%".$searchValue."%'
            OR purchased_from like '%".$searchValue."%'
            OR particulars like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_expenditure` WHERE status=1 ";
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($category!="")
    {
     $sel_qry .=" AND revenue='$category'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_expenditure` WHERE status=1 ";
    if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
	if($category!="")
    {
     $data_sql .=" AND revenue='$category'";   
    }
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        "qry1" => $sel_qry,
        "qry2" => $data_sql
	);

	echo json_encode($response);
   
}
else if($action=="view_farmer")
{
    $req=$_REQUEST["req"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            date like '%".$searchValue."%'
            OR purchased_from like '%".$searchValue."%'
            OR particulars like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_farmer`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_farmer`  ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_sales_invoice")
{
    $req=$_REQUEST["req"];
    //$name=$_REQUEST["name"];
    $customer_id=$_REQUEST["customer_id"];
    $is_active=$_REQUEST["is_active"];
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $grp=$_REQUEST["grp"];
    $dropdown=explode("_",$_REQUEST["dropdown"]);
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '0';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            date like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR customer_address like '%".$searchValue."%'
            OR boxes_arrived like '%".$searchValue."%'
            OR quality_name like '%".$searchValue."%'
            OR quantity like '%".$searchValue."%'
            OR rate like '%".$searchValue."%'
            OR bill_amount like '%".$searchValue."%'
            OR total_bill_amount like '%".$searchValue."%'
          
            
	    )";
	}else{
	    $searchQuery = " ";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice`  ";
	if($req=="enabled")
    {
        $sel_qry.=" where is_active=1 and nullify=0";
    }
    else if($req=="disabled")
    {
        $sel_qry.=" where is_active=0 and nullify=1";
    }
    
	if($from!="" && $to!="")
    {
     $sel_qry .=" where (date >='$from' AND date<='$to')";   
    if($searchQuery!=""){
	$sel_qry .= " AND ".$searchQuery;
	}
            }
    if($dropdown[0]!="")
	{
	   $sel_qry .= " AND (customer_name='$dropdown[0]' OR mobile_number='$dropdown[1]') AND groupname='$grp'";
	}
	$sel_qry.=" GROUP BY sale_id";

    //   print_r($sel_qry);die();
       $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice`  ";
	if($req=="enabled")
    {
        $sel_qry.=" where is_active=1 and nullify=0";
    }
    else if($req=="disabled")
    {
        $sel_qry.=" where is_active=0 and nullify=1";
    }
   if($from!="" && $to!="")
    {
     $sel_qry .=" where (date >='$from' AND date<='$to')";   
    if($searchQuery!=""){
	$sel_qry .= " AND ".$searchQuery;
	}
            }
    if($dropdown[0]!="")
	{
	   $sel_qry .= " AND (customer_name='$dropdown[0]' OR mobile_number='$dropdown[1]') AND groupname='$grp'";
	}
	$sel_qry.=" GROUP BY sale_id";

	$sel_sql= $connect->prepare($sel_qry);
   	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
		$totalRecordwithFilter = $sel_row["allcount"];

    	$data_sql = "SELECT * FROM `sar_sales_invoice` ";
        if($req=="enabled")
		{
			$data_sql.=" where is_active=1 and nullify=0";
		}
		else if($req=="disabled")
		{
			$data_sql.=" where is_active=0 and nullify=1";
		}
	if($from!="" && $to!="")
    {
     $data_sql .=" where (date >='$from' AND date<='$to')";   
    if($searchQuery!=""){
	$data_sql .= " AND ".$searchQuery;
	}
            }
        if($dropdown[0]!="")
	    {
	    $data_sql .= " AND (customer_name='$dropdown[0]' OR mobile_number='$dropdown[1]') AND groupname='$grp'";
	    }
    	 
    	$data_sql.=" GROUP BY sale_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage; 
    // 	 print_r($data_sql);die();
	
    	$data_qry= $connect->prepare($data_sql);
    	$data_qry->execute();
    	
	
    	$data = array();
	$tot=0;    
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   
	    $sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_sales_payment where customer_id = '".$data_row["sales_no"]."' AND is_revoked is NULL group by customer_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    $tot+=$data_row["total_bill_amount"];
	    
        // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["total_bill_amount"] - $total_discount_on_sales - $data_row2["paid_amount"];
	    $select_qry3= "SELECT * FROM `trays` WHERE ids='".$data_row["sale_id"]."' and type='".$data_row["type"]."'";
		$select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	// print_r($select_row3);die();
	    $inhand=isset($select_row3["inhand"])?$select_row3["inhand"]:0;
	    $small=isset($select_row3["smalltray"])?$select_row3["smalltray"]:0;
	    $big=isset($select_row3["bigtray"])?$select_row3["bigtray"]:0;
   	// $tray_pend=($tray_pend<0)?0:$data_row['tray_pend'];
	// if($tray_pay==0){
	// 	$tray_pay=$data_row['quantity']*100;
	// }
	// else{
	$tray_pay=$inhand*100;
	// }
	// $select_qry4= "SELECT * FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_id"]."' order by id desc limit 1";
	    // $select_sql4=$connect->prepare($select_qry4);
    	// $select_sql4->execute();
    	// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["inhand"];
    	 $total_sum=$inward_sum;
	    $data[]=array(
	        "balance"=>$balance,
	        "paid_amount"=>$data_row2["paid_amount"],
	        "type"=>$data_row["type"],
	        "id"=>$data_row["id"],
	        "customer_id"=>$data_row['customer_id'],
	        "sale_id"=>$data_row['sale_id'],
	        "date"=>$data_row["date"],
	       "amount"=>$data_row["amount"],
	        "customer_name"=>$data_row["customer_name"],
	        "sales_no"=>$data_row["sales_no"],
	        "updated_by"=>$data_row["updated_by"],
	        "quality_name"=>$data_row["quality_name"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "total_bill_amount"=>$tot,
	         "is_active"=>$data_row["is_active"],
	         "waiver_discount"=>$total_discount_on_sales,
			 "tray_pay"=>$inhand*100,	     
			//  "tray_pend"=>$tray_pend,
	         "inhand"=>$inhand,
	         "small"=>$small,
	         "big"=>$big
	    );
	}
    


	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_cash_carry")
{
    $req=$_REQUEST["req"];
    $id=$_REQUEST["id"];
    $is_active=$_REQUEST["is_active"];
    $cash_no=$_REQUEST["cash_no"];
    $from=$_REQUEST["from_cash"];
    $to=$_REQUEST["to_cash"];
    $grp=$_REQUEST["grp"];
    $dropdown=explode("_",$_REQUEST["dropdown_cash"]);
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
    $filter = array();
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = "  
	   (
            date like '%".$searchValue."%'
            OR updated_by like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR bill_amount like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
            
	    )";
	    array_push($filter, $searchQuery);
	}
	
    $sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry` ";
	if($from!="" && $to!="")
    {
     $sel_qry .=" where (date >='$from' AND date<='$to')";  
    //  array_push($filter, " (date >='$from' AND date<='$to')");
    }
	if($req=="active")
    {
        $sel_qry.=" WHERE is_active=1 ";
        // array_push($filter, "is_active=1");
    }
    else if($req=="inactive")
    {
        $sel_qry.=" WHERE is_active=0  ";
        // array_push($filter, "is_active=0");
    }
    if($dropdown[0]!="")
    	{
			$sel_qry.=" AND customer_name='$dropdown[0]' AND groupname='$grp' ";
			// array_push($filter,"  (customer_name='$dropdown[0]') AND groupname='$grp'");
    	}
	$sel_qry.= " GROUP BY cash_no";
	// print_r($sel_qry);die();
    $sel_sql= $connect->prepare($sel_qry);

	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	// $sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry` ";
	// if($from!="" && $to!="")
    // {
    //  $sel_qry .=" (date >='$from' AND date<='$to')";  
    // //  array_push($filter, " (date >='$from' AND date<='$to')");
    // }
	// if($req=="active")
    // {
    //     $sel_qry.=" WHERE is_active=1 AND";
    //     // array_push($filter, "is_active=1");
    // }
    // else if($req=="inactive")
    // {
    //     $sel_qry.=" WHERE is_active=0 AND ";
    //     // array_push($filter, "is_active=0");
    // }
    // if($dropdown[0]!="")
    // 	{
	// 		$sel_qry.=" customer_name='$dropdown[0]' AND groupname='$grp' ";
	// 		// array_push($filter,"  (customer_name='$dropdown[0]') AND groupname='$grp'");
    // 	}
    // // $filter_str = join(" AND ",$filter);
    // $sel_qry.= " GROUP BY cash_no";
	
	// print_r($sel_qry);die();
	// $sel_sql= $connect->prepare($sel_qry);
	// $sel_sql->execute();
	// $sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];
	
    $data_sql = " SELECT * FROM sar_cash_carry ";
	if($from!="" && $to!="")
    {
     $data_sql .=" where (date >='$from' AND date<='$to')";  
    //  array_push($filter, " (date >='$from' AND date<='$to')");
    }
	if($req=="active")
    {
        $data_sql.=" WHERE is_active=1";
        // array_push($filter, "is_active=1");
    }
    else if($req=="inactive")
    {
        $data_sql.=" WHERE is_active=0 ";
        // array_push($filter, "is_active=0");
    }
    if($dropdown[0]!="")
    	{
			$data_sql.=" AND customer_name='$dropdown[0]' AND groupname='$grp' ";
			// array_push($filter,"  (customer_name='$dropdown[0]') AND groupname='$grp'");
    	}

	$data_sql.=" GROUP BY cash_no ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	// $data_row = $data_qry->fetch(PDO::FETCH_ASSOC);
	$data = array();
	
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {

		$sal=$datarow["saleid"];
		$cash=$data_row["cash_no"];
		$saleid="select * from sar_sales_invoice where sale_id='".$data_row["saleid"]."'"; 
		$saleids= $connect->prepare($saleid);
		$saleids->execute();
		$saleidss = $saleids->fetch(PDO::FETCH_ASSOC);
// print_r($saleidss['type']);die();

		$select_qry3= "SELECT * FROM `trays` WHERE category='Customer' and type='".$saleidss["type"]."' AND ids='".$data_row["saleid"]."' order by id desc limit 1";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inhand"];
		$total_sum=isset($inward_sum)?$inward_sum:0;
    	$tray_pay=$total_sum*100;

		$cus_id=$data_row['customer_id'];
		$sup_name="select * from sar_customer where customer_no='$cus_id'"; 
		$sup_namee= $connect->prepare($sup_name);
		$sup_namee->execute();
		$supname = $sup_namee->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['customer_name'];

		
		// $tot="select *,SUM(total_bill_amount) as total from sar_sales_invoice where sale_id='".$data_row["saleid"]."'"; 
		// $tots= $connect->prepare($tot);
		// $tots->execute();
		// $tota = $tots->fetch(PDO::FETCH_ASSOC);

    	// $select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    // $select_sql4=$connect->prepare($select_qry4);
    	// $select_sql4->execute();
    	// $select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["outward_sum"];
    	// $total_sum=$outward_sum-$inward_sum;

        $data[] = array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "saleid"=>$data_row["saleid"],
	        "customer_id"=>$cus_id,
	        "date"=>$data_row["date"],
	        "customer_name"=>$suppliername,
	        "cash_no"=>$cash,
	        "updated_by"=>$data_row["updated_by"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "total_bill_amount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
			 "tray_pay"=>$tray_pay,
	         "inhand_sum"=>$total_sum
	    );
	//   print_r($data);die();
	}
//print_r($data);die();
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        // "qry1" => $sel_qry,
        // "qry2" => $sel_qry,
        "qry3" => $data_sql
	);

	echo json_encode($response);
   
}
// else if($action=="view_cash_carry_all")
// {
//     $req=$_REQUEST["req"];
//     $is_active=$_REQUEST["is_active"];
//     $cash_no=$_REQUEST["cash_no"];
//     $from_all=$_REQUEST["from_all"];
//     $to_all=$_REQUEST["to_all"];
//     $dropdown=$_REQUEST["dropdown"];
//     $grp=$_REQUEST["grp"];
//     $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
// 	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
// 	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
// 	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
// 	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
// 	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
// 	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
//     $filter = array();
// 	$searchQuery = " ";
// 	if($searchValue != ''){
// 	   $searchQuery = "  payment_status=0 AND
// 	   (
//             date like '%".$searchValue."%'
//             OR updated_by like '%".$searchValue."%'
//             OR category like '%".$searchValue."%'
//             OR customer_name like '%".$searchValue."%'
//             OR mobile_number like '%".$searchValue."%'
//             OR bill_amount like '%".$searchValue."%'
//             OR amount like '%".$searchValue."%'
            
// 	    )";
// 	    array_push($filter, $searchQuery);
// 	}else{
// 	    $searchQuery = " payment_status = 0 ";
// 	}
	
//     $sel_qry = " SELECT  count(*) as allcount FROM `sar_cash_carry`";
//     if($from_all!="" && $to_all!="")
//     {
//      //$sel_qry .=" (date >='$from' AND date<='$to')";  
//      array_push($filter, " (date >='$from_all' AND date<='$to_all') ");
//     }
	
//     $filter_str = join(" AND ",$filter);
//     //$sel_qry1_test = $sel_qry ." WHERE " .$filter_str." GROUP BY cash_no";
//     if ($filter_str != ""){
// 	    $sel_qry .= " WHERE " .$filter_str." GROUP BY cash_no";
//     }
    
//     $sel_sql= $connect->prepare($sel_qry);
// 	$sel_sql->execute();
// 	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
// 	$totalRecords = $sel_row["allcount"];
//     $sel_qry2_test = "";
// 	$sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry`";
	
// 	//$sel_qry2_test = $sel_qry ." WHERE " .$filter_str." GROUP BY cash_no";
//     if ($filter_str != ""){
//         $sel_qry.=" WHERE " .$filter_str. " GROUP BY cash_no";
//     }
// 	$sel_sql= $connect->prepare($sel_qry);
// 	$sel_sql->execute();
// 	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
// 	$totalRecordwithFilter = $sel_row["allcount"];
	
	
// 	if($dropdown!="")
// 	{
//      array_push($filter," invoice.customer_name='$dropdown' AND invoice.groupname='$grp'");
// 	}
//     $filter_str = join(" AND ",$filter);
    
//     $data_sql = "SELECT invoice.sales_no as sales_no,invoice.customer_name as name,invoice.date as date,invoice.total_bill_amount as bill_amount, sum(pay.amount) as payment FROM sar_sales_invoice as invoice, sar_sales_payment as pay Where invoice.sales_no = pay.customer_id and payment_status=0 and is_revoked is NULL GROUP By sales_no UNION SELECT ob.balance_id as balance_id,ob.name as name,ob.date as date,ob.amount as bill_amount,sum(bpay.amount) as payment FROM sar_opening_balance as ob,sar_balance_payment as bpay WHERE ob.balance_id = bpay.balance_id AND ob.balance_id LIKE '%COB%' AND payment_status=0 GROUP By bpay.balance_id";

//     if ($filter_str != ""){
//         $data_sql = "SELECT invoice.sales_no as sales_no,invoice.customer_name as name,invoice.date as date,invoice.total_bill_amount as bill_amount, sum(pay.amount) as payment FROM sar_sales_invoice as invoice, sar_sales_payment as pay Where ".$filter_str." AND invoice.sales_no = pay.customer_id and payment_status=0 and is_revoked is NULL GROUP By sales_no UNION SELECT ob.balance_id as balance_id,ob.name as name,ob.date as date,ob.amount as bill_amount,sum(bpay.amount) as payment FROM sar_opening_balance as ob,sar_balance_payment as bpay WHERE ".$filter_str." AND ob.balance_id = bpay.balance_id AND ob.balance_id LIKE '%COB%' AND payment_status=0 GROUP By bpay.balance_id";
//     }
	
// 	$data_qry= $connect->prepare($data_sql);
// 	$data_qry->execute();
	
// 	$get_discount_sql = "Select sales_no,waiver_discount from sar_waiver";
	
// 	$get_discount_qry= $connect->prepare($get_discount_sql);
// 	$get_discount_qry->execute();
	
//     // print_r($sales_no_discount);
//     $sales_no_discount = array();
//     while ($data_row = $get_discount_qry->fetch(PDO::FETCH_ASSOC)) {
//         $sales_no_discount[$data_row['sales_no']] = $data_row['waiver_discount'];
//     }
    
//     $get_paymeny_sql = "select customer_id, sum(amount) as payment from sar_sales_payment where is_revoked is null group by customer_id";
//     $get_paymeny_sql_qry= $connect->prepare($get_paymeny_sql);
// 	$get_paymeny_sql_qry->execute();
	
//     // print_r($sales_no_discount);
//     $customer_total_pay = array();
//     while ($data_row = $get_paymeny_sql_qry->fetch(PDO::FETCH_ASSOC)) {
//         $customer_total_pay[$data_row['customer_id']] = $data_row['payment'];
//     }
// 	$data = array();
// 	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 	   $data_row["waiver_discount"] = $sales_no_discount[$data_row["sales_no"]];
	   
// 	   //$data_row["payment"] = $customer_total_pay[$data_row['customer_id']];
	   
// 	   //$data_row["balance"] = $data_row["bill_amount"] - $data_row["waiver_discount"] - $data_row["payment"];
//     //     $data_row["balance_pay"] = $data_row["bill_amount"] - $data_row["payment"];
	   
// 	   $data[] = $data_row;
// 	}

// 	$response = array(
//         "draw" => intval($draw),
//         "iTotalRecords" => $totalRecords,
//         "iTotalDisplayRecords" => $totalRecordwithFilter,
//         "aaData" => $data
//         // "qry1" => $sel_qry1_test,
//         // "qry2" => $sel_qry2_test,
//         // "qry3" => $data_sql_test
// 	);

// 	echo json_encode($response);
   
// }

else if($action=="view_cash_carry_all")
{
	$req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    // $cash_no=$_REQUEST["cash_no"];
    $from_all=$_REQUEST["from_all"];
    $to_all=$_REQUEST["to_all"];
    $dropdown_settled=$_REQUEST["dropdown_all"];
    $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
    $filter = array();
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = "  is_active=0
	   (
            date like '%".$searchValue."%'
            OR updated_by like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR bill_amount like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
            
	    )";
	    array_push($filter, $searchQuery);
	}else{
	    $searchQuery = "AND nullify=0 AND paid=1";
	}

// print_r($dropdown_settled);die();
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice` where is_active=0";
if($searchQuery!=""){
	$sel_qry .= " ".$searchQuery;
}
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (customer_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]') AND groupname='$grp'";
	}
	$sel_qry.="  GROUP BY sale_id";
// AND nullify=0 AND paid=1 
	// print_r($sel_qry);die();
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	// //if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice` where is_active=0";
	if($searchQuery!=""){   
	$sel_qry .= " ".$searchQuery;
	}
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	 if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (customer_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]')  AND groupname='$grp'";
	}   
    $sel_qry.=" GROUP BY sale_id";

$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT *, SUM(bill_amount) AS totalbillamount FROM `sar_sales_invoice`  where is_active=0";
	if($searchQuery!=""){ 
	$data_sql .= " ".$searchQuery;  
	}
	if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $data_sql .= " AND (customer_name='$dropdown_settled[0]' OR mobile_number='$dropdown_settled[1]')  AND groupname='$grp'";
	}
	$data_sql.=" GROUP BY sale_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	// print_r($data_sql);die();
//AND nullify=0 AND paid=1 
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	//    $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	//     $select_sql3=$connect->prepare($select_qry3);
    // 	$select_sql3->execute();
    // 	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    // 	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT * FROM `trays` WHERE ids='".$data_row["sale_id"]."' and type='".$data_row["type"]."'";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	// $outward_sum=$select_row4["outward_sum"];
    	// $total_sum=$outward_sum-$inward_sum;

	    $data[]=array(
	        "id"=>$data_row["id"],
	        // "farmer_name"=>$data_row["farmer_name"],
	        "customer_name"=>$data_row["customer_name"],
	        "customer_id"=>$data_row["customer_id"],
	        "quality_name"=>$data_row["quality_name"],
	        "date"=>$data_row["date"],
	        "sales_no"=>$data_row["sales_no"],
	        "sale_id"=>$data_row["sale_id"],
	        // "updated_by"=>$data_row["updated_by"],
	        // "boxes_arrived"=>$data_row["boxes_arrived"],
	        // "total_deduction"=>$data_row["total_deduction"],
	        // "net_bill_amount"=>$data_row["net_bill_amount"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "total_bill_amount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "inhand"=>$select_row4["inhand"],
	         "small"=>$select_row4["smalltray"],
	         "big"=>$select_row4["bigtray"],
			 "traypay"=>$select_row4["inhand"]*100,
	         
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_customer_wise_report")
{
    $balance_id=$_REQUEST["balance_id"];
    $req=$_REQUEST["req"];
    $customer_name=$_REQUEST["customer_name"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
    $filter = array();
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = "  
	   (
            date like '%".$searchValue."%'
            OR updated_by like '%".$searchValue."%'
            OR category like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR bill_amount like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
            
	    )";
	    array_push($filter, $searchQuery);
	}
	
    $sel_qry = " SELECT  count(*) as allcount FROM `sar_sales_invoice` where is_active=1 and nullify=0";
	$filter_str = join(" AND ",$filter);
    //$sel_qry1_test = $sel_qry ." WHERE " .$filter_str." GROUP BY cash_no";
    if ($filter_str != ""){
	    $sel_qry .= " AND " .$filter_str." GROUP BY customer_id";
    }  
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
    $sel_qry2_test = "";
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice` where is_active=1 and nullify=0";
	if ($filter_str != ""){
	    $sel_qry .= " AND " .$filter_str." GROUP BY customer_id";
    }  
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];
	
    // $data_sql = "SELECT invoice.sales_no as id,invoice.customer_name,sum(invoice.bill_amount) as total_bill_amount,sum(pay.amount) as payment FROM `sar_sales_invoice` as invoice, `sar_sales_payment` as pay WHERE invoice.customer_name=pay.customer_name GROUP BY invoice.customer_name,pay.customer_name";
    $data_sql="SELECT sar_sales_invoice.sales_no,sar_sales_invoice.nullify,sar_sales_invoice.customer_name,sar_sales_invoice.customer_id,sar_sales_invoice.total_bill_amount,sar_opening_balance.name,sar_opening_balance.amount,sar_opening_balance.balance_id FROM sar_sales_invoice INNER JOIN sar_opening_balance ON sar_opening_balance.name = sar_sales_invoice.customer_id where sar_sales_invoice.nullify=0";
    // $data_sql = "SELECT total_bill_amount,customer_name,sales_no,date FROM sar_sales_invoice WHERE payment_status=0";
    $data_qry=$connect->prepare($data_sql);
    $data_qry->execute();
    // print_r($data_sql);die();
while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   
	    $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '".$data_row["customer_id"]."' AND is_revoked=0 group by customer_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["total_bill_amount"] - $total_discount_on_sales - $data_row2["paid_amount"];
	    
	    $open_balance_qry = "SELECT amount from sar_opening_balance where balance_id = '".$data_row["balance_id"]."' group by balance_id ";
	    $open_balance_sql= $connect->prepare($open_balance_qry);
	    $open_balance_sql->execute();
	    $open_balance_row = $open_balance_sql->fetch(PDO::FETCH_ASSOC);
	    
	    $open_balance_pay_qry = "SELECT  sum(amount) as pay_amount from sar_balance_payment where balance_id = '".$data_row["balance_id"]."' group by balance_id ";
	    $open_balance_pay_sql= $connect->prepare($open_balance_pay_qry);
	    $open_balance_pay_sql->execute();
	    $open_balance_pay_row = $open_balance_pay_sql->fetch(PDO::FETCH_ASSOC);
	    $balance_ob = $open_balance_row["amount"] - $open_balance_pay_row["pay_amount"];
	    $balance_total=$balance+$balance_ob;
	    
	    $data[]=array(
	        "balance"=>$balance_total,
	        "net_inv_balance"=>$balance,
	        "net_ob_balance"=>$balance_ob,
	        "id"=>$data_row["id"],
	        "customer_name"=>$data_row["customer_name"]
	    );
	}
    if($data==null){
		$data=[];
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        "qry1" => $payment
	);

	echo json_encode($response);
   
}
else if($action=="view_datewise_report")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $customer=$_REQUEST["customer"];
    $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND 
	   (
            date like '%".$searchValue."%'
            OR credit like '%".$searchValue."%'
            OR debit like '%".$searchValue."%'
             OR description like '%".$searchValue."%'
	    )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND ids='$customer'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	//print_r($sel_row);die();
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND ids='$customer'";   
    }
	if($from!="" || $to!="" || $customer!="")
	{
	 $sel_qry.=" AND ids LIKE 'C%'";
	}
	else{
	 $sel_qry.=" WHERE ids LIKE 'C%'";
	}
 // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `financial_transactions`";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
	if($customer!="")
    {
     $data_sql .=" AND ids='$customer'";   
    }
	if($from!="" || $to!="" || $customer!="")
   {
	$data_sql.=" AND ids LIKE 'C%'";
   }
   else{
	$data_sql.=" WHERE ids LIKE 'C%'";
   }
	// print_r($data_sql);die();

//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    	$rowIndex++;
			
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_datewise_report_all")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $customer=$_REQUEST["customer"];
    $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND 
	   (
            date like '%".$searchValue."%'
            OR credit like '%".$searchValue."%'
            OR debit like '%".$searchValue."%'
             OR description like '%".$searchValue."%'
	    )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND ids='$customer'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	//print_r($sel_row);die();
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
	// if($customer!="")
    // {
    //  $sel_qry .=" AND ids='$customer'";   
    // }
	// if($from!="" || $to!="" || $customer!="")
	// {
	//  $sel_qry.=" AND ids LIKE 'C%'";
	// }
	// else{
	//  $sel_qry.=" WHERE ids LIKE 'C%'";
	// }
 // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `financial_transactions`";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
// 	if($customer!="")
//     {
//      $data_sql .=" AND ids='$customer'";   
//     }
// 	if($from!="" || $to!="" || $customer!="")
//    {
// 	$data_sql.=" AND ids LIKE 'C%'";
//    }
//    else{
// 	$data_sql.=" WHERE ids LIKE 'C%'";
//    }
	// print_r($data_sql);die();

//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    	$rowIndex++;
			
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}

else if($action=="view_payment_report")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $supplier=$_REQUEST["supplier"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            payment_date like '%".$searchValue."%'
            OR payment_id like '%".$searchValue."%'
            OR payment_mode like '%".$searchValue."%'
         )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `payment`  where pattid like 'P%'";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND supplierid='$supplier'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	//print_r($sel_row);die();
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `payment` where pattid like 'P%'";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND supplierid='$supplier'";   
    }
//  $sel_qry.=" GROUP BY supplier_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `payment` where pattid like 'P%'";
	//,SUM(amount) as given
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $data_sql .=" AND supplierid='$supplier'";   
    }
	// $data_sql .=" group by supplier_id";   
  
	
		// print_r($sel_qry);die();

//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    $sup_id=$data_row['supplier_id'];
			$sup_name="select * from sar_supplier where supplier_no='$sup_id'"; 
			$sup_namee= $connect->prepare($sup_name);
			$sup_namee->execute();
			$supname = $sup_namee->fetch(PDO::FETCH_ASSOC);
			$suppliername=$supname['contact_person'];
			$rowIndex++;

			$sqlsup="select supplier_id,SUM(total_bill_amount) as total from sar_patti where supplier_id='$sup_id' GROUP BY supplier_id";
$data_qryup= $connect->prepare($sqlsup);
$data_qryup->execute();
$resultup = $data_qryup->fetch(PDO::FETCH_ASSOC);

// $sqltray="select * from trays where name='$sup_id' and type='Big tray' order by id desc limit 1";
// $datatray= $connect->prepare($sqltray);
// $datatray->execute();
// $resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

// $sqltray1="select * from trays where name='$sup_id' and type='Small Tray' order by id desc limit 1";
// $datatray1= $connect->prepare($sqltray1);
// $datatray1->execute();
// $resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);

$obb="select *,count(*) as co,SUM(amount) as sum_bal from sar_ob_supplier where supplier_name='$sup_id' and payment_status=0";
$ob= $connect->prepare($obb);
$ob->execute();
$obal = $ob->fetch(PDO::FETCH_ASSOC);
$no=$obal['co'];
$op=$obal['sum_bal'];
// print_r($op);die();
if($no>0){
	$bal=$data_row['balance']+$op;
}
else {
	$bal=$data_row['balance'];}
	$total=$resultup['total'];
	
$bigtray=isset($resulttray['inhand'])?$resulttray['inhand']:0;
$smalltray=isset($resulttray1['inhand'])?$resulttray1['inhand']:0;

		
		$data_row["rowIndex"] = $rowIndex;
		$data_row["supplier_name"] = $data_row['name'];
		$data_row["total"] = $data_row['total'];
		$data_row["payment_date"] = $data_row['date'];
		$data_row["given"] = $data_row['pay'];
		// $data_row["bal"] = $data_row[''];
		 $data_row["bigtray"] = $data_row['bigtray'];
		 $data_row["smalltray"] = $data_row['smalltray'];
	   $data[] = $data_row;
	 	}


		$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

// print_r( $data);die();
	echo json_encode($response);
   
}


else if($action=="view_payment_report_customer")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $customer=$_REQUEST["customer"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
            payment_date like '%".$searchValue."%'
            OR payment_id like '%".$searchValue."%'
            OR payment_mode like '%".$searchValue."%'
         )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `payment_sale` where saleid like 'C_%'";
  
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customerid='$customer'";   
    }
	// $sel_qry .=" AND saleid like 'C_%'";   
    // print_r($sel_qry);die();
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `payment_sale` where saleid like 'C_%'";
	
	if($from!="" && $to!="" )
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customerid='$customer'";   
    }
  // $sel_qry .=" group by customer_id";   
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `payment_sale` where saleid like 'C_%'";
	//,SUM(amount) as given
	if($from!="" && $to!="")
    {
     $data_sql .="AND (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $data_sql .=" AND customerid='$customer'";   
    }
	// $data_sql .=" group by customer_id";   
	
	// print_r($data_sql);die();

//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$cus_id=$data_row['customerid'];
		// print_r($data_row);die();
		// $sale_id=$data_row['saleid'];
		// $sup_name="select * from sar_customer where customer_no='$cus_id'"; 
		// $sup_namee= $connect->prepare($sup_name);
		// $sup_namee->execute();
		// $supname = $sup_namee->fetch(PDO::FETCH_ASSOC);
		// $suppliername=$supname['customer_name'];
		
			
		$sqlsup="select customer_id,SUM(total_bill_amount) as total from sar_sales_invoice where customer_id='$cus_id' GROUP BY customer_id";
$data_qryup= $connect->prepare($sqlsup);
$data_qryup->execute();
$resultup = $data_qryup->fetch(PDO::FETCH_ASSOC);

// $type=$datarow['type'];

// $sqltray="select * from trays where type='$type'";
// $datatray= $connect->prepare($sqltray);
// $datatray->execute();
// $resulttray = $datatray->fetch(PDO::FETCH_ASSOC);

// $sqltray1="select * from trays where name='$cus_id' and type='Small Tray' order by id desc limit 1";
// $datatray1= $connect->prepare($sqltray1);
// $datatray1->execute();
// $resulttray1 = $datatray1->fetch(PDO::FETCH_ASSOC);


$total=($resultup['total'])?$resultup['total']:0;
$bal=$resultup['total']-$data_row['pay'];
// $bigtray=isset($resulttray['bigtray'])?$resulttray['bigtray']:0;
// $smalltray=isset($resulttray1['smalltray'])?$resulttray1['smalltray']:0;


		$rowIndex++;
		$datarow["rowIndex"] = $rowIndex;
		$datarow["name"] = $data_row['name'];
		$datarow["date"] = $data_row['date'];
		$datarow["total"] = $data_row['total'];
		$datarow["given"] = $data_row['pay'];
		// $datarow["bal"]=$bal;
		// $data_row["bal"] = abs($bal);
		$datarow["bigtray"] = $data_row['bigtray'];
		$datarow["smalltray"] = $data_row['smalltray'];
		// $data_row["inhand"] = $data_row['inhand'];
	   $data[] = $datarow;
	 	}
	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	// print_r($data_row);die();
	echo json_encode($response);
   
}

else if($action=="view_datewise_report_supplier")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	$supplier=$_REQUEST["supplier"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND 
	   (
            date like '%".$searchValue."%'
            OR credit like '%".$searchValue."%'
            OR debit like '%".$searchValue."%'
             OR description like '%".$searchValue."%'
	    )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND ids='$supplier'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND ids='$supplier'";   
    }
	if($from!="" || $to!="" || $customer!="")
	{
	 $sel_qry.=" AND ids LIKE 'S%'";
	}
	else{
	 $sel_qry.=" WHERE ids LIKE 'S%'";
	}	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >= '$from' AND date <= '$to')";   
    }
	if($supplier!="")
    {
     $data_sql .=" AND ids='$supplier'";   
    }
	if($from!="" || $to!="" || $customer!="")
	{
	 $data_sql.=" AND ids LIKE 'S%'";
	}
	else{
	 $data_sql.=" WHERE ids LIKE 'S%'";
	}
	// print_r($data_sql);die();
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    	$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}



else if($action=="view_miscellaneous_revenue")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND 
	   (
            date like '%".$searchValue."%'
            OR credit like '%".$searchValue."%'
            OR debit like '%".$searchValue."%'
             OR description like '%".$searchValue."%'
	    )";
	}
    $sel_qry = "SELECT count(*) as allcount FROM `sar_miscellaneous_revenue`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_miscellaneous_revenue`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_miscellaneous_revenue`  ";
	
	 
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    	$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_daywise_report")
{

    $req=$_REQUEST["req"];
	$from=$_GET["from"];
	$to=$_GET["to"];
    $is_active=$_REQUEST["is_active"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
			dateOnly like '%".$searchValue."%'
            OR sum_credit like '%".$searchValue."%'
            OR sum_debit like '%".$searchValue."%'
			OR profit like '%".$searchValue."%'
	    )";
	}
	
	
	
	$sel_qry = "SELECT COUNT(*) as allcount FROM( SELECT DATE(date) as dateOnly, SUM(credit) as sum_credit, SUM(debit) as sum_debit, (SUM(credit) - SUM(debit)) as profit FROM `financial_transactions`  ";
	if($from!="" && $to!=""){
		$sel_qry .="WHERE date >='$from' AND date<='$to'";   
    }
    $sel_qry .=" group by dateOnly ";
	if($searchValue!=''){
	    $sel_qry .= " having ".$searchQuery;
	}
	$sel_qry .= " ) t";
	// echo $sel_qry;exit;

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT *,DATE(date) as dateOnly, SUM(credit) as sum_credit, SUM(debit) as sum_debit, (SUM(credit) - SUM(debit)) as profit FROM `financial_transactions` ";
    $counter = 0;
	if($from!="" && $to!=""){
		$data_sql .="WHERE date >='$from' AND date<='$to'";   
    }
    
    
	$data_sql.=" group by dateOnly";
	if($searchValue!=''){
	    $data_sql .= " having ".$searchQuery;
	}
	$data_sql.=" ORDER BY ".$columnName." ". $columnSortOrder ." limit ".$row.",".$rowperpage;
	// echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_payment")
{
    $req=$_REQUEST["req"];
    
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " 
	   (
	        payment_id like '%".$searchValue."%'
            OR payment_date like '%".$searchValue."%'
            OR payment_mode like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
            OR balance like '%".$searchValue."%'
	    )";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_payment`  ";
	if($searchValue!=''){
	    $sel_qry .= " WHERE ".$searchQuery;
	}
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_sales_payment`  ";
	if($searchValue!=''){
	    $data_sql .= " WHERE ".$searchQuery;
	}
    
	$data_sql.=" ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	//echo $data_sql;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="add_waiver")
{
    $waiver_date = $_POST['waiver_date'];
    $waiver_amount = $_POST['waiver_amount'];
    $sales_no_id = $_POST['sales_no_id'];
    
    $updated_date=date("Y-m-d");
    $waiver_qry="INSERT INTO sar_waiver SET
                waiver_date='$waiver_date',
                waiver_discount='$waiver_amount',
                updated_date='$updated_date',
                sales_no='$sales_no_id'
    ";
    $res1 = mysqli_query($con, $waiver_qry);
    
    $select_qry2 = "SELECT *, sum(bill_amount) as totalbillamount FROM sar_sales_invoice WHERE sales_no='$sales_no_id' GROUP BY sales_no ORDER BY id DESC ";
    $sel_sql2 = $connect->prepare($select_qry2);
    $sel_sql2->execute();
    $sel_row2 = $sel_sql2->fetch(PDO::FETCH_ASSOC);
    
    $select_qry1 = "SELECT *, sum(amount) as totalamount FROM sar_sales_payment WHERE customer_id='$sales_no_id' AND is_revoked is NULL GROUP BY customer_id ";
    $sel_sql1 = $connect->prepare($select_qry1);
    $sel_sql1->execute();
    $sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
    
     $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_no_id' GROUP BY sales_no";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
    
    if (($sel_row2['totalbillamount'] - $total_discount_on_sales) <= $sel_row1['totalamount']) {
        $date = date("Y/m/d");
        $UPDATE = "UPDATE `sar_sales_invoice` SET payment_status=1,updated_date='$updated_date' where sales_no ='$sales_no_id'";
        $UPDATE_sql = $connect->prepare($UPDATE);
        $UPDATE_sql->execute();
        
        $select_qry3 = "SELECT * FROM sar_sales_invoice WHERE sales_no='$sales_no_id'";
        $sel_sql3 = $connect->prepare($select_qry3);
        $sel_sql3->execute();
        $sel_row3 = $sel_sql3->fetchAll();

        foreach ($sel_row3 as $sel) {

            $add_sales_query = "INSERT INTO `sar_cash_carry` SET
               cash_no = '" . $sel['sales_no'] . "',
               date = '" . $sel['date'] . "',
               customer_name = '" . $sel['customer_name'] . "',
               mobile_number = '" . $sel['mobile_number'] . "',
               quality_name = '" . $sel['quality_name'] . "',
               quantity = '" . $sel['quantity'] . "',
               rate = '" . $sel['rate'] . "',
               bill_amount = '" . $sel['bill_amount'] . "',
               customer_id = '" . $sel['id'] . "',
               total_bill_amount = '" . $sel['total_bill_amount'] . "',
               payment = '" . $sel['payment_id'] . "',
               is_active=1
               ";
            $res = mysqli_query($con, $add_sales_query);
        }

    }
    $data = array();
    echo json_encode($data);
    //header("Location: /sar/view_sales_invoice.php");
}
else if($action=="update_report"){
	$patti_id = $_POST['patti_id'];
  
	$delete = "UPDATE `sar_patti` SET is_active=0 where patti_id ='$patti_id'";
	$delete_sql = $connect->prepare($delete);
	$delete_sql->execute();
}
else if($action=="update_reports"){
	$supname=$_POST['supname'];
		$delete1 = "UPDATE `sar_patti_payment` SET given=0 where supplier_id ='$supname'";
	$delete_sql1 = $connect->prepare($delete1);
	$delete_sql1->execute();
}
else if($action=="update_remain"){

	$remain = $_POST['remain'];
	$supname=$_POST['supname'];
	$delete1 = "UPDATE `sar_patti_payment` SET remain='$remain',given=0  where supplier_id ='$supname'";
	$delete_sql1 = $connect->prepare($delete1);
	$delete_sql1->execute();

}
else if($action=="add_waiver_stock")
{
    $waiver_date = $_POST['waiver_date'];
    $waiver_amount = $_POST['waiver_amount'];
    $purchase_id = $_POST['purchase_id'];
    
    $waiver_qry="INSERT INTO sar_waiver_pay SET
                waiver_date='$waiver_date',
                discount='$waiver_amount',
                purchase_id='$purchase_id'
    ";
    $res1 = mysqli_query($con, $waiver_qry);
    
    $total_qry = "SELECT *, sum(amount) as totalamount FROM sar_stock_payment WHERE purchase_id='$purchase_id' GROUP BY purchase_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);

    $open_amount_qry = "SELECT *, sum(stock_amount) as totalbillamount FROM sar_stock WHERE purchase_id='$purchase_id' GROUP BY purchase_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);
    
    $select_qry2="SELECT sum(discount) as discount FROM sar_waiver_pay WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
    

    if (($open_amount_row['totalbillamount'] - $total_discount_on_sales) <= $total_row['totalamount']) {
        // $date = date("Y/m/d");
        // $UPDATE = "UPDATE `sar_stock` SET payment_status=1 where purchase_id ='$purchase_id'";
        // $UPDATE_sql = $connect->prepare($UPDATE);
        // $UPDATE_sql->execute();

        $delete = "UPDATE `sar_stock` SET payment_status=1  where purchase_id ='$purchase_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        // $select_qry3 = "SELECT * FROM sar_stock WHERE purchase_id='$purchase_id'";
        // $sel_sql3 = $connect->prepare($select_qry3);
        // $sel_sql3->execute();
        // $sel_row3 = $sel_sql3->fetchAll();
    }
    $data = array();
    echo json_encode($data);
    //header("Location: /sar/view_sales_invoice.php");
}
else if($action=="add_waiver_return")
{
    $waiver_date = $_POST['waiver_return_date'];
    $waiver_amount = $_POST['discount_return'];
    $purchase_id = $_POST['return_id'];
    
    $waiver_qry="INSERT INTO sar_waiver_return SET
                waiver_return_date='$waiver_date',
                return_discount='$waiver_amount',
                purchase_id='$purchase_id'
    ";
    $res1 = mysqli_query($con, $waiver_qry);
    
    $total_qry = "SELECT *, sum(no_of_boxes) as totalquantity FROM sar_ob_return WHERE purchase_id='$purchase_id' GROUP BY purchase_id ";
    $total_sql = $connect->prepare($total_qry);
    $total_sql->execute();
    $total_row = $total_sql->fetch(PDO::FETCH_ASSOC);

    $open_amount_qry = "SELECT *, sum(quantity) as total_bill_quantity FROM sar_stock WHERE purchase_id='$purchase_id' GROUP BY purchase_id ORDER BY id DESC ";
    $open_amount_sql = $connect->prepare($open_amount_qry);
    $open_amount_sql->execute();
    $open_amount_row = $open_amount_sql->fetch(PDO::FETCH_ASSOC);
    
    $select_qry2="SELECT sum(return_discount) as discount FROM sar_waiver_return WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];

    if (($open_amount_row['total_bill_quantity'] - $total_discount_on_sales) <= $total_row['totalquantity']) {
        // $date = date("Y/m/d");
        // $UPDATE = "UPDATE `sar_stock` SET payment_status=1 where purchase_id ='$purchase_id'";
        // $UPDATE_sql = $connect->prepare($UPDATE);
        // $UPDATE_sql->execute();

        $delete = "UPDATE `sar_stock` SET return_status=1  where purchase_id ='$purchase_id'";

        $delete_sql = $connect->prepare($delete);

        $delete_sql->execute();

        // $select_qry3 = "SELECT * FROM sar_stock WHERE purchase_id='$purchase_id'";
        // $sel_sql3 = $connect->prepare($select_qry3);
        // $sel_sql3->execute();
        // $sel_row3 = $sel_sql3->fetchAll();
    }
    $data = array();
    echo json_encode($data);
    //header("Location: /sar/view_sales_invoice.php");
}
else if($action=="view_ob")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND
	   (
            date like '%".$searchValue."%'
            OR ob_supplier_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_ob_supplier` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_ob_supplier` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_ob_supplier` ";
    
	$data_sql .= " WHERE ".$searchQuery;
	
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
        $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_ob_payment where ob_supplier_id = '".$data_row["ob_supplier_id"]."' group by ob_supplier_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	
		$name="select * from sar_supplier where supplier_no='".$data_row["supplier_name"]."'";
		$exename= $connect->prepare($name);
	    $exename->execute();
	    $supname = $exename->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['contact_person'];

	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "ob_supplier_id"=>$data_row["ob_supplier_id"],
	        "date"=>$data_row["date"],
	        "supplier_id"=>$data_row["supplier_name"],
	        "supplier_name"=>$suppliername,
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_ob_customer")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND
	   (
            date like '%".$searchValue."%'
            OR ob_supplier_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	
// print_r($from);die();
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_opening_balance` ";
    
	$data_sql .= " WHERE ".$searchQuery;
	
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
        $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_ob_payment where ob_supplier_id = '".$data_row["ob_supplier_id"]."' group by ob_supplier_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];

		
		$name="select * from sar_customer where customer_no='".$data_row["customerid"]."'";
		$exename= $connect->prepare($name);
	    $exename->execute();
	    $supname = $exename->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['customer_name'];

	    
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "balance_id"=>$data_row["balance_id"],
	        "customer_id"=>$data_row["name"],
	        "customerid"=>$data_row["customerid"],
	        "date"=>$data_row["date"],
			"group_name"=>  $data_row["group_name"],
			"name"=>$suppliername,
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_ob_settled")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND
	   (
            date like '%".$searchValue."%'
            OR ob_supplier_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_ob_supplier` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_ob_supplier` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_ob_supplier` ";

	    $data_sql .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 	    	$rowIndex++;
// 		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_ob_settled_cus")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND
	   (
            date like '%".$searchValue."%'
            OR ob_supplier_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_opening_balance` ";

	    $data_sql .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 	    	$rowIndex++;
// 		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_cust_open_balance")
{
   
    $from=$_REQUEST["from_ob"];
    $to=$_REQUEST["to_ob"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND
	   (
            date like '%".$searchValue."%'
            OR balance_id like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_opening_balance` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_opening_balance` ";
    
	$data_sql .= " WHERE ".$searchQuery;
	
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
        $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_balance_payment where balance_id = '".$data_row["balance_id"]."' group by balance_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "balance_id"=>$data_row["balance_id"],
	        "date"=>$data_row["date"],
	        "name"=>$data_row["name"],
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_stock")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND return_status=0 AND
	   (
            date like '%".$searchValue."%'
            OR purchase_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR quality_name like '%".$searchValue."%'
            OR quantity like '%".$searchValue."%'
            OR rate like '%".$searchValue."%'
            OR stock_amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 AND return_status=0";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
    
	$sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
	
	$sel_qry .= " WHERE ".$searchQuery;
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_stock` ";

	$data_sql .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
        $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_stock_payment where purchase_id = '".$data_row["purchase_id"]."' group by purchase_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
	    $sel_qry3 = "SELECT  sum(no_of_boxes) as no_of_boxes from sar_ob_return where purchase_id = '".$data_row["purchase_id"]."' group by purchase_id ";
	    $data_qry3= $connect->prepare($sel_qry3);
	    $data_qry3->execute();
	    $data_row3 = $data_qry3->fetch(PDO::FETCH_ASSOC);
	    
        $select_qry2="SELECT sum(discount) as discount FROM sar_waiver_pay WHERE purchase_id='".$data_row["purchase_id"]."' GROUP BY purchase_id";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["stock_amount"] - $total_discount_on_sales - $data_row2["paid_amount"];
	    
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "purchase_id"=>$data_row["purchase_id"],
	        "no_of_boxes"=>$data_row3["no_of_boxes"],
	        "date"=>$data_row["date"],
	       "supplier_name"=>$data_row["supplier_name"],
	        "quality_name"=>$data_row["quality_name"],
	        "quantity"=>$data_row["quantity"],
	        "rate"=>$data_row["rate"],
	         "stock_amount"=>$data_row["stock_amount"],
	         "updated_by"=>$data_row["updated_by"]
	         
	    );
	}
    
	

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_stock_settled")
{
   
    $from=$_REQUEST["from_settled"];
    $to=$_REQUEST["to_settled"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND
	   (
            date like '%".$searchValue."%'
            OR purchase_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR quality_name like '%".$searchValue."%'
            OR quantity like '%".$searchValue."%'
            OR rate like '%".$searchValue."%'
            OR stock_amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;

    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];

    $data_sql = " SELECT * FROM `sar_stock` ";
	
	    $data_sql .= " WHERE ".$searchQuery;
     
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 	    	$rowIndex++;
// 		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
else if($action=="view_return_settled")
{
   
    $from=$_REQUEST["from_return"];
    $to=$_REQUEST["to_return"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " return_status=1 AND
	   (
            date like '%".$searchValue."%'
            OR purchase_id like '%".$searchValue."%'
            OR supplier_name like '%".$searchValue."%'
            OR quantity like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = "return_status=1";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
    
	    $sel_qry .= " WHERE ".$searchQuery;
	    
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = " SELECT count(*) as allcount FROM `sar_stock` ";
	
	    $sel_qry .= " WHERE ".$searchQuery;

    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];

    $data_sql = " SELECT * FROM `sar_stock` ";
	
	    $data_sql .= " WHERE ".$searchQuery;
     
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to')";   
    }
//	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	
  //  $data_sql.=" GROUP BY wastage_id";
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
// 	$rowIndex=$row;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
// 	    	$rowIndex++;
// 		$data_row["rowIndex"] = $rowIndex;
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}

else if($action=="patti_report"){
	$supname=$_REQUEST['sup_name'];
	$sql="select * from sar_patti where supplier_name='".$_REQUEST['sup_name']."' and is_active!=0 order by total_bill_amount desc";
	$exe=mysqli_query($con,$sql);
	$empdata=array();
	while($emp=mysqli_fetch_assoc($exe)){
		$empdata[] = $emp;
	}
	
	$sup="SELECT * FROM sar_patti_payment WHERE supplier_id='$supname' and balance!=0";
	$exesql=mysqli_query($con,$sup);
	$row=mysqli_fetch_assoc($exesql);
	
	$empdata[0]['pay']=$row;
	//print_r($empdata[0]['pay']['amount']);die();

	echo json_encode($empdata);
}
else if($action=="customer_report"){
	$cusname=$_REQUEST['cus_name'];
	$sql="select * from sar_sales_invoice where customer_name='".$_REQUEST['cus_name']."' and is_active!=0";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}
	
	$sup="SELECT * FROM sar_sales_payment WHERE customer_name='$cusname' and balance!=0";
	$exesql=mysqli_query($con,$sup);
	$row=mysqli_fetch_assoc($exesql);
	
	$cusdata[0]['pay']=$row;
	//print_r($empdata);die();

	echo json_encode($cusdata);
}

else if($action=="pat_list"){
	$supid=$_REQUEST['supplier_id'];
	$date=$_REQUEST['supplier_date'];
	$sql="select * from sar_patti where pat_id='$supid' and remain_box!=-2";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}

//print_r($cusdata);die();
echo json_encode($cusdata);
}

else if($action=="fetchgrp"){
	$grp=$_REQUEST['grp'];
	$sql="select * from sar_supplier where group_name='$grp'";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}

//print_r($cusdata);die();
echo json_encode($cusdata);
}

else if($action=="fetchsup"){
	$grp=$_REQUEST['grp'];
	$sql="select * from sar_customer where grp_cust_name='$grp'";
	$exe=mysqli_query($con,$sql);
	$cusdata=array();
	while($cus=mysqli_fetch_assoc($exe)){
		$cusdata[] = $cus;
	}

//print_r($cusdata);die();
echo json_encode($cusdata);
}
if($action=="revoke_interest_payment")
{
  if(isset($_REQUEST['id'])){
		$id=$_REQUEST["id"];
		$data_src=$_REQUEST["data_src"];
	//	$supplier_id=$_REQUEST["supplier_id"];
		
		$fetch_record = "SELECT * FROM `sar_interest_payment` WHERE id='".$id."' AND is_revoked is NULL";
		$sql_1 = $connect->prepare($fetch_record);
		$sql_1->execute();
		$data = array();
		while ($data_row = $sql_1->fetch(PDO::FETCH_ASSOC)) {
		   $data[] = $data_row;
		}
		$amount = $data[0]['amount'];
		$interest_id = $data[0]['interest_id'];
		if(count($data) != 0){
			$delete = "UPDATE `sar_interest_payment` SET is_revoked = 1 WHERE id='".$id."'";
			$sql_1 = $connect->prepare($delete);
			$sql_1->execute();
			
			$update = "UPDATE `sar_interest_payment` SET balance = balance + ".$amount." WHERE interest_id='".$interest_id."' and is_revoked is NULL and id > " . $id ;
			$sql_1 = $connect->prepare($update);
			$sql_1->execute();
			
			if($data_src=="settled"){
    			$update = "UPDATE `sar_interest` SET payment_status = 0 WHERE interest_id='".$interest_id."' and payment_status = 1";
    			$sql_1 = $connect->prepare($update);
    			$sql_1->execute();
			}
			
		}
		echo json_encode($data);
	}  
}
if($action=="revoke_finance_payment")
{
  if(isset($_REQUEST['id'])){
		$id=$_REQUEST["id"];
		$data_src=$_REQUEST["data_src"];
	//	$supplier_id=$_REQUEST["supplier_id"];
		
		$fetch_record = "SELECT * FROM `sar_finance_payment` WHERE id='".$id."' AND is_revoked is NULL";
		$sql_1 = $connect->prepare($fetch_record);
		$sql_1->execute();
		$data = array();
		while ($data_row = $sql_1->fetch(PDO::FETCH_ASSOC)) {
		   $data[] = $data_row;
		}
		$amount = $data[0]['amount'];
		$finance_id = $data[0]['finance_id'];
		if(count($data) != 0){
			$delete = "UPDATE `sar_finance_payment` SET is_revoked = 1 WHERE id='".$id."'";
			$sql_1 = $connect->prepare($delete);
			$sql_1->execute();
			
			$update = "UPDATE `sar_finance_payment` SET balance = balance + ".$amount." WHERE finance_id='".$finance_id."' and is_revoked is NULL and id > " . $id ;
			$sql_1 = $connect->prepare($update);
			$sql_1->execute();
			
			if($data_src=="settled"){
    			$update = "UPDATE `sar_finance` SET payment_status = 0 WHERE finance_id='".$finance_id."' and payment_status = 1";
    			$sql_1 = $connect->prepare($update);
    			$sql_1->execute();
			}
			
		}
		echo json_encode($data);
	}  
}
if($action=="view_interest")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	// $customer=$_REQUEST["customer"];
    // $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND
	   (
            date like '%".$searchValue."%'
            OR interest_id like '%".$searchValue."%'
            OR client_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_interest` ";
    
		$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $sel_qry .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $sel_qry .=" AND group_name='$grp'";   
    // }
	// else if($from!="" && $to!="" && $customer!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// else if($from!="" && $to!="" && $grp!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // else if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }
	
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	
	$sel_qry = " SELECT count(*) as allcount FROM `sar_interest` ";
	
	$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $sel_qry .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $sel_qry .=" AND group_name='$grp'";   
    // }
	// if($from!="" && $to!="" && $customer!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// if($from!="" && $to!="" && $grp!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  $sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_interest` ";
	
		$data_sql .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$data_sql .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $data_sql .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $data_sql .=" AND group_name='$grp'";   
    // }
	// if($from!="" && $to!="" && $customer!="")
	// {
	// 	$data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// if($from!="" && $to!="" && $grp!="")
	// {
	// 	$data_sql .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  $data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_interest_payment where interest_id = '".$data_row["interest_id"]."' AND is_revoked is NULL group by interest_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
			"payment_status"=>$data_row["payment_status"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "interest_id"=>$data_row["interest_id"],
	        "date"=>$data_row["date"],
	        "client_name"=>$data_row["client_name"],
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
if($action=="view_interest_settled")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	// $customer=$_REQUEST["customer"];
    // $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND
	   (
            date like '%".$searchValue."%'
            OR interest_id like '%".$searchValue."%'
            OR client_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_interest` ";
    
		$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $sel_qry .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $sel_qry .=" AND group_name='$grp'";   
    // }
	// else if($from!="" && $to!="" && $customer!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// else if($from!="" && $to!="" && $grp!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // else if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }
	
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	
	$sel_qry = " SELECT count(*) as allcount FROM `sar_interest` ";
	
	$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $sel_qry .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $sel_qry .=" AND group_name='$grp'";   
    // }
	// if($from!="" && $to!="" && $customer!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// if($from!="" && $to!="" && $grp!="")
	// {
	// 	$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  $sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_interest` ";
	
		$data_sql .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$data_sql .=" AND (date >='$from' AND date<='$to')";   
	}
	// else if($customer!="")
    // {
    //  $data_sql .=" AND customer_name='$customer'";   
    // }
	// else if($grp!="")
    // {
    //  $data_sql .=" AND group_name='$grp'";   
    // }
	// if($from!="" && $to!="" && $customer!="")
	// {
	// 	$data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	// }
	// if($from!="" && $to!="" && $grp!="")
	// {
	// 	$data_sql .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	// }
    // if($from!="" && $to!="" && $customer!="" && $grp!="")
    // {
    //  $data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    // }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_interest_payment where interest_id = '".$data_row["interest_id"]."' AND is_revoked is NULL group by interest_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
			"payment_status"=>$data_row["payment_status"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "interest_id"=>$data_row["interest_id"],
	        "date"=>$data_row["date"],
	        "client_name"=>$data_row["client_name"],
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
if($action=="view_finance")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	$customer=$_REQUEST["customer"];
    $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';

	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 0 AND
	   (
            date like '%".$searchValue."%'
            OR finance_id like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
			OR group_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_finance` ";
    
		$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	else if($customer!="")
    {
     $sel_qry .=" AND customer_name='$customer'";   
    }
	else if($grp!="")
    {
     $sel_qry .=" AND group_name='$grp'";   
    }
	else if($from!="" && $to!="" && $customer!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	}
	else if($from!="" && $to!="" && $grp!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	}
    else if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     	$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }
	
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	
	$sel_qry = " SELECT count(*) as allcount FROM `sar_finance` ";
	
	$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to')";   
	}
	else if($customer!="")
    {
     $sel_qry .=" AND customer_name='$customer'";   
    }
	else if($grp!="")
    {
     $sel_qry .=" AND group_name='$grp'";   
    }
	if($from!="" && $to!="" && $customer!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	}
	if($from!="" && $to!="" && $grp!="")
	{
		$sel_qry .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	}
    if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_finance` ";
	
		$data_sql .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
	{
		$data_sql .=" AND (date >='$from' AND date<='$to')";   
	}
	else if($customer!="")
    {
     $data_sql .=" AND customer_name='$customer'";   
    }
	else if($grp!="")
    {
     $data_sql .=" AND group_name='$grp'";   
    }
	if($from!="" && $to!="" && $customer!="")
	{
		$data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer'";   
	}
	if($from!="" && $to!="" && $grp!="")
	{
		$data_sql .=" AND (date >='$from' AND date<='$to') AND group_name='$grp'";   
	}
    if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     $data_sql .=" AND (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_finance_payment where finance_id = '".$data_row["finance_id"]."' AND is_revoked is NULL group by finance_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
			"payment_status"=>$data_row["payment_status"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "finance_id"=>$data_row["finance_id"],
	        "date"=>$data_row["date"],
	        "customer_name"=>$data_row["customer_name"],
	        "group_name"=>$data_row["group_name"],
	        "amount"=>$data_row["amount"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
if($action=="view_finance_settled")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	$customer=$_REQUEST["customer"];
    $grp=$_REQUEST["grp"];
    $draw = isset($_REQUEST['draw']) ? $_REQUEST['draw']: '';
	$row = isset($_REQUEST['start']) ? $_REQUEST['start']: '';
	$rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length']: '10';
	$columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column']: '';
	$columnName =    isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data']: 'ID';
	$columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir']: '';
	$searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value']: '';
	
	$searchQuery = " ";
	if($searchValue != ''){
	   $searchQuery = " payment_status = 1 AND
	   (
            date like '%".$searchValue."%'
            OR finance_id like '%".$searchValue."%'
            OR customer_name like '%".$searchValue."%'
			OR group_name like '%".$searchValue."%'
            OR amount like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1 ";
	}
    $sel_qry = " SELECT count(*) as allcount FROM `sar_finance` ";

		$sel_qry .= " WHERE ".$searchQuery;
    if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customer_name='$customer'";   
    }
	if($grp!="")
    {
     $sel_qry .=" AND group_name='$grp'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	
	$sel_qry = " SELECT count(*) as allcount FROM `sar_finance` ";
	
	$sel_qry .= " WHERE ".$searchQuery;
	
    if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     $sel_qry .=" WHERE (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customer_name='$customer'";   
    }
	if($grp!="")
    {
     $sel_qry .=" AND group_name='$grp'";   
    }
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT * FROM `sar_finance` ";
	
		$data_sql .= " WHERE ".$searchQuery;

    if($from!="" && $to!="" && $customer!="" && $grp!="")
    {
     $data_sql .=" WHERE (date >='$from' AND date<='$to') AND customer_name='$customer' AND group_name='$grp'";   
    }
	if($customer!="")
    {
     $data_sql .=" AND customer_name='$customer'";   
    }
	if($grp!="")
    {
     $data_sql .=" AND group_name='$grp'";   
    }
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();

	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$sel_qry2 = "SELECT  *,sum(amount) as paid_amount from sar_finance_payment where finance_id = '".$data_row["finance_id"]."' AND is_revoked is NULL group by finance_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        // $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	// $select_sql2=$connect->prepare($select_qry2);
    	// $select_sql2->execute();
    	// $total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	// $total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["amount"] - $data_row2["paid_amount"];
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "finance_id"=>$data_row["finance_id"],
	        "date"=>$data_row["date"],
	        "customer_name"=>$data_row["customer_name"],
	        "group_name"=>$data_row["group_name"],
	        "amount"=>$data_row["amount"],
			"payment_status"=>$data_row["payment_status"],
	        "updated_by"=>$data_row["updated_by"]
	    );
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
	);

	echo json_encode($response);
   
}
?>