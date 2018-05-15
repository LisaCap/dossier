<?php

//connexion à la BDD

$arg1 = "mysql:host=localhost;dbname=lokisalle";
$arg2 = "root";
$arg3 = "";
$arg4 = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

$pdo = new PDO($arg1, $arg2, $arg3, $arg4);

session_start();

// déclaration d'une variable permettant d'afficher des messages utilisateurs.
$message = "";

//appel du fichier contenant les fonctions de notre projets
include("function.inc.php");

//déclaration d'une constant contenant racine site (chemin absolu depuis la racine serveur).
define("URL", "http://localhost/php/tp_4_lokisalle/");

//déclaration d'une constant contenant le chemin complet permettant de copier les photos du formulaire "ajouter un produit".
define("RACINE_SERVEUR", $_SERVER['DOCUMENT_ROOT'] . '/php/tp_4_lokisalle/');