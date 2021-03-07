<?php
include '../config/database.php';
include '../tools/tools.php';
$active = 1;
$no_active = 0;
session_start();
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['user']) &&  $_SESSION['user'] != '' && $_POST['submit'] == "edit" && isset($_POST['token']) && isset($_SESSION['token_profile']) && $_POST['token'] == $_SESSION['token_profile']) {
    unset($_SESSION['token_profile']);
    if (isset($_POST['editemail']) &&  $_POST['editemail'] != '' && isset($_POST['editLastName']) &&  $_POST['editLastName'] != '' && isset($_POST['editFastName']) && $_POST['editFastName'] != '' && isset($_POST['editUserName']) && $_POST['editUserName'] != '') {
        if (!check_email($_POST['editemail'], $pdo) || $_POST['editemail'] == $_SESSION['email']) {
            if ((chech_username($_POST['editUserName']) && !user_ready_taken($_POST['editUserName'], $pdo)) || $_POST['editUserName'] ==  $_SESSION['user']) {
                if (chech_name($_POST['editFastName'], $_POST['editFastName'])) {
                    $stmt = $pdo->prepare("UPDATE `account` SET `email` = :editemail, `LastName` = :editLastName, `FirstName` = :editFastName, `username` = :editUserName,`notification` = :notification WHERE `token` = :token");
                    $stmt->bindParam(":token", $_SESSION['token']);
                    $stmt->bindParam(":editemail", $_POST['editemail']);
                    $stmt->bindParam(":editLastName", trim($_POST['editLastName']));
                    $stmt->bindParam(":editFastName", trim($_POST['editFastName']));
                    $stmt->bindParam(":editUserName", $_POST['editUserName']);
                    if (isset($_POST['checkbox']) &&  $_POST['checkbox'] == 1)
                        $stmt->bindParam(":notification", $active);
                    else
                        $stmt->bindParam(":notification", $no_active);
                    $stmt->execute();
                    $_SESSION['user'] = $_POST['editUserName'];
                    $_SESSION['FirstName'] = trim($_POST['editFastName']);
                    $_SESSION['LastName'] = trim($_POST['editLastName']);
                    $_SESSION['email'] = $_POST['editemail'];
                    if (isset($_POST['checkbox']) &&  $_POST['checkbox'] == 1)
                        $_SESSION['notification'] = 1;
                    else
                        $_SESSION['notification'] = 0;
                    header('Location: ../profil.php');
                    exit();
                } else {
                    $_SESSION['error-edit-info'] = 4;
                    header('Location: ../profil.php');
                    exit();
                }
            } else {
                $_SESSION['error-edit-info'] = 3;
                header('Location: ../profil.php');
                exit();
            }
        } else {
            $_SESSION['error-edit-info'] = 2;
            header('Location: ../profil.php');
            exit();
        }
    } else {
        $_SESSION['error-edit-info'] = 1;
        header('Location: ../profil.php');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
