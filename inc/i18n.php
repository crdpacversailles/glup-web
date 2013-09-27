<?php
/*
 * Created on 24 janv. 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'libs/XMLEngine.php';

if ( !isset( $_SESSION['langue'] ) )
{
	$_SESSION['langue'] = 'fr'; //Français par défaut.
}
$textes = new XMLEngine( 'langues/website.xml', $_SESSION['langue'] );


?>
