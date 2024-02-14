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
        <?php include "../header.html" ?>
        <main>
            <h2>チャット</h2>
            <a href="#post">投稿欄に移動</a>
            <?php
                ob_start();
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    writeData();
                }
                echo readData();
                function readData(){
                    $chat_file = '../data/chat-ja.csv';
                    $rows = [];
                    $data = '';
                    $fp = fopen($chat_file, 'rb');
                    if ($fp){
                        if (flock($fp, LOCK_SH)){
                            while ($row = fgetcsv($fp)) {
                                $rows[] = $row;
                            }
                            if (count($rows) >= 30){
                                $rows = array_slice($rows, -30);
                            }
                            if (!empty($rows)): ?>
                                <ul>
                            <?php foreach ($rows as $row): ?>
                                <li><?=$row[2]?>|ID:<?=$row[3]?></br>
                                <?=$row[0]?>|<?=$row[1]?></li>
                            <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                
                            <?php endif; ?>
                            <?php
                            flock($fp, LOCK_UN);
                        }else{
                            echo '<script>alert("File lock failed.");</script>';
                        }
                    }
                    fclose($fp);
                    return $data;
                }
                function writeData(){
                    $chat_file = '../data/chat-ja.csv';
                    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
                    if ($name === "名が無き者"){$name = "名が無き者@※実際はあります";}
                    else if ($name === ""){$name = "名が無き者";}
                    $contents = htmlspecialchars($_POST['contents'], ENT_QUOTES, 'UTF-8');
                    if ($contents === ""){return;}
                    $fp = fopen($chat_file, 'ab');
                    if ($fp){
                        if (flock($fp, LOCK_EX)){
                            if (fputcsv($fp, [$name, $contents, date("Y/m/d H:i"), hash("fnv1a32", str_replace('.', '', $_SERVER['REMOTE_ADDR']))]) === FALSE){
                                echo '<script>alert("File write failed.");</script>';
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
            }
            ?>
            <hr>
            <h3 id="post">投稿</h3>
            <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <span>名前 : </span><input type="text" name="name"></br></br>
            <span>内容</br></span>
            <textarea name="contents" rows="8" cols="40"></textarea></br>
            <input type="submit" name="sbm_btn" value="投稿">
            </form>
            <a href="../data/chat-ja.csv">もしすべてのチャットを見たいなら</a>
        </main>
        <?php include "../footer.html" ?>
    </body>
</html>