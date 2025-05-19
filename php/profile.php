<?php
session_start();
require 'db.php';

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


$stmt = $conn->prepare("SELECT name, description FROM badges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$badges = $stmt->get_result();
$stmt->close();


$stmt = $conn->prepare("SELECT title, status FROM challenges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$challenges = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Profiel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4 rounded-4 border-0">
        <h2 class="text-primary fw-bold mb-3">Profiel van <?= htmlspecialchars($_SESSION['username']) ?></h2>
        <p><strong>XP:</strong> <?= $xp ?></p>
        <p><strong>Level:</strong> <?= $level ?></p>

        <hr>

        <h4 class="text-success mb-3">Badges</h4>
        <?php if ($badges->num_rows > 0): ?>
            <ul class="list-group mb-4">
                <?php while ($badge = $badges->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($badge['name']) ?></strong>: <?= htmlspecialchars($badge['description']) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Je hebt nog geen badges verdiend.</p>
        <?php endif; ?>

        <h4 class="text-warning mt-4 mb-3">Uitdagingen</h4>
        <?php if ($challenges->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($challenge = $challenges->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($challenge['title']) ?>
                        <span class="badge bg-<?= $challenge['status'] == 'completed' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($challenge['status']) ?>
                        </span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Je hebt nog geen uitdagingen gestart.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-outline-primary mt-4">Terug naar dashboard</a>
    </div>
</div>
</body>
</html>
