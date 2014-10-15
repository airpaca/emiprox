<?php

/* ************** Paramètres généraux ************** */

// Nom et version de l'inventaire
$IE = array(
    'annee' => '2010',
	'version' => 'a2010_v2013_v2', //'a2007_v2010_v2' | 'a2010_v2013_v1'
	'nom' => 'Inventaire des &eacute;missions PACA 2010'
);

// Nom de l'AASQA
$AASQA = array(
	'nom' => 'Air PACA',
	'web' => 'http://www.airpaca.org'
);

// Introduction HTML
$INTRO = "Air PACA pr&eacute;sente ici les r&eacute;sultats du nouvel inventaire des &eacute;missions polluantes sur la r&eacute;gion PACA.<br />
Cette base de donn&eacute;es rassemble les &eacute;missions d'une trentaine de polluants incluant les principaux gaz &agrave; effet de serre d'origine humaine et naturelle.
Cet inventaire est construit &agrave; l'&eacute;chelle du kilom&egrave;tre.<br /><br />
Vous pouvez consulter, de mani&egrave;re simple et rapide, la r&eacute;partition des sources d'&eacute;missions, par collectivit&eacute;, pour les principaux polluants.";

// Description pour la balise META Description
$DESC = "Air PACA pr&eacute;sente ici les r&eacute;sultats du nouvel inventaire des &eacute;missions polluantes sur la r&eacute;gion PACA";

// Liste des polluants MyEmiss'air avec
//   'col' => nom de la colonne de la base de données,
//   'html' => code HTML du polluant,
//   'nom' => nom du polluant,
//   'unite' => schéma de l'écriture du polluant : le caractére "*" sera remplacé par la quantité. Exemple : *, */an, * eq.CO2, ...
$POLLUANTS = array(
	"NOx" => array(
		'col' => "NOx kg/an", 
		'html' => "NO<sub>x</sub>", 
		'nom' => "Oxydes d'azote", 
		'unite' => "*"
		),
	"PM10" => array(
		'col' => "PM10 kg/an", 
		'html' => "PM10", 
		'nom' => "Particules inf&eacute;rieures &agrave; 10 &micro;m", 
		'unite' => "*"
		),
	"PM2.5" => array(
		'col' => "PM2.5 kg/an", 
		'html' => "PM2.5", 
		'nom' => "Particules inf&eacute;rieures &agrave; 2.5 &micro;m", 
		'unite' => "*"
		),
	"CO2" => array(
		'col' => "CO2 kg/an", 
		'html' => "CO<sub>2</sub>", 
		'nom' => "Dioxyde de carbone", 
		'unite' => "*"
		),
	"GES" => array(
		'col' => "GES kg eq.CO2/an",
		'html' => "GES", 
		'nom' => "Gaz &agrave; Effet de Serre", 
		'unite' => "* eq.CO2 "
		),
	"CO" => array(
		'col' => "CO kg/an", 
		'html' => "CO", 
		'nom' => "Monoxyde de carbone", 
		'unite' => "*"
		),
	"SO2" => array(
		'col' => "SO2 kg/an", 
		'html' => "SO<sub>2</sub>", 
		'nom' => "Dioxyde de soufre", 
		'unite' => "*"
		),
	"COVNM" => array(
		'col' => "COVNM kg/an", 
		'html' => "COVNM", 
		'nom' => "Compos&eacute;s Organiques Volatils Non M&eacute;thaniques", 
		'unite' => "*"
		)
);

// Nom du la table et colonne du secteur à afficher
$SECTEUR = array('tb' => 'grp_sect_atmopaca_s1', 'col' => 'id_sect_atmopaca_s1');

// Code couleur (valeurs RGB)
// Le nombre de valeurs doit correspondre exactement au nombre de ligne de la table du secteur (ci-dessus)
$COULEURS = array('b4ff5a', 'FF6600', 'FFFC98', 'FFCB04', '96D0FF', '1077B2');

// Configuration des niveaux géographiques
$PC = array(
	'grp_geo_reg' => array(
		'nom' => 'R&eacute;gion',
		'colonne' => "id_geo_reg",
		'pcreg' => false,
		'pcdep' => false
		),
	'grp_geo_dep' => array(
		'nom' => 'D&eacute;partement',
		'colonne' => "id_geo_dep",
		'pcreg' => true,
		'pcdep' => false
		),
	'grp_geo_zas' => array(
		'nom' => "Zone Administrative de Surveillance",
		'colonne' => "id_geo_zas",
		'pcreg' => true,
		'pcdep' => false,
		),
	'grp_geo_uu' => array(
		'nom' => 'Unit&eacute; urbaine',
		'colonne' => "id_geo_uu",
		'pcreg' => true,
		'pcdep' => false
		),
	'grp_geo_com_com' => array(
		'nom' => 'Communaut&eacute;',
		'colonne' => "id_geo_com_com",
		'pcreg' => true,
		'pcdep' => false
		),
	'grp_geo_commune' => array(
		'nom' => 'Commune',
		'colonne' => "id_geo_commune",
		'pcreg' => true,
		'pcdep' => true
		)
);

