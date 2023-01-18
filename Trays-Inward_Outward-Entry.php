<?php
require "header.php";
$date = date("Y-m-d");

$select_qry1="SELECT * FROM trays ORDER BY id desc limit 1";
$select_sql1=$connect->prepare($select_qry1);
$select_sql1->execute();
$select_fetch1=$select_sql1->fetch(PDO::FETCH_ASSOC);
$atray=$select_fetch1['ab_tray'];
$asmtray=$select_fetch1['absmall'];
$abgtray=$select_fetch1['abbig'];
// print_r($atray);die();

if(isset($_POST["add_tray"]))
{
 $supplier_name=$_POST["supplier_name"];
 $issued_date=$_POST["tray_date"];
 $no_of_trays_issued=$_POST["no_of_tray"];
 $type=$_POST["type"];
 //$category=$_POST["invoice"];
if(!empty($supplier_name)){
   $supplier_name=$_POST["supplier_name"];
} else{
   $supplier_name=$_POST["customer"];
 
}
 $description=$_POST["description"];
 $select_qry="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id desc limit 1";
  $select_sql=$connect->prepare($select_qry);
 $select_sql->execute();
 $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
 $small=$select_fetch['smalltray'];
 $big=$select_fetch['bigtray'];
//  $absmall=$select_fetch['absmall'];
//  $abbig=$select_fetch['abbig'];

//  $select_qry1="SELECT * FROM trays ORDER BY id desc limit 1";
//  $select_sql1=$connect->prepare($select_qry1);
// $select_sql1->execute();
// $select_fetch1=$select_sql1->fetch(PDO::FETCH_ASSOC);
// $atray=$select_fetch1['atray'];

// print_r($atray);die()

if($description=="Supplier Inward"){
$sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
$query = $connect -> prepare($sql);
   $query->execute();
   $results=$query->fetch(PDO::FETCH_OBJ);
   $names=$results->contact_person;
   $groupname=$results->group_name;
   // $name=$results->supplier_no;
  
      $updated_by="Admin";
      
      if($select_fetch['inhand']==0){
         $hand=0-$no_of_trays_issued;
         $total_tray=$no_of_trays_issued;
         $inward=0-$no_of_trays_issued;
         // if($atray==0){
         //    $abtray=0;
         // } 
         // else{
            $inward=abs($inward);
            $abtray=$atray+$inward;
         // }
         
         if($type=="Small Tray"){
            if($small!=0)
{
                $small=$small-$total_tray;
}
else{
  $small=-$total_tray;
}

if($big!=0){
  $big=$big;
}
else{
  $big=0;
}

if($asmtray!=0)
{
                $absmall=$asmtray+$total_tray;
}
else{
  $absmall=$total_tray;
}

if($abgtray!=0){
  $abbig=$abgtray;
}
else{
  $abbig=0;
}

            }
            else if($type=="Big Tray"){
              if($big!=0)
{
                  $big=$big-$total_tray;
}
else{
    $big=-$total_tray;
}

if($small!=0){
    $small=$small;
}
else{
    $small=0;
}

if($abgtray!=0)
{
                  $abbig=$abgtray+$total_tray;
}
else{
    $abbig=$total_tray;
}

if($asmtray!=0){
    $absmall=$asmtray;
}
else{
    $absmall=0;
}
              }

$small=isset($small)?$small:0;
$big=isset($big)?$big:0;
$absmall=isset($absmall)?$absmall:0;
$abbig=isset($abbig)?$abbig:0;

               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$inward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
      //   print_r($supplier_insert_query);die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
      
         $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
$tray1=$connect->prepare("$tray");
$tray1->execute();
$tray=$tray1->fetch(PDO::FETCH_ASSOC);   
//  $balc= $balan['balance']-$amount;
//  $balc=abs($balc);
$small=$tray['smalltray'];
$big=$tray['bigtray'];
$inhand=$tray['inhand'];
   
$sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
$exebal=mysqli_query($con,$sqlbal);
$valbal=mysqli_fetch_assoc($exebal);
$no=mysqli_num_rows($exebal);

$paybal = $valbal["id"] + 1;
$pay_id = "PAY" . date("Ym") . $paybal; 

$pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

if($valbal['obal']==""){
   $obal=0;
}
else{
   $obal=$valbal['obal'];
}

   //    $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
   //  //   print_r($insbal."k");die(); 
   //    $exe=mysqli_query($con,$insbal);

      }
                 
      else if($select_fetch['inhand']<0){
         $hand=$select_fetch['inhand']-$no_of_trays_issued;
         // print_r($hand);die();
         $total_tray=$no_of_trays_issued;
         $inward=-$no_of_trays_issued;
         // if($atray==0){
         //    $abtray=0;
         // } 
         // else{
            $abtray=$atray+$inward;
            if($type=="Small Tray"){
               if($small!=0)
   {
                   $small=$small+$total_tray;
   }
   else{
     $small=$total_tray;
   }
   
   if($big!=0){
     $big=$big;
   }
   else{
     $big=0;
   }

   if($asmtray!=0)
   {
                   $absmall=$asmtray+$total_tray;
   }
   else{
     $absmall=$total_tray;
   }
   
   if($abgtray!=0){
     $abbig=$abgtray;
   }
   else{
     $abbig=0;
   }

               }
               else if($type=="Big Tray"){
                 if($big!=0)
   {
                     $big=$big+$total_tray;
   }
   else{
       $big=$total_tray;
   }
   
   if($small!=0){
       $small=$small;
   }
   else{
       $small=0;
   }

   if($abgtray!=0)
   {
                     $abbig=$abgtray+$total_tray;
   }
   else{
       $abbig=$total_tray;
   }
   
   if($asmtray!=0){
       $absmall=$absmall;
   }
   else{
       $absmall=0;
   }
                 }
   
   $small=isset($small)?$small:0;
   $big=isset($big)?$big:0;
   $absmall=isset($absmall)?$absmall:0;
   $abbig=isset($abbig)?$abbig:0;
   
   // }
         
               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$inward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
      //  print_r($supplier_insert_query."i");die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
          
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
               $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
               $query = $connect -> prepare($sql);
                  $query->execute();
                  $results=$query->fetch(PDO::FETCH_OBJ);
                  $names=$results->contact_person;
                   
                  $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

                  if($valbal['obal']==""){
                     $obal=0;
                  }
                  else{
                     $obal=$valbal['obal'];
                  }
                  
                  //    $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
                  //  //   print_r($insbal."k");die(); 
                  //    $exe=mysqli_query($con,$insbal);

                  }
            else{
               $inhand=$select_fetch['inhand'];
               $hand=$inhand-$no_of_trays_issued;
               $inward=-$no_of_trays_issued;
               
               $total_tray=$select_fetch['no_of_trays']-$no_of_trays_issued;
               // if($atray==0){
               //    $abtray=0;
               // } 
               // else{
                  $inward1=abs($inward);
                  $abtray=$atray+$inward1;
                  if($type=="Small Tray"){
                     if($small!=0)
         {
                         $small=$small-$no_of_trays_issued;
         }
         else{
           $small=$no_of_trays_issued;
         }
         
         if($big!=0){
           $big=$big;
         }
         else{
           $big=0;
         }

         if($asmtray!=0)
         {
                         $absmall=$asmtray+$no_of_trays_issued;
         }
         else{
           $absmall=$no_of_trays_issued;
         }
         
         if($abgtray!=0){
           $abbig=$abgtray;
         }
         else{
           $abbig=0;
         }
                     }
                     else if($type=="Big Tray"){
                       if($big!=0)
         {
                           $big=$big-$no_of_trays_issued;
         }
         else{
             $big=$no_of_trays_issued;
         }
         
         if($small!=0){
             $small=$small;
         }
         else{
             $small=0;
         }

         if($abgtray!=0)
         {
                           $abbig=$abgtray+$no_of_trays_issued;
         }
         else{
             $abbig=$no_of_trays_issued;
         }
         
         if($asmtray!=0){
             $absmall=$asmtray;
         }
         else{
             $absmall=0;
         }
                       }
         
         $small=isset($small)?$small:0;
         $big=isset($big)?$big:0;
         $absmall=isset($absmall)?$absmall:0;
         $abbig=isset($abbig)?$abbig:0;
           // }
               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$inward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
               // print_r($supplier_insert_query."s");die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
 
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
               $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
               $query = $connect -> prepare($sql);
                  $query->execute();
                  $results=$query->fetch(PDO::FETCH_OBJ);
                  $names=$results->contact_person;
                   
                  $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

                  if($valbal['obal']==""){
                     $obal=0;
                  }
                  else{
                     $obal=$valbal['obal'];
                  }

                  //    $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
                  //  //   print_r($insbal."k");die(); 
                  //    $exe=mysqli_query($con,$insbal);
               
            }         
 
   }
   if($description=="Supplier Outward"){
     
      

$sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
$query = $connect -> prepare($sql);
   $query->execute();
   $results=$query->fetch(PDO::FETCH_OBJ);
   $names=$results->contact_person;
   $groupname=$results->group_name;
    

      $updated_by="Admin";
   //   print_r($select_fetch['inhand']);die();
      if($select_fetch['inhand']==0){
   
        $hand=$no_of_trays_issued;
         $total_tray=$no_of_trays_issued;
         $outward=$no_of_trays_issued;
         if($atray==""){
            $abtray=$atray-$outward;
          } 
         else{
            $abtray=$atray-$outward;
         }
                  if($type=="Small Tray"){
                     if($small!=0)
         {
                         $small=$small+$total_tray;
         }
         else{
           $small=$total_tray;
         }
         
         if($big!=0){
           $big=$big;
         }
         else{
           $big=0;
         }
          
         if($asmtray!=0)
         {
                         $absmall=$asmtray-$total_tray;
         }
         else{
           $absmall=-$total_tray;
         }
         
         if($abgtray!=0){
           $abbig=$abgtray;
         }
         else{
           $abbig=0;
         }
      }
                     else if($type=="Big Tray"){
                       if($big!=0)
         {
                           $big=$big+$total_tray;
         }
         else{
             $big=$total_tray;
         }
         
         if($small!=0){
             $small=$small;
         }
         else{
             $small=0;
         }
          
         if($abgtray!=0)
         {
                           $abbig=$abgtray-$total_tray;
         }
         else{
             $abbig=-$total_tray;
         }
         
         if($asmtray!=0){
             $absmall=$asmtray;
         }
         else{
             $absmall=0;
         }
      }
         
         $small=isset($small)?$small:0;
         $big=isset($big)?$big:0;
         $absmall=isset($absmall)?$absmall:0;
         $abbig=isset($abbig)?$abbig:0;

         $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$outward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
   //   print_r($supplier_insert_query);die();   
      $supplier_sql=mysqli_query($con,$supplier_insert_query);
         
      $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
      $tray1=$connect->prepare("$tray");
      $tray1->execute();
      $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
      //  $balc= $balan['balance']-$amount;
      //  $balc=abs($balc);
      $small=$tray['smalltray'];
      $big=$tray['bigtray'];
      $inhand=$tray['inhand'];
         
      $sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
      $exebal=mysqli_query($con,$sqlbal);
      $valbal=mysqli_fetch_assoc($exebal);
      $no=mysqli_num_rows($exebal);
      
      $paybal = $valbal["id"] + 1;
      $pay_id = "PAY" . date("Ym") . $paybal; 
      
      $pay=$no_of_trays_issued*100;
      if($valbal['total']==0){
         $op=$no_of_trays_issued*100;
         $tot=$pay;
      }
      else if($valbal['total']==""){
         $op=$no_of_trays_issued*100;
         $tot=$pay;
      }  
      else{
         $op=($no_of_trays_issued*100)-$valbal['total'];
         $tot=$op;
      }
      
      $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
      $query = $connect -> prepare($sql);
         $query->execute();
         $results=$query->fetch(PDO::FETCH_OBJ);
         $names=$results->contact_person;
          
         $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

         if($valbal['obal']==""){
            $obal=0;
         }
         else{
            $obal=$valbal['obal'];
         }

         //    $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
         //  //   print_r($insbal."k");die(); 
         //    $exe=mysqli_query($con,$insbal);
      
   }
             else if($select_fetch['inhand']<0){
   
               $hand=$select_fetch['inhand']+$no_of_trays_issued;
                $total_tray=$no_of_trays_issued;
                $outward=$no_of_trays_issued;
                if($atray==""){
                   $abtray=$atray-$outward;
                 } 
                 else if($atray<0){
                  $abtray=$atray+$outward;
                } 
                else{
                   $abtray=$atray-$outward;
                }
 $abtray=abs($abtray);      
 if($type=="Small Tray"){
   if($small!=0)
{
       $small=$small+$total_tray;
}
else{
$small=$total_tray;
}

if($big!=0){
$big=$big;
}
else{
$big=0;
}

if($asmtray!=0)
{
       $absmall=$asmtray-$total_tray;
}
else{
$absmall=-$total_tray;
}

if($abgtray!=0){
$abbig=$abgtray;
}
else{
$abbig=0;
}

   }
   else if($type=="Big Tray"){
     if($big!=0)
{
         $big=$big+$total_tray;
}
else{
$big=$total_tray;
}

if($small!=0){
$small=$small;
}
else{
$small=0;
}

if($abgtray!=0)
{
         $abbig=$abgtray-$total_tray;
}
else{
$abbig=-$total_tray;
}

if($asmtray!=0){
$absmall=$asmtray;
}
else{
$absmall=0;
}
     }

$small=isset($small)?$small:0;
$big=isset($big)?$big:0;
$absmall=isset($absmall)?$absmall:0;
$abbig=isset($abbig)?$abbig:0;

                $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$outward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
            // print_r($supplier_insert_query."r");die();   
             $supplier_sql=mysqli_query($con,$supplier_insert_query);
              
             $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
             $tray1=$connect->prepare("$tray");
             $tray1->execute();
             $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
             //  $balc= $balan['balance']-$amount;
             //  $balc=abs($balc);
             $small=$tray['smalltray'];
             $big=$tray['bigtray'];
             $inhand=$tray['inhand'];
                
             $sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
             $exebal=mysqli_query($con,$sqlbal);
             $valbal=mysqli_fetch_assoc($exebal);
             $no=mysqli_num_rows($exebal);
             
             $paybal = $valbal["id"] + 1;
             $pay_id = "PAY" . date("Ym") . $paybal; 
             
             $pay=$no_of_trays_issued*100;
             if($valbal['total']==0){
                $op=$no_of_trays_issued*100;
                $tot=$pay;
             }
             else if($valbal['total']==""){
                $op=$no_of_trays_issued*100;
                $tot=$pay;
             }  
             else{
                $op=($no_of_trays_issued*100)-$valbal['total'];
                $tot=$op;
             }
             
             $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
             $query = $connect -> prepare($sql);
                $query->execute();
                $results=$query->fetch(PDO::FETCH_OBJ);
                $names=$results->contact_person;
                 
                $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

                if($valbal['obal']==""){
                  $obal=0;
               }
               else{
                  $obal=$valbal['obal'];
               }

               //     $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
               //   //   print_r($insbal."k");die(); 
               //     $exe=mysqli_query($con,$insbal);
             
                  }
            else{
               $hand=$select_fetch['inhand']+$no_of_trays_issued;
               $total_tray=$select_fetch['no_of_trays']+$no_of_trays_issued;
               $outward=$no_of_trays_issued;
               // if($atray==0){
               //    $abtray=0;
               // } 
               // else{
                  $outward=abs($outward);
                  $abtray=$atray-$outward;
               // }
               if($type=="Small Tray"){
                  if($small!=0)
      {
                      $small=$small+$no_of_trays_issued;
      }
      else{
        $small=$no_of_trays_issued;
      }
      
      if($big!=0){
        $big=$big;
      }
      else{
        $big=0;
      }

      if($asmtray!=0)
      {
                      $absmall=$asmtray-$no_of_trays_issued;
      }
      else{
        $absmall=-$no_of_trays_issued;
      }
      
      if($abgtray!=0){
        $abbig=$abgtray;
      }
      else{
        $abbig=0;
      }
                  }
                  else if($type=="Big Tray"){
                    if($big!=0)
      {
                        $big=$big+$no_of_trays_issued;
      }
      else{
          $big=$no_of_trays_issued;
      }
      
      if($small!=0){
          $small=$small;
      }
      else{
          $small=0;
      }
       
      if($abgtray!=0)
      {
                        $abbig=$abgtray-$no_of_trays_issued;
      }
      else{
          $abbig=-$no_of_trays_issued;
      }
      
      if($asmtray!=0){
          $absmall=$asmtray;
      }
      else{
          $absmall=0;
      }}
      
      $small=isset($small)?$small:0;
      $big=isset($big)?$big:0;
      $absmall=isset($absmall)?$absmall:0;
      $abbig=isset($abbig)?$abbig:0;
      
               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$outward,'$type','$description','$outward','$hand','$updated_by','Supplier',$abtray,$small,$big,$absmall,$abbig)";
            //   print_r($supplier_insert_query."q");die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
     
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment where supplierid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
               $sql = "SELECT * FROM  sar_supplier WHERE supplier_no='$supplier_name'";
               $query = $connect -> prepare($sql);
                  $query->execute();
                  $results=$query->fetch(PDO::FETCH_OBJ);
                  $names=$results->contact_person;

                  $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

                  if($valbal['obal']==""){
                     $obal=0;
                  }
                  else{
                     $obal=$valbal['obal'];
                  }
                   
                     $insbal="insert into payment(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,supplierid,pattid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$supplier_name','',$small,$big,$inhand)";
                     // print_r($insbal."k");die(); 
                     $exe=mysqli_query($con,$insbal);

                  }         

     }
     
     if($description=="Customer Inward"){
      
$sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
$query = $connect -> prepare($sql);
   $query->execute();
   $results=$query->fetch(PDO::FETCH_OBJ);
   $names=$results->customer_name;
   $groupname=$results->grp_cust_name;
$name=$results->customer_no;

      $updated_by="Admin";
      
      if($select_fetch['inhand']==0){
         $hand=0-$no_of_trays_issued;
         $total_tray=$no_of_trays_issued;
         $inward=0-$no_of_trays_issued;
         // if($atray==0){
         //    $abtray=0;
         // } 
         // else{
            $inwards=abs($inward);
            $abtray=$atray+$inwards;
         // }
         if($type=="Small Tray"){
            if($small!=0)
{
                $small=$small-$no_of_trays_issued;
}
else{
  $small=-$no_of_trays_issued;
}

if($big!=0){
  $big=$big;
}
else{
  $big=0;
}

if($asmtray!=0)
{
                $absmall=$asmtray+$no_of_trays_issued;
}
else{
  $absmall=-$no_of_trays_issued;
}

if($abgtray!=0){
  $abbig=$abgtray;
}
else{
  $abbig=0;
}
            }
            else if($type=="Big Tray"){
              if($big!=0)
{
                  $big=$big-$no_of_trays_issued;
}
else{
    $big=-$no_of_trays_issued;
}

if($small!=0){
    $small=$small;
}
else{
    $small=0;
}

if($abgtray!=0)
{
                  $abbig=$abgtray+$no_of_trays_issued;
}
else{
    $abbig=-$no_of_trays_issued;
}

if($asmtray!=0){
    $absmall=$asmtray;
}
else{
    $absmall=0;
}
              }

$small=isset($small)?$small:0;
$big=isset($big)?$big:0;
$absmall=isset($absmall)?$absmall:0;
$abbig=isset($abbig)?$abbig:0;

               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description',$inwards,'$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
            //  print_r($supplier_insert_query);die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
           
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
                
   $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
   $query = $connect -> prepare($sql);
      $query->execute();
      $results=$query->fetch(PDO::FETCH_OBJ);
      $names=$results->customer_name;
      $name=$results->customer_no;

      $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

      if($valbal['obal']==""){
         $obal=0;
      }
      else{
         $obal=$valbal['obal'];
      }
                   
                  $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
                    //  print_r($insbal."k");die(); 
                    $exe=mysqli_query($con,$insbal);
                
                  }
             else if($select_fetch['inhand']<0){
               $hand=0-$no_of_trays_issued;
               $total_tray=$no_of_trays_issued;
               $inward=0-$no_of_trays_issued;
               // if($atray==0){
               //    $abtray=0;
               // } 
               // else{
                  $inwards=abs($inwards);
                  $abtray=$atray+$inwards;
                  if($type=="Small Tray"){
                     if($small!=0)
         {
                         $small=$small+$no_of_trays_issued;
         }
         else{
           $small=$no_of_trays_issued;
         }
         
         if($big!=0){
           $big=$big;
         }
         else{
           $big=0;
         }

         if($asmtray!=0)
         {
                         $absmall=$asmtray+$no_of_trays_issued;
         }
         else{
           $absmall=$no_of_trays_issued;
         }
         
         if($abgtray!=0){
           $abbig=$abgtray;
         }
         else{
           $abbig=0;
         }
                     }
                     else if($type=="Big Tray"){
                       if($big!=0)
         {
                           $big=$big+$no_of_trays_issued;
         }
         else{
             $big=$no_of_trays_issued;
         }
         
         if($small!=0){
             $small=$small;
         }
         else{
             $small=0;
         }
          
         if($abgtray!=0)
         {
                           $abbig=$abgtray+$no_of_trays_issued;
         }
         else{
             $abbig=$no_of_trays_issued;
         }
         
         if($asmtray!=0){
             $absmall=$asmtray;
         }
         else{
             $absmall=0;
         }
      }
         
         $small=isset($small)?$small:0;
         $big=isset($big)?$big:0;
         $absmall=isset($absmall)?$absmall:0;
         $abbig=isset($abbig)?$abbig:0;
           
         
               // }
                     $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$inwards','$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
                  //  print_r($supplier_insert_query);die();
                     $supplier_sql=mysqli_query($con,$supplier_insert_query);
              
              
              
                     $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
                     $tray1=$connect->prepare("$tray");
                     $tray1->execute();
                     $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
                     //  $balc= $balan['balance']-$amount;
                     //  $balc=abs($balc);
                     $small=$tray['smalltray'];
                     $big=$tray['bigtray'];
                     $inhand=$tray['inhand'];
                        
                     $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
                     $exebal=mysqli_query($con,$sqlbal);
                     $valbal=mysqli_fetch_assoc($exebal);
                     $no=mysqli_num_rows($exebal);
                     
                     $paybal = $valbal["id"] + 1;
                     $pay_id = "PAY" . date("Ym") . $paybal; 
                     
                     $pay=$no_of_trays_issued*100;
                     if($valbal['total']==0){
                        $op=$no_of_trays_issued*100;
                        $tot=$pay;
                     }
                     else if($valbal['total']==""){
                        $op=$no_of_trays_issued*100;
                        $tot=$pay;
                     }  
                     else{
                        $op=($no_of_trays_issued*100)-$valbal['total'];
                        $tot=$op;
                     }
                     
                      
         $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
         $query = $connect -> prepare($sql);
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            $names=$results->customer_name;
                         
            $pay=$no_of_trays_issued*100;
            if($valbal['total']==0){
               $op=$no_of_trays_issued*100;
               $tot=$pay;
            }
            else if($valbal['total']==""){
               $op=$no_of_trays_issued*100;
               $tot=$pay;
            }  
            else{
               $op=($no_of_trays_issued*100)-$valbal['total'];
               $tot=$op;
            }
             if($valbal['obal']==""){
               $obal=0;
            }
            else{
               $obal=$valbal['obal'];
            }

                        $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
                        //   print_r($insbal."k");die(); 
                          $exe=mysqli_query($con,$insbal);
                
                        }
            else{
               $inhand=$select_fetch['inhand'];
               $hand=$inhand-$no_of_trays_issued;
               $inward=-$no_of_trays_issued;
           
               $total_tray=$select_fetch['no_of_trays']-$no_of_trays_issued;
               // if($atray==0){
               //    $abtray=0;
               // } 
               // else{
                  $inwards=abs($inward);
                  $abtray=$atray+$inwards;
               // }
               if($type=="Small Tray"){
                  if($small!=0)
      {
                      $small=$small-$no_of_trays_issued;
      }
      else{
        $small=$no_of_trays_issued;
      }
      
      if($big!=0){
        $big=$big;
      }
      else{
        $big=0;
      }

      if($asmtray!=0)
      {
                      $absmall=$asmtray+$inwards;
      }
      else{
        $absmall=$no_of_trays_issued;
      }
      
      if($abgtray!=0){
        $abbig=$abgtray;
      }
      else{
        $abbig=0;
      }
                  }
                  else if($type=="Big Tray"){
                    if($big!=0)
      {
                        $big=$big-$no_of_trays_issued;
      }
      else{
          $big=$no_of_trays_issued;
      }
      
      if($small!=0){
          $small=$small;
      }
      else{
          $small=0;
      }

      if($abgtray!=0)
      {
                        $abbig=$abgtray+$no_of_trays_issued;
      }
      else{
          $abbig=$no_of_trays_issued;
      }
      
      if($asmtray!=0){
          $absmall=$asmtray;
      }
      else{
          $absmall=0;
      }
                    }
      
      $small=isset($small)?$small:0;
      $big=isset($big)?$big:0;
      $absmall=isset($absmall)?$absmall:0;
      $abbig=isset($abbig)?$abbig:0;
        
      
               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,inward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$inwards,'$type','$description','$inwards','$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
               // print_r($supplier_insert_query);die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
 
 
 
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
                
   $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
   $query = $connect -> prepare($sql);
      $query->execute();
      $results=$query->fetch(PDO::FETCH_OBJ);
      $names=$results->customer_name;

      $pay=$no_of_trays_issued*100;
      if($valbal['total']==0){
         $op=$no_of_trays_issued*100;
         $tot=$pay;
      }
      else if($valbal['total']==""){
         $op=$no_of_trays_issued*100;
         $tot=$pay;
      }  
      else{
         $op=($no_of_trays_issued*100)-$valbal['total'];
         $tot=$op;
      }
      
      if($valbal['obal']==""){
         $obal=0;
      }
      else{
         $obal=$valbal['obal'];
      }
                   
                  $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
                  //   print_r($insbal."k");die(); 
                    $exe=mysqli_query($con,$insbal);
                
                  }         
 
   }
   if($description=="Customer Outward"){
     
      $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
      $query = $connect -> prepare($sql);
         $query->execute();
         $results=$query->fetch(PDO::FETCH_OBJ);
         $names=$results->customer_name;
         $groupname=$results->grp_cust_name;
      $name=$results->customer_no;

      $updated_by="Admin";
   //   print_r($select_fetch['inhand']);die();
      if($select_fetch['inhand']==0){
   
        $hand=$no_of_trays_issued;
         $total_tray=$no_of_trays_issued;
         $outward=$no_of_trays_issued;
         // if($atray==0){
         //    $abtray=0;
         // } 
         // else{
            $abtray=$atray-$outward;
         // }
         if($type=="Small Tray"){
            if($small!=0)
{
                $small=$small+$total_tray;
}
else{
  $small=$total_tray;
}

if($big!=0){
  $big=$big;
}
else{
  $big=0;
}

if($asmtray!=0)
{
                $absmall=$asmtray-$total_tray;
}
else{
  $absmall=-$total_tray;
}

if($abgtray!=0){
  $abbig=$abgtray;
}
else{
  $abbig=0;
}

            }
            else if($type=="Big Tray"){
              if($big!=0)
{
                  $big=$big+$total_tray;
}
else{
    $big=$total_tray;
}

if($small!=0){
    $small=$small;
}
else{
    $small=0;
}

if($abgtray!=0)
{
                  $abbig=$abgtray-$total_tray;
}
else{
    $abbig=-$total_tray;
}

if($asmtray!=0){
    $absmall=-$asmtray;
}
else{
    $absmall=0;
}
              }

$small=isset($small)?$small:0;
$big=isset($big)?$big:0;
$absmall=isset($absmall)?$absmall:0;
$abbig=isset($abbig)?$abbig:0;

         $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$outward','$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
      // print_r($supplier_insert_query);die();
         $supplier_sql=mysqli_query($con,$supplier_insert_query);
   
   
         $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
         $tray1=$connect->prepare("$tray");
         $tray1->execute();
         $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
         //  $balc= $balan['balance']-$amount;
         //  $balc=abs($balc);
         $small=$tray['smalltray'];
         $big=$tray['bigtray'];
         $inhand=$tray['inhand'];
            
         $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
         $exebal=mysqli_query($con,$sqlbal);
         $valbal=mysqli_fetch_assoc($exebal);
         $no=mysqli_num_rows($exebal);
         
         $paybal = $valbal["id"] + 1;
         $pay_id = "PAY" . date("Ym") . $paybal; 
         
         $pay=$no_of_trays_issued*100;
         if($valbal['total']==0){
            $op=$no_of_trays_issued*100;
            $tot=$pay;
         }
         else if($valbal['total']==""){
            $op=$no_of_trays_issued*100;
            $tot=$pay;
         }  
         else{
            $op=($no_of_trays_issued*100)-$valbal['total'];
            $tot=$op;
         }
         
          
$sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
$query = $connect -> prepare($sql);
$query->execute();
$results=$query->fetch(PDO::FETCH_OBJ);
$names=$results->customer_name;
             
            $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
            //   print_r($insbal."k");die(); 
              $exe=mysqli_query($con,$insbal);
             
            }
             else if($select_fetch['inhand']<0){
   
               $hand=$select_fetch['inhand']+$no_of_trays_issued;
               // $hand=0-$no_of_trays_issued;
               // $hand=$no_of_trays_issued;
                $total_tray=$no_of_trays_issued;
                $outward=$no_of_trays_issued;
                // if($atray==0){
                //    $abtray=0;
                // } 
                // else{
                   $abtray=$atray-$outward;
                // }
                if($type=="Small Tray"){
                  if($small!=0)
      {
                      $small=$small+$total_tray;
      }
      else{
        $small=$total_tray;
      }
      
      if($big!=0){
        $big=$big;
      }
      else{
        $big=0;
      }

      if($asmtray!=0)
      {
                      $absmall=$asmtray-$total_tray;
      }
      else{
        $absmall=-$total_tray;
      }
      
      if($abgtray!=0){
        $abbig=$abgtray;
      }
      else{
        $abbig=0;
      }
                  }
                  else if($type=="Big Tray"){
                    if($big!=0)
      {
                        $big=$big+$total_tray;
      }
      else{
          $big=$total_tray;
      }
      
      if($small!=0){
          $small=$small;
      }
      else{
          $small=0;
      }

      if($abgtray!=0)
      {
                        $abbig=$abgtray-$total_tray;
      }
      else{
          $abbig=-$total_tray;
      }
      
      if($asmtray!=0){
          $absmall=$asmtray;
      }
      else{
          $absmall=0;
      }
                    }
      
      $small=isset($small)?$small:0;
      $big=isset($big)?$big:0;
      $absmall=isset($absmall)?$absmall:0;
      $abbig=isset($abbig)?$abbig:0;
        
      
                $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$total_tray,'$type','$description','$outward','$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
            //  print_r($supplier_insert_query."d");die();
                $supplier_sql=mysqli_query($con,$supplier_insert_query);
           
           
                $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
                $tray1=$connect->prepare("$tray");
                $tray1->execute();
                $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
                //  $balc= $balan['balance']-$amount;
                //  $balc=abs($balc);
                $small=$tray['smalltray'];
                $big=$tray['bigtray'];
                $inhand=$tray['inhand'];
                   
                $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
                $exebal=mysqli_query($con,$sqlbal);
                $valbal=mysqli_fetch_assoc($exebal);
                $no=mysqli_num_rows($exebal);
                
                $paybal = $valbal["id"] + 1;
                $pay_id = "PAY" . date("Ym") . $paybal; 
                
                $pay=$no_of_trays_issued*100;
                if($valbal['total']==0){
                   $op=$no_of_trays_issued*100;
                   $tot=$pay;
                }
                else if($valbal['total']==""){
                   $op=$no_of_trays_issued*100;
                   $tot=$pay;
                }  
                else{
                   $op=($no_of_trays_issued*100)-$valbal['total'];
                   $tot=$op;
                }
                
                 
    $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
    $query = $connect -> prepare($sql);
       $query->execute();
       $results=$query->fetch(PDO::FETCH_OBJ);
       $names=$results->customer_name;
                    
       $pay=$no_of_trays_issued*100;
       if($valbal['total']==0){
          $op=$no_of_trays_issued*100;
          $tot=$pay;
       }
       else if($valbal['total']==""){
          $op=$no_of_trays_issued*100;
          $tot=$pay;
       }  
       else{
          $op=($no_of_trays_issued*100)-$valbal['total'];
          $tot=$op;
       }
          if($valbal['obal']==""){
         $obal=0;
      }
      else{
         $obal=$valbal['obal'];
      }

                   $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
                   //   print_r($insbal."k");die(); 
                     $exe=mysqli_query($con,$insbal);
                
                  }
            else{
               $hand=$select_fetch['inhand']+$no_of_trays_issued;
               $total_tray=$select_fetch['no_of_trays']+$no_of_trays_issued;
               $outward=$no_of_trays_issued;
               // if($atray==0){
               //    $abtray=0;
               // } 
               // else{
                  $abtray=$atray-$outward;
               // }
               if($type=="Small Tray"){
                  if($small!=0)
      {
                      $small=$small+$total_tray;
      }
      else{
        $small=$total_tray;
      }
      
      if($big!=0){
        $big=$big;
      }
      else{
        $big=0;
      }

      if($asmtray!=0)
      {
                      $absmall=$asmtray-$total_tray;
      }
      else{
        $absmall=-$total_tray;
      }
      
      if($abgtray!=0){
        $abbig=$abgtray;
      }
      else{
        $abbig=0;
      }
                  }
                  else if($type=="Big Tray"){
                    if($big!=0)
      {
                        $big=$big+$total_tray;
      }
      else{
          $big=$total_tray;
      }
      
      if($small!=0){
          $small=$small;
      }
      else{
          $small=0;
      }

      if($abgtray!=0)
      {
                        $abbig=$abgtray-$total_tray;
      }
      else{
          $abbig=-$total_tray;
      }
      
      if($asmtray!=0){
          $absmall=$asmtray;
      }
      else{
          $absmall=0;
      }
                    }
      
      $small=isset($small)?$small:0;
      $big=isset($big)?$big:0;
      $absmall=isset($absmall)?$absmall:0;
      $abbig=isset($abbig)?$abbig:0;
        
      
               $supplier_insert_query="insert into `trays` (date,name,no_of_trays,type,description,outward,inhand,updated_by,category,ab_tray,smalltray,bigtray,absmall,abbig) values('$date','$supplier_name',$outward,'$type','$description','$outward','$hand','$updated_by','Customer',$abtray,$small,$big,$absmall,$abbig)";
            //   print_r($supplier_insert_query."x");die();
               //  print_r($supplier_insert_query);die();
               $supplier_sql=mysqli_query($con,$supplier_insert_query);
       
       
               $tray="SELECT * FROM trays where name='$supplier_name' and type='$type' ORDER BY id DESC LIMIT 1 ";
               $tray1=$connect->prepare("$tray");
               $tray1->execute();
               $tray=$tray1->fetch(PDO::FETCH_ASSOC);   
               //  $balc= $balan['balance']-$amount;
               //  $balc=abs($balc);
               $small=$tray['smalltray'];
               $big=$tray['bigtray'];
               $inhand=$tray['inhand'];
                  
               $sqlbal="select * from payment_sale where customerid='$supplier_name' order by id desc limit 1";
               $exebal=mysqli_query($con,$sqlbal);
               $valbal=mysqli_fetch_assoc($exebal);
               $no=mysqli_num_rows($exebal);
               
               $paybal = $valbal["id"] + 1;
               $pay_id = "PAY" . date("Ym") . $paybal; 
               
               $pay=$no_of_trays_issued*100;
               if($valbal['total']==0){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }
               else if($valbal['total']==""){
                  $op=$no_of_trays_issued*100;
                  $tot=$pay;
               }  
               else{
                  $op=($no_of_trays_issued*100)-$valbal['total'];
                  $tot=$op;
               }
               
                
   $sql = "SELECT * FROM  sar_customer WHERE customer_no='$supplier_name'";
   $query = $connect -> prepare($sql);
      $query->execute();
      $results=$query->fetch(PDO::FETCH_OBJ);
      $names=$results->customer_name;
                   
      $pay=$no_of_trays_issued*100;
if($valbal['total']==0){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}
else if($valbal['total']==""){
   $op=$no_of_trays_issued*100;
   $tot=$pay;
}  
else{
   $op=($no_of_trays_issued*100)-$valbal['total'];
   $tot=$op;
}

      if($valbal['obal']==""){
         $obal=0;
      }
      else{
         $obal=$valbal['obal'];
      }
                  $insbal="insert into payment_sale(groupname,payid,date,name,obal,sale,pay,tpay,dis,total,customerid,saleid,smalltray,bigtray,inhand) values('$groupname','$pay_id','$date','$names',$obal,0,0,$pay,0,$tot,'$name','',$small,$big,$inhand)";
                  //   print_r($insbal."k");die(); 
                    $exe=mysqli_query($con,$insbal);
                
                  }         

     }
   //   else if($description=="Customer Inward"){
   //    $supplier_insert_query="insert into `trays`
   //    (date,name,no_of_trays,type,description)values('$date','$supplier_name',$no_of_trays_issued,'$type','$description')";
   //   $supplier_sql=mysqli_query($con,$supplier_insert_query);
   //   }
   //   else if($description=="Customer Outward"){
   //    $supplier_insert_query="insert into `trays`
   //    (date,name,no_of_trays,type,description)values('$date','$supplier_name',$no_of_trays_issued,'$type','$description')";
   //   $supplier_sql=mysqli_query($con,$supplier_insert_query);
   //   }
           // $select_qry="SELECT * FROM sar_trays ORDER BY id DESC "; supplier_trays_issued
    // $select_sql=$connect->prepare($select_qry);
    // $select_sql->execute();
    // $select_fetch=$select_sql->rowCount();
    // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
    //     $id=$select_fetch["id"];
    // $total_amt=$select_fetch["total_trays"]+$no_of_trays_issued;
    // $add_supplier_update_query="update sar_trays set total_trays='$total_amt' where id=".$id;
    // $add_supplier_update_sql=$connect->prepare($add_supplier_update_query);
    // $add_supplier_update_sql->execute();
    
