<?php
include("../include/config.php");

$action = $_REQUEST["action"];

if($action=="view_interest_modal")
{
    $interest_id = $_REQUEST["interest_id"];
    $cash_qry="SELECT * FROM sar_interest WHERE interest_id='$interest_id'";
    // print_r($cash_qry);die();
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();
    // while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
    //     $fetch[]=array(
    //         "id"=>$cash_row["id"],
    //         "finance_id"=> $cash_row["finance_id"],
    //         "date"=>  $cash_row["date"],
    //         "amount"=>  $cash_row["amount"],
    //         "updated_by"=>  $cash_row["updated_by"]
    //     );
    // }
        $interest_id = $_REQUEST["interest_id"];
        $select_qry1="SELECT * FROM sar_interest_payment WHERE interest_id='$interest_id'";
        $select_sql1=$connect->prepare($select_qry1);
        $select_sql1->execute();
	
	
	    while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
            $fetch[]=array(
                "id"=>$select_fetch["id"],
                "payment_id"=> $select_fetch["payment_id"],
                "payment_date"=>  $select_fetch["payment_date"],
                "payment_mode"=>  $select_fetch["payment_mode"],
                "amount"=>  $select_fetch["amount"],
                "balance"=>  $select_fetch["balance"],
                "interest_id"=>  $select_fetch["interest_id"],
                "is_revoked"=> $select_fetch["is_revoked"]
            );
        }		
	echo json_encode($fetch);
}
if($action=="view_finance_modal")
{
    $finance_id = $_REQUEST["finance_id"];
    $cash_qry="SELECT * FROM sar_finance WHERE finance_id='$finance_id'";
    // print_r($cash_qry);die();
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();
    // while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
    //     $fetch[]=array(
    //         "id"=>$cash_row["id"],
    //         "finance_id"=> $cash_row["finance_id"],
    //         "date"=>  $cash_row["date"],
    //         "amount"=>  $cash_row["amount"],
    //         "updated_by"=>  $cash_row["updated_by"]
    //     );
    // }
        $finance_id = $_REQUEST["finance_id"];
        $select_qry1="SELECT * FROM sar_finance_payment WHERE finance_id='$finance_id'";
        $select_sql1=$connect->prepare($select_qry1);
        $select_sql1->execute();
	
	
	    while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
            $fetch[]=array(
                "id"=>$select_fetch["id"],
                "payment_id"=> $select_fetch["payment_id"],
                "payment_date"=>  $select_fetch["payment_date"],
                "payment_mode"=>  $select_fetch["payment_mode"],
                "amount"=>  $select_fetch["amount"],
                "balance"=>  $select_fetch["balance"],
                "finance_id"=>  $select_fetch["finance_id"],
                "is_revoked"=> $select_fetch["is_revoked"]
            );
        }	
    }	
