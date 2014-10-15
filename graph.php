<?php

// Ouverture des fonctions et paramètres
include("parametres.cfg.php");
include("parametres.mysql.cfg.php");
include("fonctions.inc.php");

// Récupération des données passées dans l'URL ou via le formulaire
if (array_key_exists('sent', $_POST)) {
    $level_geo = $_POST['level_geo'];
    $geo = $_POST['geo'];
    $annee = $_POST['annee'];
}
else {
    $geo = $_GET['geo'];
    $level_geo = $_GET['level_geo'];
    $annee = $_GET['annee'];
}
$list_polluants = array_keys($POLLUANTS);
// TODO: traitement de $annee !

// Connection à la base de données
mysql_connect($MYSQL['host'], $MYSQL['user'], $MYSQL['password']);
mysql_select_db($DB);

// Entete HTML
include("debut.inc.php");

// Vérification
if (!$geo) {
    die("cannot find 'geo' arg !");
}
if (!$level_geo) {
    die("cannot find 'level_geo' arg !");
}
if (!$annee) {
    die("cannot find 'annee' arg !");
}

// Lecture du nom de l'entité geographique
$reponse = mysql_query("SELECT `name` FROM `".$level_geo."` WHERE `id` = '".$geo."'");
$nom_geo = $PC[$level_geo]['nom']." de ".mysql_result($reponse, 0);
echo "<p class='geo'>".$nom_geo."</p>\n";
	
// Variables intermédiaires
$colonne = $PC[$level_geo]['colonne']; // lecture de la colonne de sélection
$with_pcreg = $PC[$level_geo]['pcreg']; // présence du % région
$with_pcdep = $PC[$level_geo]['pcdep']; // présence du % département
	
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
	
// Affiche une cellules pour chaque polluant
foreach($list_polluants as $ipol => $pol) {
    
    // Information sur le polluant
    $tpol = $POLLUANTS[$pol];
		
    echo "<div class='emissions'>\n";
		
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
		
    echo "<table class='tabpol'>\n<tr>\n<td width=300 class='centre'><p><span class='pol'>".$tpol['html']."</span><br /><span class='pol_def'>(".$tpol['nom'].")</span><p>";
		
    // Convertion quantité / unité
    $sumkg2o = kg2o($sum[$pol], $tpol['unite']);
			
    echo "<p><span class='quantite'>".number_format($sumkg2o[0], 0, ',', ' ')." ".$sumkg2o[1]."</span><br /><br />\n";
    if ($with_pcdep || $with_pcreg) {
        echo "<span class='part'>soit...<br />";
        if ($with_pcdep) {
            echo number_format($pcDep[$pol][$code_dep], $precisionDep, ',', ' ')." % du d&eacute;partement<br />";
        }
        if ($with_pcreg) {
            echo number_format($pcReg[$pol], $precisionReg, ',', ' ')." % de la r&eacute;gion";
        }
        echo "</span>";
    }
    echo "</p></td>\n";
    echo "<td class='centre'>\n";
		
    // Mise en forme des données
    $gvaleurs = array();
    $glegend = array();
    $gpourcent = array();
    foreach($data[$pol] as $key => $val) {
        $gvaleurs[] = round($val); 
        $glegend[] = $key; //utf8_encode($key);
        $gpourcent[] = round(($val/$sum[$pol])*100) >= 1 ? round(($val/$sum[$pol])*100)."%" : "" ;
    }
          
    echo "<div id='chart_".$ipol."' class='chart'></div>\n";
    echo "<div class='src'>Inventaire des &eacute;missions ".$annee.", ".$AASQA['nom']."</div>\n";
    ?>

    <script>

    var chart = c3.generate({
        bindto: '#chart_<?php echo $ipol; ?>',
        legend: {
            position: 'right'
        },
        data: {
            columns: [
                <?php
                for ($i=0; $i<6; $i++) {
                    echo "[\"".$glegend[$i]."\", ".$gvaleurs[$i]."],\n";
                }


                ?>
            ],
            type : 'pie',
            colors: {
                <?php
                for ($i=0; $i<6; $i++) {
                    echo "\"".$glegend[$i]."\": '#".$COULEURS[$i]."',\n";
                }
                ?>

            },
            color: function (color, d) {
                return color;
            }
        }
    });

    </script>

    <?php
    echo "</td>\n";
    echo "</tr>\n</table>\n</div>\n";
}
	
// Déconnexion à MySQL
mysql_close();

// Fin de la page HTML
include("fin.inc.php");


