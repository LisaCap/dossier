<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/back/init.inc.php");
///////////////////////////////////////////////////////

$id_salle = ""; // pour modification d'id emprunt

$titre = "";
$description = "";
$capacite = "";
$categorie = "";
$pays = "";
$ville = "";
$code_postal = "";
$adresse = "";

//SUPPRESSION SALLE/////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(isset($_GET["action"]) && $_GET["action"] == 'suppression')
{
    $salle_a_supprimer = $_GET['id_salle'];
    
    $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $suppression->bindParam(":id_salle", $salle_a_supprimer, PDO::PARAM_STR);
    $suppression->execute();
    
    $_GET['action'] = 'voir_salle'; // Pour que le tableau continue d'etre afficher même quand on a supprimé
    
}

//FIN SUPPRESSION SALLE/////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

///MODIFICATION SALLE ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    $salle_a_modifier = $_GET['id_salle'];
    
    $recup_info = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $recup_info->bindParam(":id_salle", $salle_a_modifier, PDO::PARAM_STR);
    $recup_info->execute();
    //ici on a recuperer les infos qui correspondent a l'id salle recuperer 
    
    $salle_actuelle = $recup_info->fetch(PDO::FETCH_ASSOC); //transformation en tableau array
    
    $id_salle = $salle_actuelle['id_salle']; // on place dans des variables ce que l'on recupere du tableau
    $titre = $salle_actuelle['titre'];
    $description = $salle_actuelle['description'];
    $capacite = $salle_actuelle['capacite'];
    $categorie = $salle_actuelle['categorie'];
    $pays = $salle_actuelle['pays'];
    $ville = $salle_actuelle['ville'];
    $code_postal = $salle_actuelle['code_postal'];
    $adresse = $salle_actuelle['adresse'];
    
}

///FIN MODIFICATION SALLE ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

//AJOUTER SALLE ////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["capacite"]) && isset($_POST["categorie"]) && isset($_POST["pays"]) && isset($_POST["ville"]) && isset($_POST["code_postal"]) && isset($_POST["adresse"]))
{    

    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $capacite = $_POST["capacite"];
    $categorie = $_POST["categorie"];
    $pays = $_POST["pays"];
    $ville = $_POST["ville"];
    $code_postal = $_POST["code_postal"];
    $adresse = $_POST["adresse"];

    $erreur = false; 
    
    //Vérifier que les champs de soient pas vides
    if( empty($titre) || empty($description) || empty($capacite) || empty($categorie) || empty($pays) || empty($ville) || empty($code_postal) || empty($adresse) )
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Merci de remplir tous les champs.</div>";
    }
    

    //Notre requete d'ajout de salle OU de modification////////////////////////////

    if($erreur == false)
    {
        
        $photo_bdd = '';
		
		// récupération de la photo
		if(!empty($_FILES['photo']['name']))
		{
			// mise en place du src
			$photo_bdd = 'img/' . $id_salle . $_FILES['photo']['name'];
			
			$chemin = RACINE_SERVEUR . $photo_bdd;
			// copy() est une fonction prédéfinie permettant de copier un fichier depuis un emplacement (1er argument) vers un emplacement cible (2eme argument)
			copy($_FILES['photo']['tmp_name'], $chemin);			
		}
        
        if(empty($id_salle)) // si id_salle est vide, c'est un ajout de salle
        {
            
		$ajouter_salle = $pdo->prepare("INSERT INTO salle (titre, description, capacite, categorie, pays, ville, code_postal, adresse, photo) VALUES (:titre, :description, :capacite, :categorie, :pays, :ville, :code_postal, :adresse, '$photo_bdd')");
            
        } else{ // C'est une modification de salle
            
            $id_salle = $_POST['id_salle']; // pour la modification 
            $photo_bdd = $_POST['photo_bdd'];
            
            $ajouter_salle = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, capacite = :capacite, categorie = :categorie, pays = :pays, ville = :ville, code_postal = :code_postal, adresse = :adresse, photo = '$photo_bdd' WHERE id_salle = :id_salle");
            //le WHERE pour ne pas modifier tous les produits de la table...
            
            $ajouter_salle->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);// pour que le nombre de UPDATE CORRESPONDE
        }
        
        $ajouter_salle->bindParam(":titre", $titre, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":description", $description, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":capacite", $capacite, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":pays", $pays, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":ville", $ville, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":code_postal", $code_postal, PDO::PARAM_STR);
        $ajouter_salle->bindParam(":adresse", $adresse, PDO::PARAM_STR);

        $ajouter_salle->execute();
    }
    
    
    
