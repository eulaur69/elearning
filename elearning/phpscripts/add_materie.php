<?php
    require("../mysql.php");
    if(isset($_POST['materie'])){
        $idMaterie = $_POST['materie'];
        $idProf = $_POST['prof'];
        $stmt = $connection->prepare("select * from profesori_materii where materie = ? and profesor = ?");
        $stmt->bind_param("ii",$idMaterie,$idProf);
        $stmt->execute();
        $resultCheck = $stmt->get_result();
        if(mysqli_num_rows($resultCheck) == 0){
            $stmt = $connection->prepare("insert into profesori_materii(profesor,materie) values (?,?)");
            $stmt->bind_param("ii",$idProf,$idMaterie);
            $stmt->execute();
            $stmt = $connection->prepare("select materie from materii where id = ?");
            $stmt->bind_param("i",$idMaterie);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $numeMaterie = $row['materie'];
            $codMaterie = $row['cod'];
            echo "<div class = 'list-item-content'>".$numeMaterie." (".$codMaterie.")"."</div>";
            echo "<div class = 'list-item-content'><i class='fas fa-times' onclick = 'javascript:openOrCloseModalMaterii()' ></i></div>";
        }
    }
    
?>