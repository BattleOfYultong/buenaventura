document.getElementById("registerButton").addEventListener("click", function() {
    // Play exit animation for the login form
    var loginForm = document.getElementById("loginForm");
    loginForm.style.animation = "exitAnim 1s forwards";
    loginForm.addEventListener("animationend", function() {
        // Hide the login form after exit animation completes
        loginForm.style.display = "none";

        // Play entrance animation for the signup form after exit animation completes
        var signupForm = document.getElementById("signupForm");
        signupForm.style.animation = "entranceAnim 1s forwards";
        signupForm.style.display = "block";
    }, { once: true }); // Remove the event listener after it's triggered once
});

document.getElementById("loginLink").addEventListener("click", function() {
    // Play exit animation for the signup form
    var signupForm = document.getElementById("signupForm");
    signupForm.style.animation = "exitAnim 1s forwards";
    signupForm.addEventListener("animationend", function() {
        // Hide the signup form after exit animation completes
        signupForm.style.display = "none";

        // Play entrance animation for the login form after exit animation completes
        var loginForm = document.getElementById("loginForm");
        loginForm.style.animation = "entranceAnim 1s forwards";
        loginForm.style.display = "block";
    }, { once: true }); // Remove the event listener after it's triggered once
});

function togglePasswordVisibility(passwordFieldId, eyeIcon) {
  var passwordInput = document.getElementById(passwordFieldId);

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    eyeIcon.src = "styles/imgs/eye-off.png"; // Change image to eye-off
  } else {
    passwordInput.type = "password";
    eyeIcon.src = "styles/imgs/eye.png"; // Change image back to eye
  }
}

function previewImage() {
  var preview = document.querySelector('#imagePreview');
  var file = document.querySelector('#imageUpload').files[0];
  var reader = new FileReader();

  reader.onloadend = function () {
      preview.style.backgroundImage = "url(" + reader.result + ")";
      preview.style.backgroundSize = "cover";
  }

  if (file) {
      reader.readAsDataURL(file);
  } else {
      preview.style.backgroundImage = null;
  }
}

