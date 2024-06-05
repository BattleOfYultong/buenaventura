<?php
    session_start();
    include 'connections.php';
    if(isset($_SESSION['$loginEmail'])){
        $loginEmail = $_SESSION['$loginEmail'];

        $sqlfetchprofile = "SELECT *FROM logintbl WHERE email = '$loginEmail'";
        $sqlfetchresult = mysqli_query($connections, $sqlfetchprofile);

        if($sqlfetchprofile && mysqli_num_rows($sqlfetchresult) > 0){
            $row = mysqli_fetch_assoc($sqlfetchresult);
            $picture = "../uploads/" .$row['profile_pic_path'];
            
        }
    }
    else{
        echo "<script>window.location.href='../login.php?not_in_session=true';</script>";
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../Sweetalert/sweetalert2.min.css">
    <script src="../SweetAlert/sweetalert2.all.min.js"></script>
    <script src="../Jquery/jquery.js"></script>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <title>Admin - Invoice</title>
</head>
<body>
<nav>
        <span class="profile-container">
            <img src="<?php echo $picture ?>" alt="Admin Profile Pic" class="admin-pic"><br><br>
            <center><h3><?php echo "$loginEmail" ?></h3></center><br>
        </span>
        <div class="nav-container">
            <button><img src="../styles/imgs/dashboard.png" alt="dashboard-icon"> Dashboard</button><br>
             <a href="admin.php">
            <button class=""><img src="../styles/imgs/users.png" alt="users-icon"> Users</button><br>
            </a>
            <button class="navbar-parents"><img src="../styles/imgs/payable.png" alt="payable-icon"> Accounts Payable</button><br>
            <div class="parent-contents">
                <a href="invoices.php">
                    <button class="active-nav">>> Invoices</button><br> 
                </a>    
                    <button>>> Payments</button><br>
                    <button>>> Vendor Management</button><br>
                    <button>>> Expense Tracking</button><br>
            </div>
            <button class="navbar-parents"><img src="../styles/imgs/receive.png" alt="receive-icon"> Accounts Receivable</button>
            <div class="parent-contents">
                    <button>>> Invoices</button><br> 
                    <button>>> Payments Received</button><br>
                    <button>>> Customer Management</button><br>
                    <button>>> Revenue Tracking</button><br>
            </div>
            <button><img src="../styles/imgs/settings.png" alt="settings-icon"> Settings</button><br>
            <a href="../php/logout.php"> <!--LOG OUT FUNCTION-->
                <button type="submit" name="logout"><img src="../styles/imgs/logout.png" alt="logout-icon"> Log-Out</button>
            </a>
        </div>
    </nav>
    <div class="main-container">
            <div class="create-btn">
                <button onclick="openContainer()">Create Invoice</button>
            </div>

             <table>
        <thead>
            <tr>
                <th>Profile</th>
                <th>User ID</th>
                <th>Email</th>
                <th>InvoiceID</th>
                
                <th>InvoiceNumber</th>
                <th>InvoiceDate</th>
                <th>DueDate</th>
                <th>AmountDue</th>
                <th>PaymentStatus</th>
                <th>Action</th>
                
                
            </tr>
        </thead>
       <tbody>
    <?php
    $sql = "
        SELECT 
            invoice.InvoiceID, 
            invoice.VendorID, 
            invoice.InvoiceNumber, 
            invoice.InvoiceDate, 
            invoice.DueDate, 
            invoice.AmountDue, 
            invoice.PaymentStatus, 
            logintbl.userID, 
            logintbl.email,
            logintbl.profile_pic_path
        FROM 
            invoice 
        INNER JOIN 
            logintbl 
        ON 
            invoice.userID = logintbl.userID";
    $result = $connections->query($sql);

    if (!$result) {
        die("Error: " . $connections->error);
    }
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
            <tr>
                <td><img src="../uploads/' . $row['profile_pic_path'] . '" alt="Profile Photo"></td>
                <td>' . $row['userID'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['InvoiceID'] . '</td>
               
                <td>' . $row['InvoiceNumber'] . '</td>
                <td>' . $row['InvoiceDate'] . '</td>
                <td>' . $row['DueDate'] . '</td>
                <td>' . $row['AmountDue'] . '</td>
                <td>' . $row['PaymentStatus'] . '</td>
                  <td>
                           <div class="action-container">
                                <button onclick="editInvoiceContainer(' . $row['InvoiceID'] . ');" class="edit-btn" id="editBtn_' . $row['InvoiceID'] . '">Edit</button>
                                <button onclick="confirmDelete(\'' . $row['InvoiceID'] . '\');">Delete</button>         
                            </div>
                        </td>
              
               
            </tr>';
        }
    } else {
        echo '<tr><td colspan="10">No records found</td></tr>';
    }
    $connections->close();
    ?>