if($action=="view_chit_modal")
{
    $chit_id = $_REQUEST["chit_id"];
    $cash_qry="SELECT * FROM chit WHERE chitid='$chit_id' ";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "chitid"=> $cash_row["chitid"],
            "chitname"=>  $cash_row["chitname"],
            "chitamt"=>  $cash_row["chitamt"],
            "chitdate"=>  $cash_row["chitdate"]
           
        );
    }
        	
	echo json_encode($fetch);
}
if($action=="view_patti_search")
{
   $contact_person = $_REQUEST["contact_person"];
   
    $loading_qry="SELECT * FROM sar_supplier WHERE contact_person='$contact_person'";
    $loading_sql=$connect->prepare($loading_qry);
    $loading_sql->execute();
	
    
    $loading_row = $loading_sql->fetch(PDO::FETCH_ASSOC);
        $fetch=array(
            "contact_person"=>$loading_row["contact_person"],
            "contact_number1"=>$loading_row["contact_number1"],
            "Address"=>$loading_row["Address"],
            "id"=>$loading_row["id"],
            "supplier_id"=>$loading_row["supplier_no"]
        );
    
    $response = array(
        "status" => 1,
        "data" => $fetch
	);

	echo json_encode($response);
}
else if($action=="view_group_dropdown")
{
    $group_name = $_REQUEST["group_name"];
    $loading_qry="SELECT id,contact_person FROM sar_supplier WHERE group_name='$group_name'";
    $loading_sql=$connect->prepare($loading_qry);
    $loading_sql->execute();
	
    $fetch=array();
    while($loading_row = $loading_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "contact_person"=>$loading_row["contact_person"],
            "id"=>$loading_row["id"]
        );
    }
    $response = array(
        "status" => 1,
        "data" => $fetch
	);

	echo json_encode($response);
}
else if($action=="view_loading_dropdown")
{
    $farmer_name = $_REQUEST["farmer_name"];
    $loading_qry="SELECT id,farmer_no FROM sar_farmer WHERE farmer_name='$farmer_name'";
    $loading_sql=$connect->prepare($loading_qry);
    $loading_sql->execute();
	
    $fetch=array();
    while($loading_row = $loading_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "farmer_no"=>$loading_row["farmer_no"],
            "id"=>$loading_row["id"]
        );
    }
    $response = array(
        "status" => 1,
        "data" => $fetch
	);

	echo json_encode($response);
}
else if($action=="view_contact_supplier")
{
    $contact_number1=$_REQUEST["contact_number1"];
    $sel_qry1 = "SELECT * FROM `sar_supplier` WHERE contact_number1='$contact_number1' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["contact_number1"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_contact_farmer")
{
    $farmer_contact_number=$_REQUEST["farmer_contact_number"];
    $sel_qry1 = "SELECT * FROM `sar_farmer` WHERE farmer_contact_number='$farmer_contact_number' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["farmer_contact_number"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_contact_customer")
{
    $contact_number1=$_REQUEST["contact_number1"];
    $sel_qry1 = "SELECT * FROM `sar_customer` WHERE contact_number1='$contact_number1' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["contact_number1"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_name_customer")
{
    $customer_name=$_REQUEST["customer_name"];
    $sel_qry1 = "SELECT * FROM `sar_customer` WHERE customer_name='$customer_name' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["customer_name"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_name_supplier")
{
    $contact_person=$_REQUEST["contact_person"];
    $sel_qry1 = "SELECT * FROM `sar_supplier` WHERE contact_person='$contact_person' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["contact_person"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_name_tray_farmer")
{
    $supplier_name=$_REQUEST["supplier_name"];
    $sel_qry1 = "SELECT * FROM `supplier_trays_received` WHERE supplier_name='$supplier_name' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["supplier_name"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_name_farmer")
{
    $farmer_name=$_REQUEST["farmer_name"];
    $sel_qry1 = "SELECT * FROM `sar_farmer` WHERE farmer_name='$farmer_name' ";
                  	//echo $sel_qry1;exit;
                  	$sel_sql1= $connect->prepare($sel_qry1);
        	$sel_sql1->execute();
        	$sel_rows=$sel_sql1->rowCount();
        	$sel_row1 = $sel_sql1->fetch(PDO::FETCH_ASSOC);
        	$mobile_check = $sel_row1["farmer_name"];
    if($mobile_check==""){
        $response = array(
            "status" => 1,
            "msg" => "available"
    	);
    } else {
        $response = array(
            "status" => 1,
            "msg" => "alreadyexist"
    	);
    }
	echo json_encode($response);    	
        	
}
else if($action=="view_customer_search")
{
    $customer_name = $_REQUEST["customer_name"];
    $load_qry="SELECT * FROM sar_sales_invoice WHERE customer_name like %$customer_name%";
    $load_sql=$connect->prepare($load_qry);
    $load_sql->execute();
    $fetch=array();
    while( $load_row = $load_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=$load_row;
    }
    $response = array(
        "status" => 1,
        "data" => $fetch["cust_name"]
	);

	echo json_encode($response);
}
else if($action=="view_customer_searchs")
{
    $customer_name = $_REQUEST["customer_name"];
    $load_qry="SELECT * FROM sar_customer WHERE customer_name like %$customer_name%";
    $load_sql=$connect->prepare($load_qry);
    $load_sql->execute();
    $fetch=array();
    while( $load_row = $load_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=$load_row;
    }
    $response = array(
        "status" => 1,
        "data" => $fetch["cust_name"]
	);

	echo json_encode($response);
}
else if($action=="view_patti")
{
    $id = $_REQUEST["id"];
    $load_qry="SELECT * FROM sar_patti WHERE id=$id";
    $load_sql=$connect->prepare($load_qry);
    $load_sql->execute();
    $load_row = $load_sql->fetch(PDO::FETCH_ASSOC);
    $response = array(
        "status" => 1,
        "data" => $load_row
	);

	echo json_encode($response);
}
else if($action=="view_patti_nullify")
{
    $id = $_REQUEST["id"];
    $load_qry="SELECT * FROM sar_patti_nullify_records WHERE id=$id";
    $load_sql=$connect->prepare($load_qry);
    $load_sql->execute();
    $load_row = $load_sql->fetch(PDO::FETCH_ASSOC);
    $response = array(
        "status" => 1,
        "data" => $load_row
	);

	echo json_encode($response);
}
else if($action=="view_supplier_modal")
{
    $group_name = $_REQUEST["group_name"];
    $cash_qry="SELECT * FROM sar_supplier WHERE group_name='$group_name'";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();

    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "group_no"=> $cash_row["group_id"],
            "group_name"=>  $cash_row["group_name"],
            "supplier_no"=>  $cash_row["supplier_no"],
            "contact_person"=>  $cash_row["contact_person"],
            "contact_number1"=>  $cash_row["contact_number1"],
            "Address"=>  $cash_row["Address"],
            // "is_active"=>  $cash_row["is_active"]
           
        );
    }
        	
	echo json_encode($fetch);
}
else if($action=="view_customer_modal")
{
    $group_name = $_REQUEST["grp_cust_name"];
    $cash_qry="SELECT * FROM sar_customer WHERE grp_cust_name='$group_name'";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();

    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "grp_cust_id"=> $cash_row["grp_cust_id"],
            "grp_cust_name"=>  $cash_row["grp_cust_name"],
            "customer_no"=>  $cash_row["customer_no"],
            "customer_name"=>  $cash_row["customer_name"],
            "contact_number1"=>  $cash_row["contact_number1"],
            "address"=>  $cash_row["address"],
            // "is_active"=>  $cash_row["is_active"]
           
        );
    }
        	
	echo json_encode($fetch);
}

