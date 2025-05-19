<?php
session_start();
require 'php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? null;

if ($task_id) {

    $stmt = $conn->prepare("UPDATE tasks SET completed = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE users SET xp = xp + 10 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

   
    $stmt = $conn->prepare("SELECT xp, level FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($xp, $level);
    $stmt->fetch();
    $new_level = floor($xp / 100) + 1;
    if ($new_level > $level) {
        $stmt = $conn->prepare("UPDATE users SET level = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_level, $user_id);
        $stmt->execute();
    }

    header("Location: tasks.php");
} else {
    echo "Geen taak opgegeven.";
}
