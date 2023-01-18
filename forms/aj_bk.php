<?php
include("../include/config.php");

$action = $_REQUEST["action"];

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
	$sel_qry = "SELECT count(*) as allcount FROM `sar_supplier` ";
	
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


    $data_sql = "SELECT * FROM `sar_supplier` ";
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
                             description = 'Revoke of sales payment, ID: $customer_id',
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
	   $searchQuery = " payment_status = 1 AND 
	   (
            supplier_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR supplier_address like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 1 ";
	}
	
    $sel_qry = "SELECT count(*) as allcount FROM `sar_patti`  ";
    $sel_qry .= " WHERE ".$searchQuery;
    if($req=="enabled")
    {
        $sel_qry.=" AND is_active=1 ";
    }
    else if($req=="disabled")
    {
        $sel_qry.=" AND is_active=0 ";
    }
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
	}
	$sel_qry.=" GROUP BY patti_id";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti` ";
	 $sel_qry .= " WHERE ".$searchQuery;
	    
	if($req=="enabled")
    {
        $sel_qry.=" AND is_active=1 ";
    }
    else if($req=="AND disabled")
    {
        $sel_qry.=" is_active=0 ";
    }
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
	}
	    $sel_qry.=" GROUP BY patti_id";
	//}
    

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT *, SUM(bill_amount) AS totalbillamount FROM `sar_patti`  ";
	
	 $data_sql .= " WHERE ".$searchQuery;  
	if($req=="enabled")
    {
        $data_sql.=" AND is_active=1 ";
    }
    else if($req=="disabled")
    {
        $data_sql.=" AND is_active=0 ";
    }
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown[0]!="")
	{
	   $data_sql .= " AND (supplier_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
	}
	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   
	    $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
    	
    	$select_qry6 = "SELECT sum(amount) as paid FROM sar_patti_payment WHERE supplier_id='".$data_row["patti_id"]."' AND is_revoked is NULL GROUP BY supplier_id";
        
        $select_sql6 = $connect->prepare($select_qry6);
        $select_sql6->execute();
        $sel_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    
	    $data[]=array(
	        "id"=>$data_row["id"],
	        "paid"=>$sel_row6["paid"],
	         "farmer_name"=>$data_row["farmer_name"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "patti_date"=>$data_row["patti_date"],
	        "patti_id"=>$data_row["patti_id"],
	        "updated_by"=>$data_row["updated_by"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row["net_bill_amount"],
	       // "net_payable"=>$data_row["net_payable"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "totalbillamount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "inhand_sum"=>$total_sum
	         
	    );
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
	   $searchQuery = " payment_status = 0 AND 
	   (
            supplier_name like '%".$searchValue."%'
            OR mobile_number like '%".$searchValue."%'
            OR supplier_address like '%".$searchValue."%'
	    )";
	}else{
	    $searchQuery = " payment_status = 0 ";
	}
	
	
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti`  ";
	$sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown_settled[0]' AND mobile_number='$dropdown_settled[1]') ";
	}
	$sel_qry.=" GROUP BY patti_id";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti`  ";
	    $sel_qry .= " WHERE ".$searchQuery;
	if($from!="" && $to!="")
    {
     $sel_qry .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
	 if($dropdown_settled[0]!="")
	{
	   $sel_qry .= " AND (supplier_name='$dropdown_settled[0]' AND mobile_number='$dropdown_settled[1]') ";
	}   
    $sel_qry.=" GROUP BY patti_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT *, SUM(bill_amount) AS totalbillamount FROM `sar_patti`  ";
	
	    $data_sql .= " WHERE ".$searchQuery;  
	if($from!="" && $to!="")
    {
     $data_sql .=" AND (patti_date >='$from' AND patti_date<='$to')";   
    }
    if($dropdown_settled[0]!="")
	{
	   $data_sql .= " AND (supplier_name='$dropdown_settled[0]' AND mobile_number='$dropdown_settled[1]') ";
	}
	$data_sql.=" GROUP BY patti_id ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;  //GROUP BY sales_id 
	

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Supplier' AND name='".$data_row["supplier_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
	    $data[]=array(
	        "id"=>$data_row["id"],
	        "farmer_name"=>$data_row["farmer_name"],
	        "supplier_name"=>$data_row["supplier_name"],
	        "patti_date"=>$data_row["patti_date"],
	        "patti_id"=>$data_row["patti_id"],
	        "updated_by"=>$data_row["updated_by"],
	        "boxes_arrived"=>$data_row["boxes_arrived"],
	        "total_deduction"=>$data_row["total_deduction"],
	        "net_bill_amount"=>$data_row["net_bill_amount"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "totalbillamount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "inhand_sum"=>$total_sum
	         
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


    $data_sql = "SELECT (SELECT SUM(`total_trays`) FROM `sar_trays` ) as `total_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `supplier_trays_issued` ) as `supplier_tray`, (SELECT SUM(`no_of_trays_issued`) FROM `customer_trays_issued` ) as `customer_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_patti`) as `patti_tray`,(SELECT SUM(`boxes_arrived`) FROM `sar_sales_invoice`) as `sales_tray`,(SELECT SUM(no_of_trays_received) FROM `supplier_trays_received`) as `supplier_received`,(SELECT SUM(no_of_trays_issued) FROM `customer_trays_received`)as `customer_received`,(SELECT customer_tray +
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
     $sel_qry .=" AND (created_at >='$from' AND created_at<='$to')";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_wastage` ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (created_at >='$from' AND created_at<='$to')";   
    }
	 
    $sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_wastage`  ";
	
	 
    if($from!="" && $to!="")
    {
     $data_sql .=" AND (created_at >='$from' AND created_at<='$to')";   
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
	}//$sel_qry .="GROUP by name";
	
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
	//$data_sql .=" order by id desc limit 1";
		
	//$data_sql .="group by name,date order by id desc limit 1";
	//$data_sql.=" ORDER BY date ASC limit ".$row.",".$rowperpage;
	//print_r($data_sql);die();

	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
			$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
    	$select_qry6= "SELECT * FROM `sar_sales_invoice` WHERE customer_id='".$data_row["name"]."' group by customer_id" ;
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$customer_name=$select_row6["customer_name"];
        
        
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
	        "name"=>$customer_name,
	        "category"=>$data_row["category"],
	        "inward"=>$data_row["inward"],
	        "outward"=>$data_row["outward"],
	        "description"=>$data_row["description"],
	        "updated_by"=>$data_row["updated_by"],
	        "inhand"=>$data_row["inhand"],
	        "balance"=>$previous_days_balance_amount
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
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays`  ";
		 $counter = 0;
	if ($searchValue!='' || ($from!="" && $to!="")) {
		$sel_qry .= "  WHERE ";
	}
	if($searchValue!=''){
	    $sel_qry .= " ".$searchQuery;
	}
	if($from!="" && $to!="")
    {
		if($searchValue!=''){
			$sel_qry .= " AND ";
		}
     $sel_qry .=" date >='$from' AND date<='$to'";   
    }
	if($supplier!=''){
	    $sel_qry .= " AND name='$supplier'";
	}
	if($trays!=''){
	    $sel_qry .= " AND type='$trays'";
	}
//$data_sql .="GROUP BY name";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `trays` WHERE category='Supplier' ";
   
    $counter = 0;
	if ($searchValue!='' || ($from!="" && $to!="")) {
		$data_sql .= "  AND ";
	}
	if($searchValue!=''){
	    $data_sql .= " ".$searchQuery;
	}
	if($from!="" && $to!="")
    {
		if($searchValue!=''){
			$data_sql .= " AND ";
		}
     $data_sql .="(date >='$from' AND date<='$to')";   
    }
	if($supplier!=''){
	    $data_sql .= " AND name='$supplier'";
	}
	if($trays!=''){
	    $data_sql .= " AND type='$trays'";
	}
	//$data_sql .="GROUP BY name";
    
	$data_sql.=" ORDER BY id desc limit ".$row.",".$rowperpage;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		
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
	
	$sel_qry = "SELECT count(*) as allcount FROM `trays`  ";
		 $counter = 0;
	if ($searchValue!='' || ($from!="" && $to!="")) {
		$data_sql .= "  AND ";
	}
	if($searchValue!=''){
	    $data_sql .= " ".$searchQuery;
	}
	if($from!="" && $to!="")
    {
	if($searchValue!=''){
		$data_sql .= " AND ";
	}
     $data_sql .="date >='$from' AND date<='$to'";   
    }

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `trays` WHERE category='Trays'";
   
    $counter = 0;
	if ($searchValue!='' || ($from!="" && $to!="")) {
		$data_sql .= "  AND ";
	}
	if($searchValue!=''){
	    $data_sql .= " ".$searchQuery;
	}
	if($from!="" && $to!="")
    {
		if($searchValue!=''){
			$data_sql .= " AND ";
		}
     $data_sql .=" date >='$from' AND date<='$to'";   
    }
	//$data_sql .="GROUP BY name";
    
	$data_sql.=" ORDER BY date ASC limit ".$row.",".$rowperpage;
	
	// echo $data_sql;exit;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	
	$rowIndex = $row;
	$balanceTray = 0;
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
		
		$balanceTray = $balanceTray + $data_row["outward"]-$data_row["inward"] ;
		$data_row["inhand"] = $balanceTray;
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
	$from_all=$_GET["from_all"];
	$dropdown=$_REQUEST["dropdown"];
	$to_all=$_GET["to_all"];
	
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
	
        if($from_all!="" && $to_all!="")
    {
     $sel_qry .=" WHERE (date >='$from_all' AND date<='$to_all')";   
    }
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    	if($dropdown!="")
	{
	   $sel_qry .= " WHERE (category='$dropdown') ";
	}

    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
	
    $sel_qry = " SELECT count(*) as allcount FROM `trays` ";
	    if($from_all!="" && $to_all!="")
    {
     $sel_qry .=" WHERE (date >='$from_all' AND date<='$to_all')";   
    }
	if($searchValue!=''){
	    $sel_qry .= " AND ".$searchQuery;
	}
    	if($dropdown!="")
	{
	   $sel_qry .= " WHERE (category='$dropdown') ";
	}
	
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = " SELECT id,name,category FROM `trays` ";
   

	if($searchValue!=''){
	    $data_sql .= " AND ".$searchQuery;
	}
    	if($dropdown!="")
	{
	   $data_sql .= " WHERE (category='$dropdown') ";
	}
	$data_sql .="GROUP by name";
	$data_sql.=" ORDER BY date ASC limit ".$row.",".$rowperpage;
	
    
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	$rowIndex = $row;

	
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
		$rowIndex++;
		$data_row["rowIndex"] = $rowIndex;
    	if($data_row["category"] == 'Supplier'){
    	$select_qry4= "SELECT sum(outward) - sum(inward) as farmer_sum FROM `trays` WHERE name='".$data_row["name"]."' AND category='Supplier' group by name";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$farmer_sum=$select_row4["farmer_sum"];
    	$total_sum=$farmer_sum;
    	}
    	else if($data_row["category"] == 'Customer'){
    	$select_qry6= "SELECT sum(outward) - sum(inward) as customer_sum FROM `trays` WHERE name='".$data_row["name"]."' AND category='Customer' group by name";
	    $select_sql6=$connect->prepare($select_qry6);
    	$select_sql6->execute();
    	$select_row6 = $select_sql6->fetch(PDO::FETCH_ASSOC);
    	$customer_sum=$select_row6["customer_sum"];
    	$total_sum=$customer_sum;
    	}
	   $data[]=array(
	       "rowIndex"=>$data_row["rowIndex"],
	        "name"=>$data_row["name"],
	        "category"=>$data_row["category"],
	        "inhand"=>$total_sum
	    );
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
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_customer` ";
	
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


    $data_sql = " SELECT * FROM `sar_customer`  ";
    
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
	   $searchQuery = " payment_status = 0 AND 
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
	    $searchQuery = " payment_status = 0 ";
	}
	
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice`  ";
	$sel_qry .= " WHERE ".$searchQuery;
	if($req=="enabled")
    {
        $sel_qry.=" AND is_active=1 ";
    }
    else if($req=="disabled")
    {
        $sel_qry.=" AND is_active=0 ";
    }
    if($from!="" && $to!="")
    {
     $sel_qry .=" AND (date >='$from' AND date<='$to')";   
    }
    if($dropdown[0]!="")
	{
	   $sel_qry .= " AND (customer_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
	}
	$sel_qry.=" GROUP BY sales_no";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice`";
	    $sel_qry .= " WHERE ".$searchQuery;
	    
	    if($req=="enabled")
        {
            $sel_qry.=" AND is_active=1 ";
        }
        else if($req=="disabled")
        {
            $sel_qry.=" AND is_active=0 ";
        }
        if($from!="" && $to!="")
        {
         $sel_qry .=" AND (date >='$from' AND date<='$to')";   
        }
        if($dropdown[0]!="")
    	{
	    $sel_qry .= " AND (customer_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
    	}
	    $sel_qry.=" GROUP BY sales_no";
    	$sel_sql= $connect->prepare($sel_qry);
    	$sel_sql->execute();
    	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
    	$totalRecordwithFilter = $sel_row["allcount"];

    	$data_sql = "SELECT * FROM `sar_sales_invoice` ";
        $data_sql .= " WHERE ".$searchQuery; 
        if($req=="enabled")
        {
            $data_sql.=" AND is_active=1 ";
        }
        else if($req=="disabled")
        {
            $data_sql.=" AND is_active=0 ";
        }
        if($from!="" && $to!="")
        {
         $data_sql .=" AND (date >='$from' AND date<='$to')";   
        }
        if($dropdown[0]!="")
	    {
	    $data_sql .= " AND (customer_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ";
	    }
    	 
    	$data_sql.=" GROUP BY sales_no ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage; 
    	
    	$data_qry= $connect->prepare($data_sql);
    	$data_qry->execute();
    	
	
    	$data = array();
	    
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   
	    $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '".$data_row["sales_no"]."' AND is_revoked is NULL group by customer_id ";
	    $data_qry2= $connect->prepare($sel_qry2);
	    $data_qry2->execute();
	    $data_row2 = $data_qry2->fetch(PDO::FETCH_ASSOC);
	    
        $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='".$data_row["sales_no"]."' GROUP BY sales_no";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales_list = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	$total_discount_on_sales =  $total_discount_on_sales_list['discount'];
	
	    $balance = $data_row["total_bill_amount"] - $total_discount_on_sales - $data_row2["paid_amount"];
	    
	    $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
	    $data[]=array(
	        "balance"=>$balance,
	        "paid_amount"=>$data_row2["paid_amount"],
	        "id"=>$data_row["id"],
	        "date"=>$data_row["date"],
	       "amount"=>$data_row["amount"],
	        "customer_name"=>$data_row["customer_name"],
	        "sales_no"=>$data_row["sales_no"],
	        "updated_by"=>$data_row["updated_by"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "total_bill_amount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "waiver_discount"=>$total_discount_on_sales,
	         "inhand_sum"=>$total_sum
	         
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
    $is_active=$_REQUEST["is_active"];
    $cash_no=$_REQUEST["cash_no"];
    $from=$_REQUEST["from_cash"];
    $to=$_REQUEST["to_cash"];
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
	
    $sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry`  ";
    if($from!="" && $to!="")
    {
     //$sel_qry .=" (date >='$from' AND date<='$to')";  
     array_push($filter, " (date >='$from' AND date<='$to')");
    }
	
	if($req=="active")
    {
        //$sel_qry.=" WHERE is_active=1 AND";
        array_push($filter, "is_active=1");
    }
    else if($req=="inactive")
    {
        //$sel_qry.=" WHERE is_active=0 AND ";
        array_push($filter, "is_active=0");
    }
    if($dropdown[0]!="")
    	{
	     array_push($filter,"  (customer_name='$dropdown[0]' AND mobile_number='$dropdown[1]') ");
    	}
    $filter_str = join(" AND ",$filter);
        
	$sel_qry.= " WHERE " .$filter_str. " GROUP BY cash_no";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];

	$sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry` ";

    $sel_qry.=" WHERE " .$filter_str. " GROUP BY cash_no";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];

    $data_sql = " SELECT id,cash_no,date,updated_by,customer_name,total_bill_amount,is_active,mobile_number FROM sar_cash_carry ";
    
	$data_sql.=" WHERE " .$filter_str. "  GROUP BY cash_no ORDER BY ".$columnName." DESC limit ".$row.",".$rowperpage;
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	    $select_qry3= "SELECT sum(inward) as inward_sum FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql3=$connect->prepare($select_qry3);
    	$select_sql3->execute();
    	$select_row3 = $select_sql3->fetch(PDO::FETCH_ASSOC);
    	$inward_sum=$select_row3["inward_sum"];
    	
    	$select_qry4= "SELECT sum(outward) as outward_sum FROM `trays` WHERE category='Customer' AND name='".$data_row["customer_name"]."' ";
	    $select_sql4=$connect->prepare($select_qry4);
    	$select_sql4->execute();
    	$select_row4 = $select_sql4->fetch(PDO::FETCH_ASSOC);
    	$outward_sum=$select_row4["outward_sum"];
    	$total_sum=$outward_sum-$inward_sum;
        $data[] = array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "date"=>$data_row["date"],
	        "customer_name"=>$data_row["customer_name"],
	        "cash_no"=>$data_row["cash_no"],
	        "updated_by"=>$data_row["updated_by"],
	         "mobile_number"=>$data_row["mobile_number"],
	         "total_bill_amount"=>$data_row["total_bill_amount"],
	         "is_active"=>$data_row["is_active"],
	         "inhand_sum"=>$total_sum
	    );
	   
	}

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
else if($action=="view_cash_carry_all")
{
    $req=$_REQUEST["req"];
    $is_active=$_REQUEST["is_active"];
    $cash_no=$_REQUEST["cash_no"];
    $from_all=$_REQUEST["from_all"];
    $to_all=$_REQUEST["to_all"];
    $dropdown_all=$_REQUEST["dropdown_all"];
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
	   $searchQuery = "  payment_status=0 AND
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
	    $searchQuery = " payment_status = 0 ";
	}
	
    $sel_qry = " SELECT  count(*) as allcount FROM `sar_cash_carry`";
    if($from_all!="" && $to_all!="")
    {
     //$sel_qry .=" (date >='$from' AND date<='$to')";  
     array_push($filter, " (date >='$from_all' AND date<='$to_all') ");
    }
	
    $filter_str = join(" AND ",$filter);
    //$sel_qry1_test = $sel_qry ." WHERE " .$filter_str." GROUP BY cash_no";
    if ($filter_str != ""){
	    $sel_qry .= " WHERE " .$filter_str." GROUP BY cash_no";
    }
    
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
    $sel_qry2_test = "";
	$sel_qry = " SELECT count(*) as allcount FROM `sar_cash_carry`";
	
	//$sel_qry2_test = $sel_qry ." WHERE " .$filter_str." GROUP BY cash_no";
    if ($filter_str != ""){
        $sel_qry.=" WHERE " .$filter_str. " GROUP BY cash_no";
    }
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];
	
	
	if($dropdown_all!="")
	{
     array_push($filter," invoice.customer_name='$dropdown_all' ");
	}
    $filter_str = join(" AND ",$filter);
    
    $data_sql = "SELECT invoice.sales_no as sales_no,invoice.customer_name as name,invoice.date as date,invoice.total_bill_amount as bill_amount, sum(pay.amount) as payment FROM sar_sales_invoice as invoice, sar_sales_payment as pay Where invoice.sales_no = pay.customer_id and payment_status=0 and is_revoked is NULL GROUP By sales_no UNION SELECT ob.balance_id as balance_id,ob.name as name,ob.date as date,ob.amount as bill_amount,sum(bpay.amount) as payment FROM sar_opening_balance as ob,sar_balance_payment as bpay WHERE ob.balance_id = bpay.balance_id AND ob.balance_id LIKE '%COB%' AND payment_status=0 GROUP By bpay.balance_id";

    if ($filter_str != ""){
        $data_sql = "SELECT invoice.sales_no as sales_no,invoice.customer_name as name,invoice.date as date,invoice.total_bill_amount as bill_amount, sum(pay.amount) as payment FROM sar_sales_invoice as invoice, sar_sales_payment as pay Where ".$filter_str." AND invoice.sales_no = pay.customer_id and payment_status=0 and is_revoked is NULL GROUP By sales_no UNION SELECT ob.balance_id as balance_id,ob.name as name,ob.date as date,ob.amount as bill_amount,sum(bpay.amount) as payment FROM sar_opening_balance as ob,sar_balance_payment as bpay WHERE ".$filter_str." AND ob.balance_id = bpay.balance_id AND ob.balance_id LIKE '%COB%' AND payment_status=0 GROUP By bpay.balance_id";
    }
	
	$data_qry= $connect->prepare($data_sql);
	$data_qry->execute();
	
	$get_discount_sql = "Select sales_no,waiver_discount from sar_waiver";
	
	$get_discount_qry= $connect->prepare($get_discount_sql);
	$get_discount_qry->execute();
	
    // print_r($sales_no_discount);
    $sales_no_discount = array();
    while ($data_row = $get_discount_qry->fetch(PDO::FETCH_ASSOC)) {
        $sales_no_discount[$data_row['sales_no']] = $data_row['waiver_discount'];
    }
    
    $get_paymeny_sql = "select customer_id, sum(amount) as payment from sar_sales_payment where is_revoked is null group by customer_id";
    $get_paymeny_sql_qry= $connect->prepare($get_paymeny_sql);
	$get_paymeny_sql_qry->execute();
	
    // print_r($sales_no_discount);
    $customer_total_pay = array();
    while ($data_row = $get_paymeny_sql_qry->fetch(PDO::FETCH_ASSOC)) {
        $customer_total_pay[$data_row['customer_id']] = $data_row['payment'];
    }
	$data = array();
	while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   $data_row["waiver_discount"] = $sales_no_discount[$data_row["sales_no"]];
	   
	   //$data_row["payment"] = $customer_total_pay[$data_row['customer_id']];
	   
	   //$data_row["balance"] = $data_row["bill_amount"] - $data_row["waiver_discount"] - $data_row["payment"];
    //     $data_row["balance_pay"] = $data_row["bill_amount"] - $data_row["payment"];
	   
	   $data[] = $data_row;
	}

	$response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
        // "qry1" => $sel_qry1_test,
        // "qry2" => $sel_qry2_test,
        // "qry3" => $data_sql_test
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
	
    $sel_qry = " SELECT  count(*) as allcount FROM `sar_sales_invoice`";
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecords = $sel_row["allcount"];
    $sel_qry2_test = "";
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_invoice`";

	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];
	
    // $data_sql = "SELECT invoice.sales_no as id,invoice.customer_name,sum(invoice.bill_amount) as total_bill_amount,sum(pay.amount) as payment FROM `sar_sales_invoice` as invoice, `sar_sales_payment` as pay WHERE invoice.customer_name=pay.customer_name GROUP BY invoice.customer_name,pay.customer_name";
    $data_sql="SELECT sar_sales_invoice.sales_no,sar_sales_invoice.customer_name,sar_sales_invoice.total_bill_amount,sar_opening_balance.name,sar_opening_balance.amount,sar_opening_balance.balance_id FROM sar_sales_invoice INNER JOIN sar_opening_balance ON sar_opening_balance.name = sar_sales_invoice.customer_name";
    // $data_sql = "SELECT total_bill_amount,customer_name,sales_no,date FROM sar_sales_invoice WHERE payment_status=0";
    $data_qry=$connect->prepare($data_sql);
    $data_qry->execute();
    
while ($data_row = $data_qry->fetch(PDO::FETCH_ASSOC)) {
	   
	    $sel_qry2 = "SELECT  sum(amount) as paid_amount from sar_sales_payment where customer_id = '".$data_row["sales_no"]."' AND is_revoked is NULL group by customer_id ";
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
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND ids='$customer'";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND ids='$customer'";   
    }
//	print_r($sel_qry);die();

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
    $sel_qry = "SELECT count(*) as allcount FROM `sar_patti_payment`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND supplier_id='$supplier'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	//print_r($sel_row);die();
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_patti_payment`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND supplier_id='$supplier'";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_patti_payment`  ";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($supplier!="")
    {
     $data_sql .=" AND supplier_id='$supplier'";   
    }