else if($action=="view_open_balance_modal")
{
	
    $balance_id = $_REQUEST["balance_id"];
    $quotation_qry="SELECT * FROM sar_opening_balance WHERE balance_id='$balance_id' AND category='Customer'";
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    $fetch=array();
    $amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "balance_id"=> $quotation_row["balance_id"],
            "group_name"=>  $quotation_row["group_name"],
            "name"=>  $quotation_row["name"],
            "category"=>  $quotation_row["category"],
            "date"=>  $quotation_row["date"],
            "amount"=>  $quotation_row["amount"]
        );
         $amount=$quotation_row["amount"];
    }
    $balance_id = $_REQUEST["balance_id"];
	$select_qry1="SELECT * FROM sar_balance_payment WHERE balance_id='$balance_id' order by id";
	$select_sql1=$connect->prepare($select_qry1);
	$select_sql1->execute();
	//$balance=0;
	   while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
	       //$balance = $amount - $select_fetch["amount"];
         $fetch['cash'][]=array(
             "id"=>$select_fetch["id"],
             "payment_id"=> $select_fetch["payment_id"],
             "payment_date"=>  $select_fetch["payment_date"],
             "payment_mode"=>  $select_fetch["payment_mode"],
             "open_balance_amount"=>  $select_fetch["amount"],
              "balance"=>  $select_fetch["balance"],
             "balance_id"=>  $select_fetch["balance_id"]
             //"is_revoked"=>  $select_fetch["is_revoked"]
         );
         
    }	
	echo json_encode($fetch);
}
else if($action=="view_sales_modal")
{
	
    // $customer_name = $_REQUEST["customer_name"];
    // $customer_id = $_REQUEST["customer_id"];
    $sales_id = $_REQUEST["customer_id"];
    $saleid = $_REQUEST["saleid"];
    //customer_date
    $date = $_REQUEST["customer_date"];
    // $date = $_REQUEST["date"];
    // print_r($saleid);die();
    $quotation_qry="SELECT * FROM sar_sales_invoice WHERE customer_id='$sales_id' and sale_id='$saleid'";
    //$quotation_qry="SELECT * FROM thai_quotation ";
    // print_r($quotation_qry);die();
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    
// $quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC);
    // print_r($quotation_row);die();
    $fetch=array();
    // $fetch[]=array("name" => $customer_name,
    // "query" =>$quotation_qry
    // );
    $total_bill_amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "rate"=> $quotation_row["rate"],
            "customer_address"=>  $quotation_row["customer_address"],
            "mobile_number"=>  $quotation_row["mobile_number"],
            "customer_name"=>  $quotation_row["customer_name"],
            "date"=>  $quotation_row["date"],
            "sales_no"=>  $quotation_row["sales_no"],
            "boxes_arrived"=>  $quotation_row["boxes_arrived"],
            "quality_name"=>  $quotation_row["quality_name"],
            "quantity"=>  $quotation_row["quantity"],
            "bill_amount"=>  $quotation_row["bill_amount"],
            "total_bill_amount"=>  $quotation_row["total_bill_amount"],
            "tray_pend"=>  $quotation_row["tray_pend"]
        );
        $total_bill_amount =  $quotation_row["total_bill_amount"];
    }
	
	
	 $customer_id = $_REQUEST["customer_id"];
	 
    $select_qry2="SELECT sum(waiver_discount) as discount FROM sar_waiver WHERE sales_no='$sales_id' GROUP BY sales_no";
	$select_sql2=$connect->prepare($select_qry2);
	$select_sql2->execute();
	$total_discount_on_sales = $select_sql2->fetch(PDO::FETCH_ASSOC);
	$fetch['total_discount_on_sales'] =  $total_discount_on_sales['discount']?$total_discount_on_sales['discount'] : 0;
	
	$select_qry1="SELECT * FROM sar_sales_payment WHERE customer_id='$sales_id' order by id";
	$select_sql1=$connect->prepare($select_qry1);
	$select_sql1->execute();
	
	$total_payment = 0;
	   while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
	       if(!$select_fetch["is_revoked"]){
	           $total_payment = $total_payment + $select_fetch["amount"];
	       }
	   
         $fetch['cash'][]=array(
             "id"=>$select_fetch["id"],
             "payment_id"=> $select_fetch["payment_id"],
             "payment_date"=>  $select_fetch["payment_date"],
             "payment_mode"=>  $select_fetch["payment_mode"],
             "amount"=>  $select_fetch["amount"],
             "balance"=>  $total_bill_amount - $fetch['total_discount_on_sales'] - $total_payment,
             "customer_id"=>  $select_fetch["customer_id"],
             "is_revoked"=>  $select_fetch["is_revoked"]
         );
         
     }	

	
	echo json_encode($fetch);
}
else if($action=="view_cash_modal")
{
    $cash_no = $_REQUEST["cash_no"];
    $saleid = $_REQUEST["saleid"];
     $cash_qry="SELECT * FROM sar_cash_carry WHERE cash_no='$cash_no' and saleid='$saleid'";
    //$quotation_qry="SELECT * FROM thai_quotation ";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
//     $quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC);
//     $response = array(
//         "status" => 1,
//         "data" => $quotation_row
// 	);
if($saleid!="")
{$cashqry="SELECT * FROM sar_sales_invoice WHERE sale_id='$saleid'";
//$quotation_qry="SELECT * FROM thai_quotation ";
$cashsql=$connect->prepare($cashqry);
$cashsql->execute();
// $cashrow = $cash_sql->fetch(PDO::FETCH_ASSOC);
    $fetch=array();
    // $fetch[]=array("name" => $customer_name,
    // "query" =>$quotation_qry
    // );
    while($cash_row = $cashsql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "rate"=> $cash_row["rate"],
            "date"=>  $cash_row["date"],
            "cash_no"=>  $cash_no,
            "quality_name"=>  $cash_row["quality_name"],
            "quantity"=>  $cash_row["quantity"],
            "bill_amount"=>  $cash_row["bill_amount"],
            "total_bill_amount"=>  $cash_row["total_bill_amount"]
        );
    }
}
else{
    $cashqry="SELECT * FROM sar_cash_carry WHERE cash_no='$cash_no'";
//$quotation_qry="SELECT * FROM thai_quotation ";
$cashsql=$connect->prepare($cashqry);
$cashsql->execute();
// $cashrow = $cash_sql->fetch(PDO::FETCH_ASSOC);
    $fetch=array();
    // $fetch[]=array("name" => $customer_name,
    // "query" =>$quotation_qry
    // );
    while($cash_row = $cashsql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "rate"=> $cash_row["rate"],
            "date"=>  $cash_row["date"],
            "cash_no"=>  $cash_no,
            "quality_name"=>  $cash_row["quality_name"],
            "quantity"=>  $cash_row["quantity"],
            "bill_amount"=>  $cash_row["bill_amount"],
            "total_bill_amount"=>  $cash_row["total_bill_amount"]
        );
    }
}
	echo json_encode($fetch);
}
else if($action=="view_stock_payment_modal")
{
    $purchase_id = $_REQUEST["purchase_id"];
    $quotation_qry="SELECT * FROM sar_stock WHERE purchase_id='$purchase_id'";
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    $select_qry2="SELECT sum(discount) as discount FROM sar_waiver_pay WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	
    	$fetch['total_discount_on_sales'] =  $total_discount_on_sales['discount']?$total_discount_on_sales['discount'] : 0; 
    $fetch=array();
    $amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){
        
        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "date"=> $quotation_row["date"],
            "purchase_id"=> $quotation_row["purchase_id"],
            "group_name"=>  $quotation_row["group_name"],
            "supplier_name"=>  $quotation_row["supplier_name"],
            "quality_name"=>  $quotation_row["quality_name"],
            "quantity"=>  $quotation_row["quantity"],
            "rate"=>  $quotation_row["rate"],
            "stock_amount"=>  $quotation_row["stock_amount"],
            "total_discount"=>  $total_discount_on_sales['discount'],
            "total_amount"=>  $quotation_row["stock_amount"] - $total_discount_on_sales['discount']
        );
         $amount=$quotation_row["stock_amount"];
    }
    
    
    	
    $purchase_id = $_REQUEST["purchase_id"];
     $cash_qry="SELECT * FROM sar_stock_payment WHERE purchase_id='$purchase_id' order by id";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['cash'][]=array(
            "id"=>$cash_row["purchase_id"],
            "payment_id"=> $cash_row["payment_id"],
            "payment_date"=>  $cash_row["payment_date"],
            "payment_mode"=>  $cash_row["payment_mode"],
            "amount"=>  $cash_row["amount"],
            "balance"=>  $cash_row["balance"] - $total_discount_on_sales['discount']
        );
    }
	echo json_encode($fetch);
}
else if($action=="view_ob_return_modal")
{
    $purchase_id = $_REQUEST["purchase_id"];
    $quotation_qry="SELECT * FROM sar_stock WHERE purchase_id='$purchase_id'";
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    $select_qry2="SELECT sum(return_discount) as discount FROM sar_waiver_return WHERE purchase_id='$purchase_id' GROUP BY purchase_id";
    	$select_sql2=$connect->prepare($select_qry2);
    	$select_sql2->execute();
    	$total_discount_on_sales = $select_sql2->fetch(PDO::FETCH_ASSOC);
    	
    	$fetch['total_discount_on_sales'] =  $total_discount_on_sales['discount']?$total_discount_on_sales['discount'] : 0; 
 	 
        $fetch=array();
    $amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "date"=> $quotation_row["date"],
            "purchase_id"=> $quotation_row["purchase_id"],
            "group_name"=>  $quotation_row["group_name"],
            "supplier_name"=>  $quotation_row["supplier_name"],
            "quality_name"=>  $quotation_row["quality_name"],
            "quantity"=>  $quotation_row["quantity"],
            "rate"=>  $quotation_row["rate"],
            "stock_amount"=>  $quotation_row["stock_amount"],
            "total_discount"=>  $total_discount_on_sales['discount'],
            "total_quantity"=>  $quotation_row["quantity"] - $total_discount_on_sales['discount']
        );
         $amount=$quotation_row["stock_amount"];
    }
    $purchase_id = $_REQUEST["purchase_id"];
     $cash_qry="SELECT * FROM sar_ob_return WHERE purchase_id='$purchase_id' order by id";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['return'][]=array(
            "id"=>$cash_row["purchase_id"],
            "return_id"=> $cash_row["return_id"],
            "return_date"=>  $cash_row["return_date"],
            "no_of_boxes"=>  $cash_row["no_of_boxes"],
            "balance"=>  $cash_row["balance"] - $total_discount_on_sales['discount']
        );
    }
	echo json_encode($fetch);
}
else if($action=="view_ob_payment_modal")
{
    $ob_supplier_id = $_REQUEST["ob_supplier_id"];
    $quotation_qry="SELECT * FROM sar_ob_supplier WHERE ob_supplier_id='$ob_supplier_id'";
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    $fetch=array();
    $amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){

        
		$name="select * from sar_supplier where supplier_no='".$quotation_row["supplier_name"]."'";
		$exename= $connect->prepare($name);
	    $exename->execute();
	    $supname = $exename->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['contact_person'];

        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "date"=> $quotation_row["date"],
            "ob_supplier_id"=> $quotation_row["ob_supplier_id"],
            "group_name"=>  $quotation_row["group_name"],
            "supplier_name"=>  $suppliername,
            "amount"=>  $quotation_row["amount"]
        );
         $amount=$quotation_row["amount"];
    }
    $ob_supplier_id = $_REQUEST["ob_supplier_id"];
     $cash_qry="SELECT * FROM sar_ob_payment WHERE ob_supplier_id='$ob_supplier_id' order by id";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['cash'][]=array(
            "ob_supplier_id"=>$cash_row["ob_supplier_id"],
            "payment_id"=> $cash_row["payment_id"],
            "payment_date"=>  $cash_row["payment_date"],
            "payment_mode"=>  $cash_row["payment_mode"],
            "amount"=>  $cash_row["amount"],
            "balance"=>  $cash_row["balance"]
        );
    }
	echo json_encode($fetch);
}
else if($action=="view_ob_payment_modal1")
{
    $ob_supplier_id = $_REQUEST["balance_id"];
    $quotation_qry="SELECT * FROM sar_opening_balance WHERE balance_id='$ob_supplier_id'";
    $quotation_sql=$connect->prepare($quotation_qry);
    $quotation_sql->execute();
    $fetch=array();
    $amount = 0;
    while($quotation_row = $quotation_sql->fetch(PDO::FETCH_ASSOC)){

        $name="select * from sar_customer where customer_no='".$quotation_row["name"]."'";
		$exename= $connect->prepare($name);
	    $exename->execute();
	    $supname = $exename->fetch(PDO::FETCH_ASSOC);
		$suppliername=$supname['customer_name'];


        $fetch['data'][] =array(
            "id"=>$quotation_row["id"],
            "date"=> $quotation_row["date"],
            "balance_id"=> $quotation_row["balance_id"],
            "group_name"=>  $quotation_row["group_name"],
            "name"=>  $suppliername,
            "amount"=>  $quotation_row["amount"]
        );
         $amount=$quotation_row["amount"];
    }
    $ob_supplier_id = $_REQUEST["balance_id"];
     $cash_qry="SELECT * FROM sar_ob_payment WHERE ob_supplier_id='$ob_supplier_id' order by id";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch['cash'][]=array(
            "ob_supplier_id"=>$cash_row["ob_supplier_id"],
            "payment_id"=> $cash_row["payment_id"],
            "payment_date"=>  $cash_row["payment_date"],
            "payment_mode"=>  $cash_row["payment_mode"],
            "amount"=>  $cash_row["amount"],
            "balance"=>  $cash_row["balance"]
        );
    }
	echo json_encode($fetch);
}
else if($action=="view_sales_search")
{
    $customer_name = $_REQUEST["customer_name"];
    $sales_invoice_qry="SELECT * FROM sar_customer WHERE customer_name='$customer_name'";
    $sales_invoice_sql=$connect->prepare($sales_invoice_qry);
    $sales_invoice_sql->execute();
    $sales_invoice_row = $sales_invoice_sql->fetch(PDO::FETCH_ASSOC);
    $fetch=array(
        
            "grp_cust_name"=>$sales_invoice_row["grp_cust_name"],
            "customer_name"=>$sales_invoice_row["customer_name"],
            "mobile_number"=>$sales_invoice_row["contact_number1"],
            "customer_address"=>$sales_invoice_row["address"],
            "id"=>$sales_invoice_row["id"],
            "customer_id"=>$sales_invoice_row["customer_no"]
        );
    
    $response = array(
        "status" => 1,
        "data" => $fetch
	);

	echo json_encode($response);
}
else if($action=="view_patti_modal")
{
    $patti_id = $_REQUEST["patti_id"];
    $patdate = $_REQUEST["patdate"];
    $supid = $_REQUEST["supplier_id"];
    $patid = $_REQUEST["patid"];
    $farmer = $_REQUEST["farmer"];
    $cash_qry="SELECT * FROM sar_patti WHERE supplier_id='$supid' and pat_id='$patid' and farmer_name='$farmer'";
    // print_r($cash_qry);die();
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
         $fetch[]=array(
            "id"=>$cash_row["id"],
            "rate"=> $cash_row["rate"],
            "patti_date"=>  $cash_row["patti_date"],
            "patti_id"=>  $cash_row["patti_id"],
            "supplier_name"=>  $cash_row["supplier_name"],
            "supplier_address"=>  $cash_row["supplier_address"],
            "mobile_number"=>  $cash_row["mobile_number"],
            "lorry_no"=>  $cash_row["lorry_no"],
            "quality_name"=>  $cash_row["quality_name"],
            "quantity"=>  $cash_row["quantity"],
            "bill_amount"=>  $cash_row["bill_amount"],
            "boxes_arrived"=>  $cash_row["boxes_arrived"],
            "total_bill_amount"=>  $cash_row["total_bill_amount"],
            "net_bill_amount"=>  $cash_row["net_bill_amount"],
            "net_payable"=>  $cash_row["net_payable"],
            "commision"=>  $cash_row["commision"],
            "f"=>  $cash_row["f"],
            "lorry_hire"=>  $cash_row["lorry_hire"],
            "cooli"=>  $cash_row["cooli"],
            "box_charge"=>  $cash_row["box_charge"],
            "total_deduction"=>  $cash_row["total_deduction"]
        );
    }
        $supplier_id = $_REQUEST["supplier_id"];
        $select_qry1="SELECT * FROM sar_patti_payment WHERE supplier_id='$patti_id'";
        $select_sql1=$connect->prepare($select_qry1);
        $select_sql1->execute();
	
	
	   while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
         $fetch[]=array(
             "id"=>$select_fetch["id"],
             "payment_id"=> $select_fetch["payment_id"],
             "payment_date"=>  $select_fetch["payment_date"],
             "payment_mode"=>  $select_fetch["payment_mode"],
             "amount"=>  $select_fetch["amount"],
             "balance"=>  $select_fetch["balance"],
             "supplier_id"=>  $select_fetch["supplier_id"],
             "is_revoked"=> $select_fetch["is_revoked"]
         );
     }		
	echo json_encode($fetch);
}
else if($action=="view_patti_insert")
{
    $patti_id = $_REQUEST["patti_id"];
    $cash_qry="SELECT * FROM sar_patti WHERE patti_id='$patti_id'";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();
    $id=$_REQUEST["id"];
            $rate= $_REQUEST["rate"];
            $patti_date=  $_REQUEST["patti_date"];
            $patti_id=  $_REQUEST["patti_id"];
            $supplier_name=  $_REQUEST["supplier_name"];
            $supplier_address=  $_REQUEST["supplier_address"];
            $mobile_number=  $_REQUEST["mobile_number"];
            $lorry_no=  $_REQUEST["lorry_no"];
            $quality_name=  $_REQUEST["quality_name"];
            $quantity=  $_REQUEST["quantity"];
            $bill_amount=  $_REQUEST["bill_amount"];
            $boxes_arrived=  $_REQUEST["boxes_arrived"];
            $total_bill_amount=  $_REQUEST["total_bill_amount"];
            $net_bill_amount=  $_REQUEST["net_bill_amount"];
            $commision=  $_REQUEST["commision"];
            $lorry_hire=  $_REQUEST["lorry_hire"];
            $cooli=  $_REQUEST["cooli"];
            $box_charge=  $_REQUEST["box_charge"];
            $total_deduction=  $_REQUEST["total_deduction"];
    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
            $insert_row = "INSERT INTO `sar_patti_nullify_records`  set 
            patti_id='$patti_id', 
            patti_date='$patti_date', 
            supplier_name='$supplier_name',
            supplier_address='$supplier_address',
            mobile_number='$mobile_number',
            lorry_no='$lorry_no',
            quality_name='$quality_name',
            quantity='$quantity',
            bill_amount='$bill_amount',
            boxes_arrived='$boxes_arrived',
            total_bill_amount='$total_bill_amount',
            commision='$commision',
            lorry_hire='$lorry_hire',
            cooli='$cooli',
            box_charge=$box_charge,
            total_deduction=$total_deduction
            ";
             $insert_sql=$connect->prepare($insert_row);
        $insert_sql->execute();
	

    }
        $supplier_id = $_REQUEST["supplier_id"];
        $select_qry1="SELECT * FROM sar_patti_payment WHERE supplier_id='$patti_id'";
        $select_sql1=$connect->prepare($select_qry1);
        $select_sql1->execute();
	
	
	   while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
         $fetch[]=array(
             "id"=>$select_fetch["id"],
             "payment_id"=> $select_fetch["payment_id"],
             "payment_date"=>  $select_fetch["payment_date"],
             "payment_mode"=>  $select_fetch["payment_mode"],
             "amount"=>  $select_fetch["amount"],
             "balance"=>  $select_fetch["balance"],
             "supplier_id"=>  $select_fetch["supplier_id"],
             "is_revoked"=> $select_fetch["is_revoked"]
         );
     }		
	echo json_encode($fetch);
}
else if($action=="view_wastage_modal")
{
    $wastage_id = $_REQUEST["wastage_id"];
    $cash_qry="SELECT * FROM sar_wastage WHERE wastage_id='$wastage_id'";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();

    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "wastage_id"=>$cash_row["wastage_id"],
            "created_at"=>$cash_row["created_at"],
            "upated_by"=>$cash_row["updated_by"],
            "quality_name"=>$cash_row["quality_name"],
            "quantity"=>$cash_row["quantity"],
            "total_quantity"=>$cash_row["total_quantity"]
            
        );
    }
        
	echo json_encode($fetch);
}
else if($action=="view_patti_settled_modal")
{
    $patti_id = $_REQUEST["patti_id"];
    $cash_qry="SELECT * FROM sar_patti WHERE patti_id='$patti_id'";
    $cash_sql=$connect->prepare($cash_qry);
    $cash_sql->execute();
    $fetch=array();

    while($cash_row = $cash_sql->fetch(PDO::FETCH_ASSOC)){
        $fetch[]=array(
            "id"=>$cash_row["id"],
            "rate"=> $cash_row["rate"],
            "patti_date"=>  $cash_row["patti_date"],
            "patti_id"=>  $cash_row["patti_id"],
            "supplier_name"=>  $cash_row["supplier_name"],
            "mobile_number"=>  $cash_row["mobile_number"],
            "lorry_no"=>  $cash_row["lorry_no"],
            "quality_name"=>  $cash_row["quality_name"],
            "quantity"=>  $cash_row["quantity"],
            "bill_amount"=>  $cash_row["bill_amount"],
            "boxes_arrived"=>  $cash_row["boxes_arrived"],
            "total_bill_amount"=>  $cash_row["total_bill_amount"],
            "net_bill_amount"=>  $cash_row["net_bill_amount"],
            "commision"=>  $cash_row["commision"],
            "lorry_hire"=>  $cash_row["lorry_hire"],
            "cooli"=>  $cash_row["cooli"],
            "box_charge"=>  $cash_row["box_charge"],
            "total_deduction"=>  $cash_row["total_deduction"]
        );
    }
        $supplier_id = $_REQUEST["supplier_id"];
        $select_qry1="SELECT * FROM sar_patti_payment WHERE supplier_id='$patti_id'";
        $select_sql1=$connect->prepare($select_qry1);
        $select_sql1->execute();
	
	
	   while($select_fetch = $select_sql1->fetch(PDO::FETCH_ASSOC)){
         $fetch['cash'][]=array(
             "id"=>$select_fetch["id"],
             "payment_id"=> $select_fetch["payment_id"],
             "payment_date"=>  $select_fetch["payment_date"],
             "payment_mode"=>  $select_fetch["payment_mode"],
             "amount"=>  $select_fetch["amount"],
             "balance"=>  $select_fetch["balance"],
             "supplier_id"=>  $select_fetch["supplier_id"],
             "is_revoked"=> $select_fetch["is_revoked"]
         );
     }		
	echo json_encode($fetch);
}
?>