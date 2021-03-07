<?php
session_start();
if (isset($_SESSION['user']))
    session_destroy();
header('Location: ../index.php');
exit();
