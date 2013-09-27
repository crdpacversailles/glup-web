<?php

class ChargeurHtml {

	public static function charger($nomFichier) {
		$contenu = file_get_contents('html/'.$nomFichier.'.html');
		return $contenu;
	}

}

?>