///////////////////////////////////////////////////////////////////////////////
}//////////////// FIN D'AJOUT SALLE ///////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////



//Recupération de la table Salle dans la BDD
$recuperation_salle = $pdo->query("SELECT * FROM salle");

//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/back/header.inc.php");
include("inc/back/nav.inc.php");

/*echo "<pre>"; var_dump($_SERVER); echo "</pre>";*/

?>

<!---MON WRAPPER DE LA PAGE--->
<div id="page-wrapper">

    <!--TITRE---------------------------------------------------------------->
    <div class="container-fluid">

        <div class="col-sm-12 starter-template">
            <h1> Bienvenue sur le BackOffice !</h1> 
            <h2> Gestion des salles</h2>
            <?= $message; // affiche le message de init.inc.php?>        
        </div>

    </div>
    <!--FIN DE TITRE-------------------------------------------------------->

    <div class="container-fluid">
        <!--BOUTONS------------------------------------------------------------->
        <div class="row">

                <div class="col-sm-12 text-center">
                    <a href="?action=ajouter_salle" class="btn btn-default">Ajouter une salle</a>
                    <a href="?action=voir_salle" class="btn btn-default">Voir les salles</a>
                    <hr>
                </div>
        </div>
        <!--FIN DE BOUTONS----------------------------------------------------->
    </div> <!--FIN DE CONTAINER-FUID--->
    
    
<!--FORMULAIRE AJOUTER SALLE--------------------------------------------------->

<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter_salle' || $_GET['action'] == 'modification')) // FORMULAIRE AJOUTER OU MODIFIER EMPRUNT
{?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form method="post" action="" enctype="multipart/form-data">
                
                <!--On rajoute un champ caché (type hidden) pour voir l'Id_salle lors d'une modification-->
	            <input type="hidden" name="id_salle" value="<?php echo $id_salle; ?>" >
	            <input type="hidden" name="photo_bdd" value="<?php echo $photo_bdd; ?>" >
	            
	            <!------------------------------------------------------------------------>
                
                <!--TITRE------------------------------------------------------->
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?=$titre?>">
                </div>
                <!--FIN TITRE--------------------------------------------------->
                
                <!--DESCRIPTION------------------------------------------------->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control rows='3'" id="description" name="description"><?=$description?></textarea>
                </div>
                <!--FIN DESCRITPION--------------------------------------------->
                
                <!--PHOTO------------------------------------------------------->
                <div class="form-group">				
					<label for="photo">Photo</label>
					<input type="file" class="form-control" id="photo" name="photo">
				</div>
                <!--FIN PHOTO--------------------------------------------------->
                
                <!--CAPACITE---------------------------------------------------->
                <div class="form-group">
                    <label for="capacite">Capacité</label>
                    <input type="text" class="form-control" id="capacite" name="capacite" value="<?=$capacite?>">
                </div>
                <!--FIN CAPACITE------------------------------------------------>
                
                <!--CATEGORIE--------------------------------------------------->
                <label for="categorie">Catégorie</label>
                    <select class="form-control" name="categorie" id="categorie">
                       
                        <option value="formation">Formation</option>    
                        <option value="reunion" <?php if($categorie == 'reunion'){ echo "selected";} ?> > Réunion </option>
                        <option value="bureaux" <?php if($categorie == 'bureaux'){ echo "selected";} ?> > Bureaux </option>
                        
                    </select>
                <br>
                <!--FIN CATEGORIE----------------------------------------------->
                
                <!--PAYS-------------------------------------------------------->
                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" class="form-control" id="pays" name="pays" value="<?=$pays?>">
                </div>
                <!--FIN PAYS---------------------------------------------------->
                
                <!--VILLE------------------------------------------------------->
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" value="<?=$ville?>">
                </div>
                <!--FIN VILLE--------------------------------------------------->
                
                <!--ADRESSE----------------------------------------------------->
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?=$adresse?>">
                </div>
                <!--FIN ADRESSE------------------------------------------------->
                
                <!--CODE POSTAL------------------------------------------------->
                <div class="form-group">
                    <label for="code_postal">Code Postal</label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?=$code_postal?>">
                </div>
                <!--FIN CODE POSTAL--------------------------------------------->

                <!--BOUTON D'ENVOI---------------------------------------------->
                <button type="submit" class="btn btn-success col-sm-12" name="ajouter" > Ajouter la salle</button>

            </form>

        </div><!--FIN DE COL-->

    </div><!--FIN DE DIV ROW-->
</div> <!--FIN DE CONTAINER-FUID--->

<?php } // FIN DE IF FORMULAIRE AJOUTER EMPRUNT?>

