<?php
include '../tools/tools.php';
include '../config/database.php';
session_start();
if (isset($_GET['token']) && $_GET['token'] != "") {
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $stmt = $pdo->prepare("UPDATE `account` SET `active`= 1 WHERE `token` = :token");
    $stmt->bindParam(":token", $_GET['token']);
    $stmt->execute();
}
header('Location: ../login.php');
