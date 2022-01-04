<?php 
    session_start();
    // session_unset();
    // session_destroy();
    if(!isset($_SESSION["loginMail"])){
        header("Location: ./php/signUp_signIn.php");
    }
    else {
        //make connection with db / extract the last current path of this user <session> 
        try{
            $con = new PDO("mysql:host=localhost;dbname=gidb","root","");
            $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
            $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);
            $currentPath = $sta->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            die("Error in <index.php> when you try to extract path!!");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lister Moocs</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+SC:wght@500&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a1987b05f9.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div id="topHeader">
            <div id="left">
                <p id="pageTitle">LISTER</p>
            </div>
            <div class="searchContainer">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" name="reperoty" class=" serachBox" placeholder="Search here">
            </div>
            <div id="right">
                <p id="userInfo"><span>Signed in as</span> <span><?php echo $_SESSION["login"] ?></span></p>
                <img src="./images/profile.png">
                <a href="./php/logOut.php">
                    <p>log Out</p>
                </a>
            </div>
        </div>
        <div id="southHeader">
            <div>
                <i id="backward" class="fas fa-chevron-circle-left" onclick="backwardPath()"></i>
                <i id="forward" class="fas fa-chevron-circle-right" onclick="backwardPath()"></i>
            </div>
            <p id="usrPath"><?php echo $currentPath["currentPath"]; ?></p>
        </div>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th id="file">File</th>
                    <th id="name">name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //get all files of this path 
                $defDir = scandir(substr($currentPath["currentPath"],1));  //because path is ::> ../Moocs/test :> 
                                                                            // so we need to remove the first <.> because we're in index.php

                foreach ($defDir as $e) {
                    echo '
                            <tr onclick="selectDir(this)">
                                <td><i class="fas fa-folder"></i></td>
                                <td>' . $e . '</td>
                                <td>' . filetype(substr($currentPath["currentPath"],1). "/" . $e) . '</td>
                                <td>' . filesize(substr($currentPath["currentPath"],1). "/" . $e) . '</td>
                                <td>' . date("Y-m-d H:i:s a", filemtime(substr($currentPath["currentPath"],1). "/" . $e)) . '</td>
                            </tr>
                        ';
                }
                ?>
            </tbody>
        </table>
    </main>

    <section>
    </section>

    <aside>
        <?php
        $array = scandir("./Moocs");
        foreach ($array as $e) {
            echo '<p>' . $e . ' --> ' . date('Y-m-d G:i:s a', filemtime("./Moocs/" . $e)) . ' --> ' . filesize("./Moocs/$e") . 'B  -->' . filetype("./Moocs/$e") . '</p></br>';
        }
        ?>
    </aside>
    <script src="./js/script.js"></script>
</body>

</html>