<!DOCTYPE html>
<html lang="ro" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
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
            if(isset($_SESSION['tipcont'])){
                if($_SESSION['tipcont'] == 4){
                    header("Location: profile.php?id=".$_SESSION['idcont']);
                    die();
                } else {
                    header("Location: controlpanel.php");
                    die();
                }
            } else {
                header("Location: login.php");
                die();
            }
        ?>
        <?php include("footer.php");?>
    </body>
</html>