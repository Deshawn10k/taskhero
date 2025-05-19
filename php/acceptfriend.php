<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$friendship_id = $_GET['id'] ?? null;

if ($friendship_id) {

    $stmt = $conn->prepare("UPDATE friends SET status = 'accepted' WHERE id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $friendship_id, $user_id);
    $stmt->execute();


    $stmt2 = $conn->prepare("SELECT user_id FROM friends WHERE id = ?");
    $stmt2->bind_param("i", $friendship_id);
    $stmt2->execute();
    $stmt2->bind_result($other_user_id);
    $stmt2->fetch();


    $stmt_check = $conn->prepare("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?");
    $stmt_check->bind_param("ii", $user_id, $other_user_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
        $stmt_insert = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'accepted')");
        $stmt_insert->bind_param("ii", $user_id, $other_user_id);
        $stmt_insert->execute();
    }

    header("Location: ../friends.php");
} else {
    echo "Geen vriend opgegeven.";
}
?>
