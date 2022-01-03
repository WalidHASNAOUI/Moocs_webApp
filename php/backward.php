<?php 
    session_start();

    if(!isset($_SESSION["loginMail"]))
        header("Location: ./loginIn.php");
    else {
        if(!isset($_GET["path"]))
            header("Location: ../index.php");
        else {
            //convert the path from <string> to <array>
            $pathArr = explode("/",$_GET["path"]);
            $newPath = "";

            // check if the user is not in the default directory <Moocs>
            if($pathArr[count($pathArr)-1] != "Moocs")
                array_splice($pathArr,count($pathArr)-1,1);

             //convert this <array> to <string> manually
             foreach($pathArr as $e)
             {
                 if($e != $pathArr[count($pathArr)-1])
                     $newPath .= $e . "/";
                 else
                     $newPath .= $e;
             }

            //change the path of this user
            try{
                    $con = new PDO("mysql:host=localhost;dbname=gidb","root","c++javajs");
                    $sta = $con->prepare("update users set currentPath = :newPath where userMail = :usrMail");
                    $sta->execute(["newPath"=>$newPath ,"usrMail"=>$_SESSION["loginMail"]]);
                    $con = null;
            }catch(PDOException $e) {
                die("Error iin <backward.php> when you try update user path");
            }

            //liste all sub directory of this path
            $subDir = scandir($newPath); 
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