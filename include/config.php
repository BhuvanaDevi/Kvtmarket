<?php
session_start();
ob_start();
ini_set("display_errors",0);
date_default_timezone_set('Asia/kolkata');

  //  $dbHost = "localhost";
  //   $dbUsername="udhaarsu_ab";
  //   $dbPassword="lane@123";
  //   $dbName="udhaarsu_ab";

    
  // $dbHost = "localhost";
  // $dbUsername="udhaarsu_kvt";
  // $dbPassword="GbC8EU05^e*#";
  // $dbName="udhaarsu_kvt";
  
  //  $dbHost = "localhost";
  //   $dbUsername="root";
  //   $dbPassword="";
  //   $dbName="udhaarsu_ab";

  
  $dbHost = "localhost";
  $dbUsername="root";
  $dbPassword="";
  $dbName="kvt";

    try {
      $connect = new PDO("mysql:host=$dbHost;dbname=$dbName;", "$dbUsername", "$dbPassword", array(
          PDO::MYSQL_ATTR_LOCAL_INFILE => true,
      ));
      // set the PDO error mode to exception
      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo "Connected successfully ".$dbName;//exit;
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
// exit;
 $con = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if($con) {
    //echo "connected";
} else {
    echo "Failed to connect to MySQL: " . $con;
    exit();
}

?>