<?php

declare(strict_types=1);
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");
include("{$_SERVER['DOCUMENT_ROOT']}/../Url.php");
include("{$_SERVER['DOCUMENT_ROOT']}/../constants.php");

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../../database.db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// If $_REQUEST['id'] exists, then it's an update request.
	if ($_REQUEST['id']) {
		$statement = $db->prepare('
		update tickets
		set name = :name,
		  status = :status
		where id = :id
		');

		$id     = $_REQUEST['id'];
		$name   = $_REQUEST["$id-name"];
		$status = new Status($_REQUEST["$id-status"]);
		$executed = false;

		if (!$status->isValid()) {
			$url = new Url($_SERVER['HTTP_REFERER']);

			$url->updateQuery('updated', 'failed');
			$url->updateQuery('reason', 'invalid status: ' . $status->rawString);

			header("Location: {$url->toString()}");
			exit;
		}

		$statement->bindValue(':name', $name);
		$statement->bindValue(':status', $status->rawString);
		$statement->bindValue(':id', $id);

		$executed = $statement->execute();

		$url = new Url($_SERVER['HTTP_REFERER']);

		$url->updateQuery('updated', $id);

		if (!$executed) {
			$url->updateQuery('updated', 'failed');
			$url->updateQuery('reason', 'db statement failed to execute');
		}

		header("Location: {$url->toString()}");
	} else {
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
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
	$id = $_REQUEST['id'];

	$statement = $db->prepare('
		update tickets
			set deleted_at = strftime(\'' . STRFTIME_FORMAT . '\', datetime())
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

$statement = $db->prepare('
update tickets
set name = :name,
  status = :status
where id = :id
');
