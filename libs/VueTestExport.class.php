<?php
class VueTestExport extends AbstractVueTest implements IVue {
	function __construct($modele) {
		parent :: __construct($modele,'mode_emploi', NULL);
		$this->ajouterPresentation();
		
		$this->recupererContenuXML();
		$this->verifierContenu();
	}
	function ajouterPresentation() {
		$this->rendu .= ChargeurHtml :: charger('test-export');
		$mentionIndisponible = $this->modele->isGameplayEnregistreActif()?'':ChargeurHtml :: charger('test-export-mention-jeu-indisponible');
		$this->rendu = str_replace('[MENTION_JEU_INDISPONIBLE]', $mentionIndisponible, $this->rendu);
		$visibiliteBoutons=($this->modele->isGameplayEnregistreActif())?'':'desactive';		
		$this->rendu = str_replace('[JEU_ACTIF]', $visibiliteBoutons, $this->rendu);
	}
	function verifierContenu() {
		$nbPhrases = $this->contenuXML->getElementsByTagName('enonce')->length;
		$this->rendu = str_replace('[EXERCICE_VIDE]', $nbPhrases==0?'oui':'non', $this->rendu);
	}
	function toHTML() {
		return $this->rendu;
	}
	function recupererContenuXML() {
		$this->contenuXML = new DOMDocument('1.0', 'utf-8');
		$this->contenuXML->preserveWhiteSpace = false; //On ne se soucie pas des espaces blancs.
		if(strlen($this->modele->getContenuExercice())>0)
			$this->contenuXML->loadXML(stripslashes($this->modele->getContenuExercice()));
	}
}
?>