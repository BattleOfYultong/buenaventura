<?php
session_start();


include '../backends/connections.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $Email = $_POST['email'];
    $Password = $_POST['password'];

   
    if (!empty($_FILES["photo"]["tmp_name"]) && is_uploaded_file($_FILES["photo"]["tmp_name"])) {
        
        $targetDirectory = "../uploads/"; 
        $targetFile = $targetDirectory . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

       
        if ($_FILES["photo"]["size"] > 50000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

       
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            
            $uniqueFilename = uniqid() . "." . $imageFileType;
            $targetFilePath = $targetDirectory . $uniqueFilename;

            
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
              
                $sql = "INSERT INTO logintbl (email, password, profile_pic_path) VALUES ('$Email','$Password','$uniqueFilename')";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        
        $defaultImage = "../uploads/default.png";
        $sql = "INSERT INTO login_tbl (Email, Password, profile_pic_path) VALUES ('$Email','$Password','$defaultImage')";
    }

   


    

   
    if ($connections->query($sql) === TRUE) {
       
        echo "<script>window.location.href='../backends/admin.php?Create_success=true';</script>";
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $connections->error;
    }

   


}
?>