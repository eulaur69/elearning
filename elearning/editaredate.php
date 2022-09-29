<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tabele date</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src = "script/jquery.js"></script>
        <script src = "script/functions.js"></script>
        <script src = "script/script.js"></script>
        <script src="https://kit.fontawesome.com/b08654effa.js" crossorigin="anonymous"></script>
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
                <h1>
                    <?php
                        if($secretar == true){
                            echo "Vizualizare "; 
                        } else echo "Editare ";
                    ?> date</h1>
                <?php
                    if(isset($_POST['editareElev'])){
                        $id = $_GET['elev'];
                        $nume = $_POST['nume'];
                        $prenume = $_POST['prenume'];
                        $prenume2 = $_POST['prenume2'];
                        $cnp = $_POST['cnp'];
                        $email = $_POST['email'];
                        $nrtel = $_POST['nrtel'];
                        $judet = $_POST['judet'];
                        $localitate = $_POST['localitate'];
                        $strada = $_POST['strada'];
                        $numar = $_POST['numar'];
                        $bloc = $_POST['bloc'];
                        $bloc = !empty($bloc) ? "'$bloc'" : NULL;
                        $scara = $_POST['scara'];
                        $scara = !empty($scara) ? "'$scara'" : NULL;
                        $etaj = $_POST['etaj'];
                        $etaj = !empty($etaj) ? "'$etaj'" : NULL;
                        $apartament = $_POST['apartament'];
                        $apartament = !empty($apartament) ? "'$apartament'" : NULL;
                        $cod_postal = $_POST['cod_postal'];
                        $cetatenie = $_POST['cetatenie'];
                        $nationalitate = $_POST['nationalitate'];
                        $email = $_POST['email'];
                        $clasa = $_POST['clasa'];
                        $tip_cont = 4;
                        //$parola = $nume.$prenume.substr($cnp,-6);
                        //$parola = password_hash($parola,PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("update elevi set nume = ?,prenume  = ?,prenume2  = ?,cnp  = ?,email  = ?,
                                                                        nrtelefon  = ?,judet  = ?,localitate  = ?,
                                                                        strada  = ?,numar  = ?,bloc  = ?,scara  = ?,etaj  = ?,
                                                                        apartament  = ?,cod_postal  = ?,cetatenie  = ?,
                                                                        nationalitate  = ?,clasa  = ?,tip_cont  = ?
                                                                  where id = ?");
                        $stmt->bind_param("sssssisssissiiissiii",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$clasa,$tip_cont,$id);
                        $stmt->execute(); 
                        echo "<h2 class = 'succes-message'>Elev editat cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                    if(isset($_POST['editareProf'])){
                        $id = $_GET['profesor'];
                        $nume = $_POST['nume'];
                        $prenume = $_POST['prenume'];
                        $prenume2 = $_POST['prenume2'];
                        $cnp = $_POST['cnp'];
                        $email = $_POST['email'];
                        $nrtel = $_POST['nrtel'];
                        $judet = $_POST['judet'];
                        $localitate = $_POST['localitate'];
                        $strada = $_POST['strada'];
                        $numar = $_POST['numar'];
                        $bloc = $_POST['bloc'];
                        $bloc = !empty($bloc) ? "'$bloc'" : NULL;
                        $scara = $_POST['scara'];
                        $scara = !empty($scara) ? "'$scara'" : NULL;
                        $etaj = $_POST['etaj'];
                        $etaj = !empty($etaj) ? "'$etaj'" : NULL;
                        $apartament = $_POST['apartament'];
                        $apartament = !empty($apartament) ? "'$apartament'" : NULL;
                        $cod_postal = $_POST['cod_postal'];
                        $cetatenie = $_POST['cetatenie'];
                        $nationalitate = $_POST['nationalitate'];
                        $email = $_POST['email'];
                        $tip_cont = 3;
                        //$parola = $nume.$prenume.substr($cnp,-6);
                        //$parola = password_hash($parola,PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("update profesori set nume = ?,prenume  = ?,prenume2  = ?,cnp  = ?,email  = ?,
                                                                        nrtelefon  = ?,judet  = ?,localitate  = ?,
                                                                        strada  = ?,numar  = ?,bloc  = ?,scara  = ?,etaj  = ?,
                                                                        apartament  = ?,cod_postal  = ?,cetatenie  = ?,
                                                                        nationalitate  = ?,tip_cont  = ?
                                                                  where id = ?");
                        $stmt->bind_param("sssssisssissiiissii",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$id);
                        $stmt->execute();
                        $stmt = $connection->prepare("delete from profesori_materii where profesor = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        if(isset($_POST['materii'])){
                            $materii = $_POST['materii'];
                            foreach($materii as $materie){
                                $stmt = $connection->prepare("insert into profesori_materii(profesor,materie) values(?,?)");
                                $stmt->bind_param("ii",$id,$materie);
                                $stmt->execute();
                            }
                        }
                        echo "<h2 class = 'succes-message'>Profesor editat cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                    if(isset($_POST['editareSecretar'])){
                        $id = $_GET['secretar'];
                        $nume = $_POST['nume'];
                        $prenume = $_POST['prenume'];
                        $prenume2 = $_POST['prenume2'];
                        $cnp = $_POST['cnp'];
                        $email = $_POST['email'];
                        $nrtel = $_POST['nrtel'];
                        $judet = $_POST['judet'];
                        $localitate = $_POST['localitate'];
                        $strada = $_POST['strada'];
                        $numar = $_POST['numar'];
                        $bloc = $_POST['bloc'];
                        $bloc = !empty($bloc) ? "'$bloc'" : NULL;
                        $scara = $_POST['scara'];
                        $scara = !empty($scara) ? "'$scara'" : NULL;
                        $etaj = $_POST['etaj'];
                        $etaj = !empty($etaj) ? "'$etaj'" : NULL;
                        $apartament = $_POST['apartament'];
                        $apartament = !empty($apartament) ? "'$apartament'" : NULL;
                        $cod_postal = $_POST['cod_postal'];
                        $cetatenie = $_POST['cetatenie'];
                        $nationalitate = $_POST['nationalitate'];
                        $email = $_POST['email'];
                        $tip_cont = 3;
                        $parola = $nume.$prenume.substr($cnp,-6);
                        $parola = password_hash($parola,PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("update secretari set nume = ?,prenume  = ?,prenume2  = ?,cnp  = ?,email  = ?,
                                                                        nrtelefon  = ?,judet  = ?,localitate  = ?,
                                                                        strada  = ?,numar  = ?,bloc  = ?,scara  = ?,etaj  = ?,
                                                                        apartament  = ?,cod_postal  = ?,cetatenie  = ?,
                                                                        nationalitate  = ?,tip_cont  = ?
                                                                  where id = ?");
                        $stmt->bind_param("sssssisssissiiissii",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$id);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Secretar editat cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                    if(isset($_POST['editareAdmin'])){
                        $id = $_GET['admin'];
                        $nume = $_POST['nume'];
                        $prenume = $_POST['prenume'];
                        $prenume2 = $_POST['prenume2'];
                        $cnp = $_POST['cnp'];
                        $email = $_POST['email'];
                        $nrtel = $_POST['nrtel'];
                        $judet = $_POST['judet'];
                        $localitate = $_POST['localitate'];
                        $strada = $_POST['strada'];
                        $numar = $_POST['numar'];
                        $bloc = $_POST['bloc'];
                        $bloc = !empty($bloc) ? "'$bloc'" : NULL;
                        $scara = $_POST['scara'];
                        $scara = !empty($scara) ? "'$scara'" : NULL;
                        $etaj = $_POST['etaj'];
                        $etaj = !empty($etaj) ? "'$etaj'" : NULL;
                        $apartament = $_POST['apartament'];
                        $apartament = !empty($apartament) ? "'$apartament'" : NULL;
                        $cod_postal = $_POST['cod_postal'];
                        $cetatenie = $_POST['cetatenie'];
                        $nationalitate = $_POST['nationalitate'];
                        $email = $_POST['email'];
                        $tip_cont = 3;
                        $parola = $nume.$prenume.substr($cnp,-6);
                        $parola = password_hash($parola,PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("update administratori set nume = ?,prenume  = ?,prenume2  = ?,cnp  = ?,email  = ?,
                                                                        nrtelefon  = ?,judet  = ?,localitate  = ?,
                                                                        strada  = ?,numar  = ?,bloc  = ?,scara  = ?,etaj  = ?,
                                                                        apartament  = ?,cod_postal  = ?,cetatenie  = ?,
                                                                        nationalitate  = ?,tip_cont  = ?
                                                                  where id = ?");
                        $stmt->bind_param("sssssisssissiiissii",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$id);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Administrator editat cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                    if(isset($_POST['editareClasa'])){
                        $id = $_GET['clasa'];
                        $clasa = $_POST['clasa'];
                        $litera = $_POST['litera'];
                        $diriginte = $_POST['diriginte'];
                        $stmt = $connection->prepare("update clase set clasa = ?, litera = ?, diriginte = ? where id = ?");
                        $stmt->bind_param("isii",$clasa,$litera,$diriginte,$id);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Clasa editata cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                    if(isset($_POST['editareMaterie'])){
                        $id = $_GET['materie'];
                        $materie = $_POST['materie'];
                        $cod = $_POST['cod'];
                        $descriere = $_POST['descriere'];
                        $stmt = $connection->prepare("update materii set materie = ?, cod = ?, descriere = ? where id = ?");
                        $stmt->bind_param("sssi",$materie,$cod,$descriere,$id);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Materie editata cu succes! <a href = 'editaredate.php'>Inapoi la tabelul principal</a></h2>";
                    }
                ?>
                <?php if(empty($_GET) || isset($_GET['succes']) || isset($_GET['fail'])) : ?>
                <?php
                    if(isset($_GET['succes'])){
                        if(isset($_GET['delete'])){
                            echo "<h2 id = 'succes-message'>Stergere efectuata cu succes!</h2>";
                        } else {
                            echo "<h2 id = 'succes-message'>Resetare parola efectuata cu succes!</h2>";
                        }
                    }
                    if(isset($_GET['fail'])){
                        if(isset($_GET['delete'])){
                            echo "<h2 id = 'error-message'>Stergerea nu a fost efectuata. Exista conflicte cu alte date dependente.</h2>";
                        } else {
                            echo "<h2 id = 'error-message'>Resetarea parolei nu a fost efectuata. A aparut o eroare.</h2>";
                        }
                    }
                ?>
                <div id = "edit-menu">
                    <div id = "edit-menu-elevi" class = "edit-menu-item edit-menu-first-item" onclick = "showEditMenuItem('editareElevTable')">Elev</div>
                    <div id = "edit-menu-profesor" class = "edit-menu-item" onclick = "showEditMenuItem('editareProfTable')">Profesor</div>
                    <?php if($admin == true): ?>
                        <div id = 'edit-menu-secretar' class = 'edit-menu-item' onclick = 'showEditMenuItem("editareSecretarTable")'>Secretar</div>
                        <div id = 'edit-menu-admin' class = 'edit-menu-item' onclick = 'showEditMenuItem("editareAdminTable")'>Admin</div>
                    <?php endif ?> 
                    <div id = "edit-menu-clasa" class = "edit-menu-item" onclick = "showEditMenuItem('editareClasaTable')">Clasa</div>
                    <div id = "edit-menu-materie" class = "edit-menu-item edit-menu-last-item" onclick = "showEditMenuItem('editareMaterieTable')">Materie</div>
                </div>
                <div id = "editareElevTable" class = "edit-table">
                    <h3>Tabel date elevi</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select elevi.id,nume,prenume,clase.clasa,clase.litera,cnp,email,nrtelefon,judet,localitate,strada,
                                      numar, bloc, scara, etaj, apartament, cod_postal, cetatenie, nationalitate from elevi
                                      join clase on elevi.clasa = clase.id
                                      order by clase.clasa, clase.litera, nume, prenume";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>ID</th>";
                                echo "<th>Nume</th>";
                                echo "<th>Prenume</th>";
                                echo "<th>Clasa</th>";
                                echo "<th>CNP</th>";
                                echo "<th>Email</th>";
                                echo "<th>Numar telefon</th>";
                                echo "<th>Judet</th>";
                                echo "<th>Localitate</th>";
                                if($secretar == true){
                                    echo "<th>Adresa</th>";
                                    echo "<th>Cod postal</th>";
                                    echo "<th>Cetatenie</th>";
                                    echo "<th>Nationalitate</th>";
                                }
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $nume = $row['nume'];
                                $prenume = $row['prenume'];
                                $clasa = $row['clasa'].$row['litera'];
                                $cnp = $row['cnp'];
                                $email = $row['email'];
                                $nrtelefon = $row['nrtelefon'];
                                $judet = $row['judet'];
                                $localitate = $row['localitate'];
                                if($secretar == true){
                                    $adresa = $row['strada']." ".$row['numar'];
                                    if($row['bloc'] != null){
                                        $adresa = $adresa . " Bl. ".$row['bloc'];
                                    }
                                    if($row['scara'] != null){
                                        $adresa = $adresa . " Sc. ".$row['scara'];
                                    }
                                    if($row['etaj'] != null){
                                        $adresa = $adresa . " Et. ".$row['etaj'];
                                    }
                                    if($row['apartament'] != null){
                                        $adresa = $adresa . " Ap. ".$row['apartament'];
                                    }
                                    $cod_postal = $row['cod_postal'];
                                    $cetatenie = $row['cetatenie'];
                                    $nationalitate = $row['nationalitate'];
                                }
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$id</td>";
                                    echo "<td>$nume</td>";
                                    echo "<td>$prenume</td>";
                                    echo "<td>$clasa</td>";
                                    echo "<td>$cnp</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$nrtelefon</td>";
                                    echo "<td>$judet</td>";
                                    echo "<td>$localitate</td>";
                                    if($secretar == true){
                                        echo "<td>$adresa</td>";
                                        echo "<td>$cod_postal</td>";
                                        echo "<td>$cetatenie</td>";
                                        echo "<td>$nationalitate</td>";
                                    }
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?elev=$id'><i title = 'Editare elev' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?elev=$id&delete'><i title = 'Stergere elev' class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?elev=$id&recover'><i title = 'Resetare parola' class='fa-solid fa-sync'></i></td>";

                                    }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

                <div id = "editareProfTable" class = "edit-table initially-hidden">
                    <h3>Tabel date profesori</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select * from profesori order by nume, prenume";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>ID</th>";
                                echo "<th>Nume</th>";
                                echo "<th>Prenume</th>";
                                echo "<th>CNP</th>";
                                echo "<th>Email</th>";
                                echo "<th>Numar telefon</th>";
                                echo "<th>Judet</th>";
                                echo "<th>Localitate</th>";
                                if($secretar == true){
                                    echo "<th>Adresa</th>";
                                    echo "<th>Cod postal</th>";
                                    echo "<th>Cetatenie</th>";
                                    echo "<th>Nationalitate</th>";
                                }
                                echo "<th>Materii</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $nume = $row['nume'];
                                $prenume = $row['prenume'];
                                $cnp = $row['cnp'];
                                $email = $row['email'];
                                $nrtelefon = $row['nrtelefon'];
                                $judet = $row['judet'];
                                $localitate = $row['localitate'];
                                if($secretar == true){
                                    $adresa = $row['strada']." ".$row['numar'];
                                    if($row['bloc'] != null){
                                        $adresa = $adresa . " Bl. ".$row['bloc'];
                                    }
                                    if($row['scara'] != null){
                                        $adresa = $adresa . " Sc. ".$row['scara'];
                                    }
                                    if($row['etaj'] != null){
                                        $adresa = $adresa . " Et. ".$row['etaj'];
                                    }
                                    if($row['apartament'] != null){
                                        $adresa = $adresa . " Ap. ".$row['apartament'];
                                    }
                                    $cod_postal = $row['cod_postal'];
                                    $cetatenie = $row['cetatenie'];
                                    $nationalitate = $row['nationalitate'];
                                }
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$id</td>";
                                    echo "<td>$nume</td>";
                                    echo "<td>$prenume</td>";
                                    echo "<td>$cnp</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$nrtelefon</td>";
                                    echo "<td>$judet</td>";
                                    echo "<td>$localitate</td>";
                                    if($secretar == true){
                                        echo "<td>$adresa</td>";
                                        echo "<td>$cod_postal</td>";
                                        echo "<td>$cetatenie</td>";
                                        echo "<td>$nationalitate</td>";
                                    }
                                    echo "<td>";
                                    $stmt = $connection->prepare("select profesori_materii.materie, materii.cod from profesori_materii 
                                                                  join materii on profesori_materii.materie = materii.id where profesor = ?");
                                    $stmt->bind_param("i",$id);
                                    $stmt->execute();
                                    $resultMaterii = $stmt->get_result();
                                    while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
                                        $materie = $rowMaterii['cod'];
                                        echo $materie." ";
                                    }
                                    echo "</td>";
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?profesor=$id'><i title = 'Editare profesor' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?profesor=$id&delete'><i title = 'Stergere profesor' class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?profesor=$id&recover'><i title = 'Resetare parola' class='fa-solid fa-sync'></i></td>";
                                    }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

                <div id = "editareSecretarTable" class = "edit-table initially-hidden">
                    <h3>Tabel date secretari</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select * from secretari order by nume, prenume";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>ID</th>";
                                echo "<th>Nume</th>";
                                echo "<th>Prenume</th>";
                                echo "<th>CNP</th>";
                                echo "<th>Email</th>";
                                echo "<th>Numar telefon</th>";
                                echo "<th>Judet</th>";
                                echo "<th>Localitate</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $nume = $row['nume'];
                                $prenume = $row['prenume'];
                                $cnp = $row['cnp'];
                                $email = $row['email'];
                                $nrtelefon = $row['nrtelefon'];
                                $judet = $row['judet'];
                                $localitate = $row['localitate'];
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$id</td>";
                                    echo "<td>$nume</td>";
                                    echo "<td>$prenume</td>";
                                    echo "<td>$cnp</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$nrtelefon</td>";
                                    echo "<td>$judet</td>";
                                    echo "<td>$localitate</td>";
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?secretar=$id'><i title = 'Editare secretar' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?secretar=$id&delete'><i title = 'Stergere secretar' class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?secretar=$id&recover'><i title = 'Resetare parola' class='fa-solid fa-sync'></i></td>";
                                     }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

                <div id = "editareAdminTable" class = "edit-table initially-hidden">
                    <h3>Tabel date administratori</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select * from administratori order by nume, prenume";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>ID</th>";
                                echo "<th>Nume</th>";
                                echo "<th>Prenume</th>";
                                echo "<th>CNP</th>";
                                echo "<th>Email</th>";
                                echo "<th>Numar telefon</th>";
                                echo "<th>Judet</th>";
                                echo "<th>Localitate</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $nume = $row['nume'];
                                $prenume = $row['prenume'];
                                $cnp = $row['cnp'];
                                $email = $row['email'];
                                $nrtelefon = $row['nrtelefon'];
                                $judet = $row['judet'];
                                $localitate = $row['localitate'];
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$id</td>";
                                    echo "<td>$nume</td>";
                                    echo "<td>$prenume</td>";
                                    echo "<td>$cnp</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$nrtelefon</td>";
                                    echo "<td>$judet</td>";
                                    echo "<td>$localitate</td>";
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?admin=$id'><i title = 'Editare administrator' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?admin=$id&delete'><i title = 'Stergere administrator' class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?admin=$id&recover'><i title = 'Resetare parola' class='fa-solid fa-sync'></i></td>";
                                }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

                <div id = "editareClasaTable" class = "edit-table initially-hidden">
                    <h3>Tabel date clase</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select * from clase order by clasa, litera";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Clasa</th>";
                                echo "<th>Diriginte</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $clasa = $row['clasa'].$row['litera'];
                                $diriginte = $row['diriginte'];
                                $stmtDrigitinte = $connection->prepare("select nume, prenume from profesori where id = ?");
                                $stmtDrigitinte->bind_param("i",$diriginte);
                                $stmtDrigitinte->execute();
                                $resultDiriginte = $stmtDrigitinte->get_result();
                                $rowDiriginte = mysqli_fetch_assoc($resultDiriginte);
                                $nume = $rowDiriginte['nume']." ".$rowDiriginte['prenume'];
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$clasa</td>";
                                    echo "<td>$nume</td>";
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?clasa=$id'><i title = 'Editare clasa' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?clasa=$id&delete'><i title = 'Stergere clasa' class='fa fa-trash'></i></a></td>";
                                    }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

                <div id = "editareMaterieTable" class = "edit-table initially-hidden">
                    <h3>Tabel date materii</h3>
                    <table class = "contentTable">
                        <?php
                            $query = "select * from materii order by materie, cod";
                            $result = mysqli_query($connection,$query);
                            $counter = 1;
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Materie</th>";
                                echo "<th>Cod</th>";
                                echo "<th>Descriere</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $materie = $row['materie'];
                                $cod = $row['cod'];
                                $descriere = $row['descriere'];
                                echo "<tr>";
                                    echo "<td>$counter</td>";
                                    $counter++;
                                    echo "<td>$materie</td>";
                                    echo "<td>$cod</td>";
                                    echo "<td>$descriere</td>";
                                    if($admin == true){
                                        echo "<td><a href = 'editaredate.php?materie=$id'><i title = 'Editare materie' class='fas fa-edit'></i></a></td>";
                                        echo "<td><a href = 'editaredate.php?materie=$id&delete'><i title = 'Stergere materie' class='fa fa-trash'></i></a></td>";
                                    }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <?php elseif(isset($_GET['elev'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['elev'];
                        $stmt = $connection->prepare("delete from elevi where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    if(isset($_GET['recover'])){
                        $id = $_GET['elev'];
                        $stmt = $connection->prepare("select * from elevi where id = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $nume = $row['nume'];
                            $prenume = $row['prenume'];
                            $cnp = $row['cnp'];
                            $parola = $nume.$prenume.substr($cnp,-6);
                            $parola = password_hash($parola,PASSWORD_DEFAULT);
                            $stmt = $connection->prepare("update elevi set parola = ? where id = ?");
                            $stmt->bind_param("si",$parola, $id);
                            try{
                                $stmt->execute();
                                header("Location: editaredate.php?succes&recover");
                                die();
                            }catch(exception $e){
                                header("Location: editaredate.php?fail&recover");
                                die();
                            }
                        } else {
                            echo "<h3 class = 'error-message'>Eroare.</h3>";
                        }
                    }
                    $id = $_GET['elev'];
                    $stmt = $connection->prepare("select * from elevi where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $nume = $data['nume'];
                    $prenume = $data['prenume'];
                    $prenume2 = $data['prenume2'];
                    $cnp = $data['cnp'];
                    $email = $data['email'];
                    $nrtel = $data['nrtelefon'];
                    $judet = $data['judet'];
                    $localitate = $data['localitate'];
                    $strada = $data['strada'];
                    $numar = $data['numar'];
                    $bloc = $data['bloc'];
                    $scara = $data['scara'];
                    $etaj = $data['etaj'];
                    $apartament = $data['apartament'];
                    $cod_postal = $data['cod_postal'];
                    $cetatenie = $data['cetatenie'];
                    $nationalitate = $data['nationalitate'];
                    $email = $data['email'];
                    $clasa = $data['clasa'];
                ?>
                <form id="editareElev" name = "editareElev" action="editaredate.php?elev=<?=$_GET['elev']?>" onsubmit = "return validatePersonalInformation(this.id);" method="post">
                <h2>Editare elev</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume' value = '<?=$nume?>'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume' value = '<?=$prenume?>'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2' value = '<?=$prenume2?>'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp' value = '<?=$cnp?>'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email' value = '<?=$email?>'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel' value = '<?=$nrtel?>'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet' value = '<?=$judet?>'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate' value = '<?=$localitate?>'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada' value = '<?=$strada?>'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input" value = '<?=$numar?>'>
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input" value = '<?=$bloc?>'>
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input" value = '<?=$scara?>'>
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input" value = '<?=$etaj?>'>
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input" value = '<?=$apartament?>'><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal' value = '<?=$cod_postal?>'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie' value = '<?=$cetatenie?>'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate' value = '<?=$nationalitate?>'><br>
                    <label for = "clasa" id = "clase-add-label">Clasa* : </label>
                    <select name="clasa" id="clasa">
                        <?php
                            $queryClasa = "select * from clase order by clasa, litera";
                            $resultClasa = mysqli_query($connection,$queryClasa);
                            echo "<option selected disabled></option>";
                            while($rowClasa = mysqli_fetch_assoc($resultClasa)){
                                $idClasa = $rowClasa['id'];
                                $clasaN = $rowClasa['clasa'];
                                $litera = $rowClasa['litera'];
                                echo "<option value = '$idClasa' ";
                                if($idClasa == $clasa){
                                    echo "selected";
                                }
                                echo ">$clasaN$litera</option>";
                            }
                        ?>
                    </select><br>
                    <input class = "submit-button-default" type="submit" name="editareElev" value="Submit">
                </form>
                
                <?php elseif(isset($_GET['profesor'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['profesor'];
                        $stmt = $connection->prepare("delete from profesori where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            echo mysqli_error($connection);
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    if(isset($_GET['recover'])){
                        $id = $_GET['profesor'];
                        $stmt = $connection->prepare("select * from profesori where id = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $nume = $row['nume'];
                            $prenume = $row['prenume'];
                            $cnp = $row['cnp'];
                            $parola = $nume.$prenume.substr($cnp,-6);
                            $parola = password_hash($parola,PASSWORD_DEFAULT);
                            $stmt = $connection->prepare("update profesori set parola = ? where id = ?");
                            $stmt->bind_param("si",$parola, $id);
                            try{
                                $stmt->execute();
                                header("Location: editaredate.php?succes&recover");
                                die();
                            }catch(exception $e){
                                header("Location: editaredate.php?fail&recover");
                                die();
                            }
                        } else {
                            echo "<h3 class = 'error-message'>Eroare.</h3>";
                        }
                    }
                    $id = $_GET['profesor'];
                    $stmt = $connection->prepare("select * from profesori where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $nume = $data['nume'];
                    $prenume = $data['prenume'];
                    $prenume2 = $data['prenume2'];
                    $cnp = $data['cnp'];
                    $email = $data['email'];
                    $nrtel = $data['nrtelefon'];
                    $judet = $data['judet'];
                    $localitate = $data['localitate'];
                    $strada = $data['strada'];
                    $numar = $data['numar'];
                    $bloc = $data['bloc'];
                    $scara = $data['scara'];
                    $etaj = $data['etaj'];
                    $apartament = $data['apartament'];
                    $cod_postal = $data['cod_postal'];
                    $cetatenie = $data['cetatenie'];
                    $nationalitate = $data['nationalitate'];
                    $email = $data['email'];

                    $stmt = $connection->prepare("select * from profesori_materii where profesor = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $materii = array();
                    while($data = mysqli_fetch_assoc($result)){
                        array_push($materii,$data['materie']);
                    }
                ?>
                <form id="editareProf" name = "editareProf" onsubmit = "return validatePersonalInformation(this.id);" action="editaredate.php?profesor=<?=$_GET['profesor']?>" method="post">
                <h2>Editare profesor</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume' value = '<?=$nume?>'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume' value = '<?=$prenume?>'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2' value = '<?=$prenume2?>'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp' value = '<?=$cnp?>'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email' value = '<?=$email?>'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel' value = '<?=$nrtel?>'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet' value = '<?=$judet?>'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate' value = '<?=$localitate?>'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada' value = '<?=$strada?>'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input" value = '<?=$numar?>'>
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input" value = '<?=$bloc?>'>
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input" value = '<?=$scara?>'>
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input" value = '<?=$etaj?>'>
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input" value = '<?=$apartament?>'><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal' value = '<?=$cod_postal?>'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie' value = '<?=$cetatenie?>'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate' value = '<?=$nationalitate?>'><br>
                    <label for = "Materii" id = "clase-add-label">Materii : </label>
                    <div id = 'modalMaterii' class='modal'>
                        <div class='modal-content'>
                            <span class = 'modal-close' onclick = 'openOrCloseModalMaterii()'>&times;</span>
                            <select class = 'materii' id = 'selectAddMaterii'>
                            <?php
                                $stmtMaterii = $connection->prepare("select * from materii");
                                $stmtMaterii->execute();
                                $resultMaterii = $stmtMaterii->get_result();
                                while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
                                    $idMaterie = $rowMaterii['id'];
                                    $numeMaterie = $rowMaterii['materie'];
                                    $codMaterie = $rowMaterii['cod'];
                                    echo "<option value = '$idMaterie'>".$numeMaterie." (".$codMaterie.")"."</option>";
                                }
                            ?>
                            </select>
                            <div class = 'submit-button-default' onclick = 'javascript:addMaterieInDOM()'>Adaugare</div>
                        </div>
                    </div>
                    <div id = 'materiiList'>
                        <?php
                            foreach($materii as $idMaterie){
                                $stmt = $connection->prepare("select * from materii where id = ?");
                                $stmt->bind_param("i",$idMaterie);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $data = mysqli_fetch_assoc($result);
                                $materie = $data['materie'];
                                $cod = $data['cod'];
                                echo "
                                <div class='list-item'>
                                    <input class='list-item-content' readonly='' type='hidden' name='materii[]' value='$idMaterie'>$materie ($cod)
                                    <div class='list-item-content'>
                                        <i class='fas fa-times' onclick='javascript:deleteMaterieFromDOM(this)'></i>
                                    </div>
                                </div>
                                ";
                            }
                        ?>
                        <i class='fas fa-plus' onclick = 'javascript:openOrCloseModalMaterii()' ></i>
                    </div>
                    <input class = "submit-button-default" type="submit" name="editareProf" value="Submit">
                </form>

                <?php elseif(isset($_GET['secretar'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['secretar'];
                        $stmt = $connection->prepare("delete from secretari where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    if(isset($_GET['recover'])){
                        $id = $_GET['secretar'];
                        $stmt = $connection->prepare("select * from secretari where id = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $nume = $row['nume'];
                            $prenume = $row['prenume'];
                            $cnp = $row['cnp'];
                            $parola = $nume.$prenume.substr($cnp,-6);
                            $parola = password_hash($parola,PASSWORD_DEFAULT);
                            $stmt = $connection->prepare("update secretari set parola = ? where id = ?");
                            $stmt->bind_param("si",$parola, $id);
                            try{
                                $stmt->execute();
                                header("Location: editaredate.php?succes&recover");
                                die();
                            }catch(exception $e){
                                header("Location: editaredate.php?fail&recover");
                                die();
                            }
                        } else {
                            echo "<h3 class = 'error-message'>Eroare.</h3>";
                        }
                    }
                    $id = $_GET['secretar'];
                    $stmt = $connection->prepare("select * from secretari where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $nume = $data['nume'];
                    $prenume = $data['prenume'];
                    $prenume2 = $data['prenume2'];
                    $cnp = $data['cnp'];
                    $email = $data['email'];
                    $nrtel = $data['nrtelefon'];
                    $judet = $data['judet'];
                    $localitate = $data['localitate'];
                    $strada = $data['strada'];
                    $numar = $data['numar'];
                    $bloc = $data['bloc'];
                    $scara = $data['scara'];
                    $etaj = $data['etaj'];
                    $apartament = $data['apartament'];
                    $cod_postal = $data['cod_postal'];
                    $cetatenie = $data['cetatenie'];
                    $nationalitate = $data['nationalitate'];
                    $email = $data['email'];
                ?>
                <form id="editareSecretar" name = "editareSecretar" onsubmit = "return validatePersonalInformation(this.id);" action="editaredate.php?secretar=<?=$_GET['secretar']?>" method="post">
                <h2>Adaugare secretar</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume' value = '<?=$nume?>'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume' value = '<?=$prenume?>'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2' value = '<?=$prenume2?>'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp' value = '<?=$cnp?>'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email' value = '<?=$email?>'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel' value = '<?=$nrtel?>'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet' value = '<?=$judet?>'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate' value = '<?=$localitate?>'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada' value = '<?=$strada?>'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input" value = '<?=$numar?>'>
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input" value = '<?=$bloc?>'>
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input" value = '<?=$scara?>'>
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input" value = '<?=$etaj?>'>
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input" value = '<?=$apartament?>'><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal' value = '<?=$cod_postal?>'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie' value = '<?=$cetatenie?>'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate' value = '<?=$nationalitate?>'><br>
                    <input class = "submit-button-default" type="submit" name="editareSecretar" value="Submit">
                </form>
                <?php elseif(isset($_GET['admin'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['admin'];
                        $stmt = $connection->prepare("delete from administratori where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    if(isset($_GET['recover'])){
                        $id = $_GET['admin'];
                        $stmt = $connection->prepare("select * from administratori where id = ?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            $nume = $row['nume'];
                            $prenume = $row['prenume'];
                            $cnp = $row['cnp'];
                            $parola = $nume.$prenume.substr($cnp,-6);
                            $parola = password_hash($parola,PASSWORD_DEFAULT);
                            $stmt = $connection->prepare("update administratori set parola = ? where id = ?");
                            $stmt->bind_param("si",$parola, $id);
                            try{
                                $stmt->execute();
                                header("Location: editaredate.php?succes&recover");
                                die();
                            }catch(exception $e){
                                header("Location: editaredate.php?fail&recover");
                                die();
                            }
                        } else {
                            echo "<h3 class = 'error-message'>Eroare.</h3>";
                        }
                    }
                    $id = $_GET['admin'];
                    $stmt = $connection->prepare("select * from administratori where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $nume = $data['nume'];
                    $prenume = $data['prenume'];
                    $prenume2 = $data['prenume2'];
                    $cnp = $data['cnp'];
                    $email = $data['email'];
                    $nrtel = $data['nrtelefon'];
                    $judet = $data['judet'];
                    $localitate = $data['localitate'];
                    $strada = $data['strada'];
                    $numar = $data['numar'];
                    $bloc = $data['bloc'];
                    $scara = $data['scara'];
                    $etaj = $data['etaj'];
                    $apartament = $data['apartament'];
                    $cod_postal = $data['cod_postal'];
                    $cetatenie = $data['cetatenie'];
                    $nationalitate = $data['nationalitate'];
                    $email = $data['email'];
                ?>
                <form id="editareAdmin" name = "editareAdmin" onsubmit = "return validatePersonalInformation(this.id);" action="editaredate.php?admin=<?=$_GET['admin']?>" method="post">
                <h2>Adaugare administrator</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume' value = '<?=$nume?>'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume' value = '<?=$prenume?>'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2' value = '<?=$prenume2?>'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp' value = '<?=$cnp?>'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email' value = '<?=$email?>'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel' value = '<?=$nrtel?>'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet' value = '<?=$judet?>'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate' value = '<?=$localitate?>'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada' value = '<?=$strada?>'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input" value = '<?=$numar?>'>
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input" value = '<?=$bloc?>'>
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input" value = '<?=$scara?>'>
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input" value = '<?=$etaj?>'>
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input" value = '<?=$apartament?>'><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal' value = '<?=$cod_postal?>'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie' value = '<?=$cetatenie?>'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate' value = '<?=$nationalitate?>'><br>
                    <input class = "submit-button-default" type="submit" name="editareAdmin" value="Submit">
                </form>
                <?php elseif(isset($_GET['clasa'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['clasa'];
                        $stmt = $connection->prepare("delete from clase where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    $id = $_GET['clasa'];
                    $stmt = $connection->prepare("select * from clase where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $clasa = $data['clasa'];
                    $literaInit = $data['litera'];
                    $diriginte = $data['diriginte'];
                ?>
                <form id="editareClasa" name = "editareClasa" action="editaredate.php?clasa=<?=$_GET['clasa']?>" method="post">
                <h2>Editare Clasa</h2>
                    <label for = "clasa">Clasa : </label>
                    <select name = "clasa" id = "clasa">
                        <?php
                            for($i = 5;$i<=12;$i++){
                                echo "<option value = '$i' ";
                                if($clasa == $i){
                                    echo "selected";
                                }
                                echo ">$i</option>";
                            }
                        ?>
                    </select><br>
                    <label for = "litera">Litera : </label>
                    <select name="litera" id="litera">
                        <?php
                            for($i = 65;$i<=90;$i++){
                                $litera = chr($i);
                                echo "<option value = '$litera' ";
                                if($litera == $literaInit){
                                    echo "selected";
                                }
                                echo ">$litera</option>";
                            }
                        ?>
                    </select><br>
                    <label for = "diriginte">Diriginte : </label>
                    <select name = "diriginte" id = "diriginte">
                        <?php 
                            $query = "select diriginte,id from clase";
                            $diriginti = array();
                            $result = mysqli_query($connection,$query);
                            while($row = mysqli_fetch_assoc($result)){
                                if($id == $row['id']){
                                    $diriginteClasa = $row['diriginte'];
                                    $stmtDiriginte = $connection->prepare("select * from profesori where id = ?");
                                    $stmtDiriginte->bind_param("i",$diriginteClasa);
                                    $stmtDiriginte->execute();
                                    $resultDiriginte = $stmtDiriginte->get_result();
                                    $dataDiriginte = mysqli_fetch_assoc($resultDiriginte);
                                    $numeDiriginte = $dataDiriginte['nume'];
                                    $prenumeDiriginte = $dataDiriginte['prenume'];
                                }
                                array_push($diriginti,$row['diriginte']);
                            }
                        ?>
                        <?php
                            $query = "select id, nume, prenume from profesori";
                            $result = mysqli_query($connection,$query);
                            echo "<option selected disabled>$numeDiriginte $prenumeDiriginte</option>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $numeprenume = $row['nume']." ".$row['prenume'];
                                if(array_search($id,$diriginti) === false){
                                    echo "<option value = '$id' ";
                                    if($id == $diriginte){
                                        echo "selected";
                                    }
                                    echo ">$numeprenume</option>";
                                }
                            }
                        ?>
                    </select>
                    <input class = "submit-button-default" type="submit" name="editareClasa" value="Submit">
                </form>
                <?php elseif(isset($_GET['materie'])): ?>
                <?php
                    if(isset($_GET['delete'])){
                        $id = $_GET['materie'];
                        $stmt = $connection->prepare("delete from materii where id = ?");
                        $stmt->bind_param("i",$id);
                        try{
                            $stmt->execute();
                            header("Location: editaredate.php?succes&delete");
                            die();
                        }catch(exception $e){
                            header("Location: editaredate.php?fail&delete");
                            die();
                        }
                    }
                    $id = $_GET['materie'];
                    $stmt = $connection->prepare("select * from materii where id = ?");
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = mysqli_fetch_assoc($result);
                    $materie = $data['materie'];
                    $cod = $data['cod'];
                    $descriere = $data['descriere'];
                ?>
                <form id="editareMaterie" name = "editareMaterie" onsubmit = "return validatePersonalInformation(this.id);" action="editaredate.php?materie=<?=$_GET['materie']?>" method="post">
                <h2>Adaugare Materie</h2>
                    <label for = "materie">Nume materie : </label>
                    <input type='text' name='materie' id='materie' value = '<?=$materie?>'><br>
                    <label for = "cod">Cod materie : </label>
                    <input type='text' name='cod' id='cod' value = '<?=$cod?>'><br>
                    <label for = "descriere">Descriere materie : </label>
                    <input type='text' name='descriere' id='descriere' value = '<?=$descriere?>'><br>
                    <input class = "submit-button-default" type="submit" name="editareMaterie" value="Submit">
                </form>
                <?php endif; ?>
            </article>
        </section>
        <?php include("footer.php")?>
    </body>
</html>