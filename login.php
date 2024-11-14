<?php include('server.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Sign in</title>
</head>

<body>
    <img src="resources/img/GremYap-logo.png" alt="logo">

    <div class="content">
        <div class="form">
            <div class="btn">
                <a href="login.php" id="login">Sign In</a>
                <a href="register.php" id="register">Sign Up</a>
            </div>

            <div class="text">
                <p>Welcome back!</p>
                <p>Please login to your account</p>
            </div>

            <form action="login.php" method="POST">
                <div class="input">
                    <label for="username">Username or Email:</label> <br>
                    <input type="text" name="username" id="username" value="<?= $username ?>" autocomplete="off"> <br>

                    <label for="password">Password:</label> <br>
                    <input type="password" name="password" id="password"> <br>
                </div>

                <input type="submit" name="login" value="Login">
                <p class="error"><?= $error ?></p>
            </form>
            <p class="signup">Don't have an account? <a href="register.php">Sign up</a> now!</p>
        </div>
    </div>
</body>

</html>