<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database_name = "simple-crud-db";

    $connection = mysqli_connect($servername, $username, $password, $database_name);

    if(!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } 


?>