<?php declare(strict_types=1);
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");

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
        [
            'scheme' => $scheme,
            'query' => $query,
            'host' => $host,
            'port' => $port,
            'path' => $path,
        ] = parse_url($_SERVER['HTTP_REFERER']);

        parse_str($query, $output);
        $output['updated'] = 'failed';
        $output['reason'] = 'invalid status: ' . $status->rawString;

        $query = http_build_query($output);

        $url = "{$scheme}://{$host}:{$port}{$path}?{$query}";
        header("Location: $url");
        exit;
    }

    $statement->bindValue(':name', $name);
    $statement->bindValue(':status', $status->rawString);
    $statement->bindValue(':id', $id);

    $executed = $statement->execute();

    [
        'scheme' => $scheme,
        'query' => $query,
        'host' => $host,
        'port' => $port,
        'path' => $path,
    ] = parse_url($_SERVER['HTTP_REFERER']);

    parse_str($query ?? '', $output);
    $output['updated'] = $id;

    if (!$executed) {
        $output['updated'] = 'failed';
        $output['reason'] = 'db statement failed to execute';
    }


    $query = http_build_query($output);

    $url = "{$scheme}://{$host}:{$port}{$path}?{$query}";
    header("Location: $url");
}
