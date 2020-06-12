<?php

require_once("$_SERVER[DOCUMENT_ROOT]/vault/igel-in-lu/config.php");
require_once("sql-xpdo.php");
require_once("uuid.php");

$error = NULL;

try
{
	if (isset($ExplainSQL))
		$classname = 'xPDO';
	else
		$classname = 'PDO';

	$db = new $classname(sprintf('mysql:host=%s;dbname=%s;charset=utf8',
					$cfg->database->host, $cfg->database->db),
					$cfg->database->user, $cfg->database->password,
					[
						PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone='Europe/Berlin'",
					]
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
