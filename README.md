Emiprox
=======

**Interface de consultation des résultats de l'inventaire des émissions**  

Structure
---------
* `cfg/parametres.cfg.php` : fichier de configuration général.
* `cfg/parametres.mysql.cfg.php` : fichier de configuration pour la connexion à la base de données.
* `css/*.css` : fichiers de style.
* `img/*` : images (logo).
* `js/*` : fichiers Javascript.
* `README.md` : ce document.
* `debut.inc.php` : entête des pages.
* `fin.inc.php` : pied de page.
* `fonctions.inc.php` : fonctions spécifiques.
* `graph.php` : page des graphiques.
* `index.php` : page principale (forulaire de saisie).

Fonctionnement
--------------

Deux modes de fonctionnement :
* via la page principale par la saisie d'une zone géographique.
* directement via l'URL de la page des graphiques (exemple : `graph.php?annee=2010&lvl=commune&geo=13002`) 

Ressources
---------
* [Bootstrap] pour la partie design/responsive web.
* [JQuery].
* [Selectize.js] pour la partie recherche de la page principale.
* [CanvasJS] pour les graphiques dynamiques.
* [Google Fonts].


[Bootstrap]: http://getbootstrap.com/
[JQuery]: http://jquery.com/
[Selectize.js]: http://brianreavis.github.io/selectize.js/
[CanvasJS]: http://canvasjs.com/
[Google Fonts]: http://www.google.com/fonts
