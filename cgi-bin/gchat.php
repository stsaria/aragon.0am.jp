<?php
    ob_start();
    $chat_file = "../data/chat-".$_GET['thread'].".csv";
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['name']) && isset($_GET['thread'])){
        writeData();
    }
    print(readData());
    function readData(){
        global $chat_file;
        $num_response = 0;
        $rows = [];
        $data = '';
        $fp = fopen($chat_file, 'rb');
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while ($row = fgetcsv($fp)) {
                    $rows[] = $row;
                }
                if (!empty($rows)): ?>
                    <ul>
                <?php foreach ($rows as $row): ?>
                    <?php $num_response++ ?>
                    <li id="<?=$num_response?>"><?=$num_response?>|<?=$row[2]?>|ID:<?=$row[3]?></br>
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
        $num_response = 0;
        $name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
        if ($name === "Anonymous/匿名"){$name = "Anonymous/匿名@Fake/偽物";}
        else if ($name === ""){$name = "Anonymous/匿名";}
        $contents = htmlspecialchars($_GET['contents'], ENT_QUOTES, 'UTF-8');
        if ($contents === ""){return;}
        if (strlen($contents) >= 4){
            if ($contents[0].$contents[1].$contents[2] == "///" && is_numeric($contents[3])){
                $contents = "<a href=#".$contents[3].">//".$contents[3]."</a>".str_replace("///".$contents[3], '', $contents);
            }
        }
        $fp = fopen($chat_file, 'rb');
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while ($row = fgetcsv($fp)){
                    $rows[] = $row;
                }
                if (!empty($rows)): ?>
                    <ul>
                <?php foreach ($rows as $row): ?>
                    <?php $num_response++ ?>
                <?php endforeach; ?>
                    
                <?php else: ?>
                    
                <?php endif; ?>
                <?php
                flock($fp, LOCK_UN);
            }else{
                echo '<script>alert("File lock failed.");</script>';
            }
        }
        if($num_response >= 200){
            echo '<script>alert("Response > 200");</script>';
            return;
        }

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
    if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['thread']) && isset($_GET['name'])){
        header("location: ?thread=".$_GET['thread'].str_replace(".csv","",str_replace("chat-", "", explode("/",$row[0])[2]))."#post");
        exit;
    }
?>