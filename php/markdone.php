<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: tasks.php?error=missing_id");
    exit;
}

$task_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("UPDATE tasks SET status = 'done' WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$stmt->close();


$xpToAdd = 10;


$stmt = $conn->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
$stmt->bind_param("ii", $xpToAdd, $user_id);
$stmt->execute();
$stmt->close();

header("Location: tasks.php");
exit;
