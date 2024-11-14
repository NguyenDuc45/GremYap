<?php
include "check_login.php";
include "server.php";
require "database/connect.php";

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>GremYap</title>
</head>

<body>
    <div class="top">
        <header>
            <div class="left">
                <div onclick="location.href='index.php'">
                    <img src="resources/img/koyuprism.gif" alt="koyuprism" class="icon">
                    <img src="resources/img/GremYap-logo.png" alt="GremYap" class="logo">
                </div>
            </div>
            <div class="right">
                <div>
                    <p class="greeting">Hello</p>
                    <p class="username"><?= $user['name'] ?></p>
                </div>
                <img src="uploads/<?= $user["avatar"] ?>" alt="avt">
            </div>
        </header>

        <nav>
            <ul>
                <li><a href="profiles.php" target="leftI">Your Profile</a></li>
                <li><a href="messages.php" target="leftI">Your Messages</a></li>
                <li><a href="friends.php" target="leftI">Your Friends</a></li>
                <li><a href="search_friends.php" target="leftI">Find Friends</a></li>
                <li><a href="friend_requests.php" target="leftI">Friend Requests</a></li>
                <!-- <li><a href="#">Settings</a></li> -->
                <li><a href="index.php?action=logout" onclick="return confirm('Do you want to logout?')">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <div class="left">
            <iframe id="leftI" src="messages.php" name="leftI" width="100%" height="100%"></iframe>
        </div>
        <div class="right">
            <iframe id="rightI" src="blank.php" name="rightI" width="100%" height="100%"></iframe>
        </div>
    </div>
</body>

</html>