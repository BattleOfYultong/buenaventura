<?php



include '../../backends/connections.php';

$invoiceID = $_POST['invoiceID'];
$InvoiceDate = $_POST['inv_date'];
$InvoicedueDate = $_POST['inv_due_date'];
$AmountDue = $_POST['inv_due'];
$PaymentStatus = $_POST['inv_status'];


$sql = "UPDATE invoice SET InvoiceDate = '$InvoiceDate ', DueDate  = '$InvoicedueDate ', 
AmountDue = $AmountDue, PaymentStatus = '$PaymentStatus' WHERE InvoiceID = $invoiceID";


if($connections->query($sql) === TRUE){
        echo "<script>window.location.href='../../backends/invoices.php?edit_success=true'</script>";
    }
    else{
        echo "Error: " .$sql . "br" .$connections->error;
    }



