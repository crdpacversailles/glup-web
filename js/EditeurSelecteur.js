function EditeurSelecteur(numero, motEntiers, invitation) {
	this.invitation=invitation;
	this.chaine="";
	this.motsEntiers=motEntiers?motEntiers:false;;
	this.numero=numero;
	this.LONGUEUR_MAX_LIGNES = 450;
	this.gererMouseUpLiee=this.gererMouseUp.bind(this);
	this.gererToucheLiee = this.gererTouche.bind(this);
	this.modeSelectionReponses=true;
	this.repertoireImages="img";
	this.enregistrements=new Array();
	this.espacesInsecables=new Array();
	
}

EditeurSelecteur.prototype = {
	afficher : function(chaine) {
		this.zoneEdition.value=this.chaine=this.analyserEntree(chaine);
		this.actualiser();
	},
	analyserEntree:function(chaine){
		for(var index=0; index<chaine.length; index++) {
			if (chaine.charAt(index)=="["  )
			{
				this.enregistrements.push(index);
				chaine=chaine.substring(0,index)+chaine.substr(index+1);
				index--;
			} else if (chaine.charAt(index)=="]" )
			{
				this.enregistrements.push(index);
				chaine=chaine.substring(0,index)+chaine.substr(index+1);
				index--;
			}
		}
		for(var index=0; index<chaine.length; index++) {
			if (chaine.charAt(index)=="_"  )
			{
				this.espacesInsecables.push(index);
				chaine=chaine.substring(0,index)+" "+chaine.substr(index+1);
			} 
		}
		return chaine;
	},
	actualiser : function() {
		this.actualiserBoutons();
		if(this.modeSelectionReponses) this.actualiserEnModeSelectionReponses();
		else this.actualiserEnModeEditionTexte();
	},
	actualiserBoutons : function() {
		this.modeSelectionReponses&&this.boutonsVisibles?this.boutonEdition.show():this.boutonEdition.hide();
		this.modeSelectionReponses?this.boutonValidation.hide():this.boutonValidation.show();
		this.modeSelectionReponses&&this.boutonsVisibles?this.boutonSelection.show():this.boutonSelection.hide();
		this.modeSelectionReponses&&this.boutonsVisibles?this.boutonLiaison.show():this.boutonLiaison.hide();
		this.modeSelectionReponses&&this.boutonsVisibles?this.boutonReset.show():this.boutonReset.hide();
		
		if(this.chaineVide()){
			this.boutonSelection.addClassName('bouton_desactive');
			this.boutonLiaison.addClassName('bouton_desactive');
		} else {
			this.boutonSelection.removeClassName('bouton_desactive');
			this.boutonLiaison.removeClassName('bouton_desactive');
		}
		if(this.chaineVide() || (this.enregistrements.length==0 && this.espacesInsecables.length==0) ){
			this.boutonReset.addClassName('bouton_desactive');
		} else this.boutonReset.removeClassName('bouton_desactive');;
	},
	recupererSaisie:function() {
		this.zoneEdition.value=this.chaine=this.filtrer(this.zoneEdition.value);
	},
	filtrer:function(chaine) {
		return chaine.stripScripts().stripTags().strip();
	},
	actualiserEnModeSelectionReponses : function() {
		this.recupererSaisie();
		
		var copieChaine=this.chaineVide()?this.invitation:this.chaineAvecUnderscore();
		this.zoneEdition.hide();
		this.zoneSelectionReponses.show();
		var pointeurEnregistrements=0;
		var pointeurLettres=0;
		var containerCourant=null ;
		var tronconChaine="";
		if(this.enregistrements.length==0) {
		 this.zoneSelectionReponses.update(copieChaine);
		 return;	
		}
		this.zoneSelectionReponses.update('');
		var limiteAtteinte;
		while(pointeurLettres<copieChaine.length){
			limiteAtteinte=pointeurLettres== this.enregistrements[pointeurEnregistrements];
			if(limiteAtteinte || !containerCourant)
					{
						containerCourant= this.creerContainerLettres(limiteAtteinte && pointeurEnregistrements%2==0);
						tronconChaine="";
						if(limiteAtteinte)
							pointeurEnregistrements++;
					}
			tronconChaine+=copieChaine.charAt(pointeurLettres);
			containerCourant.update(tronconChaine);
			pointeurLettres++;
			
		}
	},
	 chaineVide: function() {
		return this.chaine.match(/^\s*$/);
	},
	creerContainerLettres:function(encadre){
		var container=new Element('span');
		if(encadre) container.addClassName("enregistrement");
		this.zoneSelectionReponses.insert({bottom : container});
		
		return container;
	},
	actualiserEnModeEditionTexte : function() {
		this.container.stopObserving('mousedown',this.gererMouseDownLiee);
		this.zoneEdition.show();
		this.zoneSelectionReponses.hide();
		this.supprimerToutesSelections(true);
		this.zoneEdition.update(this.chaine);
	},
	gererTouche:function(event) {
		var kcode = event.keyCode;	
		 if(kcode==Event.KEY_RETURN) {
			this.arreterEdition();
			event.preventDefault();
		} 
	},
	creerDans : function(tagInsertion, modeEdition) {
		this.tagInsertion = tagInsertion;
		this.modeSelectionReponses=!modeEdition;
		this.container = document.createElement('div');
		this.container.addClassName('editeur_selecteur');
		this.container.setAttribute('id', 'editeur_selecteur_'+this.numero);
		this.creerZoneSelectionReponses();
		this.creerZoneEdition();
		this.creerBoutons();
		tagInsertion.insert({bottom:this.container});
		tagInsertion.observe('mouseover', this.gererMouseOver.bind(this));
		tagInsertion.observe('mouseout', this.gererMouseOut.bind(this));
		this.boutonsVisibles=false;
		this.editer(modeEdition);
	},
	gererMouseOver:function(event) {
		this.boutonsVisibles=true;
		this.actualiserBoutons();
	},
	gererMouseOut:function(event) {
		this.boutonsVisibles=false;
		this.actualiserBoutons();
	},
	creerZoneSelectionReponses:function() {
		this.zoneSelectionReponses=document.createElement('p');
		this.container.insert(this.zoneSelectionReponses);
		this.container.observe('mouseup',this.gererMouseUpLiee);
	},
	creerZoneEdition:function() {
		this.zoneEdition=document.createElement('textarea');
		this.zoneEdition.setAttribute('rows', 2);
		this.zoneEdition.setAttribute('cols', 50);
		this.container.insert(this.zoneEdition);
	},
	creerBoutons:function() {
		var zoneBoutonsPermanents=document.createElement('div');
		zoneBoutonsPermanents.addClassName('boutons_permanents');
		var zoneBoutonsEdition=document.createElement('div');
		zoneBoutonsEdition.addClassName('boutons_edition');
		
		this.boutonEdition=document.createElement('img');
		this.boutonEdition.setAttribute('src', this.repertoireImages+"/draw.gif");
		this.boutonEdition.setAttribute('id', "btn_edit_"+this.numero);
		this.boutonEdition.setAttribute('title', "Editer cette phrase");
		
		this.boutonValidation=document.createElement('img');
		this.boutonValidation.setAttribute('src', this.repertoireImages+"/valid.gif");
		this.boutonValidation.setAttribute('id', "btn_valid_"+this.numero);
		this.boutonValidation.setAttribute('title', "Valider la saisie");
		
		this.boutonSelection=document.createElement('img');
		this.boutonSelection.setAttribute('src', this.repertoireImages+"/bon.gif");
		this.boutonSelection.setAttribute('id', "btn_select_"+this.numero);
		this.boutonSelection.setAttribute('title', $('texte_enregistrer_bonne_reponse').value);
		
		this.boutonLiaison=document.createElement('img');
		this.boutonLiaison.setAttribute('src', this.repertoireImages+"/lock.gif");
		this.boutonLiaison.setAttribute('id', "btn_liaison_"+this.numero);
		this.boutonLiaison.setAttribute('title', $('texte_grouper_mots').value);
		
		this.boutonReset=document.createElement('img');
		this.boutonReset.setAttribute('src', this.repertoireImages+"/reset.gif");
		this.boutonReset.setAttribute('id', "btn_reset_"+this.numero);
		this.boutonReset.setAttribute('title', $('texte_effacer_reponse').value);
		
		zoneBoutonsEdition.insert(this.boutonEdition);
		zoneBoutonsEdition.insert(this.boutonValidation);
		zoneBoutonsEdition.insert(this.boutonSelection);
		zoneBoutonsEdition.insert(this.boutonLiaison);
		zoneBoutonsEdition.insert(this.boutonReset);
		
		this.container.insert({bottom:zoneBoutonsEdition});
	},
	gererMouseUp:function(event) {
		var cible = event.element();
		if(cible.hasClassName('bouton_desactive')) return;
		if(cible==this.boutonEdition)
			{
				this.editer(true);
				this.avertirEdition();
			}
		else if(cible==this.boutonValidation)
			{
				this.arreterEdition();
			}
		else if(cible==this.boutonSelection)
			{
				this.enregistrerSelection();
			}
		else if(cible==this.boutonReset)
			{
				this.supprimerToutesSelections();
			} 
		else if(cible==this.boutonLiaison)
			{
				this.enregistrerLiaison();
			} 
	},
	arreterEdition:function() {
		Event.stopObserving(document, 'keypress', this.gererToucheLiee);
		this.editer(false);
		this.avertirEnregistrement();
	},
	supprimerToutesSelections:function(sansActualisation) {
		while(this.enregistrements.length>0)
			this.enregistrements.pop();
		while(this.espacesInsecables.length>0)
			this.espacesInsecables.pop();
		if(!sansActualisation)
			this.actualiser();
		
	},
	enregistrerLiaison:function() {
		if(this.chaineVide()) return;
		var bornes=this.bornesSelection();
		for(var i=bornes[0]; i<bornes[1]; i++) {
			if(this.chaine.charAt(i).match(/\s/))
				if(this.espacesInsecables.indexOf(i)==-1)
					if(!this.estDansUneSelection(i))
						if(!this.toucheUneSelection(i))
							this.espacesInsecables.push(i);
		}
		this.avertirEnregistrement();
		this.actualiser();
	},
	enregistrerSelection:function(){
		this.selectionValide=false;
		var texteSelectionne=this.recupererSelection();
		if (texteSelectionne=="") 
			{
				Avertissement.afficher($("TEST-CONTENU-SOULIGNER-LES-MOTS-AVERTISSEMENT-SELECTION-VIDE").value);
				return;
			}
		var positionSelection = this.bornesSelection();
		this.debutSelection=positionSelection[0];
		this.finSelection=positionSelection[1];
		this.trimSelection();
		if(this.motsEntiers)
			this.selectionnerMotsEntiers();
		if(!this.pasDeChevauchementEntreSelectionsEnregistrees())
			{
				Avertissement.afficher($("TEST-CONTENU-SOULIGNER-LES-MOTS-AVERTISSEMENT-SELECTIONS").value);
				this.actualiser();
				return;
			}
		this.enregistrements.push(this.debutSelection);
		this.enregistrements.push(this.finSelection);		
		this.enregistrements.sort(function(a,b){return a - b});
		var espacesASupprimer=new Array();
		for(var index=0; index<this.espacesInsecables.length; index++) {
			if(this.espacesInsecables[index]>= this.debutSelection-1 && this.espacesInsecables[index]<=this.finSelection)
				{
					espacesASupprimer.push(index);
				}
				
		}
		for(var i=0; i<espacesASupprimer.length; i++) {
			this.supprimerEspaceInsecable(espacesASupprimer[i]);
		}
		this.avertirEnregistrement();
		this.actualiser();
	},
	supprimerEspaceInsecable:function(index) {
		this.espacesInsecables.splice(index,1);
	},
	recupererSelection:function() {
		  var selection = '';
		  if(Prototype.Browser.IE){
		    selection = document.selection.createRange().text;
		  }else if(Prototype.Browser.Gecko){ //FFOX
		    selection = document.getSelection();
		  }else if (Prototype.Browser.WebKit) { //Chrome
		  	selection = window.getSelection().toString();
		  }
		  return selection;
	},
	bornesSelection:function() {
		  var bornes = new Array();
		  var selection;
		  if(Prototype.Browser.IE){
		    selection = document.selection.createRange().text;
		  }else if(Prototype.Browser.Gecko || Prototype.Browser.WebKit){ //FFOX
		  	selection = window.getSelection();
		    bornes[0]=Math.min(selection.anchorOffset,selection.focusOffset);
		    
		    var container = selection.anchorNode.parentNode;
		    var predecesseur=container;
		    while(predecesseur.previousSibling) {
		    	predecesseur=predecesseur.previousSibling;
		    	if(predecesseur.firstChild && predecesseur.firstChild.nodeValue)// && predecesseur.firstChild.nodeValue)
		    		{
		    			bornes[0]+=predecesseur.firstChild.nodeValue.length
		    		};
		    }
		    bornes[1]=bornes[0]+selection.toString().length;
		  }
		  return bornes;
	},
	pasDeChevauchementEntreSelectionsEnregistrees:function(event){
		var avant =true;
		var apres=true;
		for(var index=0; index<this.enregistrements.length; index=index+2)
			{
			avant = (this.debutSelection<this.enregistrements[index]) && (this.finSelection<this.enregistrements[index]);
			apres = (this.debutSelection>this.enregistrements[index+1]) && (this.finSelection>this.enregistrements[index+1]);	
			if (!avant && !apres) return false;
			}
		return true;
	},
	estDansUneSelection:function(indexChar){
		for(var index=0; index<this.enregistrements.length; index=index+2)
			{
			if (indexChar>=this.enregistrements[index] && indexChar<this.enregistrements[index+1])
				return true;
			}
		return false;
	},
	toucheUneSelection:function(indexChar){
		for(var index=0; index<this.enregistrements.length; index+=2)
			{
			if (this.enregistrements[index]==indexChar+1)
				return true;
			if (this.enregistrements[index+1]==indexChar)
				return true;
			}
		return false;
		},
	selectionnerMotsEntiers:function() {
		var caracteres=/[a-zA-Z0-9]/;
		while(this.debutSelection>0 && this.chaine.charAt(this.debutSelection-1).match(caracteres))
			this.debutSelection--;
		while(this.finSelection<this.chaine.length && this.chaine.charAt(this.finSelection).match(caracteres))
			this.finSelection++;
	},
	trimSelection:function() {
		while(this.chaine.charAt(this.debutSelection).match(/\s/) && this.debutSelection<this.chaine.length)
			this.debutSelection++;
		while(this.chaine.charAt(this.finSelection-1).match(/\s/) && this.finSelection>0)
			this.finSelection--;
		if(this.debutSelection>=this.finSelection)
			this.selectionValide=false;
	},
	editer:function(bool) {
		this.modeSelectionReponses=!bool;
		this.selectionValide=false;
		this.actualiser();
		if(bool) Event.observe(document, 'keypress', this.gererToucheLiee);
		else Event.stopObserving(document, 'keypress', this.gererToucheLiee);
		
	},
	avertirEnregistrement:function() {
		this.tagInsertion.fire("selecteur:validation");
	},
	avertirEdition:function() {
		this.tagInsertion.fire("selecteur:edition");
	},
	decalerEnregistrement:function(index, decalage) {
		this.enregistrements[index]+=decalage;
		this.enregistrements[index+1]+=decalage;
	},
	redimensionnerEnregistrement:function(index, decalage) {
		this.enregistrements[index+1]+=decalage;
	},
	supprimerEnregistrement:function(index) {
		this.enregistrements.splice(index, 2);
	},
	getPhrase:function() {
		return this.chaine;
	},
	getEnregistrements:function() {
		return this.enregistrements;
	},
	getPhraseFormatee:function() {
		var copieChaine=this.chaineAvecUnderscore();
		if(this.enregistrements.length==0) return this.filtrer(copieChaine);
		var phraseFormatee="";
		var precedent=0;
		for(var index=0; index<this.enregistrements.length; index+=2) {
			phraseFormatee+=copieChaine.substring(precedent, this.enregistrements[index]);
			phraseFormatee+="[";
			phraseFormatee+=copieChaine.substring(this.enregistrements[index],this.enregistrements[index+1]);
			phraseFormatee+="]";
			precedent=this.enregistrements[index+1];
		}
		
		phraseFormatee+=copieChaine.substr(precedent);

		return this.filtrer(phraseFormatee);
	},
	chaineAvecUnderscore:function() {
		var copieChaine=this.chaine;
		for(var index=0; index<this.espacesInsecables.length; index++) {
			if(this.chaine.charAt(this.espacesInsecables[index]).match(/\s/))
				copieChaine=copieChaine.substring(0, this.espacesInsecables[index])+"_"+copieChaine.substring(this.espacesInsecables[index]+1);
		}
		return copieChaine;
	}
	
};