//     $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    $balance = $bal_row["inhand"] - $no_of_trays_issued;
//    $tray_trans_qry = "INSERT INTO tray_transactions SET 
//                   date = '$date',
//                   name = '$supplier_name',
//                   category = 'Supplier',
//                   outward = $no_of_trays_issued,
//                   inhand = $balance,
//                   updated_by='$username',
//                   description = 'Direct outward'";
//   $res2=mysqli_query($con,$supplier_insert_query);
   if($supplier_sql){
      // echo'<script>';
      // echo'alert("Trays Added Success")';
      // echo '</script>';
   header('Location: ./view_tray_inventory.php');
  
   }else{
      echo'<script>';
      echo'alert("Trays Added Failure")';
      echo '</script>';
   }
   } 


// if(isset($_POST["add_supplier_issued"]))
// {
//     $select_qry="SELECT * FROM sar_trays ORDER BY id DESC ";
//     $select_sql=$connect->prepare($select_qry);
//     $select_sql->execute();
//     //print_r($_POST);
//     $supplier_name=$_POST["supplier_name"];
//     $issued_date=$_POST["issued_date"];
//     $no_of_trays_issued=$_POST["no_of_trays_issued"];
     
//     $supplier_insert_query="insert into `supplier_trays_issued`
//     (issued_date,supplier_name,no_of_trays_issued)values('$issued_date','$supplier_name',$no_of_trays_issued)";
//    $supplier_sql=mysqli_query($con,$supplier_insert_query);
//     // $select_qry="SELECT * FROM sar_trays ORDER BY id DESC ";
//     // $select_sql=$connect->prepare($select_qry);
//     // $select_sql->execute();
//     // $select_fetch=$select_sql->rowCount();
//     // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//     //     $id=$select_fetch["id"];
//     // $total_amt=$select_fetch["total_trays"]+$no_of_trays_issued;
//     // $add_supplier_update_query="update sar_trays set total_trays='$total_amt' where id=".$id;
//     // $add_supplier_update_sql=$connect->prepare($add_supplier_update_query);
//     // $add_supplier_update_sql->execute();
    
