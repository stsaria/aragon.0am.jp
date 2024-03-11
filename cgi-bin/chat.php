<?php
    ob_start();
    $language = explode("/", $_SERVER['HTTP_REFERER'])[count(explode("/", $_SERVER['HTTP_REFERER']))-2];
    if ($language == "ja"){date_default_timezone_set('America/Los_Angeles');}
    else{date_default_timezone_set('America/Los_Angeles');}
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
                    <ol>
                <?php foreach ($rows as $row): ?>
                    <?php $num_response++ ?>
                    <strong><li id="<?=$num_response?>"><?=$row[0]?></strong> ID:<?=$row[3]?> <?=$row[2]?></br>
                    <?=$row[1]?></li>
                <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    
                <?php endif; ?>
                <?php
                flock($fp, LOCK_UN);
            }else{
                echo '<script>alert("File lock failed.");</script>';
                exit;
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
        $contents = nl2br(htmlspecialchars($_GET['contents'], ENT_QUOTES, 'UTF-8'));
        $contents = str_replace("<br>", "", $contents);
        if ($contents == ""){return;}
        else if (count(explode("/",$contents)) > 5){return;}
        if (strlen($contents) >= 4){
            if ($contents[0].$contents[1].$contents[2] == "///" && is_numeric($contents[3])){
                $resnum = "";
                $a_contents = "<a href=#reSnuM>///".$contents[3];
                $resnum = $contents[3];
                if (is_numeric($contents[4])){$a_contents = $a_contents.$contents[4]; $resnum = $resnum.$contents[4];}
                if (is_numeric($contents[5])){$a_contents = $a_contents.$contents[5]; $resnum = $resnum.$contents[5];}
                $a_contents = $a_contents."</a>".str_replace("///".$resnum, '', $contents);
                $contents = str_replace("reSnuM", $resnum, $a_contents);
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
                exit;
            }
        } else {exit;}
        if($num_response >= 200){
            echo '<script>alert("Response > 200");</script>';
            exit;
        }

        $fp = fopen("../log/gchat.log", 'ab');
        if ($fp){
            if (flock($fp, LOCK_EX)){
                if (fwrite($fp, "post,'".date("Y/m/d H:i")."','".$_SERVER['REMOTE_ADDR']."','".$_GET['thread']."','".
                str_replace("'", "\"", $name)."','".str_replace("'", "\"", $contents)."'\n") === FALSE){
                    echo '<script>alert("File write failed.");</script>';
                    exit;
                }
                flock($fp, LOCK_UN);
            }else{
                echo '<script>alert("File lock failed.");</script>';
                exit;
            }
        } else {exit;}
        
        $fp = fopen($chat_file, 'ab');
        if ($fp){
            if (flock($fp, LOCK_EX)){
                if (fputcsv($fp, [$name, $contents, date("Y/m/d H:i"), hash("fnv1a32", $_SERVER['REMOTE_ADDR'])]) === FALSE){
                    echo '<script>alert("File write failed.");</script>';
                    exit;
                }
                flock($fp, LOCK_UN);
            }else{
                echo '<script>alert("File lock failed.");</script>';
                exit;
            }
        } else {exit;}
        fclose($fp);
    }
    if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['thread']) && isset($_GET['name'])){
        header("location: ?thread=".$_GET['thread'].str_replace(".csv","",str_replace("chat-", "", explode("/",$row[0])[2]))."#post");
        exit;
    }
?>