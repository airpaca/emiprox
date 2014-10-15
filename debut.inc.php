<!DOCTYPE html>
<!--

<?php

echo html_entity_decode(br2nl($INTRO))."\n\n";
echo html_entity_decode($AASQA['nom'])." | ".html_entity_decode($AASQA['web'])."\n\n";
 
?>
-->

<html lang="fr">
<head>
    <meta charset="latin-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate" />
    <meta name="description" content="<?php echo $DESC; ?>" />
    <meta name="author" content="<?php echo $AASQA['nom']; ?>" />
    <meta name="copyright" content="<?php echo $AASQA['nom']; ?>" />
    <meta name="publisher" content="<?php echo $AASQA['nom']; ?>" />
	
    <title>Emiprox | <?php echo $IE['nom']; ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/heroic-features.css" rel="stylesheet">

    <!-- CanvasJS -->
    <script type="text/javascript" src="js/canvasjs.min.js"></script>

    <!-- Emiprox -->
    <script type="text/javascript" src="js/emiprox.js"></script>
    <link rel="stylesheet" type="text/css" href="css/emiprox.css" media="screen" title="emiprox" />

</head>

<body>
    
    <!-- Page Content -->
    <div class="container">
        
        <!-- Header -->
        <a href='<?php echo $AASQA['web']; ?>'><img id="logo" src='img/logo.png'></a>
        <h1>Emiprox | <?php echo $IE['nom']; ?></h1>

        <div id="intro">
            <p class='intro'>
                <?php echo $INTRO; ?>
            </p>
        </div>
    
    
    