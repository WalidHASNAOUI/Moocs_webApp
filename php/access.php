<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
        header("Location: ./loginIn.php");
    else {
        if(!isset($_GET["dir"]))
            header("Location: ../index.php");
        else {
            $currentPath = "";
            try{
                // make connection with db / extract the current path of this user
                $con = new PDO("mysql:host=localhost;dbname=gidb","root","");
                $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
                $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);
                $currentPath = $sta->fetch(PDO::FETCH_ASSOC);

                //generate the new path
                $newPath = $currentPath["currentPath"]."/".$_GET["dir"];

                //save the <new path> of this user 
                $sta = $con->prepare("update users set currentPath = :newPath where userMail = :usrMail");
                $sta->execute(["newPath"=> $newPath, "usrMail"=>$_SESSION["loginMail"]]);

                //close connection (using just null)
                $con = null;
            }catch(PDOException $e) {
                die("Error in <Access.php>");
            }
            // list all subdir of the $_GET["dir"]
            $subDir = scandir($newPath);  //return array("." , ".." , ....);
            $response;
            foreach($subDir as $e)
            {
                $response[] = '
                                    <td><i class="fas fa-folder"></i></td>
                                    <td>' . $e . '</td>
                                    <td>' . filetype($newPath. "/" .$e) . '</td>
                                    <td>' . filesize($newPath. "/" .$e) . '</td>
                                    <td>' . date("Y-m-d H:i:s a", filemtime($newPath. "/" .$e)) . '</td>
                ';
            }
            // print_r(json_encode($response));
            echo json_encode($response);
        }
    }
?>