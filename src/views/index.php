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
<a href="/add-ticket.php">Add ticket</a>
<button id="toggle-done-tickets">Toggle Done Tickets</button>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");

$db = new SQLite3("{$_SERVER['DOCUMENT_ROOT']}/../../database.db");
$results = $db->query('
  select id, name, status, deleted_at
  from tickets
  where deleted_at is NULL;');

while ($row = $results->fetchArray()) {
  $id = $row['id'];
  $ticketStatus = $row['status'];
  $nameLabel = "\"$id-name\"";
  $statusLabel = "\"$id-status\"";
  $style = $ticketStatus == 'done' ? 'display: none;' : '';
  $renderStatusOption = function ($value) use (&$ticketStatus) {
    $status = new Status($value);
    if ($status->rawString == $ticketStatus) {
      return "<option selected value=$value>"
        . $status->displayName()
        . "</option>";
    } else {
      return "<option value=$value>"
        . $status->displayName()
        . '</option>';
    }
  };
  echo
  "<form action=\"/api/todos.php?id=$id\" 
    method=\"POST\" 
    data-status=\"$ticketStatus\"
    style=\"$style\"
  >
    <ul>
      <li>
        <label for=$nameLabel>name: </label>
        <input name=$nameLabel type=\"text\" value=\"{$row['name']}\" />
      </li>
      <li>
        <label for=$statusLabel>status: </label>
        <select name=$statusLabel>"
    . implode("", array_map($renderStatusOption, Status::$statuses)) .
    "   </select>
      </li>
    </ul>
    <button type=\"submit\">Update</button>
    <button 
      class=\"delete-btn\"
      data-id=\"$id\"
    >
      Delete
    </button>
  </form>";
}
?>