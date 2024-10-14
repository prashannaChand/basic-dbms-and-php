<?php 
include "connect1.php";
$errorMessage = ""; 

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Checking if username or email is already taken
    $sql = "SELECT * FROM user WHERE username ='$username' OR email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $errorMessage = "Query error: " . mysqli_error($conn);
    } else {
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            $errorMessage = "Username or Email Has Already Been Taken.";
        } else {
            if ($password === $confirmpassword) {
                // Hashing the password before storing it in the database for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO user (name, username, email, password)
                          VALUES ('$name', '$username', '$email', '$hashedPassword')";
                $insertResult = mysqli_query($conn, $query);

                if (!$insertResult) {
                    $errorMessage = "Registration error: " . mysqli_error($conn);
                } else {
                    // Setting cookies 
                    setcookie("Username", $username, time() + (86400 * 30), "/");
                    setcookie("Password", $password, time() + (86400 * 30), "/");
                    $errorMessage = "Registration successful. You can now log in.";
                }
            } else {
                $errorMessage = "Passwords do not match.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Sign Up</title>
</head>
<body>
    <form method="post" action="<?php echo ($_SERVER['PHP_SELF']); ?>">

        <br>
        Name: <input type="text" name="name"> <br> <br>
        User name: <input type="text" name="username"> <br> <br>
        Email: <input type="text" name="email"> <br> <br>
        Password: <input type="password" name="password"> <br> <br>
        Confirm password: <input type="password" name="confirmpassword"> <br> <br>
        <input type="submit" value="Register" name="submit" class="submit"> <br> <br>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <a href="login.php" class="login">Return to login!</a>
   <br> <a href="index.html" id="Home">Return to homepage!</a>

    </form>
    <br>
</body>
</html>