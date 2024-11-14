<?php
    session_start();
    $db = mysqli_connect('localhost', 'root', '', 'gremyap');
    require "database/connect.php";

    // current_user: the one who is using the browser
    // user: the other user who is not browsing this browser
    $current_user_id = $_SESSION['user_id'];
    $user_id = $_GET['id'];
    $user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch();
    $user_setting = $conn->query("SELECT * FROM user_settings WHERE user_id='$user_id'")->fetch();
    $name = $user["name"];
    $birthday = date('F jS, Y', strtotime($user["birthday"]));
    $address = $user["address"];


    if (isset($_GET['action'])) {
        // Add friend
        if ($_GET['action'] == "addfriend") {
            $sql = "INSERT INTO friend_requests (sender_id, receiver_id)
                    VALUES ('$current_user_id', '$user_id')";
            $conn->exec($sql);
        }

        // Accept friend request
        if ($_GET['action'] == "accept") {
            // Check if friend is already exists to prevent dupes
            $query = "SELECT * FROM friends WHERE user_id='$current_user_id' AND friend_id='$user_id'";
            $results = mysqli_query($db, $query);

            if (mysqli_num_rows($results) == 0) {
                // Add friend for both users
                $sql = "INSERT INTO friends (user_id, friend_id)
                        VALUES ('$current_user_id', '$user_id')";
                $conn->exec($sql);

                $sql = "INSERT INTO friends (user_id, friend_id)
                        VALUES ('$user_id', '$current_user_id')";
                $conn->exec($sql);
            }

            // Delete friend request
            $sql = "DELETE FROM friend_requests WHERE sender_id='$user_id' AND receiver_id='$current_user_id'";
            $conn->exec($sql);

            if (isset($_GET['blank']))
                header("Location: blank.php");
        }

        // Reject friend request
        if ($_GET['action'] == "reject") {
            $sql = "DELETE FROM friend_requests WHERE sender_id='$user_id' AND receiver_id='$current_user_id'";
            $conn->exec($sql);

            header("Location: blank.php");
        }

        // Cancel friend request
        if ($_GET['action'] == "cancelrequest") {
            $sql = "DELETE FROM friend_requests WHERE sender_id='$current_user_id' AND receiver_id='$user_id'";
            $conn->exec($sql);

            if (isset($_GET['blank']))
                header("Location: blank.php");
        }

        // Delete friend
        if ($_GET['action'] == "unfriend") {
            $sql = "DELETE FROM friends WHERE user_id='$current_user_id' AND friend_id='$user_id'";
            $conn->exec($sql);

            $sql = "DELETE FROM friends WHERE user_id='$user_id' AND friend_id='$current_user_id'";
            $conn->exec($sql);
        }

        // Go to messages
        if ($_GET['action'] == "message") {
            $query = "SELECT * FROM message_rooms WHERE (user1_id='$current_user_id' AND user2_id='$user_id')
                                                     OR (user1_id='$user_id' AND user2_id='$current_user_id')";
            $results = mysqli_query($db, $query);

            // Create a message room if it does not exist
            if (mysqli_num_rows($results) <= 0) {
                date_default_timezone_set('Asia/Bangkok'); // Set the timezone to UTC+7
                // $time = date("F jS Y H:i:s");
                $time = date("Y-m-d H:i:s");

                $sql = "INSERT INTO message_rooms (user1_id, user2_id, last_message, last_message_time)
                        VALUES ('$current_user_id', '$user_id', '', '$time')";
                $conn->exec($sql);
            }

            // Get the room id, trigger the action variable, then go to that message room
            $room_id = $conn->query("SELECT id FROM message_rooms 
                                     WHERE (user1_id='$current_user_id' AND user2_id='$user_id')
                                        OR (user1_id='$user_id' AND user2_id='$current_user_id')")->fetch()['id'];
            header("Location: blank.php?action=message&roomid=$room_id");
        }
    }


    // Get the friend request type
    if (isset($_GET['type'])) {
        if ($_GET['type'] == "sentrequest") {
            $requestType = "sent";
        }

        // Cancel friend request
        if ($_GET['type'] == "receivedrequest") {
            $requestType = "received";
        }
    } else $requestType = "none";


    // Check if the user is a friend or not
    $query = "SELECT * FROM friends WHERE user_id='$current_user_id' AND friend_id='$user_id'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1)
        $isFriend = true;
    else $isFriend = false;

    // Check if there is a friend request
    $query = "SELECT * FROM friend_requests WHERE sender_id='$current_user_id' AND receiver_id='$user_id'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1)
        $isPending = true;
    else $isPending = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/user_details.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>User Details</title>
</head>
<body>
    <div class="content">
        <img src="uploads/<?= $user["avatar"] ?>" alt="avt">
        <p class="name"><?= $name ?></p>
        <p class="birthday">Date of Birth: 
            <?php if ($user_setting["show_birthday"] == "true" || $current_user_id == $user_id) echo $birthday;
                    else echo "Hidden"?></p>
        <p class="address">Live in: 
            <?php if ($user_setting["show_address"] == "true" || $current_user_id == $user_id) echo $address;
                    else echo "Hidden"?></p>
        <div>
            <?php
            // Check if the user is not the current user
            if ($current_user_id != $user_id) {
                // Check if the user has a friend request
                if ($requestType != "none") {
                    if ($requestType == "sent") { ?>
                        <p>You sent them a friend request</p>
                        <a href="friend_requests.php" target="leftI" onclick="location.href='user_details.php?id=<?= $user_id ?>&action=cancelrequest&blank=true'">Cancel Request</a>
                <?php } else if ($requestType == "received") { ?>
                        <p>They sent you a friend request</p>
                        <a href="friend_requests.php" target="leftI" onclick="location.href='user_details.php?id=<?= $user_id ?>&action=accept&blank=true'">Accept</a>
                        <a href="friend_requests.php" target="leftI" onclick="location.href='user_details.php?id=<?= $user_id ?>&action=reject'">Reject</a>
                <?php }
                } else {
                // Add an add friend/cancel friend request/unfriend button base on friend status
                if ($isFriend) { ?>
                    <a href="friends.php" target="leftI" onclick="location.href='user_details.php?id=<?= $user_id ?>&action=unfriend'">Unfriend</a>
                <?php } else {
                        if ($isPending) { ?>
                            <a href="user_details.php?id=<?= $user_id ?>&action=cancelrequest">Cancel Request</a>
                    <?php } else { ?>
                            <a href="user_details.php?id=<?= $user_id ?>&action=addfriend">Add Friend</a>
                <?php } } ?>
                <a href="user_details.php?id=<?= $user_id ?>&action=message">Message</a>
            <?php } 
            }?>
        </div>
    </div>
</body>
</html>