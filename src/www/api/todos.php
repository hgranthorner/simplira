<?php

declare(strict_types=1);
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");
include("{$_SERVER['DOCUMENT_ROOT']}/../Url.php");

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../../database.db");
$statement = $db->prepare('
update tickets
set name = :name,
  status = :status
where id = :id
');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
}
