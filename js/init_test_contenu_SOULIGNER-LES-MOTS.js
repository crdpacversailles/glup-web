var editeurs;
var nbEditeurs;
var editeurASupprimer;
var containerEditeurASupprimer;

function initialisationTertiaire() {
	nbEditeurs=0;
 	ajouterEditeurs();
 	document.observe('selecteur:validation', gererClicBoutonValidationPhrase);
 	document.observe('selecteur:edition', gererClicBoutonEditionPhrase);
 	$('bouton_ajouter_phrase').observe('click', gererClicBoutonAjouterPhrase);
 	$('btn_valider_nom_jeu').observe('click', gererClicBoutonValiderNomJeu);
 	$('btn_valider_consigne').observe('click', gererClicBoutonValiderConsigne);
 	donneesPropres();
 	$('test-contenu-consigne').observe('focus', gererPerteFocusChampsSaisie);
 	$('test-contenu-nom-jeu').observe('focus', gererPerteFocusChampsSaisie);
 	$('test-contenu-consigne').observe('mousedown', gererPerteFocusChampsSaisie);
 	$('test-contenu-nom-jeu').observe('mousedown', gererPerteFocusChampsSaisie);
 	$('test-contenu-consigne').observe('blur', gererPerteFocusChampsSaisie);
 	$('test-contenu-nom-jeu').observe('blur', gererPerteFocusChampsSaisie);
 	$('test-contenu-consigne').observe('keypress', gererToucheConsigne);
 	$('test-contenu-nom-jeu').observe('keypress', gererToucheNomJeu);
 	actualiserInvitation();
}
function donneesConsigneSales() {
	$('btn_valider_consigne').removeClassName('bouton_desactive');	
	
}
function donneesNomJeuSales() {
	$('btn_valider_nom_jeu').removeClassName('bouton_desactive');	
}
function donneesPropres() {
	$('btn_valider_consigne').addClassName('bouton_desactive');	
	$('btn_valider_nom_jeu').addClassName('bouton_desactive');	
}
function gererGainFocusConsigne(event) {
	fermerTousEditeursSauf(-1);
}
function gererPerteFocusChampsSaisie(event) {
	envoyerAuServeur();	
}
function gererToucheNomJeu(event) {	
	if(event.keyCode==Event.KEY_RETURN)
		{
			envoyerAuServeur();	
			event.preventDefault();
		} else donneesNomJeuSales();
}
function gererToucheConsigne(event) {	
	if(event.keyCode==Event.KEY_RETURN)
		{
			envoyerAuServeur();	
			event.preventDefault();
		} else donneesConsigneSales();
}
function actualiserInvitation() {
	//la langue a pu changer côté serveur !
	for(var index=0; index<editeurs.length; index++) {
			editeurs[index].invitation= $('texte_invitation').value;
	}
}
 function  ajouterEditeurs() {
 	editeurs=new Array();
 	var tabZonesInsertionsEditeurs = $$('div.insertion_editeur_selecteur');
	tabZonesInsertionsEditeurs.each(insererEditeur);
 }
function insererEditeur(zoneInsertion,  numero, modeEdition) {
	var numZone = nbEditeurs;
	var contenuZone="";
	if(zoneInsertion.firstChild) contenuZone= zoneInsertion.firstChild.nodeValue;
	zoneInsertion.update('');
	editeur=new EditeurSelecteur(numZone, true, $('texte_invitation').value);
	editeur.creerDans(zoneInsertion, modeEdition);
	editeur.afficher(contenuZone);
	editeurs.push(editeur);
	var poubelle= new Element("img");
	poubelle.setAttribute('src', "img/delete.gif");
	poubelle.setAttribute('id', "btn_supprimer_editeur_"+numZone);
	poubelle.addClassName('btn_supprimer_editeur');
	poubelle.setAttribute('title', $('texte_supprimer').value);
	poubelle.observe('click', gererClicBoutonSupprimerPhrase);
	zoneInsertion.insert({after:poubelle});
	nbEditeurs++;
}

