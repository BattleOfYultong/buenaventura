<?php
include 'backends/connections.php';

// Define variables and initialize with empty values
$signupEmailErr = $signupPasswordErr = $confirmPasswordErr = "";
$signupEmail = $signupPassword = $confirmPassword = "";

// Function to sanitize and validate input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process signup form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signupSubmit'])) {
    // Validate email
    if (empty($_POST["signupEmail"])) {
        $signupEmailErr = "Email is required";
    } else {
        $signupEmail = test_input($_POST["signupEmail"]);
        // Check if email is valid
        if (!filter_var($signupEmail, FILTER_VALIDATE_EMAIL)) {
            $signupEmailErr = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST["signupPassword"])) {
        $signupPasswordErr = "Password is required";
    } else {
        $signupPassword = test_input($_POST["signupPassword"]);
        // Password validation logic (if any)
    }

    // Validate confirm password
    if (empty($_POST["confirmPassword"])) {
        $confirmPasswordErr = "Please confirm password";
    } else {
        $confirmPassword = test_input($_POST["confirmPassword"]);
        if ($confirmPassword != $signupPassword) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    if (empty($signupEmailErr) && empty($signupPasswordErr) && empty($confirmPasswordErr)) {
        // Perform signup
        // Insert user data into database
        $query = "INSERT INTO logintbl (email, password, account_type, acc_creation_date) VALUES ('$signupEmail', '$signupPassword', 2, CURDATE())";
        if (mysqli_query($connections, $query)) {
            // Signup successful, redirect user to login page
            header("Location: login.php");
            exit();
        } else {
            // Error handling
            echo "Error: " . $query . "<br>" . mysqli_error($connections);
        }
    }
}

// Close connection
mysqli_close($connections);
?>
