<?php
session_start();

if (empty($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newsdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Insert data into database
    $sql = "INSERT INTO news (title, content) VALUES ('$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        echo "<script> alert('News added successfully.'); </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["delete"])) {
    $newsId = $_GET["delete"];

    $sql = "DELETE FROM news WHERE id = '$newsId'";
    if ($conn->query($sql) === TRUE) {
        echo "<script> alert('News deleted successfully.'); </script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $updateId = $_POST["update_id"];
    $newTitle = $_POST["new_title"];
    $newContent = $_POST["new_content"];

    $sql = "UPDATE news SET title='$newTitle', content='$newContent' WHERE id='$updateId'";
    if ($conn->query($sql) === TRUE) {
        echo "<script> alert('News updated successfully.'); </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch and display news
$sql = "SELECT id, title, content FROM news";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <style>
    body, h1, h2, h3, h4, h5, h6, p, ul, li {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
}


nav {
    background-color: #333;
    color: white;
    padding: 10px 0;
    position: relative;
    z-index: 1;
}

nav ul {
    list-style: none;
    text-align: center;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: white;
    text-decoration: none;
}

/* Sections */
.add-news, .news-list {
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
    margin: 20px;
    padding: 20px;
}

.add-news h2, .news-list h2 {
    margin-top: 0;
    color: #333;
}

/* Form Styling */
label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"], textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 14px;
}

button {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 14px;
}

button:hover {
    background-color: #555;
}

/* News List */
.news-list article {
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
    padding-bottom: 20px;
}

.news-list article h3 {
    margin-top: 0;
    color: #333;
}

.news-list article p {
    color: #666;
}

/* Edit Form */
#edit-form {
    
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
    margin: 20px;
    padding: 20px;
}

#edit-form h2 {
    margin-top: 0;
    color: #333;
}

#edit-form button {
    margin-top: 10px;
}

/* Utility */
.hidden {
    display: none;
}
   </style>
</head>
<body>
    <nav>
         <ul>
            <li type="none"><a href="index.html">Home</a></li>
            <li><a href="shownews.php">News</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Signup</a></li>
            <li><a href="insertnews.php">Add/Edit news</a></li>
            <li><a href="logout.php">Logout</a></li>
         </ul>            
    </nav>
    <section class="add-news">
        <h2>Add News</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content"  required></textarea>
            
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>

    <section class="news-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<article data-id='{$row['id']}'>";
                echo "<h3>" . $row["title"] . "</h3>";
                echo "<p>" . $row["content"] . "</p>";
                echo "<a href='?delete=" . $row["id"] . "' class='delete-link'>Delete</a>";
                echo "<br><a href='#' class='edit-link' onclick='openEditForm(" . $row["id"] . ")'>Edit</a>";
                echo "</article>";
            }
        } else {
            echo "No news found.";
        }
        ?>
    </section>

    <div id="edit-form" class="hidden">
        <h2>Edit News</h2>
        <form method="post" id="edit" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" id="update_id" name="update_id">  
            <label for="new_title">Title:</label>
            <input type="text" id="new_title" name="new_title" placeholder="do not enter old title here" required>
            
            <label for="new_content">Content:</label>
            <textarea id="new_content" name="new_content" rows="4" required></textarea>
            
            <button type="submit" name="update">Update</button>
            <button type="button" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <script>
    function openEditForm(id) {
        var editForm = document.getElementById("edit-form");
        editForm.classList.remove("hidden"); // Remove the "hidden" class

        document.getElementById("update_id").value = id;

        var title = document.querySelector("article[data-id='" + id + "'] h3").textContent;
        var content = document.querySelector("article[data-id='" + id + "'] p").textContent;
        document.getElementById("new_title").value = title;
        document.getElementById("new_content").value = content;
    }

    function closeEditForm() {
        var editForm = document.getElementById("edit-form");
        editForm.classList.add("hidden"); // Add the "hidden" class
    }
    </script>
</body>
</html>