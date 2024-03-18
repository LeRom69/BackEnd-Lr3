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

            if (is_dir($folderPath)) {
                function deleteDirectory($dir)
                {
                    if (!file_exists($dir)) return true;
                    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
                    foreach (scandir($dir) as $item) {
                        if ($item == '.' || $item == '..') continue;
                        if (!deleteDirectory($dir . "/" . $item)) return false;
                    }
                    return rmdir($dir);
                }

                if (deleteDirectory($folderPath)) {
                    echo "Папка '$login' успішно видалена!";
                } else {
                    echo "Виникла помилка під час видалення папки '$login'!";
                }
            } else {
                echo "Папка '$login' не знайдена!";
            }
        } else {
            echo "Неправильний логін або пароль!";
        }
    ?>

    <h2>Видалення папки</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Логін: <input type="text" name="login"><br><br>
        Пароль: <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Видалити папку">
    </form>
    <br>
    <a href="index.php">Повернутися назад</a>

</body>

</html>