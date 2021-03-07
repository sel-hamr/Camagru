<?php
include './config/database.php';
include './tools/tools.php';
session_start();
if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['token_singUp']))
    $_SESSION['token_singUp'] =  hash('whirlpool', rand());
if (isset($_POST['sumbit']) && $_POST['sumbit'] == "create" && isset($_POST['username']) && isset($_POST['lastName']) && isset($_POST['firstName']) &&  isset($_POST['email']) && isset($_POST['password']) &&  isset($_POST['confirmer']) &&  $_POST['token'] == $_SESSION['token_singUp']) {
    unset($_SESSION['token_singUp']);
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $image_file = "user_photo.png";
    chmod("./upload", 0777);
    if (check_email($_POST['email'], $pdo) == FALSE) {
        if ($_POST['password'] == $_POST['confirmer']) {
            if (chech_name($_POST['lastName'], $_POST['firstName'], $pdo)) {
                if (check_user($_POST['username'], $pdo)) {
                    if (chech_pass($_POST['password'], $pdo)) {
                        create_user(trim($_POST['lastName']), trim($_POST['firstName']), $image_file, $_POST['email'], $_POST['password'], $_POST['username'], $pdo);
                        $_SESSION['error'] = 4;
                        header('Location: login.php');
                        exit();
                    } else {
                        $_SESSION['error'] = 8;
                        header('Location: create_sing_up.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 7;
                    header('Location: create_sing_up.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = 6;
                header('Location: create_sing_up.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 5;
            header('Location: create_sing_up.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 3;
        header('Location: create_sing_up.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Sing Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/style_sing_up.css">
    <link rel="stylesheet" href="./Style/style_navbar.css">
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
                        <a class="nav-link font_nav" href="index.php">Home</a>
                    </div>
                </li>
                <li class="ml-lg-3 ml-sm-1">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/user.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_profil_photo">
                        <a class="nav-link  font_nav" href="profil.php">profil</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav" id="gest">
                <li class="nav-item">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/sing_up.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_sing_up_photo">
                        <a class="nav-link active font_nav" href="create_sing_up.php">Sign Up</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/login.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_login_photo">
                        <a class="nav-link font_nav" href="login.php">LogIn </a>
                    </div>
                </li>
            </ul>
            <ul id="user"></ul>
        </div>
    </nav>
    <div class="container">
        <div class="row ">
            <div class="left col-lg-5 rounded-left  d-none d-lg-block">
                <p class="titre_grand col-md-12">Sing up </p>
                <div class="row">
                    <a href="index.php" class="container_img_website">
                        <img src="img/test.svg" class="img_website"></a>
                </div>
            </div>
            <div class="right col-lg-7 col-md-12 col-ms-12 col-xs-12 rounded-right ">
                <form method="POST" action="create_sing_up.php" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= $_SESSION['token_singUp'] ?>">
                    <div class="row mr_t">
                        <div class="col-lg-4 mt-4">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <input type="text" class="form-control" id="username" name="username" placeholder="UserName" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 mt-4">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        </div>
                        <div class="col-lg-4 col-md-12 mt-4">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="*******" id="password" name="password" oninput="check_match_pass(1)" required>
                                <div class="input-group-append" id="id_show_pass" onclick="show_password(this)">
                                    <img class="input-group-text " id="image_password" src="./img/padlock.png">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-lg-4 col-md-12  mt-4">
                            <label for="confirmer">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm" name="confirmer" placeholder="********" oninput="check_match_pass(1)" required>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-info col-lg-12" value="create" name="sumbit" id="submit">
                </form>
            </div>
        </div>
        <div class="error">
            <div class="alert alert-danger " id="error_email">
                Error! email format not valid or email is already taken .
            </div>
            <div class="alert alert-danger " id="error_pass">
                Error! password not match .
            </div>
            <div class="alert alert-danger " id="error_name">
                Error! "Only letters and white space allowed in LastName and Firstname and Firstname/LastName must be 4-16 characters" .
            </div>
            <div class="alert alert-danger " id="error_user">
                Error! username format or is already taken.
            </div>
            <div class="alert alert-danger " id="error_pass_format">
                Error! password format not valid must be 8-16 characters and uppercase and lowercase letter and number and caractère "@#%&(!)".
            </div>
        </div>
    </div>
    <nav class="navbar fixed-bottom  nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <?php
    if (isset($_SESSION['error']) && $_SESSION['error'] != "")
        echo '<script type="text/javascript"> message(' . $_SESSION['error'] . ');</script>';
    $_SESSION['error'] = "";
    echo '<script type="text/javascript"> navbar(' . "0" . ',"...","..."' . ');</script>';
    echo '<script type="text/javascript"> icons_navbar(5);</script>';
    ?>
</body>

</html>