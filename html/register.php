<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$questions = [];
$query = "SELECT id, question FROM security_questions";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    die('Failed to retrieve security questions: ' . $conn->error);
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['username']) && !empty($_POST['password'])) {
    $user = $_POST['username'];
    $pass = hash('sha512', $_POST['password']);

    $selected_questions = array($_POST['question1'], $_POST['question2']);
    if (count(array_unique($selected_questions)) !== 2) {
        $message = "All security questions must be unique.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $pass);
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            $allQuestionsProcessed = true;
            for ($i = 1; $i <= 2; $i++) {
                $question_id = $_POST['question' . $i];
                $answer = $_POST['answer' . $i];
                $stmt = $conn->prepare("INSERT INTO user_questions (user_id, question_id, answer) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $user_id, $question_id, $answer);
                if (!$stmt->execute()) {
                    $allQuestionsProcessed = false;
                    $message = "Error inserting security answers: " . $stmt->error;
                    break;
                }
            }
            if ($allQuestionsProcessed) {
                echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registration Successful</title>
    <link rel='stylesheet' href='css/styles.css'>
</head>
<body>
    <div class='success-message'>
        <h1>Registration is successful</h1>
    </div>
    <ul><li><a href='login.html'>Login Now</a></li></ul>
</body>
</html>";
                exit;
            }
        } else {
            $message = "Error registering user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();

if (!empty($message)) {
    echo "<p>$message</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Sign Up</h1>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <?php for ($i = 1; $i <= 2; $i++): ?>
            <label for="question<?= $i ?>">Security Question <?= $i ?>:</label>
            <select name="question<?= $i ?>" required>
                <?php foreach ($questions as $question): ?>
                    <option value="<?= $question['id'] ?>"><?= htmlspecialchars($question['question']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="answer<?= $i ?>" required placeholder="Answer"><br>
        <?php endfor; ?>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