//     $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    $balance = $bal_row["inhand"] - $no_of_trays_issued;
//    $tray_trans_qry = "INSERT INTO tray_transactions SET 
//                   date = '$issued_date',
//                   name = '$supplier_name',
//                   category = 'Supplier',
                 
//                   outward = $no_of_trays_issued,
//                   inhand = $balance,
//                   updated_by='$username',
//                   description = 'Direct outward'";
//    $res2=mysqli_query($con,$tray_trans_qry);
//    header('Location: ./Trays-Inward_Outward-Entry.php');
//    } 

// else if(isset($_POST["add_customer_received"]))
// {
//     $customer_received_date=$_POST["customer_received_date"];
//     $customer_name=$_POST["customer_name"];
//     $no_of_trays_received=$_POST["no_of_trays_received"];
       
//     $add_customer_received_query="insert into `customer_trays_received` 
//     SET customer_received_date='$customer_received_date',
//     customer_name='$customer_name',
//     no_of_trays_issued='$no_of_trays_received'
//     ";
     
//      $customer_received_sql= $connect->prepare($add_customer_received_query);
//                             $customer_received_sql->execute();
//     //                         $select_qry="SELECT * FROM sar_trays ORDER BY id DESC ";
//     // $select_sql=$connect->prepare($select_qry);
//     // $select_sql->execute();
//     // $select_fetch=$select_sql->rowCount();
//     // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//     //     $id=$select_fetch["id"];
//     // $total_amt=$select_fetch["total_trays"]-$no_of_trays_received;
//     // $add_customer_update_query="update sar_trays set total_trays='$total_amt' where id=".$id;
//     // $add_customer_update_sql=$connect->prepare($add_customer_update_query);
//     // $add_customer_update_sql->execute();