//	print_r($sel_qry);die();

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

//	print_r( $data);die();
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
    $sel_qry = "SELECT count(*) as allcount FROM `sar_sales_payment`  ";
    
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customer_id='$customer'";   
    }
    $sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	//print_r($sel_row);die();
	$totalRecords = $sel_row["allcount"];
	
	//if($searchValue!=''){
	$sel_qry = "SELECT count(*) as allcount FROM `sar_sales_payment`  ";
	
    if($from!="" && $to!="")
    {
     $sel_qry .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($customer!="")
    {
     $sel_qry .=" AND customer_id='$customer'";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `sar_sales_payment`  ";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (payment_date >='$from' AND payment_date<='$to')";   
    }
	if($customer!="")
    {
     $data_sql .=" AND customer_id='$customer'";   
    }
//	print_r($sel_qry);die();

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

//	print_r( $data);die();
	echo json_encode($response);
   
}

else if($action=="view_datewise_report_supplier")
{
   
    $from=$_REQUEST["from"];
    $to=$_REQUEST["to"];
	$supplier=$_GET["supplier"];
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
     $sel_qry .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND ids='$supplier'";   
    }
	   // $sel_qry.=" GROUP BY patti_id";
	//}
    
    //$sel_qry.=" GROUP BY wastage_id";
	$sel_sql= $connect->prepare($sel_qry);
	$sel_sql->execute();
	$sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC);
	$totalRecordwithFilter = $sel_row["allcount"];


    $data_sql = "SELECT * FROM `financial_transactions`  ";
	
    if($from!="" && $to!="")
    {
     $data_sql .=" WHERE (date >='$from' AND date<='$to')";   
    }
	if($supplier!="")
    {
     $sel_qry .=" AND ids='$supplier'";   
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
	    
	    $data[]=array(
	        "balance"=>$balance,
	        "id"=>$data_row["id"],
	        "paid_amount"=>$data_row2["paid_amount"],
	        "ob_supplier_id"=>$data_row["ob_supplier_id"],
	        "date"=>$data_row["date"],
	        "supplier_name"=>$data_row["supplier_name"],
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

?>