function message(msj) {
  if (msj == 3) {
    document.getElementById("error_email").style.display = "block";
  }
  if (msj == 1) {
    document.getElementById("error_form").style.display = "block";
  }
  if (msj == 2) {
    document.getElementById("error_size").style.display = "block";
  }
  if (msj == 4) {
    document.getElementById("success").style.display = "block";
  }
  if (msj == 5) {
    document.getElementById("error_pass").style.display = "block";
  }
  if (msj == 6) {
    document.getElementById("error_name").style.display = "block";
  }
  if (msj == 7) {
    document.getElementById("error_user").style.display = "block";
  }
  if (msj == 8) {
    document.getElementById("error_pass_format").style.display = "block";
  }
  if (msj == 10) {
    document.getElementById("error_user_pass").style.display = "block";
  }
  if (msj == 11) {
    document.getElementById("success_reset_password").style.display = "block";
  }
  if (msj == 12) {
    document.getElementById("WarningIsNotActive").style.display = "block";
  }
}

function DisplayImage() {
  var input = document.getElementById("fileupload");
  var reader = new FileReader();
  reader.onload = function () {
    var dataURL = reader.result;
    var output = document.getElementById("AddImage");
    output.src = dataURL;
  };
  reader.readAsDataURL(input.files[0]);
  document.getElementById("AddImage").style.width = "100%";
  document.getElementById("AddImage").style.height = "100%";
  document.getElementById("AddImage").style.borderRadius = "50%";
  document.getElementById("AddImage").style.margin = "0px";
}

function check_match_pass(obj) {
  if (obj == 1) {
    var confirm = document.getElementById("confirm").value;
    var password = document.getElementById("password").value;
    if (confirm == password) document.getElementById("confirm").style.border = "2px solid green";
    else document.getElementById("confirm").style.border = "3px solid red";
    if (confirm == "") document.getElementById("confirm").style.border = "";
  }
  if (obj == 2) {
    var confirm = document.getElementById("confirmer_new_password").value;
    var password = document.getElementById("new_password").value;
    if (confirm == password) document.getElementById("confirmer_new_password").style.border = "2px solid green";
    else document.getElementById("confirmer_new_password").style.border = "3px solid red";
    if (confirm == "") document.getElementById("confirmer_new_password").style.border = "";
  }
}

function show_password(event) {
  var inputPassword = event.closest(".input-group").children[0];
  var inputPasswordImage = event.children[0];
  if (inputPassword.type == "text") {
    inputPassword.type = "password";
    inputPasswordImage.src = "../img/padlock.png";
  } else {
    inputPassword.type = "text";
    inputPasswordImage.src = "../img/unlock.png";
  }
}

function toggleNavbar() {
  var element = document.getElementById("div_menu");
  if (element.style.display == "block") {
    document.getElementById("div_menu").style.display = "none";
    document.getElementById("btn_toggler_menu").src = "img/menu.png";
  } else {
    document.getElementById("div_menu").style.display = "block";
    document.getElementById("btn_toggler_menu").src = "img/close.png";
  }
}

function navbar(nav, photo, user) {
  if (nav == 1) {
    document.getElementById("gest").style.display = "none";
    document.getElementById("user_photo").src = photo;
    document.getElementById("user_name").innerHTML = user;
  }
  if (nav == 0) {
    document.getElementById("user").style.display = "none";
  }
}

function icons_navbar(nbr) {
  if (nbr == 1) document.getElementById("bar_home_photo").src = "img/home_activer.png";
  if (nbr == 2) document.getElementById("bar_profil_photo").src = "img/user_activer.png";
  if (nbr == 3) document.getElementById("bar_camera_login_photo").src = "img/camera_activer.png";
  if (nbr == 4) document.getElementById("bar_login_photo").src = "img/login_activer.png";
  if (nbr == 5) document.getElementById("bar_sing_up_photo").src = "img/sing_up_activer.png";
}

function msj_error(nbr) {
  if (nbr == 0) {
    document.getElementById("msj_register").style.display = "block";
  }
}

