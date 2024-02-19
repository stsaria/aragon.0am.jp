<?php
    $rows = [];
    $all = false;
    if (isset($_GET["all"])){if ($_GET["all"] == "true"){$all = true;}}
    $fp = fopen("../data/chatlist-".$language.".csv", 'rb');
    if ($fp){
        if (flock($fp, LOCK_SH)){
            while ($row = fgetcsv($fp)) {
                $rows[] = $row;
            }
            if ($rows && $all == false){if (count($rows) >= 30){$rows = array_slice($rows, -30);}}
            if (!empty($rows)): ?>
                <ul>
            <?php foreach ($rows as $row): ?>
                <?php
                    $link = "chat?thread=".str_replace(".csv","",str_replace("chat-", "", explode("/",$row[0])[2]));
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