<?php
if ($_FILES['audioUpload']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['audioUpload']['tmp_name'];
    $name = $_FILES['audioUpload']['name'];
    $destination = "uploads/$name";
    move_uploaded_file($tmp_name, $destination);

    // Database credentials
    $host = 'localhost';
    $port = '3306';
    $db   = 'limesurvey_audioplayerquestion';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';

    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);

    // Write the SQL query
    $sql = "INSERT INTO audio_uploads (question_id, audio_url) VALUES (?, ?)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Get the question ID from the form data
    $qid = $_POST['qid'];

    // Execute the SQL statement, passing the question ID and the audio URL as parameters
    $stmt->execute([$qid, $destination]);
}