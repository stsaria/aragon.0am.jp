<?php
    if(!isset($_GET["thread"])){
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
    </head>
    <body>
        <?php include "../header.html" ?>
        <main>
            <h2>チャット</h2>
            <a href="#post">投稿欄に移動</a>
            <?php
                $chat_file = "../data/chat-".$_GET['thread'].".csv";
                include "../cgi-bin/gchat.php";
            ?>
            <hr>
            <h2 id="post">投稿</h2>
            <form method="GET" action="">
            <span>スレッド : </span><input type="text" name="thread" value="<?=$_GET['thread']?>" readonly></br>
            <span>名前 : </span><input type="text" name="name"></br></br>
            <span>内容("//1 "のように特定のレスポンスを指定することができます)</br></span>
            <textarea name="contents" rows="8" cols="40"></textarea></br>
            <input type="submit" name="sbm_btn" value="投稿">
            </form>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>