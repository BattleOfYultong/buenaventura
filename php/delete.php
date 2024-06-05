<?php
    include '../backends/connections.php';
        
$id = $_GET['userID'];

$sql = "DELETE FROM logintbl WHERE userID = $id";

 if($connections->query($sql) === TRUE){
        echo "<script>window.location.href='../backends/admin.php?Delete_success=true'</script>";
    }
    else{
        echo "Error: " .$sql . "br" .$connections->error;
    }