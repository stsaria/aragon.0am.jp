<?php
    $rows = [];
    $fp = fopen("../data/chatlist.csv", 'rb');
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
                <?php
                    $link = "gchat-chat?thread=".str_replace(".csv","",str_replace("chat-", "", explode("/",$row[0])[2]));
                ?>
                <li><?=$row[2]?>|<a href="<?=$link?>"><?=$row[1]?></a></li>
            <?php endforeach; ?>
                </ul>
            <?php else: ?>
                
            <?php endif; ?>
            <?php
            flock($fp, LOCK_UN);
        } else {
            echo '<script>alert("File lock failed.");</script>';
        }
    }
    fclose($fp);
?>