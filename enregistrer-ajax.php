<?php

/*
 * Created on 27 janv. 2011
 *
 *	GLUP - CRDP Académie de Versailles
 *	@author joachim.dornbusch@crdp.ac-versailles.fr
 *
 */
require_once 'inc/autoload.php';
if(!Securite :: nettoyerPost()) die();
session_start();
if (isset ($_SESSION['controleur_principal'])) {
	$controleur = $_SESSION['controleur_principal'];
}
$controleur->gererRequeteAjax();
?>