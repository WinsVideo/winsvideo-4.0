<?php
    ob_start(); //Turns on output buffering 
    session_start();
    
    date_default_timezone_set("Asia/Bangkok");
    // attempt to connect to the database via PDO
    $servername = "localhost";
    $username = "winsvideo";
    $password = "qVwPT2ygVc8AzPKrtxjQZg2zGg6EjjRXtUdv9W9FgMZA4RXhGpDT4YKpWpD3FcfsFjt5k34ZHu6PxYuQDhPYtvP67bHFF6YrrBfZXL8fAYKKJzBXSanUkjyv2QKNBfBT8y8eb3HEfg9JGZtmSCnwBzYGZkxjn9XSbfQpa4sCqCwnFs7TDx44P5yPqVK6se3gPb6vTfsRnAkHhzJBtaLhqmtbMhMkYUDjE57GvZdD3g95yusb5wgU5s5cBSHB4hRg";
    $dbname = "winsvideo";

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
    header('Content-Type: text/html; charset=utf-8');
?>