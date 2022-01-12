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
    <title>MOOCS | SIGN IN</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <main>
        <div id="left_div">
            <div id="logo"></div>
        </div>
        <div id="right_div">
            <div id="btnCon">
                <a href="signIn.php">
                    <div id="login">
                        <p>Log In</p>
                    </div>
                </a>
                <a href="SignUp.php">
                    <div id="signup">
                        <p>Sign Up</p>
                    </div>
                </a>
            </div>
            <div id="formLogin">
                <div>
                    <h1>Sign Up</h1>
                </div>
                <form method="POST" action="../php/SignUp.php">
                    <?php
                        if (!isset($_POST["usrMail"], $_POST["usrPsw"], $_POST["usrName"], $_POST["usrPswd2"])){
                            echo '
                                            <label for="usrname">Username</label>
                                            <div class="input-container">
                                                <i class="fa fa-user icon"></i>
                                                <input type="text" placeholder="Enter your Username" name="usrName" id="usrName" class="txt" required">
                                            </div>
                                            <label for="usrMail">E-mail</label>
                                            <div class="input-container">
                                                <i class="fa fa-envelope icon"></i>
                                                <input type="email" placeholder="Enter your E-mail" name="usrMail" id="usrMail" class="txt" required>
                                            </div>
                                            <label for="">Password</label>
                                            <div class="input-container">
                                                <i class="fa fa-key icon"></i>
                                                <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" class="txt" required>
                                            </div>
                                            <label for="">Repeat Password</label>
                                            <div class="input-container">
                                                <i class="fa fa-key icon"></i>
                                                <input type="password" placeholder="Re-enter your password" name="usrPsw2" id="usrPsw2" class="txt" required>
                                            </div>
                                        ';
                        }
                        else {
                            if ($_POST["usrPsw"] !== $_POST["usrPsw2"]) {
                                echo '
                                                <label for="usrname">Username</label>
                                                <div class="input-container">
                                                    <i class="fa fa-user icon"></i>
                                                    <input type="text" placeholder="Enter your Username" name="usrName" id="usrName" value="' . $_POST["usrName"] . '" class="txt" required">
                                                </div>
                                                <label for="usrMail">E-mail</label>
                                                <div class="input-container">
                                                    <i class="fa fa-envelope icon"></i>
                                                    <input type="email" placeholder="Enter your E-mail" name="usrMail" id="usrMail" value="' . $_POST["usrMail"] . '" class="txt" required>
                                                </div>
                                                <label for="">Password</label>
                                                <div class="input-container">
                                                    <i class="fa fa-key icon"></i>
                                                    <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" value="' . $_POST["usrPsw"] . '" class="txt" required>
                                                </div>
                                                <label for="">Repeat Password</label>
                                                <div class="input-container">
                                                    <i class="fa fa-key icon"></i>
                                                    <input type="password" placeholder="Re-enter your password" name="usrPsw2" id="usrPsw2"  class="txt" required>
                                                    <div class="WrongCoordiante">Password doesn"t match</div>
                                                </div>
                                        ';
                            } else {
                                //make connection with db
                                $query = "insert into users(userMail, usrPassword, usrName) values (:a, :b, :c)";
                                try {
                                    $con = new PDO("mysql:host=localhost;dbname=gidb", "root", "");
                                    $sta = $con->prepare($query);
                                    $sta->execute(['a' => $_POST["usrMail"], 'b' => $_POST["usrPsw"], 'c' => $_POST["usrName"]]);
                                } catch (PDOException $e) {
                                    die("E-mail Already exist!!");
                                }
                                header("Location: ../index.php");
                            }
                        }
                    ?>
                    <input type="submit" value="Sign Up">
                </form>
            </div>
        </div>
    </main>
</body>

</html>