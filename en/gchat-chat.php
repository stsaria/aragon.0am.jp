<?php
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
        <script src="http://code.jquery.com/jquery-1.8.2.min.js" defer></script>
    </head>
    <body>
        <?php include "../header.html" ?>
        <main>
            <h2>Chat</h2>
            <a href="#post">Go to the Post section</a>
            <?php
                $chat_file = "../data/chat-".$_GET['thread'].".csv";
                include "../cgi-bin/gchat.php";
            ?>
            <hr>
            <h2 id="post">Post</h2>
            <form method="GET" action="">
            <span>Thread : </span><input type="text" name="thread" value="<?=$_GET['thread']?>" readonly></br>
            <span>Name : </span><input type="text" name="name" id="name"></br></br>
            <span>Contents(You can specify a specific response, such as "///1")</br></span>
            <textarea name="contents" rows="8" cols="40"></textarea></br>
            <input type="submit" id="sbm_btn" value="Post">
            </form>
            <script>
                var name = sessionStorage.getItem("name");
                if (name != "null"){document.getElementById("name").value = name;}
                $(document).ready(function(){
                    $('#sbm_btn').click(function(){
                        var name = $('#name').val();
                        console.log(name);
                        sessionStorage.setItem("name", name);
                    });
                });
            </script>
            <hr>
            <h3>Delete this thread</h3>
            <p>*Once a thread is deleted, it can never be restored.</br>
            Threads can only be deleted if the IP at the time of creation and the current IP are the same.</p>
            <a href="../cgi-bin/gchatrm.php?thread=<?=$_GET['thread']?>"><button>Delete</button></a>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>