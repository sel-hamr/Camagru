<?php session_start();
include './config/database.php';
include './tools/tools.php';
include './tools/tools_post.php';
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_GET['token']) && $_GET['token'] != '')
    if (if_token_ready(base64_decode($_GET['token']), $pdo))
        $_SESSION['token_reset_pass'] =  hash('whirlpool', rand());
    else {
        header('Location: index.php');
        exit();
    }
else {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Style/reset password.css">
    <link rel="stylesheet" href="/Style/style_navbar.css">
    <script type="text/javascript" src="./Script/script.js"></script>
</head>

<body>
    <div class="container">
        <div class="row align-items-center mt-5 flex-column">
            <form method="POST" class="col-lg-5 col-xs-12" action="../action/create_password.php?token=<?php echo $_GET['token']; ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token_reset_pass'] ?>">
                <div class="row mt-5 d-flex">
                    <img src="img/camagru.png" class="mr-auto ml-auto">
                </div>
                <div class="row mt-4">
                    <p class=" info_person">new password :</p>
                </div>
                <div class="row">
                    <input type="password" class="form-control col-12" id="new_password" placeholder="* * * * * * * *" name="new_password">
                </div>
                <div class="row mt-4">
                    <p class="info_person">Confirm Password :</p>
                </div>
                <div class="row">
                    <input type="password" class="form-control col-12" id="confirmer_new_password" placeholder="* * * * * * * *" name="confirmer_new_password">
                </div>
                <div class="row mt-5">
                    <input type="submit" class="btn btn-info col-12 " name="sumbit" value="change">
                </div>
                <div class="row mt-4">
                    <div class="alert alert-danger col-12" id="error-empty">
                        Error! "Oops empty some information" .
                    </div>
                    <div class="alert alert-danger col-12" id="error-password-match">
                        Error! "password not match" .
                    </div>
                    <div class="alert alert-danger col-12" id="error-password-format">
                        Error! "password format not valid must be 8-16 characters and uppercase and lowercase letter and number and character" .
                    </div>
                </div>
            </form>
        </div>
    </div>
    <nav class="navbar fixed-bottom  nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <?php
    if (isset($_SESSION['error-pass']))
        echo '<script type="text/javascript"> error_rest_pass(' . $_SESSION['error-pass'] . ');</script>';
    $_SESSION['error-pass'] = "";
    ?>
</body>

</html>