<?php
/*
 * On reprend le même début qu'avant :
 */
session_start();
require_once 'condb.php';
if ( !isset( $_SESSION['Language'] ) )
{
	$_SESSION['Language'] = 'fr'; //Français par défaut.
}

//On inclut la classe SQLManager et créer un objet.
require_once 'include/SQLManager.php';
$sql = new SQLManager( 'localhost', 'root', '', 'Test' );

//Création du formulaire de saisie :?>
<form action="addForm.php" method="post">

	<!-- On place un tableau dans le formulaire pour le mettre
	     en forme -->
	<table border="1">

		<!-- Titre -->
		<tr>
			<td colspan="2">
				<?php echo $xml->webPageName;?>
			</td>
		</tr>		

		<!-- Saisie de l ID de la balise -->
		<tr>
			<td>
				<?php echo $xml->inputID;?>
			</td>
			<td>
				<input type="text" name="itemId" />
			</td>
		</tr>

		<!-- Saisie du texte pour le nouveau contenu -->
		<tr>
			<td>
				<?php echo $xml->inputData;?>
			</td>
			<td>
				<textarea cols="20" rows="5" name="itemData"></textarea>
			</td>
		</tr>

		<!-- Création de la liste de choix des langues -->
		<tr>
			<td>
				<?php echo $xml->inputLang;?>
			</td>
			<td><?php 
				//On récupère toutes les langues de la base de données SQL
				$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
				
				//Puis on créer la liste ?>
				<select name="itemLanguage" style="width:100%;"><?php 
					foreach ( $langs as $language )
					{?>
						<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
					}?>
				</select>				
			</td>
		</tr>

		<!-- Bouton d envoi pour créer une balise XML -->
		<tr>
			<td colspan="2">
				<input style="width:100%;text-align:center;" type="submit" 
				name="submitCreateItem" value="<?php echo $xml->submitCreate;?>" />
			</td>
		</tr>
	</table>
</form>	<?php 
/*
 * Traitement du formulaire lorsqu'il est soumis
 */
if ( isset( $_POST['submitCreateItem'] ) )
{
	/*
	 * On fait alors appel à la fonction addXmlElement avec les
	 * variables du formulaire, comme la fonction renvoi un booleen
	 * on peut l inclure directement dans la condition du IF :
	 */
	if ( $xml->addXmlElement( $_POST['itemId'], $_POST['itemLanguage'], $_POST['itemData'] ) )
	{
		/* Réussite de l'ajout de l'élément
		 * On affiche un petit message javascript, toujours avec notre classe.
		 */?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->createXmlDone;?>");
		-->
		</script><?php		
	}
	else
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->createXmlError;?>");
		-->
		</script><?php		
	}
}
?>