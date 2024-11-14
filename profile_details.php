<?php
    session_start();
    require "database/connect.php";

    $page_type = $_GET["type"];
    $user_id = $_SESSION["user_id"];
    $user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch();
    $user_setting = $conn->query("SELECT * FROM user_settings WHERE user_id='$user_id'")->fetch();
    
    include('server.php');

    $username = $user["username"];
    $password = $user["password"];
    $repassword = $user["password"];
    $email = $user["email"];
    $name = $user["name"];
    $avatar = $user["avatar"];
    $address = $user["address"];
    $birthday = $user["birthday"];

    $show_birthday = $user_setting["show_birthday"];
    $show_address = $user_setting["show_address"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/profile_details.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Profile Details</title>
</head>
<body>
    <!-- Edit informations page -->
    <?php if ($page_type == "information") { ?>
    <form action="profile_details.php?type=information" method="POST" enctype="multipart/form-data" class="form1">
        <div class="left">
            <label for="username">Username:</label> <br>
            <input type="text" name="username" id="username" value="<?= $username ?>" disabled> <br>

            <label for="password">Password:</label> <br>
            <input type="password" name="password" id="password"> <br>

            <label for="repassword">Confirm Password:</label> <br>
            <input type="password" name="repassword" id="repassword"> <br>

            <label for="email">Email:</label> <br>
            <input type="email" name="email" id="email" value="<?= $email ?>" disabled> <br>

            <label for="name">Your name:</label> <br>
            <input type="text" name="name" id="name" value="<?= $name ?>" autocomplete="off"> <br>

            <label for="address">Address:</label> <br>
            <input type="text" name="address" id="address" value="<?= $address ?>" autocomplete="off"> <br>

            <label for="birthday">Your Birthday:</label> <br>
            <input type="date" name="birthday" id="birthday" value="<?= $birthday ?>"> <br>
        </div>

        <div class="right">
            <label for="avatar">Avatar:</label> <br>
            <label for="avatar" class="upload-btn">Upload</label>
            <input type="file" accept=".jpg, .jpeg, .png" name="avatar" id="avatar"> <br>

            <div class="prev-img">
                <img src="uploads/<?= $user["avatar"] ?>" alt="preview" id="img1">
                <div class="filler"></div>
                <img src="uploads/<?= $user["avatar"] ?>" alt="preview" id="img2" style="border-radius:100%">
            </div>

            <input type="submit" name="update_information" value="Save">
            <input type="button" value="Cancel" onclick="location.href='profile_details.php?type=information'">
            <p class="error"><?= $error ?></p>
        </div>
    </form>
    <?php } ?>

    <!-- Privacy settings page -->
    <?php if ($page_type == "privacy") { ?>
    <form action="profile_details.php?type=privacy" method="POST" class="form2">
        <p>Show Birthday:</p>
        <input type="radio" id="true1" name="show_birthday" value="true" 
            <?php if ($show_birthday == "true") echo 'checked="checked"'; ?>>
        <label for="true1">On</label>
        <input type="radio" id="false1" name="show_birthday" value="false" 
            <?php if ($show_birthday == "false") echo 'checked="checked"'; ?>>
        <label for="false1">Off</label> <br>

        <p>Show Address:</p>
        <input type="radio" id="true2" name="show_address" value="true" 
            <?php if ($show_address == "true") echo 'checked="checked"'; ?>>
        <label for="true2">On</label>
        <input type="radio" id="false2" name="show_address" value="false" 
            <?php if ($show_address == "false") echo 'checked="checked"'; ?>>
        <label for="false2">Off</label> <br>

        <input type="submit" name="update_privacy" value="Save">
        <input type="button" value="Cancel" onclick="location.href='profile_details.php?type=privacy'">
    </form>
    <?php } ?>
</body>
</html>

<script>
    // Preview image
    avatar.onchange = evt => {
        const [file] = avatar.files
        if (file) {
            img1.src = URL.createObjectURL(file)
            img2.src = URL.createObjectURL(file)
        }
    }
</script>