<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

$id_livre = "";
$id_abonne = "";
$date_sortie = "";
$date_rendu = "";

$id_emprunt = ""; // pour modification d'id emprunt

//SUPPRESSION EMPRUNT///////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(isset($_GET["action"]) && $_GET["action"] == 'suppression')
{
    $emprunt_a_supprimer = $_GET['id_emprunt'];
    
    $suppression = $pdo->prepare("DELETE FROM emprunt WHERE id_emprunt = :id_emprunt");
    $suppression->bindParam(":id_emprunt", $emprunt_a_supprimer, PDO::PARAM_STR);
    $suppression->execute();
    
    $_GET['action'] = 'voir_emprunt'; // Pour que le tableau continue d'etre afficher même quand on a supprimé
    
}

//FIN SUPPRESSION EMPRUNT///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


//Recupération de la table Emprunt dans la BDD
$recuperation_emprunt = $pdo->query("SELECT * FROM emprunt");

// Récupération des id_livre et titre de la table livre pour le formulaire d'ajout d'emprunt
$select_id_livre = $pdo->query("SELECT id_livre, titre FROM livre");

// Récupération des id_abonne et prenom de la table abonne pour le formulaire d'ajout d'emprunt
$select_id_abonne = $pdo->query("SELECT id_abonne, prenom FROM abonne");



///MODIFICATION EMPRUNT //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    $emprunt_a_modifier = $_GET['id_emprunt'];
    
    $recup_info = $pdo->prepare("SELECT * FROM emprunt WHERE id_emprunt = :id_emprunt");
    $recup_info->bindParam(":id_emprunt", $emprunt_a_modifier, PDO::PARAM_STR);
    $recup_info->execute();
    //ici on a recuperer les infos qui correspondent a l'id produit recuperer 
    
    $emprunt_actuel = $recup_info->fetch(PDO::FETCH_ASSOC); //transformation en tableau array
    
    $id_emprunt = $emprunt_actuel['id_emprunt']; // on place dans des variables ce que l'on recupere du tableau
    $id_livre = $emprunt_actuel['id_livre'];
    $id_abonne = $emprunt_actuel['id_abonne'];
    $date_sortie = $emprunt_actuel['date_sortie'];
    $date_rendu = $emprunt_actuel['date_rendu'];

}

///FIN MODIFICATION EMPRUNT //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////


//AJOUTER EMPRUNT///////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["id_livre"]) && isset($_POST["id_abonne"]) && isset($_POST["date_sortie"]) && isset($_POST["ajouter_emprunt"]))
{
    $id_livre = $_POST["id_livre"];
    $id_abonne = $_POST["id_abonne"];
    $date_sortie = $_POST["date_sortie"];
    

    
    if(!empty($_POST["date_rendu"]))
    {
        $date_rendu = $_POST["date_rendu"];
    } else
    {
        $date_rendu = null ;
    }

    $erreur = false;
    
    if(isset($_GET['action']) && $_GET['action'] == 'ajouter_emprunt') // Cette vérification se fait UNIQUEMENT lors d'un ajout d'emprunt
    {
    //verifier que le livre est disponible //////////////////////////
    $verif_dispo_livre = $pdo->prepare("SELECT id_livre, date_rendu FROM emprunt WHERE date_rendu IS null AND id_livre= :id_livre ");
    $verif_dispo_livre->bindParam(':id_livre', $id_livre, PDO::PARAM_STR);
    $verif_dispo_livre->execute();

    if($verif_dispo_livre->rowCount() > 0)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Livre indisponible.</div>";
    }
    ////////////////////////////////////////////////////////////////
    }

   
    
    //verifier que la date de sortie est entrée //////////////////////////

    if(empty($_POST["date_sortie"]))
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Date de sortie à renseigner obligatoirement.</div>";
    }
    ////////////////////////////////////////////////////////////////

    //Notre requete d'ajout de produit////////////////////////////

    if($erreur == false)
    {
        
        if(empty($id_emprunt)) // si id_emprunt est vide, c'est un ajout d'emprunt
        {
            
		$ajouter_emprunt = $pdo->prepare("INSERT INTO emprunt (id_livre, id_abonne, date_sortie, date_rendu) VALUES (:id_livre, :id_abonne, :date_sortie, :date_rendu)");
            
        } else{ // C'est une modification d'emprunt
            
            $id_emprunt = $_POST['id_emprunt']; // pour la modification 
            
            $ajouter_emprunt = $pdo->prepare("UPDATE emprunt SET id_livre = :id_livre, id_abonne = :id_abonne, date_sortie = :date_sortie, date_rendu = :date_rendu WHERE id_emprunt = :id_emprunt");
            //le WHERE pour ne pas modifier tous les produits de la table...
            
            $ajouter_emprunt->bindParam(":id_emprunt", $id_emprunt, PDO::PARAM_STR);// pour que le nombre de UPDATE CORRESPONDE
        }
        
        $ajouter_emprunt->bindParam(":id_livre", $id_livre, PDO::PARAM_STR);
        $ajouter_emprunt->bindParam(":id_abonne", $id_abonne, PDO::PARAM_STR);
        $ajouter_emprunt->bindParam(":date_sortie", $date_sortie, PDO::PARAM_STR);
        $ajouter_emprunt->bindParam(":date_rendu", $date_rendu, PDO::PARAM_STR);

        $ajouter_emprunt->execute();
    }
    
    
    
