<?php
/*
 * On fait une nouvelle page,
 * On reprend le même début qu'avant :
 */
session_start();
require_once 'include/XMLEngine.php';

require_once 'condb.php';


if ( !isset( $_SESSION['Language'] ) )
{
	$_SESSION['Language'] = 'fr'; //Français par défaut.
}

$xml = new XMLEngine( 'xml/website.xml', $_SESSION['Language'] );

/*
 * On inclus ici le traitement du formulaire qui sera vu ensuite.
 * Il faut voir le 'require_once' comme un copier/coller, donc le
 * fichier 'traduceTreatement.php' est uniquement à inclure.
 */
require_once 'traduceFormTreatment.php';

//Début du formulaire d'administration : ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Traduction glup</title>
</head>
<body>

<div align="center"><?php
/*
 * Pour commencer il faut créer un formulaire pour choisir la
 * langue dans laquelle traduire, on affichera ce formulaire que
 * si aucun autre n'a été envoyé.
 */
if ( !isset( $_POST['submitLangToTraduce'] ) && !isset( $_POST['submitTraduceFile'] ) )
{?>
<form action="traduceForm.php" method="post">
	<table border="1">
		<tr>
			<td><?php
				echo $xml->foreignLang;?>			
			</td>			
		</tr>
		
		<!-- On crée la liste de langues -->
		<tr>
			<td align="center"><?php 
				//On récupère toutes les langues de la base de données SQL
				$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
				//Puis on créer la liste ?>
				<select name="langToTraduce" style="width:100%;"><?php 
					foreach ( $langs as $language )
					{?>
						<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
					}?>
				</select>			
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<input type="submit" name="submitLangToTraduce" value="<?php echo $xml->submitChooseLang;?>" />
			</td>
		</tr>
	</table>
</form><?php 
} //Fin du formulaire de choix des langues

/*
 * Une fois la langue choisie ou le traitement effectué on affiche le formulaire 
 * avec les éléments du fichier website.xml
 */ 
if ( isset( $_POST['submitLangToTraduce']) || isset( $_POST['submitTraduceFile'] ) )
{
	//On créer un nouvel objet xml pour la langue à traduire
	$xmlTraductor = new XMLEngine( 'xml/website.xml', $_POST['langToTraduce'] );?>
	
	<form action="traduceForm.php" method="post">
		<table border="1">
			
			<!-- Titre -->
			<tr>
				<td colspan="3">
					<?php echo 'GLUP';?>
				</td>
			</tr>
			
			<!-- Cellules d en-tête -->
			<tr>
				<td>
					<?php echo $xml->frLang;?>
				</td>
				<td>
					<?php 
					/*
					 * Si on utilise toujours la même syntaxe pour les identifiants xml
					 * qui représente le nom d'une langue, on peut créer le nom de la variable
					 * à l'aide des variables dynamiques sans la connaître, on va ainsi créer
					 * le nom de la variable sous forme de chaîne de caractères avant de 
					 * l'utiliser. Ici on aura frLang ou enLang.
					 */
					echo $xml->{ $_POST['langToTraduce'].'Lang' };?>
				</td>
				<td>
					<?php echo $xml->newContent;?>
				</td>
			</tr><?php
			
			//On récupère les identifiants des balises :
			$idTab = $xml->getItemNodeList();		
			/*
			 * On parcours le tableaux des identifiants en créant à chaque
			 * fois une ligne qui contient la version française en lecture seule (langue maternelle)
			 * et à droite la langue à traduire, puis tout à droite, le champs pour modifier
			 * une valeur si l'on veut, ou remplir un champ vide.
			 */ 
			foreach ( $idTab as $cell )
			{?>
			<tr>
				<td>
					<input type="text" readonly="readonly" value="<?php echo $xml->$cell;?>" style="width:500px;" />
				</td>
				<td>
					<input type="text" readonly="readonly" value="<?php echo $xmlTraductor->$cell;?>" style="width:500px;" />
				</td>
				
				<!-- Il faut donner un nom au champ que l on remplira, ce nom sera celui de l ID de
					 la balise XML  -->
				<td>
					<textarea name="<?php echo $cell;?>" rows="1" cols="70"></textarea>
				</td>
			</tr><?php
			}
			
			//Puis un bouton d'envoi pour effectuer les modifications :?>
			<tr>
				<td colspan="3">
					<input type="hidden" name="langToTraduce" value="<?php echo $_POST['langToTraduce'];?>" />
					<input type="submit" name="submitTraduceFile" value="<?php echo $xml->submitTraduce;?>" style="width:100%;text-align:center;" />			
				</td>
			</tr>
		</table>
	</form><?php 
} //Fin du formulaire de traduction 			

//Ici on vérifie que tout s'est bien déroulé pour afficher un message JScript.
if ( isset( $_POST['submitTraduceFile'] ) )
{
	//On vérifie notre variable de contrôle et affiche le bon message.
	if ( $allSaveGood )
	{	
		//Succès?>
		<script type="text/javascript">
			alert("<?php echo $xml->modificationDone;?>");
		</script><?php								
	}
	else
	{	
		//Echec?>
		<script type="text/javascript">
			alert("<?php echo $xml->modificationError;?>");
		</script><?php
	}
}?>
</div>
</body>
</html>