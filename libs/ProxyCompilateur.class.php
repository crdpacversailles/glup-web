<?php
class ProxyCompilateur {
	public static function compiler($jeu, $largeur, $hauteur, $contenu, $options) {

		$adresse = '195.221.98.80';
		$port = 5000;
		$id = uniqid();
		$paquet = ProxyCompilateur :: remplacerDollars($id) . '$' . ProxyCompilateur :: remplacerDollars($jeu) . '$' . ProxyCompilateur :: remplacerDollars($contenu) . '$' . ProxyCompilateur :: remplacerDollars($options) . '$' . ProxyCompilateur :: remplacerDollars($largeur) . '$' . ProxyCompilateur :: remplacerDollars($hauteur) . '$';
		//Création de la socket.
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		//Connexion au serveur.
		socket_connect($socket, $adresse, $port);
		socket_write($socket, $paquet, strlen($paquet));
		while (true) {
			if ($retour = socket_read($socket, 2))
				break;
		}

		socket_close($socket);
		sleep(1);
		return "jeu_" . $id;
	}
	public static function compresser($nomFichier) {

	}
	private static function remplacerDollars($chaine) {
		$pattern = "/\\\$/";
		$replacement = '§§';
		return preg_replace($pattern, $replacement, $chaine);
	}
}
?>