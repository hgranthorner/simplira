<?php
$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../database.db");
$statement = $db->prepare('
update tickets
set name = :name,
  status = :status
where id = :id
');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id     = $_GET['id'];
    $name   = $_POST["$id-name"];
    $status = $_POST["$id-status"];
    $statement->bindValue(':name', $name);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $id);
    if ($statement->execute()) {
        [
            'scheme' => $scheme,
            'query' => $query,
            'host' => $host,
            'port' => $port,
            'path' => $path,
        ] = parse_url($_SERVER['HTTP_REFERER']);

        parse_str($query, $output);
        $output['updated'] = $id;

        $query = http_build_query($output);

        $url = "{$scheme}://{$host}:{$port}{$path}?{$query}";
        header("Location: $url");
    }
}
