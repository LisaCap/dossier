<?php

// fonction pour savoir si l'utilisateur est connecté
function utilisateur_est_connecte()
{
	if(!empty($_SESSION['membre']))
	{
		// si l'indice membre dans SESSION n'est pas vide alors forcement l'utilisateur est cpassé par connexion et s'est connecté
		return true;
	}
	return false;
}

// fonction pour savoir si l'utilisateur est connecté et a le statut administrateur
function utilisateur_est_admin()
{
	if(utilisateur_est_connecte() && $_SESSION['membre']['statut'] == 1)
	{
		return true;
	}
	return false;
}

