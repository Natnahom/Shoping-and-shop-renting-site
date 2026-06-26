<?php
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "shopingdb";
    $conn = "";

    try{
        $conn = mysqli_connect($dbHost,
                             $dbUsername, 
                             $dbPassword, 
                             $dbName);
    }
    catch(mysqli_sql_exception){
        echo "Connection failed";
    }
?>