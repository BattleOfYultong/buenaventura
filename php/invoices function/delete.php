<?php
  include '../../backends/connections.php';
        
$id = $_GET['InvoiceID'];

$sql = "DELETE FROM invoice WHERE InvoiceID = $id";

 if($connections->query($sql) === TRUE){
        echo "<script>window.location.href='../../backends/invoices.php?Delete_success=true'</script>";
    }
    else{
        echo "Error: " .$sql . "br" .$connections->error;
    }