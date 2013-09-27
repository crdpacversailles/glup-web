function initialisationSecondaire() {
	if ($$('menu-test-PEDAGOGIE'))
		ecouterItemsPedagogie();
	if ($$('menu-test-GAMEPLAY'))
		ecouterItemsGameplay();
	if ($$('.container_fleche_suite').length > 0)
		ecouterFlecheSuite();
	if (typeof (initialisationTertiaire) == 'function')
		initialisationTertiaire();
}
function ecouterItemsPedagogie() {
	var tabItemsPedago = $$('li.principe_pedagogique');
	tabItemsPedago.each(observerClicItemPedago);
}
function ecouterFlecheSuite() {
	$$('.container_fleche_suite')[0].observe('click', gererClicFlecheSuite)
}
function gererClicFlecheSuite(event) {
	var idItem = event.findElement('.container_fleche_suite').id;
	var motif = /^fleche_suite_([^_]*)_(.*)$/;
	var idPage = motif.exec(idItem)[1];
	var idAction = motif.exec(idItem)[2];
	envoyerSurPage(idPage, idAction);
}
function observerClicItemPedago(itemPedago) {
	if (itemPedago.hasClassName('item_inactif'))
		return;
	itemPedago.observe('click', gererClicItemPedago);
}
function gererClicItemPedago(event) {
	var idItem = event.element().id;
	var motif = /^principe_pedagogique_(.*)$/;
	var cleItem = motif.exec(idItem)[1];
	envoyerPhp('principe-pedago', cleItem);
}
function ecouterItemsGameplay() {
	var tabItemsGamePlay = $$('li.gameplay');
	tabItemsGamePlay.each(observerClicItemGameplay);
}
function observerClicItemGameplay(itemGameplay) {
	itemGameplay.observe('click', gererClicItemGameplay);
}
function gererClicItemGameplay(event) {
	if (event.element().tagName.toLowerCase() == "a")
		return;
	var idItem = event.findElement('li').id;
	var motif = /^gameplay_(.*)$/;
	var cleItem = motif.exec(idItem)[1];
	envoyerPhp('gameplay', cleItem);
}