//     $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    $balance = $bal_row["inhand"] + $no_of_trays_received;
//    $tray_trans_qry = "INSERT INTO tray_transactions SET 
//                   date = '$customer_received_date',
//                   name = '$customer_name',
//                   category = 'Customer',
//                   inward = $no_of_trays_received,
//                   inhand = $balance,
//                    updated_by='$username',
//                   description = 'Direct inward'";
//    $res2=mysqli_query($con,$tray_trans_qry);
//    header('Location: ./Trays-Inward_Outward-Entry.php');
//    }   

// else if(isset($_POST["add_customer_issued"]))
// {
    
//     $customer_issued_date=$_POST["customer_issued_date"];
//     $customer_name=$_POST["customer_name"];
//     $no_of_trays_issued=$_POST["no_of_trays_issued"];
      
//     $add_customer_received_query="insert into `customer_trays_issued` 
//     SET customer_issued_date='$customer_issued_date',
//     customer_name='$customer_name',
//     no_of_trays_issued='$no_of_trays_issued'
//     ";
//      $customer_received_sql= $connect->prepare($add_customer_received_query);
//                             $customer_received_sql->execute();
                            
//     // $select_qry="SELECT * FROM sar_trays ORDER BY id DESC ";
//     // $select_sql=$connect->prepare($select_qry);
//     // $select_sql->execute();
//     // $select_fetch=$select_sql->rowCount();
//     // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//     //     $id=$select_fetch["id"];
//     // $total_amt=$select_fetch["total_trays"]-$no_of_trays_issued;
//     // $add_supplier_update_query="update sar_trays set total_trays='$total_amt' where id=".$id;
//     // $add_supplier_update_sql=$connect->prepare($add_supplier_update_query);
//     // $add_supplier_update_sql->execute();

