<?php
// Define error variables and initialize them to empty strings
$loginEmailErr = $loginPasswordErr = $signupEmailErr = $signupPasswordErr = $confirmPasswordErr = "";

include 'backends/connections.php'; // Include the connections.php file for database connection

// Function to handle file upload and return file path
function handleFileUpload() {
    if (!empty($_FILES["imageUpload"]["name"])) {
        $targetDirectory = "uploads/"; // Directory where uploaded files will be stored
        $targetFilePath = $targetDirectory . basename($_FILES["imageUpload"]["name"]);
        $fileUploaded = move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFilePath);
        return $fileUploaded ? $targetFilePath : null;
    } else {
        return null; // No image uploaded
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["loginSubmit"])) {
        // Handle login
        $loginEmail = $_POST["email"];
        $loginPassword = $_POST["password"];

        // Validate input fields
        if (empty($loginEmail)) {
            $loginEmailErr = "Email is required";
        }
        if (empty($loginPassword)) {
            $loginPasswordErr = "Password is required";
        }

        // Perform login logic
        $query = "SELECT * FROM logintbl WHERE email = ?";
        $stmt = $connections->prepare($query);
        $stmt->bind_param("s", $loginEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($loginPassword, $row['password'])) {
                // Password is correct
                if ($row['account_type'] == 1) {
                    // Redirect admin to admin panel
                    header("Location: backends/admin.php");
                    exit();
                } else {
                    // Redirect user to user panel or set session
                    header("Location: user_panel.php");
                    exit();
                }
            } else {
                // Password is incorrect
                $loginPasswordErr = "Incorrect password";
            }
        } else {
            // User not found
            $loginEmailErr = "User not found";
        }
    } elseif (isset($_POST["signupSubmit"])) {
        // Handle signup
        $signupEmail = $_POST["signupEmail"];
        $signupPassword = $_POST["signupPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        // Validate input fields
        if (empty($signupEmail)) {
            $signupEmailErr = "Email is required";
        }
        if (empty($signupPassword)) {
            $signupPasswordErr = "Password is required";
        }
        if (empty($confirmPassword)) {
            $confirmPasswordErr = "Please confirm your password";
        }

        // Insert data into database with default account type 2 (customer)
        $profile_pic_path = handleFileUpload(); // Get file path
        $sql = "INSERT INTO logintbl (email, password, profile_pic_path, acc_creation_date, account_type) VALUES (?, ?, ?, NOW(), 2)";
        $stmt = $connections->prepare($sql);
        $hashedPassword = password_hash($signupPassword, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $signupEmail, $hashedPassword, $profile_pic_path);

        // Check for errors before executing SQL query
        if (empty($signupEmailErr) && empty($signupPasswordErr) && empty($confirmPasswordErr)) {
            // Execute SQL query
            $stmt->execute();

            // Check for errors
            if ($stmt->errno) {
                echo "Failed to insert record: " . $stmt->error;
            } else {
                echo "Record inserted successfully.";
            }
        }

        $stmt->close();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="styles/main.css">
    <title>Sign Up / Login</title>
</head>
<body>
    
<form action="" class="login" id="loginForm" method="post"><!--LOGIN FORM-->
    <center>
        <h1>LOG IN</h1>
        <label for="loginEmail">EMAIL</label><br>
        <input type="text" id="loginEmail" name="loginEmail"><br>
        <span class="error" id="loginEmailErr"><?php echo $loginEmailErr; ?></span><br><br>

        <label for="loginPassword">PASSWORD</label><br>
        <span class="passField"><input type="password" id="loginPassword" name="loginPassword"><img src="styles/imgs/eye.png" alt="eye-icon" class="eye-icon" onclick="togglePasswordVisibility('loginPassword', this)"><br></span>
        <span class="error" id="loginPasswordErr"><?php echo $loginPasswordErr; ?></span><br><br>

        <input type="submit" value="ENTER" id="loginButton" name="loginSubmit"><br><br><br>
        <button type="button" id="registerButton">Don't have an account? Register Here!</button>
    </center>
</form>

<form action="" class="signup" id="signupForm" method="post" style="display: none;" enctype="multipart/form-data"><!--SIGNUP FORM-->
    <center>
    <h1>SIGN UP</h1>
        <div class="signup-flex-container">
                <div class="input-texts">
                    <label for="signupEmail">EMAIL</label><br>
                    <input type="text" id="signupEmail" name="signupEmail"><br>
                    <span class="error" id="signupEmailErr"><?php echo $signupEmailErr; ?></span><br>

                    <label for="signupPassword">PASSWORD</label><br>
                    <span class="passField">
                        <input type="password" id="signupPassword" name="signupPassword"><img src="styles/imgs/eye.png" alt="eye-icon" class="eye-icon" onclick="togglePasswordVisibility('signupPassword', this)"><br>
                    </span>
                    <span class="error" id="signupPasswordErr"><?php echo $signupPasswordErr; ?></span><br>

                    <label for="confirmPassword">CONFIRM PASSWORD</label><br>
                    <span class="passField">
                        <input type="password" id="confirmPassword" name="confirmPassword"><img src="styles/imgs/eye.png" alt="eye-icon" class="eye-icon" onclick="togglePasswordVisibility('confirmPassword', this)"><br>
                    </span>
                    <span class="error" id="confirmPasswordErr"><?php echo $confirmPasswordErr; ?></span><br>

                </div>
                <div class="input-image">
                    <div class="image-preview-box" id="imagePreview">

                    </div>
                    <input type="file" id="imageUpload" onchange="previewImage();" style="display:none;" name="imageUpload">
                        <label for="imageUpload" class="image-upload-button">
                            <img src="styles/imgs/upload.png" alt="upload-icon">
                        </label>
            </div>
        </div>
        <input type="submit" value="REGISTER" id="registerSubmit" name="signupSubmit"><br>
        <button type="button" id="loginLink">Already have an account? Log In!</button>
    </center>
</form>
<script src="loginfrontend.js"></script>
</body>
</html>
