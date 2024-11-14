<?php include('server.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Sign Up</title>
</head>

<body>
    <img src="resources/img/GremYap-logo.png" alt="logo">

    <div class="content">
        <div class="form">
            <div class="btn">
                <a href="login.php" id="login">Sign In</a>
                <a href="register.php" id="register">Sign Up</a>
            </div>

            <form action="register.php" method="POST" enctype="multipart/form-data">
                <div class="input">
                    <div class="box">
                        <div class="input-left">
                            <label for="username">Username:</label> <br>
                            <input type="text" name="username" id="username" value="<?= $username ?>" autocomplete="off"> <br>

                            <label for="password">Password:</label> <br>
                            <input type="password" name="password" id="password"> <br>

                            <label for="repassword">Confirm Password:</label> <br>
                            <input type="password" name="repassword" id="repassword"> <br>

                            <label for="email">Email:</label> <br>
                            <input type="email" name="email" id="email" value="<?= $email ?>" autocomplete="off"> <br>
                        </div>

                        <div class="input-middle">
                            <label for="name">Your name:</label> <br>
                            <input type="text" name="name" id="name" value="<?= $name ?>" autocomplete="off"> <br>

                            <label for="address">Address:</label> <br>
                            <input type="text" name="address" id="address" value="<?= $address ?>" autocomplete="off"> <br>

                            <label for="birthday">Your Birthday:</label> <br>
                            <input type="date" name="birthday" id="birthday" value="<?= $birthday ?>">
                        </div>

                        <div class="input-right">
                            <label for="avatar">Avatar:</label> <br>
                            <label for="avatar" class="upload-btn">Upload</label>
                            <input type="file" accept=".jpg, .jpeg, .png" name="avatar" id="avatar"> <br>
                            <div class="prev-img">
                                <img src="resources/img/no-avatar.png" alt="preview" id="img1">
                                <div class="filler"></div>
                                <img src="resources/img/no-avatar.png" alt="preview" id="img2" style="border-radius:100%">
                            </div>
                        </div>
                    </div>
                </div>

                <input type="submit" name="register" value="Sign Up">
                <p class="error"><?= $error ?></p>
            </form>
            <p class="login">Already have an account? <a href="login.php">Sign in</a> here!</p>
        </div>
    </div>
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