//     $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    $balance = $bal_row["inhand"] - $no_of_trays_issued;
//    $tray_trans_qry = "INSERT INTO tray_transactions SET 
//                   date = '$customer_issued_date',
//                   name = '$customer_name',
//                   category = 'Customer',
//                   outward = $no_of_trays_issued,
//                   inhand = $balance,
//                    updated_by='$username',
//                   description = 'Direct outward'";
//    $res2=mysqli_query($con,$tray_trans_qry);
//    header('Location: ./Trays-Inward_Outward-Entry.php');

// }
// else if(isset($_POST["add_supplier_received"]))
// {
//        $issued_date=$_POST["supplier_received_date"];
  
//     $supplier_name=$_POST["supplier_name"];
//     $no_of_trays_issued=$_POST["no_of_trays_received"];
//      $select_name="select supplier_name from supplier_trays_issued where supplier_name='".$supplier_name."'";
//     $select_sql=mysqli_query($con,$select_name);
//     $select_row=mysqli_fetch_assoc($select_sql);
     
//     $supplier_insert_query="insert into `supplier_trays_received`
//     (supplier_received_date,supplier_name,no_of_trays_received)values('$issued_date',
    
//     '$supplier_name',
//     $no_of_trays_issued)";
//    $supplier_sql=mysqli_query($con,$supplier_insert_query);
//     // $select_qry="SELECT * FROM sar_trays ORDER BY id DESC ";
//     // $select_sql=$connect->prepare($select_qry);
//     // $select_sql->execute();
//     // $select_fetch=$select_sql->rowCount();
//     // $select_fetch=$select_sql->fetch(PDO::FETCH_ASSOC);
//     //     $id=$select_fetch["id"];
//     // $total_amt=$select_fetch["total_trays"]+$no_of_trays_received;
//     // $add_supplier_update_query="update sar_trays set total_trays='$total_amt' where id=".$id;
//     // $add_supplier_update_sql=$connect->prepare($add_supplier_update_query);
//     // $add_supplier_update_sql->execute();

