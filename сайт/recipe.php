<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="recipe.css">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>
            Фуді блог панель адміністратора
        </h1>
    </header>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "recipe_DB";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT title, description, image FROM recipes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($title, $description, $image);
        $stmt->fetch();
        $stmt->close();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = intval($_POST['id']);
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        if ($_FILES['image']['size'] > 0) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $stmt = $conn->prepare("UPDATE recipes SET title = ?, description = ?, image = ? WHERE id = ?");
            $stmt->bind_param("ssbi", $title, $description, $image, $id);
            $stmt->send_long_data(2, $image);
        } else {
            $stmt = $conn->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $description, $id);
        }

        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }

    $conn->close();
    ?>

    <section>
        <div class="container">
            <a href="index.php">Повернутися на головну</a>
            <form action="recipe.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="">
                <input type="file" name="image" id="image">
                <label for="title">Назва рецепту:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>">
                <label for="description">Опис рецепту:</label>
                <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($description); ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="save">Зберегти зміни</button>
            </form>
        </div>
    </section>
</body>
</html>