<?php
    require("../mysql.php");
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $stmt = $connection->prepare("delete from profesori_clase where id = ?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        echo mysqli_error($connection);
    }
?>