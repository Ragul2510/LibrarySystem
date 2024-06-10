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

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['new_password'])) {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT id, (SELECT answer FROM user_questions WHERE user_id = users.id ORDER BY RAND() LIMIT 1) as correct_answer FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $user_id = $user['id'];
        $correct_answer = $user['correct_answer'];
        $_SESSION['user_id'] = $user_id;
        $_SESSION['correct_answer'] = $correct_answer;

        if (isset($_POST['security_answer'])) {
            if ($_POST['security_answer'] === $_SESSION['correct_answer']) {
                $hashed_password = hash('sha512', $new_password);

                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
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
                    $message = "Error updating password: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                $message = "Incorrect answer to security question.";
            }
        } else {
            echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Security Question</title>
    <link rel='stylesheet' href='css/styles.css'>
</head>
<body>
    <h2>Answer Security Question</h2>
    <form action='' method='post'>
        <input type='hidden' name='username' value='" . htmlspecialchars($username) . "'>
        <label for='security_answer'>Security Question: What is your security answer?</label>
        <input type='text' id='security_answer' name='security_answer' required><br>
        <label for='new_password'>New Password:</label>
        <input type='password' id='new_password' name='new_password' required><br>
        <button type='submit'>Reset Password</button>
    </form>
</body>
</html>";
            exit;
        }
    } else {
        $message = "Username not found.";
    }
    $stmt->close();
}

$conn->close();

if (!empty($message)) {
    echo "<p>$message</p>";
}
?>
