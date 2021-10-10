<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css">
    <title>Document</title>
    <script src="validator.js"></script>
</head>

<body>

    <form onsubmit="validate(upload_item); return false">
        <input name="item_name" type="text" data-validate="str" data-min="2" data-max="20">
        <button>Upload item</button>
    </form>

    <div id="items"></div>


    <script>
        async function upload_item() {
            const form = event.target
            const item_name = _one("input[name='item_name']", form).value
            const conn = await fetch("apis/api-upload-item", {
                method: "POST",
                body: new FormData(form)
            })
            const res = await conn.text()
            console.log(res)
            if (conn.ok) {
                _one("#items").insertAdjacentHTML('afterbegin', `
        <div class="item">
          <div>${res}</div>
          <div>${item_name}</div>
          <div>
          <button onsubmit="return false" onclick=window.location.href='apis/api-delete-item.php?id=${res}'> 🗑️</button>
          </div>
        </div>`)
            }
            _one("input[name='item_name']", form).value = ""
        }
    </script>

</body>

</html>