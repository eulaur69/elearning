<?php
    require("../mysql.php");
    if(isset($_POST['materie'])){
        $idMaterie = $_POST['materie'];
        $stmt = $connection->prepare("select * from materii where id = ?");
        $stmt->bind_param("i",$idMaterie);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = mysqli_fetch_assoc($result)){
            $numeMaterie = $row['materie'];
            $codMaterie = $row['cod'];
        }
        echo "<input class = 'list-item-content' readonly type = 'hidden' name = 'materii[]' value = '$idMaterie'>".$numeMaterie." (".$codMaterie.")"."</input>";
        echo "<div class = 'list-item-content'><i class='fas fa-times' onclick = 'javascript:deleteMaterieFromDOM(this)' ></i></div>";
    }
?>