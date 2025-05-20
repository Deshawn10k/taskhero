<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $title = trim($_POST['title']);

  if (!empty($title)) {
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("is", $user_id, $title);
    if ($stmt->execute()) {
      header("Location: ../tasks.php?success=1");
      exit;
    } else {
      header("Location: ../tasks.php?error=1");
      exit;
    }
  } else {
    header("Location: ../tasks.php?error=1");
    exit;
  }
} else {
  header("Location: ../tasks.php");
  exit;
}
?>
