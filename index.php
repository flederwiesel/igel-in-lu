<?php
if ("localhost" == $_SERVER["SERVER_NAME"])
	header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Igel in Ludwigshafen</title>
	<meta name="language" content="de">
	<meta name="robots" content="noindex, nofollow">
	<meta name="author" content="Tobias Kühne">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="favicon-48.png" sizes="48x48">
	<link rel="icon" type="image/png" href="favicon-96.png" sizes="96x96">
	<link rel="icon" type="image/svg+xml" href="favicon.svg" sizes="any">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
</head>
<body>
<style>
/* Always set the map height explicitly to define the size of the
 *  div element that contains the map. */
#map
{
	height: 100%;
}
/* Optional: Makes the sample page fill the window. */
html, body
{
	height: 100%;
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, sans-serif;
}
#header
{
	width: 100%;
	display: table;
}
</style>

<div id="header">
	<h1 style = "margin: 0.1em 0">Igelhilfe Ludwigshafen</h1>
</div>
<div id="map"></div>

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=&callback=initMap&language=de&region=DE" async defer></script>

<script>

var map;
var infowindow;

function initMap()
{
	resizeMap();

	infowindow = new google.maps.InfoWindow();

	map = new google.maps.Map(
		document.getElementById('map'),
		{
			mapTypeId: 'hybrid',
			tilt: 0,
			center: { lat: 49.5053287, lng: 8.3810766 },
			zoom: 15,
			mapTypeControl: true,
			mapTypeControlOptions:
			{
				mapTypeIds: [ 'hybrid', 'roadmap', 'terrain' ]
			},
			scaleControl: true,
			streetViewControl: false,
			rotateControl: false
		}
	);

	map.data.loadGeoJson("data.json");

	map.data.setStyle(
		function(feature)
		{
			switch (feature.getProperty("condition"))
			{
			case "HEALTHY":
				return { icon: "img/pins/green.png" };
				break;

			case "NEEDY":
				return { icon: "img/pins/yellow.png" };
				break;

			case "DEAD":
				return { icon: "img/pins/red.png" };
				break;

			default:
				return { icon: "img/pins/black.png" };
				break;
			}
		}
	);

	// When the user clicks, open an infowindow
	map.data.addListener('click',
		function(event)
		{
			var timestamp = event.feature.getProperty("timestamp");
			var marker = event.feature.getProperty("marker");
			var gender = event.feature.getProperty("gender");
			var birth = event.feature.getProperty("birth");
			var desc = event.feature.getProperty("description");

			div = "<div style='margin: 0 1em'>";
			div += timestamp;

			switch (gender)
			{
			case "MALE":
				gender = "<span style = 'margin-left: 1em'>&#x2642;</span>";
				break;

			case "FEMALE":
				gender = "<span style = 'margin-left: 1em'>&#x2640;</span>";
				break;

			default:
				gender = "";
				break;
			}

			div += gender;

			if (birth)
			{
				birth = " " + birth;
				div += birth;
			}

			if (desc)
			{
				desc = "<div>" + desc + "</div>";
				div += desc;
			}

			if (marker)
				div += "<div style = 'margin-top: 0.5em'><img src='img/" + marker + ".png'></div>";

			div += "</div>";

			infowindow.setContent(div);

			// position the infowindow on the marker
			infowindow.setPosition(event.feature.getGeometry().get());
			// anchor the infowindow on the marker
			infowindow.setOptions({ pixelOffset: new google.maps.Size(0,-36) });
			infowindow.open(map);
		}
	);

	google.maps.event.addListener(map, "click",
		function(event)
		{
			if (infowindow)
				infowindow.close();
		}
	);

}

function resizeMap()
{
	var header = document.querySelector('#header');
	var mapdiv = document.querySelector('#map');

	if (window.innerHeight < header.offsetHeight + mapdiv.offsetHeight)
		mapdiv.setAttribute("style", "height: " + (window.innerHeight - header.offsetHeight) + "px");
}

window.addEventListener("resize", resizeMap);

</script>

</body>
</html>
