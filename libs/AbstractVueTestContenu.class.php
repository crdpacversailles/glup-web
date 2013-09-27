<?php
abstract class AbstractVueTestContenu extends AbstractVueTest {

	protected $contenuXML;
	function __construct($modele) {
		parent :: __construct($modele, 'pedagogie', 'gameplay');
		$this->chargerContenu();
		$this->recupererContenuXML();

	}
	function recupererContenuXML() {
		$this->contenuXML = new DOMDocument('1.0', 'utf-8');
		$this->contenuXML->preserveWhiteSpace = false; //On ne se soucie pas des espaces blancs.
		if(strlen($this->modele->getContenuExercice())>0)
			$this->contenuXML->loadXML(stripslashes($this->modele->getContenuExercice()));
	}
	function chargerContenu() {
		$this->rendu .= ChargeurHtml :: charger("test-contenu");
		$this->rendu .= ChargeurHtml :: charger("test-contenu-messages-".$this->modele->getPrincipePedagogiqueEnregistre());
	}
}
?>