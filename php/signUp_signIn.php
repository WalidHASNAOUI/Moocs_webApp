<?php
session_start();

if (isset($_SESSION["login"]))
    header("Location: ../index.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <link rel="stylesheet" href="../css/signUp_signIn.css">
</head>

<body>
    <div class="Main">
        <!-- <img src="../images/background.jpg" alt="" class="side"> -->
        <div class="login-wrap">
            <div class="login-html">
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
                <div class="login-form">
                    <div class="sign-in-htm">
                        <form action="../php/signUp_signIn.php" method="post">
                            <?php
                            if (!isset($_POST["usrMail"], $_POST["usrPsw"]))
                            {
                                if(!isset($_COOKIE["usrMail"]))
                                    echo '
                                        <div class="group">
                                            <label for="usrMail" class="label">E-mail</label>
                                            <input id="user" type="email" class="input" name="usrMail" placeholder="Enter your E-mail" required="required">
                                        </div>
                                        <div class="group">
                                            <label for="pass" class="label">Password</label>
                                            <input id="pass" type="password" class="input" name="usrPsw" placeholder="Enter your password" required="required">
                                        </div>
                                    ';
                                else {
                                    try{
                                        // gette the password of this user
                                        $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "c++javajs");
                                        $sta = $con->prepare("select usrPassword from users where userMail = :usrMail");
                                        $sta->execute(["usrMail"=>$_COOKIE["usrMail"]]);

                                    // set all info of this user by default inside this form <because of remember me is checked>
                                    echo '
                                        <div class="group">
                                            <label for="usrMail" class="label">E-mail</label>
                                            <input id="user" type="email" class="input" name="usrMail" value="'.$_COOKIE["usrMail"].'" placeholder="Enter your E-mail" required="required">
                                        </div>
                                        <div class="group">
                                            <label for="pass" class="label">Password</label>
                                            <input id="pass" type="password" class="input" name="usrPsw" value="'.$sta->fetch(PDO::FETCH_ASSOC)["usrPassword"].'" placeholder="Enter your password" required="required">
                                        </div>
                                    ';
                                    $con = null;
                                    }catch(PDOException $e) {
                                        die("Error(0) !!");
                                    }
                                }
                            }
                            else {
                                //make connection with db
                                $queryAll = "select usrName from users where userMail=:usrMail and usrPassword=:usrPsw";
                                $queryMail = "select count(*) as response from users where userMail=:usrMail";
                                try {
                                    $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "c++javajs");
                                    $sta = $con->prepare($queryAll);
                                    $sta->execute(["usrMail"=>$_POST["usrMail"] ,"usrPsw"=>$_POST["usrPsw"]]);
                                    $data = $sta->fetch(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("error(1) !!");
                                }

                                if (!$data) {
                                    //check if email is correct
                                    try {
                                        $sta = $con->prepare($queryMail);
                                        $sta->execute(["usrMail" => $_POST["usrMail"]]);
                                        $data = $sta->fetch(PDO::FETCH_ASSOC);
                                        // var_dump($data);
                                    } catch (PDOException $e) {
                                        die("Erron when chenking if email exists !!");
                                    }
                                    if ($data["response"] == "0") { // email doesn't existe
                                        echo '
                                            <div class="group">
                                                <label for="usrMail" class="label">E-mail</label>
                                                <input id="user" type="email" class="input" name="usrMail" placeholder="Enter your E-mail" value="' . $_POST["usrMail"] . '" required="required">
                                            </div>
                                            <div class="group">
                                                <label for="pass" class="label">Password</label>
                                                <input id="pass" type="password" class="input" name="usrPsw" placeholder="Enter your password" value="' . $_POST["usrPsw"] . '" required="required">
                                                <div class="WrongCoordiante">E-mail doesn"t exist</div>
                                            </div>
                                        ';
                                    } else {//Now the email is correct, but psw is incorrect <c'est sure !!!>
                                        echo '
                                            <div class="group">
                                                <label for="usrMail" class="label">E-mail</label>
                                                <input id="user" type="email" class="input" name="usrMail" placeholder="Enter your E-mail" value="' . $_POST["usrMail"] . '" required="required">
                                            </div>
                                            <div class="group">
                                                <label for="pass" class="label">Password</label>
                                                <input id="pass" type="password" class="input" name="usrPsw" placeholder="Enter your password" required="required">
                                                <div class="WrongCoordiante">Password doesn"t match</div>
                                            </div>
                                        ';
                                    }
                                } else {
                                    //create new session 
                                    $_SESSION["login"] = $data["usrName"];
                                    $_SESSION["loginMail"] = $_POST["usrMail"];

                                    // check if the user press to remember me button
                                    if(isset($_POST["rememberMe"]))
                                        setcookie("usrMail",$_POST["usrMail"],time()+120); //enable for <365 days> 31536000

                                    //redirect user to <index.php>
                                    header("Location: ../index.php");
                                }
                            }

                            ?>
                            <div class="group">
                                <input name="rememberMe" id="check" type="checkbox" class="check" checked>
                                <label for="check"><span class="icon"></span> Remember me</label>
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Sign In">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <a href="#forgot">Forgot Password?</a>
                            </div>
                        </form>
                    </div>

                    <!-- SIGN UP PAGE -->
                    <div class="sign-up-htm">
                        <form action="../php/signUp_signIn.php" method="post" class="hideSignIn">
                            <?php
                                if (!isset($_POST["username"], $_POST["email"], $_POST["password"], $_POST["repassword"])) {
                                    echo '
                                            <div class="group">
                                                <label for="user" class="label">Username</label>
                                                <input id="user" type="text" class="input" name="username" required>
                                            </div>
                                            <div class="group">
                                                <label for="pass" class="label">E-mail Address</label>
                                                <input id="pass" type="text" class="input" name="email" required>
                                            </div>
                                            <div class="group">
                                                <label for="pass" class="label">Password</label>
                                                <input id="pass1" type="password" class="input" name="password" required>
                                            </div>
                                            <div class="group">
                                                <label for="pass" class="label">Repeat Password</label>
                                                <input id="pass2" type="password" class="input" name="repassword" required>
                                            </div>
                                        ';
                                } else {
                                    if ($_POST["password"] !== $_POST["repassword"]) {
                                        echo '
                                                <div class="group">
                                                    <label for="user" class="label">Username</label>
                                                    <input id="user" type="text" class="input" name="username" value="' . $_POST["username"] . '" required>
                                                </div>
                                                <div class="group">
                                                    <label for="pass" class="label">E-mail Address</label>
                                                    <input id="pass" type="text" class="input" name="email" value="' . $_POST["email"] . '" required>
                                                </div>
                                                <div class="group">
                                                    <label for="pass" class="label">Password</label>
                                                    <input id="pass" type="password" class="input" name="password" value="' . $_POST["password"] . '" required>
                                                </div>
                                                <div class="group">
                                                    <label for="pass" class="label">Repeat Password</label>
                                                    <input id="pass" type="password" class="input" name="repassword" required>
                                                    <div class="WrongCoordiante">Password doesn"t match</div>
                                                </div>
                                        ';
                                    } else {
                                        //make connection with db
                                        $query = "insert into users(userMail, usrPassword, usrName) values (:a, :b, :c)";
                                        try {
                                            $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "c++javajs");
                                            $sta = $con->prepare($query);
                                            $sta->execute(['a' => $_POST["email"], 'b' => $_POST["password"], 'c' => $_POST["username"]]);
                                        } catch (PDOException $e) {
                                            die("E-mail Already exist!!");
                                        }
                                        header("Location: ../index.php");
                                    } 
                                }
                            ?>
                            <div class="group">
                                <input type="submit" class="button" value="Sign Up">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <label for="tab-1">Already Member?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/scriptSign.js"></script>

</body>

</html>