///////////////////////////////////////////////////////////////////////////////
}//////////////// FIN D'AJOUT EMPRUNT//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<!--TITRE---------------------------------------------------------------->
<div class="container">

    <div class="col-sm-12 starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Bienvenue à la Bibliothèque !</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

</div>
<!--FIN DE TITRE-------------------------------------------------------->

<!--BOUTONS------------------------------------------------------------->
<div class="row">
    
		<div class="col-sm-12 text-center">
			<a href="?action=ajouter_emprunt" class="btn btn-warning">Ajouter un emprunt</a>
			<a href="?action=voir_emprunt" class="btn btn-primary">Voir les emprunts</a>
			<hr>
		</div>
</div>
<!--FIN DE BOUTONS----------------------------------------------------->

<!--FORMULAIRE AJOUTER EMPRUNT----------------------------------------->

<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter_emprunt' || $_GET['action'] == 'modification')) // FORMULAIRE AJOUTER OU MODIFIER EMPRUNT
{?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form method="post" action="">
                
                <!--On rajoute un champ caché (type hidden) pour voir l'Id_emprunt lors d'une modification-->
	            <input type="hidden" name="id_emprunt" value="<?php echo $id_emprunt; ?>" >
	            <!------------------------------------------------------------------------>
                
                <!--ID_LIVRE---------------------------------------------------->
                <label for="id_livre">Id_livre</label>
                    <select class="form-control" name="id_livre" id="id_livre">
                        <?php
                            //recupération des id_abonne existant
                            while($select_id_livre_en_cours = $select_id_livre->fetch(PDO::FETCH_ASSOC))
                            {?>
                                 <option value="<?php echo $select_id_livre_en_cours['id_livre']; ?>" <?php if($id_livre == $select_id_livre_en_cours['id_livre']){ echo "selected";} ?> > <?php echo $select_id_livre_en_cours['id_livre'] . " - " . $select_id_livre_en_cours['titre']; ?> </option>

                        <?php } ?>
                    </select>
                <br>
                <!--FIN ID_LIVRE------------------------------------------------>
                
                <!--ID_ABONNE--------------------------------------------------->
                <label for="id_abonne">Id_abonne</label>
                    <select class="form-control" name="id_abonne" id="id_abonne">
                        <?php
                            //recupération des id_abonne existant
                            while($select_id_abonne_en_cours = $select_id_abonne->fetch(PDO::FETCH_ASSOC))
                            {?>
                                 <option value="<?php echo $select_id_abonne_en_cours['id_abonne']; ?>" <?php if($id_abonne == $select_id_abonne_en_cours['id_abonne']){ echo "selected";} ?> > <?php echo $select_id_abonne_en_cours['id_abonne'] . " - " . $select_id_abonne_en_cours['prenom']; ?> </option>

                        <?php } ?>
                    </select>
                <br>
                <!--FIN ID_ABONNE----------------------------------------------->
                
                
                <!--DATE_SORTIE------------------------------------------------->
                <div class="form-group">
                    <label for="date_sortie">Date de sortie</label>
                    <input type="date" class="form-control" id="date_sortie" name="date_sortie" value="<?=$date_sortie?>">
                </div>
                <!--FIN DATE_SORTIE--------------------------------------------->
                
                <!--DATE_RENDU-------------------------------------------------->
                <div class="form-group">
                    <label for="date_rendu">Date de rendu</label>
                    <input type="date" class="form-control" id="date_rendu" name="date_rendu" value="<?=$date_rendu?>">
                </div>
                <!--FIN DATE_RENDU---------------------------------------------->

                <!--BOUTON D'ENVOI---------------------------------------------->
                <button type="submit" class="btn btn-success col-sm-12" name="ajouter_emprunt" > Ajouter l'emprunt</button>

            </form>

        </div><!--FIN DE COL-->

    </div><!--FIN DE DIV ROW-->
</div> <!--FIN DE CONTAINER-->

<?php } // FIN DE IF FORMULAIRE AJOUTER EMPRUNT?>

