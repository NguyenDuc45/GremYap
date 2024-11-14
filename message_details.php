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

    // Get message content, push message to the server, update the message room
    if (isset($_POST['submit'])) {
        // If the message has a file
        if ($_FILES['image']['size'] > 0) {
            $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            if ($fileExtension == "jpg" || $fileExtension == "jpeg" || $fileExtension == "png" || $fileExtension == "gif") {
                $image = $user1["username"] . date("_hisB") . "." . $fileExtension; // Make a unique name
                $imageDir = "uploads/" . $image;
                move_uploaded_file($_FILES["image"]["tmp_name"], $imageDir);

                $sql = "INSERT INTO messages (room_id, sender_id, content, message_type)
                    VALUES ('$room_id', '$user1_id', '$image', 'image')";
                $conn->exec($sql);

                date_default_timezone_set('Asia/Bangkok'); // Set the timezone to UTC+7
                $time = date("Y-m-d H:i:s");
                $sql = "UPDATE message_rooms SET last_message='Sent an image', last_message_time='$time' WHERE id='$room_id'";
                $conn->exec($sql);
            } else 
                echo "<script>
                        alert('Image must be a jpg/jpeg/png/gif');
                    </script>";
        } 
        
        else {
            $content = $_POST['content'];

            if ($content != "") {
                $sql = "INSERT INTO messages (room_id, sender_id, content, message_type)
                    VALUES ('$room_id', '$user1_id', '$content', 'text')";
                $conn->exec($sql);
    
                date_default_timezone_set('Asia/Bangkok'); // Set the timezone to UTC+7
                $time = date("Y-m-d H:i:s");
                $sql = "UPDATE message_rooms SET last_message='$content', last_message_time='$time' WHERE id='$room_id'";
                $conn->exec($sql);
            }
        }
    }
    
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
<body id="body" onload="window.scrollTo(0,document.body.scrollHeight);">
    <!-- Reload message list page if there is a message sent -->
    <?php
    if (isset($_POST['content'])) { ?>
        <a id="message" href="messages.php" target="leftI" style="display: none;"></a>
        <script>
            document.getElementById("message").click();
        </script>
    <?php } ?>

    <div class="head">
        <img src="uploads/<?= $user2["avatar"] ?>" alt="avt">
        <p><?= $user2["name"] ?></p>
        <a href="user_details.php?id=<?= $user2["id"] ?>">View Profile</a>
    </div>

    <div class="content">
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

    <div class="bottom">
        <form action="message_details.php?roomid=<?= $room_id ?>" method="POST" enctype="multipart/form-data">
            <input type="text" name="content" id="content" placeholder="Enter Message" autocomplete="off">
            <label for="image" class="image"><img src="resources/img/img-icon.png" alt="icon"></label>
            <input type="file" accept="image/*" name="image" id="image">
            <label for="submit" class="submit"><img src="resources/img/send-icon.png" alt="icon"></label>
            <input type="submit" name="submit" id="submit">
        </form>
    </div>
</body>
</html>

<script type="text/javascript">
    // Autofocus the input field
    let inputElem = document.querySelector("input"); 
    window.addEventListener('load', function(e) { 
        inputElem.focus(); 
    })

    // Reload the page
    // setInterval(reload, 1000);

    // function reload() {
    //     var xhttp = new XMLHttpRequest();
    //     xhttp.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //         document.getElementById("body").innerHTML = this.responseText;
    //         }
    //     };
    //     xhttp.open("GET", "message_details.php?roomid=<?= $room_id ?>", true);
    //     xhttp.send();
    // }

    // Send image when selected
    image.onchange = evt => {
        document.getElementById("submit").click();
    }
</script>