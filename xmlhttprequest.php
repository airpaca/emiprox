<?php
header('Content-type: text/html; charset=iso-8859-1');

// Ouverture des parametres
include("cfg/parametres.cfg.php");
include("cfg/parametres.mysql.cfg.php");

// Connection à la base de données
mysql_connect($MYSQL['host'], $MYSQL['user'], $MYSQL['password']);
mysql_select_db($DB);

// Selection des entités géographiques ne commençant pas par '('
$sql = "SELECT `id`, `name` FROM `".$_POST['level_geo']."` WHERE `name` not like '(%' ORDER BY `name`";
$result = @mysql_query($sql);

echo 'var o = null;';
echo 'var s = document.forms["'.$_POST['form'].'"].elements["'.$_POST['select'].'"];';
echo 's.options.length = 0;';
while ($r = mysql_fetch_array($result))
{	echo 's.options[s.options.length] = new Option("'.$r['name'].'", "'.$r['id'].'");';
}

@mysql_close($mysql_db);

