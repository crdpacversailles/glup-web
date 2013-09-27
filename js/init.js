var NAMESPACE = "http://www.crdp.ac-versailles.fr/glup/2012";
Event.observe(window, 'load',initialiser, false);

 function initialiser(event) {
 	if($('page_courante').value=='test') {
		new Effect.Move($$('.corps_de_page')[0], {
			x :0,
			mode:'absolute',
			duration:0.3
			});
 	}
 	ecouterBoutonsLangues();
 	ecouterMenuPrincipal();
 	ecouterMenuSecondaire()
 	initialisationSecondaire();
 }
 function ecouterBoutonsLangues() {
 	var tabItemsPadLangues = $$('img.item_pad_langue');
	tabItemsPadLangues.each(observerClicItemPadLangue);
 }
 function observerClicItemPadLangue(itemPadLangue) {
 	itemPadLangue.observe('click', gererClicItemPadLangue);
 }
 function gererClicItemPadLangue(event) {
 	var idFlag = event.element().id;
 	var motif =/^langue_(.*)$/;
	var langue = motif.exec(idFlag)[1];
	envoyerPhp('langue', langue);
 }
function ecouterMenuPrincipal() {
	var tabItemsMenuPrincipal = $$('.item_menu');
	tabItemsMenuPrincipal.each(observerClicItemMenuPrincipal);
 }
 function observerClicItemMenuPrincipal(itemMenuPrincipal) {
 	itemMenuPrincipal.observe('click', gererClicItemMenuPrincipal);
 }
 function gererClicItemMenuPrincipal(event) {
 	var idItem = event.element().id;
 	var motif =/^item_menu_principal_(.*)$/;
	var idPage = motif.exec(idItem)[1];
	envoyerSurPage(idPage);
 }
 function ecouterMenuSecondaire() {
 	if(!$$('menu_secondaire')) return;
	var tabItemsMenuSecondaire = $$('.item_menu_secondaire');
	var tabFlechesMenuSecondaire = $$('.fleche_menu_secondaire');
	tabItemsMenuSecondaire.each(observerClicItemMenuSecondaire);
	tabFlechesMenuSecondaire.each(observerClicFlecheMenuSecondaire);
 }
 function observerClicItemMenuSecondaire(itemMenuSecondaire) {
 	
 	itemMenuSecondaire.observe('click', gererClicItemMenuSecondaire);
 }
  function observerClicFlecheMenuSecondaire(flecheMenuSecondaire) {
  	if(flecheMenuSecondaire.hasClassName('fleche_inactive'))
 		return;
 	flecheMenuSecondaire.observe('click', gererClicFlecheMenuSecondaire);
 }
 function gererClicItemMenuSecondaire(event) {
 	var idItem = event.element().id;
 	var motif =/^item_menu_secondaire_([^_]*)_(.*)$/;
	var idPage = motif.exec(idItem)[1];
	var idAction = motif.exec(idItem)[2];
	envoyerSurPage(idPage, idAction);
 }
function gererClicFlecheMenuSecondaire(event) {
 	var idItem = event.element().id;
 	var motif =/^fleche_menu_secondaire_([^_]*)_(.*)$/;
	var idPage = motif.exec(idItem)[1];
	var idAction = motif.exec(idItem)[2];
	envoyerSurPage(idPage, idAction);
 }



