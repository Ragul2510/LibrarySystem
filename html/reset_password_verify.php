<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['security_answer'], $_POST['new_password'], $_SESSION['correct_answer'])) {
    if ($_POST['security_answer'] === $_SESSION['correct_answer']) {
        $new_pass = hash('sha512', $_POST['new_password']);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_pass, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset Successful</title>
    <link rel='stylesheet' href='css/styles.css'>
</head>
<body>
    <div class='success-message'>
        <h1>Password Reset is successful</h1>
    </div>
</body>
</html>";
        } else {
            echo "<p>Error updating password: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>Incorrect answer to security question.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}

$conn->close();
?>
