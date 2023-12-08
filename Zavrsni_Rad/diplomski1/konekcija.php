<?php
    $conn = mysqli_connect("localhost","root","","dostava_hrane1");  
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>