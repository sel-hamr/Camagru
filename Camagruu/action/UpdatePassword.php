<?php
include '../config/database.php';
include '../tools/tools.php';
session_start();
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['user']) && $_SESSION['user'] != ''   && isset($_POST['submit']) && $_POST['submit'] == "change" && isset($_POST['token']) && isset($_SESSION['token_profile']) && $_POST['token'] == $_SESSION['token_profile']) {
    unset($_SESSION['token_profile']);
    if ($_POST['your_password'] != '' && $_POST['new_password'] != '' && $_POST['confirm_new_password'] != '' && isset($_POST['your_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
        if ($_POST['new_password'] == $_POST['confirm_new_password']) {
            if (get_info_user($_SESSION['user'], $pdo)['pass'] == hash('whirlpool', $_POST['your_password'])) {
                if (chech_pass($_POST['new_password'])) {
                    $new_pass = hash('whirlpool', $_POST['new_password']);
                    $stmt = $pdo->prepare("UPDATE `account` SET `pass`= :pass WHERE `username` = :username");
                    $stmt->bindParam(":username", $_SESSION['user']);
                    $stmt->bindParam(":pass", $new_pass);
                    $stmt->execute();
                    header('Location: ../profil.php');
                    exit();
                } else {
                    $_SESSION['error-pass'] = 4;
                    header('Location: ../profil.php');
                    exit();
                }
            } else {
                $_SESSION['error-pass'] = 3;
                header('Location: ../profil.php');
                exit();
            }
        } else {
            $_SESSION['error-pass'] = 2;
            header('Location: ../profil.php');
            exit();
        }
    } else {
        $_SESSION['error-pass'] = 1;
        header('Location: ../profil.php');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
