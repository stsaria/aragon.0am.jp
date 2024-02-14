<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" href="../img/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../styles/style.css" rel="stylesheet" />
        <title>Saadi/Chat</title>
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
            <span>Name : </span><input type="text" name="name"></br></br>
            <span>Contents</br></span>
            <textarea name="contents" rows="8" cols="40"></textarea></br>
            <input type="submit" name="sbm_btn" value="Post">
            </form>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>