<!--FIN FORMULAIRE AJOUTER SALLE------------------------------------->
  
<!--TABLEAU------------------------------------------------------------>
<?php if(isset($_GET['action']) && $_GET['action'] == 'voir_salle') // VOIR TABLEAU
{?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <?php
                    // Récupération du nombre de colonne, pour afficher les noms dans des <th>
                    $nb_col = $recuperation_salle->columnCount();
                    ?>

                    <!--Création des <th> avec le nom des colonnes-->
                    <tr>
                        <?php
                        for($i = 0; $i < $nb_col; $i++)
                        {
                            $colonne_en_cours = $recuperation_salle->getColumnMeta($i);
                            echo '<th style="padding:5px;">' . $colonne_en_cours['name'] . '</th>';

                        }
                        ?>
                        <th style="padding:5px;"> Action </th>
                        
                    </tr>

                    <!--Création des <td> avec les valeurs correspondant aux colonnes-->

                    <?php

                    while($ligne_en_cours = $recuperation_salle->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";

                        $compteur = 1;

                        foreach($ligne_en_cours AS $indice=>$valeur)
                        {
                            if($indice == 'photo')
                            {
                                echo "<td style='padding:5px;'>
                                <img class='img-responsive tableau' src='". URL . $valeur . "'></td>";
                                
                            } else{
                                
                                echo "<td style='padding:5px;'>" . $valeur . "</td>";
                            }

                            $compteur++;

                            if($compteur > $nb_col)
                            {
                                echo "
                                <td>
                                    <a href='?action=voir_fiche_produit&id_salle=" . $ligne_en_cours['id_salle'] . "'><span class='glyphicon glyphicon-search'></span><a>
                                    <a href='?action=modification&id_salle=" . $ligne_en_cours['id_salle'] . "'><span class='glyphicon glyphicon-pencil'></span><a>
                                    <a href='?action=suppression&id_salle=" . $ligne_en_cours['id_salle'] . "'><span class='glyphicon glyphicon-trash'></span><a>
                                </td>";
                                
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
    </div><!-- FIN DE CONTAINER FLUID -->
<?php } // FIN DE IF VOIR TABLEAU?>
<!--FIN DE TABLEAU----------------------------------------------------->

   
   
    
</div><!--FIN DE DIV PAGE WRAPPER--->

</div>
<!-- /#wrapper PAGE + NAV -->


<?php
include("inc/back/footer.inc.php");