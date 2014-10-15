
function majformulaire() {
	// Utilisation de requête XmlHttpRequest pour le lien entre la liste level_geo et geo
	// http://www.toutjavascript.com/savoir/xmlhttprequest.php3
	
	// affichage de la div d'attente...
	document.getElementById('attente').style.visibility = "visible";
	
	var s_level_geo = formulaire.elements["level_geo"];
	var s_geo = formulaire.elements["geo"];
	var index = s_level_geo.selectedIndex;
	
	if (index < 1) {
		s_geo.options.length = 0;
		s_geo.options[s_geo.options.length] = new Option("--- Pas de collectivité locale sélectionnée ---", "none");
		
		// masque la div d'attente
		document.getElementById('attente').style.visibility = "hidden";
	}
	else {
		var xhr_object = null;

		if (window.XMLHttpRequest) // Firefox
			xhr_object = new XMLHttpRequest();
	
		else if (window.ActiveXObject) // IE
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	
		else {
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}

		xhr_object.open("POST", "xmlhttprequest.php", true);

		xhr_object.onreadystatechange = function() {
			
			if (xhr_object.readyState == 4) {
				eval(xhr_object.responseText);
				
				// masque la div d'attente
				document.getElementById('attente').style.visibility = "hidden";
			}
		}

		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data = "level_geo=" + escape(s_level_geo.options[index].value) + "&form=" + formulaire.name + "&select=geo";
		xhr_object.send(data);
	}
}

// vérification du formulaire
function verifForm() {
	g = document.getElementById("geo");
	if (g.options[g.selectedIndex].value == 'none') {
		alert("Pas de collectivité locale sélectionnée !");
		return false;
	}
	else {
		return true;
	}
}

