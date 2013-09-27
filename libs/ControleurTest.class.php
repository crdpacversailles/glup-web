<?php
class ControleurTest {
	private $vue;
	private $modele;

	private $actions = array (
		"index",
		"pedagogie",
		"contenu",
		"gameplay",
		"options",
		"mode_emploi",
		"export"
	);

	public function __construct($modele) {
		$this->modele = $modele;
		$this->traiterPost();
		$this->determinerVue();
		$this->completerScripts();
		$this->positionnerPagePourArrivee();
	}
	private function completerScripts() {
		if ($_SESSION['action'] == 'contenu')
			ControleurPrincipal :: ajouterScript('init_test_contenu_' .
			$this->modele->getPrincipePedagogiqueEnregistre());
		else
			if ($_SESSION['action'] == 'options')
				ControleurPrincipal :: ajouterScript('init_test_options');
			else
				if ($_SESSION['action'] == 'mode_emploi')
					ControleurPrincipal :: ajouterScript('init_test_mode_emploi');
				else
					if ($_SESSION['action'] == 'export')
						ControleurPrincipal :: ajouterScript('init_test_export');
	}
	private function positionnerPagePourArrivee() {
		$indexAction = array_search($_SESSION['action'], $this->actions);
		$indexActionPrecedente = array_search($_SESSION['action_precedente'], $this->actions);
		$posDepart = 0;

		if ($indexAction > $indexActionPrecedente)
			$posDepart = 1500;
		else
			if ($indexAction < $indexActionPrecedente)
				$posDepart = -1500;
		$this->vue->assignerPosDepart($posDepart);
	}
	private function determinerVue() {
		switch ($_SESSION['action']) {
			case 'index' :
				$this->vue = new VueTestIndex($this->modele);
				break;
			case 'pedagogie' :
				$this->vue = new VueTestPedagogie($this->modele);
				break;
			case 'contenu' :
				$this->vue = VueTestContenuFactory :: donnerVueTestContenu($this->modele->getPrincipePedagogiqueEnregistre(), $this->modele);
				break;
			case 'gameplay' :
				$this->vue = new VueTestGameplay($this->modele);
				break;
			case 'options' :
				$this->vue = new VueTestOptions($this->modele);
				break;
			case 'mode_emploi' :
				$this->vue = new VueTestModeEmploi($this->modele);
				break;
			case 'export' :
				$this->vue = new VueTestExport($this->modele);
				break;
		}
	}
	private function traiterPost() {
		if (isset (Securite :: $_CLEAN['principe-pedago'])) {
			$this->modele->modifierExercice(Securite :: $_CLEAN['principe-pedago']);
		}
		if (isset (Securite :: $_CLEAN['gameplay'])) {
			$this->modele->modifierJeu(Securite :: $_CLEAN['gameplay']);
		}

	}
	public function getVue() {
		return $this->vue;
	}
}
?>