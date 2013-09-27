<?php

class JeuxFactory {

    public static function donnerJeu($gameplay) {
    	switch ( $gameplay ) {
		case 'MOTS-TRIS':
			return new JeuMotsTris($gameplay);
		break;
		case 'BRULE-MOTS':
			return new JeuBruleMots($gameplay);
		break;
}
    }
}
?>