<?php
session_start();
include 'backends/connections.php';

$loginEmailErr = $loginPasswordErr = $signupEmailErr = $signupPasswordErr = $confirmPasswordErr = $imageUploadErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['loginSubmit'])) {
        $loginEmail = $_POST['loginEmail'];
        $loginPassword = $_POST['loginPassword'];

        if (empty($loginEmail)) {
            $loginEmailErr = "Email is required.";
        }
        if (empty($loginPassword)) {
            $loginPasswordErr = "Password is required.";
        }

        if (empty($loginEmailErr) && empty($loginPasswordErr)) {
            $query = "SELECT * FROM logintbl WHERE email = ? AND password = ?";
            $stmt = $connections->prepare($query);
            $stmt->bind_param("ss", $loginEmail, $loginPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if ($user['account_type'] == 2) {
                    $_SESSION['$loginEmail'] = $loginEmail;
                    echo "<script>alert('Login successful!'); window.location.href='backends/user.php';</script>";
                } elseif ($user['account_type'] == 1) {
                    $_SESSION['$loginEmail'] = $loginEmail;
                    echo "<script>alert('Login successful!'); window.location.href='backends/admin.php';</script>";
                }
            } else {
                $loginPasswordErr = "Incorrect email or password.";
            }
        }
    } elseif (isset($_POST['signupSubmit'])) {
        $signupEmail = $_POST['signupEmail'];
        $signupPassword = $_POST['signupPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        $imageUpload = $_FILES['imageUpload'];

        if (empty($signupEmail)) {
            $signupEmailErr = "This field is required.";
        }
        if (empty($signupPassword)) {
            $signupPasswordErr = "This field is required.";
        }
        if (empty($confirmPassword)) {
            $confirmPasswordErr = "This field is required.";
        } elseif ($signupPassword !== $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match.";
        }

        if ($imageUpload['error'] != UPLOAD_ERR_OK) {
            $imageUploadErr = "Image upload failed.";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($imageUpload['type'], $allowedTypes)) {
                $imageUploadErr = "Only JPG, PNG, and GIF files are allowed.";
            }
        }

        if (empty($signupEmailErr) && empty($signupPasswordErr) && empty($confirmPasswordErr) && empty($imageUploadErr)) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($imageUpload["name"]);
            $filename = basename($targetFile); // Get only the filename

            if (move_uploaded_file($imageUpload["tmp_name"], $targetFile)) {
                $accountType = 2; // default user registration
                $query = "INSERT INTO logintbl (email, password, profile_pic_path, acc_creation_date, account_type) VALUES (?, ?, ?, NOW(), ?)";
                $stmt = $connections->prepare($query);
                $stmt->bind_param("ssss", $signupEmail, $signupPassword, $filename, $accountType);
                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful!'); window.location.href='backends/user.php';</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                $imageUploadErr = "Failed to move uploaded file.";
            }
        }
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
<form action="" class="login" id="loginForm" method="post">
    <center>
        <h1>LOG IN</h1>
        <label for="loginEmail">EMAIL</label><br>
        <input type="text" id="loginEmail" name="loginEmail"><br>
        <span class="error" id="loginEmailErr"><?php echo $loginEmailErr; ?></span><br><br>

        <label for="loginPassword">PASSWORD</label><br>
        <span class="passField">
            <input type="password" id="loginPassword" name="loginPassword"><img src="styles/imgs/eye.png" alt="eye-icon" class="eye-icon" onclick="togglePasswordVisibility('loginPassword', this)"><br>
        </span>
        <span class="error" id="loginPasswordErr"><?php echo $loginPasswordErr; ?></span><br><br>

        <input type="submit" value="ENTER" id="loginButton" name="loginSubmit"><br><br><br>
        <button type="button" id="registerButton">Don't have an account? Register Here!</button>
    </center>
</form>

<form action="" class="signup" id="signupForm" method="post" style="display: none;" enctype="multipart/form-data">
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
                <div class="image-preview-box" id="imagePreview"></div>
                <input type="file" id="imageUpload" onchange="previewImage();" style="display:none;" name="imageUpload">
                <label for="imageUpload" class="image-upload-button">
                    <img src="styles/imgs/upload.png" alt="upload-icon">
                </label>
                <span class="error" id="imageUploadErr"><?php echo $imageUploadErr; ?></span><br>
            </div>
        </div>
        <input type="submit" value="REGISTER" id="registerSubmit" name="signupSubmit"><br>
        <button type="button" id="loginLink">Already have an account? Log In!</button>
    </center>
</form>
<script src="loginfrontend.js"></script>
</body>
</html>
