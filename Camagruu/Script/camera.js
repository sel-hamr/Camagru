function showDivCameraCapture() {
  document.getElementById("DivCameraCapture").style.display = "block";
  document.getElementById("DivUploadImageCapture").style.display = "none";
  document.getElementById("camera_tab_slide").style.backgroundColor = "#00adb5";
  document.getElementById("upload_tab_slide").style.backgroundColor = "white";
}

function showDivUploadImageCapture() {
  document.getElementById("DivUploadImageCapture").style.display = "block";
  document.getElementById("DivCameraCapture").style.display = "none";
  document.getElementById("upload_tab_slide").style.backgroundColor = "#00adb5";
  document.getElementById("camera_tab_slide").style.backgroundColor = "white";
}

function get_div_emoji() {
  var ImageExist = document.getElementById("emj1");
  if (ImageExist != null) {
    document.getElementById("divChooseEmoji").style.display = "block";
  }
}

function clear_img() {
  if (document.querySelector("#snapshot #emj1")) {
    document.querySelector("#snapshot #emj1").remove();
    resetElement();
    resetInput();
  } else alert("image is not exists");
}

function resetInput() {
  document.getElementById("inputUploadImage").value = "";
}
function resetElement() {
  document.getElementsByClassName("borderDashed")[0].style.border = "2px dashed #91b0b3";
  document.getElementById("msjCaptureImageState").style.display = "block";
  document.getElementById("btn-get-emoji").disabled = true;
  document.getElementById("btn-save-image").disabled = true;
  document.getElementById("snapshot").style.display = "none";
}
function changeElementInSaveImage() {
  document.querySelector("#snapshot #emj1").style.borderRadius = "7px";
  document.getElementsByClassName("borderDashed")[0].style.border = "0px solid #0000FF";
  document.getElementById("msjCaptureImageState").style.display = "none";
  document.getElementById("btn-get-emoji").disabled = false;
  document.getElementById("btn-save-image").disabled = false;
  document.getElementById("snapshot").style.display = "block";
}
