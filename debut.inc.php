<!DOCTYPE html>
<!--

<?php

echo html_entity_decode(br2nl($INTRO))."\n\n";
echo html_entity_decode($AASQA['nom'])." | ".html_entity_decode($AASQA['web'])."\n\n";
 
?>
-->

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
	<meta name="Description" content="<?php echo $DESC; ?>" />
	<meta name="Author" content="<?php echo $AASQA['nom']; ?>" />
	<meta name="Copyright" content="<?php echo $AASQA['nom']; ?>" />
	<meta name="Publisher" content="<?php echo $AASQA['nom']; ?>" />
	
	<title>Emiprox | <?php echo $IE['nom']; ?></title>
	
	<script type="text/javascript" src="js/emiprox.js"></script>

        <link rel="stylesheet" href="css/c3.min.css">
        <script src="js/d3.min.js"></script>
        <script src="js/c3.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/emiprox.css" media="screen" title="emiprox" />

</head>

<body>
<a href='<?php echo $AASQA['web']; ?>'><img id="logo" src='img/logo.png'></a><br />
<h1>Emiprox | <?php echo $IE['nom']; ?></h1>
<center><div id='intro'><?php echo $INTRO; ?></div></center>
