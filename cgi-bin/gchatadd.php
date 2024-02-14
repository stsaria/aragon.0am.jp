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

    $fp = fopen("../data/chatlist.csv", 'ab');
        if ($fp){
            if (flock($fp, LOCK_EX)){
                if (fputcsv($fp, [$filename, $_GET['title'], date("Y/m/d H:i")]) === FALSE){
                    echo '<script>alert("File write failed.");</script>';
                }
                flock($fp, LOCK_UN);
            }else{
                echo '<script>alert("File lock failed.");</script>';
            }
        }
    fclose($fp);
    if (isset($_SERVER['HTTP_REFERER'])){
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
?>