<?php
session_start();

//On inclus le fichier de classe.
require_once 'include/XMLEngine.php';

/* On va utiliser une variable de session pour la langue de l'utilisateur.
*  Si la variable n'existe pas on la crée. */
if ( !isset( $_SESSION['Language'] ) )
{
	$_SESSION['Language'] = 'fr'; //Français par défaut.
}

//Création de l'instance de notre classe avec le fichier XML à ouvrir.
require_once 'condb.php';

/* Grâce à la fonction __get(), cette ligne suffit à afficher le contenu
*  de l'élément XMLDATA d'identifiant 'webPageName' en langue française.*/

echo $xml->webPageName;
?>