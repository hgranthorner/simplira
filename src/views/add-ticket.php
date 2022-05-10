<h1>Simplira</h1>
<form action="/api/todo.php" method="POST">
	<div>
		<label for="name">name: </label>
		<input name="name" type="text" />
	</div>
	<div>
		<label for="status">status: </label>
		<select name="status">
			<?php
			include("{$_SERVER['DOCUMENT_ROOT']}/../Status.php");

			foreach (Status::$statuses as $statusString) {
				$status = new Status($statusString);

				echo "<option value={$status->rawString}>"
					. $status->displayName()
					. '</option>';
			}
			?>
		</select>
	</div>
		<div>
		<label for="priority">priority: </label>
		<select name="priority">
			<?php
			include("{$_SERVER['DOCUMENT_ROOT']}/../constants.php");

			foreach (Priority::cases() as $priority) {

				echo "<option value={$priority->value}>"
					. $priority->toString()
					. '</option>';
			}
			?>
		</select>
	</div>
	<button type="submit">Submit</button>
</form>