<?php
require_once("include/config.php");
$sql="select * from sar_patti where supplier_name='MKB' and is_active!=0 order by id asc";
$exe=mysqli_query($con,$sql);
$sum=0;
?>
<table>
    <tbody>
        <tr><td>Bill Amount</td></tr>
    <?php
while($sql=mysqli_fetch_assoc($exe)){
    $sum+=$sql['total_bill_amount'];
    ?>
        <tr><td><?=$sql['total_bill_amount']?></td></tr>
    <?php
}
?>     <tr><td>Total</td><td><?=$sum?></td></tr>
   
</tbody>
  </table>
  
<form method="POST">
<input type="text" name="amount" value="" />
<input type="text" name="dis" />
<select name="amttype">
<option value="Select">Select Option</option>
 <option value="discount">Discount</option>
    <option value="percentage">Percentage</option>
</select>
<input type="submit" name="remain" />
</form>

<?php
if(isset($_POST['remain'])){
    $amount=$_POST['amount'];
    $dis=$_POST['dis'];
    $amttype=$_POST['amttype'];
    if($amttype=="discount"){
        $amt=$amount+$dis;
    }
    else if($amttype=="percentage"){
        $amt=$amount-(($dis*$amount)/100);
    }
    if($amt){
        $amt=$amt;
    }
    else{
    $amt=$amount;
    }
    $sql="select * from sar_patti where supplier_name='MKB'";
$exe=mysqli_query($con,$sql);
$no=mysqli_num_rows($exe);
if($no==0){
    $sql="select * from sar_patti where supplier_name='MKB' and is_active=0 and remain!='' order by total_bill_amount asc";
    $exe=mysqli_query($con,$sql);
    $no=mysqli_num_rows($exe);
}
 $remain=0; $am=0; $f=0;
?>
<table><tbody><tr><td>Bill amount</td><td>Remain</td></tr>
<?php
while($row=mysqli_fetch_assoc($exe)){
    $f+=1; ?>
<tr><td><?=$row['total_bill_amount']?></td>
<td><?php
if($amt<$row['total_bill_amount']) {
    if($remain==0){
echo $row['total_bill_amount']-$amt;
$remain=0;
    }
   else{
        if($amt==$remain){
        echo $row['total_bill_amount'];
        }
        else if($remain==0){
            echo $row['total_bill_amount'];
        }
        else{
            if($remain < $row['total_bill_amount']){
             echo $am-abs($amt)+abs($row['total_bill_amount']);
        }
        else if($remain > $row['total_bill_amount']){
            echo $am-abs($amt)+abs($row['total_bill_amount']);
        }
        else{
            echo 1;
        }
        }
    }
break;
}
else{
    $rem=$row['total_bill_amount']-$amt;
    $remain-=$rem;
    $am+=$row['total_bill_amount'];
    if($amt==$remain){
    //  echo $rem;
    echo 1;
    break;
    // echo $am-$amt;    
}
else if(($row['total_bill_amount'] < $amt)){
    $val = $am-$amt;
    if($val<0) {
        echo 1;
    }
    else {
        $remains=$am-$val;
        if($am==$remains)
        {
        echo 1;   
        }
        else{
            echo $am-$remains;
        }
        break;
}
}
    //  else if($row['total_bill_amount']<$amt && $row['total_bill_amount']>abs($rem)){
    //     // echo $row['total_bill_amount']-$amt;
    //     echo $am-$amt;
    //  }
    else if($remain > $row['total_bill_amount']){
        // echo $am;
       if($amt==$row['total_bill_amount'])
       { 
        $am-=$row['total_bill_amount'];
        echo abs($am-$amt);
    }
else{
    // $am-=$row['total_bill_amount'];
    echo $row['total_bill_amount'];
}
}
    else{
        if($amt==$row['total_bill_amount']){
            echo 1;
            break;
        } else{
        echo $row['total_bill_amount'];
    }
}
//    echo $am;
}

?></td></tr>
<?php 
}
if($no>$f){
$val=$no-$f;
// echo $val;

$sqlt="select * from sar_patti where supplier_name='MKB' and is_active=0 order by total_bill_amount asc";
$exet=mysqli_query($con,$sqlt);
$not=mysqli_num_rows($exet);   
$totamt=0;
while($s=mysqli_fetch_assoc($exet)){
$totamt+=$s['total_bill_amount'];
}

$sql="select * from sar_patti where supplier_name='MKB' and is_active=1 order by total_bill_amount asc";
$exe=mysqli_query($con,$sql);
$no=mysqli_num_rows($exe);   

while($selp=mysqli_fetch_assoc($exep)){
?>
<tr><td><?=$selp['total_bill_amount']?></td><td><?=$selp['total_bill_amount']-$totamt+$amount?></td></tr>
<?php
}
}
?>
</tbody></table>

<?php
//  echo $f."ok";
}

?>