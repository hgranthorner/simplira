<?php

declare(strict_types=1);
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");
include("{$_SERVER['DOCUMENT_ROOT']}/../Url.php");

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../../database.db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name   = $_REQUEST['name'];
	$status = new Status($_REQUEST['status']);
	$url = new Url($_SERVER['HTTP_REFERER']);

	if (!$status->isValid()) {
		$url->updateQuery('updated', 'failed');
		$url->updateQuery('reason', 'invalid status: ' . $status->rawString);

		header("Location: {$url->toString()}");
		exit;
	}

	$statement = $db->prepare('
	insert into tickets (name, status)
	values (:name, :status);
	');

	$statement->bindValue(':name', $name);
	$statement->bindValue(':status', $status->rawString);

	$results = $statement->execute();

	if (!$results) {
		$url->updateQuery('updated', 'failed');
    $url->updateQuery('reason', 'db statement failed to execute');
		header("Location: {$url->toString()}");
		exit;
	}

	$id = $db->lastInsertRowID();

	$url->updateQuery('updated', $id);
	$url->path = '';
	header("Location: {$url->toString()}");
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
	$id = $_REQUEST['id'];

	$statement = $db->prepare('
	delete from tickets
	where id = :id
	');

	$statement->bindValue(':id', $id);
	$executed = $statement->execute();

	$url = new Url($_SERVER['HTTP_REFERER']);

	if (!$executed) {
    $url->updateQuery('updated', 'failed');
    $url->updateQuery('reason', 'db statement failed to execute');
  }

  $url->updateQuery('updated', $id);

  header("Location: {$url->toString()}");
}