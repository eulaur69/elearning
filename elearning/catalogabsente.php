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
                $materie_stearsa = true;
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
                } else {
                    $idcont = $_SESSION['idcont'];
                    if($profesor == true){
                        $stmt = $connection->prepare("select materie from profesori_materii where profesor = ?");
                        $stmt->bind_param("i",$idcont);
                    } else {
                        $stmt = $connection->prepare("select distinct materie from profesori_materii");
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $materii_array = array();
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($materii_array,$row['materie']);
                    }
                }
                ?>
                <h1>Catalog absente</h1>
                <form id="catalog" name = "catalog" action="catalogabsente.php" method="post">
                <select name="claseEdit" id="claseEdit" onchange="javascript:populateMateriiSelect(this.value,<?php echo $idcont;?>,'abs')">
                        <?php
                            echo "<option selected disabled></option>";
                            if($profesor == false){
                                $query = "select distinct clasa from profesori_clase";
                            } else {
                                $query = "select distinct clasa from profesori_clase where profesor = $idcont";
                            }
                            $result = mysqli_query($connection,$query) or die("ERR1");
                            while($row = mysqli_fetch_assoc($result)){
                                $id = $row['clasa'];
                                $queryClassData = "select * from clase where id = $id";
                                $resultClassData = mysqli_query($connection,$queryClassData) or die("ERR2");
                                $classData = mysqli_fetch_assoc($resultClassData);
                                $clasa = $classData['clasa'];
                                $litera = $classData['litera'];
                                echo "<option value = '$id'";
                                if(isset($_SESSION['clasa']) && $_SESSION['clasa'] == $id){
                                    echo "selected";
                                }
                                echo">Clasa $clasa$litera</option>";
                            }
                            
                        ?>
                    </select><br>
                    <?php
                        if(isset($_SESSION['clasa'])){
                            $clasa = $_SESSION['clasa'];
                            $stmt = $connection->prepare("select diriginte from clase where id = ?");
                            $stmt->bind_param("i",$clasa);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($row = mysqli_fetch_assoc($result)){
                                if($idcont == $row['diriginte']){
                                    $diriginte = true;
                                } else {
                                    $diriginte = false;
                                }
                            }
                            echo "<select name = 'materieEdit' id = 'materieEdit' onchange = 'generateAbsencesTable($clasa,this.value)'>";
                            echo "<option selected disabled></option>";
                            if($profesor == false || $diriginte == true){
                                $query = "select materie from profesori_clase where clasa = $clasa";
                            } else {
                                $query = "select materie from profesori_clase where clasa = $clasa and profesor = $idcont";
                            }
                                $result = mysqli_query($connection,$query);
                                $materie_stearsa = true;
                                while($row = mysqli_fetch_assoc($result)){
                                    $id = $row['materie'];
                                    $querySubjectData = "select materie,cod from materii where id = $id";
                                    $resultSubjectData = mysqli_query($connection,$querySubjectData) or die("ERR2");
                                    $subjectData = mysqli_fetch_assoc($resultSubjectData);
                                    $materie = $subjectData['materie'];
                                    $cod = $subjectData['cod'];
                                    echo "<option value = '$id'";
                                    if(isset($_SESSION['materie']) && $_SESSION['materie'] == $id){
                                        echo "selected";
                                        $materie_stearsa = false;
                                    }
                                    echo ">".$materie." (".$cod.")"."</option>";
                                }
                            echo "</select>";
                        }
                    ?>
                    <?php
                        if($materie_stearsa == true){
                            unset($_SESSION['materie']);
                        }
                        if(isset($_SESSION['materie'])){
                            $materie = $_SESSION['materie'];
                        }
                    ?>
                    <?php
                        if(isset($_GET['motivare'])){
                            $id = $_GET['id'];
                            $stmt = $connection->prepare("update absente set motivata = 1 where id = ?");
                            $stmt->bind_param("i",$id);
                            $stmt->execute();
                            echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalogabsente.php'\" >";
                        }
                        if(isset($_GET['demotivare'])){
                            $id = $_GET['id'];
                            $stmt = $connection->prepare("update absente set motivata = 0 where id = ?");
                            $stmt->bind_param("i",$id);
                            $stmt->execute();
                            echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalogabsente.php'\" >";
                        }
                        if(isset($_GET['addabs'])){
                            $idElev = $_GET['id'];
                            $data = date("Y-m-d");
                            $stmt = $connection->prepare("select * from absente where data = ? and elev = ? and materie = ?");
                            $stmt->bind_param("sii",$data,$idElev,$materie);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if(mysqli_num_rows($result) > 0){
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalogabsente.php'\" >";
                            } else {
                                $stmt = $connection->prepare("insert into absente(data,elev,materie) values(?,?,?)");
                                $stmt->bind_param("sii",$data,$idElev,$materie);
                                $stmt->execute();
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalogabsente.php'\" >";
                            }
                            
                        }
                        if(isset($_POST["absSubmit"])){
                            $counter_data = 0;
                            $date = $_POST['data'];
                            $stmt = $connection->prepare("select id from elevi where clasa = ? order by nume,prenume");
                            $stmt->bind_param("i",$_SESSION['clasa']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = mysqli_fetch_assoc($result)){
                                $idElev = $row['id'];
                                $stmtAbs = $connection->prepare("select id from absente where elev = ? and materie = ? order by data, id");
                                $stmtAbs->bind_param("ii",$idElev,$_SESSION['materie']);
                                $stmtAbs->execute();
                                $resultAbs = $stmtAbs->get_result();
                                while($rowAbs = mysqli_fetch_assoc($resultAbs)){
                                    $data = $date[$counter_data];
                                    $counter_data++;
                                    $stmtUpdate = $connection->prepare("update absente set data = ? where id = ?");
                                    $stmtUpdate->bind_param("si",$data,$rowAbs['id']);
                                    $stmtUpdate->execute();
                                }
                            }
                            echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalogabsente.php'\" >";
                        }
                        if(isset($_GET['delabs'])){
                            $stmt = $connection->prepare("delete from absente where id = ?");
                            $stmt->bind_param("i",$_GET['id']);
                            $stmt->execute();
                            header("Location: catalogabsente.php");
                        }
                        if($action == "view" && isset($_SESSION['clasa']) && isset($_SESSION['materie'])){
                            echo "<div id = 'absenteContent'>";
                            echo "    
                                <table class = 'contentTable' id = 'absenteTable'>
                                    <tr>
                                        <th>#</th>
                                        <th>Nume si prenume</th>
                                        <th>Nr. abs.</th>
                                ";
                                echo "</tr>";
                            $stmt = $connection->prepare("select elevi.id as id ,elevi.nume as nume, elevi.prenume as prenume ,numarAbsente(id,?) as absente from elevi 
                            where elevi.clasa = ? 
                            order by elevi.nume asc, elevi.prenume asc");
                            $stmt->bind_param("ii",$materie,$clasa);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $nr = 0;
                            $colNr = 0;
                            echo "<form></form>";
                            while($row = mysqli_fetch_assoc($result)){
                                $nr = $nr  +1;
                                $colNr = 0;
                                $idElev = $row['id'];
                                echo "
                                    <tr>
                                        <td>".$nr."</td>
                                        <td>".$row['nume']." ".$row['prenume']."</td>
                                        <td>".$row['absente']."</td>";
                                    if($secretar == false && in_array($materie,$materii_array)){
                                        echo "<td><a href = 'catalogabsente.php?addabs&id=$idElev'><i class='fas fa-plus'></i></a></td>";
                                    }
                                $stmtAbs = $connection->prepare("select * from absente where absente.elev = ? and materie = ? order by data");
                                $stmtAbs->bind_param("ii",$idElev,$materie);
                                $stmtAbs->execute();
                                $resultAbs = $stmtAbs->get_result();
                                while($rowAbs = mysqli_fetch_assoc($resultAbs)){
                                    $id = $rowAbs['id'];
                                    echo "<td>";
                                    if($admin == true){
                                        echo "<br><input type='date' class = 'dateInput' name = 'data[]' value = '".$rowAbs['data']."'>";
                                    } else {
                                        echo $rowAbs['data'];
                                    }
                                    if($rowAbs['motivata'] == 0){
                                        if($secretar == true){
                                            echo "<br>Nemotivat";
                                        } else {
                                            echo "<br><a href = 'catalogabsente.php?motivare&id=$id'>Motivare</a>";
                                        }
                                    } else {
                                        if($admin == true){
                                            echo "<br><a href = 'catalogabsente.php?demotivare&id=$id'>Motivat</a>";
                                        } else {
                                            echo "<br>Motivat";
                                        }
                                    }
                                    if($admin == true){
                                        echo "<br><a href = 'catalogabsente.php?delabs&id=$id'><i class='fas fa-times'></i></a>";
                                    }
                                    echo "</td>";
                                }
                                echo "<tr>";
                            }
                            echo "</table>";
                            if($admin == true){
                                echo "<input class = 'submit-button-default' type='submit' name='absSubmit' value='Submit'>";
                            }
                            echo "</div>";
                        }
                            
                        
                        
                    ?>  
                    
                </form>
            </article>
        </section>
        <?php include("footer.php")?>
        
    </body>
</html>