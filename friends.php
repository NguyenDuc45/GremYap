<?php
    session_start();
    require "database/connect.php";
    $user_id = $_SESSION['user_id'];
    $friends = $conn->query("SELECT * FROM friends WHERE user_id='$user_id'")->fetchAll();
    $search_input = "";

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
    <title>Friends</title>
</head>
<body>
    <form action="friends.php" method="get">
        <p>Friend List</p>
        <input type="text" name="search" id="search" placeholder="Search Friends" value="<?= $search_input ?>" autocomplete="off">
        <input type="submit" name="submit" id="submit" value="Search">
        <input type="submit" name="reset" id="reset" value="Clear">
    </form>

    <?php
        if (sizeof($friends) <= 0)
            echo "<p class='no-request'>You have no friend :(</p>";
        else 
        {
            foreach($friends as $friend) {
                $friend_id = $friend["friend_id"];
                $friend_details = $conn->query("SELECT * FROM users WHERE id='$friend_id'")->fetch();
                $friend_name = $friend_details["name"];
                $friend_avatar = $friend_details["avatar"];
                $friend_address = $friend_details["address"];
                $friend_birthday = $friend_details["birthday"];
                $friend_age = date("Y") - date("Y", strtotime($friend_birthday));
                
                // Get the items that match the search bar
                if ($search_input == "" || str_contains(strtolower($friend_name), strtolower($search_input))) {?>
                <div class="item">
                    <a href="user_details.php?id=<?= $friend_id ?>" target="rightI"></a>
                    <img class="avatar" src="uploads/<?= $friend_avatar ?>" alt="avt">
                    <div class="information">
                        <p class="name"><?php echo $friend_name ?></p>
                        <p class="age">Age <?php echo $friend_age ?></p>
                        <p class="address"><?php echo $friend_address ?></p>
                    </div>
                </div>
        <?php   } 
            } 
        }?>
</body>
</html>