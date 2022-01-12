<?php 
    session_start();

    if(!$_SESSION["loginMail"])
        header("Location: ./signUp_signIn.php");
    else{
        if(!$_GET["vd"])
            header("Location: ../index.php");
        else {
            try{
                // make connection with DB
                $con = new PDO("mysql:host=localhost;dbname=gidb","root","c++javajs");

                // extract the current path 
                $sta = $con->prepare("select currentPath from users where userMail=:usrMail");
                $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);
                $currentPath = $sta->fetch(PDO::FETCH_ASSOC)["currentPath"];
            }catch(PDOException $e){
                die("Error !! ::> in <generateVideo.php>");
            }
            
            // close connection
            $con = null;

            // generate video src
            $src = '<source src="'.substr($currentPath,1).'/'.$_GET["vd"].'" type="video/mp4">';

            // send vdSrc to <client> |js|
            echo $src;
        }
    }
?>