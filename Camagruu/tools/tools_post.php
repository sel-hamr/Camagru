<?php
function getComments_in_post($id, $pdo)
{
    if ($id != '') {
        $stmt = $pdo->prepare("SELECT * FROM `comment` INNER JOIN account ON account.user_id = comment.userid INNER JOIN gallery ON gallery.id = comment.galleryid WHERE gallery.id=:ID");
        $stmt->bindParam(":ID", $id);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }
}

function count_comment($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `comment` INNER JOIN gallery ON gallery.id = comment.galleryid WHERE gallery.token_galley=:token_galley");
    $stmt->bindParam(":token_galley", $id);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results[0];
}
function if_gallery_of_you($token, $pdo)
{
    $stmt = $pdo->prepare("SELECT `username` FROM `account`  INNER JOIN gallery ON gallery.userid = account.user_id WHERE gallery.token_galley=:token");
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results[0];
}

// function get_gallery_of_you($user, $pdo)
// {
//     $stmt = $pdo->prepare("SELECT gallery FROM `account`  INNER JOIN gallery ON gallery.userid = account.user_id WHERE account.user_id =:token");
//     $stmt->bindParam(":user_id", $user);
//     $stmt->execute();
//     $results = $stmt->fetchAll();
//     return $results;
// }
function post($info_post_and_user, $type_like, $pdo, $i)
{
    $msg = file_get_contents("./form/post.txt");
    $msg = str_replace("{USER_NAME}", $info_post_and_user['username'], $msg);
    $msg = str_replace("{IMGPOST}",  $info_post_and_user['img'], $msg);
    $msg = str_replace("{PHOTO_USER}", "upload/" . $info_post_and_user['photo'], $msg);
    $msg = str_replace("{DATE}", $info_post_and_user['date_time'], $msg);
    $msg = str_replace("{TOKENPOST}", base64_encode($info_post_and_user['token_galley']), $msg);
    $msg = str_replace("{IDIMAGELIKE}", "imgLike_" . $i, $msg);
    $msg = str_replace("{IDCOUNTLIKE}", "countLike_" . $i, $msg);
    $msg = str_replace("{IDCOUNTCOMMENT}", "countComment_" . $i, $msg);
    $msg = str_replace("{COUNT_LIKE}", " " . count_like($info_post_and_user['token_galley'], $pdo) . " like", $msg);
    $msg = str_replace("{COUNT_COMMENT}", " " . count_comment($info_post_and_user['token_galley'], $pdo) . " comment", $msg);
    if ($type_like == 1)
        $msg = str_replace("{IMGLIKE}", "img/like.png", $msg);
    else  if ($type_like == 2)
        $msg = str_replace("{IMGLIKE}", "img/no_like.png", $msg);
    else
        $msg = str_replace("{IMGLIKE}", "img/no_acc_like.png", $msg);
    if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
        $msg = str_replace("{CREATE_POST_COMMENT}", file_get_contents("./form/create_comment.txt"), $msg);
        $msg = str_replace("{ID_TEXTAREA_COMMENT}", "id_texterea_comment" . $i, $msg);
        $msg = str_replace("{IDCOUNTCOMMENT}", "countComment_" . $i, $msg);
        $msg = str_replace("{TOKENPOST}",  base64_encode($info_post_and_user['token_galley']), $msg);
        if (if_gallery_of_you($info_post_and_user['token_galley'], $pdo) == $_SESSION['user'])
            $msg = str_replace("{REMOVE}", "<a class='delete-post'  href='../action/remove_post.php?&token=" . base64_encode($info_post_and_user['token_galley']) . "&token_index=" . $_SESSION['token_index'] . "'></a>", $msg);
        else
            $msg = str_replace("{REMOVE}", "", $msg);
    } else {
        $msg = str_replace("{CREATE_POST_COMMENT}", '', $msg);
        $msg = str_replace("{REMOVE}", "", $msg);
    }
    $total_comment = getComments_in_post($info_post_and_user['id'], $pdo);
    $comments = "";
    foreach ($total_comment as $key => $value) {
        $comment =  file_get_contents("./form/comment.txt");
        $comment = str_replace("{displayName}", $value['username'], $comment);
        $comment = str_replace("{content_comment}", $value['comment'], $comment);
        $comment = str_replace("{avatar}", "upload/" . $value['photo'], $comment);
        $comment = str_replace("{displaytime}", $value['time_comemmt'], $comment);
        $comments = $comments . $comment;
    }
    $msg = str_replace("{ID_COMMENT}", "commentPosition" . $i, $msg);
    $msg = str_replace("{COMMENTS}", $comments, $msg);
    return $msg;
}

function is_active_notification_and_username_and_email($user_name, $pdo)
{
    $stmt = $pdo->prepare("SELECT `notification`,`username`,`email` FROM `account` INNER JOIN gallery ON gallery.userid = account.user_id where gallery.token_galley = :username");
    $stmt->bindParam(":username", $user_name);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results;
}

function post2($username, $pdo, $tokenCsrf)
{
    $stmt = $pdo->prepare("SELECT gallery.img,gallery.token_galley FROM `gallery` INNER JOIN account ON account.user_id = gallery.userid WHERE account.username = :p1");
    $stmt->bindParam(":p1", $username);
    $stmt->execute();
    $imageUser =  $stmt->fetchAll();
    $images = "";
    foreach ($imageUser as $key => $value) {
        $image =  file_get_contents("./form/post2.txt");
        $image = str_replace("{IMG}", $value['img'], $image);
        $image = str_replace("{token_index}", $tokenCsrf, $image);
        $image = str_replace("{token_galley}", base64_encode($value['token_galley']), $image);
        $images = $images . $image;
    }
    return $images;
}

function count_like($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `like` INNER JOIN gallery ON gallery.id = like.galleryid WHERE gallery.token_galley=:token_galley and like.type=1");
    $stmt->bindParam(":token_galley", $id);
    $stmt->execute();
    $results = $stmt->fetch();
    return $results[0];
}
