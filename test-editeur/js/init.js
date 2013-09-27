Event.observe(window, 'load', initialiser, false);

var editeur;

function initialiser() {
	editeur=new EditeurSelecteur(0, true, "Cliquez sur le crayon pour éditer la zone");
	editeur.creerDans($('zone_editeurs'));
}


