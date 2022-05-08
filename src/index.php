<script>
  0
</script>
<h1>Simplira</h1>
<?php
if ($updated_id = $_GET['updated'] ?? false) {
  if ($updated_id == 'failed') {
    echo '<p>Failed to update ticket!</p>';
  } else {
    echo "<p>Successfully updated ticket $updated_id</p>";
  }
}
?>
<h2>Tickets</h2>
<?php
$statuses = ["to_do", "in_progress", "done"];

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../database.db");
$results = $db->query('select id, name, status from tickets;');

while ($row = $results->fetchArray()) {
  $id = $row['id'];
  $nameLabel = "\"$id-name\"";
  $statusLabel = "\"$id-status\"";
  $renderStatusOption = function ($value) use (&$row) {
    $displayValue = implode(' ', explode('_', $value));
    if ($value == $row['status']) {
      return "<option selected value=$value>" . $displayValue . "</option>";
    } else {
      return "<option value=$value>" . $displayValue . "</option>";
    }
  };
  echo "<form action=\"/api/todos.php?id=$id\" method=\"POST\">
          <ul>
            <li>
              <label for=$nameLabel>name: </label>
              <input name=$nameLabel type=\"text\" value=\"{$row['name']}\" />
            </li>
            <li>
              <label for=$statusLabel>status: </label>
              <select name=$statusLabel>
              " .
    implode("", array_map($renderStatusOption, $statuses))
    . "
              </select>
            </li>
          </ul>
          <button type=\"submit\">Update</button>
        </form>
        ";
}
?>