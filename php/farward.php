<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
        header("Location: ./loginIn.php");
    else {
        if(!isset($_GET["perm"]))
            header("Location: ../index.php");
        else {
            //inlude function.php file <configSize>
            include './functions.php';

            try{
                // make connection with DB
                $con = new PDO("mysql:host=localhost;dbname=gidb","root","c++javajs");
                $newCurrentPath = "";
                // check if lastPath is not null 
                $sta = $con->prepare("select lastPath from users where userMail = :userMail");
                $sta->execute(["userMail"=>$_SESSION["loginMail"]]);
                if($sta->fetch(PDO::FETCH_ASSOC)["lastPath"])
                {
                    // Updating the currentPath by the lastPath
                    $sta = $con->prepare("update users set currentPath = lastPath where userMail = :userMail");
                    $sta->execute(["userMail" => $_SESSION["loginMail"]]);

                    // set the lastPath to null
                    $sta = $con->prepare("update users set lastPath = NULL where userMail = :userMail");
                    $sta->execute(["userMail" => $_SESSION["loginMail"]]);

                    //get new currentPath
                    $sta = $con->prepare("select currentPath from users where userMail = :userMail");
                    $sta->execute(["userMail"=>$_SESSION["loginMail"]]);
                    $newCurrentPath = $sta->fetch(PDO::FETCH_ASSOC)["currentPath"];
                }else {
                    echo "empty";
                    die();
                }
            }catch(PDOException $e) {
                $con = null;
                die("Error in <farward.php> ::> when you try to update/get the currentPath");
            }

            // close  connection with DB
            $con = null;

            // prepare sub directories
            $dirArray = scandir($newCurrentPath);
            $response;
            foreach($dirArray as $e)
            {
                $response[] = '
                                    <td><i class="fas fa-folder"></i></td>
                                    <td>' . $e . '</td>
                                    <td>' . filetype($newCurrentPath. "/" .$e) . '</td>
                                    <td>' . configSize(filesize($newCurrentPath. "/" .$e)) . '</td>
                                    <td>' . date("Y-m-d H:i:s a", filemtime($newCurrentPath. "/" .$e)) . '</td>
                ';
            }
            
            //Convert this array to jsonForm / and send it
            array_push($response, $newCurrentPath);
            echo json_encode($response);
        }
    }
?>