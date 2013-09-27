<?php
class Footer {

	private $rendu;
	private $css;
	private $scripts;

	function Footer() {
		$this->rendu = ChargeurHtml :: charger('footer');
		$this->rendu=str_replace('[URL]', $_SESSION['page'].'+'.$_SESSION['action'], $this->rendu);
		$this->rendu=str_replace('[ACTION]', $_SESSION['action'], $this->rendu);
		$this->rendu=str_replace('[PAGE]', $_SESSION['page'], $this->rendu);
	}
	function toHTML() {
		return $this->rendu;
	}
}

?>
