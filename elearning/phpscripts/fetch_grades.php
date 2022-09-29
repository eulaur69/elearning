<?php
    require("../mysql.php");
    session_start();
    if(isset($_POST['materie']) && isset($_POST['clasa'])){
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
        $materie = $_POST['materie'];
        $clasa = $_POST['clasa'];
        $idcont = $_SESSION['idcont'];
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
                    //echo $predaMaterie;
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
            echo "<td class = 'desktop-only'>";
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
    
    }
    unset($_SESSION['clasa']);
    $_SESSION['clasa'] = $clasa;
    unset($_SESSION['materie']);
    $_SESSION['materie'] = $materie;
?>