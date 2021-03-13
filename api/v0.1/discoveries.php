<?php

header("Content-Type: application/json;charset=utf-8");

require_once("lib/database.php");

$where = NULL;
$params = [];

if (isset($_GET["span"]))
{
	$span = $_GET["span"];

	if ("-" == substr($span, 0, 1))
	{
		if (sscanf(substr($span, 1), "%u%[dwmy]", $n, $interval) == 2)
		{
			switch ($interval)
			{
			case "d":
				$interval = "DAY";
				break;

			case "w":
				$interval = "WEEK";
				break;

			case "m":
				$interval = "MONTH";
				break;

			case "y":
				$interval = "YEAR";
				break;

			default:
				$interval = NULL;
				break;
			}

			if ($interval)
			{
				$where .= $where ? " AND" : "WHERE";
				$where .= " `timestamp` > DATE_SUB(NOW(), INTERVAL $n $interval)";
			}
		}
	}
	else
	{
		if (sscanf($span, "%D", $y) == 1)
		{
			$where .= $where ? " AND" : "WHERE";
			$where .= " `timestamp` LIKE '$y-%'";
		}
	}
}

if (isset($_GET["marker"]))
{
	$markers = explode("-", $_GET["marker"]);

	$n = 0;

	foreach ($markers as $marker)
	{
		if (ctype_xdigit($marker))
		{
			$n++;
			$where .= $where ? " AND" : "WHERE";
			$where .= " marker$n = :marker$n";
			$params["marker$n"] = $marker;
		}
	}
}

if (isset($_GET["condition"]))
{
	switch ($_GET["condition"])
	{
	case "healthy":
		$condition = "HEALTHY";
		break;

	case "needy":
		$condition = "NEEDY";
		break;

	case "dead":
		$condition = "DEAD";
		break;

	default:
		$condition = NULL;
	}

	if ($condition)
	{
		$where .= $where ? " AND" : "WHERE";
		$where .= " `condition` = :condition";
		$params["condition"] = $condition;
	}
}

$query = <<<SQL
	/*[Q1]*/
	SELECT
		`timestamp`,
		`condition`,
		`lat`,
		`lon`,
		`hedgehog`,
		`gender`,
		`hedgehogs`.`notes` AS `notes`,
		CONCAT(`marker1`, '-', `marker2`) AS `marker`
	FROM `discoveries`
	INNER JOIN `hedgehogs` ON `hedgehogs`.`id` = `discoveries`.`hedgehog`
	$where
	ORDER BY `timestamp`
SQL;

try
{
	$st = $db->prepare($query);
	$st->execute($params);

	while ($row = $st->fetch(PDO::FETCH_OBJ))
	{
		$features[] = (object)array(
			"type" => "Feature",
			"properties" => (object)array(
				"timestamp" => $row->timestamp,
				"hedgehog" => $row->hedgehog,
				"condition" => $row->condition,
				"gender" => $row->gender,
				"notes" => $row->notes,
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
		"features" => isset($features) ? $features : []
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
