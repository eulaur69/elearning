
<div id = "mobile-menu" class = "mobile-only">
    <button id = "menu-close-button"><i class="fa fa-close"></i></button>
    <nav>
        <ul>
            <li><a href = "index.php">Home</a></li>
            <li><a href = "categorii/lectii.php?clasa=9">Clasa a 9-a</a></li>
            <li><a href = "categorii/lectii.php?clasa=10">Clasa a 10-a</a></li>
            <li><a href = "categorii/lectii.php?clasa=11">Clasa a 11-a</a></li>
            <li><a href = "categorii/lectii.php?clasa=12">Clasa a 12-a</a></li>
            <li><a href = "categorii/lectii.php?clasa=13">Pregatire CD</a></li>
            <li><a href = "categorii/lectii.php?clasa=14">Pregatire BAC</a></li>
            <li><a href = "cautare.php">Cautare</a></li>
            <?php
                //echo "<li><a href = 'controlpanel.php'>Panou de control</a></li>";
                if(isset($_SESSION['idcont'])){
                    $tipcont = $_SESSION['tipcont'];
                    if($_SESSION['tipcont'] != 4){
                        echo "<li><a href = 'controlpanel.php'>Panou de control</a></li>";
                    } else echo "<li><a href = 'profile.php?id=".$_SESSION["idcont"]."'><i id = 'user-icon' class='fa fa-user'></i> Profil TIP = $tipcont</a></li>";
                    echo "<li><a href = 'login.php?logout=1'>Log out</a></li>";
                } else 
                    echo "<li><a href ='login.php'>Log In</a></li>";
            ?>
        </ul>
    </nav>
</div>
<header>
    <img id = "menu-button" class = "mobile-only" src = "img/menu_button.svg">
    <?php
            echo "<a href = '";
            if(isset($_SESSION['tipcont'])){
                if($_SESSION['tipcont'] == 4){
                    echo "profile.php?id=".$_SESSION['idcont']."'>";
                } else {
                    echo "controlpanel.php'>";
                }
            } else echo "login.php'>";
        ?>
        <img id = 'sigla' class = 'mobile-only' src = 'img/sigla.svg'>
        <?php
            echo "</a>";
        ?>
    <div id = "menu-container" class = "desktop-only">
        <?php
            echo "<a href = '";
            if(isset($_SESSION['tipcont'])){
                if($_SESSION['tipcont'] == 4){
                    echo "profile.php?id=".$_SESSION['idcont']."'>";
                } else {
                    echo "controlpanel.php'>";
                }
            } else echo "login.php'>";
        ?>
        <img id = 'sigla' src = 'img/sigla.svg'>
        <?php
            echo "</a>";
        ?>
        <div id = "profile-menu-container">
            <?php
                if(isset($_SESSION['idcont'])){
                    echo "<button id = 'log-in-button'><i id = 'user-icon' class='fa fa-user'></i></button>";
                } else 
                    echo "<button id = 'log-in-button-default' onclick = \"location='login.php'\">Log In</button>";
            ?>
            <div id = "profile-menu">
                <nav>
                    <ul>
                        <?php
                            if(isset($_SESSION['idcont'])){
                                if($_SESSION['tipcont'] != 4){
                                    echo "<li><a href = 'controlpanel.php'>Panou de control</a></li>";
                                } else echo "<li><a href = 'profile.php?id=".$_SESSION["idcont"]."'>Profil</a></li>";
                            }
                        ?>
                        <li><a href = "login.php?logout=1">Log out</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
        
</header>
<img id = "banner" class = "mobile-only" src = "img/banner.jpg">