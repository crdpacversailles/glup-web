<?php
class VueTestGameplay extends AbstractVueTest implements IVue {
	function __construct($modele) {
		parent :: __construct($modele, 'contenu', 'options');
		$this->ajouterPresentation();
		$this->ajouterItems();
	}
	function ajouterPresentation() {
		$this->rendu .= ChargeurHtml :: charger('test-gameplay');
		$this->rendu=str_replace('[CLE_PRINCIPE_PEGAGO]', $this->modele->getPrincipePedagogiqueEnregistre(), $this->rendu);
	}
	function ajouterItems() {
		$this->itemsHTML='';
		$pattern ='<li id="gameplay_[CLE]" class="gameplay [SELECT]"><h3>{TEST-GAMEPLAY-ITEM-[CLE]}</h3>';
		$pattern .='<p>{TEST-GAMEPLAY-PRESENTATION-[CLE]}';
		if($this->modele->isGameplayEnregistreActif())		
			$pattern .='<br/><span><a href="exemples/index.php?jeu=[CLE]" target="_blank"> {TEST-GAMEPLAY-LIEN}</a></span></p></li>';
		else 
			$pattern .='<br/><strong>{TEST-GAMEPLAY-JEU-INDISPONIBLE}</strong></p></li>';
		$listeGameplays = $this->modele->getListeClesGameplays();
		$gameplayEnregistre=$this->modele->getGameplayEnregistre();
		foreach ( $listeGameplays as $gameplay ) {
       		$nouvelItem=$pattern;
       		$nouvelItem=str_replace('[CLE]', $gameplay, $nouvelItem);
       		$marqueurSelect= $gameplay==$gameplayEnregistre?'item_select':'';
       		$nouvelItem=str_replace('[SELECT]', $marqueurSelect, $nouvelItem);
       		$this->itemsHTML.=$nouvelItem;
		}
		
	}
	function toHTML() {
		$this->rendu=str_replace('[ITEMS]', $this->itemsHTML, $this->rendu);
		return $this->rendu;
	}
}
?>