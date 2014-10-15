<?php

// ouverture des fonctions et parametres
include("parametres.cfg.php");
include("parametres.mysql.cfg.php");
include("fonctions.inc.php");

// debut de la page HTML
include("debut.inc.php");

// formulaire
?>
<div id="form">
<form name="formulaire" method="post" action="graph.php" target="_blank" onsubmit="return verifForm()">

    <p>
        <select id="level_geo" name="level_geo" onChange='majformulaire()'>
            <option selected>1. Choisissez un type de collectivit&eacute;</option>

            <?php
            foreach ($PC as $grp => $info) {
                echo "<option value='".$grp."'>".$info['nom']."</option>\n";
            }
            ?>

        </select>&nbsp;
    
        <select id="geo" name="geo" >
            <option value='none'>2. Choix de la collectivit&eacute;</option>
        </select>&nbsp;
    
        <select id="annee" name="annee">
            <option value="2010">Ann&eacute;e 2010</option>
        </select>
    </p>
    

    <p>
        <br />
        <input type='hidden' name="sent" value=1>
        <input type='submit' value="Consulter...">
    </p>
</form>
</div>

<center>
<div id='attente'>
    Veuillez patientez...<br />Chargement de la liste des collectivit&eacute;s locales.
</div>
</center>


<?php

// Fin de la page HTML
include("fin.inc.php");

