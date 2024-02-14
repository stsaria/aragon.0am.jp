<?php
    ob_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        writeData();
    }
    print(readData());
    function readData(){
        global $chat_file;
        $rows = [];
        $data = '';
        $fp = fopen($chat_file, 'rb');
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while ($row = fgetcsv($fp)) {
                    $rows[] = $row;
                }
                if ($rows){
                    if (count($rows) >= 30){$rows = array_slice($rows, -30);}
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
        global $chat_file;
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        if ($name === "Anonymous"){$name = "Anonymous@Fake";}
        else if ($name === ""){$name = "Anonymous";}
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
    exit;
}
?>