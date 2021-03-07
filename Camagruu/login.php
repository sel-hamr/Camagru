<?php
session_start();
include './config/database.php';
include './tools/tools.php';
unset($_SESSION['token_camera']);
if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['token_login']))
    $_SESSION['token_login'] = hash('whirlpool', rand());
if (isset($_POST['sumbit']) && $_POST['sumbit'] == 'Log In' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['token']) && $_POST['token'] == $_SESSION['token_login']) {
    unset($_SESSION['token_login']);
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    if (check_user_pass($_POST['username'], $_POST['password'], $pdo)) {
        if (is_activer($_POST['username'], $pdo) == 1) {
            $_SESSION['login'] = 1;
            $_SESSION['user'] = $_POST['username'];
            $_SESSION['photo'] = "upload/" . get_info_user($_SESSION['user'], $pdo)['photo'];
            $_SESSION['LastName'] = get_info_user($_SESSION['user'], $pdo)['LastName'];
            $_SESSION['FirstName'] = get_info_user($_SESSION['user'], $pdo)['FirstName'];
            $_SESSION['email'] = get_info_user($_SESSION['user'], $pdo)['email'];
            $_SESSION['token'] = get_info_user($_SESSION['user'], $pdo)['token'];
            $_SESSION['notification'] = get_info_user($_SESSION['user'], $pdo)['notification'];
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = 12;
            $_SESSION['login'] = 0;
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 10;
        $_SESSION['login'] = 0;
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/style_navbar.css">
    <link rel="stylesheet" href="./Style/style_login.css">
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
                        <a class="nav-link  font_nav" href="profil.php">profile</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav" id="gest">
                <li>
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/sing_up.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_sing_up_photo">
                        <a class="nav-link font_nav" href="create_sing_up.php">Sign Up</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/login.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_login_photo">
                        <a class="nav-link active font_nav" href="login.php">LogIn </a>
                    </div>
                </li>
            </ul>
            <ul id="user"></ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="left col-lg-5 rounded-left  d-none d-lg-block">
                <div class="row ">
                    <p class="titre col-md-12">Sign in </p>
                </div>
                <div class="row">
                    <img src="img/img-login.svg" class="img-Login">
                </div>
            </div>
            <div class="right col-lg-7 col-md-12 col-ms-12 col-xs-12 rounded-right ">
                <img src="img/p1.png" id="img-Welcome">
                <form action="login.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['token_login'] ?>">
                    <label for="user-input" class="col-12" id="userName">User Name</label>
                    <div class="col-12 input-group">
                        <div class="input-group-prepend">
                            <img class="input-group-text" id="img_user" src="/img/userProfile.png">
                        </div>
                        <input class="form-control" type="text" placeholder="User Name" id="user-input" name="username" required>
                    </div>
                    <label for="pass-input" class="col-12" id="pass">Password</label>
                    <div class="col-12 input-group">
                        <input class="form-control" type="password" placeholder="* * * * * * * *" name="password" required>
                        <div class="input-group-append" onclick="show_password(this)">
                            <img class="input-group-text img_add" src="../img/padlock.png" id="img_pass">
                        </div>
                    </div>
                    <p onclick="document.getElementById('sendMail').style.display='block'" id="forgot" class="d-flex justify-content-end">forgot
                        password ?</p>
                    <input type="submit" class="btn btn-info col-lg-12" name="sumbit" id="submit" value="Log In" required>
                </form>
            </div>
        </div>
        <div class="error">
            <div class="alert alert-success" id="success">
                Success! created your account please check your email to activate account.
            </div>
            <div class="alert alert-danger " id="error_user_pass">
                Error! username or password is error .
            </div>
            <div class="alert alert-success" id="success_reset_password">
                Your password has been reset successfully!</a>.
            </div>
            <div class="alert alert-warning" id="WarningIsNotActive">
                Warning!This account is not activer please check your email to activate account.
            </div>
            <div class="alert alert-warning" id="successSendEmail">
                Send email success please go to email to reset password
            </div>
            <div class="alert alert-warning" id="failSendEmail">
                Oops! something is wrong with your email
            </div>
        </div>
    </div>
    <div id="sendMail">
        <img class="closeFormSendMail" src="/img/close.png" onclick="document.getElementById('sendMail').style.display='none'" />
        <form class="container container-post col-lg-3 col-md-8 col-xs-12 " action="/action/send_email.php" method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token_login'] ?>">
            <div class="input-group  mt-3 col-lg-12 col-md-12 col-ms-12 col-xs-12">
                <input class="form-control" type="email" placeholder="Enter your email" id="flog-input" name="send-email" required>
                <div class="input-group-append">
                    <img class="input-group-text img_add" src="/img/message.png" id="img_email">
                </div>
            </div>
            <div class="row">
                <input type="submit" class="btn btn-info col-lg-5 col-md-8 col-ms-8 col-xs-8 mt-3 mr-auto ml-auto d-block"" name=" Send" id="send" value="Send">
            </div>
        </form>
    </div>
    <nav class="navbar fixed-bottom nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <?php
    if (isset($_SESSION['error'])) echo '<script type="text/javascript"> message(' . $_SESSION['error'] . ');</script>';
    $_SESSION['error'] = "";
    echo '<script type="text/javascript"> navbar(' . "0" . ',"...","..."' . ');</script>';
    if (isset($_SESSION['info-send'])) echo '<script type="text/javascript"> sendEmailState(' . $_SESSION['info-send'] . ');</script>';
    $_SESSION['info-send'] = "";
    echo '<script type="text/javascript"> icons_navbar(4);</script>';
    ?>
</body>

</html>