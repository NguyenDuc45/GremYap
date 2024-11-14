<?php
    // if (isset($_GET['action'])) {
    //     if ($_GET['action'] == "message") {
    //         $room_id = $_GET['roomid'];
    //     }
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="resources/img/gremyap.png">
    <title>Blank</title>
</head>
<body>
    <?php
        if (isset($_GET['action'])) {
            if ($_GET['action'] == "message") {
                $room_id = $_GET['roomid']; ?>

                <a id="message" href="messages.php?roomid=<?= $room_id ?>" target="leftI" style="display: none;"></a>
                <script>
                    document.getElementById("message").click();
                </script>
            <?php }
        } ?>
</body>
</html>