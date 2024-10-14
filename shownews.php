<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local News</title>
    <link rel="stylesheet" href="shownews.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Signup</a></li>
            <li><a href="insertnews.php">Add/Edit news</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "newsdb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch and display news in random order
    $sql = "SELECT id, title, content FROM news ORDER BY RAND()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Latest Local News</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<article>";
            echo "<h3>" . $row["title"] . "</h3>";
            echo "<p>" . $row["content"] . "</p>";
            echo "</article>";
        }
    } else {
        echo "No local news found.";
    }

    $conn->close();
    ?>
</body>
</html>
