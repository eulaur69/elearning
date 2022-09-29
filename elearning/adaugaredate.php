<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Adaugare date</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">        
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src = "script/jquery.js"></script>
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
                <h1>Adaugare date</h1>
                <?php
                    if(isset($_POST['adaugareElev'])){
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
                        $parola = $nume.$prenume.substr($cnp,-6);
                        $parola = password_hash($parola,PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("insert into elevi(nume,prenume,prenume2,cnp,email,nrtelefon,judet,localitate,
                                                                        strada,numar,bloc,scara,etaj,apartament,cod_postal,
                                                                        cetatenie,nationalitate,clasa,tip_cont,parola) 
                                                                  values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bind_param("sssssisssissiiissiis",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$clasa,$tip_cont,$parola);
                        $stmt->execute(); 
                        echo "<h2 class = 'succes-message'>Elev adaugat cu succes!</h2>";
                    }
                    if(isset($_POST['adaugareProf'])){
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
                        $stmt = $connection->prepare("insert into profesori(nume,prenume,prenume2,cnp,email,nrtelefon,judet,localitate,
                                                                        strada,numar,bloc,scara,etaj,apartament,cod_postal,
                                                                        cetatenie,nationalitate,tip_cont,parola) 
                                                                  values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bind_param("sssssisssissiiissis",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$parola);
                        $stmt->execute();
                        $materii = $_POST['materii'];
                        $last_inserted_id = $connection->insert_id;
                        foreach($materii as $materie){
                            $stmt = $connection->prepare("insert into profesori_materii(profesor,materie) values(?,?)");
                            $stmt->bind_param("ii",$last_inserted_id,$materie);
                            $stmt->execute();
                        }
                        echo "<h2 class = 'succes-message'>Profesor adaugat cu succes!</h2>";
                    }
                    if(isset($_POST['adaugareSecretar'])){
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
                        $stmt = $connection->prepare("insert into secretari(nume,prenume,prenume2,cnp,email,nrtelefon,judet,localitate,
                                                                        strada,numar,bloc,scara,etaj,apartament,cod_postal,
                                                                        cetatenie,nationalitate,tip_cont,parola) 
                                                                  values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bind_param("sssssisssissiiissis",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$parola);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Secretar adaugat cu succes!</h2>";
                    }
                    if(isset($_POST['adaugareAdmin'])){
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
                        $stmt = $connection->prepare("insert into administratori(nume,prenume,prenume2,cnp,email,nrtelefon,judet,localitate,
                                                                        strada,numar,bloc,scara,etaj,apartament,cod_postal,
                                                                        cetatenie,nationalitate,tip_cont,parola) 
                                                                  values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bind_param("sssssisssissiiissis",$nume,$prenume,$prenume2,$cnp,$email,$nrtel,$judet,$localitate,
                        $strada,$numar,$bloc,$scara,$etaj,$apartament,$cod_postal,
                        $cetatenie,$nationalitate,$tip_cont,$parola);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Administrator adaugat cu succes!</h2>";
                    }
                    if(isset($_POST['adaugareClasa'])){
                        $clasa = $_POST['clasa'];
                        $litera = $_POST['litera'];
                        $diriginte = $_POST['diriginte'];
                        $stmt = $connection->prepare("insert into clase(clasa,litera,diriginte) values(?,?,?)");
                        $stmt->bind_param("isi",$clasa,$litera,$diriginte);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Clasa adaugat cu succes!</h2>";
                    }
                    if(isset($_POST['adaugareMaterie'])){
                        $materie = $_POST['materie'];
                        $cod = $_POST['cod'];
                        $descriere = $_POST['descriere'];
                        $stmt = $connection->prepare("insert into materii(materie,cod,descriere) values(?,?,?)");
                        $stmt->bind_param("sss",$materie,$cod,$descriere);
                        $stmt->execute();
                        echo "<h2 class = 'succes-message'>Materie adaugat cu succes!</h2>";
                    }
                ?>
                <div id = "add-menu">
                    <div id = "add-menu-elevi" class = "add-menu-item add-menu-first-item" onclick = "showAddMenuItem('adaugareElev')">Elev</div>
                    <div id = "add-menu-profesor" class = "add-menu-item" onclick = "showAddMenuItem('adaugareProf')">Profesor</div>
                    <?php
                    if($admin == true){
                        echo "
                        <div id = 'add-menu-secretar' class = 'add-menu-item' onclick = 'showAddMenuItem(\"adaugareSecretar\")'>Secretar</div>
                        <div id = 'add-menu-admin' class = 'add-menu-item' onclick = 'showAddMenuItem(\"adaugareAdmin\")'>Admin</div>
                        ";
                    }
                    ?>
                    <div id = "add-menu-clasa" class = "add-menu-item" onclick = "showAddMenuItem('adaugareClasa')">Clasa</div>
                    <div id = "add-menu-materie" class = "add-menu-item add-menu-last-item" onclick = "showAddMenuItem('adaugareMaterie')">Materie</div>
                </div>
                
                <form id="adaugareElev" class = "add-form" name = "adaugareElev" action="adaugaredate.php?added" onsubmit = "return validatePersonalInformation(this.id);" method="post">
                <h2>Adaugare elev</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input">
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input">
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input">
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input">
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input"><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate'><br>
                    <label for = "clasa" id = "clase-add-label">Clasa* : </label>
                    <select name="clasa" id="clasa">
                        <?php
                            $query = "select * from clase order by clasa, litera";
                            $result = mysqli_query($connection,$query);
                            echo "<option selected disabled></option>";
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $clasa = $row['clasa'];
                                $litera = $row['litera'];
                                echo "<option value = '$id'>$clasa$litera</option>";
                            }
                        ?>
                    </select><br>
                    <input class = "submit-button-default" type="submit" name="adaugareElev" value="Submit">
                </form>
                
                <form id="adaugareProf" class = "add-form initially-hidden" name = "adaugareProf" onsubmit = "return validatePersonalInformation(this.id);" action="adaugaredate.php?added" method="post">
                <h2>Adaugare profesor</h2>
                    <label for = "nume">Nume de familie* : </label>
                    <input type='text' name='nume' id='nume'><br>
                    <label for = "prenume">Primul prenume* : </label>
                    <input type='text' name='prenume' id='prenume'><br>
                    <label for = "prenume2">Al doilea prenume : </label>
                    <input type='text' name='prenume2' id='prenume2'><br>
                    <label for = "cnp">CNP* : </label>
                    <input type='text' name='cnp' id='cnp'><br>
                    <label for = "email">Email* : </label>
                    <input type='text' name='email' id='email'><br>
                    <label for = "nrtel">Numar telefon* : </label>
                    <input type='number' name='nrtel' id='nrtel'><br>
                    <label for = "judet">Judet* : </label>
                    <input type='text' name='judet' id='judet'><br>
                    <label for = "localitate">Localitate* : </label>
                    <input type='text' name='localitate' id='localitate'><br>
                    <label for = "strada">Strada* : </label>
                    <input type='text' name='strada' id='strada'><br>
                    <label for = "numar">Nr.*</label>
                    <input type='number' name='numar' id='numar' class = "small-input">
                    <label for = "bloc">Bl.</label>
                    <input type='text' name='bloc' id='bloc' class = "small-input">
                    <label for = "scara">Sc.</label>
                    <input type='text' name='scara' id='scara' class = "small-input">
                    <label for = "etaj">Et.</label>
                    <input type='number' name='etaj' id='etaj' class = "small-input">
                    <label for = "apartament">Ap.</label>
                    <input type='number' name='apartament' id='apartament' class = "small-input"><br>
                    <label for = "cod_postal">Cod postal* : </label>
                    <input type='number' name='cod_postal' id='cod_postal'><br>
                    <label for = "cetatenie">Cetatenie* : </label>
                    <input type='text' name='cetatenie' id='cetatenie'><br>
                    <label for = "nationalitate">Nationalitate* : </label>
                    <input type='text' name='nationalitate' id='nationalitate'><br>
                    <label for = "Materii" id = "clase-add-label">Materii : </label>
                    <div id = 'modalMaterii' class='modal'>
                        <div class='modal-content'>
                            <span class = 'modal-close' onclick = 'openOrCloseModalMaterii()'>&times;</span>
                            <select class = 'materiiSelect mobile-select' id = 'selectAddMaterii'>
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
                            <div class = 'submit-button-default modal-button' onclick = 'javascript:addMaterieInDOM()'>Adaugare</div>
                        </div>
                    </div>
                    <div id = 'materiiList'>
                        <i class='fas fa-plus' onclick = 'javascript:openOrCloseModalMaterii()' ></i>
                    </div>
                    <input class = "submit-button-default" type="submit" name="adaugareProf" value="Submit">
                </form>

                <?php if($admin == true): ?>
                    <form id="adaugareSecretar" class = "add-form initially-hidden" name = "adaugareSecretar" onsubmit = "return validatePersonalInformation(this.id);" action="adaugaredate.php?added" method="post">
                    <h2>Adaugare secretar</h2>
                        <label for = "nume">Nume de familie* : </label>
                        <input type='text' name='nume' id='nume'><br>
                        <label for = "prenume">Primul prenume* : </label>
                        <input type='text' name='prenume' id='prenume'><br>
                        <label for = "prenume2">Al doilea prenume : </label>
                        <input type='text' name='prenume2' id='prenume2'><br>
                        <label for = "cnp">CNP* : </label>
                        <input type='text' name='cnp' id='cnp'><br>
                        <label for = "email">Email* : </label>
                        <input type='text' name='email' id='email'><br>
                        <label for = "nrtel">Numar telefon* : </label>
                        <input type='number' name='nrtel' id='nrtel'><br>
                        <label for = "judet">Judet* : </label>
                        <input type='text' name='judet' id='judet'><br>
                        <label for = "localitate">Localitate* : </label>
                        <input type='text' name='localitate' id='localitate'><br>
                        <label for = "strada">Strada* : </label>
                        <input type='text' name='strada' id='strada'><br>
                        <label for = "numar">Nr.*</label>
                        <input type='number' name='numar' id='numar' class = "small-input">
                        <label for = "bloc">Bl.</label>
                        <input type='text' name='bloc' id='bloc' class = "small-input">
                        <label for = "scara">Sc.</label>
                        <input type='text' name='scara' id='scara' class = "small-input">
                        <label for = "etaj">Et.</label>
                        <input type='number' name='etaj' id='etaj' class = "small-input">
                        <label for = "apartament">Ap.</label>
                        <input type='number' name='apartament' id='apartament' class = "small-input"><br>
                        <label for = "cod_postal">Cod postal* : </label>
                        <input type='number' name='cod_postal' id='cod_postal'><br>
                        <label for = "cetatenie">Cetatenie* : </label>
                        <input type='text' name='cetatenie' id='cetatenie'><br>
                        <label for = "nationalitate">Nationalitate* : </label>
                        <input type='text' name='nationalitate' id='nationalitate'><br>
                        <input class = "submit-button-default" type="submit" name="adaugareSecretar" value="Submit">
                    </form>
                    <form id="adaugareAdmin" class = "add-form initially-hidden" name = "adaugareAdmin" onsubmit = "return validatePersonalInformation(this.id);" action="adaugaredate.php?added" method="post">
                    <h2>Adaugare administrator</h2>
                    <label for = "nume">Nume de familie* : </label>
                        <input type='text' name='nume' id='nume'><br>
                        <label for = "prenume">Primul prenume* : </label>
                        <input type='text' name='prenume' id='prenume'><br>
                        <label for = "prenume2">Al doilea prenume : </label>
                        <input type='text' name='prenume2' id='prenume2'><br>
                        <label for = "cnp">CNP* : </label>
                        <input type='text' name='cnp' id='cnp'><br>
                        <label for = "email">Email* : </label>
                        <input type='text' name='email' id='email'><br>
                        <label for = "nrtel">Numar telefon* : </label>
                        <input type='number' name='nrtel' id='nrtel'><br>
                        <label for = "judet">Judet* : </label>
                        <input type='text' name='judet' id='judet'><br>
                        <label for = "localitate">Localitate* : </label>
                        <input type='text' name='localitate' id='localitate'><br>
                        <label for = "strada">Strada* : </label>
                        <input type='text' name='strada' id='strada'><br>
                        <label for = "numar">Nr.*</label>
                        <input type='number' name='numar' id='numar' class = "small-input">
                        <label for = "bloc">Bl.</label>
                        <input type='text' name='bloc' id='bloc' class = "small-input">
                        <label for = "scara">Sc.</label>
                        <input type='text' name='scara' id='scara' class = "small-input">
                        <label for = "etaj">Et.</label>
                        <input type='number' name='etaj' id='etaj' class = "small-input">
                        <label for = "apartament">Ap.</label>
                        <input type='number' name='apartament' id='apartament' class = "small-input"><br>
                        <label for = "cod_postal">Cod postal* : </label>
                        <input type='number' name='cod_postal' id='cod_postal'><br>
                        <label for = "cetatenie">Cetatenie* : </label>
                        <input type='text' name='cetatenie' id='cetatenie'><br>
                        <label for = "nationalitate">Nationalitate* : </label>
                        <input type='text' name='nationalitate' id='nationalitate'><br>
                        <input class = "submit-button-default" type="submit" name="adaugareAdmin" value="Submit">
                    </form>
                <?php endif; ?>
                
                
                <form id="adaugareClasa" class = "add-form initially-hidden" name = "adaugareClasa" action="adaugaredate.php?added" method="post">
                <h2>Adaugare Clasa</h2>
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
                                echo "<option value = '$litera'>$litera</option>";
                            }
                        ?>
                    </select><br>
                    <label for = "diriginte">Diriginte : </label>
                    <select name = "diriginte">
                        <?php 
                            $query = "select diriginte from clase";
                            $diriginti = array();
                            $result = mysqli_query($connection,$query);
                            while($row = mysqli_fetch_assoc($result)){
                                array_push($diriginti,$row['diriginte']);
                            }
                            
                        ?>
                        <?php
                            $query = "select id, nume, prenume from profesori";
                            $result = mysqli_query($connection,$query);
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['id'];
                                $numeprenume = $row['nume']." ".$row['prenume'];
                                if(array_search($id,$diriginti) === false){
                                    echo "<option value = '$id'>$numeprenume</option>";
                                }
                                
                            }
                        ?>
                    </select>
                    <input class = "submit-button-default" type="submit" name="adaugareClasa" value="Submit">
                </form>
               
                <form id="adaugareMaterie" class = "add-form initially-hidden" name = "adaugareMaterie" onsubmit = "return validatePersonalInformation(this.id);" action="adaugaredate.php?added" method="post">
                <h2>Adaugare Materie</h2>
                    <label for = "materie">Nume materie : </label>
                    <input type='text' name='materie' id='materie'><br>
                    <label for = "cod">Cod materie : </label>
                    <input type='text' name='cod' id='cod'><br>
                    <label for = "descriere">Descriere materie : </label>
                    <input type='text' name='descriere' id='descriere'><br>
                    <input class = "submit-button-default" type="submit" name="adaugareMaterie" value="Submit">
                </form>
            </article>
        </section>
        <?php include("footer.php")?>
    </body>
</html>