<!DOCTYPE html>
<html>

<head>
    <title>Ex5</title>
</head>

<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $folderPath = $login;

        if (!is_dir($folderPath)) {
            mkdir($folderPath);
            mkdir($folderPath . "/video");
            mkdir($folderPath . "/music");
            mkdir($folderPath . "/photo");

            $videoContent = file_get_contents("C:/wamp64/www/lr3/production.mp4");
            $songContent = file_get_contents("C:/wamp64/www/lr3/fun.mp4");
            $photoContent = file_get_contents("https://masterpiecer-images.s3.yandex.net/b56e38b953aa11eeb79a6a2aaa288599:upscaled");

            file_put_contents($folderPath . "/video/video1.mp4", $videoContent);
            file_put_contents($folderPath . "/music/song1.mp3", $songContent);
            file_put_contents($folderPath . "/photo/photo1.jpg", $photoContent);

            echo "Папка '$login' успішно створена!";
        } 
        else {
            echo "Папка '$login' вже існує!";
        }
    }
    ?>

    <h2>Створення папки</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Логін: <input type="text" name="login"><br><br>
        Пароль: <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Створити папку">
    </form>
    <br>
    <a href="delete.php">Видалити файл</a>

</body>

</html>