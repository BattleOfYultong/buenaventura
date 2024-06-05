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
    previewImg.src = "#";
    previewImg.style.display = "none";
  }
}
