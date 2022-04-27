<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="resize.php" method="post" enctype="multipart/form-data">
        <input type="file"  name="filename">
        <br>
        <div>
            Resize to
            <input type="number" name="width">
            <input type="number" name="height">
        </div>
        <br>
        <button type="submit">Submit</button>
    </form>   
</body>
</html>