<?php
session_start();
include '../config/database.php';
include '../tools/tools.php';
$randomNumber = rand();
$token = hash('whirlpool', $randomNumber);
if (isset($_POST["image_data"]) && $_POST["image_data"] != "" && isset($_SESSION['user']) && $_SESSION['user'] != '' && isset($_POST['token']) && isset($_SESSION['token_camera']) && $_SESSION['token_camera'] == $_POST['token']) {
    echo $_POST['token'];
    unset($_SESSION['token_camera']);
    $type_image = "";
    if (count(explode('data:image/', $_POST["image_data"])) >= 2 && count(explode(';', explode('data:image/', $_POST["image_data"])[1])) >= 1)
        $type_image = explode(';', explode('data:image/', $_POST["image_data"])[1])[0];
    if ($type_image == "png" || $type_image == "jpg" || $type_image == "jpeg" || $type_image == "gif") {
        $img_base64 = str_replace(' ', '+', $_POST["image_data"]);
        if (getimagesize($img_base64)) {
            $path = '../post/post_' . time() . '.' . $type_image;
            if (file_exists("../post") == FALSE)
                mkdir("../post");
            chmod("../post", 0777);
            $file = file_get_contents($img_base64);
            file_put_contents($path, $file);
            $date = date("F j, Y, g:i a");
            $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
            $stmt = $pdo->prepare("INSERT INTO `gallery`(userid,img,token_galley,date_time) VALUES (:userid,:img,:token_galley,:date_time)");
            $stmt->bindParam(":userid", get_info_user($_SESSION['user'], $pdo)['user_id']);
            $stmt->bindParam(":img", $path);
            $stmt->bindParam(":token_galley", $token);
            $stmt->bindParam(":date_time", $date);
            $stmt->execute();
            echo $_POST['token'];
        } else header('Location: ../profil.php');
    } else header('Location: ../profil.php');
} else header('Location: ../index.php');
