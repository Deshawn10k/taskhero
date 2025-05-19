<?php
session_start();
require 'php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT xp, level FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($xp, $level);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
</head>
<body>
    <h1>Welkom bij TaskHero, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Je hebt <?php echo (int)$xp; ?> XP en bent level <?php echo (int)$level; ?>.</p>

    <a href="php/tasks.php">Ga naar je taken</a> | <a href="php/logout.php">Uitloggen</a>
</body>
</html>
