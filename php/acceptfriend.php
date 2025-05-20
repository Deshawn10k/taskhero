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
    
    $stmt = $conn->prepare("SELECT user_id, friend_id FROM friends WHERE id = ? AND friend_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $request_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        
        $stmt_update = $conn->prepare("UPDATE friends SET status = 'accepted' WHERE id = ?");
        $stmt_update->bind_param("i", $request_id);
        $stmt_update->execute();

        
        $stmt->bind_result($friend_user_id, $friend_friend_id);
        $stmt->fetch();

        $stmt_check = $conn->prepare("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?");
        $stmt_check->bind_param("ii", $user_id, $friend_user_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            $stmt_insert = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'accepted')");
            $stmt_insert->bind_param("ii", $user_id, $friend_user_id);
            $stmt_insert->execute();
        }

        header("Location: ../friendrequests.php?success=accepted");
        exit;
    } else {
        header("Location: ../friendrequests.php?error=notfound");
        exit;
    }
} else {
    header("Location: ../friendrequests.php?error=invalid");
    exit;
}
