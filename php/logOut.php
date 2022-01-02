<?php 
    session_start();
    if(!isset($_SESSION["loginMail"]))
        header("Location: ./loginIn.php");
    else {
        // unset and destroy the session
        session_unset();
        session_destroy();

        // delete enabled cookie 
        setcookie("check",$_POST["usrMail"],time()-7200);

        //redirect user to <login.php>
        header("Location: ./loginIn.php");
    }
?>