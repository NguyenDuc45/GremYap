<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message List</title>
</head>
<body onload = "list();">
    <script type="text/javascript">
        function list(){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function(){
            document.getElementById("list").innerHTML = this.responseText;
            }
            xhttp.open("GET", "messages.php");
            xhttp.send();
        }

        setInterval(function(){
            list();
        }, 1000);
    </script>
    <div id="list">

    </div>
</body>
</html>