var CHAMPS_NUMERIQUES = [ "largeur", "hauteur", "taille_police",
		"intervalle_min", "intervalle_max" ];

function initialisationTertiaire() {
	$('bouton_enregister_options').observe('click', sauvegarderOptions);

	donneesPropres();
	$('chrono').observe('change', miseAJourChampsChrono);
	$('bouton_options_par_defaut').observe('click', gererClicBoutonDefaut);
	$$('input').each(activerEnregistrementAuto);
	miseAJourChampsChrono(null);
	if ($('modification_donnees').value == "oui")
		donneesModifiees();
	else
		donneesOriginales();
}
function activerEnregistrementAuto(target) {
	if (target.hasClassName("valeur_fixe")) {
		target.observe('click', gererClicElementsInactifs);
	} else if (target.getAttribute('type') == 'text') {
		target.observe('blur', sauvegarderOptions);
		Event.observe(target, 'keypress', gererTouche);
	} else if (target.getAttribute('type') == 'checkbox') {
		target.observe('click', sauvegarderOptions);
		Event.observe(target, 'change', donneesSales);
	}

}
function donneesOriginales(event) {
	$('bouton_options_par_defaut').addClassName('desactive');
}
function donneesModifiees() {
	$('bouton_options_par_defaut').removeClassName('desactive');
}
function donneesSales(event) {
	$('bouton_enregister_options').removeClassName('desactive');
}
function donneesPropres() {
	$('bouton_enregister_options').addClassName('desactive');
}
function gererTouche(event) {
	if (event.keyCode == Event.KEY_RETURN) {
		sauvegarderOptions(null);
		event.preventDefault();
	} else {
		if (donneeAcceptable(parseInt(event.keyCode), parseInt(event.charCode),
				event.element().id)) {
			donneesSales();
			donneesModifiees();
		} else
			event.stop();
	}
}
function donneeAcceptable(keyCode, charCode, idChamp) {
	var cd = 0;
	if (isNaN(keyCode))
		cd = charCode;
	else if (isNaN(charCode))
		cd = keyCode;
	else
		cd = keyCode > charCode ? keyCode : charCode;
	if (CHAMPS_NUMERIQUES.indexOf(idChamp) != -1)
		return (cd == Event.KEY_BACKSPACE || cd == Event.KEY_LEFT
				|| cd == Event.KEY_RIGHT || cd == Event.KEY_DELETE || cd == Event.KEY_TAB)
				|| (cd >= 48 && cd <= 57);
	else
		return true;
}

function filtrerContenu(element) {
	if (element.getAttribute('type') == 'text')
		element.value = chiffresSeulement(element.value);
}
function miseAJourChampsChrono(event) {
	if ($('chrono').checked) {
		$('duree_chrono').enable();
	} else
		$('duree_chrono').disable();

}
function sauvegarderOptions(event) {
	if (event && event.element() && event.element().hasClassName('desactive'))
		return;
	$$('input').each(filtrerContenu);
	envoyerAuServeur();
}
function envoyerAuServeur() {
	afficherPatienter(true);
	var saisieXML = creerXML("options", NAMESPACE);
	var noeudParametres = saisieXML.createElementNS(NAMESPACE, "parametres");
	
	var noeudParametresGeneraux = saisieXML.createElementNS(NAMESPACE, "generaux");
	var noeudParametresSpecifiques = saisieXML
			.createElementNS(NAMESPACE, "specifiques");
	noeudParametres.appendChild(noeudParametresGeneraux);
	noeudParametres.appendChild(noeudParametresSpecifiques);
	saisieXML.firstChild.appendChild(noeudParametres);

	var widgetsGeneraux = $$('div.widget_option.option_generale');
	var widgetsSpecifiques = $$('div.widget_option.option_specifique');
	collecterContenu(saisieXML, widgetsGeneraux, noeudParametresGeneraux);
	collecterContenu(saisieXML, widgetsSpecifiques, noeudParametresSpecifiques);
	var params = new Object();
	params['options'] = serialiserXML(saisieXML);
	params['largeur'] = $('largeur').value;
	params['hauteur'] = $('hauteur').value;
	requeteAjax(params);
}
function rappelAjax() {
	donneesPropres();
}

function collecterContenu(docXML, tabWidgets, noeudXML) {
	for ( var index = 0; index < tabWidgets.length; index++) {
		var input = tabWidgets[index].down('input');
		if (input.id == "largeur" || input.id == "hauteur")
			continue;
		var noeud = docXML.createElementNS(NAMESPACE, "" + input.id);
		noeudXML.appendChild(noeud);
		var contenu = "";
		if (input.getAttribute('type') == 'text') {
			contenu = input.value;
		} else if (input.getAttribute('type') == 'checkbox') {
			contenu = input.checked ? 'oui' : 'non';
			if (input.disabled) {
				var attrb = docXML.createAttribute("type");
				attrb.nodeValue = "fixe";
				noeud.setAttributeNode(attrb);
			}
		}
		var texte = docXML.createTextNode(contenu);
		noeud.appendChild(texte);

	}
}
function gererClicBoutonDefaut(event) {
	if (event && event.element() && event.element().hasClassName('desactive'))
		return;
	envoyerPhp('options_par_defaut', '1');
}
function gererClicElementsInactifs(event) {
	Avertissement
			.afficher($("TEST-OPTIONS-AVERTISSEMENT-OPTION-NON-DISPO").value);
	event.stop();
}