//    $balance_qry="SELECT inhand FROM tray_transactions ORDER BY id DESC LIMIT 1 ";
//    $balance_sql=$connect->prepare("$balance_qry");
//    $balance_sql->execute();
//    $bal_row=$balance_sql->fetch(PDO::FETCH_ASSOC);    
//    $balance = $bal_row["inhand"] + $no_of_trays_issued;
//    $tray_trans_qry = "INSERT INTO tray_transactions SET 
//                   date = '$issued_date',
//                   name = '$supplier_name',
//                   category = 'Supplier',
//                   inward = $no_of_trays_issued,
//                   inhand = $balance,
//                    updated_by='$username',
//                   description = 'Direct inward'";
//    // echo $tray_trans_qry;die;
//    $res2=mysqli_query($con,$tray_trans_qry);
//    header('Location: ./Trays-Inward_Outward-Entry.php');
    
// }    
 ?>
       
 <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row col-md-12">
                  <div class=" col-lg-12">
                    
                     
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Tray - Inwards & Outwards</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="post" action="#" class="searchbox">
                           <div class="row col-md-12">   
                           <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label>
                                 <span style="color:red">*</span>
                                 <input type="date" required class="form-control datepicker" value="<?= $date ?>" id="exampleInputdate" name="tray_date" required>
                              </div>
                              
                              <div class="form-group col-md-6">
                                      <label for="exampleFormControlSelect1">Tray Type</label><span style="color:red">*</span>
                              <input list="types" name="type" id="type" required class="form-control">

