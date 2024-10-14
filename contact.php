<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contactdb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$submissionMessage = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert contact data into the "messages" table
    $sql = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$message')";
    if ($conn->query($sql) === TRUE) {
        $submissionMessage = "Message sent successfully.";
    } else {
        $submissionMessage = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <section class="contact">
        <div class="contact-content">
            <h2>Contact Us</h2>
            <?php if (!empty($submissionMessage)) : ?>
                <p><?php echo $submissionMessage; ?></p>
                <p><a href="index.html">Return to Home</a></p>
            <?php else : ?>
                <p>Have a story idea, a tip, or feedback? We'd love to hear from you!</p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="contact-form">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                    
                    <button type="submit" name="submit">Send Message</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>