<?php
class VueTestIndex extends AbstractVueTest implements IVue {
	function VueTestIndex($modele) {
		parent :: __construct($modele,NULL, 'pedagogie');
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