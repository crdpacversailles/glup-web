<?php

class StructureOffre {
	private $offreXML;

    public function __construct() {
    	$this->chargerOffreXML();
    }
    function chargerOffreXML() {
		$this->offreXML = new DOMDocument('1.0', 'utf-8');
		$this->offreXML->preserveWhiteSpace = false; //On ne se soucie pas des espaces blancs.
		$this->offreXML->load('data/offre.xml');
	}
	public function __sleep() {
		return array ();
	}

	public function __wakeup() {
		$this->chargerOffreXML();
	}
	public function getListeClesPrincipesPedago() {
		$liste = array();
		
		foreach ( $this->offreXML->getElementsByTagName('pedagogie') as $item )
		{
			array_push($liste, $item->getAttribute( 'id' ));
		}
		return $liste;
	}
	private function getPrincipePedago($principe) {
		
		foreach ( $this->offreXML->getElementsByTagName('pedagogie') as $item )
		{
			if($item->getAttribute( 'id' )==$principe)
				return $item;
		}
		return null;
	}
	public function isPrincipePedagogiqueActif($principe) {
		foreach ( $this->offreXML->getElementsByTagName('pedagogie') as $item )
		{
			if($item->getAttribute( 'id' )==$principe)
				return $item->getAttribute('actif')==1;
		}
		return false;
	}
	public function isGamePlayActif($gameplay) {
		foreach ( $this->offreXML->getElementsByTagName('jeu') as $item )
		{
			if($item->getAttribute( 'id' )==$gameplay)
				return $item->getAttribute('actif')==1;
		}
		return false;
	}
	public function getListeClesGameplays($principe) {
		$liste = array();
		foreach ( $this->getPrincipePedago($principe)->getElementsByTagName('jeu') as $item )
		{
			array_push($liste, $item->getAttribute( 'id' ));
		}
		return $liste;
	}

}
?>