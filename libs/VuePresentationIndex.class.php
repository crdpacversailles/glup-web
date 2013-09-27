<?php

class VuePresentationIndex implements IVue {

    function VuePresentationIndex() {
  		 $this->rendu = ChargeurHtml :: charger('presentation-'.Traducteur::getLangue());
    }
    function toHTML() {
    	return $this->rendu;
    }
}
?>