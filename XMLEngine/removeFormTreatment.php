<?php
/*
 * Fichier uniquement à inclure.
 * Traitement du formulaire de suppression des éléments lorsque celui-ci
 * est envoyé. On doit effectuer deux traitements differents selon le
 * bouton submit utilisé.
 */
if ( isset( $_POST['submitDeleteXmlDataElement'] ) )
{
	/*
	 * Pour supprimer un élément xmldata, on utilise la méthode de
	 * la classe XMLEngine, qui renvoi true ou false.
	 */
	if ( $xml->completelyRemoveXmlElement( $_POST['xmlDataId'] ) )
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->modificationDone;?>");
		-->
		</script><?php		
	}
	else
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->modificationError;?>");
		-->
		</script><?php		
	}
}
else if ( isset( $_POST['submitDeleteTranslationElement'] ) )
{
	/*
	 * Pour supprimer un élément translation, on utilise la méthode de
	 * la classe XMLEngine, qui renvoi true ou false.
	 */
	if ( $xml->removeXmlElement( $_POST['xmlDataId'], $_POST['translationLangId'] ) )
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->modificationDone;?>");
		-->
		</script><?php		
	}
	else
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->modificationError;?>");
		-->
		</script><?php		
	}
}?>