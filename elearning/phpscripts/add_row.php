<?php
    require("../mysql.php");
    if(isset($_POST['index'])){
        $index = $_POST['index'];
        $nr = $index + 1;
        $index = $index - 1;
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
            $cod = $rowMaterii['cod'];   
            echo "<option value = '$id'";
            echo ">".$materie." (".$cod.")"."</option>";
        }
        echo "</select>";
        echo "<input type='hidden' name = 'materii-index[]' class = 'materii-values' value = ''>";
        echo "</td>";
        echo "<td><select name='profesori[]' class ='profesori'>";
        echo "<option value = '0' disabled selected></option>";
        echo "</select>";
        echo "</td>";
        echo "<td><a class = 'asociere-delete-row' href = '#'><i class = 'fa fa-trash'></i></a></td>";
        echo "</tr>";
    }
    
?>