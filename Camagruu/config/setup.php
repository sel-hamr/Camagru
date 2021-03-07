<?php
try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e;
    exit();
}
try {
    $stmt = $pdo->prepare("CREATE DATABASE IF NOT EXISTS `" . $DB_NAME . "`;");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}
try {
    $stmt = $pdo->prepare("USE`" . $DB_NAME . "`;");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}

try {
    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS `account`
    (
        `user_id` int  PRIMARY KEY AUTO_INCREMENT,
        `LastName` varchar(255),
        `FirstName` varchar(255),
        `photo` varchar(255),
        `email` varchar(255),
        `pass` varchar(255),
        `active` varchar(255),
        `username` varchar(255),
        `token` varchar(255),
        `notification` varchar(10)
    );");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}

try {
    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS `gallery`
    (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(11) NOT NULL,
        `img` VARCHAR(255) NOT NULL,
        `token_galley` VARCHAR(255) NOT NULL,
        `date_time` VARCHAR(100) NOT NULL,
        FOREIGN KEY (userid) REFERENCES `account`(user_id)
    );");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}

try {
    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS `like`
    (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(11) NOT NULL,
        `galleryid` INT(11) NOT NULL,
        `type` VARCHAR(1) NOT NULL,
        FOREIGN KEY (userid) REFERENCES `account`(user_id),
        FOREIGN KEY (galleryid) REFERENCES gallery(id)
    );");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}

try {
    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS `comment`
    (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(11) NOT NULL,
        `galleryid` INT(11) NOT NULL,
        `comment` VARCHAR(255) NOT NULL,
        `time_comemmt` VARCHAR(255) NOT NULL,
        FOREIGN KEY (userid) REFERENCES `account`(user_id),
        FOREIGN KEY (galleryid) REFERENCES gallery(id)
    );");
    $stmt->execute();
} catch (PDOException $e) {
    echo $e;
    exit();
}
