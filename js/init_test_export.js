var demande;

function initialisationTertiaire() {
	$('bouton_export_telechargement').observe('click', gererClicBoutonExport);
	$('bouton_export_test').observe('click', gererClicBoutonExport);
	$('bouton_export_donnees').observe('click', gererClicBoutonExportDonnees);
	$('bouton_import_donnees').observe('change', gererChoixFichierImport);
}
function gererClicBoutonExport(event) {
	if (event.element().hasClassName('desactive'))
		return;
	if ($('exercice_vide').value == 'oui') {
		Avertissement.afficher($('message_exercice_vide').value);
		return;
	}
	Avertissement.afficher($('message_duree_compilation').value);
	var params = new Object();
	params['compilation'] = event.element() == $('bouton_export_test') ? 'test'
			: 'download';
	demande = params['compilation']
	$('working').appear();
	$('bouton_export_telechargement').addClassName('desactive');
	$('bouton_export_test').addClassName('desactive');
	$('bouton_export_donnees').addClassName('desactive');
	$('bouton_import_donnees').addClassName('desactive');
	requeteAjax(params, true);
}
function rappelAjax(data) {
	if (demande == 'test') {
		var cheminJeu = "output/index.php?jeu=" + data.responseText;
		var lien = new Element('a');
		lien.setAttribute('href', "output/index.php?jeu=" + data.responseText);
		lien.setAttribute('target', "_blank");
		lien.update($('invitation_lien_jeu').value);
		$('insertion_lien_jeu').update(lien);
		$('insertion_lien_jeu').shake();
		window.open(cheminJeu, 'test');
	} else if (demande == 'download') {
		envoyerPhp('nom_fichier_zip', data.responseText, 'zip.php')
	} else if (demande == 'export_donnees') {
		envoyerPhp('nom_fichier_glup', data.responseText,
				'download-glup-file.php')
	}
	$('bouton_export_telechargement').removeClassName('desactive');
	$('bouton_export_test').removeClassName('desactive');
	$('bouton_export_donnees').removeClassName('desactive');
	$('bouton_import_donnees').removeClassName('desactive');
	$('working').fade();
	if (demande == 'import_donnees') {
		if (data.responseText == "ok") {
			Avertissement.afficher($('message_import_reussi').value);
		} else {
			Avertissement.afficher($('message_import_echec').value);
		}
	} else
		Avertissement.masquer();
}
function gererClicBoutonExportDonnees(event) {
	if (event.element().hasClassName('desactive'))
		return;

	var params = new Object();
	demande = params['export_donnees'] = 'export_donnees';
	$('working').appear();
	$('bouton_export_telechargement').addClassName('desactive');
	$('bouton_export_test').addClassName('desactive');
	$('bouton_export_donnees').addClassName('desactive');
	$('bouton_import_donnees').addClassName('desactive');
	requeteAjax(params, true);
}
function gererChoixFichierImport(event) {
	var nomFichier = $('bouton_import_donnees').files[0];
	var reader = new FileReader();
	reader.onload = transmettreDonnees;
	reader.readAsText(nomFichier);
}
function transmettreDonnees(e) {
	var params = new Object();
	demande = 'import_donnees';
	params['import_donnees'] = e.target.result;
	$('working').appear();
	$('bouton_export_telechargement').addClassName('desactive');
	$('bouton_export_test').addClassName('desactive');
	$('bouton_export_donnees').addClassName('desactive');
	$('bouton_import_donnees').addClassName('desactive');
	requeteAjax(params, true);
}
