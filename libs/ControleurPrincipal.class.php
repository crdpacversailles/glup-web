<?php
class ControleurPrincipal {

	private $controleurSecondaire;
	private $modele;
	private static $scripts;

	public function __construct() {
		$this->modele = new Modele();
	}
	public function __sleep() {
		return array (
			'modele'
		);
	}

	public function gererRequeteClassique() {
		$this->initialiserListeScripts();
		$this->traiterPost();
		$this->ajouterScriptSecondaire();
		$this->determinerControleurSecondaire();
	}
	public function gererRequeteAjax() {
		$this->traiterPostAjax();
	}

	public function __wakeup() {

	}
	private function initialiserListeScripts() {
		self :: $scripts = array (
			'init'
		);

	}
	private function traiterPost() {
		if (isset (Securite :: $_CLEAN['langue'])) {
			$_SESSION['langue'] = Securite :: $_CLEAN['langue'];
			Traducteur :: assignerLangue(Securite :: $_CLEAN['langue']);
		} else
			if (isset ($_SESSION['langue']))
				Traducteur :: assignerLangue($_SESSION['langue']);

		if (isset (Securite :: $_CLEAN['page'])) {

			$_SESSION['page'] = Securite :: $_CLEAN['page'];
		} else
			if (!isset ($_SESSION['page']))
				$_SESSION['page'] = "index";
		if (isset (Securite :: $_CLEAN['action'])) {
			if (isset ($_SESSION['action']))
				$_SESSION['action_precedente'] = $_SESSION['action'];
			else
				$_SESSION['action_precedente'] = Securite :: $_CLEAN['action'];
			$_SESSION['action'] = Securite :: $_CLEAN['action'];
		} else
			if (!isset ($_SESSION['action']))
				$_SESSION['action'] = "index";
		if (isset (Securite :: $_CLEAN['options_par_defaut'])) {
			$this->modele->restaurerOptionsParDefaut();
		}
		if (isset (Securite :: $_CLEAN['mode_emploi_par_defaut'])) {
			$this->modele->restaurerModeEmploiParDefaut();
		}
	}
	public function traiterPostAjax() {
		if (isset (Securite :: $_CLEAN['contenu'])) {
			$this->modele->setContenuExercice(Securite :: $_CLEAN['contenu']);
		}
		if (isset (Securite :: $_CLEAN['options'])) {
			$this->modele->setOptionsJeu(Securite :: $_CLEAN['options']);
		}
		if (isset (Securite :: $_CLEAN['largeur'])) {
			$this->modele->setLargeurJeu(Securite :: $_CLEAN['largeur']);
		}
		if (isset (Securite :: $_CLEAN['hauteur'])) {
			$this->modele->setHauteurJeu(Securite :: $_CLEAN['hauteur']);
		}
		if (isset (Securite :: $_CLEAN['mode_emploi'])) {
			$this->modele->setModeEmploiJeu(Securite :: $_CLEAN['mode_emploi']);
		}
		if (isset (Securite :: $_CLEAN['compilation'])) {
			$this->compiler(Securite :: $_CLEAN['compilation']);
		}
		if (isset (Securite :: $_CLEAN['export_donnees'])) {
			$this->exporter();
		}
		if (isset (Securite :: $_CLEAN['import_donnees'])) {
			$this->gererImport(Securite :: $_CLEAN['import_donnees']);
		}
		if (isset (Securite :: $_CLEAN['adresse_message']) && isset (Securite :: $_CLEAN['titre_message']) && isset (Securite :: $_CLEAN['texte_message'])) {

			$succes = GestionEmails :: envoyer(Securite :: $_CLEAN['adresse_message'], Securite :: $_CLEAN['titre_message'], Securite :: $_CLEAN['texte_message']);
			if ($succes)
				Securite :: afficher('ok');
			else
				Securite :: afficher('ko');
		}
	}
	private function compiler($modeCompilation) {
		if ($this->modele->getDirty()) {
			if (isset ($_SESSION['langue']))
				Traducteur :: assignerLangue($_SESSION['langue']);
			$jeu = $this->modele->getGamePlayEnregistre();
			$largeur = $this->modele->getLargeurJeu();
			$hauteur = $this->modele->getHauteurJeu();
			$contenu = $this->modele->getContenuExercice();
			$options = $this->modele->getOptionsJeuPourExport();
			$codeJeu = ProxyCompilateur :: compiler($jeu, $largeur, $hauteur, $contenu, $options);
			$this->modele->setCodeJeu($codeJeu);
			$this->modele->cleanDirtyBit();
		}

		Securite :: afficher($this->modele->getCodeJeu());
	}
	private function exporter() {
		$jeu = $this->modele->getGamePlayEnregistre();
		$exercice = $this->modele->getPrincipePedagogiqueEnregistre();
		$contenu = $this->modele->getContenuExercice();
		$options = $this->modele->getOptionsJeu();
		$modeEmploi = $this->modele->getModeEmploiJeu();
		$largeur = $this->modele->getLargeurJeu();
		$hauteur = $this->modele->getHauteurJeu();
		Securite :: afficher(GestionIODonnees :: exporter($jeu, $exercice, $contenu, $options, $modeEmploi, $largeur, $hauteur));
	}
	private function gererImport($donnees) {
		Securite :: afficher(GestionIODonnees :: importer($this->modele, $donnees));
		
	}
	private function determinerControleurSecondaire() {
		switch ($_SESSION['page']) {
			case 'index' :
				$this->controleurSecondaire = new ControleurAccueil();
				break;
			case 'test' :
				$this->controleurSecondaire = new ControleurTest($this->modele);
				break;
			case 'presentation' :
				$this->controleurSecondaire = new ControleurPresentation();
				break;
			case 'contribuer' :
				$this->controleurSecondaire = new ControleurContribution();
				break;
				assert(false);
		}
	}
	private function ajouterScriptSecondaire() {
		$this->ajouterScript('init_' . $_SESSION['page']);
	}
	public function getVueCourante() {
		return $this->controleurSecondaire->getVue()->toHTML();
	}
	public static function getListeScripts() {
		return self :: $scripts;
	}
	public static function ajouterScript($script) {
		array_push(self :: $scripts, $script);
	}
}
?>