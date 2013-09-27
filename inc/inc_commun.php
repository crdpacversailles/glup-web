<?php
/*
 * Created on 24 janv. 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once 'inc/autoload.php';


if (!Securite::nettoyerPost()) {
	header('Location: erreur.php');   

} else {
		session_start();
		$rendu = '';
		if (isset($_SESSION['controleur_principal'])) {
		    // réveil du contrôleur s'il existe
		    $controleur = $_SESSION['controleur_principal'];
		}else {
		    // sinon création d'un contrôleur
		    $controleur = new ControleurPrincipal();
		    $_SESSION['controleur_principal'] = $controleur;
		}
		$controleur->gererRequeteClassique();
		
		require_once 'inc/i18n.php';
		$header= new Header();
		$header->ajouterScript('prototype');
		$header->ajouterScript('scriptaculous');
		$header->ajouterScript('utils');
		$header->ajouterScript('Avertissement');
		foreach ( ControleurPrincipal::getListeScripts() as $script ) {
		       $header->ajouterScript($script);
		}
		$header->ajouterScript('init_'.$_SESSION['page']);
		$rendu.=$header->toHTML();
		
		$menu= new Menu();
		$menu->ajouterItem('ACCUEIL', "index",$_SESSION['page']);
		$menu->ajouterItem('TESTER', "test", $_SESSION['page']);
		$menu->ajouterItem('SAVOIR', "presentation",$_SESSION['page']);
		//rubrique desactivée
		//$menu->ajouterItem('CONTRIBUER', "contribuer", $_SESSION['page']);
		
		$rendu.=$menu->toHTML();
		$rendu.= $controleur->getVueCourante();
		$footer= new Footer();
		$rendu.=$footer->toHTML();
		
		Securite::afficher(Traducteur::traduire($rendu)) ;	
		 $_SESSION['action_precedente'] = $_SESSION['action']; 
}


?>
