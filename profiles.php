<?php
    session_start();
    $user_id = $_SESSION["user_id"]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/profiles.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Your Profile</title>
</head>
<body>
    <ul>
        <li><a href="user_details.php?id=<?= $user_id ?>" target="rightI">Your Informations</a></li>
        <li><a href="profile_details.php?type=information" target="rightI">Edit Informations</a></li>
        <li><a href="profile_details.php?type=privacy" target="rightI">Privacy Settings</a></li>
        <!-- <li><a href="" target="rightI">Other Settings</a></li> -->
    </ul>
</body>
</html>