<datalist id="types">
<option value="" disabled>--Choose Tray Type--</option>
                                            <option value="Small Tray">Small Tray</option>
                                            <option value="Big Tray">Big Tray</option>
                                          
                                        
</datalist>
  </div>
                              </div>
                            <div class="row col-md-12">
                            
                                      <div class="form-group col-md-6">
                                      <label for="exampleFormControlSelect1">Tray Description</label><span style="color:red">*</span>
                              <!-- <input list="descriptions" required name="description" id="description" class="form-control"> -->

<select class="form-control" name="description" id="description">
<option value="--Choose Tray Description--" selected disabled>--Choose Tray Description--</option>
                                            <option value="Supplier Inward">Supplier Inward</option>
                                            <option value="Supplier Outward">Supplier Outward</option>
                                            <option value="Customer Inward">Customer Inward</option>
                                            <option value="Customer Outward">Customer Outward</option>
                                        
</select>

                                       </div>
                                       <!-- <div class="form-group col-md-6">
                                      <label for="exampleFormControlSelect1">Choose Supplier/Customer</label><span style="color:red">*</span>
     
                                       <select id="invoice" class="form-control" name="invoice">
     <option value="">Please Select</option>
     <option value="Customer">Customer</option>
        <option value="Supplier">Supplier</option>
     </select>
                                       </div>                                 -->


                                        <!-- <div class="form-group col-md-4">
                                         <label for="exampleFormControlSelect1">Supplier Name</label><span style="color:red">*</span>
                                         <select class="form-control searchval" id="searchval" name="supplier_name" required>
                                            <option value="">--Choose Supplier Name--</option> -->
                                    <?php
                                       //      $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                       //  	$sel_sql= $connect->prepare($sel_qry);
                            	         //    $sel_sql->execute();
                            	         //   while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	         //        echo '<option value="'.$sel_row["contact_person"].'">'.$sel_row["contact_person"].'</option>';
                            	         //   }
                            	           ?>
<!--                             	          
                            	           </select>
                                      </div> -->
                                      <div class="form-group col-md-6" id="supplier">
                                   <div class="row col-md-12">
                                   <div class="form-group col-md-6">
                                      <label for="exampleFormControlSelect1">Group</label><span style="color:red">*</span>
                                      <select class="form-control" id="grpname" name="grpname">
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
                    <div class="form-group col-md-6">
                    <label for="exampleFormControlSelect1">Supplier</label><span style="color:red">*</span>
                    <select class="form-control" id="supplier_name" name="supplier_name">
                      <option value="">Choose Supplier Name </option>
                       <?php
                        //     $sel_qry = "SELECT * FROM `sar_patti` WHERE payment_status=1 GROUP BY supplier_name";
                        // 	$sel_sql= $connect->prepare($sel_qry);
            	         //    $sel_sql->execute();
            	         //   while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
            	                
            	         //        echo '<option value="'.$sel_row["supplier_id"].'">'.$sel_row["supplier_name"]."_".$sel_row["mobile_number"].'</option>';
            	         //   }
            	           ?>
                      
    
                    </select>     
                    </div>
                    </div>
                                      </div>
                                      <div class="form-group col-md-6" id="custom">
                                    <div class="row col-md-12">
                                       <div class="col-md-6">
                                       <label for="exampleFormControlSelect1">Group</label><span style="color:red">*</span>
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
                                       <div class="col-md-6">
                                         <label for="exampleFormControlSelect1">Customer</label><span style="color:red">*</span>
                    <select class="form-control" id="customer" name="customer">
                      <option value="">Choose Customer Name </option>
                      <?php
                                    //     $sel_qry = "SELECT * FROM `sar_sales_invoice` WHERE payment_status=0 GROUP BY customer_name";
                                    // 	$sel_sql= $connect->prepare($sel_qry);
                        	         //    $sel_sql->execute();
                        	         //   while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                        	                
                        	         //        echo '<option value="'.$sel_row["customer_id"].'">'.$sel_row["customer_name"]."_".$sel_row["mobile_number"].'</option>';
                        	         //   }
                        	           ?>
                    </select>
                    </div>
                    </div>
                                      </div> 
                            </div>
                            <div class="row col-md-12">
                                  
  <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Number Of Trays Issued</label><span style="color:red">*</span>
                                 <input type="number" required class="form-control" id="exampleInputNumber1" name="no_of_tray" min="0" required>
                              </div>
                                       </div>
                                      <input style="position: relative; top:20px;left:2px" type="submit" class="btn btn-primary"name="add_tray" value="Submit">
                                      </div>
                                 
                             
                           </form>
                        </div>
                     </div>
                     
