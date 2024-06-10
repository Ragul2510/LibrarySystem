<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Please log in first.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$book_id = $_GET['book_id'];
$sql = "SELECT file_path FROM books WHERE id='$book_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $file_path = $row['file_path'];

    if (file_exists($file_path)) {
        $basename = basename($file_path);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $basename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo "File not found. Checked path: " . $file_path;
    }
} else {
    echo "Book not found.";
}

$conn->close();
?>
