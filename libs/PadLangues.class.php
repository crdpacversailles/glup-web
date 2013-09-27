<?php
class PadLangues {

	private static $LANGUES = array (
		'fr',
		'en-gb'
		//,'de'
	);

	function PadLangues() {
		$this->rendu = '<div class="pad_langues">';
		foreach (self :: $LANGUES as $langue) {
			$this->ajouterLangue($langue);
		}
	}

	function ajouterLangue($cleLangue) {
		$this->rendu .= '<img class="item_pad_langue';
		if ($cleLangue==Traducteur::getLangue())
			$this->rendu .=' langue_active ';
		$this->rendu .= '" id="langue_' . $cleLangue . '" src="img/langues/' . $cleLangue . '.png"/>';
	}

	function toHTML() {
		$this->rendu .= '</div>';
		return $this->rendu;
	}
}
?>