<!doctype html>
<html>
<head>
<title>Igel in Ludwigshafen REST API v0.1</title>
<style type="text/css">
body
{
	font-family: Arial, Helvetica, Sans Serif;
}
.inline-code
{
	font-family: monospace;
}
.request
{
	background: #ffa;
}
.request-param
{
	background: #ddf;
}
.response
{
	background: #dfd;
}
.example
{
	background: #eee;
}
.error
{
	background: #fdd;
}
div.param-desc
{
	margin: 0 0 1em 2em;
}
a
{
	text-decoration: none;
}
a:link,
a:visited
{
	color: black;
}
a:active,
a:hover
{
	color: #aa00ff;
}
</style>
<?php

$s = empty($_SERVER["HTTPS"]) ? "" : "s";
$url = $_SERVER["REQUEST_URI"];
$len = strlen($url);

if ($url[--$len] != "/")
	$url = dirname($url)."/";

$url = "http{$s}://$_SERVER[HTTP_HOST]$url";

function api($params)
{
	global $url;
	echo "<a href='$url$params'>/$params</a>";
}

function curl($params)
{
	global $url;
	echo "curl -s <a href='$url$params'>$url$params</a>";
}

?>
</head>
<body>

<h1>Igel in Ludwigshafen REST API v0.1</h1>

<div style="margin-bottom: 2em"><?php echo "$url"; ?> may not be called directly.</div>

<div>The following API calls are available:
<ul>
	<li><a href="#discoveries"><pre>GET  discoveries</pre></a></li>
	<li><a href="#colours"><pre>GET  colours</pre></a></li>
</ul>

<hr>
<div id="discoveries">
	<h5>Request:</h5>
	<div>
		<pre class="request">GET <?php echo api("discoveries"); ?></pre>
		<div class="param-desc">Get list of discovered hedgehogs, with zero or more filters applied.</div>
	</div>

	<h5>Parameters:</h5>
	<div>
		<pre class="request-param"><span style="font-style:italic">marker</span> argb-argb</pre>
		<div class="param-desc">A combination of argb color specifiers as head-tail.
			If no marker is specified, the query matches all markers.</div>

		<pre class="request-param"><span style="font-style:italic">condition</span> healthy|needy|dead</pre>
		<div class="param-desc">Condition how the hedgehog has been discovered. Unknown values are
			silently ignored and can therefore lead to huge amounts of data being returned.</div>

		<pre class="request-param"><span style="font-style:italic">span</span> <span class="inline-code">-%u%[dwmy]|%Y</span></pre>
		<div class="param-desc">Relative or absolute time span, where <span class="inline-code">%u</span> is an unsigned integer,
			specifying a timespan relative to the time of query,
			<span class="inline-code">%[dwmy]</span> if one of the characters representing day, week, month or year.
			Alternatively, you can use <span class="inline-code">%Y</span> to specify a year.
			If no marker is specified, the query matches all markers.</div>
	</div>

	<h5>Response:</h5>
	<div>
		<div>The payload is a GeoJSON object as:
			<pre class="response">{
  "type": "FeatureCollection",
  "features":
  [
    {
      "type": "Feature",
      "properties": {
        "timestamp": "2020-06-13 00:20:00",
        "hedgehog": 64,
        "condition": "HEALTHY",
        "gender": "FEMALE",
        "notes": null,
        "marker": "ff00-ff00"
      },
      "geometry": {
        "type": "Point",
        "coordinates": [
          8.379017,
          49.505223
        ]
      }
    }
  ]
}</pre>
		</div>
	</div>

	<h5>curl examples:</h5>
	<div>
		<pre class="example"><?php echo curl("discoveries")."\n"; ?>
<?php echo curl("discoveries?marker=ff00-ff00")."\n"; ?>
<?php echo curl("discoveries?condition=healthy")."\n"; ?>
<?php echo curl("discoveries?span=-1d")."\n"; ?>
<?php echo curl("discoveries?span=-2w")."\n"; ?>
<?php echo curl("discoveries?span=-3m")."\n"; ?>
<?php echo curl("discoveries?span=-1y")."\n"; ?>
<?php echo curl("discoveries?span=2020")."\n"; ?>
<?php echo curl("discoveries?span=2019&marker=0000-ff00&condition=healthy")."\n"; ?></pre>
	</div>
</div>

<hr>
<div id="colours">
	<h5>Request:</h5>
	<div>
		<pre class="request">GET <?php echo api("colours"); ?></pre>
		<div class="param-desc">Get list of colours.</div>
	</div>

	<h5>Parameters:</h5>
	<div>
		<div class="param-desc">None.</div>
	</div>

	<h5>Response:</h5>
	<div>
		<div>A JSON array containing colour objects, containing of name,
			argb value and weight, sorted by weight.
			<pre class="response">[
  {
    "name": "0000",
    "value": "ohne",
    "weight":  "100"
  }
]</pre>
		</div>
	</div>
	<h5>curl examples:</h5>
	<div>
		<pre class="example"><?php echo curl("colours"); ?></pre>
	</div>
</div>

<hr>
<div>On error, all API calls return:
	<pre class="error">{ "error": "$error" }</pre>
</div>

</body>
</html>
