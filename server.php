<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "database/connect.php";
$db = mysqli_connect('localhost', 'root', '', 'gremyap');

// Initialize variables
$username = "";
$email    = "";
$name     = "";
$address  = "";
$birthday = "";
$error    = "";


// REGISTER
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $fileExtension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);

    // Form validation
    if (empty($username))
        $error = "Username is required";

    else if (empty($password))
        $error = "Password is required";

    else if (empty($repassword))
        $error = "Please comfirm your password";
    
    else if ($password != $repassword)
        $error = "The two passwords do not match";

    else if (empty($email))
        $error = "Email is required";

    else if (empty($name))
        $error = "A name is required";

    else if ($_FILES['avatar']['size'] == 0)
        $error = "An avatar is required";

    else if ($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png")
        $error = "Uploaded file must be a jpg/jpeg/png";

    else if (empty($address))
        $error = "Enter your address";

    else if (empty($birthday))
        $error = "Enter your birthday";

    else {
        // Check the database to make sure a user does not already exist with the same username and/or email
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        // If user and/or email already exist
        if ($user) { 
            if ($user['username'] === $username)
                $error = "Username already exists";

            else if ($user['email'] === $email)
                $error = "Email is already used";
        }

        else {
            $password = md5($password); // Encrypt the password before saving in the database

            // Process the avatar
            $avatar = $username . date("_hisB") . "." . $fileExtension; // Make a unique image name
            $avatarDir = "uploads/" . $avatar;
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatarDir);

            // Create user
            $query = "INSERT INTO users (username, password, email, name, avatar, address, birthday) 
                    VALUES('$username', '$password', '$email', '$name', '$avatar', '$address', '$birthday')";
            mysqli_query($db, $query);

            $user = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch();
            $_SESSION['user_id'] = $user["id"];

            // Create default user settings
            $user_id = $_SESSION['user_id'];
            $query = "INSERT INTO user_settings (user_id, show_birthday, show_address) 
                    VALUES('$user_id', 'true', 'true')";
            mysqli_query($db, $query);

            // Redirect to the main page
            header('Location: index.php');
        }
    }
}


// LOGIN
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Form validation
    if (empty($username))
        $error = "Username is required";
    
    else if (empty($password))
        $error = "Password is required";
    
    else {
        $password = md5($password); // Encrypt the password
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $loginWith = "username";
            $isAbleToLogin = true;
        }
        else {
            $query = "SELECT * FROM users WHERE email='$username' AND password='$password'";
            $results = mysqli_query($db, $query);

            if (mysqli_num_rows($results) == 1) {
                $loginWith = "email";
                $isAbleToLogin = true;
            }
            else $isAbleToLogin = false;
        }

        if ($isAbleToLogin) {
            if ($loginWith == "username")
                $user = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch();
            else if ($loginWith == "email")
                $user = $conn->query("SELECT * FROM users WHERE email='$username'")->fetch();

            $_SESSION['user_id'] = $user["id"];
            header('Location: index.php');
        } else {
            $error = "Username or password is incorrect";
        }
    }
}


// LOGOUT
if (isset($_GET['action'])) {
    if ($_GET['action'] == "logout") {
        unset($_SESSION['user']);
        header("Location: login.php");
    }
}


// UDDATE INFORMATION
if (isset($_POST['update_information'])) {
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $fileExtension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);

    // Form validation
    if (empty($password))
        $error = "Password is required";

    else if (empty($repassword))
        $error = "Please comfirm your password";
    
    else if ($password != $repassword)
        $error = "The two passwords do not match";

    else if (empty($name))
        $error = "A name is required";

    else if ($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png" && $_FILES['avatar']['size'] > 0)
        $error = "Uploaded file must be a jpg/jpeg/png";

    else if (empty($address))
        $error = "Enter your address";

    else if (empty($birthday))
        $error = "Enter your birthday";

    else {
        $password = md5($password); // Encrypt the password before saving in the database

        if ($_FILES['avatar']['size'] > 0) {
            // Process the avatar
            $avatar = $user["username"] . date("_hisB") . "." . $fileExtension; // Make a unique image name
            $avatarDir = "uploads/" . $avatar;
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatarDir);
        } else $avatar = $user["avatar"];

        // Update user
        $sql = "UPDATE users SET password='$password', name='$name', avatar='$avatar', 
                                 address='$address', birthday='$birthday' WHERE id='$user_id'";
        $conn->exec($sql);

        $_SESSION['name'] = $name;

        // Reload page
        header('Location: profile_details.php?type=information');
    }
}


// UDDATE PRIVACY
if (isset($_POST['update_privacy'])) {
    $show_birthday = $_POST['show_birthday'];
    $show_address = $_POST['show_address'];

    // Update privacy
    $sql = "UPDATE user_settings SET show_birthday='$show_birthday', show_address='$show_address' 
            WHERE user_id='$user_id'";
    $conn->exec($sql);

    // Reload page
    header('Location: profile_details.php?type=privacy');
}