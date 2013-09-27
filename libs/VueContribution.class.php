<?php

class VueContribution implements IVue {
	
	private $rendu;
    function __construct() {
    	$this->rendu = ChargeurHtml :: charger('contribution');
    }
    function toHTML() {
    	return $this->rendu;
    }
}
?>