<?php
    ob_start();
    if (!isset($_GET['thread'])){
        if (isset($_SERVER['HTTP_REFERER'])){
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }
        exit;
    }

    $rows = [];
    $filename = "";
    $num_rm_thread=0;
    $fp = fopen("../data/chatlist.csv", 'rb');
    if ($fp){
        if (flock($fp, LOCK_SH)){
            while ($row = fgetcsv($fp)) {
                $rows[] = $row;
            }
            if ($rows){
                if (count($rows) >= 30){$rows = array_slice($rows, -30);}
            }
            foreach ($rows as $row){
                if ($row[0] == "../data/chat-".$_GET['thread'].".csv" && $row[3] == hash("fnv1a32", ip2long($_SERVER['REMOTE_ADDR']))){
                    $filename = $row[0];
                    break;
                }
                $num_rm_thread++;
            }
            if($filename == "") {
                if (isset($_SERVER['HTTP_REFERER'])){
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }
                exit;
            }
            flock($fp, LOCK_UN);
        } else {
            echo '<script>alert("File lock failed.");</script>';
        }
    } else {exit;}
    fclose($fp);

    $fp = fopen("../log/gchat.log", 'ab');
    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp, "remove,'".date("Y/m/d H:i")."','".long2ip(ip2long($_SERVER['REMOTE_ADDR']))."','".$_GET['thread']."'\n") === FALSE){
                echo '<script>alert("File write failed.");</script>';
                exit;
            }
            flock($fp, LOCK_UN);
        }else{
            echo '<script>alert("File lock failed.");</script>';
            exit;
        }
    } else {exit;}

    $fp = fopen("../data/chatlist.csv", 'wb');
    if ($fp){
        if (flock($fp, LOCK_EX)){
            unlink($filename);
            unset($rows[$num_rm_thread]);
            if ($rows == []){fwrite($fp, null);}
            else{foreach ($rows as $row){fputcsv($fp, $row);}}
            flock($fp, LOCK_UN);
        }else{
            echo '<script>alert("File lock failed.");</script>';
            exit;
        }
    } else {exit;}
    fclose($fp);
    if (isset($_SERVER['HTTP_REFERER'])){
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
?>