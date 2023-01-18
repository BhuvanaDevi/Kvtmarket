<?php
session_start();
ob_start();
ini_set("display_errors",0);
date_default_timezone_set('Asia/kolkata');

    $dbHost = "localhost";
    $dbUsername="lanecqgh_sar";
    $dbPassword="sar@123";
    $dbName="lanecqgh_sar_tomato_erp";


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
//exit;
 $con = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if($con) {
    //echo "connected";
} else {
    echo "Failed to connect to MySQL: " . $con;
    exit();
}

?>