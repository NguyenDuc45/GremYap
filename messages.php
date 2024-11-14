<?php
    session_start();
    require "database/connect.php";

    $user1_id = $_SESSION['user_id'];
    $message_rooms = $conn->query("SELECT * FROM message_rooms WHERE user1_id='$user1_id' OR user2_id='$user1_id' ORDER BY create_at DESC")->fetchAll();
    $search_input = "";

    // Get the queried room id to redirect
    if (isset($_GET['roomid'])) {
        $room_id = $_GET['roomid'];
    } else $room_id = "";

    // Get search input
    if (isset($_GET['search']))
        $search_input = $_GET['search'];

    // Clear search input
    if (isset($_REQUEST['reset']))
        $search_input = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/sidebar_list.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Document</title>
</head>
<body>
    <!-- Trigger if there is a message room redirect request -->
    <?php
    if (isset($_GET['roomid'])) {
        $room_id = $_GET['roomid']; ?>

        <a id="message" href="message_details.php?roomid=<?= $room_id ?>" target="rightI" style="display: none;"></a>
        <script>
            document.getElementById("message").click();
        </script>
    <?php } ?>

    <form action="messages.php" method="get">
        <p>Your Messages</p>
        <input type="text" name="search" id="search" placeholder="Search Messages" value="<?= $search_input ?>" autocomplete="off">
        <input type="submit" name="submit" id="submit" value="Search">
        <input type="submit" name="reset" id="reset" value="Clear">
    </form>

    <?php
    foreach($message_rooms as $message_room) {
        if ($message_room["user1_id"] == $user1_id)
            $user2_id = $message_room["user2_id"];
        else $user2_id = $message_room["user1_id"];

        $user2 = $conn->query("SELECT * FROM users WHERE id='$user2_id'")->fetch();
        $user2_name = $user2["name"];
        $user2_avatar = $user2["avatar"];
        
        $room_id = $message_room["id"];
        $last_message = $conn->query("SELECT * FROM messages WHERE room_id='$room_id' ORDER BY id DESC LIMIT 1")->fetch();

        // Check if the message room has any message to be display on the message list
        if ($last_message) {
            // Calculate the time between current time and the last time a message was sent
            date_default_timezone_set('Asia/Bangkok'); // Set the timezone to UTC+7
            $last_message_time = $last_message["create_at"];
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($last_message_time);
            $time = floor($diff / 86400);
            $time_type = "d";
            if ($time == 0) {
                $time = floor($diff / 3600);
                $time_type = "h";
            }
            if ($time == 0) {
                $time = floor($diff / 60);
                $time_type = "m";
            }
            $time = $time.$time_type;

            // Process the last message's content
            $message = $message_room["last_message"];
            if ($last_message["sender_id"] == $user1_id)
                $message = "You: " . $message;
            if (strlen($message) > 20) 
                $message = substr($message, 0, 20) . "...";

            // Get the items that match the search bar
            if ($search_input == "" || str_contains(strtolower($user2_name), strtolower($search_input))) {?>
            <div class="item">
                <a href="message_details.php?roomid=<?= $message_room["id"] ?>" target="rightI"></a>
                <img class="avatar" src="uploads/<?= $user2_avatar ?>" alt="avt">
                <div class="information">
                    <p class="name"><?= $user2_name ?></p>
                    <div class="message-information">
                        <p class="last-message"><?= $message ?></p>
                        <p class="time"><?= $time ?></p>
                    </div>
                </div>
            </div>
        <?php }
        }
    } ?>
</body>
</html>