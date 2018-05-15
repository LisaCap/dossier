<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////



//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">

    

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Template</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

</div><!-- /.container -->


<?php
include("inc/footer.inc.php");