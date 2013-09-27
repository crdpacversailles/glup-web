<?php
class Modele {

	private $structureOffre;
	private $exercice;
	private $jeu;
	private $codeJeu;
	private $dirtyBit;
	private static $CLE_EXERCICE_PAR_DEFAUT = "SOULIGNER-LES-MOTS";
	private static $CLE_JEU_PAR_DEFAUT = "MOTS-TRIS";
	public static $NAMESPACE ="http://www.crdp.ac-versailles.fr/glup/2012";

	public function __construct() {
		$this->structureOffre = new StructureOffre();
		$this->creerExercice(self :: $CLE_EXERCICE_PAR_DEFAUT);
		$this->choisirJeuParDefaut();
		$this->dirtyBit = false;
		$this->codeJeu = "";
	}

	public function __sleep() {
		return array (
			'structureOffre',
			'exercice',
			'jeu',
			'codeJeu',
			'dirtyBit'
		);
	}

	public function __wakeup() {

	}
	public function getListeClesPrincipesPedago() {
		return $this->structureOffre->getListeClesPrincipesPedago();
	}
	public function getListeClesGameplays() {
		return $this->structureOffre->getListeClesGameplays($this->exercice->getPrincipe());
	}
	public function isPrincipePedagogiqueActif($principe) {
		return $this->structureOffre->isprincipePedagogiqueActif($principe);
	}

	public function modifierExercice($principe) {
		if ($principe == $this->exercice->getPrincipe())
			return;
		$this->creerExercice($principe);
		$this->choisirJeuParDefaut();
	}
	public function modifierJeu($gameplay) {
		if ($gameplay == $this->jeu->getGameplay())
			return;
		$this->creerJeu($gameplay);
		$this->dirtyBit = true;
	}
	private function choisirJeuParDefaut() {
				$listeJeux		=$this->getListeClesGameplays();
		$this->creerJeu($listeJeux[0]);
		$this->dirtyBit = true;
	}
	public function restaurerModeEmploiParDefaut() {
		$this->jeu->chargerModeEmploiParDefaut();
		$this->dirtyBit = true;
	}
	public function restaurerOptionsParDefaut() {
		$this->jeu->chargerOptionsParDefaut();
		$this->dirtyBit = true;
	}
	private function creerExercice($principe) {
		$this->exercice = ExercicesFactory :: donnerExercice($principe);
		$this->dirtyBit = true;
	}
	private function creerJeu($gameplay) {
		$this->jeu = new Jeu($gameplay);
		$this->dirtyBit = true;
	}
	public function getPrincipePedagogiqueEnregistre() {
		return $this->exercice->getPrincipe();
	}
	public function getGamePlayEnregistre() {
		return $this->jeu->getGameplay();
	}
	public function isGameplayEnregistreActif() {
		return $this->structureOffre->isGameplayActif($this->jeu->getGameplay());
	}
	public function getContenuExercice() {
		return $this->exercice->getContenu();
	}
	public function setContenuExercice($contenu) {
		$this->exercice->setContenu($contenu);
		$this->dirtyBit = true;
	}
	public function setOptionsJeu($options) {
		$this->jeu->setOptions($options);
		$this->dirtyBit = true;
	}
	public function getOptionsJeuPourExport() {
		return $this->jeu->getOptionsPourExport();
	}
	public function getOptionsJeu() {
		return $this->jeu->getOptions();
	}
	public function getLargeurJeu() {
		return $this->jeu->getLargeur();
	}
	public function getHauteurJeu() {
		return $this->jeu->getHauteur();
	}
	public function setLargeurJeu($largeur) {
		$this->jeu->setLargeur($largeur);
		$this->dirtyBit = true;
	}
	public function setHauteurJeu($hauteur) {
		return $this->jeu->setHauteur($hauteur);
		$this->dirtyBit = true;
	}
	public function setModeEmploiJeu($modeEmploi) {
		$this->jeu->setModeEmploi($modeEmploi);
		$this->dirtyBit = true;
	}
	public function getModeEmploiJeu() {
		return $this->jeu->getModeEmploi();
	}
	public function getModeEmploiModifie() {
		return $this->jeu->getModeEmploiModifie();
	}
	public function getOptionsModifiees() {
		return $this->jeu->getOptionsModifiees();
	}
	public function setCodeJeu($code) {
		$this->codeJeu = $code;
	}
	public function getCodeJeu() {
		return $this->codeJeu;
	}
	public function getDirty() {
		return $this->dirtyBit;
	}
	public function cleanDirtyBit() {
		$this->dirtyBit = false;
	}
}
?>