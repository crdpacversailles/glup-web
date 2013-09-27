<?php
class VueTestOptions extends AbstractVueTest implements IVue {
	function VueTestOptions($modele) {
		parent :: __construct($modele, 'gameplay', 'mode_emploi');
		$this->recupererOptionsXML();
		$this->ajouterOptionsGenerales();
		$this->ajouterOptionsSpecifiques();
		$this->ajouterMessages();
		$this->substituerValeurs();
		$this->signalerDonnerModifiees();
	}
	function ajouterOptionsGenerales() {
		$this->rendu .= ChargeurHtml :: charger('test-options');
		$this->rendu = str_replace('[CLE_GAMEPLAY]', $this->modele->getGamePlayEnregistre(), $this->rendu);

	}
	function ajouterMessages() {
		$this->rendu .= ChargeurHtml :: charger("test-options-messages");

	}
	function ajouterOptionsSpecifiques() {
		$optionsSpecifiques = ChargeurHtml :: charger('test-options-' . $this->modele->getGamePlayEnregistre());
		$this->rendu = str_replace('[OPTIONS-SPECIFIQUES]', $optionsSpecifiques, $this->rendu);

	}
	function substituerValeurs() {
		$noeudParamsGeneraux = $this->optionsXML->getElementsByTagName('generaux')->item(0)->childNodes;
		$noeudParamsSpecifiques = $this->optionsXML->getElementsByTagName('specifiques')->item(0)->childNodes;
		foreach ($noeudParamsSpecifiques as $param) {
			$this->inserer($param);
		}
		foreach ($noeudParamsGeneraux as $param) {
			$this->inserer($param);
		}
		$this->rendu = str_replace('[largeur]', $this->modele->getLargeurJeu(), $this->rendu);
		$this->rendu = str_replace('[hauteur]', $this->modele->getHauteurJeu(), $this->rendu);
		
	}
	function signalerDonnerModifiees() {
		$this->rendu = str_replace('[MODIFICATION_DONNEES]', $this->modele->getOptionsModifiees()?'oui':'non', $this->rendu);
	}
	function inserer($param) {
		$nomParam = $param->tagName;
		$valeurParam = trim($param->nodeValue);
		$remplacement = '';
		if ($param ->hasAttribute('type') && $param -> getAttribute('type') == 'fixe')
			$remplacement .= ' class="valeur_fixe"';
		if ($valeurParam == 'oui')
			$remplacement .= ' checked="checked"';
		else
			if ($valeurParam != 'non') 
				$remplacement = $valeurParam;
		$this->rendu = str_replace('[' . $nomParam . ']', $remplacement, $this->rendu);
	}
	function toHTML() {
		return $this->rendu;
	}
	function recupererOptionsXML() {
		$this->optionsXML = new DOMDocument('1.0', 'utf-8');
		$this->optionsXML->preserveWhiteSpace = false; //On ne se soucie pas des espaces blancs.
		if (strlen($this->modele->getOptionsJeu()) > 0)
			$this->optionsXML->loadXML(stripslashes($this->modele->getOptionsJeu()));
	}
}
?>