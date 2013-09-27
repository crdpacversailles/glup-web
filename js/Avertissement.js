function Avertissement() {
	if(Avertissement.instance) return;
	this.enveloppe = new Element("div");
	this.enveloppe.addClassName("bandeau_avertissement");
	var container = new Element("div");
	this.enveloppe.insert(container);
	this.afficheur = new Element("div");
	container.insert(this.afficheur);
	this.bouton = new Element("img");
	this.bouton.setAttribute("src", "img/fermeture.png");
	this.bouton.observe('click', this.masquer.bind(this));
	container.insert(this.bouton);
	this.enveloppe.hide();
	document.body.insert({top:this.enveloppe});
	
}

Avertissement.prototype = {
	afficher:function(texte){
		this.afficheur.update(texte);
	},
	deployer:function(){
		this.enveloppe.show();
		this.enveloppe.slideDown({duration:1});
	},
	masquer:function(){
		this.enveloppe.fade({duration:0.3});
	}
	
};
Avertissement.afficher = function (texte) {
	if(!Avertissement.instance) Avertissement.instance=new Avertissement();
	Avertissement.instance.afficher(texte);
	Avertissement.instance.deployer();
};
Avertissement.masquer = function () {
	if(!Avertissement.instance) return;
	Avertissement.instance.masquer();
};