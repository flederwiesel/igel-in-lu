<?php

header("Content-Type: application/json;charset=utf-8");

require_once("lib/database.php");

if ("GET" == strtoupper("$_SERVER[REQUEST_METHOD]"))
{

$query = <<<SQL
	SELECT
		`name`,
		`value`
	FROM `colours`
	ORDER BY `weight` ASC
SQL;

try
{
	$st = $db->prepare($query);
	$st->execute();

	echo json_encode($st->fetchAll());
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

}

?>
