<?php
    require("../mysql.php");
    session_start();
    if(isset($_POST['clasa']) && isset($_POST['materie'])){
        $idcont = $_SESSION['idcont'];
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
        $clasa = $_POST['clasa'];
        $materie = $_POST['materie'];
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
                        echo "<td class = 'desktop-only'><a href = 'catalogabsente.php?addabs&id=$idElev'><i class='fas fa-plus'></i></a></td>";
                    }
            $stmtAbs = $connection->prepare("select * from absente where absente.elev = ? and materie = ? order by data");
            $stmtAbs->bind_param("ii",$idElev,$materie);
            $stmtAbs->execute();
            $resultAbs = $stmtAbs->get_result();
            while($rowAbs = mysqli_fetch_assoc($resultAbs)){
                $id = $rowAbs['id'];
                echo "<td class = 'desktop-only'>";
                echo $rowAbs['data'];
                if($rowAbs['motivata'] == 0){
                    if($secretar == true){
                        echo "<br>Nemotivat";
                    } else {
                        echo "<br><a href = 'catalogabsente.php?motivare&id=$id'>Motivare</a>";
                    }
                } else {
                    echo "<br>Motivat";
                }
                
                echo "</td>";
            }
            echo "<tr>";
        }
        echo "</table>";
    }
    unset($_SESSION['clasa']);
    $_SESSION['clasa'] = $clasa;
    unset($_SESSION['materie']);
    $_SESSION['materie'] = $materie;
?>