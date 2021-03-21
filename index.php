<?php

require_once("$_SERVER[DOCUMENT_ROOT]/lib/curl.php");

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

$req = new curl;

$req->exec("$api/v0.1/colours", $json);

$colours = json_decode($json);

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
	<script src="lib/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
	<script src="lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
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

form div
{
	padding: 0.2em;
}

form div select:first-child
{
	margin-left: 1em;
}

.hedgehog-info
{
	margin: 0 1em;
}

.hedgehog-info .gender
{
	margin-left: 1em;
}

.hedgehog-info .marker
{
	margin-top: 0.5em
}

option.match-all,
option.add-unknown { background-color: white; color: black; }

.argb-0000 { background-color: #EEBF9E; color: black; }
.argb-ff00 { background-color: #f00; color: #fff; }
.argb-ff90 { background-color: #f90; color: black; }
.argb-fff0 { background-color: #ff0; color: black; }
.argb-f3f0 { background-color: #3f0; color: black; }
.argb-f00f { background-color: #00f; color: #fff; }
.argb-fc0f { background-color: #c0f; color: #fff; }
.argb-ff9f { background-color: #f9f; color: black; }
.argb-f0ff { background-color: #0ff; color: black; }
.argb-f3cc { background-color: #3cc; color: black; }
.argb-ffff { background-color: #fff; color: black; }

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
				<?php $d = date('Y', strtotime ('-3 year')); ?><option value="<?php echo $d; ?>"><?php echo $d; ?></option>
				<?php $d = date('Y', strtotime ('-2 year')); ?><option value="<?php echo $d; ?>"><?php echo $d; ?></option>
				<?php $d = date('Y', strtotime ('-1 year')); ?><option value="<?php echo $d; ?>"><?php echo $d; ?></option>
				<?php $d = date('Y', strtotime ('now')); ?><option value="<?php echo $d; ?>"><?php echo $d; ?></option>
			</select>
		</div>
		<div class="cell">
			<label for="marker1">Markierung</label>
		</div>
		<div class="cell">
			<select id="marker1">
				<option value="" class="match-all">alle</option>
				<?php
					foreach ($colours as $c)
						echo "<option value=\"{$c->value}\" class=\"argb-{$c->value}\">{$c->name}</option>\n";
				?>
			</select>
			<select id="marker2">
				<option value="" class="match-all">alle</option>
				<?php
					foreach ($colours as $c)
						echo "<option value=\"{$c->value}\" class=\"argb-{$c->value}\">{$c->name}</option>\n";
				?>
			</select>
		</div>
		<div class="cell">
			<label for="condition">Zustand</label>
		</div>
		<div class="cell">
			<select id="condition">
				<option value="" class="match-all">alle</option>
				<option value="healthy" class="argb-f3f0">gesund</option>
				<option value="needy" class="argb-fff0">hilfsbedürftig</option>
				<option value="dead" class="argb-ff00">tot</option>
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

function toggleSidebar() {
	var sidebar = $("#sidebar");

	if ("block" == sidebar.css("display"))
		sidebar.css("display", "none");
	else
		sidebar.css("display", "block");
}

function openSidebar() {
	$("#sidebar").css("display", "block");
}

function closeSidebar() {
	$("#sidebar").css("display", "none");
}

var map;
var infowindow;

function initMap()
{
	resizeMap();

	infowindow = new google.maps.InfoWindow();

	map = new google.maps.Map(
		document.getElementById("map"),
		{
			mapTypeId: "hybrid",
			tilt: 0,
			center: { lat: 49.5053287, lng: 8.3810766 },
			zoom: 15,
			mapTypeControl: true,
			mapTypeControlOptions:
			{
				mapTypeIds: [ "hybrid", "roadmap", "terrain" ]
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

	// When the user clicks on the map, hide sidebar
	map.addListener("click",
		function(event)
		{
			if ($("#sidebar").is(":visible"))
				closeSidebar();
		}
	);

	// When the user clicks on a marker, open an infowindow
	map.data.addListener("click",
		function(event)
		{
			var timestamp = event.feature.getProperty("timestamp");
			var marker = event.feature.getProperty("marker");
			var gender = event.feature.getProperty("gender");
			var birth = event.feature.getProperty("birth");
			var notes = event.feature.getProperty("notes");

			div = '<div class="hedgehog-info">';
			div += timestamp;

			switch (gender)
			{
			case "MALE":
				gender = '<span class="gender">&#x2642;</span>';
				break;

			case "FEMALE":
				gender = '<span class="gender">&#x2640;</span>';
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
				div += '<div class="marker"><img src="img/' + marker + '.png"></div>';

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
	var header = document.querySelector("#header");
	var mapdiv = document.querySelector("#map");

	if (window.innerHeight != header.offsetHeight + mapdiv.offsetHeight)
		mapdiv.style.height = (window.innerHeight - header.offsetHeight) + "px";
}

$(window).on("resize", resizeMap);

$("form").submit(
function(event)
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

	query += (query.length ? "&" : "?") + "marker";

	[ 1, 2 ].forEach( function(item, index)
	{
		marker = document.getElementById("marker" + item);
		marker = marker.options[marker.selectedIndex].value

		if (marker)
			query += (1 == item ? "=" : "-") + marker;
	});

	condition = document.getElementById("condition");
	condition = condition.options[condition.selectedIndex].value

	if (condition)
		query += (query.length ? "&" : "?") + "condition=" + condition;

	map.data.forEach(function(feature) {
		map.data.remove(feature);
	});

	map.data.loadGeoJson("<?php echo "$api/discoveries.php"; ?>" + query, "",

		function(features)
		{
			var zoom = !document.getElementById("nozoom").checked;

			if (zoom && features.length > 0)
			{
				var bounds = new google.maps.LatLngBounds();

				features.forEach(

					function(feature)
					{
						feature.getGeometry().forEachLatLng(function(pos)
						{
							bounds.extend(pos);
						});
					}
				);

				map.fitBounds(bounds);
			}
		}
	);

	if (infowindow)
		infowindow.close();

	closeSidebar();
});

$("select").change(function() {
	this.className = this.options[this.selectedIndex].className;
});

$("form").on("reset", function(event) {
	form = $(this);

	setTimeout(function() {
		form.find("select").each(function(index, item) {
			item.className = item.options[0].className;
		});
	}, 0);
});

</script>

</body>
</html>
