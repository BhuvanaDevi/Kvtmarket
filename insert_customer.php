<?php require "include/config.php";
$date = date("Y-m-d");
?>
<?php
if(isset($_POST["submit"])){
    $customer_qry="SELECT id FROM sar_customer ORDER BY id DESC LIMIT 1 ";
    $customer_sql=$connect->prepare($customer_qry);
    $customer_sql->execute();
    $customer_row=$customer_sql->fetch(PDO::FETCH_ASSOC);
    $Last_id=$customer_row["id"]+1;
    
    $customer_no = "CUS_".date("Ym")."0".$Last_id;
  $customer_name = ucwords($_POST["customer_name"]);
  $contact_number1 = $_POST["contact_number1"];
  $address = $_POST["address"];
  
   
    $svar="SELECT * FROM sar_customer WHERE contact_number1='".$contact_number1."' ";
    $query = $connect->prepare($svar);
    $user_array = $query ->execute();
    $count=$query->rowCount();
    if($count==0 || $id=""){
        $query_1 = "INSERT INTO `sar_customer` SET 
                customer_no='$customer_no',
                customer_name='$customer_name',
                contact_number1='$contact_number1',
                address='$address',
                is_active=1
                ";
                
         // echo  $query_1; 
        
        $sql_1= $connect->prepare($query_1);
        $sql_1->execute();
         echo "<script> alert('Stored successfully'); </script>";
    } else if($count !=0) {
         echo '<script type ="text/JavaScript">';  
echo 'alert("JavaScript Alert Box by PHP")';  
echo '</script>';  
    }
    else {
        
            $query_1 = "UPDATE `sar_customer` SET 
                        customer_no='$customer_no',
                        customer_name='$customer_name',
                        contact_number1='$contact_number1',
                        address='$address',
                        is_active=1
                         WHERE id=$id";
            $sql_1= $connect->prepare($query_1);
            $sql_1->execute();
                       
    } 
    header("location:add_customer.php");


}

         ?>