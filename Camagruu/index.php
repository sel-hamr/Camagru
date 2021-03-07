<?php
session_start();
include './config/database.php';
include './config/setup.php';
include './tools/tools.php';
include './tools/tools_post.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == 1 && isset($_SESSION['user']) && $_SESSION['user'] != '') {
    $_SESSION['navbar'] = 1;
    $_SESSION['NameUser'] = $_SESSION['user'];
    if (!isset($_SESSION['token_index']))
        $_SESSION['token_index'] =  hash('whirlpool', rand());
} else {
    $_SESSION['navbar'] = 0;
    $_SESSION['photo'] = 0;
    $_SESSION['NameUser'] = 0;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/style_navbar.css">
    <link rel="stylesheet" href="./Style/post.css">
    <script type="text/javascript" src="./Script/script.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="navbar-design" style="background-color: #393e46;">
        <img src="img/camagru.png" class="LogoCamagru">
        <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
            <img src="img/menu.png" id="btn_toggler_menu">
        </button>
        <div class="collapse navbar-collapse" id="div_menu">
            <ul class="navbar-nav mr-auto">
                <li class="ml-lg-4 ">
                    <div class="form-inline mb-2 mb-lg-0 ">
                        <img src="img/home.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_home_photo">
                        <a class="nav-link active font_nav" href="index.php">Home</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/user.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_profil_photo">
                        <a class="nav-link font_nav" href="profil.php">profile</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav" id="gest">
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0 position-relative">
                        <img src="img/sing_up.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_sing_up_photo">
                        <a class="nav-link font_nav" href="create_sing_up.php">Sign Up</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0 position-relative">
                        <img src="img/login.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_login_photo">
                        <a class="nav-link font_nav" href="login.php">LogIn </a>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav" id="user">
                <li>
                    <form class="form-inline mb-2 mb-lg-0 position-relative">
                        <img src="img/camera.png" class="mr-3 mr-lg-1 icons_navbar" id="bar_camera_login_photo">
                        <a class="nav-link font_nav" href="camera.php">Camera</a>
                    </form>
                </li>
                <li class="d-none d-lg-block ml-lg-3  mr-lg-3">
                    <form class="form-inline mb-2 mb-lg-0 position-relative">
                        <img src="" class="photo_user" id="user_photo">
                        <a class="nav-link font_nav" href="#" id="user_name"></a>
                    </form>
                </li>
                <li class=" ml-auto ml-lg-2">
                    <form class="form-inline mr-sm-1 mb-lg-0 position-relative">
                        <a class="nav-link mr-3 mr-lg-1 font_nav" href="../action/logout.php">LogOut</a>
                        <img src="img/logout.png" class="icons_navbar" id="bar_logout">
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php
        if (isset($_GET['index']) && $_GET['index'] != '' && is_numeric($_GET['index']) && $_GET['index'] > 0)
            $pagination = intval($_GET['index']);
        else
            $pagination = 1;
        $PostsInPage = 5;
        $IndexStart = ($pagination - 1) * $PostsInPage;
        $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `gallery`");
        $stmt->execute();
        $count = $stmt->fetch();
        $total_pages = ceil($count[0] / $PostsInPage);
        $stmt = $pdo->prepare("SELECT * FROM `gallery` ORDER BY id DESC LIMIT $IndexStart, $PostsInPage ");
        $stmt->execute();
        $posts = $stmt->fetchAll();
        $i = 1;
        foreach ($posts as $key => $post) {
            $stmt = $pdo->prepare("SELECT * FROM `account` INNER JOIN `gallery` ON account.user_id = gallery.userid WHERE gallery.id =:p1");
            $stmt->bindParam(":p1", $post['id']);
            $stmt->execute();
            $info_post_and_user = $stmt->fetch();
            if (isset($_SESSION['user']) && $_SESSION['user'] != "") {
                $token_user = get_info_user($_SESSION['user'], $pdo)['token'];
                $stmt = $pdo->prepare("SELECT `type` FROM `like` INNER JOIN account ON account.user_id = like.userid INNER JOIN gallery ON gallery.id = like.galleryid WHERE account.token = :token and gallery.token_galley=:token_galley");
                $stmt->bindParam(":token",  $token_user);
                $stmt->bindParam(":token_galley", $info_post_and_user['token_galley']);
                $stmt->execute();
                $type = $stmt->fetch();
                if (is_array($type))
                    $type_like = $type[0];
                else
                    $type_like = 2;
            } else
                $type_like = 0;
            echo post($info_post_and_user, $type_like, $pdo, $i);
            $i++;
        }
        ?>
        <nav style=" margin-bottom: 44px;">
            <ul class="pagination justify-content-center">
                <?php
                echo  "<li ><a class='page-link' href='index.php?index=1' > First </a></li>";
                $i = 1;
                while ($i <= $total_pages) {
                    echo "<li ><a class='page-link' href='index.php?index=" . $i . "'> " . $i . " </a></li>";
                    $i++;
                }
                echo "<li ><a class='page-link' href='index.php?index=" . $total_pages . "'> Last </a></li>";
                ?>
            </ul>
        </nav>
    </div>
    <div class="msj_error_activer">
        <div class="alert alert-danger" id="msj_register">
            <button type="button" class="close" onclick="hide_msj_error(0)">x</button>
            join camagru today please register .
        </div>
    </div>
    <nav class="navbar fixed-bottom  nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <script>
        function like(tokenPost, imageId, idElementCountLike) {
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(idElementCountLike).innerHTML = "   " + this.responseText + " like";
                    if (document.getElementById(imageId).src == window.location.origin + "/img/like.png")
                        document.getElementById(imageId).src = "img/no_like.png";
                    else
                        document.getElementById(imageId).src = "img/like.png";

                }
            };
            xhttp.open("POST", "../action/like.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("galleryToken=" + tokenPost);
        }

        function CreateComment(ValueCommentId, tokenPost, getPositionElementComment, count_comment) {
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                var ValueComment = document.getElementById(ValueCommentId).value;
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText != "error") {
                        document.getElementById(ValueCommentId).value = "";
                        var response = this.responseText.split("@");
                        var comment = document.createElement("div");
                        comment.className = "comment";
                        var content_comment = document.createElement("span");
                        content_comment.innerText = ValueComment;
                        content_comment.className = "content_comment";
                        var header = document.createElement("div");
                        header.className = "header";
                        var avatar = document.createElement("div");
                        avatar.className = "avatar";
                        var info_user = document.createElement("div");
                        info_user.className = "info_user";
                        var displayName = document.createElement("span");
                        displayName.innerHTML = response[0];
                        displayName.className = "displayName";
                        var displaytime = document.createElement("span");
                        displaytime.innerHTML = response[1];
                        displaytime.className = "displaytime text-muted";
                        var profil_comment_user = document.createElement("img");
                        profil_comment_user.src = response[2];
                        profil_comment_user.className = "profil_comment_user";
                        document.getElementById(getPositionElementComment).appendChild(comment);
                        comment.appendChild(header);
                        comment.appendChild(content_comment);
                        header.appendChild(avatar);
                        header.appendChild(info_user);
                        info_user.appendChild(displayName);
                        info_user.appendChild(displaytime);
                        avatar.appendChild(profil_comment_user);
                        document.getElementById(count_comment).innerHTML = response[3] + "  comment";
                    } else
                        alert("comment valid");
                }
            };
            xhttp.open("POST", "./action/comment.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("galleryToken=" + tokenPost + "&" + "commentValue=" + document.getElementById(ValueCommentId).value);
        }
    </script>
    <?php
    echo '<script type="text/javascript"> navbar(' . $_SESSION['navbar'] . ',"' . $_SESSION['photo'] . '","' . $_SESSION['NameUser'] . '"' . ');</script>';
    echo '<script type="text/javascript"> icons_navbar(1);</script>';
    echo '<script type="text/javascript"> msj_error(' . $_SESSION['navbar'] . ');</script>';
    ?>
</body>

</html>