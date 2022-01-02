<?php 
    session_start();

    if(isset($_SESSION["login"]))
        header("Location: ../index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOCS | SIGN IN</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <main>
        <div id="left_div">
            <div id="logo"></div>
        </div>
        <div id="right_div">
            <div id="btnCon">
                <div id="login"><p>Log In</p></div>
                <div id="signup"><p>Sign Up</p></div>
            </div>
            <div id="formLogin">
                <div>
                    <h1>Log In</h1>
                </div>
                <form method="POST" action="./loginIn.php">
                    <?php 
                        if(!isset($_POST["usrMail"],$_POST["usrPsw"]))
                            echo '
                                <label for="usrMail">E-mail</label>
                                <input type="text" placeholder="Enter your E-mail" name="usrMail" id="usrMail" class="txt" required="required">
                                <label for="">Password</label>
                                <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" class="txt" required="required">
                            ';
                        else {
                            //make connection with db
                            $queryAll = "select usrName from users where userMail=:usrMail and usrPassword=:usrPsw";
                            $queryMail = "select count(*) as response from users where userMail=:usrMail";
                            try {
                                $con = new PDO("mysql:host=localhost;dbname=gidb","root","c++javajs");
                                $sta = $con->prepare($queryAll);
                                $sta->execute($_POST);
                                $data = $sta->fetch(PDO::FETCH_ASSOC);
                            }catch(PDOException $e){
                                die("error !!");
                            }
                            
                            if(!$data)
                            {
                                //check if email is correct
                                try{
                                    $sta = $con->prepare($queryMail);
                                    $sta->execute(["usrMail"=>$_POST["usrMail"]]);
                                    $data = $sta->fetch(PDO::FETCH_ASSOC);
                                    // var_dump($data);
                                }catch(PDOException $e){
                                    die("Erron when chenking if email exists !!");
                                }
                                if($data["response"] == "0")
                                    echo '
                                        <label for="usrMail">E-mail</label>
                                        <input type="text" placeholder="Enter your E-mail" name="usrMail" id="usrMail" class="txtErr" value="'.$_POST["usrMail"].'" required="required">
                                        <label for="">Password</label>
                                        <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" class="txt" value="'.$_POST["usrPsw"].'" required="required">
                                    ';
                                else{
                                    //Now the email is correct, but psw is incorrect <c'est sure !!!>
                                    echo '
                                        <label for="usrMail">E-mail</label>
                                        <input type="text" placeholder="Enter your E-mail" name="usrMail" id="usrMail" class="txt" value="'.$_POST["usrMail"].'" required="required">
                                        <label for="">Password</label>
                                        <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" class="txtErr" value="'.$_POST["usrPsw"].'" required="required">
                                    ';
                                }    

                            }else {
                                //create new session 
                                $_SESSION["login"] = $data["usrName"];
                                $_SESSION["loginMail"] = $_POST["usrMail"];

                                //create cookie (to destroy session auto after (x : hours) if user forgot her session open)
                                // setcookie("check",$_POST["usrMail"],time()+3600);

                                //redirect user to <index.php>
                                header("Location: ../index.php");
                            }
                        }
                    ?>
                    <input type="submit" value="Sign In">
                </form>
            </div>
        </div>
    </main>
</body>
</html>

