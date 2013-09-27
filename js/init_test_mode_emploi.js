var containerASupprimer;

function initialisationTertiaire() {
 	$('bouton_ajouter_paragraphe_mode_emploi_debut').observe('click', gererClicBoutonAjouterParagraphe);
 	$('bouton_ajouter_paragraphe_mode_emploi_fin').observe('click', gererClicBoutonAjouterParagraphe);
 	$('bouton_enregister_mode_emploi').observe('click', gererClicBoutonEnregistrer);
 	donneesPropres();
 	$('bouton_mode_emploi_par_defaut').observe('click', gererClicBoutonDefaut);
 	if($('modification_donnees').value=="oui") donneesModifiees();
 	else donneesOriginales(); 
 	$$('.btn_supprimer_paragraphe').each(ecouterBoutonSupprimer);
 	$$('textarea').each(activerEnregistrementAuto);
}
function activerEnregistrementAuto(target) {
	target.observe('blur', enregistrementAuto);
	Event.observe(target, 'keypress', gererTouche);
}
function desactiverEnregistrementAuto(target) {
	target.stopObserving('blur', enregistrementAuto);
	Event.stopObserving(target, 'keypress', gererTouche);
}
function donneesOriginales(event) {
	$('bouton_mode_emploi_par_defaut').addClassName('desactive');	
}
function donneesModifiees() {
	$('bouton_mode_emploi_par_defaut').removeClassName('desactive');	
}
function donneesSales(event) {
	$('bouton_enregister_mode_emploi').removeClassName('desactive');	
}
function donneesPropres() {
	$('bouton_enregister_mode_emploi').addClassName('desactive');	
}
function enregistrementAuto(event) {
	envoyerAuServeur();
}
function gererTouche(event) {	
	if(event.keyCode==Event.KEY_RETURN)
		{
			enregistrementAuto(null);
			event.preventDefault();
		} else {
			donneesSales(null);
			donneesModifiees();
		}
}
function ecouterBoutonSupprimer(bouton) {
	bouton.observe("click", gererClicBoutonSupprimerParagraphe);
}
function gererClicBoutonSupprimerParagraphe(event) {
	containerASupprimer=event.element().up('div.paragraphe_mode_emploi');
	new Effect.Shrink(containerASupprimer, {
		direction: 'top-left',
		duration : 0.5,
		afterFinish:function(){
			desactiverEnregistrementAuto(containerASupprimer);
			containerASupprimer.remove();
			enregistrementAuto(null);
		}
		});
}
function gererClicBoutonAjouterParagraphe(event){
		insererZoneSaisie(event.element()==$('bouton_ajouter_paragraphe_mode_emploi_debut'));
}


function insererZoneSaisie(debut) {	
	var container = new Element("div");
	container.addClassName("paragraphe_mode_emploi");
	var zone = new Element("textarea");
	zone.writeAttribute("rows", "4");
	zone.writeAttribute("cols", "60");
	activerEnregistrementAuto(zone);
	container.hide();
	container.insert({top:zone});
	var poubelle= new Element("img");
	poubelle.setAttribute('src', "img/delete.gif");
	poubelle.setAttribute('id', "btn_supprimer_paragraphe");
	poubelle.addClassName('btn_supprimer_paragraphe');
	//poubelle.setAttribute('title', $('texte_supprimer').value);
	ecouterBoutonSupprimer(poubelle);
	container.insert({bottom:poubelle});
	if(debut)
	$('bouton_ajouter_paragraphe_mode_emploi_debut').insert({after:container});
	else
	$('bouton_ajouter_paragraphe_mode_emploi_fin').insert({before:container});
	Effect.Grow(container, {direction : 'top-left', duration:0.3});
}

function gererClicBoutonEnregistrer(event) {	
	if(event && event.element() && event.element().hasClassName('desactive')	) return;
	envoyerAuServeur();	
}
function envoyerAuServeur() {
	afficherPatienter(true);
	var saisieXML=creerXML("mode_emploi", NAMESPACE);
	var zonesSaisie = $$("textarea");
	for(var index=0; index<zonesSaisie.length; index++) {
		var noeudParagraphe = saisieXML.createElementNS(NAMESPACE, "paragraphe");
		var texteParagraphe = saisieXML.createTextNode(zonesSaisie[index].value);
		noeudParagraphe.appendChild(texteParagraphe);
		saisieXML.firstChild.appendChild(noeudParagraphe);
	}
	var params=new Object();
	params['mode_emploi']=serialiserXML(saisieXML);
	requeteAjax(params);
}
function rappelAjax() {
	donneesPropres();
}
function gererClicBoutonDefaut(event) {
	if(event && event.element() && event.element().hasClassName('desactive')	) return;
	envoyerPhp('mode_emploi_par_defaut', '1');
}


