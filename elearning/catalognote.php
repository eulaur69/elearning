<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catalog note</title>
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
                }
                ?>
                <h1>Catalog note</h1>
                <form id="catalog" name = "catalog" action="catalognote.php" method="post">
                    <select name="claseEdit" id="claseEdit" onchange="javascript:populateMateriiSelect(this.value,<?php echo $idcont?>,'note')">
                        <?php
                            echo "<option selected disabled></option>";
                            if($profesor == false){
                                $stmt = $connection->prepare("select distinct clasa from profesori_clase");
                                $stmt->execute();
                            } else {
                                $stmt = $connection->prepare("select distinct clasa from profesori_clase where profesor = ?");
                                $stmt->bind_param("i",$idcont);
                                $stmt->execute();
                            }
                            $result = $stmt->get_result();
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
                            echo "<select name = 'materieEdit' id = 'materieEdit' onchange = 'generateGradesTable($clasa,this.value)'>";
                            echo "<option selected disabled></option>";
                                if($profesor == false || $diriginte == true){
                                    $query = "select materie from profesori_clase where clasa = $clasa";
                                } else {
                                    $query = "select materie from profesori_clase where clasa = $clasa and profesor = $idcont";
                                }
                                $result = mysqli_query($connection,$query);
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
                        if(isset($_POST["noteSubmit"])){
                            $counter_nota = 0;
                            $note = $_POST['note'];
                            $date = $_POST['data'];
                            $stmt = $connection->prepare("select id from elevi where clasa = ? order by nume,prenume");
                            $stmt->bind_param("i",$_SESSION['clasa']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = mysqli_fetch_assoc($result)){
                                $idElev = $row['id'];
                                $stmtNote = $connection->prepare("select id from note where elev = ? and materie = ? order by data, id");
                                $stmtNote->bind_param("ii",$idElev,$_SESSION['materie']);
                                $stmtNote->execute();
                                $resultNote = $stmtNote->get_result();
                                while($rowNote = mysqli_fetch_assoc($resultNote)){
                                    $nota = $note[$counter_nota];
                                    $data = $date[$counter_nota];
                                    $counter_nota++;
                                    $stmtUpdate = $connection->prepare("update note set nota = ?, data = ? where id = ?");
                                    $stmtUpdate->bind_param("isi",$nota,$data,$rowNote['id']);
                                    $stmtUpdate->execute();
                                }
                            }
                            echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalognote.php'\" >";
                        }
                        if(isset($_POST['addNotaSubmit'])){
                            $idElev = $_GET['id'];
                            $data = date("Y-m-d");
                            $nota = $_POST['nota'];
                            if($nota >= 1 && $nota <= 10){
                                $stmt = $connection->prepare("insert into note(data,elev,nota,materie) values(?,?,?,?)");
                                $stmt->bind_param("siii",$data,$idElev,$nota,$materie);
                                $stmt->execute();
                            }
                            echo "<meta http-equiv=\"refresh\" content=\"0; URL='catalognote.php'\" >";
   
                        }
                        if(isset($_GET['delnota'])){
                            $id = $_GET['id'];
                            $stmt = $connection->prepare("delete from note where id = ?");
                            $stmt->bind_param("i",$id);
                            $stmt->execute();
                            header("Location: catalognote.php");
                        }
                        if($action == "view" && isset($_SESSION['clasa']) && isset($_SESSION['materie'])){
                            echo "<div id = 'noteContent'>";
                            echo "    
                                <table class = 'contentTable' id = 'noteTable'>
                                    <tr>
                                        <th>#</th>
                                        <th>Nume si prenume</th>
                                        <th>Media</th>
                                ";
                                echo "</tr>";
                            $stmt = $connection->prepare("select elevi.id as id ,elevi.nume as nume, elevi.prenume as prenume ,calculMedie(id,?) as medie from elevi 
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
                                        <td>".number_format($row['medie'], 2)."</td>";
                                if($secretar == false){
                                    if($profesor == true){
                                        $stmtMateriePredata = $connection->prepare("select * from profesori_materii where profesor = ?");
                                        $stmtMateriePredata->bind_param("i",$idcont);
                                        $stmtMateriePredata->execute();
                                        $resultMateriePredata = $stmtMateriePredata->get_result();
                                        $predaMaterie = false;
                                        while($rowMateriePredata = mysqli_fetch_assoc($resultMateriePredata)){
                                            if($rowMateriePredata['materie'] == $materie){
                                                $predaMaterie = true;
                                                break;
                                            }
                                        }
                                    }
                                    if($admin == true || $predaMaterie == true){
                                        echo"
                                        <td><a href = '#'>
                                            <i id = 'modalBtn$idElev' class='fas fa-plus'></i></a>
                                            <div id='modalCard$idElev' class='modal'>
                                                <div class='modal-content'>
                                                    <span id='closeModal$idElev' class = 'modal-close'>&times;</span>
                                                    <form id='addNota' name = 'addNota' action='catalognote.php?id=$idElev' method='post'>
                                                        <label for='nota'>Nota : </label>
                                                        <input type='number' class = 'noteInput noteInputModal' name = 'nota'><br>
                                                        <input id = 'submit-button-default' type='submit' name='addNotaSubmit' value='Submit'>
                                                    </form>
                                                </div>

                                            </div>
                                        </td>
                                ";
                                    }
                                    
                                }
                                $stmtNote = $connection->prepare("select * from note where note.elev = ? and materie = ? order by data, note.id");
                                $stmtNote->bind_param("ii",$idElev,$materie);
                                $stmtNote->execute();
                                $resultNote = $stmtNote->get_result();
                                while($rowNote = mysqli_fetch_assoc($resultNote)){
                                    $id = $rowNote['id'];
                                    echo "<td>";
                                    echo "Nota ";
                                    if($admin == true){
                                        echo "<input type='number' class = 'noteInput' name = 'note[]' value = '".$rowNote['nota']."'>";
                                    } else {
                                        echo $rowNote['nota'];
                                    }
                                    if($admin == true){
                                        echo "<br><input type='date' class = 'dateInput' name = 'data[]' value = '".$rowNote['data']."'>";
                                    } else {
                                        echo "<br>".$rowNote['data'];
                                    }
                                    if($admin == true){
                                        echo "<br><a href = 'catalognote.php?delnota&id=$id'><i class='fas fa-times'></i></a>";
                                    }
                                    echo "</td>";
                                }
                                echo "<tr>";
                            }
                                
                            echo "</table>";
                            if($admin == true){
                                echo "<input class = 'submit-button-default' type='submit' name='noteSubmit' value='Submit'>";
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
