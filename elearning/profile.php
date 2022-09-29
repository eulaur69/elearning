<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil</title>
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
            include("header.php");
        ?>
        <section id = "content">
            <article>
                <?php
                    if(!isset($_SESSION['idcont']) || $_SESSION["tipcont"] != 4){
                        echo "<h2 class='error-message' >ACCES NEAUTORIZAT</h2>";
                        echo "</article></section>";
                        include("footer.php");
                        die();
                    }
                ?>
                <h1>Profil</h1>
                <div id = "add-menu" class = "desktop-only">
                    <div class = "add-menu-item add-menu-first-item" onclick = "showProfileItem('profile-item-data')">Date personale</div>
                    <div class = "add-menu-item" onclick = "showProfileItem('profile-item-grades')">Situatie note</div>
                    <div class = "add-menu-item" onclick = "showProfileItem('profile-item-attendence')">Situatie absente</div>
                    <div class = "add-menu-item add-menu-last-item" onclick = "showProfileItem('profile-item-recover')">Schimbare parola</div>
                </div>
                <?php
                    if(isset($_POST['change-password'])){
                        $id = $_GET['id'];
                        $stmt = $connection->prepare("select parola from elevi where id = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $parola_veche = $_POST['old-password'];
                            $hash = $row['parola'];
                            if(password_verify($parola_veche,$hash)){
                                if($_POST['new-password'] === $_POST['new-password-repeat']){
                                    $stmt = $connection->prepare("update elevi set parola = ? where id = ?");
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
                        }
                    }
                ?>
                <div id = "profile-item-data" class = "profile-item">
                    <h2>Date personale</h2>
                    <?php
                        $stmt = $connection->prepare("select * from elevi where id = ?");
                        $stmt->bind_param("i",$_SESSION['idcont']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $id = $row['id'];
                            $nume = $row['nume']." ".$row['prenume']." ".$row['prenume2'];
                            $cnp = $row['cnp'];
                            $adresa = $row['strada']." nr. ".$row['numar'];
                            $clasa = $row['clasa'];
                            if(isset($row['bloc'])){
                                $adresa = $adresa." bl. ".$row['bloc'];
                            }
                            if(isset($row['scara'])){
                                $adresa = $adresa." sc. ".$row['scara'];
                            }
                            if(isset($row['etaj'])){
                                $adresa = $adresa." et. ".$row['etaj'];
                            }
                            if(isset($row['apartament'])){
                                $adresa = $adresa." ap. ".$row['apartament'];
                            }
                            $codpostal = $row['cod_postal'];
                            $cetatenie = $row['cetatenie'];
                            $nationalitate = $row['nationalitate'];
                            $email = $row['email'];
                            $nrtel = $row['nrtelefon'];
                            echo "<div class = 'profile-info'>";
                                echo "<span>ID : </span>";
                                echo "<span>$id</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Nume complet : </span>";
                                echo "<span>$nume</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>CNP : </span>";
                                echo "<span>$cnp</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Adresa : </span>";
                                echo "<span>$adresa</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Cod postal : </span>";
                                echo "<span>$codpostal</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Cetatenie : </span>";
                                echo "<span>$cetatenie</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Nationalitate : </span>";
                                echo "<span>$nationalitate</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Email : </span>";
                                echo "<span>$email</span>";
                            echo "</div>";
                            echo "<div class = 'profile-info'>";
                                echo "<span>Nr. tel. : </span>";
                                echo "<span>$nrtel</span>";
                            echo "</div>";
                            $stmtClasa = $connection->prepare("select * from clase where id = ?");
                            $stmtClasa->bind_param("i",$clasa);
                            $stmtClasa->execute();
                            $resultClasa = $stmtClasa->get_result();
                            if($rowClasa = mysqli_fetch_assoc($resultClasa)){
                                $clasaN = $rowClasa['clasa'];
                                $litera = $rowClasa['litera'];
                                echo "<div class = 'profile-info'>";
                                echo "<span>Clasa : </span>";
                                echo "<span>$clasaN$litera</span>";
                            echo "</div>";
                            }
                        }
                    ?>
                </div>
                <div id = "profile-item-grades" class = "profile-item initially-hidden">
                    <h2>Situatie note</h2>
                    <?php
                        $stmtMaterii = $connection->prepare("select materie from profesori_clase where clasa = ?");
                        $stmtMaterii->bind_param("i",$clasa);
                        $stmtMaterii->execute();
                        $resultMaterii = $stmtMaterii->get_result();
                        echo "<table class = 'contentTable'>";
                        echo "<tr>
                                <th>Materie</th>
                                <th>Media</th>
                        ";
                        while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
                            $stmtNumeMaterie = $connection->prepare("select * from materii where id = ?");
                            $stmtNumeMaterie->bind_param("i",$rowMaterii['materie']);
                            $stmtNumeMaterie->execute();
                            $resultNumeMaterie = $stmtNumeMaterie->get_result();
                            echo "<tr>";
                            if($rowNumeMaterie = mysqli_fetch_assoc($resultNumeMaterie)){
                                echo "<td>".$rowNumeMaterie['materie']." (".$rowNumeMaterie['cod'].")"."</td>";
                            }
                            $stmtMedia = $connection->prepare("select avg(nota) as media from note where elev = ? and materie = ?");
                            $stmtMedia->bind_param("ii",$_SESSION['idcont'],$rowMaterii['materie']);
                            $stmtMedia->execute();
                            $resultMedia = $stmtMedia->get_result();
                            if($rowMedia = mysqli_fetch_assoc($resultMedia)){
                                echo "<td>".number_format($rowMedia['media'],2)."</td>";
                            }
                            $stmtNote = $connection->prepare("select nota, data from note where elev = ? and materie = ?");
                            $stmtNote->bind_param("ii",$_SESSION['idcont'],$rowMaterii['materie']);
                            $stmtNote->execute();
                            $resultNote = $stmtNote->get_result();
                            while($rowNote = mysqli_fetch_assoc($resultNote)){
                                echo "<td>";
                                echo "Nota ".$rowNote['nota']."<br>";
                                echo $rowNote['data'];
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    ?>
                </div>
                <div id = "profile-item-attendence" class = "profile-item initially-hidden">
                    <h2>Situatie absente</h2>
                    <?php
                        $stmtMaterii = $connection->prepare("select materie from profesori_clase where clasa = ?");
                        $stmtMaterii->bind_param("i",$clasa);
                        $stmtMaterii->execute();
                        $resultMaterii = $stmtMaterii->get_result();
                        echo "<table class = 'contentTable'>";
                        echo "<tr>
                                <th>Materie</th>
                                <th>Nr. abs.</th>
                        ";
                        while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
                            $stmtNumeMaterie = $connection->prepare("select * from materii where id = ?");
                            $stmtNumeMaterie->bind_param("i",$rowMaterii['materie']);
                            $stmtNumeMaterie->execute();
                            $resultNumeMaterie = $stmtNumeMaterie->get_result();
                            echo "<tr>";
                            if($rowNumeMaterie = mysqli_fetch_assoc($resultNumeMaterie)){
                                echo "<td>".$rowNumeMaterie['materie']." (".$rowNumeMaterie['cod'].")"."</td>";
                            }
                            $stmtNrAbs = $connection->prepare("select count(id) as nrabs from absente where elev = ? and materie = ? and motivata = 0");
                            $stmtNrAbs->bind_param("ii",$_SESSION['idcont'],$rowMaterii['materie']);
                            $stmtNrAbs->execute();
                            $resultNrAbs = $stmtNrAbs->get_result();
                            if($rowNrAbs = mysqli_fetch_assoc($resultNrAbs)){
                                echo "<td>".$rowNrAbs['nrabs']."</td>";
                            }
                            $stmtAbs = $connection->prepare("select * from absente where elev = ? and materie = ?");
                            $stmtAbs->bind_param("ii",$_SESSION['idcont'],$rowMaterii['materie']);
                            $stmtAbs->execute();
                            $resultAbs = $stmtAbs->get_result();
                            while($rowAbs = mysqli_fetch_assoc($resultAbs)){
                                echo "<td>";
                                if($rowAbs['motivata'] == 1){
                                    echo "Motivat<br>";
                                } else {
                                    echo "Nemotivat<br>";
                                }
                                echo $rowAbs['data'];
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    ?>
                </div>
                <div id = "profile-item-recover" class = "profile-item initially-hidden">
                    <h2>Schimbare parola</h2>
                    <form name = "change-password" action = "profile.php?id=<?=$_SESSION['idcont']?>" method = "post">
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
        </section>
        <?php include("footer.php");?>
    </body>
</html>