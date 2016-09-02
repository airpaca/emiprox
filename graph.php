<?php

// Ouverture des fonctions et paramètres
include("cfg/parametres.cfg.php");
include("cfg/parametres.mysql.cfg.php");
include("fonctions.inc.php");
$debug = true;

// Récupération des données passées dans l'URL ou via le formulaire
if (array_key_exists('sent', $_POST)) {
    $level_geo = $_POST['geotyp'];
    $geo = $_POST['geoid'];
    //$annee = $_POST['annee'];
}
else {
    $level_geo = $_GET['geotyp'];
    $geo = $_GET['geoid'];
    //$annee = $_GET['annee'];
}
$list_polluants = array_keys($POLLUANTS);
// TODO: traitement de $annee !

// Connection à la base de données
mysql_connect($MYSQL['host'], $MYSQL['user'], $MYSQL['password']);
mysql_select_db($DB);

// Vérification
if (!$geo) {
    die("cannot find 'geoid' arg !");
}
if (!$level_geo) {
    die("cannot find 'geotyp' arg !");
}
/*if (!$annee) {
    die("cannot find 'annee' arg !");
}*/

// Hack code geo !
if (array_key_exists('geoid', $_GET) && $level_geo == 'commune') {
    $geo = '193'.$geo;
}
if (array_key_exists('geoid', $_GET) && $level_geo == 'dep') {
    $geo = '93'.$geo;
}


// Entete HTML
include("debut.inc.php");

// Lecture du nom de l'entité geographique
$reponse = mysql_query("SELECT `name` FROM `grp_geo_".$level_geo."` WHERE `id` = '".$geo."'");
$nom_geo = $PC[$level_geo]['nom']." ".mysql_result($reponse, 0);
echo "        <!-- Title -->\n";
echo "        <div class=\"row\">\n";
echo "            <div class=\"col-lg-12\">\n";
echo "                <h3 class='emi_title'>".$nom_geo."</h3>\n";
echo "            </div>\n";
echo "        </div>\n";
echo "        <!-- /.row -->\n";

// Variables intermédiaires
$colonne = $PC[$level_geo]['colonne']; // lecture de la colonne de sélection
$with_pcreg = $PC[$level_geo]['pcreg']; // présence du % région
$with_pcdep = $PC[$level_geo]['pcdep']; // présence du % département

// Correspondance nom des secteurs
$sql = "SELECT `name`, `shortname` FROM `".$SECTEUR['tb']."` ORDER BY `id`";
$sects = array();
$reponse = mysql_query($sql);
while($enr = mysql_fetch_array($reponse)) {
    $sects[$enr[0]] = $enr[1];
}

// Composition de la requete d'extraction des émissions par secteur
$sql = "SELECT g.`name` as `secteur`, ";
foreach($POLLUANTS as $pol => $tpol) {
    $sql .= "COALESCE(e.`" . $pol . "`, 0) as `". $pol ."`, ";
}
$sql = substr($sql, 0, -2);
$sql .= " FROM `".$SECTEUR['tb']."` g LEFT JOIN (SELECT `".$SECTEUR['col']."`, ";
foreach($POLLUANTS as $pol => $tpol) {
    $sql .= "sum(`".$tpol['col']."`) as `". $pol ."`, ";
}
$sql = substr($sql, 0, -2);
$sql .= " FROM `emi_total_".$IE['version']."` WHERE `".$colonne."` = '".$geo."' GROUP BY `".$SECTEUR['col']."`) e ON e.`".$SECTEUR['col']."` = g.`id` ORDER BY g.`name`";
if ($debug) {
    echo "<!-- SQL = \n $sql \n -->";
}

// Extraction des données
$data = array();
$reponse = mysql_query($sql);
while($enr = mysql_fetch_array($reponse)) {
    foreach($POLLUANTS as $pol => $tpol) {
        $data[$pol][$enr[0]] = $enr[$pol];
    }
}

// Calcul de la somme des émissions par polluants
foreach($POLLUANTS as $pol => $tpol) {
    $sum[$pol] = array_sum($data[$pol]);
}

