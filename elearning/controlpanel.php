<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panou de control</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src = "script/script.js"></script>
    </head>
    <body>
        <?php 
            require("mysql.php");
            session_start();
            include("header.php");
        ?>
        <section id = "content">
            <article>
                <?php
                if(!isset($_SESSION['idcont']) || $_SESSION['tipcont'] == 4){
                    echo "<h2 class='error-message' >ACCES NEAUTORIZAT</h2>";
                    echo "</article></section>";
                    include("footer.php");
                    die();
                } 
                ?>
                <h1>Panou de control</h1>
                <form id="controlpanel" name = "controlpanel" action="controlpanel.php" method="get">
                    <ul>
                        <?php
                            if($_SESSION['tipcont'] < 3){
                                echo '<li><a href = "adaugaredate.php">Adaugare date</a></li>';
                                echo '<li><a href = "editaredate.php">';
                                if($_SESSION['tipcont'] == 2){
                                    echo "Vizualizare ";
                                }else echo "Editare ";
                                echo 'date</a></li>';
                            }
                        ?>
                        <li><a href = "catalognote.php">Catalog note</a></li>
                        <li><a href = "catalogabsente.php">Catalog absente</a></li>
                        <?php
                            if($_SESSION['tipcont'] < 3){
                                echo '<li><a href = "asociereprofclasa.php">Asociere profesor-clasa</a></li>';
                            }
                            if($_SESSION['tipcont'] < 4){
                                echo '<li><a href = "situatienote.php" target="_blank">Situatie note</a></li>';
                                echo '<li><a href = "situatieabsente.php" target="_blank"> Situatie absente</a></li>';
                            }
                        ?>
                        <li><a href = "schimbareparola.php">Schimbare parola</a></li>
                    </ul>
                </form>
            </article>
            <?php
                if(isset($_GET['increment'])){
                    $query = "delete from elevi where clasa = 12";
                    $result = mysqli_query($connection,$query) or die ("Eroare la delete");
                        
                   
                    $query = "select numarCatalog(id) as nr from elevi where id != 0;";
                    $resultNr = mysqli_query($connection,$query) or die ("Eroare la select");
                        
                    $query = "update elevi set elevi.clasa = elevi.clasa + 1 where (elevi.clasa = 9 or elevi.clasa = 10 or
                    elevi.clasa = 11)";
                    $result = mysqli_query($connection,$query) or die ("Eroare la update");
                        

                    $query = "select id from elevi where id != 0";
                    $result = mysqli_query($connection,$query) or die ("Eroare la select");
                   
                    $counter = 9999;
                    while($row = mysqli_fetch_assoc($result)){
                        //var_dump($row);
                        $query = "update elevi set elevi.id = ".$counter." where elevi.id = ".$row['id'];
                        //echo $query;
                        $result1 = mysqli_query($connection,$query) or die ("Eroare la update 999");
                        $counter = $counter + 1;
                    }
                    
                    $counter = 0;

                    $query = "select id, nume, prenume , clasa, grupa from elevi where id != 0";
                    $result = mysqli_query($connection,$query) or die ("Eroare la select");
                    $rowNr = mysqli_fetch_assoc($resultNr);
                    while($row = mysqli_fetch_assoc($result)){
                        $newId = $row['clasa'].$rowNr['nr'].$row['grupa'];
                        $query = "update elevi set elevi.id = ".$newId." where elevi.id = ".$row['id'];
                        //echo $query;
                        $result1 = mysqli_query($connection,$query) or die (mysqli_error($connection));
                        $rowNr = mysqli_fetch_assoc($resultNr);
                    }
                    
                 
                    //die("da");
                    echo " <meta http-equiv='refresh' content='0;url=controlpanel.php?incrementdone'>";
                }
                
            ?>
        </section>
        <?php include("footer.php")?>
    </body>
</html>