<?php
    require("../mysql.php");
    if(isset($_POST['materie'])){
        $materie = $_POST['materie'];
        $query = "select profesori.nume, profesori.prenume,profesori.id from profesori_materii
                  join profesori on profesori_materii.profesor = profesori.id
                  where materie = $materie";
        $result = mysqli_query($connection,$query);
        echo "<option value = '0' disabled selected></option>";
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['id'];
            $nume = $row['nume'];
            $prenume = $row['prenume'];
            echo "<option value = '$id'>$nume $prenume</option>";
        }
    }
    exit;
?>