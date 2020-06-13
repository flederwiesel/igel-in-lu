<?php

$host = $_SERVER['HTTP_HOST'];
$host = $_SERVER["SERVER_NAME"];
$path = dirname($_SERVER["SCRIPT_NAME"]);
$api = "https://$host$path/api/v0.1";

/******************************************************************************
 * detect device type
 ******************************************************************************/

$Mobile_Detect = 'd5d87b4';

require_once "$_SERVER[DOCUMENT_ROOT]/lib/Mobile-Detect/$Mobile_Detect/Mobile_Detect.php";

$device = new Mobile_Detect();

if (!$device)
{
	$mobile = FALSE;
	$tablet = FALSE;
}
else
{
	/* Treat tablets as desktop */
	$mobile = $device->isMobile() && !$device->isTablet();
	$tablet = $device->isTablet();

	unset($device);
}

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Igel in Ludwigshafen</title>
	<meta name="language" content="de">
	<meta name="robots" content="noindex, nofollow">
	<meta name="author" content="Tobias Kühne">
<?php
	if ($mobile && !$tablet) { ?>
	<meta name="viewport" content="width=device-width; initial-scale=1.0;"/>
<?php } ?>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="favicon-48.png" sizes="48x48">
	<link rel="icon" type="image/png" href="favicon-96.png" sizes="96x96">
	<link rel="icon" type="image/svg+xml" href="favicon.svg" sizes="any">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
</head>
<body>
<style>

body
{
	height: 100%;
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, Sans Serif
}

h1
{
	display: inline;
	font-size: 24pt;
	width: inherit;
	padding: 0.2em 0;
	page-break-inside: avoid;
}

