<?php
   $host = "localhost";
   $username = "root";
   $password = "";
   $dbname = "it12l";

   try {
      $conn = new PDO("mysql:host=$host; dbname=$dbname;",$username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }
   catch(PDOException $e) {
    echo "Error";
     
   }
?>