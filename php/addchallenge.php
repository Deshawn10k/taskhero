<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.html");
  exit;
}

$user_id = $_SESSION['user_id'];
$challenge_text = $_POST['challenge_text'];

$stmt = $conn->prepare("INSERT INTO challenges (user_id, challenge_text) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $challenge_text);
$stmt->execute();

header("Location: ../challenges.php");
?>
