<?php
    session_start();
    require "database/connect.php";

    $room_id = $_GET['roomid'];
    $room_info = $conn->query("SELECT * FROM message_rooms WHERE id='$room_id'")->fetch();
    $user1_id = $_SESSION['user_id'];
    
    if ($room_info["user1_id"] == $user1_id)
        $user2_id = $room_info["user2_id"];
    else $user2_id = $room_info["user1_id"];

    $user1 = $conn->query("SELECT * FROM users WHERE id='$user1_id'")->fetch();
    $user2 = $conn->query("SELECT * FROM users WHERE id='$user2_id'")->fetch();
    $messages = $conn->query("SELECT * FROM messages WHERE room_id='$room_id'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/message_details.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Messages</title>
</head>
<body>
    <div id="scrollpoint"></div>
    <div>
        <?php
        foreach($messages as $message) {
            $sender_id = $message["sender_id"];
            if ($sender_id == $user1_id) { ?>
                <div class="message message1">
                    <img class="img1" src="uploads/<?= $user1["avatar"] ?>" alt="avt">
                    <?php if ($message["message_type"] == "text") { ?>
                        <p class="p1" title="<?= $message["create_at"]?>"><?= $message["content"] ?></p>
                    <?php } else { ?>
                        <img class="img-mes-1" src="uploads/<?= $message["content"] ?>" alt="img" title="<?= $message["create_at"]?>">
                    <?php } ?>
                    
                </div>
            <?php } 
            else if ($sender_id == $user2_id) {?>
                <div class="message message2">
                    <img class="img2" src="uploads/<?= $user2["avatar"] ?>" alt="avt">
                    <?php if ($message["message_type"] == "text") { ?>
                        <p class="p2" title="<?= $message["create_at"]?>"><?= $message["content"] ?></p>
                    <?php } else { ?>
                        <img class="img-mes-2" src="uploads/<?= $message["content"] ?>" alt="img" title="<?= $message["create_at"]?>">
                    <?php } ?>
                </div>
            <?php } 
        } ?>
    </div>
</body>
</html>