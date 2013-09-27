<?php
class Traducteur {

	private static $pattern = '/\{([^{}]+)\}/';
	private static $textes;
	private static $langue;
	private static $LANGUE_PAR_DEFAUT="fr";
	
	public static function traduire($chaine) {
		if (!isset(self :: $langue))
			self :: $langue=self::$LANGUE_PAR_DEFAUT;
		if (is_null(self :: $textes))
			self :: chargerTextes();
		preg_match_all(self :: $pattern, $chaine, $cles);
		foreach ($cles[0] as $cle) {
			$cleTraduction = preg_replace('/[\{\}]/', '', $cle);
			$traduction = self::$textes->getItemValue($cleTraduction);
			$chaine = str_replace($cle, $traduction, $chaine);
		}
		return $chaine;
	}
	public static function assignerLangue($langue) {
		self::$langue=$langue;
	}
	public static function getLangue() {
		return isset(self :: $langue)?self::$langue:self::$LANGUE_PAR_DEFAUT;
	}
	private static function chargerTextes() {
		self :: $textes = new XMLEngine('langues/website.xml', self::$langue);
	}
}
?>