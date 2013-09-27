<?php
/*
 * On détecte le lancement du formulaire de traduction pour modifier
 * le fichier XML avant de le réutiliser.
 */
if ( isset( $_POST['submitTraduceFile'] ) )
{
	/*
	 * On supprime la variable du bouton SUBMIT dans le tableau $_POST, et ce pour
	 * pouvoir utiliser le tableau $_POST qui ne contiendra QUE les identifiants
	 * des éléments XML. Si vous ajoutez d'autres balises dans le formulaire, prenez garde
	 * à supprimer ces variables du tableau $_POST ici.
	 */
	unset($_POST['submitTraduceFile']);
	
	//On récupère la langue avant de la supprimer du tableau $_POST.
	$langToTraduce = $_POST['langToTraduce'];
	unset( $_POST['langToTraduce'] );
	
	/*
	 * On lance la fonction ajoutant une langue dans le fichier XML.
	 * Cette fonction est intelligente et ne créera pas de doublon, si tous les
	 * éléments existent déjà elle ne modifiera pas les données.
	 */
	$xml->addLanguageToFile( $langToTraduce );
	
	//Variable permettant de savoir si tout s'est bien passé.
	$allSaveGood = true;
	
	//Maintenant on parcour le tableau et modifie en boucle :
	foreach ( $_POST as $xmlID => $newValue )
	{
		/*
		 * Si vous avez des difficultés avec le foreach :
		 * Ici pour chaque case du tableau, on récupère le nom de la case
		 * avec $xmlID (identifiant XML), et sa valeur avec $newValue (le nouveau texte)
		 * 
		 */
		//Si le champ n'est pas vide.
		if ( $newValue != '' )
		{	
			/*
			 * On modifie l'élément XML en utilisant la valeur de retour de la
			 * fonction qui est un booleen, si ce dernier est faux alors notre
			 * variable de contrôle $allSaveGood sera aussi fausse.
			 */
			if ( !$xml->modifyXMLElement( $xmlID, $langToTraduce, $newValue ) )
			{
				$allSaveGood = false;			
			}
		}
	}
	
	/*
	 * On doit ré-insérer ces deux valeurs dans le tableau $_POST pour que la suite
	 * du fichier se déroule sans problèmes.
	 */
	
	$_POST['langToTraduce'] = $langToTraduce;//Langue à traduire.
	$_POST['submitTraduceFile'] = true;//Comme quoi le formulaire à été envoyé.
	
}//Fin du traitement du formulaire de traduction 
?>