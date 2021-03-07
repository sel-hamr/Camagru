<?php
include '../config/database.php';
include '../tools/tools.php';
include '../tools/tools_post.php';
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
session_start();
if (isset($_GET['token']) && $_GET['token'] != ''  && if_token_ready(base64_decode($_GET['token']), $pdo) && isset($_POST['token']) && isset($_SESSION['token_reset_pass']) && $_POST['token'] == $_SESSION['token_reset_pass']) {
    unset($_SESSION['token_reset_pass']);
    if (isset($_POST['new_password']) && isset($_POST['confirmer_new_password']) && $_POST['new_password'] != '' && $_POST['confirmer_new_password'] != '') {
        if ($_POST['new_password'] == $_POST['confirmer_new_password']) {
            if (chech_pass($_POST['new_password'])) {
                $new_pass = hash('whirlpool', $_POST['new_password']);
                $stmt = $pdo->prepare("UPDATE `account` SET `pass`= :pass WHERE `token` = :token");
                $stmt->bindParam(":token", base64_decode($_GET['token']));
                $stmt->bindParam(":pass", $new_pass);
                $stmt->execute();
                $_SESSION['error'] = 11;
                header('Location: ../login.php');
                exit();
            } else {
                $_SESSION['error-pass'] = 3;
                header('Location: ../reset password.php?token=' . $_GET['token']);
                exit();
            }
        } else {
            $_SESSION['error-pass'] = 2;
            header('Location: ../reset password.php?token=' . $_GET['token']);
            exit();
        }
    } else {
        $_SESSION['error-pass'] = 1;
        header('Location: ../reset password.php?token=' . $_GET['token']);
        exit();
    }
} else {
    $_SESSION['error-pass'] = 0;
    header('Location: ./index.php');
    exit();
}
