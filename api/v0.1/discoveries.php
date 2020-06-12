<?php

require_once("lib/database.php");

$query = <<<SQL
	/*[Q1]*/
	SELECT
		`timestamp`,
		`condition`,
		`lat`,
		`lon`,
		`hedgehog`,
		`gender`,
		CONCAT(`marker1`, '-', `marker2`) AS `marker`
	FROM `discoveries`
	INNER JOIN `hedgehogs` ON `hedgehogs`.`id` = `discoveries`.`hedgehog`
	ORDER BY `timestamp`
SQL;

try
{
	$st = $db->prepare($query);
	$st->execute();

	while ($row = $st->fetch(PDO::FETCH_OBJ))
	{
		$features[] = (object)array(
			"type" => "Feature",
			"properties" => (object)array(
				"timestamp" => $row->timestamp,
				"hedgehog" => $row->hedgehog,
				"condition" => $row->condition,
				"gender" => $row->gender,
				"marker" => "$row->marker"
			),
			"geometry" => (object)array(
				"type" => "Point",
				"coordinates" => [ $row->lon, $row->lat ]
			)
		);
	}

	echo json_encode((object)array(
		"type" => "FeatureCollection",
		"features" => $features
		), JSON_NUMERIC_CHECK
	);
}
catch (Exception $e)
{
	$date = date("c");
	$uuid = uuid();

	syslog(LOG_EMERG,
		   "Exception ticket $uuid [$date]:\n".
		   "{$e->getCode()}: {$e->getMessage()}\n".
		   "{$e->getTraceAsString()}");

	$error = <<<JSON
{
	"type": "Feature",
	"properties": {
		"error": "A database error occured. For support, refer to ticket $uuid."
	}
}
JSON;

	die("$error");
}

?>