</tbody>
    </table>
                                                <?php
                        include 'connections.php';

                        // Fetch userID and email from logintbl
                        $query2 = "SELECT userID, email, account_type FROM logintbl";
                        $resultquery2 = $connections->query($query2);

                        $users = [];
                        if ($resultquery2->num_rows > 0) {
                            while($row = $resultquery2->fetch_assoc()) {
                                // Skip users with account_type 1
                                if ($row['account_type'] == 1) {
                                    continue;
                                }
                                $users[] = ['userID' => $row['userID'], 'email' => $row['email']];
                            }
                        }
                        $connections->close();
?>


            <form class="createform" action="../php/invoices function/create.php" method="post" enctype="multipart/form-data">
                <div class="formheader">
                    <h1>Create 
                        <br>Invoice</h1>
                    <i onclick="closeContainer()" class="fa-solid fa-circle-xmark exitbtn"></i>
                </div>
                <div class="formwrapper">
                   
                     <div class="inputbox">
                        <label for="">userID</label>
                        <select name="userid">
                            <?php foreach ($users as $user): ?>
                                    <option value="<?php echo htmlspecialchars($user['userID']); ?>">
                                        <?php echo htmlspecialchars($user['email']) . ' (' . htmlspecialchars($user['userID']) . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="inputbox">
                        <label for="">Amount Due</label>
                        <input name="amount_due" type="number">
                    </div>

                     <div class="inputbox">
                        <label for="">Status</label>
                     
                        <select name="payment_status" id="payment_status">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="due">Due</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>

                       <div class="inputbox">
                        <label for="">Invoice Date</label>
                        <input name="inv_date" type="date">
                    </div>                  


                    <div class="inputbox">
                        <label for="">Due Date</label>
                        <input name="due_date" type="date">
                    </div>                 

                     <div class="inputsubmit">
                        <input type="submit" value="Submit">
                    </div>
                </div>
            </form>

                            <form class="editInvoiceForm" action="../php/invoices function/edit.php" method="post" enctype="multipart/form-data">
                        <div class="formheader">
                            <h1>Edit Invoice</h1>
                            <i onclick="closeEditInvoice()" class="fa-solid fa-circle-xmark exitbtn"></i>
                        </div>
                        <div class="formwrapper">

                            <div class="inputbox">
                                <label for="editInvoiceID">Invoice ID (not editable)</label>
                                <input name="invoiceID" id="editInvoiceID" type="text" readonly>
                            </div>

                            <div class="inputbox">
                                <label for="editInvoiceID">UserID (not editable)</label>
                                <input name="userID" id="edituserID" type="text" readonly>
                            </div>

                            <div class="inputbox">
                                <label for="editInvoiceID">Email (not editable)</label>
                                <input name="editEmail" id="editEmail" type="text" readonly>
                            </div>

                          

                            <div class="inputbox">
                                <label for="editInvoiceID">Invoice Number (not editable)</label>
                                <input name="invoicenumber" id="editInvoiceNumber" type="text" readonly>
                            </div>

                            <div class="inputbox">
                                <label for="editInvoiceID">Invoice Date</label>
                                <input name="inv_date" id="editInvoiceDate" type="date">
                            </div>

                            <div class="inputbox">
                                <label for="editInvoiceID">Invoice Due Date</label>
                                <input name="inv_due_date" id="editDueDate" type="date">
                            </div>

                            <div class="inputbox">
                                <label for="editInvoiceID">Amount Due</label>
                                <input name="inv_due" id="editAmountDue" type="number">
                            </div>

                                   <div class="inputbox">
                                    <label for="editPaymentStatus">Payment Status</label>
                                    <select name="inv_status" id="editPaymentStatus">
                                       
                                    </select>
                                </div>
                           
                            <div class="inputsubmit">
                                <input type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
    </div>

    <?php
    if (isset($_GET['Create_success']) && $_GET['Create_success'] == 'true') {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Invoice Has been Created',
                timer: 2000,
                showConfirmButton: false,
                position: 'top',
            });
        </script>
        ";
    }
    ?> 

 <?php
