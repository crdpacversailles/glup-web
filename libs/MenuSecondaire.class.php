<?php
class MenuSecondaire {

	private $rendu;
	private $actionSuivante;
	private $actionPrecedente;
	private $page;

	function MenuSecondaire($page, $actionPrecedente = NULL, $actionSuivante = NULL) {
		$this->actionSuivante = $actionSuivante;
		$this->actionPrecedente = $actionPrecedente;
		$this->page = $page;
		$this->rendu = '<div class="menu_secondaire">';
		if (!is_null($this->actionPrecedente))
			$this->rendu .= '<img id="fleche_menu_secondaire_' . $this->page . '_' . $this->actionPrecedente . '" class="fleche_menu_secondaire fleche_gauche" src="img/left.gif" />';
		else
			$this->rendu .= '<img class="fleche_menu_secondaire fleche_gauche fleche_inactive" src="img/left.gif" />';
	}
	function ajouterItem($label, $action, $actionCourante) {
		$this->rendu .= '<div class="item_menu_secondaire';
		if ($actionCourante == $action)
			$this->rendu .= ' action_courante ';

		$this->rendu .= '" id="item_menu_secondaire_' . $this->page . '_' . $action . '">{' . ($label) . '}</div>';

	}
	function ajouterFlecheSuite() {
		$cleActionSuivantPourI18n='{MENU-TEST-' . str_replace("_", "-", strtoupper($this->actionSuivante)) . '}';
		$this->rendu .= '<div class="container_fleche_suite" id="fleche_suite_' . $this->page . '_' . $this->actionSuivante . '">'.$cleActionSuivantPourI18n.'<img src="img/suite.gif" /></div>';
	}

	function toHTML() {
		if (!is_null($this->actionSuivante))
			$this->rendu .= '<img id="fleche_menu_secondaire_' . $this->page . '_' . $this->actionSuivante . '" class="fleche_menu_secondaire fleche_droite" src="img/right.gif" />';
		else
			$this->rendu .= '<img class="fleche_menu_secondaire fleche_droite fleche_inactive" src="img/right.gif" />';
		$this->rendu .= '</div>';
		if (!is_null($this->actionSuivante))
			$this->ajouterFlecheSuite();
		return $this->rendu;
	}
}
?>
