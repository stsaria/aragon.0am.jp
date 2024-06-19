<?php
    date_default_timezone_set('Asia/Tokyo');
    if(!isset($_GET["thread"])){
        header("Location: chatlist");
        exit;
    } else if (!file_exists("../data/chat-".$_GET['thread'].".csv")){
        header("Location: chatlist");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" href="../img/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../styles/style.css" rel="stylesheet" />
        <title>Hispania/Chat</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    </head>
    <body>
    <div class="bg">
        <?php include "../header.html" ?>
        <main class="container">
            <div id="liveAlertPlaceholder"></div>
            <h2>Chat</h2>
            <a href="#post">Go to the Submission section</a>/<a href="#1">1</a>/<a href="#50">50</a>/<a href="#100">100</a>/<a href="#150">150</a>/<a href="#200">200</a>
            <?php
                $chat_file = "../data/chat-".$_GET['thread'].".csv";
                include "../cgi-bin/chat.php";
            ?>
            <hr>
            <h2 id="post">Post</h2>
            <form method="GET", name="post-response">
            <span>Thread : </span><input class="form-control" type="text" name="thread" value="<?=$_GET['thread']?>" readonly></br>
            <span>Name : </span><input class="form-control" type="text" name="name" id="name" maxlength="10"></br></br>
            <span>Contents (up to 5 lines)</br>
            You can specify a specific response, such as "///1</br>
            (must be written at the beginning of the statement)</span>
            <textarea class="form-control" name="contents" rows="8" cols="40" value="" maxlength="70"></textarea></br>
            <input class="form-control btn btn-primary" type="submit" id="sbm_btn" oneclick="saveName();" value="Post">
            </form>
            <script>
                var name = localStorage.getItem("name");
                if (name != "null"){document.getElementById("name").value = name;}
                $(document).ready(function(){
                    $('#sbm_btn').click(function(){
                        var name = $('#name').val();
                        localStorage.setItem("name", name);
                    });
                });
            </script>
            <a href="../data/chat-<?=$_GET["thread"]?>.csv">Download Chat(csv)</a>
            <hr>
            <h3>Delete this thread</h3>
            <p>*Once a thread is deleted, it can never be restored.
            Basically, you can only delete a thread if the IP you used to create it and your current IP are the same.
            If you can't prove that you can delete the thread, please send it to "Discord" under "Etc/Contact Us".</p>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
        </main>
        <?php include "../footer.html" ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete this thread</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this thread?<br><strong>This operation cannot be undone!</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="../cgi-bin/chatrm.php?thread=<?=$_GET['thread']?>"><button id="threadDeletionConfirm" type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>