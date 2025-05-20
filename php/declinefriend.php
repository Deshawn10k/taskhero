<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$request_id = $_GET['id'] ?? null;

if ($request_id) {
   
    $stmt = $conn->prepare("DELETE FROM friends WHERE id = ? AND friend_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $request_id, $user_id);
    $stmt->execute();

    header("Location: ../friendrequests.php?success=declined");
    exit;
} else {
    header("Location: ../friendrequests.php?error=invalid");
    exit;
}
