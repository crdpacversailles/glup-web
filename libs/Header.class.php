<?php
class Header {

	private $rendu;
	private $css;
	private $scripts;
	private $padLangues;

	function Header() {
		$this->rendu = ChargeurHtml :: charger('header');
		$this->css = '';
		$this->scripts = '';
		$this->ajouterCss('styles_ios');
		$this->ajouterCss('styles');
		$this->creerPadLangues();
	}
	function creerPadLangues() {
		$this->padLangues= new PadLangues();
	}
	function ajouterCss($nomCSS) {
		$this->css .= '<link rel="stylesheet" type="text/css" href="styles/' . $nomCSS . '.css" />';
	}
	function ajouterScript($nomScript) {
		$this->scripts .= '<script src="js/' . $nomScript . '.js" type="text/javascript"></script>';
	}
	function toHTML() {
		$this->rendu=str_replace('[STYLES]', $this->css, $this->rendu);
		$this->rendu=str_replace('[SCRIPTS]', $this->scripts, $this->rendu);
		$this->rendu=str_replace('[LANGUES]', $this->padLangues->toHTML(), $this->rendu);
		return $this->rendu;
	}
}

?>
