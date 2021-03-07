<?php
include '../tools/tools.php';
include '../config/database.php';
session_start();
if (isset($_POST['Send']) &&  $_POST['Send'] == 'Send' && isset($_POST['token']) && isset($_SESSION['token_login']) && $_POST['token'] == $_SESSION['token_login']) {
    unset($_SESSION['token_login']);
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    if (isset($_POST['send-email']) && $_POST['send-email'] != '') {
        if (check_email($_POST['send-email'], $pdo)) {
            $token = base64_encode(get_info_user_byEmail($_POST['send-email'], $pdo)['token']);
            $user = get_info_user_byEmail($_POST['send-email'], $pdo)['username'];
            $to = $_POST['send-email'];
            $subject = 'Welcome to Camagru';
            $msg = file_get_contents("../form/send-page-forgotPassword.txt");
            $msg = str_replace("{IP}", $_SERVER['HTTP_HOST'], $msg);
            $msg = str_replace("{USER_NAME}", $user, $msg);
            $msg = str_replace("{TOKEN}", $token, $msg);
            $headers = 'Content-type: text/html;';
            mail($to, $subject, $msg, $headers);
            $_SESSION['info-send'] = 1;
            header('Location: ../login.php');
            exit();
        } else {
            $_SESSION['info-send'] = 2;
            header('Location: ../login.php');
            exit();
        }
    } else {
        $_SESSION['info-send'] = 0;
        header('Location: ../login.php');
        exit();
    }
} else {
    $_SESSION['info-send'] = 0;
    header('Location: ../index.php');
    exit();
}