<!--          add_supplier_add_received            
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Customer Inward</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="post">
                           <div class="row col-md-12">  
                           <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Number Of Trays Received</label><span style="color:red">*</span>
                                 <input type="number" class="form-control" id="exampleInputNumber1" name="no_of_trays_received" min="0" required>
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                 <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="customer_received_date" required>
                              </div>
                           </div> 
                           <div class="row col-md-12">  
                               <div class="form-group col-md-6">
                                         <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                                         <select class="form-control searchval3" id="searchval3" name="customer_name" required>
                                            <option value="">--Choose Customer Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct customer_name from `sar_customer` order by customer_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["customer_name"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
                                      </div>
                                      <div class="form-group col-md-6">
                                      <input type="submit" style="position: relative; top:20px;left:25px" class="btn btn-primary" name="add_customer_received" value="Submit">
                                     </div>
                                      </div> 
                              
                              
                           </form>
                        </div>
                     </div>
                  </div><br><br>
                  <div class=" col-lg-6">
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Supplier Outward</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form  method="post" action="#" class="searchbox" enctype="multipart/form-data">
                           <div class="row col-md-12">
                                <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label><span style="color:red">*</span>
                                 <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="issued_date" required>
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Number Of Trays Issued</label>
                                 <span style="color:red">*</span>
                                 <input type="number" class="form-control" id="exampleInputNumber1" name="no_of_trays_issued" min="0" required>
                              </div>
                           
                           </div>
                             <div class="row col-md-12">
                              <div class="form-group col-md-6">
                                         <label for="exampleFormControlSelect1">Supplier Name</label><span style="color:red">*</span>
                                         <select class="form-control searchval1" id="searchval1" name="supplier_name" required>
                                            <option value="">--Choose Supplier Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct contact_person from `sar_supplier` order by contact_person ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["contact_person"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
                                         
                                      </div>
                              
                                      <div class="form-group col-md-6">
                              <input type="submit" class="btn btn-primary" style="position: relative; top:20px;left:25px" name="add_supplier_issued" value="Submit">
                                      </div>
                             </div>
                           </form>
                        </div>
                     </div>
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Customer Outward</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <p></p>
                           <form method="post">
                              <div class="row col-md-12">
                                <div class="form-group col-md-6">
                                 <label for="exampleInputdate">Date </label>
                                 <span style="color:red">*</span>
                                 <input type="date" value="<?= $date ?>" class="form-control datepicker" id="exampleInputdate" name="customer_issued_date" required>
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="exampleInputNumber1">Number Of Trays Issued</label>
                                 <span style="color:red">*</span>
                                 <input type="number" class="form-control" id="exampleInputNumber1" name="no_of_trays_issued" min="0" required>
                              </div>
                            
                              </div>
                              
                              <div class="row col-md-12">
                               <div class="form-group col-md-6">
                                         <label for="exampleFormControlSelect1">Customer Name</label><span style="color:red">*</span>
                                         <select class="form-control searchval2" id="searchval2" name="customer_name" required>
                                            <option value="">--Choose Customer Name--</option>
                                    <?php
                                            $sel_qry = "SELECT distinct customer_name from `sar_customer` order by customer_name ASC ";
                                        	$sel_sql= $connect->prepare($sel_qry);
                            	            $sel_sql->execute();
                            	           while ($sel_row = $sel_sql->fetch(PDO::FETCH_ASSOC)){
                            	                
                            	                echo '<option>'.$sel_row["customer_name"].'</option>';
                            	           }
                            	           ?>
                            	          
                            	           </select>
                                      </div>
                                      <div class="col-md-6">
                                      <input type="submit" style="position: relative; top:20px;left:25px" class="btn btn-primary" name="add_customer_issued" value="Submit">
                                      </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div> -->
               </div>
            </div>
         </div>
     
      <!-- Wrapper END -->
      <!-- Footer -->


<?php
require "footer.php";
?>
<script>
    $(document).ready(function() {
  
     var dtToday = new Date();

   var month = dtToday.getMonth() + 1;
   var day = dtToday.getDate();
   var year = dtToday.getFullYear();

   if (month < 10)
      month = '0' + month.toString();
   if (day < 10)
      day = '0' + day.toString();

   var maxDate = year + '-' + month + '-' + day;
   $('.datepicker').attr('max', maxDate);
    });
    // $("#dc_number").on("change",function(){
    //     var farmer_name=$(this).val();
    //     //alert(dc_number)
    //     $.ajax({
    //             type:"POST",
    //             url:"forms/ajax_request_view.php",
    //             data:{"action":"view_loading_dropdown","farmer_name":farmer_name},
    //             dataType:"json",
    //             success:function(result){
    //                 if(result.status==1){
    //                   var len = result.data.length;
    //                   $("#dc_number_disp").html("");
    //                   for(var i=0;i<len;i++){
    //                       $("#dc_number_disp").append('<option value="'+result.data[i].farmer_no+'">'+result.data[i].farmer_no+'</option>');
    //                   }
    //             }
    //         }
    //     });
    // });
    //   $(".supplier_name").on("change",function(){
    //     var supplier_name=$(this).val();
    //     //alert(employee_mobile)
    //     $.ajax({
    //             type:"POST",
    //             url:"forms/ajax_request_view.php",
    //             data:{"action":"view_name_tray_farmer","supplier_name":supplier_name},
    //             dataType:"json",
    //             success:function(result){
    //                 if(result.status==1){
    //                     if(result.msg=="alreadyexist") {
    //                         $("#supplier_name_disp").html("Farmer Already Exists");
    //                 } else {
    //                     $("#supplier_name_disp").html("");
    //                 }
    //             }
    //         }
    //     });
    // });
     $("#searchval").on("change", function() {
      var contact_person=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_patti_search",
            "contact_person":contact_person
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
   });
      $("#searchval2").on("change", function() {
      var customer_name=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_customer_searchs",
            "customer_name":customer_name
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
   });
      $("#searchval3").on("change", function() {
      var customer_name=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_customer_search",
            "customer_name":customer_name
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
   });
      $("#searchval1").on("change", function() {
      var contact_person=$(this).val();
      //alert(contact_person);
      $.ajax({
         type: "POST",
         url: "forms/ajax_request_view.php",
         data: {
            "action": "view_patti_search",
            "contact_person":contact_person
         },
         dataType: "json",
         success: function(result) {
            if (result.status == 1) {
              // $("#searchval_disp").html("");
              $("#supplier_id").val(result.data.supplier_id).attr('readonly', true);
               $("#contact_number1").val(result.data.contact_number1).attr('readonly', true);
               $("#contact_person").val(result.data.contact_person).attr('readonly', true);
               $("#Address").val(result.data.Address).attr('readonly', true);
            } 
         }

      });
   });
</script>
<script>
    $("#searchval").chosen();
    $("#searchval1").chosen();
     $("#searchval2").chosen();
      $("#searchval3").chosen();
</script>

<script>
   $(document).ready(function(){
    
    $("#description").change(function(){
        var invoice=$(this).find(":selected").val();
    //    alert(invoice);
   if(invoice=="Supplier Inward" || invoice=="Supplier Outward"){
    $("#supplier").show();
   $("#custom").hide();
   $("#supplier").prop('required',true);

 }
   else if(invoice=="Customer Inward" || invoice=="Customer Outward"){
    $("#custom").show();
    $("#supplier").hide();
    $("#custom").prop('required',true);
 }
    });
    //$("#re").hide();
    
    $("#supplier").hide();
   // $("#supplier_name").text("Choose Supplier");
    $("#custom").hide();
   });
</script>

<script>
      $("#grpname").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchgrp","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#supplier_name").empty();
                    $("#supplier_name").append('<option>Choose Supplier Name</option>');
                    for(var i=0;i<len;i++){
                    $("#supplier_name").append('<option value='+result[i].supplier_no+'>'+result[i].contact_person+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});
$("#group").on("change",function(){
        var grp=$(this).val();
        // alert(grp);
        $.ajax({
                type:"POST",
                url:"forms/ajax_request.php",
                data:{"action":"fetchsup","grp":grp},
                dataType:"json",
                success:function(result){
                    var len=result.length;
                    // alert(result.length);
                    $("#customer").empty();
                    $("#customer").append('<option>Choose Customer Name</option>');
                     for(var i=0;i<len;i++){
                    $("#customer").append('<option value='+result[i].customer_no+'>'+result[i].customer_name+'</option>');
                                    }
                                                    // alert(result.contact_person);
	   }
    })
});


</script>