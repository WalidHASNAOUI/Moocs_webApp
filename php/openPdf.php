<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
        header("Location: ./signIn.php");
    else {
        if(!isset($_GET["file"]))
            header("Location: ../index.php");
        else {
            // extract the current path
            try{
                $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "c++javajs");
                $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
                $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);
                $currentPath = $sta->fetch(PDO::FETCH_ASSOC)["currentPath"];

                // close connection
                $con = null;
            }catch(PDOException $e) {
                die("Error !! ::> <openPdf.php>");
            }

            // extract the extention

            // display file <.pdf>
            $originalPath = $currentPath."/".$_GET["file"];
            header('Content-Type: application/'.$_GET["ext"]);
            header('Content-Disposition: attachement; filename="'.pathinfo($originalPath)["basename"].'"');
            header('Content-Length: '.filesize($originalPath));

            readfile($originalPath);
            exit;
        }
    }
?>