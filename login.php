<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the username and password (add more secure validation)
    if ($username === "your_username" && $password === "your_password") {
        // Redirect to the main page or execute the existing code
        header("Location: main_page.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}
?>