// Extraction des émissions régionales totales si nécessaire
if ($with_pcreg) {
    $sql = "SELECT ";
    foreach($POLLUANTS as $pol => $tpol) {
        $sql .= "sum(`".$tpol['col']."`) as `".$pol."`, ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= " FROM `emi_total_".$IE['version']."`";
    $reponse = mysql_query($sql);
    $reg = mysql_fetch_array($reponse);
}
else {
    $reg = null;
}

// Extraction des émissions départementales totales
if ($with_pcdep) {
    $code_dep = substr($geo, 1, 4); // extraction du code départment

    $sql = "SELECT ";
    foreach($POLLUANTS as $pol => $tpol) {
        $sql .= "sum(`".$tpol['col']."`) as `".$pol."`, ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= " FROM `emi_total_".$IE['version']."` WHERE `id_geo_dep` = '".$code_dep."'";
    $reponse = mysql_query($sql);
    $dep[$code_dep] = mysql_fetch_array($reponse);
}
else {
    $dep = null;
}

// Composition de la page
echo "        <!-- Page Features -->\n";
echo "        <div class=\"row text-center\">\n";

// Affiche une cellules pour chaque polluant
foreach($list_polluants as $ipol => $pol) {

    // Information sur le polluant
    $tpol = $POLLUANTS[$pol];

    // Calcul des %dep et %reg
    if ($with_pcreg) {
        $pcReg[$pol] = $sum[$pol] / $reg[$pol] * 100;
        if ($pcReg[$pol] < 1) {
            $precisionReg = 2;
        }
        else {
            $precisionReg = 0;
        }
    }

    if ($with_pcdep) {
        $pcDep[$pol][$code_dep] = $sum[$pol] / $dep[$code_dep][$pol] * 100;
        if ($pcDep[$pol][$code_dep] < 1) {
            $precisionDep = 2;
        }
        else {
            $precisionDep = 0;
        }
    }

    // Convertion quantité / unité
    $sumkg2o = kg2o($sum[$pol], $tpol['unite']);

    // HTML
    //$html_annee = $annee == 'latest' ? $IE['annee'] : $annee;
    $html_annee = $IE['annee'];
    echo "            <div class=\"col-md-6 col-sm-6 hero-feature\">\n";
    echo "                <div class=\"thumbnail\" style=\"text-align: center;\">\n";
    echo "                    <div class=\"caption\">\n";
    echo "                        <h3><span class=\"label label-default\">".$tpol['html']."</span></h3>\n";
    echo "                        <p><b>".$tpol['nom']."</b></p>\n";
    echo "                        <div class='emi_src'>Inventaire des &eacute;missions ".$html_annee.", ".$AASQA['nom']."</div>\n";
    echo "                    </div>\n";
    echo "                    <div id=\"chart_".$ipol."\" style=\"height: 300px; width: 90%;\"></div>\n";
    echo "                    <h3 class='emi_quantite'>".number_format($sumkg2o[0], 0, ',', ' ')." ".$sumkg2o[1]."</h3>\n";

    if ($with_pcdep || $with_pcreg) {
        echo "                    <p>";
        if ($with_pcdep) {
            echo number_format($pcDep[$pol][$code_dep], $precisionDep, ',', ' ')." % du d&eacute;partement<br />";
        }
        if ($with_pcreg) {
            echo number_format($pcReg[$pol], $precisionReg, ',', ' ')." % de la r&eacute;gion";
        }
        echo "</p>";
    }
    echo "                </div>\n";
    echo "            </div>\n";

    // Mise en forme des données
    $gvaleurs = array();
    $glegend = array();
    $gpourcent = array();
    foreach($data[$pol] as $key => $val) {
        $gvaleurs[] = round($val);
        $glegend[] = $key; //utf8_encode($key);
        $gpourcent[] = round(($val/$sum[$pol])*100);
    }

    ?>

            <script>

            var chart_<?php echo $ipol; ?> = new CanvasJS.Chart("chart_<?php echo $ipol; ?>",
                {
                    exportEnabled: false,
                    backgroundColor: "",
                    data: [
                        {
                            type: "pie",
                            startAngle: -90,
                            showInLegend: false,
                            toolTipContent: "{label}: <b>{y}%</b>",
                            indexLabel: "{shortname}",
                            indexLabelFontSize: 12,
                            dataPoints: [
                                <?php
                                for ($i=0; $i<6; $i++) {
                                    echo "{ y: ".$gpourcent[$i].", color: '#".$COULEURS[$i]."', label: \"".$glegend[$i]."\", shortname: \"".$sects[$glegend[$i]]."\" },\n";
                                }
                                ?>
                            ]
                        }
                    ]
                });
            chart_<?php echo $ipol; ?>.render();

            </script>

    <?php
}
echo "        </div>\n";
echo "        <!-- /.row -->";

// Déconnexion à MySQL
mysql_close();

// Fin de la page HTML
include("fin.inc.php");
