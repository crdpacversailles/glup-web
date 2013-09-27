<?php
class GestionIODonnees {
	public static function exporter($jeu, $exercice, $contenu, $options, $modeEmploi, $largeur, $hauteur) {
		$id = uniqid();
		$XMLCombine = new DOMDocument('1.0', 'utf-8');
		$XMLOptions = new DOMDocument('1.0', 'utf-8');
		$XMLModeEmploi = new DOMDocument('1.0', 'utf-8');
		$XMLContenus = new DOMDocument('1.0', 'utf-8');
		$XMLOptions->loadXML($options);
		$XMLContenus->loadXML($contenu);
		$XMLModeEmploi->loadXML($modeEmploi);
		$root = $XMLCombine->createElementNS(Modele::$NAMESPACE, "glup");
		$XMLCombine->appendChild($root);
		$noeudOptions = $XMLOptions->getElementsByTagNameNS(Modele::$NAMESPACE, "options")->item(0);
		$noeudContenus = $XMLContenus->getElementsByTagNameNS(Modele::$NAMESPACE, "contenu")->item(0);
		$noeudModeEmploi = $XMLModeEmploi->getElementsByTagNameNS(Modele::$NAMESPACE, "mode_emploi")->item(0);
		$copieNoeudOptions = $XMLCombine->importNode($noeudOptions, true);
		$copieNoeudOptions->setAttribute("jeu", $jeu);
		$copieNoeudOptions->setAttribute("langue", Traducteur :: getLangue());
		$copieNoeudOptions->setAttribute("largeur", $largeur);
		$copieNoeudOptions->setAttribute("hauteur", $hauteur);
		$copieNoeudContenus = $XMLCombine->importNode($noeudContenus, true);
		$copieNoeudContenus->setAttribute("exercice", $exercice);
		$copieNoeudModeEmploi = $XMLCombine->importNode($noeudModeEmploi, true);
		$root->appendChild($copieNoeudOptions);
		$root->appendChild($copieNoeudContenus);
		$root->appendChild($copieNoeudModeEmploi);
		$XMLCombine->formatOutput = true;
		$XMLCombine->preserveWhiteSpace = false;
		$XMLCombine->save('files/' . $id . '.glup');
		return $id;
	}
	public static function importer($modele, $donnees) {
		$XML = new DOMDocument('1.0', 'utf-8');
		$donnees = preg_replace("/[\r\n\t]/","",$donnees);
		$donnees = preg_replace("/\s+/"," ",$donnees);
		$donnees = preg_replace("/>\s+/",">",$donnees);
		$donnees = preg_replace("/\s+</","<",$donnees);
		if (!$XML->loadXML($donnees))
			return ("ko");
		$XML->preserveWhiteSpace = false;
		if (!$XML->schemaValidate('data/glup.xsd'))
			return ("ko");
		$XMLOptions = new DOMDocument('1.0', 'utf-8');
		$racineOptions = $XML->getElementsByTagNameNS(Modele::$NAMESPACE, "options")->item(0);
		$copieRacineOptions = $XMLOptions->importNode($racineOptions, true);
		$XMLOptions->appendChild($copieRacineOptions);
		$options = $XMLOptions->saveXML();

		$jeu = $racineOptions->getAttribute("jeu");
		$langue = $racineOptions->getAttribute("langue");
		$largeur = $racineOptions->getAttribute("largeur");
		$hauteur = $racineOptions->getAttribute("hauteur");

		$XMLContenu = new DOMDocument('1.0', 'utf-8');
		$racineContenu = $XML->getElementsByTagNameNS(Modele::$NAMESPACE, "contenu")->item(0);
		$copieRacineContenu = $XMLContenu->importNode($racineContenu, true);
		$XMLContenu->appendChild($copieRacineContenu);
		$contenu = $XMLContenu->saveXML();
		$exercice = $racineContenu->getAttribute("exercice");

		$XMLModeEmploi = new DOMDocument('1.0', 'utf-8');
		$racineModeEmploi = $XML->getElementsByTagNameNS(Modele::$NAMESPACE, "mode_emploi")->item(0);
		$copieRacineModeEmploi = $XMLModeEmploi->importNode($racineModeEmploi, true);
		$XMLModeEmploi->appendChild($copieRacineModeEmploi);
		$modeEmploi = $XMLModeEmploi->saveXML();

		Traducteur :: assignerLangue($langue);
		$modele->modifierExercice($exercice);
		$modele->modifierJeu($jeu);
		$modele->setOptionsJeu($options);
		$modele->setContenuExercice($contenu);
		$modele->setModeEmploiJeu($modeEmploi);
		$modele->setLargeurJeu($largeur);
		$modele->setHauteurJeu($hauteur);
		return "ok";
	}

}
?>