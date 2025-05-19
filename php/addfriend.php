<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$friend_username = $_POST['friend_username'];


$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $friend_username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($friend_id);
    $stmt->fetch();


    $stmt_check = $conn->prepare("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?");
    $stmt_check->bind_param("ii", $user_id, $friend_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
  
        $stmt_insert = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $user_id, $friend_id);
        $stmt_insert->execute();
        echo "Vriendschapsverzoek verstuurd.";
    } else {
        echo "Vriendschap bestaat al of verzoek is al verzonden.";
    }
} else {
    echo "Gebruiker niet gevonden.";
}
?>
<br><a href="../friends.php">Terug</a>