function hide_msj_error(nbr) {
  if (nbr == 1) {
    document.getElementById("msj_activer").style.display = "none";
  }
  if (nbr == 0) {
    document.getElementById("msj_register").style.display = "none";
  }
  if (nbr == 2) {
    document.getElementById("msj_div_emoji_no").style.display = "none";
  }
}

function seeInfo(notification) {
  if (notification == 1) document.getElementById("checkbox").checked = true;
  else document.getElementById("checkbox").checked = false;
}

function ChangeAndDisplayImageProfil(obj) {
  if (obj == 1) var input = document.getElementById("updateImageProfile");
  else if (obj == 2) var input = document.getElementById("fileupload");
  var reader = new FileReader();
  reader.readAsDataURL(input.files[0]);
  reader.onload = function () {
    var dataURL = reader.result;
    if (obj == 1) var output = document.getElementById("PhotoProfile");
    else if (obj == 2) var output = document.getElementsByClassName("img")[0];
    output.src = dataURL;
  };
}

function edit_click() {
  document.getElementById("my_about_li").style.display = "none";
  document.getElementById("my_edit_li").style.display = "block";
  document.getElementById("my_change_pass_li").style.display = "none";
  document.getElementById("edit_tab").style.color = "#00adb5";
  document.getElementById("about_tab").style.color = "white";
  document.getElementById("change_pass_tab").style.color = "white";
}

function about_click() {
  document.getElementById("my_about_li").style.display = "block";
  document.getElementById("my_edit_li").style.display = "none";
  document.getElementById("my_change_pass_li").style.display = "none";
  document.getElementById("edit_tab").style.color = "white";
  document.getElementById("about_tab").style.color = "#00adb5";
  document.getElementById("change_pass_tab").style.color = "white";
}

function changePass_click() {
  document.getElementById("my_about_li").style.display = "none";
  document.getElementById("my_edit_li").style.display = "none";
  document.getElementById("my_change_pass_li").style.display = "block";
  document.getElementById("about_tab").style.color = "white";
  document.getElementById("edit_tab").style.color = "white";
  document.getElementById("change_pass_tab").style.color = "#00adb5";
}

function error_profil_photo(obj) {
  if (obj == 1) {
    document.getElementById("errorImage").style.display = "block";
  }
  if (obj == 2) {
    document.getElementById("errorSize").style.display = "block";
  }
  if (obj == 3) {
    document.getElementById("errorCheckFormatImage").style.display = "block";
  }
}

function error_profil_info(obj) {
  if (obj == 4) {
    document.getElementById("errorFormatName").style.display = "block";
  }
  if (obj == 3) {
    document.getElementById("errorCheckUserName").style.display = "block";
  }
  if (obj == 2) {
    document.getElementById("errorEmail").style.display = "block";
  }
  if (obj == 1) {
    document.getElementById("errorEmptyInfo").style.display = "block";
  }
}

function error_profil_pass(obj) {
  if (obj == 4) {
    document.getElementById("error_pass_format1").style.display = "block";
  }
  if (obj == 3) {
    document.getElementById("errorOldPasswordWrong").style.display = "block";
  }
  if (obj == 2) {
    document.getElementById("errorPasswordNotMatch").style.display = "block";
  }
  if (obj == 1) {
    document.getElementById("errorEmptyInfo").style.display = "block";
  }
}

function error_rest_pass(obj) {
  if (obj == 3) {
    document.getElementById("error-password-format").style.display = "block";
  }
  if (obj == 2) {
    document.getElementById("error-password-match").style.display = "block";
  }
  if (obj == 1) {
    document.getElementById("error-empty").style.display = "block";
  }
}

function sendEmailState(obj) {
  if (obj == 1) document.getElementById("successSendEmail").style.display = "block";

  if (obj == 2) document.getElementById("failSendEmail").style.display = "block";

  document.getElementById("sendMail").style.display = "none";
}

function view_comment(element) {
  var getElement = element.split("commentPosition");
  if (document.getElementById(element).style.display == "block") {
    document.getElementById(element).style.display = "none";
    document.getElementsByClassName("view_comment")[getElement[1] - 1].innerHTML = "View Comment";
  } else {
    document.getElementById(element).style.display = "block";
    document.getElementsByClassName("view_comment")[getElement[1] - 1].innerHTML = "Hide Comment";
  }
}
