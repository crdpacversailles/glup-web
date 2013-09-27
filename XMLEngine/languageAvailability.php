<?php
/*
 * On fait une nouvelle page,
 * On reprend le même début qu'avant :
 */
session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>Glup / traduction</title>

	</head>

	<body>
<?php
require_once 'include/XMLEngine.php';


require_once 'condb.php';

if ( !isset( $_SESSION['Language'] ) )
{
	$_SESSION['Language'] = 'fr'; //Français par défaut.
}

$xml = new XMLEngine( 'xml/website.xml', $_SESSION['Language'] );

/*
 * Traitement du formulaire de vérification
 */
if ( isset( $_POST['submitCheckLanguage'] ) )
{
	/*
	 * Pour vérifier les langues de tous les fichiers, il
	 * faut auparavant mettre les fichiers XML à part dans un
	 * répertoire seul. Puis on ouvre ce répertoire :
	 */
	$i=0;
	$dirRes = opendir( 'xml' );
	
	//Ensuite on lit les noms des fichiers un par un
	while ( false !== ( $file = readdir( $dirRes ) ) )
	{
		//Si on a bien un fichier XML
		if ( strpos( $file, '.xml' ) )
		{
			//On sauvegarde le nom dans un tableau
			$xmlTab[$i++] = $file;	
		}
	}
	
	//Puis si le tableau existe, on va vérifier tous les fichiers
	if ( isset( $xmlTab ) )
	{
		//On sauvegarde le fichier utilisé acutellement
		$CFile = $xml->getCurrentFile();
		
		//Variable de contrôle sur true par défaut
		$fileChecker = true;
		
		//On parcours la liste de fichier pour les vérifier
		foreach ( $xmlTab as $file )
		{
			//On change le fichier dans l'objet XMLEngine
			$xml->changeFile( 'xml/'.$file );
			
			/*
			 * Puis on vérifie son intégrité avec la fonction de la classe
			 * qui renvoi true ou false.
			 */
			if ( !$xml->checkIntegrity( $_POST['languageToCheck'] ) )
			{
				//En cas d'échec on affiche une alerte pour chaque fichier?>
				<script type="text/javascript">
				<!--
					alert("<?php echo $xml->fileCheckError.' : '.$file;?>");
				//-->
				</script><?php
				$fileChecker = false;//Variable de contrôle sur false
			}
		}
		/*
		 * Une fois les fichiers analysés, on replace le fichier utilisé
		 * initialement par la classe.
		 */ 
		$xml->changeFile( $CFile );
		
		/*
		 * Puis si la variable de contrôle l'autorise, on met à jour la base de données
		 * pour mettre le champ 'IsAvailable' sur 1 ( ou true )
		 */
		if ( $fileChecker )
		{
			$fields[0]='IsAvailable';$values[0]=true;
			$sql->updateQuery( 'Languages', $fields, $values, 'Language', '=', $_POST['languageToCheck'] );
			
			//Puis on affiche un message?>			
			<script type="text/javascript">
			<!--
				alert("<?php echo $xml->checkLanguageDone;?>");
			//-->
			</script><?php
		}
	}
}

//Début du formulaire d'administration : ?>

<div style="height:500px;overflow:scroll;" align="center">
	<form action="languageAvailability.php" method="post">
		<table border="1">
			<tr>
				<td><?php 
					echo $xml->languageToCheck;?>
				</td>
			</tr>
			<tr>
				<td><?php 
					//On récupère toutes les langues de la base de données SQL
					$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
					
					//Puis on créer la liste ?>
					<select name="languageToCheck" style="width:100%;"><?php 
						foreach ( $langs as $language )
						{?>
							<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
						}?>
					</select>				
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submitCheckLanguage" value="<?php echo $xml->checkIt;?>" />
				</td>
			</tr>	
		</table>	
	</form>
</div>
</body>
</html>