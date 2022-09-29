<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Asociere profesor-clasa</title>
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
                if(isset($_POST['asociere'])){
                    //var_dump($_POST);
                    $clasa = $_SESSION['clasa'];
                    $stmt = $connection->prepare("select distinct materie from profesori_clase where clasa = ?");
                    $stmt->bind_param("i",$clasa);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $materii_db = [];
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($materii_db,$row['materie']); 
                    }
                    $materii = $_POST['materii-index'];
                    //var_dump($materii);
                    if(isset($_POST['profesori'])){
                        $profesori = $_POST['profesori'];
                    }
                    for($i = 0;$i<count($materii);$i++){
                            $materieId = $materii[$i];
                            $stmtCheck = $connection->prepare("select * from profesori_clase where materie = ? and clasa = ?");
                            $stmtCheck->bind_param("ii",$materii[$i],$clasa);
                            $stmtCheck->execute();
                            $resultCheck = $stmtCheck->get_result();
                            if(mysqli_num_rows($resultCheck) != 0){
                                $row = mysqli_fetch_assoc($resultCheck);
                                $id = $row['id'];
                                $stmt = $connection->prepare("update profesori_clase set profesor = ? where id = ?");
                                $stmt->bind_param("ii",$profesori[$i],$id);
                                $stmt->execute();
                                if (($key = array_search($row['materie'], $materii_db)) !== false) {
                                    unset($materii_db[$key]);
                                }
                            } else {
                                $stmt = $connection->prepare("insert into profesori_clase(profesor,materie,clasa) values(?,?,?)");
                                $stmt->bind_param("iii",$profesori[$i],$materii[$i],$clasa);
                                $stmt->execute();
                            }
                        //}
                    
                    }
                    foreach($materii_db as $materie){
                        $stmt = $connection->prepare("delete from profesori_clase where materie = ? and clasa = ?");
                        $stmt->bind_param("ii",$materie,$clasa);
                        $stmt->execute();
                    }
                }
                ?>
                <h1>Asociere profesor-clasa</h1>
                <form id="asociere" name = "asociere" action="asociereprofclasa.php" method="post">
                <select name="claseEdit" id="claseEdit" onchange="generateTabelAsociere(this.value)">
                        <?php
                            echo "<option selected disabled></option>";
                            $query = "select distinct clasa from profesori_clase";
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
                        if($action == "view" && isset($_SESSION['clasa'])){
                            $clasa = $_SESSION['clasa'];
                            echo "<div id = 'asociereContent'>";
                            echo "    
                                <table class = 'contentTable' id = 'asociereTable'>
                                    <tr>
                                        <th>#</th>
                                        <th>Materia</th>
                                        <th>Profesor</th>
                                ";
                                echo "</tr>";
                            $query = "select id,profesor,materie from profesori_clase where clasa = $clasa";
                            $result = mysqli_query($connection,$query) or die("Eroare la select");
                            $nr = 0;
                            $colNr = 0;
                            while($row = mysqli_fetch_assoc($result)){
                                $nr = $nr  +1;
                                $index = $nr - 1;
                                $colNr = 0;
                                $idProfesor = $row['profesor'];
                                $idMaterie = $row['materie'];
                                $materieSelected = $idMaterie;
                                $idRow = $row['id'];
                                echo "
                                    <tr>
                                        <td>".$nr."</td>
                                ";
                                $queryMaterii = "select * from materii order by materie";
                                $resultMaterii = mysqli_query($connection,$queryMaterii);
                                echo "<td><select name='materii[]' class = 'materii-select' onchange = fetch_profesori(this.value,$index)>";
                                echo "<option value = '0' disabled selected></option>";
                                while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
                                    $id = $rowMaterii['id'];
                                    $materie = $rowMaterii['materie']; 
                                    $descriere = $rowMaterii['descriere'];
                                    $cod = $rowMaterii['cod'];    
                                    echo "<option title = '$descriere' value = '$id'";
                                    if($id == $idMaterie){
                                        echo "selected";
                                        $materieSelected = $id;
                                        //$materiiArray.append($idMaterie);
                                    }
                                    echo ">".$materie." (".$cod.")"."</option>";
                                }
                                echo "</select>";
                                echo "<input type='hidden' name = 'materii-index[]' class = 'materii-values' value = '$idMaterie'>";
                                echo "</td>";


                                $queryProf = "select profesori_materii.profesor, profesori.nume, profesori.prenume from profesori_materii
                                join profesori on profesori_materii.profesor = profesori.id
                                where materie = $idMaterie";
                                $resultProf = mysqli_query($connection,$queryProf);
                                echo "<td><select name='profesori[]' class ='profesori'>";
                                echo "<option value = '0' disabled selected></option>";
                                while($rowProf = mysqli_fetch_assoc($resultProf)){
                                    $id = $rowProf['profesor'];
                                    $nume = $rowProf['nume'];
                                    $prenume = $rowProf['prenume'];
                                    echo "<option value = '$id'";
                                    if($id == $idProfesor){
                                        echo "selected";
                                    }
                                    echo ">$nume $prenume</option>";
                                }
                                echo "</select>";
                                echo "<td><a class = 'asociere-delete-row' href = '#'><i class = 'fa fa-trash'></i></a></td>";
                                echo "</td>";
                            
                                echo "</tr>";
                            }
                            $nr = $nr  +1;
                            $index = $nr - 1;
                            echo "
                                <tr>
                                    <td class = 'asociere-add-button'><a href = '#' onclick = 'add_row($nr)'><i class = 'fas fa-plus'></i></a></td>
                            ";
                            echo "</tr>";
                            echo "</table>";
                            echo "<input class = 'submit-button-default' type='submit' name='asociere' value='Submit' onclick = 'return checkInputs();'>";
                        } 
                    ?>  
                    </div>
                </form>
            </article>
        </section>
        <?php include("footer.php")?>
    </body>
</html>