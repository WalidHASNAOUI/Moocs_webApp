<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
       header("Location: ./signUp_signIn.php");
    else {
        try{
            // make connection with db
            $con = new PDO("mysql:host=localhost;dbname=gidb","root","");

            // extract the currentPath
            $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
            $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);

            if($sta->fetch(PDO::FETCH_ASSOC)["currentPath"] != "../Moocs")
            {
                // modify the last path (lastpath = currnentPath of the user)
                $sta = $con->prepare("update users set lastPath = currentPath where userMail = :usrMail");
                $sta->execute(["usrMail"=> $_SESSION["loginMail"]]);

                // change the current path of this user
                $sta = $con->prepare("update users set currentPath = :defaultPath where userMail = :usrMail");
                $sta->execute(["defaultPath"=>"../Moocs","usrMail"=>$_SESSION["loginMail"]]);
            }
        }catch(PDOException $e) {
            die("Error !! ::> in defaultPaht.php");
        }

        // close connection 
        $con = null;

        // redirection to <index.php>
        header("Location: ../index.php");
    }
?>