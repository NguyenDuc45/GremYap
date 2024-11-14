<?php
    session_start();
    require "database/connect.php";
    $user_id = $_SESSION['user_id'];
    $friends = $conn->query("SELECT * FROM users WHERE NOT id='$user_id' 
                            AND id NOT IN (SELECT friend_id FROM friends WHERE user_id='$user_id')")->fetchAll();
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
    <title>Search Friends</title>
</head>
<body>
    <form action="search_friends.php" method="get">
        <p>Search Friends</p>
        <input type="text" name="search" id="search" placeholder="Search Friends" value="<?= $search_input ?>" autocomplete="off">
        <input type="submit" name="submit" id="submit" value="Search">
        <input type="submit" name="reset" id="reset" value="Clear">
    </form>

    <?php
        foreach($friends as $friend) {
            $friend_id = $friend["id"];
            $friend_details = $conn->query("SELECT * FROM users WHERE id='$friend_id'")->fetch();
            $friend_name = $friend_details["name"];
            $friend_avatar = $friend_details["avatar"];
            $friend_address = $friend_details["address"];
            $friend_birthday = $friend_details["birthday"];
            $friend_age = date("Y") - date("Y", strtotime($friend_birthday));

            // // Check if the searched user is already a friend or not
            // $db = mysqli_connect('localhost', 'root', '', 'gremyap');
            // $query = "SELECT * FROM friends WHERE user_id='$user_id' AND friend_id='$friend_id'";
            // $results = mysqli_query($db, $query);
            // if (mysqli_num_rows($results) == 1)
            //     $isFriend = true;
            // else $isFriend = false;
            
            // Display the searched user
            if ($search_input != "" && str_contains(strtolower($friend_name), strtolower($search_input))) {?>

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
        } ?>
</body>
</html>