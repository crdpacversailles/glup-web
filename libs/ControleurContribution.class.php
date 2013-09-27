<?php

class ControleurContribution implements IControleurSecondaire {

    function ControleurContribution() {
    }
    public function getVue() {
    	return new VueContribution();
    }
}
?>