<?php
abstract class AbstractVueTest implements IVue {

	protected $rendu;
	protected $contenuSpecifique;
	private $actionSuivante;
	private $actionPrecedente;
	protected $modele;
	

	public function __construct($modele, $actionSuivante, $actionPrecedente){
		$this->contenuSpecifique='';
		$this->modele=$modele;
		$this->actionSuivante=$actionSuivante;
		$this->actionPrecedente=$actionPrecedente;
		$this->creerMenu();
		
	}
	private function creerMenu() {
		$menu = new MenuSecondaire($_SESSION['page'], $this->actionSuivante, $this->actionPrecedente);
		$menu->ajouterItem('MENU-TEST-INDEX', 'index', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-PEDAGOGIE', 'pedagogie', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-CONTENU', 'contenu', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-GAMEPLAY', 'gameplay', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-OPTIONS', 'options', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-MODE-EMPLOI', 'mode_emploi', $_SESSION['action']);
		$menu->ajouterItem('MENU-TEST-EXPORT', 'export', $_SESSION['action']);
		$this->rendu.=$menu->toHTML();
	}	
	public function assignerPosDepart($posDepart) {
		$style = ' style="position:relative; left:' . $posDepart .'px"';
		$this->rendu = str_replace('[POS_DEPART]', $style, $this->rendu);
	}
	public function toHTML() {
		return $this->rendu;
	}


}
?>