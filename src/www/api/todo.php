<?php declare(strict_types=1);
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../../database.db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name   = $_REQUEST['name'];
	$status = new Status($_REQUEST['status']);
	if (!$status->isValid()) {

	}
}
