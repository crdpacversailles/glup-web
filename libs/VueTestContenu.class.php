<?php
class VueTestContenu extends AbstractVueTest implements IVue {
	function VueTestContenu($modele) {
		parent :: __construct($modele,'pedagogie', 'gameplay');
		$this->ajouterPresentation();
	}
	function ajouterPresentation() {
		$this->rendu .= ChargeurHtml :: charger('test-demarrage');
	}
	function toHTML() {
		return $this->rendu;
	}
}
?>