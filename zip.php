<?php
/*
 * Created on 24 janv. 2011
 *
 */
require_once 'inc/autoload.php';
if(!Securite :: nettoyerPost()) die();
$zip = new zipfile(); 
$filename = Securite ::$_CLEAN['nom_fichier_zip'].".swf";
$chemin='output/'.$filename;
$fp = fopen($chemin, 'r'); 
$contenu = fread($fp, filesize($chemin)); 
fclose($fp); 

$zip->addfile($contenu, $filename); //on ajoute le fichier

$archive = $zip->file(); //on associe l'archive

header('Content-Type: application/x-zip'); //on détermine les en-tête
header('Content-Disposition: inline; filename='.Securite ::$_CLEAN['nom_fichier_zip'].'.zip');

echo $archive;

?>
