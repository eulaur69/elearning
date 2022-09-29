<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src = "script/jquery.js"></script>
        <script src = "script/functions.js"></script>
        <script src = "script/script.js"></script>
    </head>
    <body>
        <?php 
            require("mysql.php");
            session_start();
            $loggedout = 0;
            if(isset($_GET['logout'])){
                if($_GET['logout'] == 1){
                    session_destroy();
                    echo "<meta http-equiv=\"refresh\" content=\"0; URL='login.php?logout=0'\" >";
                }
            }
            include("header.php");
        ?>
        
        <section id = "content">
            <article>
                <h1>Login</h1>
                <div id = "login-menu">
                    <div id = "login-menu-elevi" class = "login-menu-item login-menu-first-item" onclick = "showLoginMenuItem('loginElev')">Elev</div>
                    <div id = "login-menu-profesor" class = "login-menu-item" onclick = "showLoginMenuItem('loginProfesor')">Profesor</div>
                    <div id = "login-menu-secretar" class = "login-menu-item" onclick = "showLoginMenuItem('loginSecretar')">Secretar</div>
                    <div id = "login-menu-admin" class = "login-menu-item login-menu-last-item" onclick = "showLoginMenuItem('loginAdmin')">Admin</div>
                </div>
                <?php
                    if(isset($_GET['logout'])){
                        if($_GET['logout'] == 0){
                            echo "<p>Ati fost deconectat de la cont</p>";
                        }
                    }
                ?>
                <form id="loginElev" class = "login-form" name = "loginElev" action="login.php" method="post">
                    <h2>Login elev</h2>    
                    <label for = "email">Email : </label><br>
                    <input type='text' name='email' id='email'><br>
                    <label for = "parola">Parola : </label><br>
                    <input type='password' name='parola' id='parola'><br>
                    <input class = "submit-button-default" type="submit" name="loginElev" value="Submit">
                </form>
                <form id="loginProfesor" class = "login-form initially-hidden" name = "loginProfesor" action="login.php" method="post">
                    <h2>Login profesor</h2>
                    <label for = "email">Email : </label><br>
                    <input type='text' name='email' id='email'><br>
                    <label for = "parola">Parola : </label><br>
                    <input type='password' name='parola' id='parola'><br>
                    <input class = "submit-button-default" type="submit" name="loginProfesor" value="Submit">
                </form>
                <form id="loginSecretar" class = "login-form initially-hidden" name = "loginSecretar" action="login.php" method="post">
                    <h2>Login secretar</h2>
                    <label for = "email">Email : </label><br>
                    <input type='text' name='email' id='email'><br>
                    <label for = "parola">Parola : </label><br>
                    <input type='password' name='parola' id='parola'><br>
                    <input class = "submit-button-default" type="submit" name="loginSecretar" value="Submit">
                </form>
                <form id="loginAdmin" class = "login-form initially-hidden" name = "loginAdmin" action="login.php" method="post">
                    <h2>Login admin</h2>
                    <label for = "email">Email : </label><br>
                    <input type='text' name='email' id='email'><br>
                    <label for = "parola">Parola : </label><br>
                    <input type='password' name='parola' id='parola'><br>
                    <input class = "submit-button-default" type="submit" name="loginAdmin" value="Submit">
                </form>
                <?php
                    //elev
                    if(isset($_POST["loginElev"])){
                        $email = $_POST['email'];
                        $password = $_POST['parola'];
                        $statement = $connection->prepare("select id,parola from elevi where email = ?");
                        $statement->bind_param("s",$email);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            if(password_verify($password,$row['parola'])){
                                $tipcont = 4;
                                $_SESSION['tipcont'] = $tipcont;
                                $_SESSION['idcont'] = $row['id'];
                                $idcont = $row['id'];
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='profile.php?id=$idcont'\" >";
                            } else echo "<p>Parola gresita!</p>";
                        } else echo "<p>Utilizatorul nu exista!</p>";
                    } else if(isset($_POST["loginProfesor"])){
                        //profesor
                        $email = $_POST['email'];
                        $password = $_POST['parola'];
                        $statement = $connection->prepare("select id,parola from profesori where email = ?");
                        $statement->bind_param("s",$email);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            //echo "PASS ".$row['pass'];
                            if(password_verify($password,$row['parola'])){
                                $tipcont = 3;
                                $_SESSION['tipcont'] = $tipcont;
                                $_SESSION['idcont'] = $row['id'];
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='controlpanel.php'\" >";
                            } else echo "<p>Parola gresita!</p>";
                        } else echo "<p>Utilizatorul nu exista!</p>";
                    //secretar
                    } else if(isset($_POST["loginSecretar"])){
                        $email = $_POST['email'];
                        $password = $_POST['parola'];
                        $statement = $connection->prepare("select id,parola from secretari where email = ?");
                        $statement->bind_param("s",$email);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            //echo "PASS ".$row['pass'];
                            if(password_verify($password,$row['parola'])){
                                $tipcont = 2;
                                $_SESSION['tipcont'] = $tipcont;
                                $_SESSION['idcont'] = $row['id'];
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='controlpanel.php'\" >";
                            } else echo "<p>Parola gresita!</p>";
                        } else echo "<p>Utilizatorul nu exista!</p>";
                    //admin
                    } else if(isset($_POST["loginAdmin"])){
                        $email = $_POST['email'];
                        $password = $_POST['parola'];
                        $statement = $connection->prepare("select id,parola from administratori where email = ?");
                        $statement->bind_param("s",$email);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                            //echo "PASS ".$row['pass'];
                            if(password_verify($password,$row['parola'])){
                                $tipcont = 1;
                                $_SESSION['tipcont'] = $tipcont;
                                $_SESSION['idcont'] = $row['id'];
                                echo "<meta http-equiv=\"refresh\" content=\"0; URL='controlpanel.php'\" >";
                            } else echo "<p>Parola gresita!</p>";
                        } else echo "<p>Utilizatorul nu exista!</p>";
                        //echo password_hash("123",PASSWORD_DEFAULT);
                    }
                ?>  
            </article>
        </section>
        <?php include("footer.php")?>
    </body>
</html>