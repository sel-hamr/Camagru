<?php
session_start();
include '../config/database.php';
include '../tools/tools.php';
include '../tools/tools_post.php';

if (isset($_SESSION['user'])  && $_SESSION['user'] != '' && isset($_POST['commentValue']) && isset($_POST['galleryToken']) && $_POST['galleryToken'] != "") {
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    if (isset($_POST['commentValue']) && trim($_POST['commentValue']) != "") {
        if (strlen($_POST['commentValue']) < 256) {
            $comment = htmlspecialchars($_POST['commentValue']);
            $id_user = get_info_user($_SESSION['user'], $pdo)['user_id'];
            $image = get_info_user($_SESSION['user'], $pdo)['photo'];
            $id_gallery = get_id_by_token(base64_decode($_POST['galleryToken']), $pdo);
            $timeNow = date("F j, Y, g:i a");
            $stmt = $pdo->prepare("INSERT INTO `comment`(userid,galleryid,comment,time_comemmt) VALUES (:userid,:galleryid,:comment,:timeNow)");
            $stmt->bindParam(":userid", $id_user);
            $stmt->bindParam(":galleryid", $id_gallery);
            $stmt->bindParam(":comment", $comment);
            $stmt->bindParam(":timeNow", $timeNow);
            $stmt->execute();
            if (is_active_notification_and_username_and_email(base64_decode($_POST['galleryToken']), $pdo)['notification'] == 1) {
                $to = is_active_notification_and_username_and_email(base64_decode($_POST['galleryToken']), $pdo)['email'];
                $subject = 'Welcome to Camagru';
                $msg = file_get_contents("../form/pageComment.txt");
                $msg = str_replace("{USER_NAME}", is_active_notification_and_username_and_email(base64_decode($_POST['galleryToken']), $pdo)['username'], $msg);
                $msg = str_replace("{USER_like}", $_SESSION['user'], $msg);
                $headers = 'Content-type: text/html;';
                mail($to, $subject, $msg, $headers);
            }
            echo  $_SESSION['user'] . "@" . $timeNow . "@upload/" . $image . "@" . count_comment(base64_decode($_POST['galleryToken']), $pdo);
        } else  echo "error";
    } else echo "error";
} else header('Location: ../index.php');