<!--FIN FORMULAIRE AJOUTER EMPRUNT------------------------------------->

<!--TABLEAU------------------------------------------------------------>
<?php if(isset($_GET['action']) && $_GET['action'] == 'voir_emprunt') // VOIR TABLEAU
{?>
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <?php
                    // Récupération du nombre de colonne, pour afficher les noms dans des <th>
                    $nb_col = $recuperation_emprunt->columnCount();
                    ?>

                    <!--Création des <th> avec le nom des colonnes-->
                    <tr>
                        <?php
                        for($i = 0; $i < $nb_col; $i++)
                        {
                            $colonne_en_cours = $recuperation_emprunt->getColumnMeta($i);
                            echo '<th style="padding:5px;">' . $colonne_en_cours['name'] . '</th>';

                        }
                        ?>
                        <th style="padding:5px;"> Modification </th>
                        <th style="padding:5px;"> Suppression </th>
                    </tr>

                    <!--Création des <td> avec les valeurs correspondant aux colonnes-->

                    <?php

                    while($ligne_en_cours = $recuperation_emprunt->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";

                        $compteur = 1;

                        foreach($ligne_en_cours AS $valeur)
                        {
                            echo "<td style='padding:5px;'>" . $valeur . "</td>";

                            $compteur++;

                            if($compteur > $nb_col)
                            {
                                echo "<td><a href='?action=modification&id_emprunt=" . $ligne_en_cours['id_emprunt'] . "'><span class='glyphicon glyphicon-pencil'></span><a></td>";
                                echo "<td><a href='?action=suppression&id_emprunt=" . $ligne_en_cours['id_emprunt'] . "'><span class='glyphicon glyphicon-trash'></span><a></td>";
                                
                                /*echo "<pre>"; var_dump($ligne_en_cours); echo "</pre>";*/

                                $compteur = 1;

                            }//fin de if

                        }//fin de foreach
                        echo "</tr>";

                    }//fin de while

                    ?>
                </table>
            </div>
        </div><!--FIN DE DIV CLASS ROW -->
    </div><!-- FIN DE CONTAINER -->
<?php } // FIN DE IF VOIR TABLEAU?>
<!--FIN DE TABLEAU----------------------------------------------------->

<!--FOOTER------------------------------------------------------------->
<?php
include("inc/footer.inc.php");