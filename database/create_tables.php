<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gremyap";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Create table users
    $sql = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(50) NOT NULL,
        email VARCHAR(50),
        name VARCHAR(30) NOT NULL,
        avatar VARCHAR(100),
        address VARCHAR(50),
        birthday VARCHAR(15),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table users created successfully" . "<br>";


    // Create table user_settings
    $sql = "CREATE TABLE user_settings (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        show_birthday VARCHAR(5),
        show_address VARCHAR(5),
        FOREIGN KEY (user_id) REFERENCES users(id),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table user_settings created successfully" . "<br>";


    // Create table friends
    $sql = "CREATE TABLE friends (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        friend_id INT(6) UNSIGNED NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (friend_id) REFERENCES users(id),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table friends created successfully" . "<br>";


    // Create table friend_requests
    $sql = "CREATE TABLE friend_requests (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sender_id INT(6) UNSIGNED NOT NULL,
        receiver_id INT(6) UNSIGNED NOT NULL,
        content TEXT,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table friend_request created successfully" . "<br>";


    // Create table message_rooms
    $sql = "CREATE TABLE message_rooms (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user1_id INT(6) UNSIGNED,
        user2_id INT(6) UNSIGNED,
        last_message TEXT,
        last_message_time VARCHAR(40),
        FOREIGN KEY (user1_id) REFERENCES users(id),
        FOREIGN KEY (user2_id) REFERENCES users(id),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table message_rooms created successfully" . "<br>";


    // Create table messages
    $sql = "CREATE TABLE messages (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        room_id INT UNSIGNED,
        sender_id INT(6) UNSIGNED,
        content TEXT,
        message_type VARCHAR(10),
        FOREIGN KEY (room_id) REFERENCES message_rooms(id),
        FOREIGN KEY (sender_id) REFERENCES users(id),
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $conn->exec($sql);
    echo "Table messages created successfully" . "<br>";
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;