if (isset($_GET['edit_success']) && $_GET['edit_success'] == 'true') {
    echo "
    <script>
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Invoice Has been Edited',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    ";
}
?>

<?php
if (isset($_GET['Delete_success']) && $_GET['Delete_success'] == 'true') {
    echo "
    <script>
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Invoice Has been Deleted',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    ";
}
?>
</body>
<script>
    function confirmDelete(InvoiceID) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FAEF5D',
            cancelButtonColor: '#FAEF5D',
            confirmButtonText: '<span style="color: black">Yes, delete it!</span>', // Set color to black
            cancelButtonText: '<span style="color: black">Cancel</span>' // Set color to black
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../php/invoices function/delete.php?InvoiceID=" + InvoiceID;
            }
        });
    }
</script>

<script>
function previewPhoto() {
    const fileInput = document.getElementById("imgup");
    const previewImg = document.getElementById("imagedis");
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.src = "../uploads/default.png";
        previewImg.style.display = "none";
    }
}

function previewEditPhoto() {
    const fileInput = document.getElementById("editImgup");
    const previewImg = document.getElementById("editImage");
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.src = "../uploads/default.png";
        previewImg.style.display = "none";
    }
}

function openContainer() {
    const createForm = document.querySelector('.createform');
    createForm.classList.add('show');
}

function closeContainer() {
    const createForm = document.querySelector('.createform');
    createForm.classList.remove('show');
}
</script>

<script>
function editInvoiceContainer(id) {
    console.log("Clicked Invoice ID: " + id);
    
    // Send an AJAX request to fetch invoice details
    $.ajax({
        type: 'POST',
        url: 'fetchinvoice.php', // Replace 'fetch_invoice.php' with the correct path to your PHP script
        data: { id: id },
        success: function(response) {
            console.log("Response: " + response);
            
            // Parse the JSON response
            var data = JSON.parse(response);
            
            // Check if there's an error in the response
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Update the form fields with the fetched invoice data
            $('#edituserID').val(data[0].userID);
            $('#editEmail').val(data[0].email);
            $('#editInvoiceID').val(data[0].InvoiceID);
            $('#editVendorID').val(data[0].VendorID);
            $('#editInvoiceNumber').val(data[0].InvoiceNumber);
            $('#editInvoiceDate').val(data[0].InvoiceDate);
            $('#editDueDate').val(data[0].DueDate);
            $('#editAmountDue').val(data[0].AmountDue);

            // Populate the payment status dropdown
            populatePaymentStatusDropdown(data[0].PaymentStatus);
            
            // Show the edit form
            $('.editInvoiceForm').addClass('show');
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function populatePaymentStatusDropdown(selectedStatus) {
    // Array of payment status options
    var paymentStatusOptions = ['paid', 'pending', 'due', 'overdue'];

    // Select dropdown element
    var select = $('#editPaymentStatus');

    // Clear previous options
    select.empty();

    // Add each payment status option to the dropdown
    paymentStatusOptions.forEach(function(status) {
        var option = $('<option></option>').attr('value', status).text(status);
        if (status === selectedStatus) {
            option.attr('selected', 'selected');
        }
        select.append(option);
    });
}
</script>


<script>

    function closeEditInvoice() {
        const editForm = document.querySelector('.editInvoiceForm');
        editForm.classList.remove('show');
    }
</script>
</html>