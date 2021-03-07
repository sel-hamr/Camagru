<?php
session_start();
include './config/database.php';
include './tools/tools.php';
include './tools/tools_post.php';
$_SESSION['token_camera'] =  hash('whirlpool', rand());
if (!isset($_SESSION['user']) && $_SESSION['user'] == '') {
    header('Location: index.php');
    exit();
} else
    $pdo = new PDO($DB_DSN . "dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);

if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    $_SESSION['navbar'] = 1;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Camera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/style_navbar.css">
    <link rel="stylesheet" href="./Style/camera.css">
    <script type="text/javascript" src="./Script/script.js"></script>
    <script type="text/javascript" src="./Script/camera.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="navbar-design" style="background-color: #393e46;">
        <img src="img/camagru.png" class="LogoCamagru">
        <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
            <img src="img/menu.png" id="btn_toggler_menu">
        </button>
        <div class="collapse navbar-collapse" id="div_menu">
            <ul class="navbar-nav mr-auto">
                <li class="ml-lg-4" id="bar_nav_home">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/home.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_home_photo">
                        <a class="nav-link font_nav" href="index.php">Home</a>
                    </div>
                </li>
                <li class="ml-lg-3">
                    <div class="form-inline mb-2 mb-lg-0">
                        <img src="img/user.png" class="mr-3 mr-lg-0 icons_navbar" id="bar_profil_photo">
                        <a class="nav-link font_nav" href="profil.php">profile</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav" id="gest"></ul>

            <ul class="navbar-nav" id="user">
                <li>
                    <div class="form-inline my-2 my-lg-0">
                        <img src="img/camera.png" class="mr-3 mr-lg-0  icons_navbar" id="bar_camera_login_photo">
                        <a class="nav-link active font_nav" href="#">Camera</a>
                    </div>
                </li>
                <li class="ml-lg-3 d-none d-lg-block">
                    <div class="form-inline mb-2 mb-lg-0 position-relative">
                        <img src="" class="photo_user" id="user_photo">
                        <a class="nav-link font_nav" href="#" id="user_name"></a>
                    </div>
                </li>
                <li class="ml-auto ml-lg-2">
                    <div class="form-inline mr-sm-1 mb-lg-0">
                        <a class="nav-link mr-3 mr-lg-1 font_nav" href="../action/logout.php">LogOut</a>
                        <img src="img/logout.png" class="icons_navbar" id="bar_logout">
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="left rounded-left  col-lg-5 col-md-12 col-ms-12 col-xs-12">
                <div class="row mt-2">
                    <img src="img/img_camera.svg" class="col-6 ml-auto mr-auto">
                </div>
                <div class="row">
                    <div class="captureImage borderDashed mt-4 col-10">
                        <p id="msjCaptureImageState" style="margin: auto;">no image found.</p>
                        <div id="snapshot" class="col-12"></div>
                    </div>
                </div>
                <div class="row">
                    <button id="btn-clear-image" type="button" class="btn btn-secondary col-3 mr-auto mt-4 ml-auto" onclick="clear_img()">clear image</button>
                    <button id="btn-save-image" type="button" class="btn btn-secondary col-3 mr-auto mt-4" onclick="image_sent()" disabled>Save Image</button>
                    <button id="btn-get-emoji" type="button" class="btn btn-secondary col-3 mr-auto mt-4" onclick="get_div_emoji()" disabled>Get Emoji</button>
                </div>
            </div>
            <div class="right col-lg-7 col-md-12 col-ms-12 col-xs-12 rounded-right">
                <p class="display-4 d-none d-lg-block mt-lg-3 ml-3" style="color: white;">Create post</p>
                <div class="mt-2 ml-3">
                    <ul class="nav">
                        <li onclick="showDivCameraCapture()" style="cursor: pointer;">
                            <a class="nav-link" style="color: white;">Camera Pc</a>
                            <div id="camera_tab_slide"></div>
                        </li>
                        <li onclick="showDivUploadImageCapture()" style="cursor: pointer;">
                            <a class="nav-link" style="color: white;">upload image</a>
                            <div id="upload_tab_slide"></div>
                        </li>
                    </ul>
                </div>
                <div id="DivCameraCapture">
                    <div class="row">
                        <div class="borderDashed col-8 mt-3 ml-auto mr-auto" style="padding-left: 2px;padding-right: 2px;border-radius: 5px;height: 317px;">
                            <video id="stream" style="border-radius: 8px;"></video>
                        </div>
                    </div>
                    <div class="row">
                        <button id="btn-capture" type="button" class="btn btn-info col-4 mt-3 ml-auto mr-auto" onclick="captureSnapshot()" disabled>Capture Image</button>
                    </div>
                </div>
                <div id="DivUploadImageCapture">
                    <div class="row">
                        <div class="borderDashed col-8 mt-3 ml-auto mr-auto" style="padding-left: 2px;padding-right: 2px;border-radius: 5px;height: 317px;display: flex;">
                            <p style="margin: auto;">Click or drag and drop to upload image</p>
                            <input type="file" id="inputUploadImage" onchange="createPostUploadFile()" onclick="resetInput()" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="divChooseEmoji" class="fullScreenDiv">
            <div class="divEmoji container">
                <div class="row">
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/cool.png" id="emoji-1" class="emoji" onclick="add_emoji_to_image(1)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/crazy.png" id="emoji-2" class="emoji" onclick="add_emoji_to_image(2)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/devil.png" id="emoji-3" class="emoji" onclick="add_emoji_to_image(3)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/hypnotized.png" id="emoji-4" class="emoji" onclick="add_emoji_to_image(4)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/laughing.png" id="emoji-5" class="emoji" onclick="add_emoji_to_image(5)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/EmojiLike.png" id="emoji-6" class="emoji" onclick="add_emoji_to_image(6)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/money.png" id="emoji-7" class="emoji" onclick="add_emoji_to_image(7)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/pirate.png" id="emoji-8" class="emoji" onclick="add_emoji_to_image(8)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/scared.png" id="emoji-9" class="emoji" onclick="add_emoji_to_image(9)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/skull.png" id="emoji-10" class="emoji" onclick="add_emoji_to_image(10)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/devilPirate.png" id="emoji-11" class="emoji" onclick="add_emoji_to_image(11)">
                    </div>
                    <div class="col-lg-2 col-3" style="padding: 5px;">
                        <img src="img/alien.png" id="emoji-12" class="emoji" onclick="add_emoji_to_image(12)">
                    </div>
                </div>
                <button type="button" class="btn btn-primary" style="margin-bottom: 10px;" onclick="document.getElementById('divChooseEmoji').style.display='none'">Close</button>
            </div>
        </div>
    </div>
    <div class="container containerPostImage">
        <div class="row">
            <?php echo post2($_SESSION['user'], $pdo, $_SESSION['token_camera']) ?>
        </div>
    </div>
    <input type="hidden" value="<?= $_SESSION['token_camera'] ?>" id="csrf-token">
    <nav class="navbar fixed-bottom  nav_button" style="background-color: #393e46;">
        <p class="copyright">copyright © 2020 sel-hamr</p>
    </nav>
    <script>
        var btnCapture = document.getElementById("btn-capture");
        var stream = document.getElementById("stream");
        var elementCanvas = document.createElement('canvas');
        elementCanvas.width = 240;
        elementCanvas.height = 240;
        var snapshot = document.getElementById("snapshot");
        var i = 0;
        var j = 0;
        var cameraStream = null;
        window.onload = function() {
            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                })
                .then(function(mediaStream) {
                    cameraStream = mediaStream;
                    if (typeof stream.srcObject == "object") stream.srcObject = mediaStream;
                    else stream.src = URL.createObjectURL(mediaStream);
                    stream.play();
                    document.getElementById("btn-capture").disabled = false;
                })
                .catch(function(e) {
                    alert(e)
                })
        };

        function captureSnapshot() {
            if (null != cameraStream) {
                i = 0;
                j = 0;
                var ctx = elementCanvas.getContext("2d");
                var ImagePost = new Image();
                ctx.drawImage(stream, 0, 0, elementCanvas.width, elementCanvas.height);
                ImagePost.width = 240;
                ImagePost.height = 240;
                ImagePost.setAttribute("id", "emj1");
                snapshot.innerHTML = "";
                snapshot.appendChild(ImagePost);
                document.querySelector("#snapshot #emj1").setAttribute("src", elementCanvas.toDataURL("image/png"));
                changeElementInSaveImage();
            }
        }

        function add_emoji_to_image(element) {
            var imageSource = document.getElementById("emj1");
            if (imageSource != null) {
                if (element == 1) var emoji_image = document.getElementById("emoji-1");
                else if (element == 2) var emoji_image = document.getElementById("emoji-2");
                else if (element == 3) var emoji_image = document.getElementById("emoji-3");
                else if (element == 4) var emoji_image = document.getElementById("emoji-4");
                else if (element == 5) var emoji_image = document.getElementById("emoji-5");
                else if (element == 6) var emoji_image = document.getElementById("emoji-6");
                else if (element == 7) var emoji_image = document.getElementById("emoji-7");
                else if (element == 8) var emoji_image = document.getElementById("emoji-8");
                else if (element == 9) var emoji_image = document.getElementById("emoji-9");
                else if (element == 10) var emoji_image = document.getElementById("emoji-10");
                else if (element == 11) var emoji_image = document.getElementById("emoji-11");
                else if (element == 12) var emoji_image = document.getElementById("emoji-12");
                var ctx = elementCanvas.getContext("2d");
                ctx.drawImage(imageSource, 0, 0, elementCanvas.width, elementCanvas.height);
                ctx.drawImage(emoji_image, i, j, 40, 40);
                document.querySelector("#snapshot #emj1").setAttribute("src", elementCanvas.toDataURL("image/png"));
                document.getElementById("divChooseEmoji").style.display = "none";
                if (i == 200) {
                    i = 0;
                    if (j == 200)
                        j = 0
                    else
                        j += 40;
                } else
                    i += 40;
            }
        }

        function createPostUploadFile() {
            i = 0;
            j = 0;
            var snapshot = document.getElementById("snapshot");
            var ctx = elementCanvas.getContext("2d");
            var img = new Image();
            var img1 = new Image();
            img.setAttribute("id", "emj1");
            img.width = 240;
            img.height = 240;
            img1.width = 240;
            img1.height = 240;
            snapshot.innerHTML = "";
            snapshot.appendChild(img);
            var input = document.getElementById("inputUploadImage");
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function() {
                var dataURL = reader.result;
                if (dataURL != "data:") {
                    img1.src = dataURL;
                    img1.onload = function() {
                        ctx.drawImage(img1, 0, 0, elementCanvas.width, elementCanvas.height);
                        document.querySelector("#snapshot #emj1").setAttribute("src", elementCanvas.toDataURL("image/png"));
                    }
                    img1.onerror = function() {
                        resetElement();
                    }
                } else resetElement();
            };
            changeElementInSaveImage();
        }

        function image_sent() {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "./action/image.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("image_data=" + document.querySelector("#snapshot #emj1").getAttribute("src") + "&" + "token=" + document.getElementById('csrf-token').value);
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    location.reload();
                }
            }
        }
    </script>
    <?php
    echo '<script type="text/javascript"> navbar(' . $_SESSION['navbar'] . ',"' . $_SESSION['photo'] . '","' . $_SESSION['user'] . '"' . ');</script>';
    echo '<script type="text/javascript"> icons_navbar(3);</script>';
    ?>
</body>

</html>