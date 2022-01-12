<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
        header("Location: ./signUp_signIn.php");
    else {
        if(!isset($_GET["dir"]))
            header("Location: ../index.php");
        else {
             //inlude function.php file <configSize>
             include './functions.php';

            $currentPath = "";
            try{
                // make connection with db 
                $con = new PDO("mysql:host=localhost;dbname=gidb","root","");

                // set null into lastPath of user (because user try to access new directory so the farward path will be null)
                $sta = $con->prepare("update users set lastPath = NULL where userMail = :userMail");
                $sta->execute(["userMail"=>$_SESSION["loginMail"]]);

                // extract the current path of this user
                $sta = $con->prepare("select currentPath from users where userMail = :usrMail");
                $sta->execute(["usrMail"=>$_SESSION["loginMail"]]);
                $currentPath = $sta->fetch(PDO::FETCH_ASSOC);

                //generate the new path
                $newPath = $currentPath["currentPath"]."/".$_GET["dir"];
            }catch(PDOException $e) {
                die("Error in <Access.php>");
            }
            
            // List all subdir of the $_GET["dir"]
            $subDir = array_slice(scandir($newPath),2);  //return array("." , ".." , ....);

            // Checking if the folder is empty or not
            if(count($subDir) != 0)
            {
                 //save the <new path> of this user 
                try{
                    $sta = $con->prepare("update users set currentPath = :newPath where userMail = :usrMail");
                    $sta->execute(["newPath"=> $newPath, "usrMail"=>$_SESSION["loginMail"]]);

                    //close connection (using just null)
                    $con = null;
                }catch(PDOException $e){
                    die("Error!! ::> When you try to save the new current path");
                }
                
                // Generate new array of sub folders
                $response;
                foreach($subDir as $e)
                {
                    $response[] = '
                                        <td>'.generateIcon($newPath."/".$e).'</td>
                                        <td class="courstitle">' . $e . '</td>
                                        <td>' . filetype($newPath. "/" .$e) . '</td>
                                        <td>' . configSize(filesize($newPath. "/" .$e)) . '</td>
                                        <td>' . date("Y-m-d H:i:s a", filemtime($newPath. "/" .$e)) . '</td>
                    ';
                }

                //send the response to the client <js> (Json array)
                echo json_encode($response);
            }else {
                //The folder is empty <send a text message to the client <js>>
                echo "empty";
            }
        }
    }
?>