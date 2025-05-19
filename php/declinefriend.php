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
    $stmt = $conn->prepare("DELETE FROM friends WHERE id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $friendship_id, $user_id);
    $stmt->execute();
    header("Location: ../friends.php");
} else {
    echo "Geen vriend opgegeven.";
}
?>
