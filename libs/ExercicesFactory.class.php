<?php

class ExercicesFactory {

    public static function donnerExercice($principe) {
    	switch ( $principe ) {
		case 'SOULIGNER-LES-MOTS':
			return new ExerciceSouligner($principe);
		break;
		case 'TRIER-LES-MOTS':
			return new ExerciceTrier($principe);
		break;
}
    }
}
?>