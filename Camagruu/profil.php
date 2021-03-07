<?php
session_start();
include './config/database.php';
include './tools/tools.php';
if (!isset($_SESSION['user']) && $_SESSION['user'] == '') {
    header('Location: index.php');
    exit();
} else
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);

$_SESSION['token_profile'] =  hash('whirlpool', rand());
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/style_navbar.css">
    <link rel="stylesheet" href="./Style/style_profil.css">
    <script type="text/javascript" src="./Script/script.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="navbar-design" style="background-color: #393e46;">
        <img src="img/camagru.png" class="LogoCamagru">
        <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
            <img src="img/menu.png" id="btn_toggler_menu">
        </button>
        <div class="collapse navbar-collapse" id="div_menu">
            <ul class="navbar-nav mr-auto">
                <li class="ml-lg-4">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/home.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_home_photo">
                        <a class="nav-link  font_nav" href="index.php">Home</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/user.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_profil_photo">
                        <a class="nav-link active font_nav" href="profil.php">profile</a>
                    </div>
                </li>
            </ul>
            <ul id="gest" style="margin:0;"></ul>
            <ul class="navbar-nav" id="user">
                <li>
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/camera.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_camera_login_photo">
                        <a class="nav-link font_nav" href="camera.php">Camera</a>
                    </div>
                </li>
                <li class="ml-lg-3 d-none d-lg-block">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="<?= $_SESSION['photo'] ?>" class=" photo_user" id="user_photo">
                        <a class="nav-link font_nav" id="user_name"><?= $_SESSION['user'] ?></a>
                    </div>
                </li>
                <li class="ml-auto ml-lg-2">
                    <div class="form-inline mr-1 mb-lg-0">
                        <a class="nav-link mr-3 mr-lg-1 font_nav" href="../action/logout.php">LogOut</a>
                        <img src="img/logout.png" class="icons_navbar" id="bar_logout">
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row ">
            <div class="left col-md-12 col-ms-12 col-xs-12 col-lg-5 rounded-left">
                <div class="row">
                    <form method="POST" action="../action/UpdatePhotoProfile.php" enctype="multipart/form-data" style="margin-left: auto;margin-right: auto;">
                        <div class="profile-img">
                            <input type="hidden" name="token" value="<?= $_SESSION['token_profile'] ?>">
                            <img src="<?= $_SESSION['photo'] ?>" alt="" id="PhotoProfile" />
                            <input type="file" name="fileUploadImageProfile" id="updateImageProfile" onchange="ChangeAndDisplayImageProfil(1)" accept="image/*" />
                        </div>
                        <input type="submit" value="Change Image" name="changeProfileImage" class="btnChangeImage btn btn-secondary" />
                    </form>
                </div>
            </div>
            <div class="right col-lg-7 col-md-12 col-ms-12 col-xs-12 rounded-right">
                <div class="d-none d-lg-block">
                    <p class=" display-4 titre_profil ">profile</p>
                </div>
                <div id="myTab">
                    <ul class="nav">
                        <li id="about_li" onclick="about_click()">
                            <a class="nav-link" id="about_tab">About</a>
                        </li>
                        <li id="edit_li" onclick="edit_click()">
                            <a class="nav-link" id="edit_tab">Edit profile</a>
                        </li>
                        <li id="change_pass_li" onclick="changePass_click()">
                            <a class="nav-link" id="change_pass_tab">change password</a>
                        </li>
                    </ul>
                </div>
                <div id="my_about_li">
                    <div class="row mb-4">
                        <p class="col-lg-4 col-xs-12 info_person">User Name :</p>
                        <p class="col-lg-7 col-xs-12 info_user" id="infoUserName"><?= $_SESSION['user'] ?></p>
                    </div>
                    <div class="row mb-4">
                        <p class="col-lg-4 col-xs-12 info_person">First Name :</p>
                        <p class="col-lg-7 col-xs-12 info_user" id="infoFirstName"><?= $_SESSION['FirstName'] ?></p>
                    </div>
                    <div class="row mb-4">
                        <p class="col-lg-4 col-xs-12 info_person">Last Name :</p>
                        <p class="col-lg-7 col-xs-12 info_user" id="infoLastName"><?= $_SESSION['LastName']  ?></p>
                    </div>
                    <div class="row mb-4">
                        <p class="col-lg-4 col-xs-12 info_person">email :</p>
                        <p class="col-lg-7 col-xs-12 info_user" id="infoGmail"><?= $_SESSION['email'] ?></p>
                    </div>
                </div>
                <div id="my_edit_li">
                    <form method="POST" action="../action/UpdateProfile.php">
                        <input type="hidden" name="token" value="<?= $_SESSION['token_profile'] ?>">
                        <div class="row mb-3">
                            <p class="col-lg-4 col-xs-12 info_person">User Name :</p>
                            <input type="text" class="form-control col-lg-7" id="editUserName" name="editUserName" value="<?= $_SESSION['user'] ?>">
                        </div>
                        <div class="row mb-3">
                            <p class="col-lg-4 col-xs-12 info_person">First Name :</p>
                            <input type="text" class="form-control col-lg-7" id="editFastName" name="editFastName" value="<?= $_SESSION['FirstName'] ?>">
                        </div>
                        <div class="row mb-3">
                            <p class="col-lg-4 col-xs-12 info_person">Last Name :</p>
                            <input type="text" class="form-control col-lg-7" id="editLastName" name="editLastName" value="<?= $_SESSION['LastName']  ?>">
                        </div>
                        <div class="row mb-3">
                            <p class="col-lg-4 col-xs-12 info_person">Email :</p>
                            <input type="text" class="form-control col-lg-7" id="editemail" name="editemail" value="<?= $_SESSION['email'] ?>">
                        </div>
                        <div class="row">
                            <p class="info_person col-lg-4 col-12">Notification :</p>
                            <input type="checkbox" value="1" id="checkbox" name="checkbox">
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 col-xs-12"></div>
                            <input type="submit" class="btn btn-info col-lg-7 col-xs-12" name="submit" value="edit">
                        </div>
                    </form>
                </div>
                <div id="my_change_pass_li">
                    <form method="POST" action="../action/UpdatePassword.php">
                        <input type="hidden" name="token" value="<?= $_SESSION['token_profile'] ?>">
                        <div class="row mb-4">
                            <p class="col-lg-4 col-xs-12 info_person">Your Password :</p>
                            <div class="input-group col-lg-7 col-xs-12">
                                <input type="password" class="form-control" id="your_password" placeholder="* * * * * * * *" name="your_password">
                                <div class="input-group-append" onclick="show_password(this)">
                                    <img class="input-group-text" id="image_password1" src="./img/padlock.png">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <p class="col-lg-4 col-xs-12 info_person">new password :</p>
                            <div class="input-group col-lg-7 col-xs-12">
                                <input type="password" class="form-control" id="new_password" placeholder="* * * * * * * *" name="new_password" oninput="check_match_pass(2)" required>
                                <div class="input-group-append" id="id_show_pass2" onclick="show_password(this)">
                                    <img class="input-group-text " id="image_password2" src="./img/padlock.png">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <p class="col-lg-4 col-xs-12 info_person">Confirm Password :</p>
                            <input type="password" class="form-control col-lg-7 col-xs-12" id="confirmer_new_password" placeholder="* * * * * * * *" name="confirm_new_password" oninput="check_match_pass(2)" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4"></div>
                            <input type="submit" class="btn btn-info col-lg-7 " name="submit" value="change">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="error">
            <div class="alert alert-warning " id="errorImage">
                Warning! please choose image to need upload .
            </div>
            <div class="alert alert-warning " id="errorSize">
                Warning! Your image To large Please Upload 5MB Size .
            </div>
            <div class="alert alert-danger " id="errorEmail">
                Error! email is already taken or Invalid email format .
            </div>
            <div class="alert alert-danger " id="errorCheckUserName">
                Error! username or email is already taken .
            </div>
            <div class="alert alert-warning " id="errorCheckFormatImage">
                Warning! Upload JPG, JPEG, PNG & GIF File Formate.....CHECK FILE EXTENSION .
            </div>
            <div class="alert alert-danger " id="errorFormatName">
                Error! "Only letters and white space allowed in LastName and Firstname" .
            </div>
            <div class="alert alert-danger " id="errorEmptyInfo">
                Error! empty some information .
            </div>
            <div class="alert alert-danger " id="errorPasswordFormat">
                Error! password format not valid must be 8-16 characters and uppercase and lowercase letter and number and character "@#%&(!)"".
            </div>
            <div class="alert alert-danger " id="errorPasswordNotMatch">
                Error! "The password and confirmation password do not match." .
            </div>
            <div class="alert alert-danger " id="errorOldPasswordWrong">
                Error! password .
            </div>
        </div>
    </div>
    <nav class="navbar fixed-bottom  nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <?php
    echo '<script type="text/javascript"> icons_navbar(2);</script>';
    echo '<script type="text/javascript"> seeInfo("' . $_SESSION['notification'] . '"' . ');</script>';
    if (isset($_SESSION['error-photo']) && $_SESSION['error-photo'] != "")
        echo '<script type="text/javascript"> error_profil_photo(' . $_SESSION['error-photo'] . ');</script>';
    if (isset($_SESSION['error-edit-info']) && $_SESSION['error-edit-info'] != "")
        echo '<script type="text/javascript"> error_profil_info(' . $_SESSION['error-edit-info'] . ');</script>';
    if (isset($_SESSION['error-pass']) && $_SESSION['error-pass'] != "")
        echo '<script type="text/javascript"> error_profil_pass(' . $_SESSION['error-pass'] . ');</script>';
    $_SESSION['error-photo'] = "";
    $_SESSION['error-edit-info'] = "";
    $_SESSION['error-pass'] = "";
    ?>
</body>

</html>