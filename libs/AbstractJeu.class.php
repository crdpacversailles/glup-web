<?php
class AbstractJeu {

	protected $options;
	protected $gameplay;

	private static $LARGEUR_PAR_DEFAUT = 800;
	private static $HAUTEUR_PAR_DEFAUT = 600;

	private $largeur;
	private $hauteur;

	public function __construct($gameplay) {
		$this->gameplay = $gameplay;
		$this->largeur = self :: $LARGEUR_PAR_DEFAUT;
		$this->hauteur = self :: $HAUTEUR_PAR_DEFAUT;

		$optionsXML = new DOMDocument('1.0', 'utf-8');
		$optionsXML->preserveWhiteSpace = false;
		$optionsXML->load('data/options_' . $gameplay . '.xml');
		$this->options = $optionsXML->saveXML();
	}
	public function __sleep() {
		return array (
			'options',
			'gameplay'
		);
	}
	public function __wakeup() {

	}
	public function getOptions() {
		return $this->options;
	}
	public function getGameplay() {
		return $this->gameplay;
	}
	public function setOptions($options) {		
		return $this->options = $options;
	}
	public function getLargeur() {
		return $this->largeur;
	}
	public function getHauteur() {
		return $this->hauteur;
	}
}
?>