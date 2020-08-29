<?php
 $dbh = "mysql:host=localhost; dbname=ticketadmin";
 $username = "root";
 $password = "";

 try {
     $pdo = new PDO($dbh, $username, $password);
 } catch (Exception $e) {
     echo json_encode(array('status' => 500, 'message' => 'Database Connection Error'));
     die();
 }
?>