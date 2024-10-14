<?php
include "connect1.php";

if (!empty($_SESSION["login"])) {
    echo "Already logged in.";
    exit();
}

$errorMessage = "";
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $row    = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row["password"])) {
        echo "Password verified!";
        $_SESSION["login"] = true;
        $_SESSION["id"]    = $row["id"];
        header("Location: index.html");
        exit();
    } else {
       $errorMessage = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
    <form method="POST" action="<?php echo ($_SERVER['PHP_SELF']); ?>">
        <table>
            <tr>
                <th>Username</th>
                <td><input type="text" name="username" placeholder="Username" value="<?php if(isset($_COOKIE['Username'])) echo ($_COOKIE['Username']); ?>"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" placeholder="Password" value="<?php if(isset($_COOKIE['Password'])) echo ($_COOKIE['Password']); ?>"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Login" name="submit" class="submit">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="signup.php" class="reg">New Registration</a>
                </td>
            </tr>
            <?php if(isset($errorMessage)): ?>
            <tr>
                <td colspan="2" class="error-message"><?php echo $errorMessage; ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </form>
</body>
</html>
