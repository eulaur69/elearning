<?php
    require("../mysql.php");
    session_start();
    if(isset($_POST['clasa'])){
        $clasa = $_POST['clasa'];
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
    unset($_SESSION['clasa']);
    $_SESSION['clasa'] = $clasa;
?>