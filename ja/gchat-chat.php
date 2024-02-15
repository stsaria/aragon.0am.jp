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
<html lang="ja">
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
            <h2>チャット</h2>
            <a href="#post">投稿欄に移動</a>/<a href="#1">1</a>/<a href="#50">50</a>/<a href="#100">100</a>/<a href="#150">150</a>/<a href="#200">200</a>
            <?php
                $chat_file = "../data/chat-".$_GET['thread'].".csv";
                include "../cgi-bin/gchat.php";
            ?>
            <hr>
            <h2 id="post">投稿</h2>
            <form method="GET", name="post-response">
            <span>スレッド : </span><input type="text" name="thread" value="<?=$_GET['thread']?>" readonly></br>
            <span>名前 : </span><input type="text" name="name", id="name"></br></br>
            <span>内容("///1"のように特定のレスポンスを指定することができます)</br></span>
            <textarea name="contents" rows="8" cols="40", value=""></textarea></br>
            <input type="submit" id="sbm_btn" oneclick="saveName();" value="投稿">
            </form>
            <script>
                var name = sessionStorage.getItem("name");
                if (name != "null"){document.getElementById("name").value = name;}
                $(document).ready(function(){
                    $('#sbm_btn').click(function(){
                        var name = $('#name').val();
                        sessionStorage.setItem("name", name);
                    });
                });
            </script>
            <hr>
            <h3>このスレッドを削除する</h3>
            <p>※スレッドを削除すると、二度と元に戻すことはできません。</br>
            スレッドを削除できるのは作成した時のIPと現在のIPが同じ場合のみです。</p>
            <a href="../cgi-bin/gchatrm.php?thread=<?=$_GET['thread']?>"><button>削除</button></a>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>