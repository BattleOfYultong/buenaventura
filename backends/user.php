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
    <title>Admin</title>
</head>
<body>
<nav>
        <span class="profile-container">
            <img src="<?php echo $picture ?>" alt="Admin Profile Pic" class="admin-pic"><br><br>
            <center><h3><?php echo "$loginEmail" ?></h3></center><br>
        </span>
        <div class="nav-container">
            <button><img src="../styles/imgs/dashboard.png" alt="dashboard-icon"> Dashboard</button><br>
            <button><img src="../styles/imgs/users.png" alt="users-icon"> Users</button><br>
           
            <button><img src="../styles/imgs/settings.png" alt="settings-icon"> Settings</button><br>
            <a href="../php/logout.php"> <!--LOG OUT FUNCTION-->
                <button type="submit" name="logout"><img src="../styles/imgs/logout.png" alt="logout-icon"> Log-Out</button>
            </a>
        </div>
    </nav>
    <div class="main-container">
           
    </div> 

 <?php
if (isset($_GET['Edit_success']) && $_GET['Edit_success'] == 'true') {
    echo "
    <script>
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Slot Has been Edited',
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
            title: 'User Has Been Deleted',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    ";
}
?>
</body>
<script>
    function confirmDelete(userID) {
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
                window.location.href = "../php/delete.php?userID=" + userID;
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

function editContainer(id) {
    console.log("Clicked ID: " + id);
    $.ajax({
        type: 'POST',
        url: 'fetch.php',
        data: { id: id },
        success: function(response) {
            console.log("Response: " + response); 
            
            var data = JSON.parse(response);
            if (data.error) {
                console.error(data.error);
                return;
            }

            console.log(data); 

            $('#editID').val('');
            $('#editEmail').val('');
            $('#editPassword').val('');
            
            $('#editID').val(data.userID);
            $('#editEmail').val(data.email);
             $('#editPassword').val(data.password);

            if (data.profile_pic_path) {
                $('#editImage').attr('src', '../uploads/' + data.profile_pic_path);
            } else {
                $('#editImage').attr('src', '../uploads/default.png');
            }
            
            $('.editForm').addClass('show');
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function closeEdit() {
    const editForm = document.querySelector('.editForm');
    editForm.classList.remove('show');
}
</script>
</html>