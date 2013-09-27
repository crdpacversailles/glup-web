//TODO filter entrées (oubli char étangers ?)
//TOTO autoriser le saut de ligne
//TODO pb charcode keycode windows

var ACCENT_CIRCONFLEXE = {
	a:"â",
	e:"ê",
	i:"î",
	o:"ô",
	u:"ü",
}

function EditeurSelecteur(numero, motEntiers, invitation) {
	this.invitation=invitation;
	this.chaine=" ";
	this.motsEntiers=motEntiers?motEntiers:false;;
	this.numero=numero;
	this.editionEnCours=false;
	this.repertoireImages="img";
	this.enregistrements=new Array();
	this.zoneVierge=true;
	this.memorisationAccent="";
	
}

EditeurSelecteur.prototype = {
	afficher : function(chaine) {
		this.zoneVierge=false;
		this.remettreEspaceAuDebut();
		this.actualiser();
	},
	actualiser : function() {
		this.actualiserContainer();
		this.actualiserBoutons();
		if(this.editionEnCours) this.actualiserEnModeEdition();
		else this.actualiserEnModeAffichage();
	},
	actualiserContainer : function() {
		this.editionEnCours?this.zoneEdition.addClassName('edition'):this.zoneEdition.removeClassName('edition');
	},
	actualiserBoutons : function() {
		this.editionEnCours?this.boutonEdition.hide():this.boutonEdition.show();
		this.editionEnCours?this.boutonValidation.show():this.boutonValidation.hide();
		this.editionEnCours?this.boutonSelection.show():this.boutonSelection.hide();
		this.editionEnCours?this.boutonReset.show():this.boutonReset.hide();
	},
	actualiserEnModeEdition : function() {
		if(this.zoneVierge) {
			this.zoneVierge=false;
			this.chaine=" ";
		}
		this.zoneEdition.update('');
		var car='';
		for(var index=0; index<this.chaine.length; index++) {
			var enveloppe= document.createElement('span');
			if(index==this.indexCurseur)  {
				this.carCurseur=enveloppe;
			}
			enveloppe.setAttribute('id', 'e_'+this.numero+'_l_'+index);
			if(this.selectionValide && index>=this.debutSelection && index<=this.finSelection) {
				enveloppe.addClassName("select");
			}
			car = this.chaine.charAt(index).replace(/\s/, "&nbsp;");
			enveloppe.update(car);
			this.zoneEdition.insert({bottom :enveloppe});
			
		}
		var nomLettre;
		for(index=0; index<this.enregistrements.length; index+=2) {
			var wrapper= document.createElement('span');
			wrapper.setAttribute('id', 'w_'+this.numero+'_l_'+index);
			wrapper.addClassName("enregistrement");
			$('e_'+this.numero+'_l_'+this.enregistrements[index]).insert({before :wrapper});
			for(var index2=this.enregistrements[index]; index2<=this.enregistrements[index+1]; index2++) {
				nomLettre='e_'+this.numero+'_l_'+index2;
				wrapper.insert({bottom:$(nomLettre)});
			}
		}
	},
	actualiserEnModeAffichage : function() {
		
		if (this.enregistrements.length==0)
			{
				this.zoneEdition.update(this.zoneVierge?this.invitation:this.chaine);
			}
		else {
			this.zoneEdition.update("");
			var encadre=false;
			var wrapper=document.createElement('span');
			this.zoneEdition.insert({bottom:wrapper});
			for(index=0; index<this.chaine.length; index+=1) {
				if(this.enregistrements.indexOf(index)!=-1) {
					encadre=!encadre;	
					if(!encadre) wrapper.insert(this.chaine.charAt(index));				
					wrapper= document.createElement('span');
					this.zoneEdition.insert({bottom:wrapper});
					if(encadre)
						wrapper.addClassName("enregistrement");
					if(!encadre) continue;
					
				}
				wrapper.insert(this.chaine.charAt(index));
			}
		}
	},
	creerDans : function(tagInsertion) {
		this.tagInsertion = tagInsertion;
		this.container = document.createElement('div');
		this.container.addClassName('editeur_selecteur');
		this.container.setAttribute('id', 'editeur_selecteur_'+this.numero);
		this.creerCurseur();
		this.creerZoneEdition();
		this.creerBoutons();
		tagInsertion.insert({bottom:this.container});
		this.container.observe('mouseup',this.gererMouseUp.bind(this));
		this.container.observe('mousedown',this.gererMouseDown.bind(this));
		this.actualiser();
	},
	creerCurseur:function() {
		this.curseur=document.createElement('span');
		this.curseur.update('|');
	},
	creerZoneEdition:function() {
		this.zoneEdition=document.createElement('p');
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
		this.boutonSelection.setAttribute('title', "Enregister comme bonne réponse");
		
		this.boutonReset=document.createElement('img');
		this.boutonReset.setAttribute('src', this.repertoireImages+"/reset.gif");
		this.boutonReset.setAttribute('id', "btn_reset_"+this.numero);
		this.boutonReset.setAttribute('title', "Effacer toutes les réponses");
		
		zoneBoutonsPermanents.insert(this.boutonEdition);
		zoneBoutonsPermanents.insert(this.boutonValidation);
		zoneBoutonsEdition.insert(this.boutonSelection);
		zoneBoutonsEdition.insert(this.boutonReset);
		
		this.container.insert({bottom:zoneBoutonsPermanents});
		this.container.insert({top:zoneBoutonsEdition});
	},
	gererMouseUp:function(event) {
		this.activerSelectionAuSurvol(false);
		var cible = event.element();
		var posLettre=this.estUneLettre(cible.id);
		if(posLettre>=0)
			{
				this.placerCurseur(parseInt(posLettre));
				this.selectionner(this.memoirePosMouseDown, this.indexCurseur, true);
				this.actualiser();
			}
		else if(cible==this.boutonEdition)
			{
				this.editer(true);
			}
		else if(cible==this.boutonValidation)
			{
				this.editer(false);
			}
		else if(cible==this.boutonSelection)
			{
				this.enregistrerSelection();
			}
		else if(cible==this.boutonReset)
			{
				this.supprimerToutesSelections();
			} 
	},
	supprimerToutesSelections:function() {
		while(this.enregistrements.length>0)
			this.enregistrements.pop();
		this.actualiser();
		
	},
	enregistrerSelection:function(event){
		if(!this.selectionValide) {
			this.selectionValide=false;
			this.actualiser();
			return;
		}
		this.selectionValide=false;
		this.trimSelection();
		if(this.motsEntiers)
			this.selectionnerMotsEntiers();
		if(!this.pasDeChevauchementEntreSelectionsEnregistrees())
			{
				this.actualiser();
				return;
			}
		this.enregistrements.push(this.debutSelection);
		this.enregistrements.push(this.finSelection);		
		
		this.actualiser();
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
	gererMouseDown:function(event) {
		this.selectionEnCours=false;
		this.actualiser();
		this.memoirePosMouseDown=this.estUneLettre(event.element().id);
		if(this.memoirePosMouseDown!=-1) {
			this.activerSelectionAuSurvol(true);
			event.element().addClassName('select');
			
		}
		event.stop();
	},
	activerSelectionAuSurvol:function(bool){
		if(bool) document.observe('mouseover',this.gererMouseMove.bind(this));
		else document.stopObserving('mouseover');
		this.selectionEnCours=bool;
	},
	gererMouseMove:function(event) {
		var numLettre=this.estUneLettre(event.element().id);
		if(this.selectionEnCours && numLettre>=0) {
			this.styleSelection(this.memoirePosMouseDown, numLettre);
		}
	},
	styleSelection:function(debut,fin) {
		if(fin==debut) return;
		var prov;
		if(fin<debut) {
			prov=debut;
			debut=fin;
			fin=prov;
		}
		var lettre;
		for(var index=0; index<this.chaine.length; index++) {
				lettre=$('e_'+this.numero+'_l_'+index);
				if(index>=debut && index<=fin) 
					lettre.addClassName('select');
				else lettre.removeClassName('select');
		}
	},
	selectionner:function(depart, arrivee) {
		this.selectionValide=depart!=arrivee;
		if (!this.selectionValide) return;
		this.selectionSensInverse= (depart-arrivee>0);
		this.debutSelection=Math.min(depart,arrivee);
		this.finSelection=Math.max(depart,arrivee);
		this.placerCurseur(parseInt(arrivee)-1);
		
		
		if(this.selectionSensInverse) this.placerCurseur(parseInt(this.debutSelection)-1);
		else this.placerCurseur(this.finSelection);
	},
	selectionnerMotsEntiers:function() {
		while(this.debutSelection>0 && !this.chaine.charAt(this.debutSelection-1).match(/[\s'-]/))
			this.debutSelection--;
		while(this.finSelection<this.chaine.length-1 && !this.chaine.charAt(this.finSelection+1).match(/[\s'-]/))
			this.finSelection++;
	},
	trimSelection:function() {
		while(this.chaine.charAt(this.debutSelection).match(/\s/) && this.debutSelection<this.chaine.length)
			this.debutSelection++;
		while(this.chaine.charAt(this.finSelection).match(/\s/) && this.finSelection>=0)
			this.finSelection--;
		if(this.debutSelection>=this.finSelection)
			this.selectionValide=false;
	},
	estUneLettre:function(id) {
		var motifLettre =/^e_(\d+)_l_(\d+)$/;
		if(motifLettre.match(id))
			{
				return motifLettre.exec(id)[2];
			}
		else return -1;
	},
	editer:function(bool) {
		this.zoneEdition.focus();
		this.placerCurseur(0);
		this.editionEnCours=bool;
		this.selectionValide=false;
		this.actualiser();
		this.clignoterCurseur(bool);
		if(bool) document.observe('keypress', this.gererTouche.bind(this));
		else document.stopObserving('keypress');
	},
	gererTouche:function(event) {
		var ccode = event.charCode;
		var kcode = event.keyCode;	
		if(kcode==Event.KEY_DELETE) {
			if (this.selectionValide) {
				this.supprimerSelection();
				this.deplacerCurseur(-1);
			}
			else this.splice(parseInt(this.indexCurseur)+1, parseInt(this.indexCurseur)+2,"");
		} else if(kcode==Event.KEY_BACKSPACE) {
			if (this.selectionValide){
				this.supprimerSelection();
				this.deplacerCurseur(-1);
			}
			else {
				this.splice(parseInt(this.indexCurseur), parseInt(this.indexCurseur)+1,"");
				this.deplacerCurseur(-1);
			};
		} else if(kcode==Event.KEY_LEFT) {
			this.deplacerCurseur(-1);
		} else if(kcode==Event.KEY_RIGHT) {
			this.deplacerCurseur(1);
		} else if(kcode==Event.KEY_RETURN) {
			this.editer(false);
		} else {
			if (this.selectionValide) {
				this.supprimerSelection();
				this.deplacerCurseur(-1);
			}			
			var car = String.fromCharCode(ccode);
			if (car.match(/[eiauo]/) && this.memorisationAccent!="") {
				if(this.memorisationAccent=="^") {
					this.insererLettre(ACCENT_CIRCONFLEXE[car]);
				}
				this.memorisationAccent="";
			}	
			else if (car.match(/[\w\s&"'-;:?!éèùàç]/)) {
				this.insererLettre(car);
			}
			else if (car.match(/[^]/)) {
				this.memorisationAccent="^";
				
			} else {
				this.memorisationAccent="";
			}			
		}
		this.selectionValide=false;
		event.preventDefault();
		this.actualiser();
	},
	insererLettre:function(car) {
		var posInsertion=parseInt( this.indexCurseur)+1;
		this.splice(posInsertion, posInsertion,car);
		this.deplacerCurseur(1);
	},
	supprimerSelection:function() {
		if (!this.selectionValide) return;
		this.splice(parseInt(this.debutSelection), parseInt(this.finSelection)+1,"");
		this.placerCurseur(parseInt(this.debutSelection));
	},
	splice:function(debut,fin, remplacement) {
		var copieChaine=this.chaine;		
		this.chaine=copieChaine.substr(0, debut)+remplacement+copieChaine.substr(fin, copieChaine.length-fin);		
		this.adapterEnregistrements(debut,fin, remplacement);
		this.remettreEspaceAuDebut();
	},
	remettreEspaceAuDebut:function() {
		if (!this.chaine.match(/^\s.*$/)) 
			this.chaine=" "+this.chaine;
	},
	adapterEnregistrements:function(debut,fin, remplacement) {
		var avant=true;
		var apres=true;
		var dedans=true;
		for(var index=0; index<this.enregistrements.length; index=index+2)
			{
			avant = (debut<this.enregistrements[index]) && (fin<this.enregistrements[index]);
			apres = (debut>this.enregistrements[index+1]) && (fin>this.enregistrements[index+1]);	
			dedans = (debut>this.enregistrements[index]) && (fin<=this.enregistrements[index+1]);	
			if(avant) {
				this.decalerEnregistrement(index, -(fin-debut)+remplacement.length);
					}
			else if(dedans) {
				this.redimensionnerEnregistrement(index, -(fin-debut)+remplacement.length);
					}
			else if(!apres) {
				this.supprimerEnregistrement(index);
				index-=2;
				}
			}
			
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
	clignoterCurseur:function(bool) {
		if(bool)
			this.intervalleCurseur=setInterval(this.toggleCurseur.bind(this), 300);
			else clearInterval(this.intervalleCurseur);
		this.compteurCurseur=0;
	},
	toggleCurseur:function() {
		if(!this.carCurseur) return;	
		this.compteurCurseur  = (this.compteurCurseur +1)%8;
		this.curseurVisible=this.compteurCurseur!=0;		
	 	if(!this.curseurVisible)	 this.carCurseur.removeClassName("curseur");
	 	else this.carCurseur.addClassName("curseur");
	 	
	},
	deplacerCurseur:function(deplacement){
		this.indexCurseur+=parseInt(deplacement);
		this.bornerCurseur();
	},
	placerCurseur:function(position){
		this.indexCurseur=parseInt(position);
		this.bornerCurseur();
	},
	bornerCurseur:function() {
		if(this.indexCurseur<0)
			this.indexCurseur=0;
		else if(this.indexCurseur>this.chaine.length-1)
			this.indexCurseur=this.chaine.length-1;
	}
	
};