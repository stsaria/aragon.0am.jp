<?php
    ob_start();
    if (!isset($_GET['title'])){
        if (isset($_SERVER['HTTP_REFERER'])){
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }
        exit;
    }

    $filename = "";
    while (!file_exists($filename)){
        $filename = "../data/chat-".uniqid(rand(), true).".csv";
        if (!file_exists($filename)){
            touch($filename);
            break;
        }
        continue;
    }

    $fp = fopen("../log/gchat.log", 'ab');
    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp, "add,'".date("Y/m/d H:i")."','".$_SERVER['REMOTE_ADDR']."','".str_replace("'", "\"", $_GET['title'])."'\n") === FALSE){
                echo '<script>alert("File write failed.");</script>';
            }
            flock($fp, LOCK_UN);
        }else{
            echo '<script>alert("File lock failed.");</script>';
            exit;
        }
    } else {exit;}

    $fp = fopen("../data/chatlist.csv", 'ab');
        if ($fp){
            if (flock($fp, LOCK_EX)){
                if (fputcsv($fp, [$filename, $_GET['title'], date("Y/m/d H:i"), hash("fnv1a32", $_SERVER['REMOTE_ADDR'])]) === FALSE){
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
    if (isset($_SERVER['HTTP_REFERER'])){
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
?>