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
                <form method="POST" action="#">
                    <label for="usrMail">E-mail</label>
                    <input type="email" placeholder="Enter your E-mail" name="usrMail" id="usrMail" class="txt">
                    <label for="">Password</label>
                    <input type="password" placeholder="Enter your password" name="usrPsw" id="usrPsw" class="txt">
                    <input type="submit" value="Sign In">
                </form>
            </div>
        </div>
    </main>
</body>
</html>