<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catalog absente</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script/jquery.js"></script>
        <script src = "script/functions.js"></script>
        <script src = "script/script.js"></script>
    </head>
    <body>
        <?php 
            require("mysql.php");
            session_start();
            $action = "view";
            if(isset($_GET['action'])){
                $action = $_GET['action'];
            }
            include("header.php");
        ?>
        <section id = "content">
            <article>
                <?php
                $admin = false;
                $secretar = false;
                $profesor = false;
                $elev = false;
                switch ($_SESSION['tipcont']) {
                    case 1:
                        $admin = true;
                        break;
                    case 2:
                        $secretar = true;
                        break;
                    case 3:
                        $profesor = true;
                        break;
                    case 4:
                        $elev = true;
                        break;
                }
                if(!isset($_SESSION['idcont']) || $elev == true){
                    echo "<h2 class='error-message' >ACCES NEAUTORIZAT</h2>";
                    echo "</article></section>";
                    include("footer.php");
                    die();
                }       
                ?>
                <h1>Schimbare parola</h1>
                <?php
                    if(isset($_POST['change-password'])){
                        $id = $_SESSION['idcont'];
                        if($admin == true){
                            $stmt = $connection->prepare("select * from administratori where id = ?");
                        } else if($secretar == true){
                            $stmt = $connection->prepare("select * from secretari where id = ?");
                        } else {
                            $stmt = $connection->prepare("select * from profesori where id = ?");
                        }
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $parola_veche = $_POST['old-password'];
                            $hash = $row['parola'];
                            if(password_verify($parola_veche,$hash)){
                                if($_POST['new-password'] === $_POST['new-password-repeat']){
                                    if($admin == true){
                                        $stmt = $connection->prepare("update administratori set parola = ? where id = ?");
                                    } else if($secretar == true){
                                        $stmt = $connection->prepare("update secretari set parola = ? where id = ?");
                                    } else {
                                        $stmt = $connection->prepare("update profesori set parola = ? where id = ?");
                                    }
                                    $newHash = password_hash($_POST['new-password'],PASSWORD_DEFAULT);
                                    $stmt->bind_param("si",$newHash,$id);
                                    $stmt->execute();
                                    echo "<p class = 'text succes-message'>Parola schimbata cu succes.</p>";
                                } else {
                                    echo "<p class = 'text error-message'>Parolele noi nu se potrivesc.</p>";
                                }
                            } else {
                                echo "<p class = 'text error-message'>Parola veche este gresita.</p>";
                            }
                        } else {
                            echo "<p class = 'text error-message'>Eroare.</p>";
                        }
                    }
                ?>
                <div id = "profile-item-recover">
                    <form name = "change-password" action = "schimbareparola.php" method = "post">
                        <label for = "new-password">Parola veche : </label>
                        <input type = "password" name = "old-password"><br>
                        <label for = "new-password">Parola noua : </label>
                        <input type = "password" name = "new-password"><br>
                        <label for = "new-password">Repetare parola noua : </label>
                        <input type = "password" name = "new-password-repeat"><br>
                        <input type = "submit" class = "submit-button-default" name = "change-password" value = "Schimbare parola">
                    </form>
                </div>
            </article>
            </article>
        </section>
        <?php include("footer.php")?>
    </body>
</html>