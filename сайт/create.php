<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="create.css">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>
            Фуді блог панель адміністратора
        </h1>
    </header>

    <section>
        <div class="container">
            <a href="index.php">Повернутися на головну</a>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "recipe_DB";
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $image = $_FILES['image']['tmp_name'];
                    $imgContent = addslashes(file_get_contents($image));

                    $sql = "INSERT INTO recipes (title, description, image) VALUES ('$title', '$description', '$imgContent')";

                    if ($conn->query($sql) === TRUE) {
                        echo "Новий рецепт успішно створений";
                    } else {
                        echo "Помилка: " . $sql . "<br>" . $conn->error;
                    }

                    $conn->close();
                }
            ?>
            <form action="create.php" method="post" enctype="multipart/form-data">
                <img src="https://placehold.co/600x400" alt="">
                <input type="file" name="image" id="image">
                <label for="title">Назва рецепту:</label>
                <input type="text" name="title" id="title">
                <label for="description">Опис рецепту:</label>
                <input type="text" name="description" id="description">
                <button type="submit" class="create">Створити</button>
            </form>
        </div>
    </section>
</body>
</html>