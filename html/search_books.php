<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Please log in first.";
    exit();
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/styles - Copy - Copy.css">
    <style>
        #logoutButton {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #e20930;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 1000;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            line-height: normal;
        }

        #logoutButton:hover {
            background-color: #ff4769;
        }

        .book-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .book-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            width: 200px;
        }

        .book-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .book-item h4 {
            margin: 10px 0;
        }

        .book-item .button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #e20930;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .book-item .button:hover {
            background-color: #ff4769;
        }
    </style>
</head>
<body>';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['show_all'])) {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
} elseif (isset($_GET['search_term']) && trim($_GET['search_term']) !== '') {
    $search_term = $_GET['search_term'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE CONCAT('%', ?, '%') OR author LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    echo "Please enter a search term or click 'Show All Books'";
    $conn->close();
    exit();
}

echo '<div class="book-container">';
if (isset($result) && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="book-item">';
        echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="Book Image">';
        echo '<h4>' . htmlspecialchars($row["title"]) . ' | ' . htmlspecialchars($row["author"]) . '</h4>';
        echo '<a href="download.php?book_id=' . htmlspecialchars($row["id"]) . '" class="button">Download</a>';
        echo '</div>';
    }
} else {
    echo "No books found.";
}
echo '</div>';

$conn->close();

echo '</body>
<a href="logout.php" id="logoutButton">Logout</a>
</html>';
?>
