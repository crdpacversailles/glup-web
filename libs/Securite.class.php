<?php
class Securite {

	public static $_CLEAN;
	public static $_TAMPON;
	private static $LONGUEUR_MAX_ENTREES = 20000;
	private static $LONGUEUR_MAX_CHAINES = 2000;

	private static $LISTE_BLANCHE = array (
		"langue" => "langue",
		"page" => "page",
		"action" => "action",
		"contenu" => "xml",
		"options" => "xml",
		"largeur" => "entier",
		"hauteur" => "entier",
		"mode_emploi" => "xml",
		"compilation" => "compilation",
		"export_donnees" => "self",
		"principe-pedago" => "principe-pedago",
		"nom_fichier_glup" => "idfichierglup",
		"nom_fichier_zip" => "idjeu",
		"adresse_message" => "email",
		"texte_message" => "chaine",
		"titre_message" => "chaine",
		"jeu" => "idjeu",
		"gameplay" => "gameplay",
		"options_par_defaut" => "entier",
		"mode_emploi_par_defaut" => "entier",
		"import_donnees" => "xml"
	);

	public static function nettoyerPost() {
		self :: $_TAMPON = array_merge($_POST, $_GET);
		foreach (self :: $_TAMPON as $key => $value) {
			if (!isset (self :: $LISTE_BLANCHE[$key]))
				return false;
			$type = self :: $LISTE_BLANCHE[$key];
			$valide = false;
			if (strlen($value) > self :: $LONGUEUR_MAX_ENTREES)
				return false;
			switch ($type) {
				case "entier" :
					$value = filter_var($value, FILTER_VALIDATE_INT);
					//TODO améliorer ça;
					if ($value > 0)
						$valide = true;
					break;
				case "email" :
					self :: $_TAMPON[$key] = filter_var($value, FILTER_VALIDATE_EMAIL);
					$valide = strlen(self :: $_TAMPON[$key]) > 0;
					break;
				case "idjeu" :
				//il peut s'agit d'un nom de jeu généré par uniqd ou du nom d'un des exemples
					$valide = preg_match('/^jeu_\w{13}$/', $value) > 0 || self::estUnGamePlay($value);
					break;
				case "self" :
					$valide = $key == $value;
					break;
				case "xml" :
					$valide = self :: controleXML($value);
					break;
				case "chaine" :
					self :: $_TAMPON[$key] = filter_var($value, FILTER_SANITIZE_STRING);
					$valide = strlen(self :: $_TAMPON[$key]) > 0 && strlen(self :: $_TAMPON[$key]) < self :: $LONGUEUR_MAX_CHAINES;
					break;
				case "principe-pedago" :
					$valide = in_array($value, array (
						"TRIER-LES-MOTS",
						"SOULIGNER-LES-MOTS"
					));
					break;
				case "gameplay" :
					$valide = self::estUnGamePlay($value);
					break;
				case "langue" :
					$valide = in_array($value, array (
						"fr",
						"en-gb"
					));
					break;
				case "compilation" :
					$valide = in_array($value, array (
						"test",
						"download"
					));
					break;
				case "page" :
					$valide = in_array($value, array (
						"index",
						"test",
						"presentation",
						"contribuer"
					));
					break;
				case "action" :
					$valide = in_array($value, array (
						"index",
						"pedagogie",
						"contenu",
						"gameplay",
						"mode_emploi",
						"options",
						"export"
					));
					break;
				case "idfichierglup" :
					$valide = preg_match('/^\w{13}$/', $value) > 0;
					break;

			}
			if ($valide === true)
				self :: $_CLEAN[$key] = preg_replace('/[{}]/', '', self :: $_TAMPON[$key]);
			else
				return false;

		}
		return true;
	}
	private static function controleXML($chaine) {
		@ $xml = simplexml_load_string($chaine);
		return !is_null($xml);
	}
	private static function estUnGamePlay($value) {
		return in_array($value, array (
						"MOTS-TRIS",
						"BRULE-MOTS",
						"POP-MOTS"
					));;
	}
	public static function afficher($rendu) {
		echo $rendu;
	}
}
?>