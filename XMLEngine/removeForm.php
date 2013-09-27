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
 * Inclusion du traitement du formulaire.
 */
require_once 'removeFormTreatment.php';

//Début du formulaire d'administration : ?>
<div style="height:500px;overflow:scroll;" align="center"><?php
	/*
	 * On prépare une fonction JavaScript pour confirmer la suppression
	 * d'un élément, cette fonction renvoi true ou false, permettant d'envoyer
	 * ou non le formulaire pour qu'il soit traité.
	 */ ?>
	<script type="text/javascript">
	<!--
	//Variable javascript globale pour savoir quel bouton submit est appuyé.
	var button;
	function confirmDelete()
	{
		if ( button == "xml" )
		{
			return confirm("<?php echo $xml->confirmDelete;?> '" + document.removeForm.xmlDataId.value + "' ?"); 
		}
		else if ( button == "translation" )
		{
			return confirm("<?php echo $xml->confirmDelete;?> 'translation' de langue '"
							 + document.removeForm.translationLangId.value + "' de l'element '"
							 + document.removeForm.xmlDataId.value + "' ?" );
		}
	}
	-->
	</script>
	<?php
	/*
	 *On ajoute l'attribut 'onsubmit' et 'name' afin d'envoyer le formulaire
	 *uniquement après confirmation de la suppression avec la fonction JS. 
	 */ ?>	
	<form action="removeForm.php" method="post" name="removeForm" onsubmit="return confirmDelete();">
		<table border="1">
			<tr>
				<td><?php 
					echo $xml->element.' <i>xmldata</i>';?>				
				</td>
				
				<td><?php 
					echo $xml->element.' <i>translation</i>';?>
				</td>				
			</tr>
			<tr>
				<td>
					<select name="xmlDataId"><?php 
						/*
						 * On récupère la liste des éléments pour ensuite
						 * l'afficher.
						 */
						$itemList = $xml->getItemNodeList();
						foreach ( $itemList as $item )
						{?>
							<option value="<?php echo $item;?>"><?php echo $item;?></option><?php							
						}?>
					</select>
				</td>
				<td><?php
					//On récupère toutes les langues de la base de données SQL
					$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
				
					//Puis on créer la liste ?>
					<select name="translationLangId" style="width:100%;"><?php 
						foreach ( $langs as $language )
						{?>
							<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
						}?>
					</select>
				</td>
			</tr>
			<tr>			
				<!-- Bouton d envoi pour supprimer un élément xmldata -->
				<td>
					<!-- On utilise l attribut ONCLICK dans lequel on place 
						 du code JavaScript, on assigne alors à la variable
						 button (variable JS) le mot XML ou TRANSLATION-->
					<input type="submit" name="submitDeleteXmlDataElement" value="<?php echo $xml->deleteElement;?>" onclick="button='xml';" />
				</td>
				
				<!-- Bouton d envoi pour supprimer un élément translation -->
				<td>
					<!-- Idem pour l attribut ONCLICK que précédement -->
					<input type="submit" name="submitDeleteTranslationElement" value="<?php echo $xml->deleteElement;?>" onclick="button='translation';"/>
				</td>				
			</tr>
		</table>	
	</form>
</div>