<?php
    include '../backends/connections.php';
        
$id = $_POST['userID'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "UPDATE logintbl SET  email = '$email', password = '$password' WHERE userID = $id";

 if($connections->query($sql) === TRUE){
        echo "<script>window.location.href='../backends/admin.php?Edit_success=true'</script>";
    }
    else{
        echo "Error: " .$sql . "br" .$connections->error;
    }