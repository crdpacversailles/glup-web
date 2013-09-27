function initialisationSecondaire() {
 	$$('h2').each(gererOuverture);
 }
function gererOuverture(element) {
	var paragraphe=element.next('div');
	element.observe('click', toggleParagraphe);
	paragraphe.hide();
}
function toggleParagraphe(event) {
	var paragraphe=event.element().next('div');
	if (paragraphe.hasClassName('visible')) {
		paragraphe.blindUp({duration:0.1});
		paragraphe.removeClassName('visible')
	}
	else {
		paragraphe.addClassName('visible')
		paragraphe.blindDown({duration:0.2});
	}
}