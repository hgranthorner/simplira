<h1>Simplira</h1>
<form action="/api/todo.php" method="POST">
	<div>
		<label for="name">name: </label>
		<input name="name" type="text" />
	</div>
	<div>
		<label for="status">status: </label>
		<select name="status">
			<option value="to_do">to do</option>
			<option value="in_progress">in progress</option>
			<option value="complete">complete</option>
		</select>
	</div>
	<button type="submit">Submit</button>
</form>