<?php
include 'connections.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Fetch user and invoice details using INNER JOIN based on InvoiceID
    $query = "SELECT logintbl.userID, logintbl.email, logintbl.profile_pic_path, logintbl.password,
                      invoice.InvoiceID, invoice.VendorID, invoice.InvoiceNumber, invoice.InvoiceDate, 
                      invoice.DueDate, invoice.AmountDue, invoice.PaymentStatus
               FROM logintbl
               INNER JOIN invoice ON logintbl.userID = invoice.userID
               WHERE invoice.InvoiceID = ?";
               
    $stmt = $connections->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the data array to store the fetched results
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        $data['error'] = 'Invoice not found';
    }

    echo json_encode($data);
}
?>
