<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
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

    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = file_get_contents($_FILES['image']['tmp_name']);

        $stmt = $conn->prepare("INSERT INTO recipes (title, description, image) VALUES (?, ?, ?)");
        $stmt->bind_param("ssb", $title, $description, $image);
        $stmt->send_long_data(2, $image);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }

    ?>

    <section class="first">
        <div class="container">
            <h3>Доступні рецепти:  <div class="search">Пошук: <input type="text"></div></h3>

            <ul>
                <?php
                $sql = "SELECT id, title, description, image FROM recipes";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<li>';
                        echo '<div class="recipe-card">';
                        echo '<a href="recipe.php?id=' . $row["id"] . '">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="">';
                        echo '<h4>' . htmlspecialchars($row["title"]) . '</h4>';
                        echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                        echo '</a>';
                        echo '<button class="delete" onclick="deleteRecipe(' . $row["id"] . ')">Delete</button>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo "0 results";
                }

                $conn->close();
                ?>
            </ul>
        </div>
    </section>

    <a href="create.php" class="create-button-link" onclick="document.getElementById('createForm').style.display='block'">
        <button class="add">   
            +    
        </button>
    </a>

    <script>
        function deleteRecipe(id) {
            if (confirm('Are you sure you want to delete this recipe?')) {
                window.location.href = 'index.php?delete_id=' + id;
            }
        }
    </script>
</body>
</html>