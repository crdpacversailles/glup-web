<?php
class Jeu {

	protected $options;
	protected $modeEmploi;
	private $langueModeEmploi;
	private $modeEmploiSansChangement;
	protected $gameplay;

	private static $LARGEUR_PAR_DEFAUT = 800;
	private static $HAUTEUR_PAR_DEFAUT = 600;
	private static $LARGEUR_MAX = 3000;
	private static $HAUTEUR_MAX = 3000;
	private static $LARGEUR_MIN = 100;
	private static $HAUTEUR_MIN = 100;
	private $largeur;
	private $hauteur;

	public function __construct($gameplay) {
		$this->gameplay = $gameplay;

		$this->chargerOptionsParDefaut();
		$this->chargerModeEmploiParDefaut();
	}
	public function chargerModeEmploiParDefaut() {
		$modeEmploiXML = new DOMDocument('1.0', 'utf-8');
		$modeEmploiXML->preserveWhiteSpace = false;
		$modeEmploiXML->load('data/mode_emploi_' . $this->gameplay . '_' . Traducteur :: getLangue() . '.xml');
		$this->modeEmploi = $modeEmploiXML->saveXML();
		$this->modeEmploiSansChangement = true;
		$this->langueModeEmploi = Traducteur :: getLangue();
	}
	public function chargerOptionsParDefaut() {
		$this->largeur = self :: $LARGEUR_PAR_DEFAUT;
		$this->hauteur = self :: $HAUTEUR_PAR_DEFAUT;
		$optionsXML = new DOMDocument('1.0', 'utf-8');
		$optionsXML->preserveWhiteSpace = false;
		$optionsXML->load('data/options_' . $this->gameplay . '.xml');
		$this->options = $optionsXML->saveXML();
		$this->optionsSansChangement = true;
	}
	public function __sleep() {
		return array (
			'options',
			'modeEmploi',
			'langueModeEmploi',
			'modeEmploiSansChangement',
			'optionsSansChangement',
			'gameplay',
			'largeur',
			'hauteur'
		);
	}
	public function __wakeup() {

	}
	public function getOptionsPourExport() {
		$optionsExport = $this->conversionXML($this->options);
		$langue = $optionsExport->createElement("langue", Traducteur :: getLangue());
		$optionsExport->getElementsByTagName('generaux')->item(0)->appendChild($langue);
		$modeEmploi = $this->conversionXML($this->modeEmploi);

		$contenuModeEmploi = $modeEmploi->getElementsByTagName('mode_emploi')->item(0);
		$noeudModeEmploi = $optionsExport->importNode($contenuModeEmploi, true);
		$optionsExport->documentElement->appendChild($noeudModeEmploi);
		$optionsExport->normalize();
		return $optionsExport->saveXML();
	}
	private function conversionXML($xmlTexte) {
		$XML = new DOMDocument('1.0', 'utf-8');
		if (strlen($xmlTexte) > 0)
			$XML->loadXML(stripslashes($xmlTexte));
		$XML->preserveWhiteSpace = false;
		return $XML;
	}
	public function getOptions() {
		return $this->options;
	}
	public function getGameplay() {
		return $this->gameplay;
	}
	public function setOptions($options) {
		$this->options = $options;
		$this->optionsSansChangement = false;
	}
	public function getLargeur() {
		return $this->largeur;
	}
	public function getHauteur() {
		return $this->hauteur;
	}
	public function setLargeur($largeur) {
		if ($this->largeur == $largeur)
			return;
		$this->largeur = max(self::$LARGEUR_MIN, min(self::$LARGEUR_MAX, $largeur));
		$this->optionsSansChangement = false;
	}
	public function setHauteur($hauteur) {
		if ($this->hauteur == $hauteur)
			return;
		$this->hauteur = max(self::$HAUTEUR_MIN, min(self::$HAUTEUR_MAX, $hauteur));
		$this->optionsSansChangement = false;
	}
	public function setModeEmploi($modeEmploi) {
		$this->modeEmploi = $modeEmploi;
		$this->modeEmploiSansChangement = false;
	}
	public function getModeEmploi() {
		if ($this->modeEmploiSansChangement && $this->langueModeEmploi != Traducteur :: getLangue()) {
			$this->chargerModeEmploiParDefaut();
		}
		return $this->modeEmploi;
	}
	public function getModeEmploiModifie() {
		return !$this->modeEmploiSansChangement;
	}
	public function getOptionsModifiees() {
		return !$this->optionsSansChangement;
	}
}
?>