<?php
class AbstractExercice {

	protected $contenu;
	protected $nom;
	protected $consigne;
	protected $principe;

	public function __construct($principe) {
		$this->principe = $principe;
		$this->contenu = '<contenu xmlns="' . Modele :: $NAMESPACE . '"><nom_jeu></nom_jeu><consigne></consigne><enonces/></contenu>';
	}
	public function __sleep() {
		return array (
			'nom',
			'contenu',
			'consigne',
			'principe'
		);
	}

	public function __wakeup() {

	}
	public function getNom() {
		return $this->nom;
	}
	public function getContenu() {
		return $this->contenu;
	}
	public function getPrincipe() {
		return $this->principe;
	}
	public function setContenu($contenu) {
		return $this->contenu = $contenu;
	}
}
?>