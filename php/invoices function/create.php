<?php


include '../../backends/connections.php';

mysqli_query($connections, "SET FOREIGN_KEY_CHECKS = 0");



function generateInvoiceNumber() {
    return uniqid('INV_');
}

// Usage example:

$InvoiceNumber = generateInvoiceNumber();
$InvoiceDate = $_POST['inv_date'];
$userID = $_POST['userid'];
$InvoicedueDate = $_POST['due_date'];
$AmountDue = $_POST['amount_due'];
$status = $_POST['payment_status'];



$sql = "INSERT INTO invoice ( InvoiceNumber, InvoiceDate, DueDate, AmountDue, PaymentStatus, userID) 
        VALUES ('$InvoiceNumber', '$InvoiceDate', '$InvoicedueDate', '$AmountDue', '$status', $userID)";

if($connections->query($sql) === TRUE) {
    echo "<script>window.location.href='../../backends/invoices.php?Create_success=true'</script>";
} else {
    echo "Error: " . $sql . "<br>" . $connections->error;
}
