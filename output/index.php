<?php
require_once '../libs/Securite.class.php';

if (!Securite::nettoyerPost()) {
	header('Location: ../erreur.php'); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">	
    <head>
        <title>GLUP</title>         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       	<link rel="stylesheet" type="text/css" href="styles.css"  media="screen"/>
        <script type="text/javascript" src="history/history.js"></script>		    
        <script type="text/javascript" src="swfobject.js"></script>
        
        <script type="text/javascript">
            var swfVersionStr = "10.0.0";
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {};
            var params = {};
            params.quality = "high";
            params.bgcolor = "#e8e8e8";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf";
            attributes.name = "<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf";
            attributes.align = "middle";
            swfobject.embedSWF(
                "<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf", "flashContent", 
                "800", "600", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
			swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        </script>
    </head>
    <body>
	<div class="cadre">
        <div id="flashContent">
        	<p>
  				10.0.0 or greater is installed. 
			</p>
			<script type="text/javascript"> 
				var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
				document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
								+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
			</script> 
        </div>

       	<noscript>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="600" id="<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf">
                <param name="movie" value="<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#e8e8e8" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="true" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="<?php echo htmlspecialchars(Securite::$_CLEAN['jeu'])?>.swf" width="800" height="600">
                    <param name="quality" value="high" />
                    <param name="bgcolor" value="#e8e8e8" />
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="true" />
                <!--<![endif]-->
                <!--[if gte IE 6]>-->
                	<p> 
                		Soit vous n'avez pas l'autorisation d'exécuter des programmes, soit vous n'avez pas installé la version
                		10.0.0 du flash player au minimum.
                	</p>
                <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflashplayer">
                        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                    </a>
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
	    </noscript>	
		</div>	   	
   </body>
</html>
