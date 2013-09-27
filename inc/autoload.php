<?php
function __autoload($nom_classe) {
	if (preg_match('/^I/', $nom_classe))
	require_once 'libs/' . $nom_classe . '.iface.php';
    else require_once 'libs/' . $nom_classe . '.class.php';
}
?>