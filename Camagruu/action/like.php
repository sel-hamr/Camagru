<?php
session_start();
include '../config/database.php';
include '../tools/tools.php';
include '../tools/tools_post.php';

if (isset($_SESSION['user'])  && $_SESSION['user'] != '' && isset($_POST['galleryToken']) && $_POST['galleryToken'] != '') {
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $gallery_token = base64_decode($_POST['galleryToken']);
    $like = "1";
    $noLike = "2";
    $token_user = get_info_user($_SESSION['user'], $pdo)['token'];
    $id_user = get_info_user($_SESSION['user'], $pdo)['user_id'];
    $id_gallery = get_id_by_token($gallery_token, $pdo);
    if ($id_gallery != "") {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `like` INNER JOIN account ON account.user_id = like.userid INNER JOIN gallery ON gallery.id = like.galleryid WHERE account.token = :token and gallery.token_galley=:token_galley");
        $stmt->bindParam(":token", $token_user);
        $stmt->bindParam(":token_galley", $gallery_token);
        $stmt->execute();
        $results = $stmt->fetch();
        if ($results[0] == 0) {
            $stmt = $pdo->prepare("INSERT INTO `like`(userid,galleryid,type) VALUES (:userid,:galleryid,:typ)");
            $stmt->bindParam(":userid", $id_user);
            $stmt->bindParam(":galleryid", $id_gallery);
            $stmt->bindParam(":typ", $like);
            $stmt->execute();
            if (is_active_notification_and_username_and_email($gallery_token, $pdo)['notification'] == 1) {
                $to = is_active_notification_and_username_and_email($gallery_token, $pdo)['email'];
                $subject = 'Welcome to Camagru';
                $msg = file_get_contents("../form/pageLike.txt");
                $msg = str_replace("{USER_NAME}", is_active_notification_and_username_and_email($gallery_token, $pdo)['username'], $msg);
                $msg = str_replace("{USER_like}", $_SESSION['user'], $msg);
                $headers = 'Content-type: text/html;';
                mail($to, $subject, $msg, $headers);
            }
            echo count_like($gallery_token, $pdo);
        } else if ($results[0] == 1) {
            $stmt = $pdo->prepare("SELECT `type` FROM `like` INNER JOIN account ON account.user_id = like.userid INNER JOIN gallery ON gallery.id = like.galleryid WHERE account.token = :token and gallery.token_galley=:token_galley");
            $stmt->bindParam(":token", $token_user);
            $stmt->bindParam(":token_galley", $gallery_token);
            $stmt->execute();
            $type = $stmt->fetch();
            if ($type[0] == 2) {
                $stmt = $pdo->prepare("UPDATE `like` SET `type` = :typ WHERE `userid` = :userid AND `galleryid` = :galleryid");
                $stmt->bindParam(":userid", $id_user);
                $stmt->bindParam(":galleryid", $id_gallery);
                $stmt->bindParam(":typ", $like);
                $stmt->execute();
                if (is_active_notification_and_username_and_email($gallery_token, $pdo)['notification'] == 1) {
                    $to = is_active_notification_and_username_and_email($gallery_token, $pdo)['email'];
                    $subject = 'Welcome to Camagru';
                    $msg = file_get_contents("../form/pageLike.txt");
                    $msg = str_replace("{USER_NAME}", is_active_notification_and_username_and_email($gallery_token, $pdo)['username'], $msg);
                    $msg = str_replace("{USER_like}", $_SESSION['user'], $msg);
                    $headers = 'Content-type: text/html;';
                    mail($to, $subject, $msg, $headers);
                }
                echo count_like($gallery_token, $pdo);
            } else {
                $stmt = $pdo->prepare("UPDATE `like` SET `type` = :typ WHERE `userid` = :userid AND `galleryid` = :galleryid");
                $stmt->bindParam(":userid", $id_user);
                $stmt->bindParam(":galleryid", $id_gallery);
                $stmt->bindParam(":typ", $noLike);
                $stmt->execute();
                echo count_like($gallery_token, $pdo);
            }
        }
    } 
}  else header('Location: ../index.php');
