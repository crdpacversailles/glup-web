<?php
/*
 * Created on 24 janv. 2011
 *
 */
require_once 'inc/autoload.php';
if(!Securite :: nettoyerPost()) die();

$id = Securite ::$_CLEAN['nom_fichier_glup'];
$cheminContenu='files/'.$id.'.glup';

$fichier = fopen($cheminContenu, 'r');
header('Content-Type: application/octet-stream'); 
header('Content-Disposition: inline; filename='.$id.'.glup');
echo fread($fichier, filesize($cheminContenu));;



?>
