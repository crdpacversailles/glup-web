<?php
class VueTestPedagogie extends AbstractVueTest implements IVue {
	
	private $itemsHTML;
	
	function VueTestPedagogie($modele) {
		parent :: __construct($modele, 'index', 'contenu');
		$this->ajouterPresentation();
	}
	function ajouterPresentation() {
		$this->rendu .= ChargeurHtml :: charger('test-pedagogie');
		$this->ajouterItems();
	}
	function ajouterItems() {
		$this->itemsHTML='';
		$pattern ='<li id="principe_pedagogique_[CLE]" class="principe_pedagogique [STATUT] [SELECT]">{TEST-PEDAGOGIE-ITEM-[CLE]}</li>';
		$listePrincipesPedago = $this->modele->getListeClesPrincipesPedago();
		$principeEnregistre=$this->modele->getPrincipePedagogiqueEnregistre();
		foreach ( $listePrincipesPedago as $principe ) {
       		$nouvelItem=$pattern;
       		$nouvelItem=str_replace('[CLE]', $principe, $nouvelItem);
       		$statut = $this->modele->isPrincipePedagogiqueActif($principe)?'item_actif':'item_inactif';
       		$nouvelItem=str_replace('[STATUT]', $statut, $nouvelItem);
       		$marqueurSelect= $principe==$principeEnregistre?'item_select':'';
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