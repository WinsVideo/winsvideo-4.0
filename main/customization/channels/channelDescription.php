<?php
    include_once("../../includes/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinsVideo - Change Description</title>
</head>
<body>
    <div class="content">
        <form action="channelDescription.php" method="post" enctype="multipart/form-data">
            <label for="descriptionInput"></label>
            <textarea name="descriptionInput" id="descriptionInput" cols="30" rows="10" placeholder="Channel description"></textarea>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>