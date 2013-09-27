var actionsTest=["index", "pedagogie", "contenu", "gameplay", "options", "mode_emploi", "export"]

function requeteAjax(params, nePasAfficherPatienter) {
	if(!nePasAfficherPatienter) afficherPatienter(true);
	new Ajax.Request('enregistrer-ajax.php', {
  		method: 'post',
 	 	parameters: params,
 	 	onSuccess: gererRetourEnregistrement
  	});
	
}
function serialiserXML(xmlObject) {
	var xmlString;
	if (Prototype.Browser.IE) {
	// Internet Explorer
	xmlString = xmlObject.xml;
	} else {
	// Autres (Mozilla, Opera, etc.)
	xmlString = (new XMLSerializer()).serializeToString(xmlObject);
	}
	
	return xmlString;
}
function gererRetourEnregistrement(data) {
	afficherPatienter(false);
	if (typeof(rappelAjax) == 'function') rappelAjax(data);
}
function afficherPatienter(bool) {
	if (bool) {
		$('patienter').show();
	} else {
		$('patienter').hide();
	}
}
function creerXML(rootTagName, namespaceURL) {
  if (!rootTagName) rootTagName = "";
  if (!namespaceURL) namespaceURL = "";
  if (document.implementation && document.implementation.createDocument) {
    // This is the W3C standard way to do it
    return document.implementation.createDocument(namespaceURL, rootTagName, null);
  }
  else { 
  // This is the IE way to do it
    // Create an empty document as an ActiveX object
    // If there is no root element, this is all we have to do
    var doc = new ActiveXObject("MSXML2.DOMDocument");
    // If there is a root tag, initialize the document
    if (rootTagName) {
      // Look for a namespace prefix
      var prefix = "crdp";
      var tagname = rootTagName;
      var p = rootTagName.indexOf(':');
      if (p != -1) {
        prefix = rootTagName.substring(0, p);
        tagname = rootTagName.substring(p+1);
      }
      // If we have a namespace, we must have a namespace prefix
      // If we don't have a namespace, we discard any prefix
      if (namespaceURL) {
        if (!prefix) prefix = "a0"; // What Firefox uses
      }
      else prefix = "";
      // Create the root element (with optional namespace) as a
      // string of text
      var text = "<" + (prefix?(prefix+":"):"") +  tagname +
          (namespaceURL
           ?(" xmlns:" + prefix + '="' + namespaceURL +'"')
           :"") +
          "/>";
      // And parse that text into the empty document
      doc.loadXML(text);
    }
    return doc;
  }
}
function envoyerSurPage(idPage, idAction){
	if(!idAction) idAction="index";
	var actionCourante=$('action_courante').value;
	 
	if(idPage=="test" && actionCourante != idAction && false) {
		var decalage=-2000;
		if(actionsTest.indexOf(idAction)<actionsTest.indexOf(actionCourante)) decalage*=-1;
		new Effect.Move($$('.corps_de_page')[0], {
			x :decalage,
			mode:'absolute',
			duration:0.05,
			afterFinish:function(){
				//fauxFormulaire(idPage,idAction);
			}
			});
	} 
	 fauxFormulaire(idPage, idAction);
		
}
function fauxFormulaire(idPage, idAction){
	var form = $('html_form');
	form.writeAttribute("action", "./"+idPage+"+"+idAction);
	form.writeAttribute('method', 'GET');
	form.submit();
}
function envoyerPhp(name, value, page){

	var form = $('html_form');
	
	var nouvelInput = new Element('input');

	nouvelInput.writeAttribute('type', 'hidden');
	
	

	nouvelInput.writeAttribute('name', name);

	nouvelInput.writeAttribute('value', value);

	form.insert(nouvelInput);
	form.writeAttribute('action', page?page:'index.php');
	form.writeAttribute('method', 'GET');

	form.submit();

}
function chiffresSeulement(chaine) {
	var filtre=/[0-9]+/;
	var resultat=filtre.exec(chaine);
	if(!resultat) resultat=[0];
	return resultat.join();
	
}