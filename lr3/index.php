<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lr3</title>
</head>

<body>

    <?php

    echo "<h1>Завдання 1</h1>";
    $fontSize = isset($_COOKIE['font_size']) ? $_COOKIE['font_size'] : 'medium';

    if (isset($_GET['size'])) {
        $size = $_GET['size'];
        setcookie('font_size', $size, time() + (86400 * 30), "/");
        $fontSize = $size;
    }

    echo "<div style=\"font-size: $fontSize;\">Текст установленого розміру шрифту</div>";
    echo "<br><br>";
    echo "<a href=\"{$_SERVER['PHP_SELF']}?size=large\">Великий шрифт</a> | ";
    echo "<a href=\"{$_SERVER['PHP_SELF']}?size=medium\">Середній шрифт</a> | ";
    echo "<a href=\"{$_SERVER['PHP_SELF']}?size=small\">Маленький шрифт</a>";

    echo "<h1>Завдання 2</h1>";

    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        echo "Добрий день, {$_SESSION['username']}! <br>";
        echo "<form method=\"POST\">
                <input type=\"submit\" name=\"logout\" value=\"Вийти\">
              </form>";
    } else {

        if (isset($_POST['login']) && isset($_POST['password'])) {
            if ($_POST['login'] === 'Admin' && $_POST['password'] === 'password') {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_POST['login'];
                echo "Добрий день, {$_SESSION['username']}! <br>";
                echo "<form method=\"POST\"><br>
                        <input type=\"submit\" name=\"logout\" value=\"Вийти\">
                      </form>";
            } else {
                echo "Невірний логін або пароль.";
                echo "<form method=\"POST\"><br>
                <input type=\"submit\" name=\"logout\" value=\"Ввести заново\">
              </form>";
            }
        } else {
            echo "<form method=\"POST\">
                    <label for=\"login\">Логін:</label><br>
                    <input type=\"text\" id=\"login\" name=\"login\"><br>
                    <label for=\"password\">Пароль:</label><br>
                    <input type=\"password\" id=\"password\" name=\"password\"><br><br>
                    <input type=\"submit\" value=\"Увійти\">
                  </form>";
        }
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    // echo "<h1>Завдання 3-1</h1>";
    function saveComment($name, $comment)
    {
        $filename = 'comments.txt';

        $file = fopen($filename, 'a');

        fwrite($file, $name . '|' . $comment . "\n");
        fclose($file);
    }


    function displayComments()
    {
        $filename = 'comments.txt';
        $file = fopen($filename, 'r');

        echo "<table border='1'>";
        echo "<tr><th>Ім'я</th><th>Коментар</th></tr>";
        while (!feof($file)) {
            $line = fgets($file);
            $data = explode('|', $line);
            if (count($data) == 2) {
                echo "<tr><td>{$data[0]}</td><td>{$data[1]}</td></tr>";
            }
        }
        echo "</table>";
        fclose($file);
    }

    function clearComments()
    {
        $filename = 'comments.txt';
        $file = fopen($filename, 'w');
        fclose($file);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $comment = $_POST['comment'] ?? '';

        if (!empty($name) && !empty($comment)) {
            saveComment($name, $comment);
        }

        if (isset($_POST['clear_comments'])) {
            clearComments();
        }
    }

    function readWordsFromFile($filename)
    {
        $words = [];
        $file = fopen($filename, 'r');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $words = array_merge($words, explode(' ', trim($line)));
            }
            fclose($file);
        }
        return $words;
    }

    ?>


    <h2>Завдання 3-1</h2>
    <form method="post" action="">
        <label for="name">Ім'я:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="comment">Коментар:</label><br>
        <textarea id="comment" name="comment" required></textarea><br><br>
        <input type="submit" value="Надіслати">
    </form>
    <br>
    <?php displayComments(); ?>
    <br>
    <form method="post" action="">
        <input type="hidden" name="clear_comments" value="1">
        <input type="submit" value="Очистити коментарі">
    </form>

    <h2>Завдання 3-2</h2>
    <form method="post">
        <label for="filename">Введіть ім'я файлу для видалення:</label><br>
        <input type="text" id="filename" name="filename"><br><br>
        <input type="submit" value="Видалити файл">
    </form>

    <?php

    //echo "<h1>Завдання 3-2</h1>";
    function writeWordsToFile($filename, $words)
    {
        $file = fopen($filename, 'w');
        if ($file) {
            foreach ($words as $word) {
                fwrite($file, $word . "\n");
            }
            fclose($file);
        }
    }

    $file1_words = readWordsFromFile('file1.txt');
    $file2_words = readWordsFromFile('file2.txt');

    $only_in_file1 = array_diff($file1_words, $file2_words);
    writeWordsToFile('only_in_file1.txt', $only_in_file1);

    $in_both_files = array_intersect($file1_words, $file2_words);
    writeWordsToFile('in_both_files.txt', $in_both_files);

    $words_count_file1 = array_count_values($file1_words);
    $words_count_file2 = array_count_values($file2_words);

    $in_both_files_more_than_two = [];
    foreach ($in_both_files as $word) {
        if (($words_count_file1[$word] ?? 0) > 2 && ($words_count_file2[$word] ?? 0) > 2) {
            $in_both_files_more_than_two[] = $word;
        }
    }
    writeWordsToFile('in_both_files_more_than_two.txt', $in_both_files_more_than_two);

    echo "<br>Було створено файли in_both_files_more_than_two.txt, in_both_files.txt, only_in_file1.txt<br>";

    if (isset($_POST['filename'])) {
        $filename_to_delete = $_POST['filename'];
        if (file_exists($filename_to_delete)) {
            unlink($filename_to_delete);
            echo "Файл $filename_to_delete успішно видалений.";
        } else {
            echo "Файл $filename_to_delete не знайдено.";
        }
    }

    $words = file('words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    echo "<h1>Завдання 3-3</h1>";

    sort($words);

    foreach ($words as $word) {
        echo $word . PHP_EOL;
    }

    ?>
    
    <h1>Завдання 4</h1>
    <form action="" method="post" enctype="multipart/form-data">
        Виберіть зображення для завантаження:
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <input type="submit" value="Завантажити зображення" name="submit">
    </form>

    <?php

    //echo "Завдання 4";

    $targetDir = "uploads/"; 

    if (isset($_POST["submit"])) {
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); 

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "Файл є зображенням - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "Файл не є зображенням.";
            $uploadOk = 0;
        }

        if (file_exists($targetFile)) {
            echo "Вибачте, файл з таким іменем вже існує.";
            $uploadOk = 0;
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Вибачте, тільки файли з розширенням JPG, JPEG, PNG & GIF дозволені.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Вибачте, ваш файл не було завантажено.";

        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                echo "Файл " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " успішно завантажено.";
                echo "<br><img style='width:300px' src='$targetFile' alt='Завантажене зображення'>";
            } else {
                echo "Сталася помилка при завантаженні вашого файлу.";
            }
        }
    }
    ?>

</body>

</html>