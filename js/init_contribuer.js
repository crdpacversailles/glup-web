var memoireChamps = new Object();
var champVierges = new Object();
function initialisationSecondaire() {
	
	gererAffichage($('titre_message'));
	gererAffichage($('adresse_message'));
	gererAffichage($('texte_message'));
	$('btn_envoyer_email').observe('click', envoyerMessage)
 }
function gererAffichage(element) {
 	champVierges[element.id]=true;
 	memoireChamps[element.id]=element.value;
 	element.observe('click', viderChamp);
 	element.observe('blur', remplirChamp);
 }
 function reinitialiser(element) {
 	champVierges[element.id]=true;
 	element.value=memoireChamps[element.id];
 }
function viderChamp(event) {
 	if (champVierges[event.element().id]) {
 		memoireChamps[event.element().id]=event.element().value;
 		event.element().value="";
 	}
 }
 function remplirChamp(event) {
 	if (event.element().value.match(/^\s*$/)) 
 		event.element().value=memoireChamps[event.element().id];
 	else
 		champVierges[event.element().id]=false;
 }
 function envoyerMessage() {
 	if ($('texte_message').value.match(/^\s*$/)) {
 		 Avertissement.afficher($('avertissement_message_vide').value);
 		 return;
 	}
 	else var texteMessage=$('texte_message').value;
 	if (! mailValide($('adresse_message').value)) {
 		 Avertissement.afficher($('avertissement_email_invalide').value);
 		 return;
 	}
 	else var adresseMessage=$('adresse_message').value;
 	var titreMessage="Sans titre";
 	if (!$('titre_message').value.match(/^\s*$/)) {
 		titreMessage=$('titre_message').value;
 	}
 	
 	var params=new Object(); 
	params['adresse_message']=adresseMessage;
	params['titre_message']=titreMessage;
	params['texte_message']=texteMessage;
	requeteAjax(params);
 }
 function mailValide(sMail) {
 	var re=/^[a-z\d]+((\.|-|_)[a-z\d]+)*@((?![-\d])[a-z\d- ]{0,62}[a-z\d]\.){1,4}[a-z]{2,6}$/gi;
	return (sMail.match(re)==sMail) && (sMail.substr(sMail.lastIndexOf("@")+1).length<=255);
 }
 function rappelAjax(data) {
 	if (data.responseText=="ok") {
 	reinitialiser($('titre_message'));
	reinitialiser($('adresse_message'));
	reinitialiser($('texte_message'));
 	Avertissement.afficher($('confirmation_envoi_email').value);
 	} else Avertissement.afficher($('infirmation_envoi_email').value);
 }