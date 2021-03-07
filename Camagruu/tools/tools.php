<?php

function get_name($name)
{
    if ($name != '') {
        $NewName = explode('.', $name);
        $extension = $NewName[count($NewName) - 1];
        $NewName = "img_" . rand() . '_' . time() . "." . $extension;
        return $NewName;
    } else {
        return "user_photo.png";
    }
}

function create_user($lastName, $firstName, $image_file, $email, $password, $username, $pdo)
{
    $pass = hash('whirlpool', $password);
    $active = 0;
    $notification = 1;
    $randomNumber = rand();
    $token = hash('whirlpool', $randomNumber);
    $stmt = $pdo->prepare("INSERT INTO `account`(LastName,FirstName,photo,email,pass,active,username,token,notification) VALUES (:LastName,:FirstName,:photo,:email,:pass,:active,:username,:token,:notification)");
    $stmt->bindParam(":LastName", $lastName);
    $stmt->bindParam(":FirstName", $firstName);
    $stmt->bindParam(":photo", $image_file);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pass", $pass);
    $stmt->bindParam(":active", $active);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":notification", $notification);
    $stmt->execute();
    $to =  $email;
    $subject = 'Welcome to Camagru';
    $msg = file_get_contents("./form/send-page-activer.txt");
    $msg = str_replace("{IP}", $_SERVER['HTTP_HOST'], $msg);
    $msg = str_replace("{USER_NAME}", $username, $msg);
    $msg = str_replace("{TOKEN}", $token, $msg);
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to, $subject, $msg, $headers);
}

function chech_name($lastName, $firstName)
{
    if ((ctype_alpha(str_replace(' ', '', $lastName)) == false  || (strlen($lastName) >= 19 || strlen($lastName) < 4)) || ctype_alpha(str_replace(' ', '', $firstName)) == false || (strlen($firstName) >= 19 || strlen($firstName) < 4))
        return (0);
    else
        return (1);
}

function chech_username($user_name)
{
    if (preg_match("/^[a-z]{3}-?[a-z]{3,5}$/", $user_name))
        return (1);
    else
        return (0);
}

function check_user($user_name, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `account`");
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $key => $value)
        if ($value['username'] == $user_name || !preg_match("/^[a-z]{3}-?[a-z]{3,5}$/", $user_name))
            return false;
    return true;
}

function chech_pass($password)
{
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number = preg_match('/[0-9]/', $password);
    $specialCharacter = preg_match('/[@#%&(!)]/', $password);
    $len = strlen($password);
    if ($uppercase && $lowercase && $number && $len >= 8 && $len <= 16 && $specialCharacter)
        return (1);
    else
        return (0);
}

function check_user_pass($user_name, $password, $pdo)
{
    $pass = hash('whirlpool', $password);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `account` WHERE `username` = :username AND `pass` = :pass");
    $stmt->bindParam(":username", $user_name);
    $stmt->bindParam(":pass", $pass);
    $stmt->execute();
    $results = $stmt->fetchColumn();
    return $results;
}

function is_activer($user_name, $pdo)
{

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `account` WHERE `username` = :username AND `active` = 1");
    $stmt->bindParam(":username", $user_name);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results[0];
}


function get_info_user($user, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `account` WHERE `username` = :username");
    $stmt->bindParam(":username", $user);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results;
}

function check_email($email, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `account`");
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $key => $value) {
        if ($value['email'] == $email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
    }
    return false;
}

function user_ready_taken($user_name, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `account`");
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $key => $value) {
        if ($value['username'] == $user_name) {
            return true;
        }
    }
    return false;
}

function get_info_user_byEmail($user, $pdo)
{

    $stmt = $pdo->prepare("SELECT * FROM `account` WHERE `email` = :email");
    $stmt->bindParam(":email", $user);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results;
}

function if_token_ready($token, $pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `account` WHERE `token` = :token");
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $results = $stmt->fetch();
    if ($results[0] == 0)
        return 0;
    else
        return 1;
}

function get_id_by_token($token, $pdo)
{
    $stmt = $pdo->prepare("SELECT `id` FROM `gallery` WHERE token_galley=:token");
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results[0];
}
