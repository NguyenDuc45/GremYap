<?php
    session_start();
    require "database/connect.php";
    $user_id = $_SESSION['user_id'];
    $sent_requests = $conn->query("SELECT * FROM friend_requests WHERE sender_id='$user_id'")->fetchAll();
    $received_requests = $conn->query("SELECT * FROM friend_requests WHERE receiver_id='$user_id'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/sidebar_list.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Friend Requests</title>
</head>
<body>
    <p class="title">Friend Requests</p>

    <?php
    if (sizeof($sent_requests) <= 0 && sizeof($received_requests) <= 0)
        echo "<p class='no-request'>You have no request</p>";
    else {
        // Display sent requests
        if (sizeof($sent_requests) > 0)
            echo "<p class='subtitle'>Your Requests</p>";

        // s_ is for "sent"
        foreach($sent_requests as $s_user) {
            $s_user_id = $s_user["receiver_id"];
            $s_user_details = $conn->query("SELECT * FROM users WHERE id='$s_user_id'")->fetch();
            $s_user_name = $s_user_details["name"];
            $s_user_avatar = $s_user_details["avatar"];
            $s_user_address = $s_user_details["address"];
            $s_user_birthday = $s_user_details["birthday"];
            $s_user_age = date("Y") - date("Y", strtotime($s_user_birthday)); ?>
    
            <div class="item">
                <a href="user_details.php?id=<?= $s_user_id ?>&type=sentrequest" target="rightI"></a>
                <img class="avatar" src="uploads/<?= $s_user_avatar ?>" alt="avt">
                <div class="information">
                    <p class="name"><?php echo $s_user_name ?></p>
                    <p class="age">Age <?php echo $s_user_age ?></p>
                    <p class="address"><?php echo $s_user_address ?></p>
                </div>
            </div>
        <?php } ?>
    

        <?php
        // Display received requests
        if (sizeof($sent_requests) > 0)
            echo "<br><br>";

        if (sizeof($received_requests) > 0)
            echo "<p class='subtitle'>Received Requests</p>";

        //r_ is for "received"
        foreach($received_requests as $r_user) {
            $r_user_id = $r_user["sender_id"];
            $r_user_details = $conn->query("SELECT * FROM users WHERE id='$r_user_id'")->fetch();
            $r_user_name = $r_user_details["name"];
            $r_user_avatar = $r_user_details["avatar"];
            $r_user_address = $r_user_details["address"];
            $r_user_birthday = $r_user_details["birthday"];
            $r_user_age = date("Y") - date("Y", strtotime($r_user_birthday)); ?>
    
            <div class="item">
                <a href="user_details.php?id=<?= $r_user_id ?>&type=receivedrequest" target="rightI"></a>
                <img class="avatar" src="uploads/<?= $r_user_avatar ?>" alt="avt">
                <div class="information">
                    <p class="name"><?php echo $r_user_name ?></p>
                    <p class="age">Age <?php echo $r_user_age ?></p>
                    <p class="address"><?php echo $r_user_address ?></p>
                </div>
            </div>
        <?php }
    }
    ?>
</body>
</html>