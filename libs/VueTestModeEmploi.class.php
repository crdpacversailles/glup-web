<?php
class VueTestModeEmploi extends AbstractVueTest implements IVue {
	private $modeEmploiXML;

	function __construct($modele) {
		parent :: __construct($modele, 'options', 'export');
		$this->ajouterPresentation();
		$this->recupererModeEmploiXML();
		$this->afficherModeEmploi();
		$this->signalerDonnerModifiees();
	}
	function ajouterPresentation() {
		$this->rendu .= ChargeurHtml :: charger('test-mode-emploi');
	}
	function afficherModeEmploi() {
		$paragraphesXML = $this->modeEmploiXML->getElementsByTagName('paragraphe');
		$zonesSaisie = "";
		$htmlzone = ChargeurHtml :: charger('test-mode-emploi-paragraphe');
		$compteur = 0;
		foreach ($paragraphesXML as $paragrapheXML) {
			$htmlNouvelleZone = $htmlzone;
			$htmlNouvelleZone = str_replace("[ID]", "paragraphe_" + $compteur, $htmlNouvelleZone);
			$texte = htmlspecialchars(trim($paragrapheXML->nodeValue), null, "UTF-8");
			$htmlNouvelleZone = str_replace("[TEXTE]", $texte, $htmlNouvelleZone);
			$zonesSaisie .= $htmlNouvelleZone;
			$compteur++;
		}
		$this->rendu = str_replace("[ZONES_SAISIE]", $zonesSaisie, $this->rendu);
	}
	function signalerDonnerModifiees() {
		$this->rendu = str_replace('[MODIFICATION_DONNEES]', $this->modele->getModeEmploiModifie() ? 'oui' : 'non', $this->rendu);
	}
	function toHTML() {
		return $this->rendu;
	}
	function recupererModeEmploiXML() {
		$this->modeEmploiXML = new DOMDocument('1.0', 'utf-8');
		$this->modeEmploiXML->preserveWhiteSpace = false; //On ne se soucie pas des espaces blancs.
		if (strlen($this->modele->getModeEmploiJeu()) > 0)
			$this->modeEmploiXML->loadXML(stripslashes($this->modele->getModeEmploiJeu()));

	}
}
?>