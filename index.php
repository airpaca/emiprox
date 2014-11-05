<?php

// Ouverture des fonctions et parametres
include("cfg/parametres.cfg.php");
include("cfg/parametres.mysql.cfg.php");
include("fonctions.inc.php");

// Début de la page HTML
include("debut.inc.php");

?>
<script>
    // Envoie du formulaire
    function submitForm() {
        v = document.forms['formulaire'].geonfo.value;
        if (!v) return;
        geotyp = v.split('|')[0];
        geoid = v.split('|')[1];
        document.forms['formulaire'].geoid.value = geoid;
        document.forms['formulaire'].geotyp.value = geotyp;
        document.forms['formulaire'].submit();
    }
    
    // Efface le formulaire
    function cleanForm() {
        $select[0].selectize.clear();
    }
</script>

<?php
// Lecture des entités géographiques
mysql_connect($MYSQL['host'], $MYSQL['user'], $MYSQL['password']);
mysql_select_db($DB);
$geos = array();
foreach ($PC as $lvl => $lvlnfo) {
    $sql = "SELECT `id`, `name` FROM `grp_geo_".$lvl."` WHERE left(`name`, 1) != '(' ORDER BY `name`";
    $reponse = mysql_query($sql);
    while($enr = mysql_fetch_array($reponse)) {
        $geos[$lvl][$enr['id']] = $enr['name'];
    }
}
mysql_close();

// Formulaire
?>
<div class='row'>
    <div class='col-lg-2'></div>
    <div class='col-lg-8'>
        <form name="formulaire" method="post" action="graph.php">
            <input type='hidden' name="sent" value=1 />
            <input type='hidden' name='geoid' value='' />
            <input type='hidden' name='geotyp' value='' />
            <input type='hidden' name='annee' value='<?php echo $IE['annee']; ?>' />

            <div>
                <select id="geonfo" placeholder="Entrez ici le nom de votre commune, votre communaut&eacute; de communes, de d&eacute;partements ..."></select>
                <p><a href="javascript:cleanForm();" class="btn btn-default" role="button">Effacer</a> <a href="javascript:submitForm();" class="btn btn-primary" role="button">Valider</a></p>
            </div>
            <script>
                $select = $('#geonfo').selectize({
                    valueField: 'geoid',
                    labelField: 'geonm',
                    searchField: ['geonm'],
                    options: [
                        <?php
                        foreach ($geos as $geotyp => $geocfg) {
                            foreach ($geocfg as $geoid => $geonm) {
                                echo "{ geoid: \"".$geotyp."|".$geoid."\", geonm: \"".$geonm."\", geotyp: \"".html_entity_decode($PC[$geotyp]['nom'])."\" },\n";
                            }
                        }
                        ?>
                    ],
                    render: {
                        option: function(item, escape) {
                            return "<div><span class='form_geonm'>" + escape(item.geonm) + "</span><br /><span class='form_geotyp'>" + escape(item.geotyp) + "</span></div>";
                        },
                        item: function(item, escape) {
                            return "<div><span class='form_geonm'>" + escape(item.geonm) + "</span> <span class='form_geotyp'>(" + escape(item.geotyp) + ")</span></div>";
                        }
                    }
                });
            </script>
        </form>
    </div>
    <div class='col-lg-2'></div>
</div>

<?php

// Fin de la page HTML
include("fin.inc.php");

