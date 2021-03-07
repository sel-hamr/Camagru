<?php
session_start();
include '../config/database.php';
include '../tools/tools_post.php';
$pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['user']) &&  $_SESSION['user'] != '' && isset($_GET['token']) && if_gallery_of_you(base64_decode($_GET['token']), $pdo) == $_SESSION['user'] && isset($_SESSION['token_index']) && $_GET['token_index'] == $_SESSION['token_camera']) {
    unset($_SESSION['token_camera']);
    $token = base64_decode($_GET['token']);
    function get_id_by_token($token, $pdo)
    {
        $stmt = $pdo->prepare("SELECT `id` FROM `gallery` WHERE token_galley=:token");
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        $results = $stmt->fetch();
        return $results[0];
    }
    $id = get_id_by_token($token, $pdo);
    $stmt = $pdo->prepare("DELETE  FROM `comment`WHERE galleryid=:ID");
    $stmt->bindParam(":ID", $id);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE  FROM `like`WHERE galleryid=:ID");
    $stmt->bindParam(":ID", $id);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM gallery WHERE token_galley=:ID");
    $stmt->bindParam(":ID", $token);
    $stmt->execute();
    header('Location: ../camera.php');
    exit();
} else {
    header('Location: ../index.php');
    exit();
}
