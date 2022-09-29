<?php
    require("../mysql.php");
    if(isset($_POST['materie'])){
        echo "am intrat in script php";
        $idMaterie = $_POST['materie'];
        $idProf = $_POST['prof'];
        $stmt = $connection->prepare("delete from profesori_materii where profesor = ? and materie = ?");
        $stmt->bind_param("ii",$idProf,$idMaterie);
        $stmt->execute();
    }
?>