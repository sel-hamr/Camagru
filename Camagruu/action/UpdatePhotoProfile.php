<?php
include '../config/database.php';
include '../tools/tools.php';
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
session_start();
if (isset($_SESSION['user']) && $_SESSION['user'] != ''  && isset($_POST['changeProfileImage']) && $_POST['changeProfileImage'] == "Change Image" && isset($_POST['token']) && $_POST['token'] == $_SESSION['token_profile']) {
    if ($_FILES["fileUploadImageProfile"]["name"] == '') {
        $_SESSION['error-photo'] = 1;
        header('Location: ../profil.php');
        exit();
    } else {
        $type = $_FILES["fileUploadImageProfile"]["type"];
        $size = $_FILES["fileUploadImageProfile"]["size"];
        $temp = $_FILES["fileUploadImageProfile"]["tmp_name"];
        if ($type == "image/jpg" || $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif') {
            $image_file = get_name($_FILES["fileUploadImageProfile"]["name"]);
            if ($size < 5000000) {
                if (getimagesize($temp)) {
                    $stmt = $pdo->prepare("UPDATE `account` SET `photo` = :photo WHERE `username` = :username");
                    $stmt->bindParam(":username", $_SESSION['user']);
                    $stmt->bindParam(":photo", $image_file);
                    $stmt->execute();
                    move_uploaded_file($temp, "../upload/" . $image_file);
                    $_SESSION['photo'] =  "../upload/" . $image_file;
                    header('Location: ../profil.php');
                    exit();
                } else {
                    $_SESSION['error-photo'] = 3;
                    header('Location: ../profil.php');
                    exit();
                }
            } else {
                $_SESSION['error-photo'] = 2;
                header('Location: ../profil.php');
                exit();
            }
        } else {
            $_SESSION['error-photo'] = 3;
            header('Location: ../profil.php');
            exit();
        }
    }
} else {
    header('Location: ../index.php');
    exit();
}
