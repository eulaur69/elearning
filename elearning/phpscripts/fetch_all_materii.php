<?php
    require("../mysql.php");
    session_start();
    if(isset($_POST['clasa']) && isset($_POST['profesor']) && isset($_POST['type'])){
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
        $clasa = $_POST['clasa'];
        $idcont = $_POST['profesor'];
        $type = $_POST['type'];
        $stmt = $connection->prepare("select diriginte from clase where id = ?");
        $stmt->bind_param("i",$clasa);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = mysqli_fetch_assoc($result)){
            if($idcont == $row['diriginte']){
                $diriginte = true;
            } else {
                $diriginte = false;
            }
        }
        echo "<select name = 'materieEdit' id = 'materieEdit' ";
        if($type == "abs"){
            echo "onchange = 'generateAbsencesTable($clasa,this.value)'>";
        } else {
            echo "onchange = 'generateGradesTable($clasa,this.value)'>";
        }
        echo "<option selected disabled value = '0'></option>";
            if($profesor == false || $diriginte == true){
                $query = "select materie from profesori_clase where clasa = $clasa";
            } else {
                $query = "select materie from profesori_clase where clasa = $clasa and profesor = $idcont";
            }
            $result = mysqli_query($connection,$query);
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['materie'];
                $querySubjectData = "select materie,cod from materii where id = $id";
                $resultSubjectData = mysqli_query($connection,$querySubjectData) or die("ERR2");
                $subjectData = mysqli_fetch_assoc($resultSubjectData);
                $materie = $subjectData['materie'];
                $cod = $subjectData['cod'];
                echo "<option value = '$id'";
                if(isset($_GET['materie']) && $_GET['materie'] == $id){
                    echo "selected";
                }
                echo ">".$materie." (".$cod.")"."</option>";
            }
        echo "</select>";
       
    }
    //var_dump($_SESSION);
    unset($_SESSION['clasa']);
    $_SESSION['clasa'] = $clasa;
?>