<?php

class VueAccueil implements IVue {
	
	private $rendu;
    function VueAccueil() {
    	$this->rendu = ChargeurHtml :: charger('accueil');
    }
    function toHTML() {
    	return $this->rendu;
    }
}
?>