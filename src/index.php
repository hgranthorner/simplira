<script>0</script>
<h1>Simple Jira</h1>
<?php
if ($updated_id = $_GET['updated'] ?? false) {
  echo "<p>Successfully updated ticket $updated_id</p>";
}
?>
<h2>Tickets</h2>
<?php
$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../database.db");
$results = $db->query('select id, name, status from tickets;');

while ($row = $results->fetchArray()) {
  $id = $row['id'];
  $nameLabel = "\"$id-name\"";
  $statusLabel = "\"$id-status\"";
  echo "<form action=\"/api/todos.php?id=$id\" method=\"POST\">
          <ul>
            <li>
              <label for=$nameLabel>name: </label>
              <input name=$nameLabel type=\"text\" value=\"{$row['name']}\" />
            </li>
            <li>
              <label for=$statusLabel>status: </label>
              <input name=$statusLabel type=\"\" value=\"{$row['status']}\" />
            </li>
          </ul>
          <button type=\"submit\">Update</button>
        </form>
        ";
}
?>