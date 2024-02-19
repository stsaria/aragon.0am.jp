<?php
    ob_start();
    $language = explode("/", $_SERVER['HTTP_REFERER'])[count(explode("/", $_SERVER['HTTP_REFERER']))-2];
    if (!isset($_GET['title']) || !($language == "ja" || $language == "en")){
        if (isset($_SERVER['HTTP_REFERER'])){
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }
        exit;
    }
    if ($language == "ja"){date_default_timezone_set('America/Los_Angeles');}
    else{date_default_timezone_set('America/Los_Angeles');}

    $filename = "";
    $thread = "";
    while (!file_exists($filename)){
        $thread = uniqid(rand(), true);
        $filename = "../data/chat-".$thread.".csv";
        if (!file_exists($filename)){
            touch($filename);
            break;
        }
        continue;
    }

    $title = htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8');
    $fp = fopen("../log/chat-".$language.".log", 'ab');
    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp, "add,'".date("Y/m/d H:i")."','".$_SERVER['REMOTE_ADDR']."','".str_replace("'", "\"", $title)."'\n") === FALSE){
                echo '<script>alert("File write failed.");</script>';
            }
            flock($fp, LOCK_UN);
        }else{
            echo '<script>alert("File lock failed.");</script>';
            exit;
        }
    } else {exit;}

    $fp = fopen("../data/chatlist-".$language.".csv", 'ab');
        if ($fp){
            if (flock($fp, LOCK_EX)){
                if (fputcsv($fp, [$filename, str_replace("'", "\"", $title), date("Y/m/d H:i"), hash("fnv1a32", $_SERVER['REMOTE_ADDR'])]) === FALSE){
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
    header("Location: ../".$language."/chat?thread=".$thread);
    exit;
?>