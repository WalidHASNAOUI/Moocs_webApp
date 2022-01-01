<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lister Moocs</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+SC:wght@500&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a1987b05f9.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div id="topHeader">
            <div id="left">
                <p id="pageTitle">LISTER</p>
            </div>
            <div class="searchContainer">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" name="reperoty" class=" serachBox" placeholder="Search here">
            </div>
            <div id="right">
                <img src="./images/profile.png">
            </div>
        </div>
        <div id="southHeader">
            <div>
                <i class="fas fa-chevron-circle-left"></i>
                <i class="fas fa-chevron-circle-right"></i>
            </div>
            <p>Moocs/rep1/rep1_2</p>
        </div>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th id="file">File</th>
                    <th id="name">name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //get all default dir
                $defDir = scandir("./Moocs");

                foreach ($defDir as $e) {
                    echo '
                            <tr>
                                <td><i class="fas fa-folder"></i></td>
                                <td>' . $e . '</td>
                                <td>' . filetype("./Moocs/" . $e) . '</td>
                                <td>' . filesize("./Moocs/" . $e) . '</td>
                                <td>' . date("Y-m-d H:i:s a", filemtime("./Moocs/" . $e)) . '</td>
                            </tr>
                        ';
                }
                ?>
            </tbody>
        </table>
    </main>

    <section>
    </section>

    <aside>
        <?php
        $array = scandir("./Moocs");
        foreach ($array as $e) {
            echo '<p>' . $e . ' --> ' . date('Y-m-d G:i:s a', filemtime("./Moocs/" . $e)) . ' --> ' . filesize("./Moocs/$e") . 'B  -->' . filetype("./Moocs/$e") . '</p></br>';
        }
        ?>
    </aside>
    <script src="./js/script.js"></script>
</body>

</html>