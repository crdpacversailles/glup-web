<?php
class GestionEmails {
	
	public static function envoyer($adresse, $titre, $texte) {
		$de_nom = "Site Glup"; //Nom de l'envoyeur
        $de_mail = $adresse; //Email de l'envoyeur
        $vers_nom = "Administration site Glup"; //Nom du receveur
        $vers_mail = "joachim.dornbusch@crdp.ac-versailles.fr";
        $sujet = $titre; //Sujet du mail
        $message = $texte;

        $entete = "MIME-Version: 1.0\r\n";
        $entete .= "Content-type: text/html; charset=utf-8\r\n";
        $entete .= utf8_encode("To: $vers_nom <$vers_mail>\r\n");
        $entete .= utf8_encode("From: $de_nom <$de_mail>\r\n");
        if (mail($vers_mail, utf8_encode($sujet), $message, $entete))
		return true;
		else return false;
	}
	
}
?>