<?php
class VueTestContenuTrier extends AbstractVueTestContenu implements IVue {
	public function __construct($modele) {
		parent :: __construct($modele, 'pedagogie', 'gameplay');
		$this->ajouterZoneConsigne();
		$this->indexDerniereZone = 0;
		$this->ajouterZonesSaisie();
		
		
		$this->ajouterTextes();
		$this->ajouterBoutonAjoutPhrase();
		ControleurPrincipal :: ajouterScript('EditeurVraiFaux');
		ControleurPrincipal :: ajouterScript('iphone-style-checkboxes/prototype/iphone-style-checkboxes');
	}
	private function ajouterZoneConsigne() {
		$nomJeu = ChargeurHtml :: charger('test-contenu-nom-jeu');
		$noeudsNomJeu=$this->contenuXML->getElementsByTagName('nom_jeu');
		if($noeudsNomJeu && $noeudsNomJeu->item(0) ) $texteNomJeu=$noeudsNomJeu->item(0)->nodeValue;
		else $texteNomJeu=" ";
		$this->contenuSpecifique .= str_replace('[NOM-JEU]', $texteNomJeu, $nomJeu);
		$consigne = ChargeurHtml :: charger('test-contenu-consigne');
		$noeudsConsigne=$this->contenuXML->getElementsByTagName('consigne');
		if($noeudsConsigne && $noeudsConsigne->item(0) ) $texteConsigne=$noeudsConsigne->item(0)->nodeValue;
		else $texteConsigne=" ";
		$this->contenuSpecifique .= str_replace('[CONSIGNE]', $texteConsigne, $consigne);
		
	}
	private function ajouterZonesSaisie() {
		$this->contenuSpecifique .= '<fieldset class="zone_contenus"><legend>{TEST-CONTENU-TEXTES}</legend>';
		$this->contenuSpecifique .= '<div id="zone_comptage"></div>';
		
		foreach ($this->contenuXML->getElementsByTagName('enonce') as $item) {
			$texte= htmlspecialchars(trim($item->nodeValue), null, "UTF-8");
			$bonneReponse = $item->getAttribute("statut")=='true'?"insertion_bonne_reponse":"";
			$this->contenuSpecifique .= '<div class="insertion_editeur_vraifaux '.$bonneReponse.'" id="insertion_editeur_vraifaux_' . $this->indexDerniereZone . '">'.$texte.'</div>';
			$this->indexDerniereZone++;
		}
		
	}
	private function ajouterTextes() {
		$this->contenuSpecifique .= '<input id="texte_invitation" type="hidden" value="{TEST-CONTENU-TRIER-INVITATION-EDITEUR}" />';
		$this->contenuSpecifique .= '<input id="texte_supprimer" type="hidden" value="{TEST-CONTENU-SOULIGNER-BULLE-SUPPRIMER}" />';
		$this->contenuSpecifique .= '<input id="texte_score" type="hidden" value="{TEST-CONTENU-TRIER-PHRASE-SCORE}" />';
	}
	private function ajouterBoutonAjoutPhrase() {
		$this->contenuSpecifique .= ChargeurHtml :: charger('test-contenu-bouton-ajouter-enonce');
		$this->contenuSpecifique .= '</fieldset>';
	}

	public function toHTML() {
		$this->rendu = str_replace('[CONTENU_SPECIFIQUE]', $this->contenuSpecifique, $this->rendu);
		return $this->rendu;
	}
}
?>