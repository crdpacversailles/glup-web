<?php

class VueTestContenuFactory {

    public static function donnerVueTestContenu($cle, $modele) {
    	switch ( $cle ) {
		case 'SOULIGNER-LES-MOTS':
			return new VueTestContenuSouligner($modele);
		break;
		case 'TRIER-LES-MOTS':
			return new VueTestContenuTrier($modele);
		break;
}
    }
}
?>