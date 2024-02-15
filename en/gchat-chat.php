<?php
    date_default_timezone_set('Asia/Tokyo');
    if(!isset($_GET["thread"])){
        header("Location: gchat");
        exit;
    } else if (!file_exists("../data/chat-".$_GET['thread'].".csv")){
        header("Location: gchat");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" href="../img/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../styles/style.css" rel="stylesheet" />
        <title>Saadi/Chat</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <?php include "../header.html" ?>
        <main>
            <h2>Chat</h2>
            <a href="#post">Go to the Post section</a>/<a href="#1">1</a>/<a href="#50">50</a>/<a href="#100">100</a>/<a href="#150">150</a>/<a href="#200">200</a>
            <?php
                $chat_file = "../data/chat-".$_GET['thread'].".csv";
                include "../cgi-bin/gchat.php";
            ?>
            <hr>
            <h2 id="post">Post</h2>
            <form method="GET" action="">
            <span>Thread : </span><input type="text" name="thread" value="<?=$_GET['thread']?>" readonly></br>
            <span>Name : </span><input type="text" name="name" id="name" maxlength="10"></br></br>
            <span>Contents(Up to 5 lines)</br>
            You can specify a specific response, such as "///1".</br>
            (Must be written at the beginning of the sentence)</br></span>
            <textarea name="contents" rows="8" cols="40" maxlength="70"></textarea></br>
            <input type="submit" id="sbm_btn" value="Post">
            </form>
            <script>
                var name = localStorage.getItem("name");
                if (name != "null"){document.getElementById("name").value = name;}
                $(document).ready(function(){
                    $('#sbm_btn').click(function(){
                        var name = $('#name').val();
                        localStorage.setItem("name", name);
                    });
                });
            </script>
            <a href="../data/chat-<?=$_GET["thread"]?>.csv">Chat Download(csv)</a>
            <hr>
            <h3>Delete this thread</h3>
            <p>*Once a thread is deleted, it can never be restored.</br>
            Threads can only be deleted if the IP at the time of creation and the current IP are the same.</br>
            Even if you can't prove it, we may be able to remove it, depending on the content and circumstances, so</br>
            please send it to the Etc/Contact "Discord".</p>
            <a href="../cgi-bin/gchatrm.php?thread=<?=$_GET['thread']?>"><button>Delete</button></a>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>