select .none { background-color: #EEBF9E; }
select .ff00 { background-color: #f00; color: #fff; }
select .ff90 { background-color: #f90; }
select .fff0 { background-color: #ff0; }
select .f3f0 { background-color: #3f0; }
select .f00f { background-color: #00f; color: #fff; }
select .fc0f { background-color: #c0f; color: #fff; }
select .ff9f { background-color: #f9f; }
select .f0ff { background-color: #0ff; }
select .ffff { background-color: #fff; }

form div
{
	padding: 0.2em;
}

form div select:first-child
{
	margin-left: 1em;
}

.submit
{
	margin-top: 1em;
	text-align: center;
}

.sidebar-item
{
	width:100%;
	display:block;
	padding: 0.5em;
	text-align:right;
	border:none;
}

#header
{
	width: 100%;
	display: table;
}

#header *
{
	display: table-cell;
	vertical-align: middle;
}

#header > span
{
	background: white;
}

#hamburger
{
	width: 1.2em;
	margin: 0 .05em;
	padding: 0;
	border: none;
	cursor: pointer;
	font-size: 18pt;
	font-weight: bold;
	background: white;
	text-align: left;
}

#sidebar
{
	height:100%;
	background-color:#fff;
	position:fixed!important;
	z-index:1;
	overflow:auto
	border-right:1px solid #ccc!important
	-webkit-box-shadow: 3px 3px 6px 0px rgba(32,32,32,1);
	-moz-box-shadow: 3px 3px 6px 0px rgba(32,32,32,1);
	box-shadow: 3px 3px 6px 0px rgba(32,32,32,1);
}

#sidebar > div
{
	padding: 0.5em;
	font-weight: bold;
}

/* Always set the map height explicitly to define the size of the
 *  div element that contains the map. */
#map
{
	height: 100%;
}

</style>

<div id="header">
	<span>
		<input id="hamburger" type="submit" value="&#9776;" onclick="toggleSidebar()"/>
	</span>
	<h1>Igelhilfe&nbsp;Ludwigshafen</h1>
</div>

<div style="display:none" id="sidebar">
	<button onclick="closeSidebar()" class="sidebar-item">Close &times;</button>
	<div>Filter anwenden:</div>
	<form style="padding: .5em">
		<div class="cell">
			<label for="timespan">Zeitspanne</label>
		</div>
		<div class="cell">
			<select id="timespan">
				<option value="">Alle</option>
				<option value="-1d">Gestern</option>
				<option value="-2d">Vorgestern</option>
				<option value="-3d">Letzte 3 Tage</option>
				<option value="-1w">Letzte Woche</option>
				<option value="-1m">Letzer Monat</option>
				<option value="-2m">Letzte 2 Monate</option>
				<option value="-3m">Letzte 3 Monate</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
			</select>
		</div>
		<div class="cell">
			<label for="marker1">Markierung</label>
		</div>
		<div class="cell">
			<select id="marker1">
				<option value="">alle</option>
				<option value="0000" class="none">ohne</option>
				<option value="ff00" class="ff00">rot</option>
				<option value="ff90" class="ff90">orange</option>
				<option value="fff0" class="fff0">gelb</option>
				<option value="f3f0" class="f3f0">grün</option>
				<option value="f00f" class="f00f">blau</option>
				<option value="fc0f" class="fc0f">lila</option>
				<option value="ff9f" class="ff9f">rosa</option>
				<option value="f0ff" class="f0ff">cyan</option>
				<option value="ffff" class="ffff">weiß</option>
			</select>
			<select id="marker2">
				<option value="">alle</option>
				<option value="0000" class="none">ohne</option>
				<option value="ff00" class="ff00">rot</option>
				<option value="ff90" class="ff90">orange</option>
				<option value="fff0" class="fff0">gelb</option>
				<option value="f3f0" class="f3f0">grün</option>
				<option value="f00f" class="f00f">blau</option>
				<option value="fc0f" class="fc0f">lila</option>
				<option value="ff9f" class="ff9f">rosa</option>
				<option value="f0ff" class="f0ff">cyan</option>
				<option value="ffff" class="ffff">weiß</option>
			</select>
		</div>
		<div class="cell">
			<label for="condition">Zustand</label>
		</div>
		<div class="cell">
			<select id="condition">
				<option value="">alle</option>
				<option value="healthy">gesund</option>
				<option value="needy">hilfsbedürftig</option>
				<option value="dead">tot</option>
			</select>
		</div>
		<div class="cell">
			<input type="checkbox" id="nozoom">Zoom beibehalten
		</div>
		<div class="submit">
			<div><input type="reset"/></div>
			<input type="submit"/>
		</div>
	</form>
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

	map.data.loadGeoJson("<?php echo "$api/discoveries.php"; ?>");

	// When the user clicks, open an infowindow
	map.data.addListener('click',
		function(event)
		{
			var timestamp = event.feature.getProperty("timestamp");
			var marker = event.feature.getProperty("marker");
			var gender = event.feature.getProperty("gender");
			var birth = event.feature.getProperty("birth");
			var notes = event.feature.getProperty("notes");

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

			if (notes)
			{
				notes = "<div>" + notes + "</div>";
				div += notes;
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

	if (window.innerHeight != header.offsetHeight + mapdiv.offsetHeight)
		mapdiv.style.height = (window.innerHeight - header.offsetHeight) + "px";
}

window.addEventListener("resize", resizeMap);

</script>

<script>

function toggleSidebar() {
	var sidebar = document.getElementById("sidebar");

	if ("block" == sidebar.style.display)
		sidebar.style.display = "none";
	else
		sidebar.style.display = "block";
}

function openSidebar() {
	document.getElementById("sidebar").style.display = "block";
}

function closeSidebar() {
	document.getElementById("sidebar").style.display = "none";
}

var form = document.getElementsByTagName("form")[0];

form.addEventListener("submit", function(event)
{
	event.preventDefault();

	var query;
	var timespan;
	var marker;
	var condition;

	query = "";

	timespan = document.getElementById("timespan");
	timespan = timespan.options[timespan.selectedIndex].value;

	if (timespan)
		query += (query.length ? "&" : "?") + "span=" + timespan;

	[ 1, 2 ]. forEach( function(item, index)
	{
		marker = document.getElementById("marker" + item);
		marker = marker.options[marker.selectedIndex].value

		if (marker)
			query += (query.length ? "&" : "?") + "marker[]=" + marker;
	});

	condition = document.getElementById("condition");
	condition = condition.options[condition.selectedIndex].value

	if (condition)
		query += (query.length ? "&" : "?") + "condition=" + condition;

	map.data.forEach(function(feature) {
		map.data.remove(feature);
	});

	map.data.loadGeoJson("<?php echo "$api/discoveries.php"; ?>" + query, "",
		function(features) {
			var zoom = !document.getElementById("nozoom").checked;

			if (zoom) {
				var bounds = new google.maps.LatLngBounds();

				features.forEach(function(feature) {
					feature.getGeometry().forEachLatLng(function(pos) {
						bounds.extend(pos);
					});
				});

				map.fitBounds(bounds);
			}
		});

	if (infowindow)
		infowindow.close();

	closeSidebar();
});

</script>

</body>
</html>
