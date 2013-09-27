<?php
class Menu {

	private $rendu;

	function Menu() {
		$this->rendu = '<div class="menu">';
	}
	function ajouterItem($label, $cle, $pageCourante) {
		$this->rendu .= '<div class="item_menu';
		if ($pageCourante==$cle)
			$this->rendu .= ' page_courante ';
		
		$this->rendu .= '" id="item_menu_principal_'.$cle.'">{'.($label).'}</div>';
		
	}
	
	function toHTML() {
		$this->rendu.='</div>';
		return $this->rendu;
	}
}

?>
