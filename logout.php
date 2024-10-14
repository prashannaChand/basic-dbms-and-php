<?php
session_start();
if (empty($_SESSION["login"])) {
    echo "Log in first.";
    exit();
}

$_SESSION = [];
session_unset();
session_destroy();
sleep(3);
echo"logged out successfullly <br> <a href='index.html'>click here to return lo home page</a>";
exit();
?>
