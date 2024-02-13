<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" href="../img/favicon.ico">
        <meta name="viewport" content="width=device-width">
        <link href="../styles/style.css" rel="stylesheet" />
        <title>Saadi/Chat</title>
    </head>
    <body>
        <?php include "header.html" ?>
        <main>
            <h2>チャット</h2>
            <a href="#post">投稿欄に移動</a>
            <?php
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    writeData();
                }
                print(readData());
                function readData(){
                    $chat_file = '../data/chat-ja.txt';
                    $data = '';
                    $fp = fopen($chat_file, 'rb');
                    if ($fp){
                        if (flock($fp, LOCK_SH)){
                            while (!feof($fp)) {
                                $buffer = fgets($fp);
                                $data = $data.$buffer;
                            }
                            flock($fp, LOCK_UN);
                        }else{
                            echo '<script>alert("File lock failed.");</script>';
                        }
                    }
                    fclose($fp);
                    return $data;
                }
                function writeData(){
                    $chat_file = '../data/chat-ja.txt';
                    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
                    $contents = htmlspecialchars($_POST['contents'], ENT_QUOTES, 'UTF-8');
                    $contents = nl2br($contents);
                    $data = "<hr>\r\n<p>".$name."|".$contents."</p>\r\n";
                    $fp = fopen($chat_file, 'ab');
                    if ($fp){
                        if (flock($fp, LOCK_EX)){
                            if (fwrite($fp,  $data) === FALSE){
                                echo '<script>alert("File lock failed.");</script>';
                            }
                            flock($fp, LOCK_UN);
                        }else{
                            echo '<script>alert("File lock failed.");</script>';
                        }
                    }
                    fclose($fp);
                }
            // リロード対策
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                header("location: #post");
                exit;
            }
            ?>
            <hr>
            <h3 id="post">投稿</h3>
            <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <span>名前 : </span><input type="text" name="name"></br></br>
            <span>内容</br></span>
            <textarea name="contents" rows="8" cols="40"></textarea></br>
            <input type="submit" name="sbm_btn1" value="投稿">
            </form>
        </main>
        <?php include "footer.html" ?>
    </body>
</html>