function gererClicBoutonSupprimerPhrase(event) {
	var motif =/^btn_supprimer_editeur_(\d*)$/;
	var numero = parseInt (motif.exec(event.target.id)[1]);
	for(var index=0; index<editeurs.length; index++) {
		if(editeurs[index].numero==numero)
			{
				editeurs.splice(index, 1);
				break;
			}
	}
	event.target.remove();
	editeurASupprimer= $("editeur_selecteur_"+numero);
	containerEditeurASupprimer=$("insertion_editeur_selecteur_"+numero);
	new Effect.Shrink(containerEditeurASupprimer, {
		direction: 'top-left',
		duration : 0.5,
		afterFinish:function(){
			editeurASupprimer.remove();
			containerEditeurASupprimer.remove();
		}
		});
	envoyerAuServeur();
}
function extraireNumeroEditeur(identifiantZone) {
	var motif =/^insertion_editeur_selecteur_(\d*)$/;
	return motif.exec(identifiantZone)[1];
}
function gererClicBoutonValidationPhrase(event) {	
	envoyerAuServeur();	
}
function gererClicBoutonValiderConsigne(event) {	
	envoyerAuServeur();	
}
function gererClicBoutonValiderNomJeu(event) {
	envoyerAuServeur();	
}
function envoyerAuServeur() {
	afficherPatienter(true);
	var saisieXML=creerXML("contenu", NAMESPACE);
	var noeudConsigne = saisieXML.createElementNS(NAMESPACE,"consigne");
	var noeudNomJeu = saisieXML.createElementNS(NAMESPACE,"nom_jeu");
	var texteConsigne = saisieXML.createTextNode($('test-contenu-consigne').value);
	var texteNomJeu = saisieXML.createTextNode($('test-contenu-nom-jeu').value);
	noeudConsigne.appendChild(texteConsigne);
	noeudNomJeu.appendChild(texteNomJeu);
	saisieXML.firstChild.appendChild(noeudNomJeu);
	saisieXML.firstChild.appendChild(noeudConsigne);
	
	var noeudContenu = saisieXML.createElementNS(NAMESPACE,"enonces");
	saisieXML.firstChild.appendChild(noeudContenu);
	for(var index=0; index<editeurs.length; index++) {
		var noeudPhrase = saisieXML.createElementNS(NAMESPACE,"enonce");
		var textePhrase = saisieXML.createTextNode(editeurs[index].getPhraseFormatee());
		noeudPhrase.appendChild(textePhrase);
		noeudContenu.appendChild(noeudPhrase);
	}
	var params=new Object();
	params['contenu']=serialiserXML(saisieXML);
	requeteAjax(params);
}
function rappelAjax() {
	donneesPropres();
}
function gererClicBoutonEditionPhrase(event) {
	var numeroEditeurEnCours = extraireNumeroEditeur(event.target.id)
	fermerTousEditeursSauf(numeroEditeurEnCours);	
}
function fermerTousEditeursSauf(exception) {
	for(var index=0; index<editeurs.length; index++) {
		if (editeurs[index].numero !=exception && editeurs[index].editionEnCours) {
			editeurs[index].arreterEdition();
		}
			
	}
}

function gererClicBoutonAjouterPhrase() {
	var zoneInsertion = document.createElement("div");
	var numero= editeurs.length;
	zoneInsertion.id="insertion_editeur_selecteur_"+nbEditeurs;
	var texteZone = document.createTextNode(" ");
	zoneInsertion.appendChild(texteZone);
	zoneInsertion.addClassName('insertion_editeur_selecteur');
	zoneInsertion.hide();
	$('bouton_ajouter_phrase').insert({before:zoneInsertion});
	insererEditeur(zoneInsertion, 0, true);
	
	Effect.Grow(zoneInsertion, {direction : 'top-left', duration:0.3});
}
