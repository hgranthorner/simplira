<html>

<head>
  <title><?php echo $title; ?></title>
  <script>
    0
  </script>
</head>

<body>
  <?php include($childView); ?>
</body>
<script type="application/javascript">
  const buttons = Array.from(document.querySelectorAll('.delete-btn'))
  for (let button of buttons) {
    const id = button.getAttribute('data-id')
    button.addEventListener('click', async (e) => {
      e.preventDefault()
      const res = await fetch(`/api/todo.php?id=${id}`, {
        method: 'DELETE'
      })
      location.reload()
    })
  }

  const toggleButton = document.getElementById('toggle-done-tickets')
  toggleButton?.addEventListener('click', e => {
    e.preventDefault()
    const forms = Array.from(document.querySelectorAll('[data-status="done"]'))

    for (let form of forms) {
      const display = form.style.display
      if (display === 'none') {
        form.style.display = ''
      } else {
        form.style.display = 'none'
      }
    }
  })
</script>

</html>