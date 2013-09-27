function EditeurVraiFaux(numero, invitation, bonneReponse) {
	this.invitation = invitation;
	this.chaine = "";
	this.numero = numero;
	this.bonneReponse = bonneReponse;
	this.LONGUEUR_MAX_LIGNES = 450;
	this.gererMouseUpLiee = this.gererMouseUp.bind(this);
	this.gererToucheLiee = this.gererTouche.bind(this);
	this.modeSelectionStatut = true;
	this.repertoireImages = "img";
	this.enregistrements = new Array();
	this.espacesInsecables = new Array();

}

EditeurVraiFaux.prototype = {
	afficher : function(chaine) {
		this.zoneEdition.value = this.chaine = chaine;
		this.actualiser();
	},
	actualiser : function() {
		this.actualiserBoutons();
		if(this.modeSelectionStatut)
			this.actualiserEnModeSelectionStatut();
		else
			this.actualiserEnModeEditionTexte();
	},
	actualiserBoutons : function() {this.modeSelectionStatut && this.boutonsVisibles ? this.boutonEdition.show() : this.boutonEdition.hide();
		this.modeSelectionStatut ? this.boutonValidation.hide() : this.boutonValidation.show();
		if(!this.boutonIphone)
			return;
		if(this.modeSelectionStatut && this.boutonsVisibles) {
			this.radioVraiFaux.up("div.iPhoneCheckContainer").removeClassName("bouton-iphone-invisible");
		} else {
			this.radioVraiFaux.up("div.iPhoneCheckContainer").addClassName("bouton-iphone-invisible");
		}

	},
	recupererSaisie : function() {
		this.zoneEdition.value = this.chaine = this.filtrer(this.zoneEdition.value);
	},
	filtrer : function(chaine) {
		return chaine.stripScripts().stripTags().strip();
	},
	actualiserEnModeSelectionStatut : function() {
		this.recupererSaisie();

		var copieChaine = this.chaineVide() ? this.invitation : this.chaine;
		this.zoneEdition.hide();
		this.zoneSelectionReponses.show();
		this.zoneSelectionReponses.update(copieChaine);

	},
	chaineVide : function() {
		return this.chaine.match(/^\s*$/);
	},
	actualiserEnModeEditionTexte : function() {
		this.container.stopObserving('mousedown', this.gererMouseDownLiee);
		this.zoneEdition.show();
		this.zoneSelectionReponses.hide();
		this.zoneEdition.update(this.chaine);
	},
	gererTouche : function(event) {
		var kcode = event.keyCode;
		if(kcode == Event.KEY_RETURN) {
			this.arreterEdition();
			event.preventDefault();
		}
	},
	creerDans : function(tagInsertion, modeEdition) {
		this.tagInsertion = tagInsertion;
		this.modeSelectionStatut = !modeEdition;
		this.container = document.createElement('div');
		this.container.addClassName('editeur_vraifaux');
		this.container.setAttribute('id', 'editeur_vraifaux_' + this.numero);
		this.creerZoneSelectionReponses();
		this.creerZoneEdition();
		this.creerBoutons();
		this.creerRadioVraiFaux();
		tagInsertion.insert({
			bottom : this.container
		});

		tagInsertion.observe('mouseover', this.gererMouseOver.bind(this));
		tagInsertion.observe('mouseout', this.gererMouseOut.bind(this));
		this.boutonsVisibles = false;

		this.editer(modeEdition);
	},
	gererMouseOver : function(event) {
		this.boutonsVisibles = true;
		this.actualiserBoutons();
	},
	gererMouseOut : function(event) {
		this.boutonsVisibles = false;
		this.actualiserBoutons();
	},
	creerZoneSelectionReponses : function() {
		this.zoneSelectionReponses = document.createElement('p');
		this.container.insert(this.zoneSelectionReponses);
		this.container.observe('mouseup', this.gererMouseUpLiee);
	},
	creerZoneEdition : function() {
		this.zoneEdition = document.createElement('textarea');
		this.zoneEdition.setAttribute('rows', 1);
		this.zoneEdition.setAttribute('cols', 50);
		this.container.insert(this.zoneEdition);
	},
	creerBoutons : function() {

		var zoneBoutonsEdition = document.createElement('div');
		zoneBoutonsEdition.addClassName('boutons_edition');

		this.boutonEdition = document.createElement('img');
		this.boutonEdition.setAttribute('src', this.repertoireImages + "/draw.gif");
		this.boutonEdition.setAttribute('id', "btn_edit_" + this.numero);
		this.boutonEdition.setAttribute('title', "Editer cette phrase");

		this.boutonValidation = document.createElement('img');
		this.boutonValidation.setAttribute('src', this.repertoireImages + "/valid.gif");
		this.boutonValidation.setAttribute('id', "btn_valid_" + this.numero);
		this.boutonValidation.setAttribute('title', "Valider la saisie");

		zoneBoutonsEdition.insert(this.boutonEdition);
		zoneBoutonsEdition.insert(this.boutonValidation);

		this.container.insert({
			bottom : zoneBoutonsEdition
		});
	},
	creerRadioVraiFaux : function() {
		var zoneRadioVraiFaux = document.createElement('div');
		zoneRadioVraiFaux.addClassName('zone_radio_vraifaux');
		this.radioVraiFaux = document.createElement('input');
		this.radioVraiFaux.setAttribute('type', "checkbox");
		if(this.bonneReponse)
			this.radioVraiFaux.setAttribute('checked', "checked");
		zoneRadioVraiFaux.insert(this.radioVraiFaux);
		this.radioVraiFaux.setAttribute('id', "radio_vraifaux_" + this.numero);

		this.container.insert({
			bottom : zoneRadioVraiFaux
		});
		this.radioVraiFaux.hide();
		this.radioVraiFaux.observe('iphone:changement', this.gererChangementBoutonIphone.bind(this));

	},
	gererChangementBoutonIphone : function(event) {
		if(this.radioVraiFaux.checked)
			this.tagInsertion.addClassName("insertion_bonne_reponse");
		else
			this.tagInsertion.removeClassName("insertion_bonne_reponse");
		this.avertirEnregistrement();
	},
	getStatutEnonce : function() {
		return this.radioVraiFaux.checked;
	},
	gererMouseUp : function(event) {
		var cible = event.element();
		if(cible.hasClassName('bouton_desactive'))
			return;
		if(cible == this.boutonEdition) {
			this.editer(true);
			this.avertirEdition();
		} else if(cible == this.boutonValidation) {
			this.arreterEdition();
		}
	},
	arreterEdition : function() {
		Event.stopObserving(document, 'keypress', this.gererToucheLiee);
		this.editer(false);
		this.avertirEnregistrement();
	},
	supprimerEspaceInsecable : function(index) {
		this.espacesInsecables.splice(index, 1);
	},
	editer : function(bool) {
		this.modeSelectionStatut = !bool;
		if(this.modeSelectionStatut && !this.boutonIphone) {
			this.radioVraiFaux.show();
			this.boutonIphone = new iPhoneStyle("#radio_vraifaux_" + this.numero, {
				checkedLabel : 'JUSTE',
				uncheckedLabel : 'FAUX'
			});
		}
		this.selectionValide = false;
		this.actualiser();
		if(bool)
			Event.observe(document, 'keypress', this.gererToucheLiee);
		else
			Event.stopObserving(document, 'keypress', this.gererToucheLiee);

	},
	avertirEnregistrement : function() {
		this.tagInsertion.fire("selecteur:validation");
	},
	avertirEdition : function() {
		this.tagInsertion.fire("selecteur:edition");
	},
	getPhrase : function() {
		return this.chaine;
	}
};
