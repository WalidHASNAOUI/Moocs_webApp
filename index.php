<?php
session_start();

if (!isset($_SESSION["loginMail"]))
    header("Location: ./php/signUp_signIn.php");
else {
    //make connection with db / extract the last current path of this user <session> 
    try {
        $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "");
        $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
        $sta->execute(["usrMail" => $_SESSION["loginMail"]]);
        $currentPath = $sta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
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
                <img src="./images/online-learning.png" alt="" id="logo">
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
                <i id="forward" class="fas fa-chevron-circle-right" onclick="forwardPath()"></i>
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
                //inlude function.php file <configSize>
                include './php/functions.php';

                //get all files of this path 
                $defDir = array_slice(scandir(substr($currentPath["currentPath"], 1)), 2);  //because path is ::> ../Moocs/test :> 
                // so we need to remove the first <.> because we're in index.php

                foreach ($defDir as $e) {
                    echo '
                            <tr onclick="selectDir(this)">
                                <td><i class="fas fa-folder"></i></td>
                                <td>' . $e . '</td>
                                <td>' . filetype(substr($currentPath["currentPath"], 1) . "/" . $e) . '</td>
                                <td>' . configSize(filesize(substr($currentPath["currentPath"], 1) . "/" . $e)) . '</td>
                                <td>' . date("Y-m-d H:i:s a", filemtime(substr($currentPath["currentPath"], 1) . "/" . $e)) . '</td>
                            </tr>
                        ';
                }
                ?>
            </tbody>
        </table>
    </main>

    <section>
        <video width="100%" height="98%" poster="./DB/images/mooc.jpeg" controls>
            <source src="./DB/Video/moocsDef.mp4" type="video/mp4">
        </video>
    </section>

    <aside>
        <!-- start sw-rss-feed code -->
        <script type="text/javascript">
            rssfeed_url = new Array();
            rssfeed_url[0] = "https://rss.app/feeds/scq7aZVJHqqtiJ1e.xml";
            rssfeed_frame_width = "412";
            rssfeed_frame_height = "530";
            rssfeed_scroll = "on";
            rssfeed_scroll_step = "2";
            rssfeed_scroll_bar = "off";
            rssfeed_target = "_blank";
            rssfeed_font_size = "13";
            rssfeed_font_face = "";
            rssfeed_border = "on";
            rssfeed_css_url = "";
            rssfeed_title = "on";
            rssfeed_title_name = "";
            rssfeed_title_bgcolor = "#377771";
            rssfeed_title_color = "#fff";
            rssfeed_title_bgimage = "";
            rssfeed_footer = "off";
            rssfeed_footer_name = "rss feed";
            rssfeed_footer_bgcolor = "#fff";
            rssfeed_footer_color = "#333";
            rssfeed_footer_bgimage = "";
            rssfeed_item_title_length = "50";
            rssfeed_item_title_color = "#000";
            rssfeed_item_bgcolor = "#edf2f4";
            rssfeed_item_bgimage = "";
            rssfeed_item_border_bottom = "on";
            rssfeed_item_source_icon = "off";
            rssfeed_item_date = "on";
            rssfeed_item_description = "on";
            rssfeed_item_description_length = "120";
            rssfeed_item_description_color = "#073b4c";
            rssfeed_item_description_link_color = "#99cc00";
            rssfeed_item_description_tag = "off";
            rssfeed_no_items = "0";
            rssfeed_cache = "9c2659347d6b13983ba16d7f6a60ef0d";
        </script>
        <script type="text/javascript" src="//feed.surfing-waves.com/js/rss-feed.js"></script>
    </aside>
    <script src="./js/script.js"></script>
</body>

</html>