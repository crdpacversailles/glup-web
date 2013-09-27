<?php

class ControleurPresentation {

    function ControleurPresentation() {
    }
    public function getVue() {
    	return new VuePresentationIndex();
    }
}
?>